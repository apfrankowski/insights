$(document).ready( function() {

    var generalInsight = '';
    if ($('#insightsdef-name').find('option:selected').val() > 0) {
        changeInsight($('#insightsdef-name'));
    }

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
        changeInsight($(this));
    });

    $('input[type="text"]').change(function() {
        getInsights();
    });

    $('input[type="radio"]').parent().change(function() {
        getInsights()
    });

    function getInsights() {
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
            generalInsight = '';
            if (data.content.general.length) {
                generalInsight = data.content.general.join(' ');
            }
            $('#insightscontent-content').val(data.content.exact.content);
            $('#insightscontent-id').val(data.content.exact.id);
            $('#insightsdef-content').html(generalInsight + ' ' + data.content.exact.content);
            $('#insightsdef-name').find('option[value="'+data.content.exact.defId+'"]').prop('selected', true);
        }, 'JSON');
        $('#insightsdef-submit_button').removeAttr('disabled');
    }

    $('#insightscontent-content').keyup(function() {
        $('#insightsdef-content').html(generalInsight + ' ' + $('#insightscontent-content').val());
    });

    $('#insightsdef-reset_button').click(function() {
        var value = $('#insightsdef-id_category').find('option:selected').val();
        window.location.replace('create?idCategory='+value);        
    });

    $('#insightsdef-submit_button').click(function() {
        if ($(this).attr('disabled') != 'disabled') {
            $('form').find('select[id="insightsdef-name"]')
                .children('option[value="0"]')
                .prop('selected', true)
                .submit();
        }
    });

    $('#insightsdef-update_button').click(function() {
        if ($(this).attr('disabled') != 'disabled') {
            $('form').attr('action', '/insights-def/update').submit();
        }
    });

    $('#insightsdef-delete_button').click(function() {
        if ($(this).attr('disabled') != 'disabled') {
            $('form').attr('action', '/insights-def/delete').submit();
        }
    });

    function changeInsight($self) {
        var value = $self.find('option:selected').val();
        if (value > 0) {
            $.post('get-insights', {'InsightsDef[name]' : value}, function(data) {
                if (data.values) {
                    $('label').removeClass('active');
                    for (var key in data.values) {
                        var value = (data.values[key] != null ? data.values[key] : "");

                        if ($.inArray(key, ['id', 'id_category', 'priority']) > -1) {

                        } else if ($.inArray(key, ['hospitals', 'units', 'specialities']) > -1) {
                            $('input[name="InsightsDef['+key+']"]').val(value);
                        } else {
                            $('input[name="InsightsDef['+key+']"][value="'+value+'"]').prop('checked', true).parent().addClass('active');
                        }
                    }
                }
                generalInsight = '';
                if (data.content.general.length) {
                    generalInsight = data.content.general.join(' ');
                }
                $('#insightscontent-content').val(data.content.exact.content);
                $('#insightscontent-id').val(data.content.exact.id);
                $('#insightsdef-content').html(generalInsight + ' ' + data.content.exact.content);
                $('#insightsdef-update_button').removeAttr('disabled');
                $('#insightsdef-delete_button').removeAttr('disabled');
                $('#insightsdef-submit_button').attr('disabled', 'disabled');
            }, 'JSON');
        } else {
            $('#insightsdef-update_button').attr('disabled', 'disabled');
            $('#insightsdef-delete_button').attr('disabled', 'disabled');
        }
    }

});
