/* 
 * Функции для работы со списком городов
 */

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
}
    
// Построение формы
function initCitiesTable() {
    if (CITY_NAMES.length == 0) {
        CITY_NAMES[0] = '';
        CITY_NAMES[1] = '';
    }
    
    for (var index = 0; index < CITY_NAMES.length; index += 1) {
        addCityRow(index, false);
    }
    changeCityTitle();
    showDelCityLink();
    jQuery("#citiesTable").find(":text[value='']:first").focus();    
}

    
// Рассчет расстояний
function calcCitiesDistance() {
    // Получим список введенных городов
    var cities = document.getElementsByName('input_city[]');
    var city_names = [];
    var city_ids = [];
    var encodedPoints = [];
    var filledCitiesCnt = 0;
    var map, mapRoute;

    
    // Подсчитаем кол-во заполненных полей
    //for(i = 0; i < cities.length; i += 1) {
    for(i = 0; i < 3; i += 1) {
        if (cities[i].value !== '') {
            city_names[filledCitiesCnt] = cities[i].value;
            city_ids[filledCitiesCnt] = cities[i].getAttribute('city_id');
            encodedPoints[filledCitiesCnt] = stringBase64EncodeDecode(cities[i].value, 'encode');
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
            alert('Укажите, пожалуйста, различные точки Вашего маршрута!');
            return;
        }
    }

    map = new ymaps.Map("map_canvas", {
        center: [55.76, 37.64],
        zoom: 5,
        controls: ['zoomControl','typeSelector']
    });

    // Удаление старого маршрута
    if (mapRoute) {
      map.geoObjects.remove(mapRoute);
    } 
    
    // Построим маршрут
    ymaps.route(encodedPoints, {mapStateAutoApply:true}).then(
        function(route) {
            map.geoObjects.add(route);
            var length = route.getDistance() / 1000;
            //var length = route.getHumanLength().replace(/&#160;/,' ');
            //var time = route.getHumanTime().replace(/&#160;/g,' ');
            var segments = route.getNumRouteSegments();
            
            for(i = 0; i < segments; i += 1) {
                var segment = route.getRouteSegment(i);
                var distance = segment.getDistance() / 1000;
                var $distance_cell = jQuery('td.distance').eq(i);
                $distance_cell.html(distance + ' км');
            }

            //jQuery('.route_node1').text(routeFrom);
            //jQuery('.route_node2').text(routeTo);
            //jQuery('.distance').text('Длина маршрута: '+ length +', '+ 'приблизительное время в пути: ' + time);
            //mapRoute = route;
            
            jQuery('#sh_distance').attr('value', length);
        },
        function(error) {
            alert('Невозможно построить маршрут:\n' + error.message);
            return;
        }
    ); 
    
}

function stringBase64EncodeDecode(str, action) {
    if ((typeof(str) !== 'string') || (str == '')) {
        alert('Необходимо указать строку !');
        return '';
    }
    
    // Create Base64 Object
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

    if (action === 'encode') {
        // Encode the String
        var encodedString = Base64.encode(str);
        console.log(encodedString); // Outputs: "SGVsbG8gV29ybGQh"
        return encodedString;
    } else if (action === 'decode') {
        // Decode the String
        var decodedString = Base64.decode(str);
        console.log(decodedString); // Outputs: "Hello World!"
        return decodedString;
    } else {
        alert('Необходимо указать значение action decode или encode !');
        return '';
    }
}
