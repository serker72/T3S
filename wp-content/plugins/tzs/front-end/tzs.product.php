<?php

include_once(TZS_PLUGIN_DIR.'/functions/tzs.product.functions.php');
//include_once(TZS_PLUGIN_DIR.'/front-end/tzs.trade.images.php');

// Эти файлы должны быть подключены в лицевой части (фронт-энде).
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

        
function tzs_print_product_form($errors, $edit=false) {
    //$d = date("d.m.Y");
    // Добавим 7 дней к текущей дате
    $dt = new DateTime();
    date_add($dt, date_interval_create_from_date_string((TZS_PR_PUBLICATION_MIN_DAYS + 1).' days'));
    $d = date_format($dt, "d.m.Y");
    $img_info = array();
    $img_names = array();

    //if(isset($_GET['spis'])) echo "<a id='edit_search' href='/account/my-products/'>Назад к списку</a> <div style='clear: both'></div>";
    //else echo "<button id='edit_search'  onclick='history.back()'>Назад к списку</button> <div style='clear: both'></div>";

    if (isset($_POST["pr_image_id_lists"]) && (strlen($_POST["pr_image_id_lists"]) > 0)) {
        $img_names = explode(';', $_POST["pr_image_id_lists"]);
        for ($i=0;$i < count($img_names);$i++) {
            if ($img_names[$i] !== null && $img_names[$i] !== '')
                $img_info[$i] = wp_get_attachment_image_src($img_names[$i], 'thumbnail');
        }
    }
    
    echo '<div style="clear: both;"></div>';
    print_errors($errors);
    ?>
    <script src="/wp-content/plugins/tzs/assets/js/autocomplete.js"></script>
    
    <div style="clear: both;"></div>
    
    <!-- test new form -->
<div class="form_wrapper">
    <form enctype="multipart/form-data" method="post" id="form_product" class="" action="">
        
    <?php if ($edit && isset($_POST["pr_active"]) && ($_POST["pr_active"] == 0)) { ?>
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_pr_active" class="span12">
            <div class="" style="background-color: #E5AEAE; text-align: center; padding: 2px;">Архивная заявка</div>
        </div>
    </div>
    <?php } ?>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="" class="span3">
            <select id="pr_type_id" name="pr_type_id">
                <option value="0" <?php if (isset($_POST['pr_type_id']) && $_POST['pr_type_id'] == 0) echo 'selected="selected"'; ?> >Категория</option>
                <?php tzs_build_product_types('pr_type_id', TZS_PR_ROOT_CATEGORY_PAGE_ID); ?>
            </select>
            <?php wp_nonce_field( 'pr_type_id', 'pr_type_id_nonce' ); ?>
            <input type="hidden" name="pr_active" id="pr_active" value="1">
	</div>
        <div id="" class="span2">
            <!--label for="pr_sale_or_purchase">Тип заявки</label-->
            <select id="pr_sale_or_purchase" name="pr_sale_or_purchase" style="width: 100px;">
                <option value="0" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 0) echo 'selected="selected"'; ?> >Тип заявки</option>
                <option value="1" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 1) echo 'selected="selected"'; ?> >Продажа</option>
                <option value="2" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 2) echo 'selected="selected"'; ?> >Покупка</option>
            </select>
        </div>
        <div id="" class="span3">
            <!--label for="pr_fixed_or_tender">Участник тендера</label-->
            <select id="pr_fixed_or_tender" name="pr_fixed_or_tender">
                <option value="0" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 0) echo 'selected="selected"'; ?> >Участник тендера</option>
                <option value="1" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 1) echo 'selected="selected"'; ?> >Цена зафиксирована</option>
                <option value="2" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 2) echo 'selected="selected"'; ?> >Тендерное предложение</option>
            </select>
        </div>
        <div class="span4">
            <input autocomplete="city" id="first_city" type="text" size="35" name="pr_city_from" value="<?php echo_val('pr_city_from'); ?>" autocomplete="on" placeholder="Местонахождение товара" style="width: 280px;">
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span9">
            <input type="text" id="pr_title" name="pr_title" size="" maxlength="255" value="<?php echo_val('pr_title'); ?>" placeholder="Наименование товара" style="width: 90%;">
        </div>
        <div class="span3">
        </div>
    </div>
                    
    <div class="row-fluid"  style="width: 100%; margin-bottom: 20px;">
        <div class="span8">
            <?php
            $args = array(  'wpautop' => 1,
                            'media_buttons' => 0,
                            'textarea_name' => 'pr_description', //нужно указывать!
                            'textarea_rows' => 4,
                            'tabindex'      => null,
                            'editor_css'    => '',
                            'editor_class'  => '',
                            'teeny'         => 1,
                            'dfw'           => 0,
                            'tinymce'       => array(
                                'theme' => 'advanced',
                                'theme_​advanced_​buttons1' => 'save,newdocument, | ,bold, italic, underline, strikethrough, |, justifyleft, justifycenter, justifyright, justifyfull, styleselect, formatselect, fontselect, fontsizeselect',
                                'theme_​advanced_​buttons2' => 'cut, copy, paste, pastetext, pasteword, |, search, replace, |, bullist, numlist, |, outdent, indent, blockquote, |, undo, redo, |, link, unlink, anchor, image, cleanup, help, code, |, insertdate, inserttime, preview, |, forecolor, backcolor',
                                'theme_​advanced_​buttons3' => 'tablecontrols, |, hr, removeformat, visualaid, |, sub, sup, |, charmap, emotions, iespell, media, advhr, |, print, |, ltr, rtl, |, fullscreen',
                            ),
                            'quicktags'     => array(
                                'id' => 'editpost',
                                //'buttons' => 'formatselect,|bold,italic,underline,bullist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,,spellchecker,wp_fullscreen,wp_adv'
                            ),
                            'drag_drop_upload' => false
                        );
            wp_editor($_POST['pr_description'], 'editpost', $args);
            ?>
        </div>
        <div class="span4">
            <div class="span12" style="margin-bottom: 10px;">
                <label>Добавить изображения (до 1Мб):</label>
            </div>
            <div class="span12">
                <div class="pr_image_wrapper">
                    <div id="div_image1_off" class="pr_image_off">
                        <input type="file" id="image1_load" name="image1_load" class="inputfile inputfile-3" accept="image/jpeg,image/png,image/gif">
                        <label for="image1_load"><span>выбрать и загрузить</span></label>
                    </div>
                    <div id="div_image1_on" class="pr_image_on">
                        <span id="image1_delete" class="pr_image_delete"></span>
                        <img id="image1" src="<?php echo isset($img_info[0][0]) ? $img_info[0][0] : ''; ?>">
                        <?php if ($edit && isset($img_names[0])) { ?>
                            <input type="hidden" name="image_id_1" value="<?php echo $img_names[0]; ?>"/>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pr_image_wrapper">
                    <div id="div_image2_off" class="pr_image_off">
                        <input type="file" id="image2_load" name="image2_load" class="inputfile inputfile-3" accept="image/jpeg,image/png,image/gif">
                        <label for="image2_load"><span>выбрать и загрузить</span></label>
                    </div>
                    <div id="div_image2_on" class="pr_image_on">
                        <span id="image2_delete" class="pr_image_delete"></span>
                        <img id="image2" src="<?php echo isset($img_info[1][0]) ? $img_info[1][0] : ''; ?>">
                        <?php if ($edit && isset($img_names[1])) { ?>
                            <input type="hidden" name="image_id_2" value="<?php echo $img_names[1]; ?>"/>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pr_image_wrapper">
                    <div id="div_image3_off" class="pr_image_off">
                        <input type="file" id="image3_load" name="image3_load" class="inputfile inputfile-3" accept="image/jpeg,image/png,image/gif">
                        <label for="image3_load"><span>выбрать и загрузить</span></label>
                    </div>
                    <div id="div_image3_on" class="pr_image_on">
                        <span id="image3_delete" class="pr_image_delete"></span>
                        <img id="image3" src="<?php echo isset($img_info[2][0]) ? $img_info[2][0] : ''; ?>">
                        <?php if ($edit && isset($img_names[2])) { ?>
                            <input type="hidden" name="image_id_3" value="<?php echo $img_names[2]; ?>"/>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_pr_copies" class="span4">
            <label for="pr_copies">Количество</label>
            <input type="text" id="pr_copies" name="pr_copies" size="" value="<?php echo_val('pr_copies'); ?>" min="0" placeholder="Количество" style="width: 80px;">
            &nbsp;&nbsp;
            <select for="pr_copies" id="pr_unit" name="pr_unit" style="width: 80px;">
            <?php
                tzs_print_array_options($GLOBALS['tzs_pr_unit'], '', 'pr_unit', 'Ед.измерения');
            ?>
            </select>
        </div>
        <div id="div_pr_price" class="span4">
            <label for="pr_price">Стоимость</label>
            <input type="text" id="pr_price" name="pr_price" size="10" value="<?php echo_val('pr_price'); ?>" placeholder="Стоимость" style="width: 80px;">
            &nbsp;&nbsp;
            <select for="pr_price" id="pr_currency" name="pr_currency" style="width: 80px;">
            <?php
                tzs_print_array_options($GLOBALS['tzs_pr_curr'], '', 'pr_currency', 'Валюта');
            ?>
            </select>
        </div>
        <div class="span4">
            <label for="pr_expiration">Окончание публикации</label>
            <input type="text" id="datepicker1" name="pr_expiration" size="" value="<?php echo_val_def('pr_expiration', $d); ?>" placeholder="Дата выгрузки" readonly="true" style="width: 80px;">
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%;">
        <div class="span8">
            <div class="span12">
                <label for="" id="payment_label">Форма расчета (можно указать несколько способов одновременно):</label>
            </div>
            
            <div class="span3 chekbox">
                <input type="checkbox" id="cash" name="cash" <?php echo isset($_POST['cash']) ? 'checked="checked"' : ''; ?>><label for="cash">Наличная</label>
            </div>
            <div class="span3 chekbox">
                <input type="checkbox" id="nocash" name="nocash" <?php echo isset($_POST['nocash']) ? 'checked="checked"' : ''; ?>><label for="nocash">Безналичная</label>
            </div>
            <div class="span3 chekbox">
                <input type="checkbox" id="nds" name="nds" <?php echo isset($_POST['nds']) ? 'checked="checked"' : ''; ?>><label for="nds">включая НДС</label>
            </div>
            <div class="span3 chekbox">
                <input type="checkbox" id="nonds" name="nonds" <?php echo isset($_POST['nonds']) ? 'checked="checked"' : ''; ?>><label for="nonds">без НДС</label>
            </div>
            
            <div class="span12">
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

    <div class="row-fluid"  style="width: 100%; margin-top: 15px;">
        <div class="span12">
            <div style="font-size: 92%; font-style: italic;">
                После нажатия кнопки "РАЗМЕСТИТЬ ЗАЯВКУ" заявка будет опубликована в базе товаров, после нажатия кнопки "ВЫХОД" заявка не сохраняется.
            </div>
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span12">
            <div style="font-size: 92%; font-style: italic;">
                <span style="color: #F00;">Напоминаем:</span> При наступлении даты окончания публикации товар будет автоматически перенесен в архив. Минимальный срок публикации - <?php echo TZS_PR_PUBLICATION_MIN_DAYS; ?> дней
            </div>
        </div>
    </div>
    
	<?php if ($edit) {?>
		<input type="hidden" name="action" value="editproduct"/>
		<input type="hidden" name="id" value="<?php echo_val('id'); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="addproduct"/>
                <input type="hidden" name="pr_active" value="1"/>
	<?php } ?>
	<input type="hidden" name="formName" value="product" />
    </form>
</div>
    <div class="clearfix">&nbsp;</div>
    
    <!-- test new form END -->
    <script>
     jQuery('#pr_type_id').on('change', function() {
            jQuery('#div_pr_type_id').addClass("change");    
     });
     
     jQuery('#pr_sale_or_purchase').on('change', function() {
            jQuery('#div_pr_sale_or_purchase').addClass("change");    
     });
     
     jQuery('#pr_fixed_or_tender').on('change', function() {
            jQuery('#div_pr_fixed_or_tender').addClass("change");    
     });
    </script>
	
    <script>
        // Изменение поля "Единица измерения"
        function onPrUnitChange() {
            jQuery('#pr_currency').attr('value', jQuery('#pr_unit').val());
        }

        // Изменение поля "Валюта"
        function onPrCurrencyChange() {
            jQuery('#pr_unit').attr('value', jQuery('#pr_currency').val());
        }

        // Функция проверки правильности заполнения полей формы до отправки
        function onFormValidate() {
            //var ErrorMsg1 = 'Список ошибок:<ul>';
            var ErrorMsg1 = '<p>';
            var ErrorMsg2 = '';
            var ErrorMsg3 = '</p>';

            if (jQuery('#pr_type_id').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана категория товара.<br>\n';
                jQuery('#pr_type_id').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_type_id').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_sale_or_purchase').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан тип заявки.<br>\n';
                jQuery('#pr_sale_or_purchase').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_sale_or_purchase').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_fixed_or_tender').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан признак тендера.<br>\n';
                jQuery('#pr_fixed_or_tender').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_fixed_or_tender').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#first_city').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указан пункт местонахождения товара.<br>\n';
                jQuery('#first_city').css({'border': '2px solid #F00'});
            } else {
                jQuery('#first_city').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_title').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано наименование товара.<br>\n';
                jQuery('#pr_title').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_title').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#editpost').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не заполнено описание товара.<br>\n';
                jQuery('#editpost').css({'border': '2px solid #F00'});
            } else {
                jQuery('#editpost').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_copies').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указано количество товара.<br>\n';
                jQuery('#pr_copies').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_copies').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_unit').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана единица измерения количества товара.<br>\n';
                jQuery('#pr_unit').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_unit').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_price').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана стоимость товара.<br>\n';
                jQuery('#pr_price').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_price').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#pr_currency').val() < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана валюта стоимости товара.<br>\n';
                jQuery('#pr_currency').css({'border': '2px solid #F00'});
            } else {
                jQuery('#pr_currency').css({'border': '1px solid #007FFF'});
            }

            if (jQuery('#datepicker1').val().length < 1) {
                ErrorMsg2 = ErrorMsg2 + 'Не указана дата окончания публикации.<br>\n';
                jQuery('#datepicker1').css({'border': '2px solid #F00'});
            } else {
                jQuery('#datepicker1').css({'border': '1px solid #007FFF'});
            }
                
            // Проверка правильности указания переключателей
            if (!(jQuery("#cash").is(':checked') || jQuery("#nocash").is(':checked') || jQuery("#nds").is(':checked') || jQuery("#nonds").is(':checked'))) {
                ErrorMsg2 = ErrorMsg2 + 'Необходимо выбрать хотя бы один способ в блоке "Форма расчета".<br>\n';
                jQuery('#payment_label').css({'border': '2px solid #F00'});
            } else {
                jQuery('#payment_label').css({'border': 'none'});
            }

            if (ErrorMsg2.length > 0) {
                jQuery("#form_error_message").html(ErrorMsg1 + ErrorMsg2 + ErrorMsg3);
                jQuery("#form_error_message").show();
                return false;
            } else {
                return true;
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
            
            jQuery("#image1_delete, #image2_delete, #image3_delete").click();
        }

        /*
         * Функция, вызываемая после загрузки страницы
         */
        jQuery(document).ready(function(){
            if (jQuery('#image1').attr('src') != '') {
                jQuery('#div_image1_off').hide();
                jQuery('#div_image1_on').show();
            }
            
            if (jQuery('#image2').attr('src') != '') {
                jQuery('#div_image2_off').hide();
                jQuery('#div_image2_on').show();
            }
            
            if (jQuery('#image3').attr('src') != '') {
                jQuery('#div_image3_off').hide();
                jQuery('#div_image3_on').show();
            }
            
            jQuery("#image1_load").change(function() {
                var fileObj = this.files[0];
                if (fileObj.size > 1024000) {
                    alert('Размер файла не должен превышать 1 Мб !');
                } else {
                    var url = URL.createObjectURL(fileObj);
                    jQuery('#image1').attr('src', url);
                    jQuery('#div_image1_off').hide();
                    jQuery('#div_image1_on').show();
                }
            });
            
            jQuery("#image2_load").change(function() {
                var fileObj = this.files[0];
                if (fileObj.size > 1024000) {
                    alert('Размер файла не должен превышать 1 Мб !');
                } else {
                    var url = URL.createObjectURL(fileObj);
                    jQuery('#image2').attr('src', url);
                    jQuery('#div_image2_off').hide();
                    jQuery('#div_image2_on').show();
                }
            });
            
            jQuery("#image3_load").change(function() {
                var fileObj = this.files[0];
                if (fileObj.size > 1024000) {
                    alert('Размер файла не должен превышать 1 Мб !');
                } else {
                    var url = URL.createObjectURL(fileObj);
                    jQuery('#image3').attr('src', url);
                    jQuery('#div_image3_off').hide();
                    jQuery('#div_image3_on').show();
                }
            });
            
            jQuery("#image1_delete").click(function(event) {
                jQuery('#image1').removeAttr('src');
                jQuery('#div_image1_off').show();
                jQuery('#div_image1_on').hide();
            });
            
            jQuery("#image2_delete").click(function(event) {
                jQuery('#image2').removeAttr('src');
                jQuery('#div_image2_off').show();
                jQuery('#div_image2_on').hide();
            });
            
            jQuery("#image3_delete").click(function(event) {
                jQuery('#image3').removeAttr('src');
                jQuery('#div_image3_off').show();
                jQuery('#div_image3_on').hide();
            });
            
            jQuery("#form_button1").click(function(event) { 
                event.preventDefault();
                var flag = onFormValidate();
                if (flag) {
                    jQuery("form[id='form_product']").submit();
                }
            });

            jQuery("#form_button2").click(function(event) { 
                event.preventDefault();
                resetForm("form[id='form_product']");
            });

            jQuery("#form_button3").click(function(event) { 
                event.preventDefault();
                location.href = "/account/profile/";
            });

            jQuery("#pr_sale_or_purchase").change(function (eventObject) {
                if (eventObject.target.value == 2) {
                    jQuery("#pr_fixed_or_tender").attr('value', 2);
                    jQuery('#pr_fixed_or_tender').attr('disabled', 'disabled');
                } else {
                    jQuery('#pr_fixed_or_tender').removeAttr('disabled');
                }
            });

            jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
            jQuery( "#datepicker1" ).datepicker({ 
                dateFormat: "dd.mm.yy",
                minDate: "<?php echo $d; ?>",
                selectOtherMonths: true,
                showOtherMonths: true,
            });
            jQuery('#datepicker1').css({'cursor': 'pointer'});
    
            jQuery("#pr_copies, #pr_price").bind("change keyup input click", function() {
                if (this.value.match(/[^0-9.]/g)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                }
            });
            
            jQuery("#pr_unit").change(function() { onPrUnitChange(); });
            jQuery("#pr_unit").keyup(function() { onPrUnitChange(); });
            jQuery("#pr_currency").change(function() { onPrCurrencyChange(); });
            jQuery("#pr_currency").keyup(function() { onPrCurrencyChange(); });
            if (jQuery('#pr_unit').val() < 1) {
                jQuery("#pr_unit").attr('value', 1);
                jQuery("#pr_currency").attr('value', 1);
            }
    
            
            <?php if ($errors != null && count($errors) > 0) {
                $err_str = '';
		foreach ($errors as $error) {
                    $err_str .= $error.'</br>';
                }
                ?>
                jQuery("#form_error_message").html("<?php echo $err_str; ?>");
                jQuery("#form_error_message").show();
            <?php } ?>

        });
    </script>
