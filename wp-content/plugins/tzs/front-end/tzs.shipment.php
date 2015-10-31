<?php

include_once(TZS_PLUGIN_DIR.'/functions/tzs.shipment.functions.php');

function tzs_print_shipment_form($errors, $edit=false) {
    $d = date("d.m.Y");
	
    //print_errors($errors);
    ?>
		<script src="/wp-content/plugins/tzs/assets/js/distance.js"></script>
		<script src="/wp-content/plugins/tzs/assets/js/autocomplete.js"></script>

    <div style="clear: both;"></div>
    
    <!-- test new form -->
    <form enctype="multipart/form-data" method="post" id="form_shipment" class="" action="">
        
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
            <input type="text" id="datepicker1" name="sh_date_from" size="" value="<?php echo_val_def('sh_date_from', ''); ?>" placeholder="Дата погрузки" readonly="true">
        </div>
        <div class="span3">
            <input autocomplete="city" id="first_city" type="text" size="35" name="sh_city_from" value="<?php echo_val('sh_city_from'); ?>" autocomplete="on" placeholder="Населенный пункт погрузки">
	   </div>
        <div class="span1">
            <img id ="first_city_flag" src="<?php echo $edit ? echo_val('from_code') : "" ?>"  style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="Флаг страны">
        </div>
        <div class="span2">
            <input type="text" id="sh_distance" name="sh_distance" size="" value="<?php echo_val('sh_distance'); ?>" maxlength = "255" readonly="true" style="width: 50px;">&nbsp;&nbsp;км<!--disabled="disabled"-->
            <input type="hidden" name="length" id="route-length">
        </div>
        <div class="span3">
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span3">
            <input type="text" id="datepicker2" name="sh_date_to" size="" value="<?php echo_val_def('sh_date_to', ''); ?>" placeholder="Дата выгрузки" readonly="true">
        </div>
        <div id="div_second_city" class="span3 form-group">
            <input autocomplete="city" id="second_city" class="form-control" type="text" size="35" name="sh_city_to" value="<?php echo_val('sh_city_to'); ?>" autocomplete="on" placeholder="Населенный пункт выгрузки">
        </div>
        <div class="span1">
            <img id ="second_city_flag" src="<?php echo $edit ? echo_val('to_code') : "" ?>" style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="Флаг страны">
        </div>
        <div class="span2">
            <a id="show_dist_link" href="javascript:showDistanceDialog();">см. карту</a>
        </div>
        <div class="span3">
        </div>
    </div>
                    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_sh_type" class="span3">
            <select id="sh_type" name="sh_type" placeholder="Тип груза">
            <?php
                tzs_print_array_options($GLOBALS['tzs_sh_types'], '', 'sh_type', 'Тип груза');
            ?>
            </select>
        </div>
        <div class="span3">
            <input type="text" id="sh_descr" name="sh_descr" size="" value="<?php echo_val('sh_descr'); ?>" maxlength = "255" placeholder="Описание груза">
        </div>
        <div class="span3">
            <input type="text" size="15" name="comment" value="<?php echo_val('comment'); ?>" maxlength = "255" placeholder="Комментарий">
        </div>
        <div class="span3"><!-- style="text-align: right;"-->
            <input type="checkbox" name="set_dim" id="set_dim" <?php if (isset($_POST['set_dim'])) echo 'checked="checked"'; ?>>&nbsp;Указать габариты груза (м):
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_trans_type" class="span3">
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
            <label for="sh_weight">Вес груза:</label>&nbsp;
            <input type="text" id="sh_weight" name="sh_weight" value="<?php echo_val('sh_weight'); ?>" maxlength = "5" style="width: 50px;">&nbsp;т
        </div>
        <div class="span3">
            <input type="text" name="sh_length" id="sh_length" value="<?php echo_val('sh_length'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Длина" style="width: 50px;">&nbsp;&nbsp;
            <input type="text" name="sh_width" id="sh_width" value="<?php echo_val('sh_width'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Ширина" style="width: 50px;">&nbsp;&nbsp;
            <input type="text" name="sh_height" id="sh_height" value="<?php echo_val('sh_height'); ?>" maxlength = "5" title="Формат: 99.99" placeholder="Высота" style="width: 50px;">
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span4">
            <label for="cost">Стоимость перевозки:</label>&nbsp;
            <input type="text" id="cost" name="cost" value="<?php echo_val('cost'); ?>" size="10" style="width: 100px;">
            &nbsp;грн
            <input type="hidden" name="cost_curr" id="cost_curr" value="1">
        </div>
        <div class="span4">
            <label for="price">Цена&nbsp;=</label>&nbsp;
            <input type="text" id="price" name="price" value="<?php echo_val('price'); ?>" size="10" disabled="disabled" style="width: 100px;">
            &nbsp;грн/км
        </div>
        <div class="span1">
        </div>
        <div class="span3"><!-- style="text-align: right; float: right;"-->
            <label for="volume">Объем груза&nbsp;=</label>&nbsp;
            <input type="text" id="volume" name="volume" value="<?php echo_val('volume'); ?>" disabled="disabled" style="width: 80px;">
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
        <div class="span2">
            <input type="checkbox" name="cash" <?php isset($_POST['cash']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="cash">Наличная</label>
        </div>
        <div class="span2">
            <input type="checkbox" name="nocash" <?php isset($_POST['nocash']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="nocash">Безналичная</label>
        </div>
        <div class="span2">
            <input type="checkbox" name="way_ship" <?php isset($_POST['way_ship']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="way_ship">При погрузке</label>
        </div>
        <div class="span2">
            <input type="checkbox" name="way_debark" <?php isset($_POST['way_debark']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="way_debark">При выгрузке</label>
        </div>
        <div class="span1">
            <input type="checkbox" name="soft" <?php isset($_POST['soft']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="soft">Софт</label>
        </div>
        <div class="span2" style="text-align: right;">
            <input type="checkbox" id="way_prepay" name="way_prepay" <?php isset($_POST['way_prepay']) ? 'checked="checked"' : ''; ?> >&nbsp;<label for="way_prepay">Предоплата</label>
        </div>
        <div class="span1">
            <input type="text" id="prepayment" name="prepayment" value="<?php echo_val('prepayment'); ?>" size="5" placeholder = "0" style="width: 20px;">&nbsp;%
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span8">
            <div class="span12" style="margin-bottom: 20px;">
                <input type="checkbox" id="price_query" name="price_query" <?php isset($_POST['price_query']) ? 'checked="checked"' : ''; ?>>&nbsp;Не указывать стоимость (цена договорная)
            </div>
            <div class="span4">
                <button id="form_button1"><?php echo $edit ? "ИЗМЕНИТЬ ЗАЯВКУ" : "РАЗМЕСТИТЬ ЗАЯВКУ" ?></button>
            </div>
            <div class="span4">
                <button id="form_button2">ОЧИСТИТЬ ВСЕ ПОЛЯ</button>
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
            После нажатия кнопки "РАЗМЕСТИТЬ ЗАЯВКУ" заявка будет опубликована в базе транспорта, после нажатия кнопки "ВЫХОД" заявка не сохраняется.
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span12">
            Напоминаем: заявка будет удалена из базы активных заявок и перенесена в архив на следующий день после указанного Вами дня выгрузки
        </div>
    </div>
    
	<?php if ($edit) {?>
		<input type="hidden" name="action" value="editshipment"/>
		<input type="hidden" name="id" value="<?php echo_val('id'); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="addshipment"/>
	<?php } ?>
	<input type="hidden" name="formName" value="shipment" />
    </form>
    <div class="clearfix">&nbsp;</div>
    
    <!-- test new form END -->
    
	
    <script src="/wp-content/plugins/tzs/assets/js/jquery.maskedinput.min.js"></script>
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
                jQuery("#sh_length, #sh_width, #sh_height").removeAttr("disabled");
                //jQuery("#sh_length, #sh_width, #sh_height").attr('required', 'required');
            } else {
                //jQuery("#sh_length, #sh_width, #sh_height").removeAttr('required');
                jQuery("#sh_length, #sh_width, #sh_height").attr("disabled", "disabled");
                jQuery("#sh_length, #sh_width, #sh_height").attr('value', '');
                jQuery("#volume").attr('value', '');
            }
        }

        // Вывод карты
        function showDistanceDialog() {
            if ((jQuery('#first_city').val().length > 0) && (jQuery('#second_city').val().length > 0)) {
                //displayDistance([jQuery('input[name=sh_city_from]').val(), jQuery('input[name=sh_city_to]').val()], null);
                displayDistance([jQuery('#first_city').val(), jQuery('#second_city').val()], null);
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
                jQuery("[name=price]").attr('value', '');
                jQuery("[name=cost]").attr('value', '');
                jQuery("[name=prepayment]").attr('value', '');

                jQuery("[name=cash]").prop('checked', false);
                jQuery("[name=nocash]").prop('checked', false);
                jQuery("[name=way_ship]").prop('checked', false);
                jQuery("[name=way_debark]").prop('checked', false);
                jQuery("[name=soft]").prop('checked', false);
                jQuery("[name=way_prepay]").prop('checked', false);

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
            if ((jQuery('#sh_length').val().length > 0) && (jQuery('#sh_width').val().length > 0) && (jQuery('#sh_height').val().length > 0)) {
                var vol = jQuery('#sh_length').val() * jQuery('#sh_width').val() * jQuery('#sh_height').val();
                jQuery('#volume').attr('value', vol);
            } else {
                jQuery('#volume').attr('value', '');
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
                jQuery('#price, #prepayment').css({'border': '1px solid #007FFF'});
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

            jQuery('#bpost').submit(function() {
                    jQuery('#addpostsub').attr('disabled','disabled');
                    return true;
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
            onWayPrepayChange();
           // onCityChange();
            onCostChange();

            jQuery('#first_city, #second_city').on('input',function() { 		
                                        delay(function(){
                                                onCityChange();
                                          //alert('Time elapsed!');
                                        }, 1000 );/*onCityChange();*/ });

            //jQuery("#sh_length, #sh_width, #sh_height").mask("99.99");
            jQuery("#sh_length, #sh_width, #sh_height, #cost, #sh_weight, #trans_count").bind("change keyup input click", function() {
                if (this.value.match(/[^0-9.]/g)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                }
            });
            jQuery("#sh_length, #sh_width, #sh_height").change(function() { onVolumeCalculate(); });
            jQuery("#price_query").change(function() { onPriceQueryChange(); });
            jQuery("#way_prepay").change(function() { onWayPrepayChange(); });

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
        $sh_active = get_param_def('sh_active', '0');
	$sh_date_from = get_param('sh_date_from');
	$sh_date_to = get_param('sh_date_to');
	$sh_city_from = get_param('sh_city_from');
	$sh_city_to = get_param('sh_city_to');
	$comment = get_param('comment');
	
	$sh_descr = get_param('sh_descr');
	$sh_weight = get_param_def('sh_weight','0');
	$sh_volume = get_param_def('sh_volume','0');
	$sh_type = get_param('sh_type');
	$trans_type = get_param('trans_type');
	$trans_count = get_param('trans_count');
	
	$set_dim = isset($_POST['set_dim']);
	$sh_length = get_param('sh_length');
	$sh_height = get_param('sh_height');
	$sh_width = get_param('sh_width');
	
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
	
	$errors = array();
	
	// cost
	$price = get_param_def('set_price','0') == '1';
	$price_json = array();
	$price_json['set_price'] = $price ? 1 : 0;
	if ($price) {
		$price_val = get_param_def('price','0');
		if (!is_valid_num($price_val)) {
			array_push($errors, "Неверно задана стоимость");
		} else {
			$price_json['price'] = floatval($price_val);
		}
		
		$cost_curr = get_param_def('cost_curr','0');
		if (!is_valid_num($cost_curr) || !isset($GLOBALS['tzs_curr'][intval($cost_curr)])) {
			array_push($errors, "Неверно задана валюта");
		} else {
			$price_json['cost_curr'] = intval($cost_curr);
		}
		
		$payment = get_param_def('payment', null);
		if ($payment != null) {
			if ($payment != 'nocash' && $payment != 'cash' && $payment != 'mix_cash' && $payment != 'soft' && $payment != 'conv' && $payment != 'on_card') {
				array_push($errors, "Неверно задана форма оплаты");
			} else {
				$price_json['payment'] = $payment;
			}
		}
		
		if (isset($_POST['payment_way_nds']))
			$price_json['payment_way_nds'] = true;
		if (isset($_POST['way_ship']))
			$price_json['way_ship'] = true;
		if (isset($_POST['way_debark']))
			$price_json['way_debark'] = true;
		if (isset($_POST['payment_way_barg']))
			$price_json['payment_way_barg'] = true;
			
		if (isset($_POST['way_prepay'])) {
			$price_json['way_prepay'] = true;
			$prepayment = get_param_def('prepayment', '0');
			if (!is_valid_num($prepayment) || floatval($prepayment) > 100) {
				array_push($errors, "Неверно задан размер предоплаты");
			} else {
				$price_json['prepayment'] = floatval($prepayment);
			}
		}
	} else {
		if (isset($_POST['price_query']))
			$price_json['price_query'] = true;
	}
	// ----
	
	if ($sh_date_from == null || $sh_date_to == null) {
		array_push($errors, "Неверный формат даты");
	}

        // Контроль пересечения дат
        if ($sh_date_to_str < $sh_date_from_str) {
            array_push($errors, "Дата выгрузки не может быть РАНЬШЕ даты погрузки");
        }
	
	if (!is_valid_city($sh_city_from)) {
		array_push($errors, "Неверный пункт погрузки");
	}
	
	if (!is_valid_city($sh_city_to)) {
		array_push($errors, "Неверный пункт разгрузки");
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
		$from_info = tzs_yahoo_convert($sh_city_from);
		if (isset($from_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт погрузки: ".$from_info["error"]);
		}
		$to_info = tzs_yahoo_convert($sh_city_to);
		if (isset($to_info["error"])) {
			array_push($errors, "Не удалось распознать населенный пункт выгрузки: ".$to_info["error"]);
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
		
		$sh_distance = get_param('length');
		//echo 'Дистанция - '+$sh_distance+'<br>';
		
		if ($id == 0) {
			$sql = $wpdb->prepare("INSERT INTO ".TZS_SHIPMENT_TABLE.
				" (time, last_edited, user_id, sh_date_from, sh_date_to, sh_city_from, sh_city_to, sh_descr, sh_weight, sh_volume, sh_length, sh_height, sh_width, trans_count, trans_type, sh_type, active, comment, cost, distance, from_cid, from_rid, from_sid, to_cid, to_rid, to_sid, price, price_val)".
				" VALUES (now(), NULL, %d, %s, %s, %s, %s, %s, %f, %f, %f, %f, %f, %d, %d, %d, %d, %s, %s, %d, %d, %d, %d, %d, %d, %d, %f, %d);",
				$user_id, $sh_date_from, $sh_date_to, stripslashes_deep($sh_city_from), stripslashes_deep($sh_city_to),
				stripslashes_deep($sh_descr), floatval($sh_weight), floatval($sh_volume), floatval($sh_length),
				floatval($sh_height), floatval($sh_width), intval($trans_count), intval($trans_type), intval($sh_type), intval($sh_active), stripslashes_deep($comment), stripslashes_deep(json_encode($price_json)), $sh_distance,
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                floatval($price_val), intval($cost_curr));
		
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
			}
		} else {
			$sql = $wpdb->prepare("UPDATE ".TZS_SHIPMENT_TABLE." SET ".
				" last_edited=now(), sh_date_from=%s, sh_date_to=%s, sh_city_from=%s, sh_city_to=%s, sh_descr=%s, sh_weight=%f, sh_volume=%f, sh_length=%f, sh_height=%f, sh_width=%f, trans_count=%d, trans_type=%d, sh_type=%d, active=%d, comment=%s, cost=%s, distance=%d, ".
				" from_cid=%d,from_rid=%d,from_sid=%d,to_cid=%d,to_rid=%d,to_sid=%d, price=%f, price_val=%d".
				" WHERE id=%d AND user_id=%d;", $sh_date_from, $sh_date_to, stripslashes_deep($sh_city_from),
				stripslashes_deep($sh_city_to), stripslashes_deep($sh_descr), floatval($sh_weight), floatval($sh_volume),
				floatval($sh_length), floatval($sh_height), floatval($sh_width), intval($trans_count), intval($trans_type), intval($sh_type), intval($sh_active), stripslashes_deep($comment), stripslashes_deep(json_encode($price_json)), round($dis['distance'] / 1000),
				$from_info["country_id"],$from_info["region_id"],$from_info["city_id"],$to_info["country_id"],$to_info["region_id"],$to_info["city_id"],
                                floatval($price_val), intval($cost_curr),
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
		
		$sql_flag1 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->from_cid;
		$row1 = $wpdb->get_row($sql_flag1);
		$sql_flag2 = "SELECT * FROM ".TZS_COUNTRIES_TABLE." WHERE country_id=".$row->to_cid;
		$row2 = $wpdb->get_row($sql_flag2);
		
		if (count($row) == 0 && $wpdb->last_error != null) {
			print_error('Не удалось отобразить информацию о грузе. Свяжитесь, пожалуйста, с администрацией сайта');
		} else if ($row == null) {
			print_error('Груз не найден');
		} else {
			$cost = json_decode($row->cost);
			foreach ($cost as $key => $val) {
				$_POST[$key] = ''.$val;
			}
			
			
			$_POST['from_code'] = "/wp-content/plugins/tzs/assets/images/flags1/".strtolower($row1->code).'.png';
			$_POST['to_code'] = "/wp-content/plugins/tzs/assets/images/flags2/".strtolower($row2->code).'.png';			
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
				$_POST['sh_width'] = ''.remove_decimal_part($row->sh_width);
				$_POST['sh_height'] = ''.remove_decimal_part($row->sh_height);
				$_POST['sh_length'] = ''.remove_decimal_part($row->sh_length);
			}
			$_POST['id'] = ''.$row->id;
			$_POST['sh_active'] = $row->active;
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