DELIMITER $$
CREATE DEFINER=`symulator`@`%` PROCEDURE `PoliczOsobodniAZLPlanowe_AFR`( IN in_id_szpital INT(10) unsigned, IN id_data_od int(7), IN id_data_do int(7) )
BEGIN

   DECLARE done INT DEFAULT FALSE;
    DECLARE v_data_przyjecia, v_data_wypisu, v_data_wypisu_ov, v_date_idx DATE;
    DECLARE v_pomin_przyjecie, v_pomin_wypis, v_nie_ma_przyjecia, v_tryb_przyjecia TINYINT(1);
    DECLARE v_id_oddzial, v_id_szpital, v_id_hospitalizacja, v_dni, v_id_data, v_czy_szpitalne, v_id_pobyt, v_il_przepustek, v_id_oddzial_azl INT(10) UNSIGNED;
    DECLARE v_id_diagproc, v_stan_czesci VARCHAR(20);
    DECLARE v_nr_ks_glownej VARCHAR(45);
    DECLARE cur_pobyt CURSOR FOR 
        SELECT 
            p.id AS id_pobyt, 
            p.id_hospitalizacja,
            p.id_diagproc,
            MIN(p.data_przyjecia) AS data_przyjecia, 
            MAX(p.data_wypisu) AS data_wypisu, 
            p.id_oddzial, 
            p.id_szpital, 
            SUM(p.dni_leczenia) AS dni,
            'N' AS stan_czesci,
            IF(p.nr_ks_glownej LIKE '%-99', 0, 1) czy_szpitalne,
			-- 1 czy_szpitalne,
            p.nr_ks_glownej,
			hosp.id_tryb_przyjecia
        FROM pobyt p
			JOIN hospitalizacja hosp ON p.id_hospitalizacja=hosp.id
			JOIN oddzial o ON p.id_oddzial = o.id
			JOIN specjalizacja spc ON o.id_specjalizacja = spc.id
        WHERE p.id_szpital=in_id_szpital AND spc.czy_dzielona = 0 AND p.data_wypisu IS NOT NULL AND ((p.id_data BETWEEN id_data_od AND id_data_do) or 
				 (DATE_FORMAT(p.data_przyjecia,'%Y%m') <= id_data_do and DATE_FORMAT(p.data_wypisu,'%Y%m') > id_data_do))
        GROUP BY p.id
	UNION
		SELECT 
            pobyt.id AS id_pobyt, 
            pobyt.id_hospitalizacja,
            pobyt.id_diagproc,
            MIN(shwp.data_przyjecia) AS data_przyjecia, 
            MAX(shwp.data_wypisu) AS data_wypisu, 
            shwp.id_oddzial, 
            shwp.id_szpital, 
            IF(polozenie_czesci = 'N', DATEDIFF(MAX(shwp.data_wypisu),MIN(shwp.data_przyjecia)), SUM(shwp.dni_leczenia)) AS dni,
            GROUP_CONCAT(DISTINCT polozenie_czesci ORDER BY polozenie_czesci) AS stan_czesci,
            IF(shwp.nr_ks_glownej LIKE '%-99', 0, 1) czy_szpitalne,
			-- 1 czy_szpitalne,
            shwp.nr_ks_glownej,
			hosp.id_tryb_przyjecia
        FROM pobyt 
            JOIN schemat_wczytania_pobytow shwp ON pobyt.id=shwp.id_pobyt
			JOIN hospitalizacja hosp ON pobyt.id_hospitalizacja=hosp.id
			JOIN oddzial o ON pobyt.id_oddzial = o.id
			JOIN specjalizacja spc ON o.id_specjalizacja = spc.id
        WHERE shwp.id_szpital=in_id_szpital AND spc.czy_dzielona = 1 AND pobyt.data_wypisu IS NOT NULL AND ((shwp.id_data BETWEEN id_data_od AND id_data_do) or 
				 (DATE_FORMAT(pobyt.data_przyjecia,'%Y%m') <= id_data_do and DATE_FORMAT(pobyt.data_wypisu,'%Y%m') > id_data_do))
        GROUP BY pobyt.id
	ORDER BY data_przyjecia ASC