<?php
}

function tzs_edit_product($id) {
    $errors = array();
    
    $file_error_message = array(
        0 => 'Ошибок не возникло, файл был успешно загружен на сервер',
        1 => 'Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini',
        2 => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме',
        3 => 'Загружаемый файл был получен только частично',
        4 => 'Файл не был загружен',
        5 => '',
        6 => 'Отсутствует временная папка',
        7 => 'Не удалось записать файл на диск',
        8 => 'PHP-расширение остановило загрузку файла',
    );
    
    $user_id = get_current_user_id();
    
    // Проверим защиту nonce
    if (isset($_POST['pr_type_id_nonce']) && wp_verify_nonce($_POST['pr_type_id_nonce'], 'pr_type_id')) {
	$pr_active = get_param_def('pr_active','0');
	$pr_type_id = get_param_def('pr_type_id','0');
        $pr_sale_or_purchase = get_param_def('pr_sale_or_purchase','0');
        $pr_fixed_or_tender = get_param_def('pr_fixed_or_tender','0');
	$pr_title = get_param('pr_title');
	$pr_description = get_param('pr_description');
	$pr_copies = get_param_def('pr_copies','0');
	$pr_unit = get_param_def('pr_unit','0');
	$pr_currency = get_param_def('pr_currency','0');
	$pr_price = get_param_def('pr_price','0');
	$pr_city_from = get_param('pr_city_from');
	$pr_comment = get_param('pr_comment');
	$pr_expiration = get_param('pr_expiration');
        $cash = isset($_POST['cash']) ? 1 : 0;
        $nocash = isset($_POST['nocash']) ? 1 : 0;
        $nds = isset($_POST['nds']) ? 1 : 0;
        $nonds = isset($_POST['nonds']) ? 1 : 0;
        $pr_payment = ($cash && $nocash) ? 11 : (($cash && !$nocash) ? 10 : ((!$cash && $nocash) ? 1 : 0));
        $pr_nds = (nds && $nonds) ? 11 : ((nds && !$nondsh) ? 10 : ((!nds && $nonds) ? 1 : 0));
	
        //$image_id_lists = get_param('image_id_lists');
        //$main_image = get_param_def('main_image', '0');
        $image_id_lists = array();
        $main_image = 0;
        
	if (is_valid_date($pr_expiration) === null) {
            array_push($errors, "Неверный формат даты.");
	} else {
            $cur_date = new DateTime();
            $exp_date = new DateTime($pr_expiration);
            $interval = date_diff($cur_date, $exp_date);
            if ($interval->days < TZS_PR_PUBLICATION_MIN_DAYS) {
                array_push($errors, "Минимальный срок публикации ".TZS_PR_PUBLICATION_MIN_DAYS." дней.");
            }
        }

	$pr_expiration = is_valid_date($pr_expiration);
        
	if (!is_valid_city($pr_city_from)) {
            array_push($errors, "Не указан пункт местонахождения товара.");
	}
        
	if (strlen($pr_title) < 2) {
            array_push($errors, "Не указано наименование товара.");
	}
        
	if (strlen($pr_description) < 2) {
            array_push($errors, "Не указано описание товара.");
	}
        
	if (!is_valid_num_zero($pr_type_id)) {
            array_push($errors, "Не указана категория товара.");
	}
        
	if (!is_valid_num_zero($pr_sale_or_purchase)) {
            array_push($errors, "Не указан тип операции.");
	}
        
	if (!is_valid_num_zero($pr_fixed_or_tender)) {
            array_push($errors, "Не указан тип ценового предложения.");
	}
        
        
	if (!is_valid_num_zero($pr_active)) {
            array_push($errors, "Не указан статус товара.");
	}
        
	if (!is_valid_num_zero($pr_copies)) {
            array_push($errors, "Не указано количество экземпляров товара.");
	}
        
	if (!is_valid_num_zero($pr_unit)) {
            array_push($errors, "Не указана единица измерения количества экземпляров товара.");
	}
        
	if (!is_valid_num_zero($pr_currency)) {
            array_push($errors, "Не указана валюта.");
	}
        
	if (!is_valid_num_zero($pr_price)) {
            array_push($errors, "Не указана стоимость товара.");
	}
        
        if (!$cash && !$nocash && !$nds && !$nonds) {
            array_push($errors, "Необходимо выбрать хотя бы один способ в блоке \"Форма расчета\".");
	}
    }
    else {
        array_push($errors, "Проверка формы не пройдена. Свяжитесь, пожалуйста, с администрацией сайта.");
    }
	
    $from_info = null;
    if (count($errors) == 0) {
            $from_info = tzs_yahoo_convert($pr_city_from);
            if (isset($from_info["error"])) {
                    array_push($errors, "Не удалось распознать населенный пункт: ".$from_info["error"]);
            }
    }

    
    if (count($errors) > 0) {
            tzs_print_product_form($errors, $id > 0);
    } else {
        global $wpdb;
        
	// Если выбран тип заявки "Покупка" - то только "Тендерное предложение"
        // Проверка и присвоение сделаны для перестраховки, на случай если не сработает JS
        if ($pr_sale_or_purchase == 2) {
            $pr_fixed_or_tender = 2;
	}
        
        $pr_expiration = date('Y-m-d', mktime(0, 0, 0, $pr_expiration['month'], $pr_expiration['day'], $pr_expiration['year']));
        
        // Обработка изображений
        for($i = 1; $i <= 3; $i++) {
            $add_image_index = 'image'.$i.'_load';
            $del_image_index = 'image_id_'.$i;
            
            // Удаление изображения
            if ((count($errors) === 0) && isset($_POST[$del_image_index]) && (strlen($_FILES[$add_image_index]['name']) > 0)) {
                if( false === wp_delete_attachment($_POST[$del_image_index], true) ) {
                    array_push($errors, "Не удалось удалить файл с изображением: ".$_POST[$del_image_index]->get_error_message());
                }
            }
            elseif ((count($errors) === 0) && isset($_POST[$del_image_index]) && (strlen($_FILES[$add_image_index]['name']) == 0)) {
                $image_id_lists[] = $_POST[$del_image_index];
            }

            // Добавление изображения
            if ((count($errors) === 0) && (strlen($_FILES[$add_image_index]['name']) > 0)) {
                if ($_FILES[$add_image_index]['error']) {
                    array_push($errors, "Не удалось загрузить файл с изображением: ".$file_error_message[$_FILES[$add_image_index]['error']]);
                } else {
                    // Позволим WordPress перехватить загрузку.
                    // не забываем указать атрибут name поля input
                    $attachment_id = media_handle_upload($add_image_index, 0);

                    if ( is_wp_error($attachment_id) ) {
                        array_push($errors, "Не удалось загрузить файл с изображением: ".$attachment_id->get_error_message());
                    } else {
                        $image_id_lists[] = $attachment_id;
                    }
                }
            }
        }
        
        $main_image = isset($image_id_lists[0]) ? $image_id_lists[0] : 0;
        // Обработка изображений - END

        if ($id == 0) {
                $sql = $wpdb->prepare("INSERT INTO ".TZS_PRODUCTS_TABLE.
                        " (type_id, user_id, sale_or_purchase, 	fixed_or_tender, title, description, copies, unit, currency, price, payment, nds, city_from, from_cid, from_rid, from_sid, created, comment, last_edited, active, expiration, image_id_lists, main_image_id)".
                        " VALUES (%d, %d, %d, %d, %s, %s, %d, %d, %d, %f, %d, %d, %s, %d, %d, %d, now(), %s, NULL, %d, %s, %s, %d);",
                        intval($pr_type_id), $user_id, intval($pr_sale_or_purchase), intval($pr_fixed_or_tender), stripslashes_deep($pr_title), stripslashes_deep($pr_description), intval($pr_copies), intval($pr_unit), intval($pr_currency), floatval($pr_price), intval($pr_payment), intval($pr_nds),
                        stripslashes_deep($pr_city_from), $from_info["country_id"],$from_info["region_id"],$from_info["city_id"], stripslashes_deep($pr_comment), intval($pr_active), $pr_expiration,
                        implode(';', $image_id_lists), intval($main_image));

                if (false === $wpdb->query($sql)) {
                        array_push($errors, "Не удалось опубликовать Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта");
                        array_push($errors, $wpdb->last_error);
                        tzs_print_product_form($errors, false);
                } else {
                        echo "<div>";
                        echo "<h2>Ваш товар/услуга опубликован !</h2>";
                        echo "<br/>";
                        echo '<a href="/view-product/?id='.tzs_find_latest_product_rec().'&spis=new">Просмотреть товар/услугу</a>';
                        //echo "<h3>Сейчас будет открыта страница для добавления изображений !</h3>";
                        //echo "<div>";
                        //$new_url = get_site_url().'/edit-images-pr/?id='.tzs_find_latest_product_rec().'&form_type=product';
                        $new_url = get_site_url().'/my-products';
                        echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
                }
        } else {
                $sql = $wpdb->prepare("UPDATE ".TZS_PRODUCTS_TABLE." SET ".
                        " last_edited=now(), type_id=%d, sale_or_purchase=%d, fixed_or_tender=%d, title=%s, description=%s, copies=%d, unit=%d, currency=%d, price=%f, payment=%d, nds=%d, ".
                        " city_from=%s, from_cid=%d, from_rid=%d, from_sid=%d, comment=%s, active=%d, expiration=%s, image_id_lists=%s, main_image_id=%d".
                        " WHERE id=%d AND user_id=%d;", 
                        intval($pr_type_id), intval($pr_sale_or_purchase), intval($pr_fixed_or_tender), stripslashes_deep($pr_title), stripslashes_deep($pr_description), intval($pr_copies), intval($pr_unit), intval($pr_currency), floatval($pr_price), intval($pr_payment), intval($pr_nds), 
                        stripslashes_deep($pr_city_from), $from_info["country_id"],$from_info["region_id"],$from_info["city_id"], stripslashes_deep($pr_comment), intval($pr_active), $pr_expiration, implode(';', $image_id_lists), intval($main_image),
                        $id, $user_id);

                if (false === $wpdb->query($sql)) {
                        array_push($errors, "Не удалось изменить Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта");
                        array_push($errors, $wpdb->last_error);
                        tzs_print_product_form($errors, true);
                } else {
                        echo "<div>";
                        echo "<h2>Ваш товар/услуга изменен !</h2>";
                        echo "<br/>";
                        echo '<a href="/view-product/?id='.$id.'">Просмотреть товар/услугу</a>';
                        //echo "<h3>Сейчас будет открыта страница для добавления изображений !</h3>";
                        //echo "<div>";
                        //$new_url = get_site_url().'/edit-images-pr/?id='.$id.'&form_type=product';
                        $new_url = get_site_url().'/my-products';
                        echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
                }
        } 
    }
}

