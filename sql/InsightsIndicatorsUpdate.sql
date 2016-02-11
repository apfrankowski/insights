DELIMITER $$
CREATE DEFINER=`symulator`@`%` PROCEDURE `InsightsIndicatorsUpdate`( IN in_id_szpital INT(10) unsigned, IN id_data_od int(7), IN id_data_do int(7) )
BEGIN

	DROP TABLE IF EXISTS afr_osobodni_azl;
	CALL PoliczOsobodniAZLPlanowe_AFR( in_id_szpital, id_data_od-100, id_data_do );
	
	DROP TABLE IF EXISTS afr_indicators;
	CREATE TABLE afr_indicators (
		id integer auto_increment primary key,
		id_szpital integer,
		id_oddzial integer,
		id_data integer,
		id_specjalizacja integer,
		occupancy decimal(5,2),
		occupancy_ly decimal(5,2),
		occupancy_benchmark decimal (5,2),
		exceeded_occupancy decimal (5,2),
		exceeded_occupancy_ly decimal(5,2),
		alos decimal(5,1),
		alos_ly decimal(5,1),
		alos_ben decimal(5,1),
		planned_admission_pop decimal(5,2),
		planned_admission_pop_ben decimal(5,2),
		emergency_admission_pop decimal(5,2),
		emergency_admission_pop_ben decimal(5,2),
		occupancy_stddev decimal (5,2),
		contract_fulfillment decimal(5,2),
		max_daily_occupancy decimal(5,2),
		avg_thu_occupancy decimal(5,2),
		avg_fri_occupancy decimal(5,2)
	);
	
	INSERT INTO afr_indicators (
		id_szpital, 
		id_oddzial, 
		id_data, 
		id_specjalizacja
	) SELECT 
		o.id_szpital, 
		azl.id_oddzial, 
		id_data_do, 
		id_specjalizacja 
	FROM afr_osobodni_azl azl 
	JOIN oddzial o ON o.id=azl.id_oddzial 
	GROUP BY id_oddzial;

	DROP TEMPORARY TABLE IF EXISTS afr_dp_count;
	CREATE TEMPORARY TABLE afr_dp_count AS (
		SELECT
			p.id_szpital, 
			p.id_oddzial, 
			p.id_diagproc, 
			o.id_specjalizacja, 
			count(*) AS dp_count 
		FROM pobyt p 
		JOIN oddzial o ON o.id=p.id_oddzial 
		WHERE id_data BETWEEN id_data_od AND id_data_do 
			AND p.id_szpital = in_id_szpital 
		GROUP BY p.id_oddzial, p.id_diagproc
	);

	#oblożenie
	UPDATE afr_indicators afr INNER JOIN (
		select id_oddzial, (sum(il_osobodni) + sum(il_przyjec) - sum(il_jednodniowych)) / sum(liczba_lozek) as occupancy 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.occupancy = rel.occupancy;

	#oblożenie_ly
	update afr_indicators afr inner join (
		select id_oddzial, (sum(il_osobodni) + sum(il_przyjec) - sum(il_jednodniowych)) / sum(liczba_lozek) as occupancy_ly 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.occupancy_ly = rel.occupancy_ly;

	#oblozenie ben
	update afr_indicators afr inner join (
		select azl.id_oddzial, value as occupancy_benchmark 
		from afr_osobodni_azl azl 
		join oddzial o on o.id=azl.id_oddzial 
		join l_grupa_szpitali_szpital lgss on lgss.id_szpital=o.id_szpital 
		join benchmark b on b.id_specjalizacja=o.id_specjalizacja and b.id_grupa_szpitali=lgss.id_grupa_szpitali 
		where id_benchmark_dimensions = 'oblozenie.9' and b.id_data_od=201401 and b.id_data_do=201506 
		group by azl.id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.occupancy_benchmark = rel.occupancy_benchmark;

	#oblozenie ponad 100% => udzial
	update afr_indicators afr inner join (
		select id_oddzial, 
			SUM( IF(((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek)>1, 1, 0  ) )/count(dzien) as exceeded_occupancy 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.exceeded_occupancy = rel.exceeded_occupancy;

	#oblozenie ponad 100% => udzial LY
	update afr_indicators afr inner join 
		(select id_oddzial, 
			SUM( IF(((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek)>1, 1, 0  ) )/count(dzien) as exceeded_occupancy_ly 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.exceeded_occupancy_ly = rel.exceeded_occupancy_ly;

	#alos
	update afr_indicators afr inner join 
		(select id_oddzial, 
			(sum(il_osobodni_wypisowych)/sum(il_wypisow)) as alos 
		from afr_osobodni_azl azl 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.alos = rel.alos;

	#alos LY
	update afr_indicators afr inner join 
		(select id_oddzial, 
			(sum(il_osobodni_wypisowych)/sum(il_wypisow)) as alos_ly 
		from afr_osobodni_azl azl 
		where id_data between id_data_od-100 AND id_data_do-100 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.alos_ly = rel.alos_ly;

	#alos ben
	update afr_indicators afr inner join 
		(select sum(afr.dp_count*b.value)/sum(afr.dp_count) as alos_ben, afr.id_oddzial as id_oddzial 
		from afr_dp_count afr 
		left join benchmark b using (id_diagproc, id_specjalizacja) 
		where b.id_data_od=201401 and b.id_data_do=201506 
			and b.id_benchmark_dimensions='dlugosc_pobytu.41' 
			and b.id_statistical_measure='AVG' 
			and b.id_grupa_szpitali=2 
		group by afr.id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.alos_ben = rel.alos_ben;

	#udzial planowe
	update afr_indicators afr inner join 
		(select id_oddzial, sum(il_przyjec_planowych)/sum(il_przyjec) as planned_admission_pop 
		from afr_osobodni_azl 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.planned_admission_pop = rel.planned_admission_pop;

	#udzial_planow_ben
	update afr_indicators afr inner join 
		(select id_specjalizacja, sum(il_przyjec_planowych)/sum(il_przyjec) as planned_admission_pop_ben 
		from afr_osobodni_azl azl 
		join oddzial o on o.id=azl.id_oddzial 
		where id_data between id_data_od AND id_data_do 
		group by o.id_specjalizacja) rel
	on rel.id_specjalizacja = afr.id_specjalizacja 
	set afr.planned_admission_pop_ben = rel.planned_admission_pop_ben;

	#udzial ostre
	update afr_indicators afr inner join 
		(select id_oddzial, sum(il_przyjec_pozostalych)/sum(il_przyjec) as emergency_admission_pop 
		from afr_osobodni_azl 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.emergency_admission_pop = rel.emergency_admission_pop;

	#udzial_ostre_ben
	update afr_indicators afr inner join 
		(select id_specjalizacja, sum(il_przyjec_pozostalych)/sum(il_przyjec) as emergency_admission_pop_ben 
		from afr_osobodni_azl azl 
		join oddzial o on o.id=azl.id_oddzial 
		where id_data between id_data_od AND id_data_do 
		group by o.id_specjalizacja) rel
	on rel.id_specjalizacja = afr.id_specjalizacja 
	set afr.emergency_admission_pop_ben = rel.emergency_admission_pop_ben;

	#occ stddev
	update afr_indicators afr inner join 
		(select id_oddzial, std((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek) as occupancy_stddev 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.occupancy_stddev = rel.occupancy_stddev;

	#contract fulfillment
	update afr_indicators afr inner join 
		(select id_oddzial, sum(realizacja_produktu_narastajaco)/sum(limit_produktu_narastajaco) as contract_fulfillment 
		from miesieczne_wykonania_produktow mwp 
		join afr_osobodni_azl azl using(id_oddzial) 
		where mwp.id_data=id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.contract_fulfillment = rel.contract_fulfillment;

	#max daily occ
	update afr_indicators afr inner join 
		(select id_oddzial, max((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek) as max_daily_occupancy 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.max_daily_occupancy = rel.max_daily_occupancy;

	#avg thursday occ
	update afr_indicators afr inner join 
		(select id_oddzial, avg((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek) as avg_thu_occupancy 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do and weekday(dzien) = 3 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.avg_thu_occupancy = rel.avg_thu_occupancy;

	#avg friday occ
	update afr_indicators afr inner join 
		(select id_oddzial, avg((il_osobodni+il_przyjec-il_jednodniowych)/liczba_lozek) as avg_fri_occupancy 
		from afr_osobodni_azl azl 
		join oddzial_informacje oi using (id_oddzial, id_data) 
		where id_data between id_data_od AND id_data_do and weekday(dzien) = 4 
		group by id_oddzial) rel
	on rel.id_oddzial = afr.id_oddzial 
	set afr.avg_fri_occupancy = rel.avg_fri_occupancy;

	DROP TABLE IF EXISTS afr_osobodni_azl;

END$$
DELIMITER ;