;

    DECLARE cur_minmax CURSOR FOR 
        SELECT 
            MIN(shwp.data_przyjecia) AS min_data_przyjecia, 
            MAX(shwp.data_wypisu) AS max_data_wypisu, 
            shwp.id_oddzial, 
            shwp.id_szpital 
        FROM pobyt JOIN schemat_wczytania_pobytow shwp ON pobyt.id=shwp.id_pobyt 
        WHERE shwp.id_szpital=in_id_szpital AND pobyt.data_wypisu IS NOT NULL AND ((shwp.id_data BETWEEN id_data_od AND id_data_do) or
			  (DATE_FORMAT(pobyt.data_przyjecia,'%Y%m') <= id_data_do and DATE_FORMAT(pobyt.data_wypisu,'%Y%m') > id_data_do))
        GROUP BY shwp.id_oddzial, shwp.id_szpital
	UNION
		SELECT 
			MIN(p.data_przyjecia) AS min_data_przyjecia, 
			MAX(p.data_wypisu) AS max_data_wypisu, 
			p.id_oddzial, 
			p.id_szpital 
		FROM pobyt p
		JOIN oddzial o ON p.id_oddzial = o.id
		JOIN specjalizacja spc ON o.id_specjalizacja = spc.id
		WHERE p.id_szpital=in_id_szpital AND p.data_wypisu IS NOT NULL AND ((p.id_data BETWEEN id_data_od AND id_data_do) or (DATE_FORMAT(p.data_przyjecia,'%Y%m') <= id_data_od and DATE_FORMAT(p.data_wypisu,'%Y%m') > id_data_do)) AND spc.czy_dzielona = 0
		GROUP BY 
			p.id_oddzial, p.id_szpital
	;
  
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    
    CREATE TABLE IF NOT EXISTS afr_osobodni_azl (                                                                                                                                                                                                               
        `id` int(11) NOT NULL AUTO_INCREMENT,                                                                                                                                                                                                              
        `id_oddzial` int(10) UNSIGNED DEFAULT NULL,                                                                                                                                                                                                                 
        `id_szpital` int(10) UNSIGNED DEFAULT NULL,                                                                                                                                                                                                                 
        `dzien` date DEFAULT NULL,                                                                                                                                                                                                                         
        `id_data` int(7) DEFAULT NULL,                                                                                                                                                                                                                     
        `il_osobodni` int(3) DEFAULT NULL,                                                                                                                                                                                                                 
        `il_wypisow` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_przyjec` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_jednodniowych` int(3) DEFAULT NULL,                                                                                                                                                                                                            
        `il_osobodni_wypisowych` int(10) DEFAULT NULL,      
        `il_osobodni_wypisowych_planowe` int(10) DEFAULT NULL,      
        `il_osobodni_wypisowych_pozostale` int(10) DEFAULT NULL,      
        `il_osobodni_wypisowych_z_dlugimi` int(10) DEFAULT NULL,
        `il_ambulatoryjnych` int(3) DEFAULT NULL,
        `il_przyjec_planowych` int(3) DEFAULT NULL,  
		`il_przyjec_pozostalych` int(3) DEFAULT NULL,																																																	
        `il_osobodni_przyjec_planowych` int(10) DEFAULT NULL,  
		`il_osobodni_przyjec_pozostalych` int(10) DEFAULT NULL,																																																	
        `il_wypisow_z_przyjec_planowych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_wypisow_z_przyjec_pozostalych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_jednodniowych_z_przyjec_planowych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_jednodniowych_z_przyjec_pozostalych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_ambulatoryjnych_planowych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        `il_ambulatoryjnych_pozostalych` int(3) DEFAULT NULL,                                                                                                                                                                                                                  
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_id_oddzial_data` (`id_oddzial`,`dzien`) USING BTREE,
        KEY `idx_dzien` (`dzien`) USING BTREE
    ) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci ENGINE=MYISAM; 

    DROP TEMPORARY TABLE IF EXISTS tmp_pobyty_azl;
    CREATE TEMPORARY TABLE tmp_pobyty_azl (                                                                                                                                                                                                               
        `id` int(11) NOT NULL AUTO_INCREMENT,                                                                                                                                                                                                              
        `id_oddzial` int(10) UNSIGNED DEFAULT NULL,                                                                                                                                                                                                                 
        `id_szpital` int(10) UNSIGNED DEFAULT NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                
        nr_ks_glownej VARCHAR(45),
        data_przyjecia DATE,
        data_wypisu DATE,
		tryb_przyjecia INT(3),
        PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci ENGINE=MYISAM; 

    DROP TEMPORARY TABLE IF EXISTS tmp_oddzialy_azl;
    CREATE TEMPORARY TABLE `tmp_oddzialy_azl` (
        `id` int(10) unsigned NOT NULL DEFAULT '0',
        `id_szpital` int(10) unsigned NOT NULL,
        `id_specjalizacja` varchar(4) COLLATE utf8_polish_ci NOT NULL,
        `kod_swd` varchar(20) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
        `nazwa_skr` varchar(64) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
        `nazwa` varchar(128) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
        `aktywny` int(1) NOT NULL DEFAULT '1',
        `il_danych` int(1) NOT NULL DEFAULT '1'
    ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

    INSERT INTO tmp_oddzialy_azl (
        `id`,`id_szpital`,`id_specjalizacja`,
        `kod_swd`,`nazwa_skr`,
        `nazwa`,`aktywny`,
        `il_danych` )
    SELECT DISTINCT
        o.id, o.id_szpital, o.id_specjalizacja, 
        o.kod_swd, o.nazwa_skr, o.nazwa, 
        1 AS aktywny, 1 AS il_danych 
    FROM 
        oddzial o JOIN oddzialy_w_raporcie ON o.id=id_oddzial
    WHERE oddzialy_w_raporcie.azl != 0 AND 
        o.id_szpital = in_id_szpital AND
        id_data BETWEEN id_data_od AND id_data_do;

    OPEN cur_minmax;
    
    initializa_loop: LOOP
        SET done = FALSE;
        FETCH cur_minmax INTO v_data_przyjecia, v_data_wypisu, v_id_oddzial, v_id_szpital;
        IF done THEN
              LEAVE initializa_loop;
        END IF;  
        
        SELECT id INTO v_id_oddzial_azl FROM tmp_oddzialy_azl WHERE id=v_id_oddzial;
        IF done THEN 
            ITERATE initializa_loop;
        END IF;

        SET v_date_idx = CONCAT(SUBSTR(id_data_od,1,4),'-',SUBSTR(id_data_od,5,2),'-01');
        SET v_data_wypisu = LAST_DAY(CONCAT(SUBSTR(id_data_do,1,4),'-',SUBSTR(id_data_do,5,2),'-01'));

        insert_loop: LOOP
                IF DATEDIFF(v_data_wypisu, v_date_idx) < 0 THEN
                    LEAVE insert_loop;
                END IF;               
                
                IF MONTH(v_date_idx) > 9 THEN
                    SET v_id_data = CONCAT(YEAR(v_date_idx),MONTH(v_date_idx));
                ELSE 
                    SET v_id_data = CONCAT(YEAR(v_date_idx),'0',MONTH(v_date_idx));
                END IF;
                
                INSERT IGNORE INTO afr_osobodni_azl (id_oddzial, id_szpital, dzien, id_data, il_osobodni, il_wypisow, il_przyjec, il_jednodniowych, il_osobodni_wypisowych, il_osobodni_wypisowych_planowe, il_osobodni_wypisowych_pozostale, il_ambulatoryjnych, il_przyjec_planowych, il_przyjec_pozostalych, il_osobodni_przyjec_planowych, il_osobodni_przyjec_pozostalych, il_osobodni_wypisowych_z_dlugimi, il_wypisow_z_przyjec_planowych, il_wypisow_z_przyjec_pozostalych, il_jednodniowych_z_przyjec_planowych, il_jednodniowych_z_przyjec_pozostalych, il_ambulatoryjnych_planowych, il_ambulatoryjnych_pozostalych) 
					VALUES (v_id_oddzial,v_id_szpital,v_date_idx,v_id_data,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                
                SET v_date_idx = DATE_ADD(v_date_idx,INTERVAL 1 DAY);
        END LOOP; 

    END LOOP; 
    CLOSE cur_minmax;
    
    DROP TEMPORARY TABLE IF EXISTS tmp_przepustki;
    CREATE TEMPORARY TABLE tmp_przepustki ENGINE=MEMORY AS
        SELECT 
			p.id_oddzial, 
			swiadczenie.id_pobyt, 
			swiadczenie.krotnosc AS ilosc, 
			swiadczenie.data_rozpoczecia AS data_rozpoczecia, 
			swiadczenie.data_zakonczenia AS data_zakonczenia
		FROM katalog_swiadczen 
		JOIN swiadczenie ON id_katalog_swiadczen=katalog_swiadczen.id 
		JOIN pobyt p ON swiadczenie.id_pobyt = p.id
		JOIN oddzial o ON p.id_oddzial = o.id
		JOIN specjalizacja s ON o.id_specjalizacja = s.id
		WHERE  
			UPPER(katalog_swiadczen.nazwa) LIKE '%PRZEPUSTKA%' AND p.id_szpital=in_id_szpital AND p.id_data BETWEEN id_data_od AND id_data_do AND s.czy_dzielona = 0
	UNION
		SELECT 
			shws.id_oddzial, 
			shws.id_pobyt, 
			shws.krotnosc2 AS ilosc, 
			shws.data_rozpoczecia2 AS data_rozpoczecia, 
			shws.data_zakonczenia2 AS data_zakonczenia
        FROM katalog_swiadczen 
        JOIN swiadczenie ON id_katalog_swiadczen=katalog_swiadczen.id 
		JOIN schemat_wczytania_swiadczen shws ON shws.id_swiadczenie=swiadczenie.id
		JOIN oddzial o ON shws.id_oddzial = o.id
		JOIN specjalizacja s ON o.id_specjalizacja = s.id
        WHERE  
			UPPER(katalog_swiadczen.nazwa) LIKE '%PRZEPUSTKA%' AND shws.id_szpital=in_id_szpital AND shws.id_data BETWEEN id_data_od AND id_data_do AND s.czy_dzielona = 1;
        
    CREATE INDEX idx_dr ON tmp_przepustki (data_rozpoczecia); 
    CREATE INDEX idx_dz ON tmp_przepustki (data_zakonczenia);
    CREATE INDEX idx_id_pobyt ON tmp_przepustki (id_pobyt);
    CREATE INDEX idx_id_oddzial ON tmp_przepustki (id_oddzial);
    
    OPEN cur_pobyt;
    
    pobyt_loop: LOOP
        SET done = FALSE;
        FETCH cur_pobyt INTO v_id_pobyt, v_id_hospitalizacja, v_id_diagproc, v_data_przyjecia, v_data_wypisu, v_id_oddzial, v_id_szpital, v_dni, v_stan_czesci, v_czy_szpitalne, v_nr_ks_glownej, v_tryb_przyjecia;
        IF done THEN
              LEAVE pobyt_loop;
        END IF;

		IF(v_dni>2000) THEN
			SELECT v_id_pobyt, v_id_hospitalizacja, v_id_diagproc, v_data_przyjecia, v_data_wypisu, v_id_oddzial, v_id_szpital, v_dni, v_stan_czesci, v_czy_szpitalne, v_nr_ks_glownej;
		END IF;

        SELECT id INTO v_id_oddzial_azl FROM tmp_oddzialy_azl WHERE id=v_id_oddzial;
        IF done THEN 
            ITERATE pobyt_loop;
        END IF;

        SET v_pomin_przyjecie = 0;
        SET v_pomin_wypis = 0;

        SELECT 
            IF(MIN(data_przyjecia)<=v_data_przyjecia, 1, 0), 
            IF(v_data_wypisu<=MAX(data_wypisu), 1, 0),
            MAX(data_wypisu)
        INTO v_pomin_przyjecie, v_pomin_wypis, v_data_wypisu_ov
        FROM pobyt 
        WHERE id_hospitalizacja=v_id_hospitalizacja AND SUBSTR(id_diagproc,1,3)=SUBSTR(v_id_diagproc,1,3) AND id!=v_id_pobyt AND id_oddzial=v_id_oddzial;

        INSERT INTO tmp_pobyty_azl (id_oddzial,id_szpital,nr_ks_glownej,data_przyjecia,data_wypisu,tryb_przyjecia) VALUES (v_id_oddzial,v_id_szpital,v_nr_ks_glownej,v_data_przyjecia,v_data_wypisu,v_tryb_przyjecia);

        SELECT SUM(ilosc) FROM tmp_przepustki WHERE id_pobyt=v_id_pobyt INTO v_il_przepustek;
        
        UPDATE afr_osobodni_azl os JOIN 
                tmp_przepustki tp ON (tp.id_oddzial=os.id_oddzial AND dzien BETWEEN data_rozpoczecia AND  ADDDATE(data_rozpoczecia, INTERVAL ilosc-1 DAY)) AND id_pobyt=v_id_pobyt
            SET il_osobodni=il_osobodni-1;

		IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
			UPDATE afr_osobodni_azl os JOIN 
					tmp_przepustki tp ON (tp.id_oddzial=os.id_oddzial AND dzien BETWEEN data_rozpoczecia AND  ADDDATE(data_rozpoczecia, INTERVAL ilosc-1 DAY)) AND id_pobyt=v_id_pobyt
				SET il_osobodni_przyjec_planowych=il_osobodni_przyjec_planowych-1;
		  ELSE
			UPDATE afr_osobodni_azl os JOIN 
					tmp_przepustki tp ON (tp.id_oddzial=os.id_oddzial AND dzien BETWEEN data_rozpoczecia AND  ADDDATE(data_rozpoczecia, INTERVAL ilosc-1 DAY)) AND id_pobyt=v_id_pobyt
				SET il_osobodni_przyjec_pozostalych=il_osobodni_przyjec_pozostalych-1;
		END IF;


        SET v_il_przepustek=0;

        IF v_czy_szpitalne=1 THEN
            
            IF( v_pomin_wypis = 0 ) THEN 
                UPDATE afr_osobodni_azl SET il_wypisow=il_wypisow+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;
       --     ELSE  																												-- zmiana jew, w razie czego odremowac
         --       UPDATE afr_osobodni_azl SET il_osobodni=il_osobodni+1 WHERE dzien=v_data_wypisu_ov AND id_oddzial=v_id_oddzial;	-- zmiana jew, w razie czego odremowac

				IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
					UPDATE afr_osobodni_azl SET il_wypisow_z_przyjec_planowych=il_wypisow_z_przyjec_planowych+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;
				  ELSE
					UPDATE afr_osobodni_azl SET il_wypisow_z_przyjec_pozostalych=il_wypisow_z_przyjec_pozostalych+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;
				END IF;
            END IF;

            IF( v_pomin_przyjecie = 0 ) THEN 
                UPDATE afr_osobodni_azl SET il_przyjec=il_przyjec+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;

				IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
					UPDATE afr_osobodni_azl SET il_przyjec_planowych=il_przyjec_planowych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
				  ELSE
					UPDATE afr_osobodni_azl SET il_przyjec_pozostalych=il_przyjec_pozostalych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
				END IF;
            END IF;

            UPDATE afr_osobodni_azl SET il_osobodni=il_osobodni+1 WHERE dzien>v_data_przyjecia AND dzien<v_data_wypisu AND id_oddzial=v_id_oddzial;

			IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
				UPDATE afr_osobodni_azl SET il_osobodni_przyjec_planowych=il_osobodni_przyjec_planowych+1 WHERE dzien>v_data_przyjecia AND dzien<v_data_wypisu AND id_oddzial=v_id_oddzial;
			  ELSE
				UPDATE afr_osobodni_azl SET il_osobodni_przyjec_pozostalych=il_osobodni_przyjec_pozostalych+1 WHERE dzien>v_data_przyjecia AND dzien<v_data_wypisu AND id_oddzial=v_id_oddzial;
			END IF;


            
            
            IF ( DATEDIFF(v_data_przyjecia, v_data_wypisu)=0 AND v_pomin_wypis = 0 AND v_pomin_przyjecie = 0 ) THEN 
                UPDATE afr_osobodni_azl SET il_jednodniowych=il_jednodniowych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
				IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
					UPDATE afr_osobodni_azl SET il_jednodniowych_z_przyjec_planowych=il_jednodniowych_z_przyjec_planowych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
				  ELSE
					UPDATE afr_osobodni_azl SET il_jednodniowych_z_przyjec_pozostalych=il_jednodniowych_z_przyjec_pozostalych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
				END IF;
            END IF;
            
            UPDATE afr_osobodni_azl SET il_osobodni_wypisowych_z_dlugimi=il_osobodni_wypisowych_z_dlugimi+v_dni-v_il_przepustek WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial ;
            
            IF( v_stan_czesci != 'N' ) THEN 
				BEGIN
					DECLARE v_data_wypisu_dlugo DATE;
					DECLARE v_dni_leczenia_dlugo INT;
					DECLARE cur_dlugie_stop TINYINT(1);
					DECLARE cur_dlugie CURSOR FOR
						SELECT shwp.data_wypisu, p.dni_leczenia 
							FROM schemat_wczytania_pobytow shwp 
							JOIN pobyt p ON p.id=shwp.id_pobyt
							WHERE id_pobyt=v_id_pobyt AND shwp.id_data BETWEEN id_data_od AND id_data_do AND polozenie_czesci NOT IN ('P','S');
					
					DECLARE CONTINUE HANDLER FOR NOT FOUND SET cur_dlugie_stop = TRUE;
					
					OPEN cur_dlugie;
					cur_dlugie_loop: LOOP
						SET cur_dlugie_stop = FALSE;
						
						FETCH cur_dlugie INTO v_data_wypisu_dlugo, v_dni_leczenia_dlugo;
						IF(cur_dlugie_stop) THEN 
							LEAVE cur_dlugie_loop;
						END IF;
						UPDATE afr_osobodni_azl SET il_osobodni_wypisowych=il_osobodni_wypisowych+v_dni_leczenia_dlugo WHERE dzien=v_data_wypisu_dlugo AND id_oddzial=v_id_oddzial;
						IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
							UPDATE afr_osobodni_azl SET il_osobodni_wypisowych_planowe=il_osobodni_wypisowych_planowe+v_dni_leczenia_dlugo WHERE dzien=v_data_wypisu_dlugo AND id_oddzial=v_id_oddzial;
						ELSE
							UPDATE afr_osobodni_azl SET il_osobodni_wypisowych_pozostale=il_osobodni_wypisowych_pozostale+v_dni_leczenia_dlugo WHERE dzien=v_data_wypisu_dlugo AND id_oddzial=v_id_oddzial;
						END IF;
					END LOOP;
					CLOSE cur_dlugie;
				END;
				
				IF(v_stan_czesci = 'K' OR v_stan_czesci = 'K,S' OR v_stan_czesci = 'S') THEN
					
					UPDATE afr_osobodni_azl SET il_przyjec=il_przyjec-1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
					UPDATE afr_osobodni_azl SET il_osobodni=il_osobodni+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;

					IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
						UPDATE afr_osobodni_azl SET il_przyjec_planowych=il_przyjec_planowych-1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
						UPDATE afr_osobodni_azl SET il_osobodni_przyjec_planowych=il_osobodni_przyjec_planowych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
					  ELSE
						UPDATE afr_osobodni_azl SET il_przyjec_pozostalych=il_przyjec_pozostalych-1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
						UPDATE afr_osobodni_azl SET il_osobodni_przyjec_pozostalych=il_osobodni_przyjec_pozostalych+1 WHERE dzien=v_data_przyjecia AND id_oddzial=v_id_oddzial;
					END IF;

				END IF;
			ELSE 
				UPDATE afr_osobodni_azl SET il_osobodni_wypisowych=il_osobodni_wypisowych+v_dni-v_il_przepustek WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial ;
					IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
						UPDATE afr_osobodni_azl SET il_osobodni_wypisowych_planowe=il_osobodni_wypisowych_planowe+v_dni-v_il_przepustek WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial ;
					ELSE
						UPDATE afr_osobodni_azl SET il_osobodni_wypisowych_pozostale=il_osobodni_wypisowych_pozostale+v_dni-v_il_przepustek WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial ;
					END IF;
            END IF;
        ELSE 
            UPDATE afr_osobodni_azl SET il_ambulatoryjnych=il_ambulatoryjnych+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;  
			IF( v_tryb_przyjecia = 1 OR v_tryb_przyjecia = 6 ) THEN
				UPDATE afr_osobodni_azl SET il_ambulatoryjnych_planowych=il_ambulatoryjnych_planowych+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;  
			  ELSE
				UPDATE afr_osobodni_azl SET il_ambulatoryjnych_pozostalych=il_ambulatoryjnych_pozostalych+1 WHERE dzien=v_data_wypisu AND id_oddzial=v_id_oddzial;  
			END IF;
        END IF;
    END LOOP;

    CLOSE cur_pobyt;   
		
END$$
DELIMITER ;
