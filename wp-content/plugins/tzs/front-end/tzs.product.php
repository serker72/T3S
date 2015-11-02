<?php

include_once(TZS_PLUGIN_DIR.'/functions/tzs.product.functions.php');
include_once(TZS_PLUGIN_DIR.'/front-end/tzs.trade.images.php');

        
function tzs_print_product_form($errors, $edit=false) {
    //$d = date("d.m.Y");
    // Добавим 7 дней к текущей дате
    $dt = new DateTime();
    date_add($dt, date_interval_create_from_date_string((TZS_PR_PUBLICATION_MIN_DAYS + 1).' days'));
    $d = date_format($dt, "d.m.Y");

    //if(isset($_GET['spis'])) echo "<a id='edit_search' href='/account/my-products/'>Назад к списку</a> <div style='clear: both'></div>";
    //else echo "<button id='edit_search'  onclick='history.back()'>Назад к списку</button> <div style='clear: both'></div>";
    
    echo '<div style="clear: both;"></div>';
    print_errors($errors);
    ?>
    <script src="/wp-content/plugins/tzs/assets/js/autocomplete.js"></script>
    <div style="clear: both;"></div>
    
    <!-- test new form -->
<div class="form_wrapper">
    <form enctype="multipart/form-data" method="post" id="form_shipment" class="" action="">
        
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_pr_active" class="span2">
            <select id="pr_active" name="pr_active">
                <option value="1" <?php if (isset($_POST["pr_active"]) && ($_POST["pr_active"] === 1)) echo 'selected="selected"'; ?> >Публикуемый</option>
                <option value="0" <?php if (isset($_POST["pr_active"]) && ($_POST["pr_active"] === 0)) echo 'selected="selected"'; ?> >Архивный</option>
            </select>
        </div>
        <div id="div_pr_type_id" class="span3">
            <select id="pr_type_id" name="pr_type_id">
                <option value="0" <?php if (isset($_POST['pr_type_id']) && $_POST['pr_type_id'] == 0) echo 'selected="selected"'; ?> >Категория</option>
                <?php tzs_build_product_types('pr_type_id', TZS_PR_ROOT_CATEGORY_PAGE_ID); ?>
            </select>
            <?php wp_nonce_field( 'pr_type_id', 'pr_type_id_nonce' ); ?>
	</div>
        <div id="div_pr_sale_or_purchase" class="span2">
            <!--label for="pr_sale_or_purchase">Тип заявки</label-->
            <select id="pr_sale_or_purchase" name="pr_sale_or_purchase">
                <option value="0" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 0) echo 'selected="selected"'; ?> >Тип заявки</option>
                <option value="1" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 1) echo 'selected="selected"'; ?> >Продажа</option>
                <option value="2" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 2) echo 'selected="selected"'; ?> >Покупка</option>
            </select>
        </div>
        <div id="div_pr_fixed_or_tender" class="span2">
            <!--label for="pr_fixed_or_tender">Участник тендера</label-->
            <select id="pr_fixed_or_tender" name="pr_fixed_or_tender">
                <option value="0" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 0) echo 'selected="selected"'; ?> >Участник тендера</option>
                <option value="1" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 1) echo 'selected="selected"'; ?> >Цена зафиксирована</option>
                <option value="2" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 2) echo 'selected="selected"'; ?> >Тендерное предложение</option>
            </select>
        </div>
        <div class="span3">
            <input autocomplete="city" id="first_city" type="text" size="35" name="pr_city_from" value="<?php echo_val('pr_city_from'); ?>" autocomplete="on" placeholder="Местонахождение товара">
            <img id ="first_city_flag" src="<?php echo $edit ? echo_val('from_code') : "" ?>"  style="visibility:<?php echo $edit ? 'visible' : 'hidden' ?>" width=18 height=12 alt="Флаг страны">
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div class="span9">
            <!--label for="pr_title">Наименование</label-->
            <input type="text" name="pr_title" size="" maxlength="255" value="<?php echo_val('pr_title'); ?>" placeholder="Наименование товара" style="width: 90%;">
        </div>
        <div class="span3">
        </div>
    </div>
                    
    <div class="row-fluid"  style="width: 100%; margin-bottom: 10px;">
        <div class="span8">
            <?php
            $args = array(  'wpautop' => 1,
                            'media_buttons' => 0,
                            'textarea_name' => 'pr_description', //нужно указывать!
                            'textarea_rows' => 2,
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
            <div class="span12">
                <label>Добавить изображения (до 1Мб):</label>
            </div>
            <div class="span3">
                <img src="" width="100%">
            </div>
            <div class="span3">
                <img src="" width="100%">
            </div>
            <div class="span3">
                <img src="" width="100%">
            </div>
        </div>
    </div>
    
    <div class="row-fluid"  style="width: 100%; ">
        <div id="div_pr_copies" class="span4">
            <label for="pr_copies">Количество</label>
            <input type="number" id="pr_copies" name="pr_copies" size="2" value="<?php echo_val('pr_copies'); ?>" min="0" style="width: 80px;">
            <select for="pr_copies" name="pr_unit" style="width: 80px;">
            <?php
                tzs_print_array_options($GLOBALS['tzs_pr_unit'], '', 'pr_unit', '');
            ?>
            </select>
        </div>
        <div id="div_pr_price" class="span4">
            <label for="pr_price">Стоимость</label>
            <input type="text" id="pr_price" name="pr_price" size="10" value="<?php echo_val('pr_price'); ?>" style="width: 80px;">
            <select for="pr_price" name="pr_currency" style="width: 80px;">
            <?php
                tzs_print_array_options($GLOBALS['tzs_pr_curr'], '', 'pr_currency', '');
            ?>
            </select>
        </div>
        <div class="span4">
            <label for="pr_expiration">Окончание публикации</label>
            <input type="text" id="datepicker1" name="pr_expiration" size="" value="<?php echo_val_def('pr_expiration', $d); ?>" placeholder="Дата выгрузки" readonly="true" style="width: 80px;">
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span4">
            <label for="cost">Стоимость перевозки:</label>&nbsp;
            <input type="text" id="cost" name="cost" value="<?php echo_val('cost'); ?>" size="10" style="width: 100px;">
            <div class="post-input">грн</div>
            <input type="hidden" name="cost_curr" id="cost_curr" value="1">
        </div>
        <div class="span4">
            <label for="price">Цена&nbsp;=</label>&nbsp;
            <input type="text" id="price" name="price" value="<?php echo_val('price'); ?>" size="10" readonly="true" style="width: 100px;">
            <div class="post-input">грн/км</div>
        </div>
        <div class="span1">
        </div>
        <div class="span3"><!-- style="text-align: right; float: right;"-->
            <label for="sh_volume">Объем груза&nbsp;=</label>&nbsp;
            <input type="text" id="sh_volume" name="sh_volume" value="<?php echo_val('sh_volume'); ?>" readonly="true" style="width: 80px;">
            <div class="post-input">м<sup>3</sup></div>
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
        <div class="span2 chekbox">
            <input type="checkbox" id="cash" name="cash" <?php echo isset($_POST['cash']) ? 'checked="checked"' : ''; ?>><label for="cash">Наличная</label>
        </div>
        <div class="span2 chekbox">
            <input type="checkbox" id="nocash" name="nocash" <?php echo isset($_POST['nocash']) ? 'checked="checked"' : ''; ?>><label for="nocash">Безналичная</label>
        </div>
        <div class="span2 chekbox">
            <input type="checkbox" id="way_ship" name="way_ship" <?php echo isset($_POST['way_ship']) ? 'checked="checked"' : ''; ?>><label for="way_ship">При погрузке</label>
        </div>
        <div class="span2 chekbox">
            <input type="checkbox" id="way_debark" name="way_debark" <?php echo isset($_POST['way_debark']) ? 'checked="checked"' : ''; ?>><label for="way_debark">При выгрузке</label>
        </div>
        <div class="span1 chekbox">
            <input type="checkbox" id="soft" name="soft" <?php echo isset($_POST['soft']) ? 'checked="checked"' : ''; ?>><label for="soft">Софт</label>
        </div>
        <div class="span2 chekbox" style="text-align: right;">
            <input type="checkbox" id="way_prepay" name="way_prepay" <?php echo isset($_POST['way_prepay']) ? 'checked="checked"' : ''; ?> ><label for="way_prepay">Предоплата</label>
        </div>
        <div class="span1" style="padding-top: 10px;">
            <input type="text" id="prepayment" name="prepayment" value="<?php echo_val('prepayment'); ?>" size="5" placeholder = "0" style="width: 20px;"><div class="post-input">%</div>
        </div>
    </div>

    <div class="row-fluid"  style="width: 100%; ">
        <div class="span8">
            <div class="span12 chekbox" style="margin-bottom: 20px;">
                <input type="checkbox" id="price_query" name="price_query" <?php echo isset($_POST['price_query']) ? 'checked="checked"' : ''; ?>>&nbsp;<label for="price_query">Не указывать стоимость (цена договорная)</label>
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
	<input type="hidden" name="formName" value="shipment" />
    </form>
</div>
    <div class="clearfix">&nbsp;</div>
    
    <!-- test new form END -->

    
    <form enctype="multipart/form-data" method="post" id="bpost" class="pr_edit_form post-form" action="">

<!-- Новый вид формы, навеяно http://xiper.net/collect/html-and-css-tricks/verstka-form/blochnaya-verstka-form -->
        <div>
            <hr/>
            <!--h3>Добавление товара или услуги</h3-->
            <!--p>Укажите, пожалуйста, категорию, наименование, описание, количество, стоимость, форму оплаты, месторасположение, дату окончания публикации товара и комментарии</p-->
            <p>Минимальный период публикации товара - <strong><?php echo TZS_PR_PUBLICATION_MIN_DAYS; ?></strong> дней.<br/>При наступлении даты окончания публикации товар будет автоматически перенесен в архив.</p>
            <hr/>
        </div>
	<?php if ($edit) {?>
        <div class="pr_edit_form_line">
            <label for="pr_id">Номер</label>
            <input type="text" id="" name="pr_id" size="15" value="<?php echo_val('id'); ?>" disabled="disabled">
        </div>
	<?php } ?>
        <div class="pr_edit_form_line">
            <label for="pr_type_id">Статус</label>
            <select name="pr_active">
                <option value="1" <?php if (isset($_POST["pr_active"]) && ($_POST["pr_active"] === 1)) echo 'selected="selected"'; ?> >Публикуемый</option>
                <option value="0" <?php if (isset($_POST["pr_active"]) && ($_POST["pr_active"] === 0)) echo 'selected="selected"'; ?> >Архивный</option>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_type_id">Категория</label>
            <select name="pr_type_id">
                <?php tzs_build_product_types('pr_type_id', TZS_PR_ROOT_CATEGORY_PAGE_ID); ?>
            </select>
            <?php wp_nonce_field( 'pr_type_id', 'pr_type_id_nonce' ); ?>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_sale_or_purchase">Тип заявки</label>
            <select name="pr_sale_or_purchase">
                <option value="1" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 1) echo 'selected="selected"'; ?> >Продажа</option>
                <option value="2" <?php if (isset($_POST['pr_sale_or_purchase']) && $_POST['pr_sale_or_purchase'] == 2) echo 'selected="selected"'; ?> >Покупка</option>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_fixed_or_tender">Участник тендера</label>
            <select name="pr_fixed_or_tender">
                <option value="1" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 1) echo 'selected="selected"'; ?> >Цена зафиксирована</option>
                <option value="2" <?php if (isset($_POST['pr_fixed_or_tender']) && $_POST['pr_fixed_or_tender'] == 2) echo 'selected="selected"'; ?> >Тендерное предложение</option>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_title">Наименование</label>
            <input type="text" id="pr_edit_text_big" name="pr_title" size="135" maxlength="255" value="<?php echo_val('pr_title'); ?>">
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_description">Описание</label>
            <?php
                /*$args = array(  'wpautop' => 1,
                                'media_buttons' => 0,
                                'textarea_name' => 'pr_description', //нужно указывать!
                                'textarea_rows' => 10,
                                'tabindex'      => null,
                                'editor_css'    => '',
                                'editor_class'  => '',
                                'teeny'         => 1,
                                'dfw'           => 0,
                                'tinymce'       => 1,
                                'quicktags'     => array(
                                                    'id' => 'editpost',
                                                    'buttons' => 'strong,em,ul,ol,li,close'
                                                    ),
                                'drag_drop_upload' => false
                            );
                wp_editor($_POST['pr_description'], 'editpost', $args);*/
            ?>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_copies">Количество</label>
            <input type="number" id="" name="pr_copies" size="2" value="<?php echo_val('pr_copies'); ?>" min="0">
            <select for="pr_copies" name="pr_unit">
            <?php
                foreach ($GLOBALS['tzs_pr_unit'] as $key => $val) {
                        echo '<option value="'.$key.'" ';
                        if ($val == '')
                                $val = '-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-';
                        if (isset($_POST['pr_unit']) && $_POST['pr_unit'] == $key && $key != 0) {
                                echo 'selected="selected"';
                        }
                        if ($key == 0) {
                                echo 'disabled="disabled"';
                        }
                        //echo '>'.htmlspecialchars($val).'</option>\n';
                        echo '>'.$val.'</option>\n';
                }
            ?>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_price">Стоимость</label>
            <input type="text" id="" name="pr_price" size="10" value="<?php echo_val('pr_price'); ?>">
            <select for="price" name="pr_currency">
            <?php
                foreach ($GLOBALS['tzs_pr_curr'] as $key => $val) {
                        echo '<option value="'.$key.'" ';
                        if ($val == '')
                                $val = '-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-';
                        if (isset($_POST['pr_currency']) && $_POST['pr_currency'] == $key && $key != 0) {
                                echo 'selected="selected"';
                        }
                        if ($key == 0) {
                                echo 'disabled="disabled"';
                        }
                        //echo '>'.htmlspecialchars($val).'</option>\n';
                        echo '>'.$val.'</option>\n';
                }
            ?>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_payment">Форма оплаты</label>
            <select name="pr_payment">
            <?php
                foreach ($GLOBALS['tzs_pr_payment'] as $key => $val) {
                        echo '<option value="'.$key.'" ';
                        if ($val == '')
                                $val = '-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-';
                        if (isset($_POST['pr_payment']) && $_POST['pr_payment'] == $key) { // && $key != 0
                                echo 'selected="selected"';
                        }
                        if ($key == 0) {
                            //echo 'disabled="disabled"';
                        }
                        //echo '>'.htmlspecialchars($val).'</option>\n';
                        echo '>'.$val.'</option>\n';
                }
            ?>
            </select>
            <select name="pr_nds">
                <option value="0" <?php if (isset($_POST['pr_nds']) && $_POST['pr_nds'] == 0) echo 'selected="selected"'; ?> disabled="disabled">---------------</option>
                <option value="1" <?php if (isset($_POST['pr_nds']) && $_POST['pr_nds'] == 1) echo 'selected="selected"'; ?> >Без НДС</option>
                <option value="2" <?php if (isset($_POST['pr_nds']) && $_POST['pr_nds'] == 2) echo 'selected="selected"'; ?> >Включая НДС</option>
            </select>
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_city_from">Местонахождение</label>
            <input autocomplete="city" id="pr_edit_text_big" type="text" size="135" name="pr_city_from" value="<?php echo_val('pr_city_from'); ?>" autocomplete="off">
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_expiration">Окончание публикации</label>
            <input type="text" id="datepicker1" name="pr_expiration" size="" value="<?php echo_val_def('pr_expiration', $d); ?>">
        </div>
        <div class="pr_edit_form_line">
            <label for="pr_comment">Комментарии</label>
            <input type="text" id="pr_edit_text_big" name="pr_comment" size="135" value="<?php echo_val('pr_comment'); ?>">
        </div>

	<?php if ($edit) {?>
		<input type="hidden" name="action" value="editproduct"/>
		<input type="hidden" name="id" value="<?php echo_val('id'); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="addproduct"/>
	<?php } ?>
	<input type="hidden" name="formName" value="product" />
        <table>
            <tr>
                <td width="130px">&nbsp;</td>
                <td>
                    <input name="addpost" type="submit" id="addpostsub" class="submit_button" value="<?php echo $edit ? "Изменить" : "Разместить" ?>"/>
                </td>
                <td width="15px">&nbsp;</td>
                <td>
                    <?php if ($edit) { ?>
                        <a href="/edit-images-pr/?id=<?php echo_val('id'); ?>&form_type=product" id="edit_images">Загрузить/обновить изображения</a>
                        <!--button id="edit_images" onClick="javascript: window.open('/edit-images-pr/?id=<?php echo_val('id');?>&form_type=product', '_self');">Изменить изображения</button-->
                    <?php }?>
                </td>
            </tr>
        </table>
    </form>
	
    <script>
        jQuery(document).ready(function(){
            jQuery('#bpost').submit(function() {
                    jQuery('#addpostsub').attr('disabled','disabled');
                    return true;
            });
            jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
            jQuery( "#datepicker1" ).datepicker({ dateFormat: "dd.mm.yy" });
            
            jQuery("[name=pr_sale_or_purchase]").change(function (eventObject) {
                if (eventObject.target.value == 2) {
                    jQuery("[name=pr_fixed_or_tender]").attr('value', 2);
                    jQuery('[name=pr_fixed_or_tender]').attr('disabled', 'disabled');
                } else {
                    jQuery('[name=pr_fixed_or_tender]').removeAttr('disabled');
                }
            });
        });
    </script>
