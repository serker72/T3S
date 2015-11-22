<?php

include_once(TZS_PLUGIN_DIR.'/functions/tzs.truck.functions.php');

function tzs_print_truck_form($errors, $edit=false) {
    $d = date("d.m.Y");

    //print_errors($errors);
    ?>

    <script src="/wp-content/plugins/tzs/assets/js/distance.js"></script>
    <script src="/wp-content/plugins/tzs/assets/js/autocomplete.js"></script>
    
    <div style="clear: both;"></div>
    
    <!-- test new form -->
<div class="form_wrapper">
    <form enctype="multipart/form-data" method="post" id="form_truck" class="" action="">
        
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
            <input type="text" id="datepicker1" name="tr_date_from" size="" value="<?php echo_val_def('tr_date_from', ''); ?>" placeholder="Дата погрузки" readonly="true">
        </div>
        <div class="span3">
            <input autocomplete="city" id="first_city" type="text" size="35" name="tr_city_from" value="<?php echo_val('tr_city_from'); ?>" autocomplete="on" placeholder="Населенный пункт погрузки">
	   </div>
        <div class="span1">
            <img id ="first_city_flag" src="<?php echo $edit ? echo_val('from_code') : "" ?>"  style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="">
        </div>
        <div class="span2">
            <input type="text" id="sh_distance" name="sh_distance" size="" value="<?php echo_val('sh_distance'); ?>" maxlength = "255" readonly="true" style="width: 50px;">&nbsp;&nbsp;км
            <input type="hidden" name="length" id="route-length">
        </div>
        <div id="div_tr_active" class="span3 input_select">
            <input type="hidden" name="tr_active" id="tr_active" value="1">
            <!--label for="tr_active">Статус</label-->
            <!--select id="tr_active" name="tr_active">
                <option value="1" <?php //if (isset($_POST["tr_active"]) && ($_POST["tr_active"] === 1)) echo 'selected="selected"'; ?> >Публикуемый</option>
                <option value="0" <?php //if (isset($_POST["tr_active"]) && ($_POST["tr_active"] === 0)) echo 'selected="selected"'; ?> >Архивный</option>
            </select-->
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
            <input type="text" id="datepicker2" name="tr_date_to" size="" value="<?php echo_val_def('tr_date_to', ''); ?>" placeholder="Дата выгрузки" readonly="true">
        </div>
        <div id="div_second_city" class="span3 form-group">
            <input autocomplete="city" id="second_city" type="text" size="35" name="tr_city_to" value="<?php echo_val('tr_city_to'); ?>" autocomplete="on" placeholder="Населенный пункт выгрузки">
        </div>
        <div class="span1">
            <img id ="second_city_flag" src="<?php echo $edit ? echo_val('to_code') : "" ?>" style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="">
        </div>
        <div class="span2">
            <a id="show_dist_link" href="javascript:showDistanceDialog();">см. карту</a>
        </div>
        <div class="span3">
        </div>
    </div>
                    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
            <input type="text" id="sh_descr" name="sh_descr" size="" value="<?php echo_val('sh_descr'); ?>" maxlength = "255" placeholder="Желаемый груз">
        </div>
        <div class="span5">
            <input type="text" size="" name="comment" value="<?php echo_val('comment'); ?>" maxlength = "255" placeholder="Комментарий" style="width: 350px;">
        </div>
        <div class="span4 chekbox"><!-- style="text-align: right;"-->
            <input type="checkbox" name="set_dim" id="set_dim" <?php if (isset($_POST['set_dim'])) echo 'checked="checked"'; ?>>&nbsp;<label for="set_dim">Указать габариты транспортного средства (м):</label>
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="" class="span3">
            <select id="trans_type" name="trans_type">
            <?php
                tzs_print_array_options($GLOBALS['tzs_tr_types'], '', 'trans_type', 'Тип транспортного средства');
            ?>
            </select>
        </div>
        <div class="span1">
            <span><img id="trans_type_img" src="" alt=""></img></span>&nbsp;&nbsp;
        </div>
        <div class="span2">
            <label for="trans_count">Кол-во машин:</label>&nbsp;
            <input type="text" size="5" id="trans_count" name="trans_count" value="<?php echo_val('trans_count'); ?>" maxlength = "2" placeholder = "0" style="width: 25px;">
        </div>
        <div class="span3">
            <label for="tr_weight">Вес груза:</label>&nbsp;
            <input type="text" id="tr_weight" name="tr_weight" value="<?php echo_val('tr_weight'); ?>" maxlength = "5" style="width: 50px;">&nbsp;т
        </div>
        <div class="span3">
            <input type="text" name="tr_length" id="tr_length" value="<?php echo_val('tr_length'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Длина" style="width: 50px;">&nbsp;&nbsp;
            <input type="text" name="tr_width" id="tr_width" value="<?php echo_val('tr_width'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Ширина" style="width: 50px;">&nbsp;&nbsp;
            <input type="text" name="tr_height" id="tr_height" value="<?php echo_val('tr_height'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Высота" style="width: 50px;">
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span4">
            <label for="cost">Стоимость перевозки:</label>&nbsp;
            <input type="text" id="cost" name="cost" value="<?php echo_val('cost'); ?>" size="10" style="width: 100px;">
            <span>грн</span>
            <input type="hidden" name="cost_curr" id="cost_curr" value="1">
        </div>
        <div class="span4">
            <label for="price">Цена&nbsp;=</label>&nbsp;
            <input type="text" id="price" name="price" value="<?php echo_val('price'); ?>" size="10" readonly="true" style="width: 100px;">
            &nbsp;грн/км
        </div>
        <div class="span1">
        </div>
        <div class="span3"><!-- style="text-align: right; float: right;"-->
            <label for="tr_volume">Объем груза&nbsp;=</label>&nbsp;
            <input type="text" id="tr_volume" name="tr_volume" value="<?php echo_val('tr_volume'); ?>" readonly="true" style="width: 80px;">
            &nbsp;м<sup>3</sup>
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; margin-top: 10px;">
        <div class="span8">
            <label for="">Форма расчета (можно указать несколько способов одновременно):</label>
        </div>
        <div class="span4" style="text-align: right;">
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%;">
        <div id="div_cash" class="span2 chekbox">
            <input type="checkbox" id="cash" name="cash" <?php echo isset($_POST['cash']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="cash">Наличная</label>
        </div>
        <div id="div_nocash" class="span2 chekbox">
            <input type="checkbox" id="nocash" name="nocash" <?php echo isset($_POST['nocash']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="nocash">Безналичная</label>
        </div>
        <div id="div_way_ship" class="span2 chekbox">
            <input type="checkbox" id="way_ship" name="way_ship" <?php echo isset($_POST['way_ship']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="way_ship">При погрузке</label>
        </div>
        <div id="div_way_debark" class="span2 chekbox">
            <input type="checkbox" id="way_debark" name="way_debark" <?php echo isset($_POST['way_debark']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="way_debark">При выгрузке</label>
        </div>
        <div id="div_soft" class="span1 chekbox">
            <input type="checkbox" id="soft" name="soft" <?php echo isset($_POST['soft']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="soft">Софт</label>
        </div>
        <div id="div_way_prepay" class="span2 chekbox" style="text-align: right;">
            <input type="checkbox" id="way_prepay" name="way_prepay" <?php echo isset($_POST['way_prepay']) ? 'checked="checked"' : ''; ?> >&nbsp;<label for="way_prepay">Предоплата</label>
        </div>
        <div id="div_prepayment" class="span1">
            <input type="text" id="prepayment" name="prepayment" value="<?php echo_val('prepayment'); ?>" size="5" placeholder = "0" style="width: 20px;">&nbsp;%
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span8">
            <div class="span12 chekbox" style="margin-bottom: 20px;">
                <input type="checkbox" id="price_query" name="price_query" <?php isset($_POST['price_query']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="price_query">Не указывать стоимость (цена договорная)</label>
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
		<input type="hidden" name="action" value="edittruck"/>
		<input type="hidden" name="id" value="<?php echo_val('id'); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="addtruck"/>
	<?php } ?>
	<input type="hidden" name="formName" value="truck" />
    </form>
</div>
    <div class="clearfix">&nbsp;</div>
    
    <!-- test new form END -->
    <script>
     jQuery('#trans_type').on('change', function() {
            jQuery('#div_trans_type').addClass("change");    
     });
    </script>
	
    <script>
        tzs_tr2_types = [];
        <?php
            foreach ($GLOBALS['tzs_tr2_types'] as $key => $val) {
                echo "tzs_tr2_types[$key] = '$val[1]';\n";
            }
        ?>
        
        
        // Расчет расстояния между пунктами
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
                jQuery("#tr_length, #tr_width, #tr_height, #tr_volume").removeAttr("disabled");
                //jQuery("#tr_length, #tr_width, #tr_height").attr('required', 'required');
            } else {
                //jQuery("#tr_length, #tr_width, #tr_height").removeAttr('required');
                jQuery("#tr_length, #tr_width, #tr_height, #tr_volume").attr("disabled", "disabled");
                jQuery("#tr_length, #tr_width, #tr_height, #tr_volume").attr('value', '');
            }
        }

        // Вывод карты
        function showDistanceDialog() {
            if ((jQuery('#first_city').val().length > 0) && (jQuery('#second_city').val().length > 0)) {
                //displayDistance([jQuery('input[name=tr_city_from]').val(), jQuery('input[name=tr_city_to]').val()], null);
                displayDistance([jQuery('#first_city').val(), jQuery('#second_city').val()], null);
            } else {

            }
        }

        // Изменение поля "Тип транспорта"
        function onTransTypeChange() {
            jQuery('#trans_type').addClass("change");
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
                jQuery("[name=price]").attr("disabled", "disabled");
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
                jQuery("[name=price]").removeAttr("disabled");
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
        function onWayPrepayChange() {
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
            if ((jQuery('#tr_length').val().length > 0) && (jQuery('#tr_width').val().length > 0) && (jQuery('#tr_height').val().length > 0)) {
                var vol = jQuery('#tr_length').val() * jQuery('#tr_width').val() * jQuery('#tr_height').val();
                jQuery('#tr_volume').attr('value', vol);
            } else {
                jQuery('#tr_volume').attr('value', '');
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
            //var ErrorMsg1 = 'Список ошибок:<ul>';
            var ErrorMsg1 = '<p>';
            var ErrorMsg2 = '';
            var ErrorMsg3 = '</p>';

            if (jQuery('#datepicker1').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана дата погрузки.<br>\n';
                jQuery('#datepicker1').css({'border': '1px solid #F00'});
            } else {
                jQuery('#datepicker1').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#first_city').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан населенный пункт погрузки.<br>\n';
                //jQuery('#first_city').addClass('form_error_input');
                jQuery('#first_city').css({'border': '1px solid #F00'});
            } else {
                jQuery('#first_city').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#datepicker2').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана дата выгрузки.<br>\n';
                jQuery('#datepicker2').css({'border': '1px solid #F00'});
            } else {
                jQuery('#datepicker2').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#second_city').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан населенный пункт выгрузки.<br>\n';
                jQuery('#second_city').css({'border': '1px solid #F00'});
            } else {
                jQuery('#second_city').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#tr_type').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан тип груза.<br>\n';
                jQuery('#tr_type').css({'border': '1px solid #F00'});
            } else {
                jQuery('#tr_type').css({'border': '1px solid #007FFF'});
            }
            
            if (jQuery('#sh_descr').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано описание груза.<br>\n';
                jQuery('#sh_descr').css({'border': '1px solid #F00'});
            } else {
                jQuery('#sh_descr').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#trans_type').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан тип транспортного средства.<br>\n';
                jQuery('#trans_type').css({'border': '1px solid #F00'});
            } else {
                jQuery('#trans_type').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#trans_count').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано количество машин.<br>\n';
                jQuery('#trans_count').css({'border': '1px solid #F00'});
            } else {
                jQuery('#trans_count').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#set_dim').prop('checked')) {
                if (jQuery('#tr_length').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана длина груза.<br>\n';
                    jQuery('#tr_length').css({'border': '1px solid #F00'});
                } else {
                    jQuery('#tr_length').css({'border': '1px solid #007FFF'});
                }

                if (jQuery('#tr_width').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана ширина груза.<br>\n';
                    jQuery('#tr_width').css({'border': '1px solid #F00'});
                } else {
                    jQuery('#tr_width').css({'border': '1px solid #007FFF'});
                }

                if (jQuery('#tr_height').val().length == 0) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана высота груза.<br>\n';
                    jQuery('#tr_height').css({'border': '1px solid #F00'});
                } else {
                    jQuery('#tr_height').css({'border': '1px solid #007FFF'});
                }
            } else {
                jQuery('#tr_length, #tr_width, #tr_height').css({'border': '1px solid #007FFF'});
            }

            if (jQuery("#price_query").is(':checked')) {
                jQuery('#cost, #prepayment').css({'border': '1px solid #007FFF'});
                jQuery('#div_cash, #div_nocash, #div_way_ship, #div_way_debark, #div_soft, #div_way_prepay').css({'border': 'none'});
            } else {
                if (jQuery('#cost').val().length < 1) {
                    ErrorMsg2 = ErrorMsg2 + 'Не указана стоимость перевозки.<br>\n';
                    jQuery('#cost').css({'border': '1px solid #F00'});
                } else {
                    jQuery('#cost').css({'border': '1px solid #007FFF'});
                }

                if (jQuery("#way_prepay").is(':checked')) {
                    if (jQuery('#prepayment').val().length < 1) {
                        ErrorMsg2 = ErrorMsg2 + 'Не указан % предоплаты.<br>\n';
                        jQuery('#prepayment').css({'border': '1px solid #F00'});
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
            jQuery('#show_dist_link').hide();

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

            jQuery("#tr_length, #tr_width, #tr_height, #cost, #tr_weight, #trans_count").bind("change keyup input click", function() {
                if (this.value.match(/[^0-9.]/g)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                }
            });
            jQuery("#tr_length, #tr_width, #tr_height").change(function() { onVolumeCalculate(); });
            jQuery("#price_query").change(function() { onPriceQueryChange(); });
            jQuery("#way_prepay").change(function() { onWayPrepayChange(); });

            jQuery("#form_button1").click(function(event) { 
                event.preventDefault();
                var flag = onFormValidate();
                if (flag) {
                    jQuery("form[id='form_truck']").submit();
                }
            });

            jQuery("#form_button2").click(function(event) { 
                event.preventDefault();
                resetForm("form[id='form_truck']");
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

function tzs_edit_truck($id) {
        $tr_active = get_param_def('tr_active', '0');
	$tr_date_from = get_param('tr_date_from');
	$tr_date_to = get_param('tr_date_to');
	$tr_city_from = get_param('tr_city_from');
	$tr_city_to = get_param('tr_city_to');
	$comment = get_param('comment');
        $sh_descr = get_param('sh_descr');
	
	$tr_weight = get_param_def('tr_weight','0');
	$tr_volume = get_param_def('tr_volume','0');
	$trans_type = get_param('trans_type');
	$tr_type = get_param_def('tr_type', '0');
	$trans_count = get_param('trans_count');
	
	$set_dim = isset($_POST['set_dim']);
	$tr_length = get_param('tr_length');
	$tr_height = get_param('tr_height');
	$tr_width = get_param('tr_width');

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
	
        // Контроль пересечения дат
        $tr_date_from_str = date("Ymd", strtotime($tr_date_from));
        $tr_date_to_str = date("Ymd", strtotime($tr_date_to));
        
        
	$tr_date_from = is_valid_date($tr_date_from);
	$tr_date_to = is_valid_date($tr_date_to);
        
        // Замена "," на точку "." в числах
        $tr_weight = str_replace(',', '.', $tr_weight);
        $tr_volume = str_replace(',', '.', $tr_volume);
        $tr_length = str_replace(',', '.', $tr_length);
        $tr_height = str_replace(',', '.', $tr_height);
        $tr_width = str_replace(',', '.', $tr_width);
        $cost = str_replace(',', '.', $cost);
        $price = str_replace(',', '.', $price);
        $prepayment = str_replace(',', '.', $prepayment);
	
	$errors = array();
	
        if (($price_query && !is_valid_num_zero($cost)) || (!$price_query && !is_valid_num($cost))) 
            array_push($errors, "Неверно задана стоимость");
        
        if (($price_query && !is_valid_num_zero($price)) || (!$price_query && !is_valid_num($price))) 
            array_push($errors, "Неверно задана цена");
        
        if (!is_valid_num($cost_curr) || !isset($GLOBALS['tzs_curr'][intval($cost_curr)]))
            array_push($errors, "Неверно задана валюта");
		
        if ($way_prepay && (!is_valid_num($prepayment) || floatval($prepayment) > 100))
            array_push($errors, "Неверно задан размер предоплаты");
                                
		
        if (!$price_query && !$cash && !$nocash && !way_ship && !way_debark)
            array_push($errors, "Необходимо выбрать хотя бы один способ в блоке \"Форма расчета\".");
	
	if ($tr_date_from == null || $tr_date_to == null) {
		array_push($errors, "Неверный формат даты");
	}

        // Контроль пересечения дат
        if ($tr_date_to_str < $tr_date_from_str) {
            array_push($errors, "Дата выгрузки не может быть РАНЬШЕ даты погрузки");
        }
        
	if (!is_valid_city($tr_city_from)) {
		array_push($errors, "Неверный пункт погрузки");
	}
	
	if (!is_valid_city($tr_city_to)) {
		array_push($errors, "Неверный пункт разгрузки");
	}
	
	if (!is_valid_num_zero($tr_weight)) {
		array_push($errors, "Неверно задан вес");
	}
	
	if (!is_valid_num_zero($tr_volume)) {
		array_push($errors, "Неверно задан объем");
	}
	
	if (strlen($trans_count) == 0) {
		$trans_count = '1';
	}
	if (!is_valid_num($trans_count)) {
		array_push($errors, "Неверно задано количество машин");
	}
	
	if (!is_numeric($trans_type) || intval($trans_type) < 1) {
		array_push($errors, "Неверно задан тип транспортного средства");
	}
	
	if (!is_numeric($tr_active) || intval($tr_active) < 0) {
		array_push($errors, "Неверно задан статус заявки");
	}
                
	if (!is_numeric($tr_type) || intval($tr_type) < 0 || intval($tr_type) > 3) {
		array_push($errors, "Неверно задан тип");
	}
	
	if ($set_dim) {
		if (!is_valid_num($tr_length)) {
			array_push($errors, "Неверно задана длина транспортного средства");
		}
		if (!is_valid_num($tr_width)) {
			array_push($errors, "Неверно задана ширина транспортного средства");
		}
		if (!is_valid_num($tr_height)) {
			array_push($errors, "Неверно задана высота транспортного средства");
		}
	} else {
		$tr_length = '0';
		$tr_width = '0';
		$tr_height = '0';
	}
	
	$user_id = get_current_user_id();
	
	$from_info = null;
	$to_info = null;
	if (count($errors) == 0) {
		$from_info = tzs_yahoo_convert($tr_city_from);
		if (isset($from_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт погрузки: ".$from_info["error"]);
		}
		$to_info = tzs_yahoo_convert($tr_city_to);
		if (isset($to_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт выгрузки: ".$to_info["error"]);
		}
	}
	
	if (count($errors) > 0) {
		tzs_print_truck_form($errors, $id > 0);
	} else {
		global $wpdb;
	
		$tr_date_from = date('Y-m-d', mktime(0, 0, 0, $tr_date_from['month'], $tr_date_from['day'], $tr_date_from['year']));
		$tr_date_to = date('Y-m-d', mktime(0, 0, 0, $tr_date_to['month'], $tr_date_to['day'], $tr_date_to['year']));
		
		$temp = $from_info['city_id'];
		$sql = "SELECT lat,lng FROM ".TZS_CITIES_TABLE." WHERE city_id=$temp;";
		$row1 = $wpdb->get_row($sql);

		$temp = $to_info['city_id'];
		$sql = "SELECT lat,lng FROM ".TZS_CITIES_TABLE." WHERE city_id=$temp;";
		$row2 = $wpdb->get_row($sql);
                
		//$dis = tzs_calculate_distance(array($tr_city_from, $tr_city_to));
		//$dis = get_param('length');
		$sh_distance = get_param('sh_distance');
		
		
		if ($id == 0) {
			$sql = $wpdb->prepare("INSERT INTO ".TZS_TRUCK_TABLE.
				" (time, last_edited, user_id, tr_date_from, tr_date_to, tr_city_from, tr_city_to, tr_weight, tr_volume, tr_length, tr_height, tr_width, trans_count, trans_type, active, tr_type, comment, distance,from_cid,from_rid,from_sid,to_cid,to_rid,to_sid,price,price_val,sh_descr, cost, cash, nocash, way_ship, way_debark, soft, way_prepay, prepayment, price_query)".
				" VALUES (now(), NULL, %d, %s, %s, %s, %s, %f, %f, %f, %f, %f, %d, %d, %d, %d, %s, %d, %d, %d, %d, %d, %d, %d, %f, %d, %s, %f, %d, %d, %d, %d, %d, %d, %f, %d);",
				$user_id, $tr_date_from, $tr_date_to, stripslashes_deep($tr_city_from), stripslashes_deep($tr_city_to),
				floatval($tr_weight), floatval($tr_volume), floatval($tr_length),
				floatval($tr_height), floatval($tr_width), intval($trans_count), intval($trans_type), intval($tr_active), intval($tr_type),
				stripslashes_deep($comment), $sh_distance,
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                floatval($price), intval($cost_curr), stripslashes_deep($sh_descr), floatval($cost), intval($cash), intval($nocash), intval($way_ship), intval($way_debark), intval($soft), intval($way_prepay), floatval($prepayment), intval($price_query));
		
			if (false === $wpdb->query($sql)) {
				array_push($errors, "Не удалось опубликовать Ваш транспорт. Свяжитесь, пожалуйста, с администрацией сайта");
				array_push($errors, $wpdb->last_error);
			//	$errors = array_merge($errors, $dis['errors']);
				tzs_print_truck_form($errors, false);
			} else {
			//	print_errors($dis['errors']);
				echo "Ваш транспорт опубликован!";
				echo "<br/>";
				echo '<a href="/view-truck/?id='.tzs_find_latest_truck_rec().'&spis=new">Просмотреть транспорт</a>';
                                $new_url = get_site_url().'/my-trucks';
                                echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
			}
		} else {
			$sql = $wpdb->prepare("UPDATE ".TZS_TRUCK_TABLE." SET ".
				" last_edited=now(), tr_date_from=%s, tr_date_to=%s, tr_city_from=%s, tr_city_to=%s, tr_weight=%f, tr_volume=%f,".
				" tr_length=%f, tr_height=%f, tr_width=%f, trans_count=%d, trans_type=%d, tr_type=%d, comment=%s, distance=%d, ".
				" from_cid=%d,from_rid=%d,from_sid=%d,to_cid=%d,to_rid=%d,to_sid=%d, active=%d, price=%f, price_val=%d, sh_descr=%s,".
                                " cost=%f, cash=%d, nocash=%d, way_ship=%d, way_debark=%d, soft=%d, way_prepay=%d, prepayment=%f, price_query=%d".
				" WHERE id=%d AND user_id=%d;", $tr_date_from, $tr_date_to, stripslashes_deep($tr_city_from),
				stripslashes_deep($tr_city_to), floatval($tr_weight), floatval($tr_volume),
				floatval($tr_length), floatval($tr_height), floatval($tr_width), intval($trans_count), intval($trans_type),
				intval($tr_type), stripslashes_deep($comment), $dis,
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                intval($tr_active), floatval($price), intval($cost_curr), stripslashes_deep($sh_descr),
                                floatval($price), intval($cost_curr), floatval($cost), intval($cash), intval($nocash), intval($way_ship), intval($way_debark), intval($soft), intval($way_prepay), floatval($prepayment), intval($price_query),
				$id, $user_id);
			
			if (false === $wpdb->query($sql)) {
				array_push($errors, "Не удалось изменить Ваш транспорт. Свяжитесь, пожалуйста, с администрацией сайта");
				array_push($errors, $wpdb->last_error);
			//	$errors = array_merge($errors, $dis['errors']);
				tzs_print_truck_form($errors, true);
			} else {
			//	print_errors($dis['errors']);
				echo "Ваш транспорт изменен";
				echo "<br/>";
				echo '<a href="/view-truck/?id='.$id.'&spis=new">Просмотреть транспорт</a>';
                                $new_url = get_site_url().'/my-trucks';
                                echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
			}
		}
	}
}

function tzs_front_end_del_truck_handler($attrs) {
	ob_start();
	
	$user_id = get_current_user_id();
	$tr_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ($tr_id <= 0) {
		print_error('Груз не найден');
	} else {
		global $wpdb;
		$sql = "DELETE FROM ".TZS_TRUCK_TABLE." WHERE id=$tr_id AND user_id=$user_id;";
		if (false === $wpdb->query($sql)) {
			$errors = array();
			array_push($errors, "Не удалось удалить Ваш транспорт. Свяжитесь, пожалуйста, с администрацией сайта");
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

function tzs_front_end_edit_truck_handler($atts) {
	ob_start();
	
	$user_id = get_current_user_id();
	$tr_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ($tr_id <= 0) {
		print_error('Груз не найден');
	} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'edittruck' && ($_POST['formName'] == 'truck')) {
		$id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
		tzs_edit_truck($id);
	} else {
		global $wpdb;
		$sql = "SELECT * FROM ".TZS_TRUCK_TABLE." WHERE id=$tr_id AND user_id=$user_id;";
		$row = $wpdb->get_row($sql);
		
		if (count($row) == 0 && $wpdb->last_error != null) {
                    print_error('Не удалось отобразить информацию о транспорте. Свяжитесь, пожалуйста, с администрацией сайта');
		} else if ($row == null) {
                    print_error('Груз не найден');
		} else {
                    $sql_flag1 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->from_cid;
                    $row1 = $wpdb->get_row($sql_flag1);
                    $sql_flag2 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->to_cid;
                    $row2 = $wpdb->get_row($sql_flag2);
                
                    $_POST['from_code'] = "/wp-content/plugins/tzs/assets/images/flags/".strtolower($row1->code).'.png';
                    $_POST['to_code'] = "/wp-content/plugins/tzs/assets/images/flags/".strtolower($row2->code).'.png';			
                    $_POST['tr_date_from'] = date("d.m.Y", strtotime($row->tr_date_from));
                    $_POST['tr_date_to'] = date("d.m.Y", strtotime($row->tr_date_to));
                    $_POST['tr_city_from'] = $row->tr_city_from;
                    $_POST['tr_city_to'] = $row->tr_city_to;
                    $_POST['comment'] = $row->comment;
                    
                    if ($row->tr_weight > 0)
                        $_POST['tr_weight'] = ''.remove_decimal_part($row->tr_weight);
                    if ($row->tr_volume > 0)
                        $_POST['tr_volume'] = ''.remove_decimal_part($row->tr_volume);
                    
                    $_POST['trans_type'] = ''.$row->trans_type;
                    $_POST['tr_type'] = ''.$row->tr_type;
                    $_POST['trans_count'] = ''.$row->trans_count;
                    
                    if ($row->tr_length > 0 || $row->tr_height > 0 || $row->tr_width > 0) {
                        $_POST['set_dim'] = '';
                        if ($row->tr_width > 0)
                            $_POST['tr_width'] = ''.remove_decimal_part($row->tr_width);
                        if ($row->tr_height > 0)
                            $_POST['tr_height'] = ''.remove_decimal_part($row->tr_height);
                        if ($row->tr_length > 0)
                            $_POST['tr_length'] = ''.remove_decimal_part($row->tr_length);
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
                        
                    $_POST['sh_distance'] = $row->distance;
                    $_POST['sh_descr'] = $row->sh_descr;
                    $_POST['tr_active'] = $row->active;
                    $_POST['id'] = ''.$row->id;
                    
                    tzs_print_truck_form(null, true);
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

function tzs_front_end_truck_handler($atts) {
	ob_start();
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'addtruck' && ($_POST['formName'] == 'truck')) {
		tzs_edit_truck(0);
	} else {
		tzs_print_truck_form(null);
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

?>