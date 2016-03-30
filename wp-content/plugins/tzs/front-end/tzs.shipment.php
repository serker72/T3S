<?php

include_once(TZS_PLUGIN_DIR.'/functions/tzs.shipment.functions.php');

function tzs_print_shipment_form($errors, $edit=false) {
    $d = date("d.m.Y");
	
    //print_errors($errors);
    ?>
    <script src="/wp-content/plugins/tzs/assets/js/distance.js"></script>
    <script src="/wp-content/plugins/tzs/assets/js/autocomplete.js"></script>
    <script src="/wp-content/plugins/tzs/assets/js/ksk_city_form.js" type="text/javascript"></script>

    <div style="clear: both;"></div>
    
    <!-- test new form -->
<div class="form_wrapper">
    <form enctype="multipart/form-data" method="post" id="form_shipment" class="" action="">
        
        <div class="row-fluid"  style="width: 100%; ">
            <div class="span5">
                <div class="city_input_div">
                    <table id="citiesTable">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
                <div><input type="text" id="datepicker1" name="sh_date_from" size="" value="<?php echo_val_def('sh_date_from', ''); ?>" placeholder="Дата погрузки" readonly="true" style="width: 60px;"></div>
                <div><input type="text" id="datepicker2" name="sh_date_to" size="" value="<?php echo_val_def('sh_date_to', ''); ?>" placeholder="Дата выгрузки" readonly="true" style="width: 60x;"></div>
                <div><input type="text" size="15" name="comment" value="<?php echo_val('comment'); ?>" maxlength = "255" placeholder="Комментарий"></div>
                <div>
                    <select id="trans_type" name="trans_type">
                    <?php
                        tzs_print_array_options($GLOBALS['tzs_tr_types'], '', 'trans_type', 'Тип транспортного средства');
                    ?>
                    </select>
                </div>
                <div class="span12">
                    <label for="trans_count">Кол-во машин:</label>&nbsp;
                    <input type="text" size="5" id="trans_count" name="trans_count" value="<?php echo_val('trans_count'); ?>" maxlength = "2" placeholder = "0" style="width: 55px;">
                </div>
                <div class="span12">
                    <input type="hidden" name="cost_curr" id="cost_curr" value="1">
                    <label for="cost">Стоимость перевозки:</label>&nbsp;
                    <input type="text" id="cost" name="cost" value="<?php echo_val('cost'); ?>" size="10" style="width: 100px;">
                    <div class="post-input">грн</div>
                </div>
                <div class="span12">
                    <label for="price">Цена&nbsp;=</label>&nbsp;
                    <input type="text" id="price" name="price" value="<?php echo_val('price'); ?>" size="10" readonly="true" style="width: 100px;">
                    <div class="post-input">грн/км</div>
                </div>
                <div class="span12">
                    <label for="">Загрузка:</label>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="top_loading" name="top_loading" <?php echo isset($_POST['top_loading']) ? 'checked="checked"' : ''; ?>><label for="top_loading">верхняя</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="side_loading" name="side_loading" <?php echo isset($_POST['side_loading']) ? 'checked="checked"' : ''; ?>><label for="side_loading">боковая</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="back_loading" name="back_loading" <?php echo isset($_POST['back_loading']) ? 'checked="checked"' : ''; ?>><label for="back_loading">задняя</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="full_movable" name="full_movable" <?php echo isset($_POST['full_movable']) ? 'checked="checked"' : ''; ?>><label for="full_movable">с полной растентовкой</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="remove_cross" name="remove_cross" <?php echo isset($_POST['remove_cross']) ? 'checked="checked"' : ''; ?>><label for="remove_cross">со снятием поперечин</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="remove_racks" name="remove_racks" <?php echo isset($_POST['remove_racks']) ? 'checked="checked"' : ''; ?> ><label for="remove_racks">со снятием стоек</label><br>
                </div>
                <div class="chekbox span12">
                    <input type="checkbox" id="without_gate" name="without_gate" <?php echo isset($_POST['without_gate']) ? 'checked="checked"' : ''; ?> ><label for="without_gate">без ворот</label><br>
                </div>
                
            </div>
            
            <div class="span3">
                <div><input type="text" id="sh_descr" name="sh_descr" size="" value="<?php echo_val('sh_descr'); ?>" maxlength = "255" placeholder="Описание груза"></div>
                <div>
                    <select id="sh_type" name="sh_type" placeholder="Тип груза">
                    <?php
                        tzs_print_array_options($GLOBALS['tzs_sh_types'], '', 'sh_type', 'Тип груза');
                    ?>
                    </select>
                </div>
                <div class="chekbox"><!-- style="text-align: right;"-->
                    <input type="checkbox" name="set_dim" id="set_dim" <?php if (isset($_POST['set_dim'])) echo 'checked="checked"'; ?>><label for="set_dim">Указать габариты груза (м):</label>
                    <!--div class="post-input gabarite"></div-->
                </div>
                <div>
                    <input type="text" name="sh_length" id="sh_length" value="<?php echo_val('sh_length'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Длина" style="width: 50px; margin-right: 5px;">&nbsp;&nbsp;
                    <input type="text" name="sh_width" id="sh_width" value="<?php echo_val('sh_width'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Ширина" style="width: 50px; margin-left: 5px;">&nbsp;&nbsp;
                    <input type="text" name="sh_height" id="sh_height" value="<?php echo_val('sh_height'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Высота" style="width: 50px; margin-left: 5px;">
                </div>
                <div><!-- style="text-align: right; float: right;"-->
                    <label for="sh_volume">Объем груза&nbsp;=</label>&nbsp;
                    <input type="text" id="sh_volume" name="sh_volume" value="<?php echo_val('sh_volume'); ?>" readonly="true" style="width: 80px;">
                    <div class="post-input">м<sup>3</sup></div>
                </div>
                <div>
                    <label for="sh_weight">Вес груза:</label>&nbsp;
                    <input type="text" id="sh_weight" name="sh_weight" value="<?php echo_val('sh_weight'); ?>" maxlength = "5" style="width: 50px;"><div class="post-input">т</div>
                </div>
                <div>
                    <label for="">Форма расчета (можно указать несколько способов одновременно):</label>
                </div>
                <div class="chekbox form-group">
                    <input type="checkbox" id="cash" name="cash" <?php echo isset($_POST['cash']) ? 'checked="checked"' : ''; ?>><label for="cash">Наличная</label><br>
                    <input type="checkbox" id="nocash" name="nocash" <?php echo isset($_POST['nocash']) ? 'checked="checked"' : ''; ?>><label for="nocash">Безналичная</label><br>
                    <input type="checkbox" id="way_ship" name="way_ship" <?php echo isset($_POST['way_ship']) ? 'checked="checked"' : ''; ?>><label for="way_ship">При погрузке</label><br>
                    <input type="checkbox" id="way_debark" name="way_debark" <?php echo isset($_POST['way_debark']) ? 'checked="checked"' : ''; ?>><label for="way_debark">При выгрузке</label><br>
                    <input type="checkbox" id="soft" name="soft" <?php echo isset($_POST['soft']) ? 'checked="checked"' : ''; ?>><label for="soft">Софт</label><br>
                    <input type="checkbox" id="way_prepay" name="way_prepay" <?php echo isset($_POST['way_prepay']) ? 'checked="checked"' : ''; ?> ><label for="way_prepay">Предоплата</label><br>
                    <input type="text" id="prepayment" name="prepayment" value="<?php echo_val('prepayment'); ?>" size="5" placeholder = "0" style="width: 20px;"><div class="post-input">%</div><br>
                    <input type="checkbox" id="price_query" name="price_query" <?php echo isset($_POST['price_query']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="price_query">Не указывать стоимость (цена договорная)</label>
                </div>
                
            </div>
        </div>
        
        
        
        
        
        
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
        </div>
        <div class="span3">
	   </div>
        <div class="span1">
            <img id ="first_city_flag" src="<?php echo $edit ? echo_val('from_code') : "" ?>"  style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="">
        </div>
        <div class="span2">
            <!--input type="text" id="sh_distance" name="sh_distance" size="" value="<?php //echo_val('sh_distance'); ?>" maxlength = "255" readonly="true" style="width: 50px;"><div class="post-input">км</div>
            -->
        </div>
        <div id="div_sh_active" class="span3">
            <!--label for="sh_active">Статус</label>
            <select id="sh_active" name="sh_active">
                <option value="1" <?php //if (isset($_POST["sh_active"]) && ($_POST["sh_active"] === 1)) echo 'selected="selected"'; ?> >Публикуемый</option>
                <option value="0" <?php //if (isset($_POST["sh_active"]) && ($_POST["sh_active"] === 0)) echo 'selected="selected"'; ?> >Архивный</option>
            </select-->
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
        </div>
        <div id="div_second_city" class="span3 form-group">
        </div>
        <div class="span1">
            <img id ="second_city_flag" src="<?php echo $edit ? echo_val('to_code') : "" ?>" style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="">
        </div>
        <div class="span2">
        </div>
        <div class="span3">
        </div>
    </div>
                    

    <div class="row-fluid"  style="width: 100%; ">
            <input type="hidden" name="sh_active" id="sh_active" value="1">
        <div class="span1">
            <span><img id="trans_type_img" src="" alt=""></img></span>&nbsp;&nbsp;
        </div>
        <div class="span8">
            <div class="span12 chekbox" style="margin-bottom: 20px;">
            </div>
            <div class="span4">
                <button id="form_button1"><?php echo $edit ? "ИЗМЕНИТЬ ЗАЯВКУ" : "РАЗМЕСТИТЬ ЗАЯВКУ" ?></button>
            </div>
            <div class="span4">
                <?php if (!$edit) { ?>
                <button id="form_button2">ОЧИСТИТЬ ВСЕ ПОЛЯ</button>
                <?php } ?>
            </div>
            <div class="span4">
                <button id="form_button3">ВЫХОД</button>
            </div>
        </div>
        <div class="span4" style="padding: 2px;"><!-- float: right; -->
            <div class="" id="form_error_message" style="color: #F00;border: 1px #F00 dashed; border-radius: 4px; padding: 3px 5px; display: none;">
            </div>
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; margin-top: 10px;">
        <div class="span12">
            <div style="font-size: 92%; font-style: italic;">
                После нажатия кнопки "РАЗМЕСТИТЬ ЗАЯВКУ" заявка будет опубликована в базе транспорта, после нажатия кнопки "ВЫХОД" заявка не сохраняется.
            </div>
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span12">
            <div style="font-size: 92%; font-style: italic;">
                <span style="color: #F00;">Напоминаем:</span> заявка будет удалена из базы активных заявок и перенесена в архив на следующий день после указанного Вами дня выгрузки
            </div>
        </div>
    </div>
    
	<?php if ($edit) {?>
		<input type="hidden" name="action" value="editshipment"/>
		<input type="hidden" name="id" value="<?php echo_val('id'); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="addshipment"/>
	<?php } ?>
        <input type="hidden" name="path_segment_distance" id="path_segment_distance" value="">
        <input type="hidden" name="route-length" id="route-length" value="">
	<input type="hidden" name="formName" value="shipment" />
    </form>
</div>
    <div class="clearfix">&nbsp;</div>
    <div id="map_canvas"></div><!-- style="display: none;"-->
    
    <!-- Modal -->
    <div id="ViewMapModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 98%; margin-left: -49%;  top: 3%;">
        <div class="modal-header">
            <button id="ViewMapModalCloseButton" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 id="myModalLabel">Просмотр карты маршрута</h4>
        </div>
        <div id="ViewMapModalBody" class="modal-body">
            <!--div id="map_canvas"></div-->
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
    </div>
    
    <!-- test new form END -->
    
    <script>
    jQuery('#sh_type').on('change', function() {
            jQuery('#div_sh_type').addClass("change");    
     });
     jQuery('#trans_type').on('change', function() {
            jQuery('#div_trans_type').addClass("change");    
     });
    </script>
	
    <script>
        tzs_tr2_types = [];
        CITY_NAMES = [];
        <?php
            foreach ($GLOBALS['tzs_tr2_types'] as $key => $val) {
                echo "tzs_tr2_types[$key] = '$val[1]';\n";
            }
            
            if (isset($_POST['input_city'])) {
                foreach ($_POST['input_city'] as $key => $val) {
                    echo "CITY_NAMES[$key] = '$val';\n";
                }
            }
            
            //echo "CITY_NAMES = [".$_POST['path_segment_cities']."];\n";
            //echo "CITY_DISTANCES = [".$_POST['path_segment_distances']."];\n";
            echo "CITY_IDS = [];\n";
        ?>
        
        // Расчет расстояния между пунктами
	function calculate_distance() {
		var length = 0;		
		var routeFrom = document.getElementById('first_city').value;
		var routeTo = document.getElementById('second_city').value;
		// Создание маршрута
		ymaps.route([routeFrom, 'Житомир', routeTo]).then(
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

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
	
        // Изменение полей "Населенный пункт погрузки" и "Населенный пункт выгрузки"
	function onCityChange() {
            if ((jQuery('#first_city').val().length > 0) && (jQuery('#second_city').val().length > 0)) {
                calculate_distance();
                jQuery('#show_dist_link').show();
            } else {
                if (jQuery('#first_city').val().length < 1) {
                    //jQuery('#first_city_flag').hide();
                    jQuery('#first_city_flag').attr('src', '');
                }
                
                if (jQuery('#second_city').val().length < 1) {
                    //jQuery('#second_city_flag').hide();
                    jQuery('#second_city_flag').attr('src', '');
                }
                
                jQuery('#sh_distance').attr('value', '');
                jQuery('#show_dist_link').hide();
            }
            
            if (typeof onCostChange === 'function') {
                onCostChange();
            }
	}
		
		
        // Изменение флага "Указать габариты груза (м):"
        function onSetDim(ch) {
            if (ch) {
                jQuery("#sh_length, #sh_width, #sh_height").removeAttr("disabled");
                //jQuery("#sh_length, #sh_width, #sh_height").attr('required', 'required');
            } else {
                //jQuery("#sh_length, #sh_width, #sh_height").removeAttr('required');
                jQuery("#sh_length, #sh_width, #sh_height").attr("disabled", "disabled");
                jQuery("#sh_length, #sh_width, #sh_height").attr('value', '');
                jQuery("#sh_volume").attr('value', '');
            }
        }

        // Вывод карты
        function showDistanceDialog() {
            if ((jQuery('#first_city').val().length > 0) && (jQuery('#second_city').val().length > 0)) {
                //displayDistance([jQuery('input[name=sh_city_from]').val(), jQuery('input[name=sh_city_to]').val()], null);
                displayDistance([jQuery('#first_city').val(), 'Житомир', jQuery('#second_city').val()], null);
            } else {

            }
        }

        // Изменение поля "Тип транспорта"
        function onTransTypeChange() {
            jQuery('#trans_type_img').attr('src', tzs_tr2_types[jQuery('[name=trans_type]').val()]);
        }

        // Изменение поля "Дата загрузки"
        function onDatePicker1Change(dateText, inst) {
            jQuery("#datepicker2").datepicker("option", "minDate", new Date(dateText.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1')));
            jQuery("#datepicker2").datepicker("setDate", dateText);
            //jQuery("#datepicker2").removeAttr("disabled");
        }

        // Изменение поля "Стоимость перевозки"
        function onCostChange() {
            if ((jQuery('#cost').val().length > 0) && (jQuery('#sh_distance').val().length > 0)) {
                var vol = (jQuery('#cost').val() / jQuery('#sh_distance').val()).toFixed(2);
                jQuery('#price').attr('value', vol);
            } else {
                jQuery('#price').attr('value', '');
            }
        }

        // Изменение флага "Не указывать стоимость (цена договорная)"
        function onPriceQueryChange() {
            if (jQuery("#price_query").is(':checked')) {
                jQuery("[name=cost]").attr('value', '');
                jQuery("[name=price]").attr('value', '');
                jQuery("[name=prepayment]").attr('value', '');

                jQuery("[name=cash]").prop('checked', false);
                jQuery("[name=nocash]").prop('checked', false);
                jQuery("[name=way_ship]").prop('checked', false);
                jQuery("[name=way_debark]").prop('checked', false);
                jQuery("[name=soft]").prop('checked', false);
                jQuery("[name=way_prepay]").prop('checked', false);

                jQuery("[name=cost]").attr("disabled", "disabled");
                jQuery("[name=prepayment]").attr("disabled", "disabled");
                jQuery("[name=cash]").attr("disabled", "disabled");
                jQuery("[name=nocash]").attr("disabled", "disabled");
                jQuery("[name=way_ship]").attr("disabled", "disabled");
                jQuery("[name=way_debark]").attr("disabled", "disabled");
                jQuery("[name=soft]").attr("disabled", "disabled");
                jQuery("#way_prepay").attr("disabled", "disabled");
                jQuery("#prepayment").attr("disabled", "disabled");
            } else {
                jQuery("[name=cost]").removeAttr("disabled");
                jQuery("[name=prepayment]").removeAttr("disabled");
                jQuery("[name=cash]").removeAttr("disabled");
                jQuery("[name=nocash]").removeAttr("disabled");
                jQuery("[name=way_ship]").removeAttr("disabled");
                jQuery("[name=way_debark]").removeAttr("disabled");
                jQuery("[name=soft]").removeAttr("disabled");
                jQuery("#way_prepay").removeAttr("disabled");

                jQuery("#prepayment").attr("disabled", "disabled");
            }
        }

        // Изменение флага "Предоплата"
        function onWayPrepayChange(eventObject) {
            if (jQuery("#way_prepay").is(':checked')) {
                jQuery("#prepayment").attr('value', '');
                jQuery("#prepayment").removeAttr("disabled");
            } else {
                jQuery("#prepayment").attr('value', '');
                jQuery("#prepayment").attr("disabled", "disabled");
            }
        }

        
        // Рассчет объема груза
        function onVolumeCalculate() {
            if ((jQuery('#sh_length').val().length > 0) && (jQuery('#sh_width').val().length > 0) && (jQuery('#sh_height').val().length > 0)) {
                var vol = jQuery('#sh_length').val() * jQuery('#sh_width').val() * jQuery('#sh_height').val();
                jQuery('#sh_volume').attr('value', vol);
            } else {
                jQuery('#sh_volume').attr('value', '');
            }
        }

        // Очистка формы
        function resetForm(selector) {
            jQuery(':text, :password, :file, textarea', selector)
                    .val('')
                    .css({'border': '1px solid #007FFF'});
            jQuery(':input, select option', selector)
                    .removeAttr('checked')
                    .removeAttr('selected')
                    .css({'border': '1px solid #007FFF'});
            jQuery('select option:first', selector).attr('selected', true);
            
            // Очистим флаги и скроем кнопку "см. карту"
            jQuery('img', selector).attr('src', '')
            jQuery('#show_dist_link').hide();

            jQuery('#div_cash, #div_nocash, #div_way_ship, #div_way_debark, #div_soft, #div_way_prepay').css({'border': 'none'});

            // Очистим список ошибок
            jQuery("#form_error_message").html('');
            jQuery("#form_error_message").hide();
        }

        // Функция проверки правильности заполнения полей формы до отправки
        function onFormValidate() {
            // test
            return true;
            
            //var ErrorMsg1 = 'Список ошибок:<ul>';
            var ErrorMsg1 = '<p>';
            var ErrorMsg2 = '';
            var ErrorMsg3 = '</p>';

            if (jQuery('#datepicker1').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана дата погрузки.<br>\n';
                jQuery('#datepicker1').css({'border': '2px solid #F00'});
            } else {
                jQuery('#datepicker1').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#first_city').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан населенный пункт погрузки.<br>\n';
                //jQuery('#first_city').addClass('form_error_input');
                jQuery('#first_city').css({'border': '2px solid #F00'});
            } else {
                jQuery('#first_city').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#datepicker2').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана дата выгрузки.<br>\n';
                jQuery('#datepicker2').css({'border': '2px solid #F00'});
            } else {
                jQuery('#datepicker2').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#second_city').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан населенный пункт выгрузки.<br>\n';
                jQuery('#second_city').css({'border': '2px solid #F00'});
            } else {
                jQuery('#second_city').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#sh_type').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан тип груза.<br>\n';
                jQuery('#sh_type').css({'border': '2px solid #F00'});
            } else {
                jQuery('#sh_type').css({'border': '1px solid #007FFF'});
            }
            
            if (jQuery('#sh_descr').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано описание груза.<br>\n';
                jQuery('#sh_descr').css({'border': '2px solid #F00'});
            } else {
                jQuery('#sh_descr').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#trans_type').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан тип транспортного средства.<br>\n';
                jQuery('#trans_type').css({'border': '2px solid #F00'});
            } else {
                jQuery('#trans_type').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#trans_count').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано количество машин.<br>\n';
                jQuery('#trans_count').css({'border': '2px solid #F00'});
            } else {
                jQuery('#trans_count').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#set_dim').prop('checked')) {
                if (jQuery('#sh_length').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана длина груза.<br>\n';
                    jQuery('#sh_length').css({'border': '2px solid #F00'});
                } else {
                    jQuery('#sh_length').css({'border': '1px solid #007FFF'});
                }

                if (jQuery('#sh_width').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана ширина груза.<br>\n';
                    jQuery('#sh_width').css({'border': '2px solid #F00'});
                } else {
                    jQuery('#sh_width').css({'border': '1px solid #007FFF'});
                }

                if (jQuery('#sh_height').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана высота груза.<br>\n';
                    jQuery('#sh_height').css({'border': '2px solid #F00'});
                } else {
                    jQuery('#sh_height').css({'border': '1px solid #007FFF'});
                }
            } else {
                jQuery('#sh_length, #sh_width, #sh_height').css({'border': '1px solid #007FFF'});
            }

            if (jQuery("#price_query").is(':checked')) {
                jQuery('#cost, #prepayment').css({'border': '1px solid #007FFF'});
                jQuery('#div_cash, #div_nocash, #div_way_ship, #div_way_debark, #div_soft, #div_way_prepay').css({'border': 'none'});
            } else {
                if (jQuery('#cost').val().length < 1) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана стоимость перевозки.<br>\n';
                    jQuery('#cost').css({'border': '2px solid #F00'});
                } else {
                    jQuery('#cost').css({'border': '1px solid #007FFF'});
                }

                if (jQuery("#way_prepay").is(':checked')) {
                    if (jQuery('#prepayment').val().length < 1) {
                        ErrorMsg2 = ErrorMsg2 + 'Не указан % предоплаты.<br>\n';
                        jQuery('#prepayment').css({'border': '2px solid #F00'});
                    } else {
                        jQuery('#prepayment').css({'border': '1px solid #007FFF'});
                    }
                } else {
                    jQuery('#prepayment').css({'border': '1px solid #007FFF'});
                }
                
                // Проверка правильности указания переключателей
                if ((jQuery("#cash").is(':checked') || jQuery("#nocash").is(':checked') || jQuery("#way_ship").is(':checked') || jQuery("#way_debark").is(':checked') || jQuery("#soft").is(':checked') || jQuery("#way_prepay").is(':checked'))) {
                    jQuery('#div_cash, #div_nocash, #div_way_ship, #div_way_debark, #div_soft, #div_way_prepay').css({'border': 'none'});
                } else {
                    ErrorMsg2 = ErrorMsg2 + 'Не выбрана форма расчета.<br>\n';
                    jQuery('#div_cash, #div_nocash, #div_way_ship, #div_way_debark, #div_soft, #div_way_prepay').css({'border': '1px solid #F00'});
                }
            }

            if (ErrorMsg2.length > 0) {
                jQuery("#form_error_message").html(ErrorMsg1 + ErrorMsg2 + ErrorMsg3);
                jQuery("#form_error_message").show();
                return false;
            } else {
                return true;
            }
        }

        /*
         * Функция, вызываемая после загрузки страницы
         */
        jQuery(document).ready(function(){
            //CITY_NAMES = ['Киев', 'Житомир', 'Чоп'];
            //CITY_IDS = [98, 97, 96];
            initCitiesTable();
            
            jQuery('#totalDistance').append('<input type="text" id="sh_distance" name="sh_distance" size="" value="<?php echo_val('sh_distance'); ?>" maxlength = "255" readonly="true" style="width: 50px;"><div class="post-input">км</div>&nbsp;&nbsp;');
            
            
            //jQuery('#show_dist_link').hide();

            jQuery('#set_dim').click(function() {
                    onSetDim(this.checked);
            });
            
            jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
            jQuery( "#datepicker1" ).datepicker({ 
                dateFormat: "dd.mm.yy",
                minDate: new Date(),
                selectOtherMonths: true,
                showOtherMonths: true,
                onSelect: function(dateText, inst) { onDatePicker1Change(dateText, inst); }
            });
            jQuery( "#datepicker2" ).datepicker({ 
                dateFormat: "dd.mm.yy",
                selectOtherMonths: true,
                showOtherMonths: true
            });
            //jQuery("#datepicker2").attr("disabled", "disabled");
            jQuery('#datepicker1, #datepicker2').css({'cursor': 'pointer'});

            onSetDim(jQuery('#set_dim').prop('checked'));
            jQuery("[name=trans_type]").change(function() { onTransTypeChange(); });
            jQuery("[name=trans_type]").keyup(function() { onTransTypeChange(); });
            jQuery("#cost").change(function() { onCostChange(); });
            jQuery("#cost").keyup(function() { onCostChange(); });
            
            <?php if ($errors != null && count($errors) > 0) {
                $err_str = '';
		foreach ($errors as $error) {
                    $err_str .= $error.'</br>';
                }
                ?>
                jQuery("#form_error_message").html("<?php echo $err_str; ?>");
                jQuery("#form_error_message").show();
            <?php } ?>

            //updateCostValue();
            onTransTypeChange();
            //onWayPrepayChange();
           // onCityChange();
            onCostChange();

            jQuery('#first_city, #second_city').on('input',function() { 		
                                        delay(function(){
                                                onCityChange();
                                          //alert('Time elapsed!');
                                        }, 1000 );/*onCityChange();*/ });

            jQuery("#sh_length, #sh_width, #sh_height, #cost, #sh_weight, #trans_count").bind("change keyup input click", function() {
                if (this.value.match(/[^0-9.]/g)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                }
            });
            jQuery("#sh_length, #sh_width, #sh_height").change(function() { onVolumeCalculate(); });
            jQuery("#price_query").change(function() { onPriceQueryChange(); });
            jQuery("#way_prepay").change(function(eventObject) { onWayPrepayChange(eventObject); });
            
            jQuery("#form_button1").click(function(event) { 
                event.preventDefault();
                var flag = onFormValidate();
                if (flag) {
                    jQuery("form[id='form_shipment']").submit();
                }
            });

            jQuery("#form_button2").click(function(event) { 
                event.preventDefault();
                resetForm("form[id='form_shipment']");
                onSetDim(jQuery('#set_dim').prop('checked'));
            });

            jQuery("#form_button3").click(function(event) { 
                event.preventDefault();
                location.href = "/account/profile/";
            });
        });
    </script>
<?php
}

function tzs_edit_shipment($id) {
        $input_city = isset($_POST['input_city']) ? $_POST['input_city'] : array();
        $path_segment_distance = get_param('path_segment_distance');
                
        $sh_active = get_param_def('sh_active', '0');
	$sh_date_from = get_param('sh_date_from');
	$sh_date_to = get_param('sh_date_to');
	$comment = get_param('comment');
        
        if (count($input_city) > 1) {
            $sh_city_from = $input_city[0];
            $sh_city_to = $input_city[count($input_city) - 1];
            $path_segment_cities = json_encode($input_city);
        } else {
            $sh_city_from = get_param('sh_city_from');
            $sh_city_to = get_param('sh_city_to');
            $path_segment_cities = '';
        }
	
	$sh_descr = get_param('sh_descr');
	$sh_weight = get_param_def('sh_weight', '0');
	$sh_volume = get_param_def('sh_volume', '0');
	$sh_type = get_param('sh_type');
	$trans_type = get_param('trans_type');
	$trans_count = get_param('trans_count');
	
	$set_dim = isset($_POST['set_dim']);
	$sh_length = get_param('sh_length');
	$sh_height = get_param('sh_height');
	$sh_width = get_param('sh_width');

        $cost = get_param_def('cost', '0');
        $price = get_param_def('price', '0');
        $cost_curr = get_param_def('cost_curr', '1');
        $prepayment = get_param('prepayment');

        $price_query = isset($_POST['price_query']) ? 1 : 0;
        $cash = isset($_POST['cash']) ? 1 : 0;
        $nocash = isset($_POST['nocash']) ? 1 : 0;
        $way_ship = isset($_POST['way_ship']) ? 1 : 0;
        $way_debark = isset($_POST['way_debark']) ? 1 : 0;
        $soft = isset($_POST['soft']) ? 1 : 0;
        $way_prepay = isset($_POST['way_prepay']) ? 1 : 0;
        
        $top_loading = isset($_POST['top_loading']) ? 1 : 0;
        $side_loading = isset($_POST['side_loading']) ? 1 : 0;
        $back_loading = isset($_POST['back_loading']) ? 1 : 0;
        $full_movable = isset($_POST['full_movable']) ? 1 : 0;
        $remove_cross = isset($_POST['remove_cross']) ? 1 : 0;
        $remove_racks = isset($_POST['remove_racks']) ? 1 : 0;
        $without_gate = isset($_POST['without_gate']) ? 1 : 0;
        
        
        // Контроль пересечения дат
        $sh_date_from_str = date("Ymd", strtotime($sh_date_from));
        $sh_date_to_str = date("Ymd", strtotime($sh_date_to));
        
	$sh_date_from = is_valid_date($sh_date_from);
	$sh_date_to = is_valid_date($sh_date_to);
        
        // Замена "," на точку "." в числах
        $sh_weight = str_replace(',', '.', $sh_weight);
        $sh_volume = str_replace(',', '.', $sh_volume);
        $sh_length = str_replace(',', '.', $sh_length);
        $sh_height = str_replace(',', '.', $sh_height);
        $sh_width = str_replace(',', '.', $sh_width);
        $cost = str_replace(',', '.', $cost);
        $price = str_replace(',', '.', $price);
        $prepayment = str_replace(',', '.', $prepayment);
	
	$errors = array();
	
        if (($price_query && !is_valid_num_zero($cost)) || (!$price_query && !is_valid_num($cost))) 
            array_push($errors, "Неверно задана стоимость.");
        
        if (($price_query && !is_valid_num_zero($price)) || (!$price_query && !is_valid_num($price))) 
            array_push($errors, "Неверно задана цена.");
        
        if (!is_valid_num($cost_curr) || !isset($GLOBALS['tzs_curr'][intval($cost_curr)]))
            array_push($errors, "Неверно задана валюта.");
		
        if ($way_prepay && (!is_valid_num($prepayment) || floatval($prepayment) > 100))
            array_push($errors, "Неверно задан размер предоплаты.");
                                
		
        if (!$price_query && !$cash && !$nocash && !$way_ship && !$way_debark && !$soft && !$way_prepay)
            array_push($errors, "Необходимо выбрать хотя бы один способ в блоке \"Форма расчета\".");
	
	if ($sh_date_from == null || $sh_date_to == null) {
		array_push($errors, "Неверный формат даты");
	}

        // Контроль пересечения дат
        if ($sh_date_to_str < $sh_date_from_str) {
            array_push($errors, "Дата выгрузки не может быть РАНЬШЕ даты погрузки.");
        }
	
        if (count($input_city) > 1) {
            for($i = 0; $i < count($input_city); $i++) {
                if (!is_valid_city($input_city[$i])) {
                    array_push($errors, "Укажите пункт маршрута № ".($i + 1));
                }
            }
        } else {
            if (!is_valid_city($sh_city_from)) {
                array_push($errors, "Неверный пункт погрузки");
            }

            if (!is_valid_city($sh_city_to)) {
                array_push($errors, "Неверный пункт разгрузки");
            }
        }
	
	if (strlen($sh_descr) < 2) {
		array_push($errors, "Введите описание груза");
	}
	
	if (!is_valid_num_zero($sh_weight)) {
		array_push($errors, "Неверно задан вес");
	}
	
	if (!is_valid_num_zero($sh_volume)) {
		array_push($errors, "Неверно задан объем");
	}
	
	if (strlen($trans_count) == 0) {
		$trans_count = '1';
	}
	if (!is_valid_num($trans_count)) {
		array_push($errors, "Неверно задано количество машин");
	}
	
	if (!is_numeric($sh_type) || intval($sh_type) < 1) {
		array_push($errors, "Неверно задан тип груза");
	}
	
	if (!is_numeric($trans_type) || intval($trans_type) < 0) {
		array_push($errors, "Неверно задан тип ТС");
	}
	
	if ($set_dim) {
		if (!is_valid_num($sh_length)) {
			array_push($errors, "Неверно задана длина груза");
		}
		if (!is_valid_num($sh_width)) {
			array_push($errors, "Неверно задана ширина груза");
		}
		if (!is_valid_num($sh_height)) {
			array_push($errors, "Неверно задана высота груза");
		}
	} else {
		$sh_length = '0';
		$sh_width = '0';
		$sh_height = '0';
	}
	
	$user_id = get_current_user_id();
	
	$from_info = null;
	$to_info = null;
	if (count($errors) == 0) {
            if (count($input_city) > 1) {
                for($i = 0; $i < count($input_city); $i++) {
                    $city_info = tzs_yahoo_convert($input_city[$i]);
                    if (isset($city_info["error"])) {
                        array_push($errors, "Не удалось распознать населенный пункт маршрута № ".($i + 1).": ".$city_info["error"]);
                    }

                    if ($i == 0) {
                        $from_info = $city_info;
                    }

                    if ($i == (count($input_city) - 1)) {
                        $to_info = $city_info;
                    }
                }
            } else {
		$from_info = tzs_yahoo_convert($sh_city_from);
		if (isset($from_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт погрузки: ".$from_info["error"]);
		}
		$to_info = tzs_yahoo_convert($sh_city_to);
		if (isset($to_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт выгрузки: ".$to_info["error"]);
		}
            }
	}
	
	if (count($errors) > 0) {
		tzs_print_shipment_form($errors, $id > 0);
	} else {
		global $wpdb;
	
		$sh_date_from = date('Y-m-d', mktime(0, 0, 0, $sh_date_from['month'], $sh_date_from['day'], $sh_date_from['year']));
		$sh_date_to = date('Y-m-d', mktime(0, 0, 0, $sh_date_to['month'], $sh_date_to['day'], $sh_date_to['year']));
		
		$temp = $from_info['city_id'];
		$sql = "SELECT lat,lng FROM ".TZS_CITIES_TABLE." WHERE city_id=$temp;";
		$row1 = $wpdb->get_row($sql);

		$temp = $to_info['city_id'];
		$sql = "SELECT lat,lng FROM ".TZS_CITIES_TABLE." WHERE city_id=$temp;";
		$row2 = $wpdb->get_row($sql);

		//print("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$lng1,$lat1&destinations=$lng2,$lat2&language=en-EN&sensor=false");
		//print("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$row1->lng,$row1->lat&destinations=$row2->lng,$row2->lat&language=ru-RU&sensor=false");echo '<br>';
/* 		$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$row1->lat,$row1->lng&destinations=$row2->lat,$row2->lng&language=ru-RU&sensor=false");
		$data = json_decode($data);
		$dis = $data->rows[0]->elements[0]->distance->value / 1000; */
		/*
		Не срабатывает это:
		$sh_distance = get_param('sh_distance');
		*/
		
                // А теперь на срабатывает это
		//$sh_distance = get_param('length');
		$sh_distance = get_param('sh_distance');
		//echo 'Дистанция - '+$sh_distance+'<br>';
		
		if ($id == 0) {
			$sql = $wpdb->prepare("INSERT INTO ".TZS_SHIPMENT_TABLE.
				" (time, last_edited, user_id, sh_date_from, sh_date_to, sh_city_from, sh_city_to, sh_descr, sh_weight, sh_volume, sh_length, sh_height, sh_width, trans_count, trans_type, sh_type, active, comment, distance,".
                                " from_cid, from_rid, from_sid, to_cid, to_rid, to_sid, price, price_val, cost, cash, nocash, way_ship, way_debark, soft, way_prepay, prepayment, price_query,".
                                " top_loading, side_loading, back_loading, full_movable, remove_cross, remove_racks, without_gate, path_segment_cities, path_segment_distances)".
				" VALUES (now(), NULL, %d, %s, %s, %s, %s, %s, %f, %f, %f, %f, %f, %d, %d, %d, %d, %s, %d, %d, %d, %d, %d, %d, %d, %f, %d, %f, %d, %d, %d, %d, %d, %d, %f, %d,".
                                " %d, %d, %d, %d, %d, %d, %d, %s, %s);",
				$user_id, $sh_date_from, $sh_date_to, stripslashes_deep($sh_city_from), stripslashes_deep($sh_city_to),
				stripslashes_deep($sh_descr), floatval($sh_weight), floatval($sh_volume), floatval($sh_length),
				floatval($sh_height), floatval($sh_width), intval($trans_count), intval($trans_type), intval($sh_type), intval($sh_active), stripslashes_deep($comment), intval($sh_distance),
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                floatval($price), intval($cost_curr), floatval($cost), intval($cash), intval($nocash), intval($way_ship), intval($way_debark), intval($soft), intval($way_prepay), floatval($prepayment), intval($price_query),
                                intval($top_loading), intval($side_loading), intval($back_loading), intval($full_movable), intval($remove_cross), intval($remove_racks), intval($without_gate), stripslashes_deep($path_segment_cities), stripslashes_deep($path_segment_distance));
		
			if (false === $wpdb->query($sql)) {
				array_push($errors, "Не удалось опубликовать Ваш груз. Свяжитесь, пожалуйста, с администрацией сайта");
				array_push($errors, $wpdb->last_error);
				//$errors = array_merge($errors, $dis['errors']);
				tzs_print_shipment_form($errors, false);
			} else {
				//print_errors($dis['errors']);
				echo "Ваш груз опубликован!";
				echo "<br/>";
				//echo '<pre>'.print_r($_POST,true).'</pre>';
				echo '<a href="/view-shipment/?id='.tzs_find_latest_shipment_rec().'&spis=new">Просмотреть груз</a>';
                                $new_url = get_site_url().'/my-shipments';
                                echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
			}
		} else {
			$sql = $wpdb->prepare("UPDATE ".TZS_SHIPMENT_TABLE." SET ".
				" last_edited=now(), sh_date_from=%s, sh_date_to=%s, sh_city_from=%s, sh_city_to=%s, sh_descr=%s, sh_weight=%f, sh_volume=%f, sh_length=%f, sh_height=%f, sh_width=%f, trans_count=%d, trans_type=%d, sh_type=%d, active=%d, comment=%s, distance=%d, ".
				" from_cid=%d,from_rid=%d,from_sid=%d,to_cid=%d,to_rid=%d,to_sid=%d, price=%f, price_val=%d,".
                                " cost=%f, cash=%d, nocash=%d, way_ship=%d, way_debark=%d, soft=%d, way_prepay=%d, prepayment=%f, price_query=%d,".
                                " top_loading=%d, side_loading=%d, back_loading=%d, full_movable=%d, remove_cross=%d, remove_racks=%d, without_gate=%d, path_segment_cities=%s, path_segment_distances=%s".
				" WHERE id=%d AND user_id=%d;", $sh_date_from, $sh_date_to, stripslashes_deep($sh_city_from),
				stripslashes_deep($sh_city_to), stripslashes_deep($sh_descr), floatval($sh_weight), floatval($sh_volume),
				floatval($sh_length), floatval($sh_height), floatval($sh_width), intval($trans_count), intval($trans_type), intval($sh_type), intval($sh_active), stripslashes_deep($comment), round($dis['distance'] / 1000),
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                floatval($price), intval($cost_curr), floatval($cost), intval($cash), intval($nocash), intval($way_ship), intval($way_debark), intval($soft), intval($way_prepay), floatval($prepayment), intval($price_query),
                                intval($top_loading), intval($side_loading), intval($back_loading), intval($full_movable), intval($remove_cross), intval($remove_racks), intval($without_gate), stripslashes_deep($path_segment_cities), stripslashes_deep($path_segment_distance),
				$id, $user_id);
			
			if (false === $wpdb->query($sql)) {
				array_push($errors, "Не удалось изменить Ваш груз. Свяжитесь, пожалуйста, с администрацией сайта");
				array_push($errors, $wpdb->last_error);
				$errors = array_merge($errors, $dis['errors']);
				tzs_print_shipment_form($errors, true);
			} else {
				//print_errors($dis['errors']);
				echo "Ваш груз изменен";
				echo "<br/>";
				echo '<a href="/view-shipment/?id='.$id.'&spis=new">Просмотреть груз</a>';
                                $new_url = get_site_url().'/my-shipments';
                                echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
			}
		}
	}
}

function tzs_front_end_del_shipment_handler($attrs) {
	ob_start();
	
	$user_id = get_current_user_id();
	$sh_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ($sh_id <= 0) {
		print_error('Груз не найден');
	} else {
		global $wpdb;
		$sql = "DELETE FROM ".TZS_SHIPMENT_TABLE." WHERE id=$sh_id AND user_id=$user_id;";
		if (false === $wpdb->query($sql)) {
			$errors = array();
			array_push($errors, "Не удалось удалить Ваш груз. Свяжитесь, пожалуйста, с администрацией сайта");
			array_push($errors, $wpdb->last_error);
			print_errors($errors);
		} else {
			echo "Груз удален";
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

function tzs_front_end_edit_shipment_handler($atts) {
	ob_start();
	
	$user_id = get_current_user_id();
	$sh_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ($sh_id <= 0) {
		print_error('Груз не найден');
	} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'editshipment' && ($_POST['formName'] == 'shipment')) {
		$id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
		tzs_edit_shipment($id);
	} else {
		global $wpdb;
		$sql = "SELECT * FROM ".TZS_SHIPMENT_TABLE." WHERE id=$sh_id AND user_id=$user_id;";	
		$row = $wpdb->get_row($sql);
		
		if (count($row) == 0 && $wpdb->last_error != null) {
			print_error('Не удалось отобразить информацию о грузе. Свяжитесь, пожалуйста, с администрацией сайта');
		} else if ($row == null) {
			print_error('Груз не найден');
		} else {
                    $sql_flag1 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->from_cid;
                    $row1 = $wpdb->get_row($sql_flag1);
                    $sql_flag2 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->to_cid;
                    $row2 = $wpdb->get_row($sql_flag2);
		
                    $_POST['from_code'] = "/wp-content/plugins/tzs/assets/images/flags/".strtolower($row1->code).'.png';
                    $_POST['to_code'] = "/wp-content/plugins/tzs/assets/images/flags/".strtolower($row2->code).'.png';			
                    $_POST['sh_date_from'] = date("d.m.Y", strtotime($row->sh_date_from));
                    $_POST['sh_date_to'] = date("d.m.Y", strtotime($row->sh_date_to));
                    $_POST['sh_city_from'] = $row->sh_city_from;
                    $_POST['sh_city_to'] = $row->sh_city_to;
                    $_POST['sh_descr'] = $row->sh_descr;
                    $_POST['comment'] = $row->comment;

                    if ($row->sh_weight > 0)
                            $_POST['sh_weight'] = ''.remove_decimal_part($row->sh_weight);
                    if ($row->sh_volume > 0)
                            $_POST['sh_volume'] = ''.remove_decimal_part($row->sh_volume);
                    $_POST['sh_type'] = ''.$row->sh_type;
                    $_POST['trans_type'] = ''.$row->trans_type;
                    $_POST['trans_count'] = ''.$row->trans_count;

                    if ($row->sh_length > 0 || $row->sh_height > 0 || $row->sh_width > 0) {
                        $_POST['set_dim'] = '';

                        if ($row->sh_width > 0) 
                            $_POST['sh_width'] = ''.remove_decimal_part($row->sh_width);
                        if ($row->sh_height > 0) 
                            $_POST['sh_height'] = ''.remove_decimal_part($row->sh_height);
                        if ($row->sh_length > 0) 
                            $_POST['sh_length'] = ''.remove_decimal_part($row->sh_length);
                    }

                    if ($row->cost > 0)
                            $_POST['cost'] = ''.remove_decimal_part($row->cost);
                    if ($row->price > 0)
                            $_POST['price'] = ''.remove_decimal_part($row->price);
                    if ($row->price_val > 0)
                            $_POST['cost_curr'] = ''.remove_decimal_part($row->price_val);
                    if ($row->prepayment > 0)
                            $_POST['prepayment'] = ''.remove_decimal_part($row->prepayment);
                    
                    if ($row->cash > 0)
                            $_POST['cash'] = 'on';
                    if ($row->nocash > 0)
                            $_POST['nocash'] = 'on';
                    if ($row->way_ship > 0)
                            $_POST['way_ship'] = 'on';
                    if ($row->way_debark > 0)
                            $_POST['way_debark'] = 'on';
                    if ($row->soft > 0)
                            $_POST['soft'] = 'on';
                    if ($row->way_prepay > 0)
                            $_POST['way_prepay'] = 'on';
                    if ($row->price_query > 0)
                            $_POST['price_query'] = 'on';
                    
                    if ($row->top_loading > 0)
                            $_POST['top_loading'] = 'on';
                    if ($row->side_loading > 0)
                            $_POST['side_loading'] = 'on';
                    if ($row->back_loading > 0)
                            $_POST['back_loading'] = 'on';
                    if ($row->full_movable > 0)
                            $_POST['full_movable'] = 'on';
                    if ($row->remove_cross > 0)
                            $_POST['remove_cross'] = 'on';
                    if ($row->remove_racks > 0)
                            $_POST['remove_racks'] = 'on';
                    if ($row->without_gate > 0)
                            $_POST['without_gate'] = 'on';

                    $_POST['sh_distance'] = $row->distance;
                    $_POST['id'] = ''.$row->id;
                    $_POST['sh_active'] = $row->active;
                    $_POST['path_segment_cities'] = $row->path_segment_cities;
                    $_POST['path_segment_distances'] = $row->path_segment_distances;

                    tzs_print_shipment_form(null, true);
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

function tzs_front_end_shipment_handler($atts) {
	ob_start();
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'addshipment' && ($_POST['formName'] == 'shipment')) {
		tzs_edit_shipment(0);
	} else {
		tzs_print_shipment_form(null);
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

?>
