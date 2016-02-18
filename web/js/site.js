$(document).ready( function() {

	var generalInsight = '';

	$('.indicators-available-lBox').dblclick(function() {
		var selected = $(this).find('option:selected').detach();
		$(selected).prop('selected', true);
		$('.indicators-lBox').append(selected);
	});

	$('.indicators-lBox').dblclick(function() {
		var selected = $(this).find('option:selected').detach();
		$('.indicators-available-lBox').prepend(selected);
	});

	$('.categories-form>form').submit(function(e) {
		$('.indicators-lBox').find('option').prop('selected', true);
	});

	$('#insightsdef-id_category').change(function() {
		var value = $(this).find('option:selected').val();
		window.location.replace('create?idCategory='+value);
	});

	$('#insightsdef-name').change(function() {
		var value = $(this).find('option:selected').val();
		if (value > 0) {
			$.post('get-insights', {'InsightsDef[name]' : value}, function(data) {
				if (data.values) {
					$('label').removeClass('active');
					for (var key in data.values) {
						if ($.inArray(key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities']) > -1) {

						} else {
							var value = (data.values[key] != null ? data.values[key] : "");
							$('input[name="InsightsDef['+key+']"][value="'+value+'"]').prop('checked', true).parent().addClass('active');
						}
					}
				}
				generalInsight = data.content.general.join(' ');
				$('#insightscontent-content').val(data.content.exact);
				$('#insightsdef-content').html(generalInsight + ' ' + data.content.exact);
			}, 'JSON');
		}
	});

	$('input[type="radio"]').parent().change(function() {
		var form = $('form').serializeArray();
		var postArray = {};
		for (var idx in form) {
			if (form[idx].name != 'InsightsDef[name]') {
				postArray[form[idx].name] = form[idx].value;
			} else {
				postArray['InsightsDef[name]'] = 0;
			}
		}
		$.post('get-insights', postArray, function(data) {
			generalInsight = data.content.general.join(' ');
			$('#insightscontent-content').val(data.content.exact);
			$('#insightsdef-content').html(generalInsight + ' ' + data.content.exact);
		}, 'JSON');
	});

	$('#insightscontent-content').keyup(function() {
		$('#insightsdef-content').html(generalInsight + ' ' + $('#insightscontent-content').val());
	});
});
