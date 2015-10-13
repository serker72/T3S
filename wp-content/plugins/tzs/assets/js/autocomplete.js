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

jQuery(document).ready(function(){
	autocomplete("#first_city");
	autocomplete("#second_city");
});

function autocomplete(element) {
	    jQuery(element).keyup(function(){
        //по мере ввода фразы, событие будет срабатывать всякий раз
        var search_query = jQuery(this).val();
        //массив, в который будем записывать результаты поиска
        search_result = [];
        //делаем запрос к геокодеру
        jQuery.getJSON('http://geocode-maps.yandex.ru/1.x/?format=json&kind=streetcallback=?&geocode='+search_query, function(data) {
            //геокодер возвращает объект, который содержит в себе результаты поиска
            //для каждого результата возвращаются географические координаты и некоторая дополнительная информация
            //ответ геокодера легко посмотреть с помощью console.log();
            for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
                //записываем в массив результаты, которые возвращает нам геокодер
                search_result.push({
                    label: data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
                    value:data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
                    longlat:data.response.GeoObjectCollection.featureMember[i].GeoObject.Point.pos});
            }
            //подключаем к текстовому полю виджет autocomplete
            jQuery(element).autocomplete({
                //в качестве источника результатов указываем массив search_result
                source: search_result,
                //onSelect: function(data, value){ }
            });
        });
	});

    jQuery.ui.autocomplete.filter = function (array, term) {
        return jQuery.grep(array, function (value) {
            return value.label || value.value || value;
        });
    };
}

 
 