<?php
/**
 *Template Name: main-page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php //comments_template( '', true ); ?>
                            <div><?php echo do_shortcode('[testimonial_view id=1]'); ?></div>
                            <div><button id="open-testimonial-form" onclick="javascript:OpenTestimonialForm();">Добавить</button></div>
                            <div id="testimonial-form" style="display: none"><?php echo do_shortcode('[testimonial_view id=2]'); ?></div>
                            <div id="cities-distance">
                                <label for="city1">Начало маршрута</label>
                                <input id="city1" type="text"><br>
                                <label for="city1">Окончание маршрута</label>
                                <input id="city2" type="text"><br>
                                <label id="cities-distance-label">0 км</label>
                                <button id="calc-cities-distance" onclick="javascript:CalcCitiesDistance();">Рассчитать</button>
                                <div id="map_canvas" style="display: none;"></div><!-- style="display: none;"-->
                            </div>
                            <div id="marquee">Тестовый текст для бегущей строки</div>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<script src="/wp-content/themes/twentytwelve/js/jquery.simplemarquee.js"></script>
<script>
    var map, mapRoute;
    
    function OpenTestimonialForm() {
        //jQuery("#testimonial-form").css("display: block");
        jQuery("#testimonial-form").show();
    }
    
    function CalcCitiesDistance() {
        if ((jQuery("#city1").val() == '') || (jQuery("#city2").val() == '')) {
            alert("Укажите обе точки маршрута !");
            return false;
        }
        
        jQuery('#cities-distance-label').text('Подсчитываем расстояние...');
                
        var city_names = [];
        city_names[0] = jQuery("#city1").val();
        city_names[1] = jQuery("#city2").val();
        
        
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

                /*for(i = 0; i < segments; i += 1) {
                    var segment = route.getPaths().get(i);
                    var distance = Math.round(segment.getLength() / 1000);
                    var $distance_cell = jQuery('td.city_distance').eq(i);
                    $distance_cell.html(distance + ' км');
                    path_segment_distance[i] = distance;
                }*/

                //jQuery('.route_node1').text(routeFrom);
                //jQuery('.route_node2').text(routeTo);
                //jQuery('.distance').text('Длина маршрута: '+ length +', '+ 'приблизительное время в пути: ' + time);
                //jQuery("#ViewMapModal #myModalLabel").text('Длина маршрута: '+ length_txt +', '+ 'приблизительное время в пути: ' + time_txt);
                jQuery('#cities-distance-label').text('Длина маршрута: ' + length_txt);
                mapRoute = route;

                //jQuery('#path_segment_distance').attr('value', path_segment_distance.join(';'));
                //jQuery('#route-length').attr('value', length);
                //jQuery('#sh_distance').attr('value', length);
                //onCostChange();
                //jQuery("#function_button").attr('value', 'См. карту');
                //jQuery("#function_button").removeAttr("disabled");
                //jQuery("#ViewMapModalBody").append(jQuery("#map_canvas"));
                return true;
            },
            function(error) {
                alert('Невозможно построить маршрут.\nВозможно один из городов введен неверно.\nОшибка:' + error.message);
                //jQuery('#route-length').attr('value', 'Ошибка');
                //jQuery("#function_button").attr('value', 'Рассчитать');
                //jQuery("#function_button").removeAttr("disabled");
                return flase;
            }
        ); 
    }
    
    jQuery(document).ready(function(){
       //jQuery('#marquee').simplemarquee(); 
    });
</script>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
