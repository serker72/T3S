<?php

include_once(TZS_PLUGIN_DIR.'/functions/privat24api.php');

function tzs_front_end_view_order_handler($atts) {
    ob_start();

    global $wpdb;

    $user_id = get_current_user_id();

    // Получен ответ от Приват-24
    if (!empty($_POST) && !empty($_POST["payment"]) && !empty($_POST["signature"])) {
        $res_arr = tzs_pay_order_p24(1);
        $order_id = $res_arr['order_id'];
    } else {
        $order_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    }


    if (strlen($res_arr['output_error']) > 0) {
        print_error($res_arr['output_error']);
    } elseif ($order_id <= 0) {
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

            ?>
            <div class="row-fluid" id="contact-block-right" >
                <div class="span2 offset10">
                    <?php 
                    if (isset($_GET['spis'])) {
                        echo "<a id='edit_search' href='/account/my-orders/'>Назад к списку</a> <div style='clear: both'></div>";
                    } elseif (isset($_GET['link'])) {
                        echo "<a id='edit_search' href='/".$_GET['link'].(isset($_GET['status']) ? "/?status=".$_GET['status'] : "/")."'>Назад к списку</a> <div style='clear: both'></div>";
                    } else {
                        echo "<a id='edit_search' href='/account/my-orders/?status=1'>Назад к списку</a> <div style='clear: both'></div>";
                        //echo "<button id='edit_search'  onclick='history.back()'>Назад к списку</button> <div style='clear: both'></div>";
                    }

                    if ($row->status == 0) {
                        $payment = 'amt='.$row->cost.'&ccy=UAH&details=Оплата услуги поднятия объявления '.$row->tbl_type.'.'.$row->tbl_id.' на портале t3s.biz согласно счета '.$row->number.'&ext_details='.$row->tbl_type.'.'.$row->tbl_id.'&pay_way=privat24&order='.($row->pay_count > 0 ? $row->number.'.'.$row->pay_count : $row->number).'&merchant='.get_option('t3s_setting_merchant_id');
                        $pass = get_option('t3s_setting_merchant_pass');
                        $signature = sha1(md5($payment.$pass));
                        ?>
                        <div style="margin-top: 15px;">
                            <form id="PayOrderForm" class="pr_edit_form" action="https://api.privatbank.ua/p24api/ishop" method="POST" accept-charset="UTF-8">
                                <input type="hidden" name="amt" value="<?php echo $row->cost; ?>"/>
                                <input type="hidden" name="ccy" value="UAH"/>
                                <input type="hidden" name="merchant" value="<?php echo get_option('t3s_setting_merchant_id');?>" />
                                <input type="hidden" name="order" value="<?php echo $row->pay_count > 0 ? $row->number.'.'.$row->pay_count : $row->number; ?>"/>
                                <input type="hidden" name="details" value="Оплата услуги поднятия объявления <?php echo $row->tbl_type.'.'.$row->tbl_id; ?> на портале t3s.biz согласно счета <?php echo $row->number; ?>"/>
                                <input type="hidden" name="ext_details" value="<?php echo $row->tbl_type.'.'.$row->tbl_id; ?>"/>
                                <input type="hidden" name="pay_way" value="privat24" />
                                <input type="hidden" name="return_url" value="http://t3s.biz/account/view-order/" />
                                <input type="hidden" name="server_url" value="http://t3s.biz/account/pay-order/" />
                                <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
                            </form>
                            <!--a id="view_edit"  onClick="javascript: window.location.href = '/account/pay-order/?id=<?php //echo $row->id;?>';">Оплатить</a-->
                            <a id="view_edit"  onClick="javascript:onPayOrder()">Оплатить</a>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>

            <div class="container" id="product-container">

                <div class="row-fluid" >
                    <div class="span4" id="img_kart">
                        <ul class="thumbnails"  style="max-height: 470px;">
                            <li class="span12">
                                <a href="#" class="thumbnail" id="general-img">
                                    <img id="general" src="/wp-content/plugins/tzs/assets/images/stack_of_coins.jpg" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="span6" id="descript">
                        <div class="well well-large">
                            <div id="labeltxt">
                                <div class="pull-left label-txt">
                                    <label><strong>ID счета:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->id; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Статус счета:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->status == 0 ? 'Неоплаченный' : ($row->status == 1 ? 'Действующий' : 'Архивный'); ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Рубрика объявления:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $tbl_type_txt; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>ID объявления:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <a href="/account/view-<?php echo $tbl_type_link; ?>/?id=<?php echo $row->tbl_id; ?>"><?php echo $row->tbl_id; ?></a>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Номер счета:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->pay_count > 0 ? $row->number.'.'.$row->pay_count : $row->number; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Сумма счета:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->cost." ".$GLOBALS['tzs_curr'][$row->currency]; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Дата создания:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->dt_create ? convert_time($row->dt_create) : ''; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Дата оплаты:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->dt_pay ? convert_time($row->dt_pay) : ''; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="pull-left label-txt">
                                    <label><strong>Дата окончания:</strong></label>
                                </div>
                                <div class="pull-left">
                                    <?php echo $row->dt_expired ? convert_date($row->dt_expired) : ''; ?>
                                </div>
                                <div class="clearfix"></div>

                                <div class="text-center text-warning hide" id="order-pay-status">
                                </div>
                                <div class="text-center text-error hide" id="order-pay-error">
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-center">
                                    <?php
                                    /*echo 'order_dt_pay - '.$res_arr['order_dt_pay'].'<br>';
                                    echo 'ts - '.$res_arr['ts'].'<br>';
                                    echo 'dt - '.$res_arr['dt'].'<br>';
                                    echo 'tse - '.$res_arr['tse'].'<br>';
                                    echo 'dte - '.$res_arr['dte'].'<br>';
                                    */?>
                                </div>
                                <div class="clearfix"></div>

                            </div>
                        </div>
                    </div>

                    <div class="span2" id="left-control">
                    </div>
                </div>

            </div>
            <script>
                function onPayOrder() {
                    jQuery("#order-pay-status").html("Подождите...Идет подключение к Приват-24...");
                    jQuery("#order-pay-status").removeClass("hide");
                    fd = "order=<?php echo $row->pay_count > 0 ? $row->number.'.'.$row->pay_count : $row->number; ?>&pay_count=<?php echo $row->pay_count; ?>&order_id=<?php echo $row->id; ?>&order_number=<?php echo $row->number; ?>";
                    jQuery.ajax({
                        url: "/wp-admin/admin-ajax.php?action=tzs_check_order_p24",
                        type: "POST",
                        data: fd,
                        dataType: 'json',
                        success: function(data) {
                            //alert('output_error='+data.output_error+'\norder_status='+data.order_status+'\norder_id='+data.order_id+'\norder_state='+data.output_state);

                            if (data.order_status == '0') {
                                jQuery('[name=order]').attr('value', data.order_new_number);
                            }

                            if ((data.order_status == '1') || (data.order_status == '0')) {
                                jQuery("#order-pay-status").html("Подождите...Идет отправка формы создания документа...");
                                jQuery('#PayOrderForm').submit();
                            } else {
                                if ((data.output_error !== 'undefined') && (data.output_error !== '')) {
                                    jQuery("#order-pay-error").html(data.output_error);
                                    jQuery("#order-pay-error").removeClass("hide");
                                }

                                jQuery("#order-pay-status").addClass("hide");
                            }

                            if ((data.order_id !== 'undefined') && (data.order_id !== '')) {
                            }
                        },
                        error: function(data) {
                            if (data.responseText !== 'undefined') {
                                alert(data.responseText);
                            }
                        }			
                    });

                }
            </script>
                <?php
        }
    }

    $output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}
?>