function tzs_front_end_del_product_handler($attrs) {
    ob_start();

    $errors = array();
    $user_id = get_current_user_id();
    $sh_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

    if ( !is_user_logged_in() ) {
        print_error("Вход в систему обязателен");
    } else if ($sh_id <= 0) {
        print_error('Товар/услуга не найден');
    } else {
        global $wpdb;
        
        // Вначале попытаемся удалить изображения
        $sql = "SELECT * FROM ".TZS_PRODUCTS_TABLE." WHERE id=$sh_id AND user_id=$user_id;";
        $row = $wpdb->get_row($sql);
        if (count($row) === 0 && $wpdb->last_error != null) {
            array_push($errors, 'Не удалось получить список товаров. Свяжитесь, пожалуйста, с администрацией сайта');
            array_push($errors, $wpdb->last_error);
            print_errors($errors);
        } else if ($row === null) {
            print_error("Товар/услуга не найден (id=$sh_id AND user_id=$user_id)");
        } else {
            if (strlen($row->image_id_lists) > 0) {
                $img_names = explode(';', $row->image_id_lists);
                for ($i=0;$i < count($img_names);$i++) {
                    if( false === wp_delete_attachment($img_names[$i], true) ) {
                        array_push($errors, "Не удалось удалить файл с изображением: ".$img_names[$i]->get_error_message());
                    }
                }
            }
            
            if (count($errors) > 0) {
                print_errors($errors);
            } else {
                // Удаление записи
                $sql = "DELETE FROM ".TZS_PRODUCTS_TABLE." WHERE id=$sh_id AND user_id=$user_id;";
                if (false === $wpdb->query($sql)) {
                    $errors = array();
                    array_push($errors, "Не удалось удалить Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта");
                    array_push($errors, $wpdb->last_error);
                    print_errors($errors);
                } else {
                    echo "Товар/услуга удален";
                }
            }
        }
    }

    $output = ob_get_contents();
    
    ob_end_clean();
	
    return $output;
}

