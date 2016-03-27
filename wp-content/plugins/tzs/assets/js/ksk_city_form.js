/* 
 * Функции для работы со списком городов
 */

var MAX_CITY_CNT = 10;
var CITY_NAMES = [];
var CITY_IDS = [];

// Подсчет кол-ва строк для ввода города
function countOfCityRows() {
    return jQuery('.city_row').length;
}

// Добавление новой строки для ввода города
function addCity(el) {
    if (countOfCityRows() < MAX_CITY_CNT) {
        var index = jQuery('.city_row').index(jQuery(el).parents('.city_row')) + 1;
        if (index > 0) {
            jQuery('td.city_distance').eq(index - 1).html('&nbsp;'); // remove distance between previous and next cities
        }
        //alert('index=' + index);
        addCityRow(index, true);
        changeCityTitle();
        jQuery('.city_input').eq(index).focus();
    }
}

// Добавление новой строки в таблицу
function addCityRow(index, from_ui) {
    var $tr = jQuery('<tr class="city_row"></tr>');
    
    if (index < countOfCityRows()-1) {
        t = 'Через';
    } else {
        t = 'Куда';
    }
    
    $tr.append('<td class="city_title">' + t + '</td>'); // cell 1 - direction
    //$tr.append('<td class="marker"></td>'); // cell 2 - marker
    $tr.append('<td class="city_distance">&nbsp;</td>'); // cell 3 - distance
    
    var $td = jQuery('<td class="city_input">'),
        $div = jQuery('<div class="input_div"></div>'),
        $input = jQuery('<input type="text" autocomplete="off" name="input_city[]" class="city_input wide_page" tabindex="1">').attr({
            value: !from_ui && CITY_NAMES[index] || '',
            city_id: !from_ui && CITY_IDS[index] || 0
        }),
        $dd_menu = jQuery('<div class="transparent2"><div class="cityHintDiv"></div></div>');

    $div.append($input).append($dd_menu);
    $td.append($div);
    // 'add city' button
    $td.append('<div class="add_city_div"><span class="add_city_span" onclick="addCity(this);">добавить пункт</span></div>');
    $tr.append($td);
    $tr.append('<td class="city_delete"><div class="delete_city_button" onclick="removeCity(this);">&nbsp;</div></td>');
    
    if (!from_ui) {
        jQuery('#citiesTable').append($tr);//.append($last_tr);
    } else if (index > 0) {
        jQuery('.city_row').eq(index - 1).after($tr);
    }
    
}

// Удаление строки для ввода города
function removeCity(el) {
    if (countOfCityRows() < 3) {
        return;
    }
    
    var $row = jQuery(el).parents('.city_row');
    var index = jQuery('.city_row').index($row);

    if ($row.prev().hasClass('ambiguous_city_label')) {
        // if the row to be deleted contains ambiguous div, we also need to delete the clarify label before this row
        $row.prev().remove();
    }
    $row.remove();
    if (index > 0) {
        jQuery('td.city_distance').eq(index - 1).html('&nbsp;');
    }
    
    changeCityTitle();
}

//
function changeCityTitle() {
    jQuery('.city_title').each(function(){
        var el = jQuery(this);
        var index = jQuery('.city_row').index(jQuery(el).parents('.city_row'));
        if (index == 0) {
            jQuery('td.city_title').eq(index).html('Откуда');
        } else if (index == countOfCityRows()-1) {
            jQuery('td.city_title').eq(index).html('Куда');
        } else {
            jQuery('td.city_title').eq(index).html('Через');
        }
    });
}