<?php
}

function tzs_edit_product($id) {
    $errors = array();
    
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
	$pr_payment = get_param_def('pr_payment','0');
	$pr_nds = get_param_def('pr_nds','0');
	$pr_city_from = get_param('pr_city_from');
	$pr_comment = get_param('pr_comment');
	$pr_expiration = get_param('pr_expiration');
	
        
	if (is_valid_date($pr_expiration) === null) {
            array_push($errors, "Неверный формат даты");
	} else {
            $cur_date = new DateTime();
            $exp_date = new DateTime($pr_expiration);
            $interval = date_diff($cur_date, $exp_date);
            if ($interval->days < TZS_PR_PUBLICATION_MIN_DAYS) {
                array_push($errors, "Минимальный срок публикации ".TZS_PR_PUBLICATION_MIN_DAYS." дней");
            }
        }

	$pr_expiration = is_valid_date($pr_expiration);
        
	if (!is_valid_city($pr_city_from)) {
            array_push($errors, "Неверный населенный пункт");
	}
        
	if (strlen($pr_title) < 2) {
            array_push($errors, "Введите наименование товара");
	}
        
	if (strlen($pr_description) < 2) {
            array_push($errors, "Введите описание товара");
	}
        
	if (!is_valid_num_zero($pr_type_id)) {
            array_push($errors, "Неверно задана категория товара");
	}
        
	if (!is_valid_num_zero($pr_sale_or_purchase)) {
            array_push($errors, "Неверно задан тип операции");
	}
        
	if (!is_valid_num_zero($pr_fixed_or_tender)) {
            array_push($errors, "Неверно задан тип ценового предложения");
	}
        
        
	if (!is_valid_num_zero($pr_active)) {
            array_push($errors, "Неверно задан статус товара");
	}
        
	if (!is_valid_num_zero($pr_copies)) {
            array_push($errors, "Неверно задано количество экземпляров товара");
	}
        
	if (!is_valid_num_zero($pr_unit)) {
            array_push($errors, "Неверно задана единица измерения количества экземпляров товара");
	}
        
	if (!is_valid_num_zero($pr_currency)) {
            array_push($errors, "Неверно задана валюта");
	}
        
	if (!is_valid_num_zero($pr_payment)) {
            array_push($errors, "Неверно задана форма оплаты");
	}
        
	if (!is_valid_num_zero($pr_nds)) {
            array_push($errors, "Неверно задан переключатель наличия НДС");
	}
        
	if (!is_valid_num_zero($pr_price)) {
            array_push($errors, "Неверно задана стоимость товара");
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

        if ($id == 0) {
                $sql = $wpdb->prepare("INSERT INTO ".TZS_PRODUCTS_TABLE.
                        " (type_id, user_id, sale_or_purchase, 	fixed_or_tender, title, description, copies, unit, currency, price, payment, nds, city_from, from_cid, from_rid, from_sid, created, comment, last_edited, active, expiration)".
                        " VALUES (%d, %d, %d, %d, %s, %s, %d, %d, %d, %f, %d, %d, %s, %d, %d, %d, now(), %s, NULL, %d, %s);",
                        intval($pr_type_id), $user_id, intval($pr_sale_or_purchase), intval($pr_fixed_or_tender), stripslashes_deep($pr_title), stripslashes_deep($pr_description), intval($pr_copies), intval($pr_unit), intval($pr_currency), floatval($pr_price), intval($pr_payment), intval($pr_nds),
                        stripslashes_deep($pr_city_from), $from_info["country_id"],$from_info["region_id"],$from_info["city_id"], stripslashes_deep($pr_comment), intval($pr_active), $pr_expiration);

                if (false === $wpdb->query($sql)) {
                        array_push($errors, "Не удалось опубликовать Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта");
                        array_push($errors, $wpdb->last_error);
                        tzs_print_product_form($errors, false);
                } else {
                        echo "<div>";
                        echo "<h2>Ваш товар/услуга опубликован !</h2>";
                        echo "<br/>";
                        //echo '<a href="/view-product/?id='.tzs_find_latest_product_rec().'&spis=new">Просмотреть товар/услугу</a>';
                        echo "<h3>Сейчас будет открыта страница для добавления изображений !</h3>";
                        echo "<div>";
                        $new_url = get_site_url().'/edit-images-pr/?id='.tzs_find_latest_product_rec().'&form_type=product';
                        echo '<meta http-equiv="refresh" content="0; url='.$new_url.'">';
                }
        } else {
                $sql = $wpdb->prepare("UPDATE ".TZS_PRODUCTS_TABLE." SET ".
                        " last_edited=now(), type_id=%d, sale_or_purchase=%d, fixed_or_tender=%d, title=%s, description=%s, copies=%d, unit=%d, currency=%d, price=%f, payment=%d, nds=%d, ".
                        " city_from=%s, from_cid=%d, from_rid=%d, from_sid=%d, comment=%s, active=%d, expiration=%s".
                        " WHERE id=%d AND user_id=%d;", 
                        intval($pr_type_id), intval($pr_sale_or_purchase), intval($pr_fixed_or_tender), stripslashes_deep($pr_title), stripslashes_deep($pr_description), intval($pr_copies), intval($pr_unit), intval($pr_currency), floatval($pr_price), intval($pr_payment), intval($pr_nds), 
                        stripslashes_deep($pr_city_from), $from_info["country_id"],$from_info["region_id"],$from_info["city_id"], stripslashes_deep($pr_comment), intval($pr_active), $pr_expiration,
                        $id, $user_id);

                if (false === $wpdb->query($sql)) {
                        array_push($errors, "Не удалось изменить Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта");
                        array_push($errors, $wpdb->last_error);
                        tzs_print_product_form($errors, true);
                } else {
                        echo "<div>";
                        echo "<h2>Ваш товар/услуга изменен !</h2>";
                        echo "<br/>";
                        //echo '<a href="/view-product/?id='.$id.'">Просмотреть товар/услугу</a>';
                        echo "<h3>Сейчас будет открыта страница для добавления изображений !</h3>";
                        echo "<div>";
                        $new_url = get_site_url().'/edit-images-pr/?id='.$id.'&form_type=product';
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
                    $_POST['pr_type_id'] = ''.$row->type_id;
                    $_POST['pr_title'] = $row->title;
                    $_POST['pr_description'] = $row->description;
                    $_POST['pr_copies'] = ''.$row->copies;
                    $_POST['pr_currency'] = ''.$row->currency;
                    if ($row->price > 0)
                            $_POST['pr_price'] = ''.remove_decimal_part($row->price);
                    $_POST['pr_payment'] = ''.$row->payment;
                    $_POST['pr_city_from'] = $row->city_from;
                    $_POST['pr_comment'] = $row->comment;
                    if ($row->expiration !== null)
                        $_POST['pr_expiration'] = date("d.m.Y", strtotime($row->expiration));
                    $_POST['id'] = ''.$row->id;
                    
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