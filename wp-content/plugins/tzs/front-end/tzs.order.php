<?php

//include_once(TZS_PLUGIN_DIR.'/functions/p24api.php');

/*
 * Вывод формы для оплаты счета
 */
function tzs_print_pay_order_form($errors, $edit=false) {
    global $wpdb;

    $user_id = get_current_user_id();

    $order_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

    if ($order_id <= 0) {
            print_error('Счет не найден');
    } else {
            $sql = "SELECT * FROM ".TZS_ORDERS_TABLE." WHERE id=$order_id;";
            $row = $wpdb->get_row($sql);
            if (count($row) == 0 && $wpdb->last_error != null) {
                    print_error('Не удалось отобразить информацию о счете. Свяжитесь, пожалуйста, с администрацией сайта.');
            } else if ($row == null) {
                    print_error('Счет не найден');
            } else {
                if ($row->tbl_type === 'SH') {
                    $tbl_type_txt = 'Товар';
                    $tbl_type_link = 'shipment';
                } else if ($row->tbl_type === 'TR') {
                    $tbl_type_txt = 'Транспорт';
                    $tbl_type_link = 'truck';
                } else {
                    $tbl_type_txt = 'Торговля';
                    $tbl_type_link = 'product';
                }
                $payment = 'amt='.$row->cost.'&ccy=UAH&details=Оплата услуги поднятия объявления '.$row->tbl_type.'.'.$row->tbl_id.' на портале t3s.biz&ext_details='.$row->tbl_id.'&pay_way=privat24&order='.$row->number.'&merchant='.get_option('t3s_setting_merchant_id');
                $pass = get_option('t3s_setting_merchant_pass');
                $signature = sha1(md5($payment.$pass));
    ?>
                <div style="clear: both;"></div>
                <!-- Новый вид формы, навеяно http://xiper.net/collect/html-and-css-tricks/verstka-form/blochnaya-verstka-form -->
                <form id="PayOrderForm" class="pr_edit_form" action="https://api.privatbank.ua/p24api/ishop" method="POST" accept-charset="UTF-8">
                    <div class="pr_edit_form_line">
                        <label for="amt">Сумма платежа</label>
                        <input type="text" name="amt" value="<?php echo $row->cost; ?>" disabled="disabled"/>
                    </div>
                    <div class="pr_edit_form_line">
                        <label for="ccy">Валюта платежа</label>
                        <input type="text" name="ccy" value="UAH" disabled="disabled"/>
                    </div>
                    <input type="hidden" name="merchant" value="<?php echo get_option('t3s_setting_merchant_id');?>" />
                    <div class="pr_edit_form_line">
                        <label for="order">Номер счета</label>
                        <input type="text" name="order" value="<?php echo $row->number; ?>" disabled="disabled"/>
                    </div>
                    <div class="pr_edit_form_line">
                        <label for="details">Назначение платежа</label>
                        <input type="text" id="pr_edit_text_big" name="details" value="Оплата услуги поднятия объявления <?php echo $row->tbl_type.'.'.$row->tbl_id; ?> на портале t3s.biz" disabled="disabled"/>
                        <!--id="pr_edit_text_big"-->
                    </div>
                    <div class="pr_edit_form_line">
                        <label for="ext_details">ID объявления</label>
                        <input type="text" name="ext_details" value="<?php echo $row->tbl_id; ?>" disabled="disabled"/>
                    </div>

                    <input type="hidden" name="pay_way" value="privat24" />
                    <input type="hidden" name="return_url" value="https://t3s.biz/account/pay-order/" />
                    <input type="hidden" name="server_url" value="" />
                    <input type="hidden" name="signature" value="<?php echo $signature; ?>" />

                    <!--input type="submit" id="addpostsub" value="Оплатить" /-->
                    <button id="addpostsub" onClick="doPayOrder();">Оплатить</button>
                </form>
                <script>
                    function doPayOrder() {
                        jQuery('[name=amt]').removeAttr('disabled');
                        jQuery('[name=ccy]').removeAttr('disabled');
                        jQuery('[name=order]').removeAttr('disabled');
                        jQuery('[name=details]').removeAttr('disabled');
                        jQuery('[name=ext_details]').removeAttr('disabled');
                        fd = jQuery('#PayOrderForm').serialize();
                        alert(fd);
                        jQuery('#PayOrderForm').submit();
                    }
                </script>
    <?php
            }
    }
}

/*
 * Оплата счета
 */
function tzs_pay_order() {
    global $wpdb;
    
    $errors = array();
    
    $user_id = get_current_user_id();
    
    $order_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

    if ($order_id <= 0) {
            print_error('Счет не найден');
    } else {
            $sql = "SELECT * FROM ".TZS_ORDERS_TABLE." WHERE id=$order_id;";
            $row = $wpdb->get_row($sql);
            if (count($row) == 0 && $wpdb->last_error != null) {
                    print_error('Не удалось отобразить информацию о счете. Свяжитесь, пожалуйста, с администрацией сайта.');
            } else if ($row == null) {
                    print_error('Счет не найден');
            } else {
                $PrivatAPI = new p24api(get_option('t3s_setting_merchant_id'), get_option('t3s_setting_merchant_pass'), 'https://api.privatbank.ua/p24api/ishop');
                $payments = array();
                $payments[0] = array(
                    'id' => 1,
                    'amt' => $row->cost,
                    'ccy' => 'UAH',
                    'merchant' => get_option('t3s_setting_merchant_id'),
                    'order' => $row->number,
                    'details' => 'Оплата услуги поднятия объявления '.$row->tbl_type.'.'.$row->tbl_id.' на портале t3s.biz',
                    'ext_details' => $row->tbl_id,
                );
                echo '<p><pre>';
                print_r($payments);
                
                $pay_status = $PrivatAPI->sendCmtRequest($payments, 60, true);
                
                print_r($pay_status);
                print_r($PrivatAPI->getErrMessage());
                echo '</pre></p>';
            }
    }

/*    
    $pr_payment = get_param('payment');
    $pr_signature = get_param('signature');
    
    echo '<p>payment = '.$pr_payment.'</p>';
    echo '<p>signature = '.$pr_signature.'</p>';
 */
}

function tzs_front_end_order_handler($atts) {
    ob_start();
	
    if ( !is_user_logged_in() ) {
        print_error("Вход в систему обязателен");
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['payment']) && !empty($_POST['signature'])) {
        tzs_pay_order();
    } else {
        //tzs_print_pay_order_form(null);
        tzs_pay_order();
    }

    $output = ob_get_contents();
    
    ob_end_clean();
	
    return $output;
}

?>
