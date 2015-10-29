/* function loadScript() {
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&callback=autocomplete_initialize';
	document.body.appendChild(script);
}

function autocomplete_initialize() {
	if (isInitialized()) {
		jQuery('input[autocomplete=city]').each(function() {
			//new google.maps.places.Autocomplete((this), { types: ['(regions)'] });
			
		new google.maps.places.Autocomplete((this), { types: ['(cities)'] });

			jQuery(this).removeAttr('autocomplete');
		});
	}
}

function isInitialized() {
	return typeof google === 'object' && typeof google.maps === 'object';
}

jQuery(document).ready(function(){
	jQuery(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
	if (isInitialized()) {
		autocomplete_initialize();
	} else {
		loadScript();
	}
		
}); */

//        <script src="/wp-content/plugins/tzs/assets/js/jquery-1.8.2.min.js"></script>
 //      <script src="/wp-content/plugins/tzs/assets/js/jquery-ui.min.js"></script>
var search_result = [];
var counter = 0;

jQuery(document).ready(function(){
	autocomplete("#first_city");
	autocomplete("#second_city");
});


function calculate_distance() {
	var length = 0;		
	var routeFrom = document.getElementById('first_city').value;
	var routeTo = document.getElementById('second_city').value;
	// Создание маршрута
	ymaps.route([routeFrom, routeTo]).then(
		function(route) {
			//alert('Длина маршрута = ' + route.getHumanLength());
			length = route.getHumanLength().replace(/&#160;/,' ').replace(/ км/,'');
			jQuery('#sh_distance').attr('value', length);
			document.getElementById('route-length').value = length;			
			/*var x = document.getElementsByName('theForm');
			x[0].submit(); // Form submission */
		},
		function(error) {
		 alert('Невозможно построить маршрут. Возможно один из городов введен неверно.');
			document.getElementById('route-length').value = 'Ошибка';
		}
	); 
}

function onCityChange() {
			if ((jQuery('#first_city').val().length > 0) && (jQuery('#second_city').val().length > 0)) {
	calculate_distance();
				jQuery('#show_dist_link').show();
			} else {
				jQuery('#sh_distance').attr('value', '');
				jQuery('#show_dist_link').hide();
			}
}

function autocomplete(element) {


	    jQuery(element).keyup(function(){
        //по мере ввода фразы, событие будет срабатывать всякий раз
        var search_query = jQuery(this).val();
        //массив, в который будем записывать результаты поиска
        search_result = [];
		
        //делаем запрос к геокодеру
        jQuery.getJSON('http://geocode-maps.yandex.ru/1.x/?format=json&kind=locality&callback=?&geocode='+search_query, function(data) {
            //геокодер возвращает объект, который содержит в себе результаты поиска
            //для каждого результата возвращаются географические координаты и некоторая дополнительная информация
            //ответ геокодера легко посмотреть с помощью console.log();
            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                //записываем в массив результаты, которые возвращает нам геокодер
				if(data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind == 'locality')
					search_result.push({
						label: data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
						value:data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
						flag: data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryNameCode,
						longlat:data.response.GeoObjectCollection.featureMember[i].GeoObject.Point.pos});
			}
            //подключаем к текстовому полю виджет autocomplete
            jQuery(element).autocomplete({
                //в качестве источника результатов указываем массив search_result
                source: search_result,
                close: function(event, ui){
				 var path = "/wp-content/plugins/tzs/assets/images/flags/";
				 for(var i = 0; i < search_result.length; i++){
					if(this.value == search_result[i].label){
						path = path + search_result[i].flag.toLowerCase()+".png";
						break;
						}
						//alert(this.value+" == "+search_result[i].label+"    "+search_result[i].flag);
				 }
				var id = element.substring(1,element.length)+'_flag';
				document.getElementById(id).src = path;
				document.getElementById(id).style.visibility = 'visible';
				//onCityChange();
				}
            });
        });
	});

    jQuery.ui.autocomplete.filter = function (array, term) {
        return jQuery.grep(array, function (value) {
            return value.label || value.value || value;
        });
    };
}
 