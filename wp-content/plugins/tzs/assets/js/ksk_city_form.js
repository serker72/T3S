/* 
 * Функции для работы со списком городов
 */

var map, mapRoute;
var MAX_CITY_CNT = 10;
//var CITY_NAMES = [];
//var CITY_IDS = [];

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
        showDelCityLink();
        changeCityNames();
        clearDistanceLabels();
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

    //$div.append($input).append($dd_menu);
    $div.append($input);
    $td.append($div);
    // 'add city' button
    $td.append('<div class="add_city_div"><span class="add_city_span" onclick="addCity(this);">добавить пункт</span></div>');
    //$td.append('<div class="add_city_div"><div class="add_city_span" onclick="addCity(this);">добавить пункт</div></div>');
    //$td.append('<div class="add_city_div" onclick="addCity(this);">добавить пункт</div>');
    $tr.append($td);
    $tr.append('<td class="city_delete"><div class="delete_city_button" onclick="removeCity(this);">&nbsp;</div></td>');
    
    // cell 5 - delete button
    var $last_tr,
        button_text = 'Рассчитать';
    if (!from_ui && (index == CITY_NAMES.length - 1)) {
        $last_tr = jQuery('<tr></tr>');
        $last_tr.append('<td id="totalDistance" colspan="2"></td>');
        $last_tr.append('<td colspan="2" style="text-align: right;"><input type="button" id="function_button" class="button dist_add" tabindex="2" value="' + button_text + '" onclick="calcCitiesDistance();"></td>');
        //$last_tr.append('<td></td>');
    }
    
    if (!from_ui) {
        jQuery('#citiesTable').append($tr).append($last_tr);
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
    showDelCityLink();
    changeCityNames();
    clearDistanceLabels();
}

// Изменение надписи
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

// Скрытие кнопок удаления при указании 2 пунктов
function showDelCityLink() {
    if (countOfCityRows() < 3) {
        jQuery("div.delete_city_button").hide();
    } else {
        jQuery("div.delete_city_button").show();
    }
    
    if (countOfCityRows() >= MAX_CITY_CNT) {
        jQuery("div.add_city_span").hide();
    } else {
        jQuery("div.add_city_span").show();
    }
}

function clearDistanceLabels() {
    jQuery('#route-length').attr('value', '');
    jQuery('#sh_distance').attr('value', '');
    jQuery('#path_segment_distance').attr('value', '');
    //jQuery("div.city_distance").html('');
    jQuery('div').find('.city_distance').each(function () {
        jQuery(this).html('');
    });
    jQuery("#function_button").attr('value', 'Рассчитать');
}

// Изменение списка городов
function changeCityNames() {
    var i = 0;
    jQuery('#citiesTable').find('input[type=text]').each(function () {
        CITY_NAMES[i] = jQuery(this).val();
        i += 1;
    });
}

// Построение формы
function initCitiesTable() {
    var filledCitiesCnt = 0;
    
    if (CITY_NAMES.length == 0) {
        CITY_NAMES[0] = '';
        CITY_NAMES[1] = '';
    }
    
    for (var index = 0; index < CITY_NAMES.length; index += 1) {
        addCityRow(index, false);
        if (CITY_NAMES[index] !== '') {
            filledCitiesCnt += 1;
        }
    }
    changeCityTitle();
    showDelCityLink();
    if (filledCitiesCnt === CITY_NAMES.length) {
        calcCitiesDistance();
    }
    jQuery("#citiesTable").find(":text[value='']:first").focus();    
}

    
// Рассчет расстояний
function calcCitiesDistance() {
    var calc_flag = ((jQuery('#route-length').attr('value') === '') || (jQuery('#route-length').attr('value') === 'Ошибка'));
    
    if (!calc_flag) {
        jQuery("#function_button").attr('value', 'См. карту');
        showDistanceModal();
    } else {
        jQuery("#function_button").attr('value', 'Рассчитать');
    }
    
    // Получим список введенных городов
    var cities = document.getElementsByName('input_city[]');
    //var cities = [];
    var city_names = [];
    var city_ids = [];
    var path_segment_distance = [];
    var filledCitiesCnt = 0;

    /*jQuery('#citiesTable').find('input[type=text]').each(function () {
        cities.push(jQuery(this).val());
    });*/
    
    
    // Подсчитаем кол-во заполненных полей
    for(i = 0; i < cities.length; i += 1) {
        if (cities[i].value !== '') {
            city_names[filledCitiesCnt] = cities[i].value;
            city_ids[filledCitiesCnt] = cities[i].getAttribute('city_id');
            filledCitiesCnt += 1;
        }
    }
    
    if (city_names.length < 2) {
        alert('Укажите, пожалуйста, как минимум две точки Вашего маршрута!');
        return;
    } 
    
    // Если указано только 2 пункта - проверим, а не одинаковы ли они
    if (city_names.length == 2) {
        if ((city_names[0] == city_names[1]) || ((city_ids[0] == city_ids[1]) && (city_ids[0] != 0))) {
            alert('Укажите, пожалуйста, различные точки Вашего маршрута !');
            return;
        }
    }
    
    if (filledCitiesCnt < cities.length) {
        alert('Укажите, пожалуйста, все добавленные пункты Вашего маршрута или удалите ненужные поля !');
        return;
    } 

    // Удаление старого маршрута
    if (mapRoute) {
        map.geoObjects.remove(mapRoute);
    } else {
        // Создание карты
        map = new ymaps.Map("map_canvas", {
            center: [55.76, 37.64],
            zoom: 5,
            controls: ['zoomControl','typeSelector']
        });
    }
    
    // Построим маршрут
    ymaps.route(city_names, {mapStateAutoApply:true}).then(
        function(route) {
            map.geoObjects.add(route);
            var length = Math.round(route.getLength() / 1000);
            var length_txt = route.getHumanLength().replace(/&#160;/,' ');
            var time_txt = route.getHumanTime().replace(/&#160;/g,' ');
            var segments = route.getPaths().getLength();
            
            for(i = 0; i < segments; i += 1) {
                var segment = route.getPaths().get(i);
                var distance = Math.round(segment.getLength() / 1000);
                var $distance_cell = jQuery('td.city_distance').eq(i);
                $distance_cell.html(distance + ' км');
                path_segment_distance[i] = distance;
            }

            //jQuery('.route_node1').text(routeFrom);
            //jQuery('.route_node2').text(routeTo);
            //jQuery('.distance').text('Длина маршрута: '+ length +', '+ 'приблизительное время в пути: ' + time);
            jQuery("#ViewMapModal #myModalLabel").text('Длина маршрута: '+ length_txt +', '+ 'приблизительное время в пути: ' + time_txt);
            mapRoute = route;
            
            jQuery('#path_segment_distance').attr('value', JSON.stringify(path_segment_distance));
            jQuery('#route-length').attr('value', length);
            jQuery('#sh_distance').attr('value', length);
            jQuery("#function_button").attr('value', 'См. карту');
            jQuery("#ViewMapModalBody").append(jQuery("#map_canvas"));
        },
        function(error) {
            alert('Невозможно построить маршрут.\nВозможно один из городов введен неверно.\nОшибка:' + error.message);
            jQuery('#route-length').attr('value', 'Ошибка');
            jQuery("#function_button").attr('value', 'Рассчитать');
            return;
        }
    ); 
    
}

function showDistanceModal() {
    //jQuery("#ViewMapModalBody").append(jQuery("#map_canvas"));
    //jQuery("#ViewMapModalBody #map_canvas").css({'display': 'block'});
    jQuery("#ViewMapModal").modal('show');
}