function tzs_front_end_edit_product_handler($atts) {
	ob_start();
	
	$user_id = get_current_user_id();
	$sh_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ( !is_user_logged_in() ) {
		print_error("Вход в систему обязателен");
	} else if ($sh_id <= 0) {
		print_error('Товар/услуга не найден');
	} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'editproduct' && ($_POST['formName'] == 'product')) {
		$id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
		tzs_edit_product($id);
	} else {
		global $wpdb;
		$sql = "SELECT * FROM ".TZS_PRODUCTS_TABLE." WHERE id=$sh_id AND user_id=$user_id;";
		$row = $wpdb->get_row($sql);
		if (count($row) == 0 && $wpdb->last_error != null) {
			print_error('Не удалось отобразить информацию о товаре/услуге. Свяжитесь, пожалуйста, с администрацией сайта');
		} else if ($row == null) {
			print_error('Товар/услуга не найден');
		} else {
                    //" (type_id, title, description, copies, currency, price, payment, city_from, comment, expiration)".
                    $_POST['id'] = ''.$row->id;
                    $_POST['pr_active'] = ''.$row->active;
                    $_POST['pr_type_id'] = ''.$row->type_id;
                    $_POST['pr_sale_or_purchase'] = ''.$row->sale_or_purchase;
                    $_POST['pr_fixed_or_tender'] = ''.$row->fixed_or_tender;
                    $_POST['pr_title'] = $row->title;
                    $_POST['pr_description'] = $row->description;
                    $_POST['pr_copies'] = ''.$row->copies;
                    $_POST['pr_unit'] = ''.$row->unit;
                    $_POST['pr_currency'] = ''.$row->currency;
                    $_POST['pr_city_from'] = $row->city_from;
                    $_POST['pr_comment'] = $row->comment;
                    $_POST["pr_image_id_lists"] = $row->image_id_lists;
                    
                    if ($row->price > 0)
                            $_POST['pr_price'] = ''.remove_decimal_part($row->price);
                    
                    if ($row->payment > 0) {
                        if (($row->payment == '1') || ($row->payment == '11'))  $_POST['nocash'] = 'on';
                        if (($row->payment == '10') || ($row->payment == '11')) $_POST['cash'] = 'on';
                    }
                    
                    if ($row->nds > 0) {
                        if (($row->nds == '1') || ($row->nds == '11'))  $_POST['nonds'] = 'on';
                        if (($row->nds == '10') || ($row->nds == '11')) $_POST['nds'] = 'on';
                    }
                    
                    if ($row->expiration !== null)
                        $_POST['pr_expiration'] = date("d.m.Y", strtotime($row->expiration));
                    
                    tzs_print_product_form(null, true);
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

function tzs_front_end_product_handler($atts) {
    ob_start();
	
    if ( !is_user_logged_in() ) {
            print_error("Вход в систему обязателен");
    } else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'addproduct' && ($_POST['formName'] == 'product')) {
            tzs_edit_product(0);
    } else {
            tzs_print_product_form(null);
    }

    $output = ob_get_contents();
    
    ob_end_clean();
	
    return $output;
}

?>