<!-- <html>
    <script src="http://api-maps.yandex.ru/2.0-stable/?load=package.full&amp;lang=ru-RU"></script>
    <script>
      var map, mapRoute;
    
      ymaps.ready(function() {
        map = new ymaps.Map('map', {
          center: [55.76, 37.64], 
          zoom: 12
        });
      });
      
      function createRoute() {
        // Удаление старого маршрута
        if (mapRoute) {
          map.geoObjects.remove(mapRoute);
        }
        
        var routeFrom = document.getElementById('route-from').value;
        var routeTo = document.getElementById('route-to').value;
        
        // Создание маршрута
        ymaps.route([routeFrom, routeTo], {mapStateAutoApply:true}).then(
          function(route) {
            map.geoObjects.add(route);
            document.getElementById('route-length').innerHTML = 'Длина маршрута = ' + route.getHumanLength();
            mapRoute = route;
          },
          function(error) {
            alert('Невозможно построить маршрут');
          }
        );
      }
    </script>
  <body>
    <div>От: <input type="text" id="route-from" value="Москва, Белорусский вокзал"></div>
    <div>До: <input type="text" id="route-to" value="Москва, Лефортово"></div>
    <div><input type="submit" value="Построить маршрут" onclick="createRoute();"></div>
    <div id="map"></div>
    <div id="route-length">Длина маршрута = 14&nbsp;км</div>
  
</body></html> -->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Быстрый старт. Размещение интерактивной карты на странице</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        
        var map, 
            mapRoute;
			
		ymaps.ready(init);

        function init(){ 
            map = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 2
            }); 
        }
		
		function createRoute() {
        // Удаление старого маршрута
        if (mapRoute) {
          map.geoObjects.remove(mapRoute);
        }
        
        var routeFrom = document.getElementById('route-from').value;
        var routeTo = document.getElementById('route-to').value;
        
		
		//map.setGlobalPixelCenter(0, 0);
		
        // Создание маршрута
        ymaps.route([routeFrom, routeTo], {mapStateAutoApply:true}).then(
          function(route) {
            map.geoObjects.add(route);
            document.getElementById('route-length').innerHTML = 'Длина маршрута = ' + route.getHumanLength();
            mapRoute = route;
          },
          function(error) {
            alert('Невозможно построить маршрут');
          }
        );
      }
		
		
		
		
    </script>
</head>

<body>
	<div>От: <input type="text" id="route-from" value=""></div>
    <div>До: <input type="text" id="route-to" value=""></div>
	<div><input type="submit" value="Построить маршрут" onclick="createRoute();"></div>
    <div id="map" style="width: 600px; height: 400px"></div>
	
</body>

</html>

