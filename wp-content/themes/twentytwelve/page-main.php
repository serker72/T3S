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
                            <div id="t3s-vseazs-informer">
                                <div id="t3s-vseazs-informer-region">
                                    <select id="t3s-vseazs-informer-region-selector">
                                        <option value="1">Винницкая область</option>
                                        <option value="2">Волынская область</option>
                                        <option value="3">Днепропетровская область</option>
                                        <option value="4">Донецкая область</option>
                                        <option value="5">Житомирская область</option>
                                        <option value="6">Закарпатская область</option>
                                        <option value="7">Запорожская область</option>
                                        <option value="8">Ивано-Франковская область</option>
                                        <option value="9">Киевская область</option>
                                        <option value="10">Кировоградская область</option>
                                        <option value="11">Крым</option>
                                        <option value="12">Луганская область</option>
                                        <option value="13">Львовская область</option>
                                        <option value="14">Николаевская область</option>
                                        <option value="15">Одесская область</option>
                                        <option value="16">Полтавская область</option>
                                        <option value="17">Ровенская область</option>
                                        <option value="18">Сумская область</option>
                                        <option value="19">Тернопольская область</option>
                                        <option value="20">Харьковская область</option>
                                        <option value="21">Херсонская область</option>
                                        <option value="22">Хмельницкая область</option>
                                        <option value="23">Черкасская область</option>
                                        <option value="24">Черниговская область</option>
                                        <option value="25">Черновицкая область</option>
                                    </select>
                                </div>
                                <div id="t3s-vseazs-informer-result"><a id="vseazs_informer" class="vseazs-informer" href="http://vseazs.com"></a><script type="text/javascript" charset="UTF-8" src="http://vseazs.com/inf.php?reg=1&fuels=01110101"></script></div>
                            </div>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<div style="clear: both;"></div>
<div><?php echo do_shortcode('[logo-slider]'); ?></div>

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
        jQuery("#t3s-vseazs-informer-region-selector").on('change', function() {
            url = "http://vseazs.com/inf.php?reg=" + jQuery("#t3s-vseazs-informer-region-selector").val() + "&fuels=01110101";
            jQuery.ajax({
                type: "GET",
                url: url,
                dataType: "script",
                success: function(data){
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    jQuery("#t3s-vseazs-informer-result").html(textStatus);
                }
            });
       });
    });
</script>

<?php get_footer(); ?>
