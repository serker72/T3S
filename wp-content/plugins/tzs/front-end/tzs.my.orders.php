<?php

function tzs_front_end_my_orders_handler($atts) {
    ob_start();

    global $wpdb;

    $user_id = get_current_user_id();
    $url = current_page_url();
    $page = current_page_number();
    $pp = TZS_RECORDS_PER_PAGE;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '0';

    if ($user_id == 0) {
            ?>
            <div>Для просмотра необходимо <a href="/account/login/">войти</a> или <a href="/account/registration/">зарегистрироваться</a></div>
            <?php
    } else {
        $sql = "SELECT COUNT(*) as cnt FROM ".TZS_ORDERS_TABLE." WHERE user_id=$user_id AND status=$status;";
        $res = $wpdb->get_row($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
            print_error('Не удалось отобразить список счетов. Свяжитесь, пожалуйста, с администрацией сайта');
        } else {
            $records = $res->cnt;
            $pages = ceil($records / $pp);
            if ($pages == 0)
                    $pages = 1;
            if ($page > $pages)
                    $page = $pages;

            $from = ($page-1) * $pp;
            $sql = "SELECT * FROM ".TZS_ORDERS_TABLE."  WHERE user_id=$user_id AND status=$status ORDER BY dt_create DESC LIMIT $from,$pp;";
            
            $res = $wpdb->get_results($sql);
            if (count($res) == 0 && $wpdb->last_error != null) {
                print_error('Не удалось отобразить список счетов. Свяжитесь, пожалуйста, с администрацией сайта');
            } else {
                ?>
                <div id="my_products_wrapper">

                    <div id="my_products_table">
                        <table id="tbl_products">
                        <thead>
                            <tr id="tbl_thead_records_per_page">
                                <th colspan="4">
                                    <div class="div_td_left">
                                        <h3>Список <?php echo ($status === '0') ? 'неоплаченных' : (($status === '1') ? 'действующих' : 'архивных'); ?> счетов</h3>
                                    </div>
                                </th>
                                
                                <th colspan="5">
                                    <div id="my_products_button">
                                        <?php if ($status !== '0') { ?>
                                            <button id="view_del" onClick="javascript: window.open('/account/my-orders/?status=0', '_self');">Показать неоплаченные</button>
                                        <?php } ?>
                                            
                                        <?php if ($status !== '1') { ?>
                                            <button id="view_edit" onClick="javascript: window.open('/account/my-orders/?status=1', '_self');">Показать действующие</button>
                                        <?php } ?>
                                            
                                        <?php if ($status !== '2') { ?>
                                            <button id="view_edit" onClick="javascript: window.open('/account/my-orders/?status=2', '_self');">Показать архивные</button>
                                        <?php } ?>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th id="">ID счета</th>
                                <th id="">Рубрика</th>
                                <th id="">ID заявки</th>
                                <th id="">Номер счета</th>
                                <th id="">Сумма счета</th>
                                <th id="">Дата и время создания</th>
                                <th id="">Дата и время оплаты</th>
                                <th id="">Дата окончания</th>
                                <th id="actions" nonclickable="true">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ( $res as $row ) {
                                ?>
                                <tr rid="<?php echo $row->id;?>">
                                <td>
                                    <?php echo $row->id;?>
                                </td>
                                <td>
                                    <?php echo ($row->tbl_type === 'PR' ? 'Торговля' : ($row->tbl_type === 'TR' ? 'Транспорт' : 'Товар'));?>
                                </td>
                                <td>
                                    <?php echo $row->tbl_id;?>
                                </td>
                                <td>
                                    <?php echo $row->number;?>
                                </td>
                                <td>
                                    <?php echo $row->cost." ".$GLOBALS['tzs_curr'][$row->currency]; ?>
                                </td>
                                <td>
                                    <?php echo convert_time($row->dt_create);?>
                                </td>
                                <td>
                                    <?php echo $row->dt_pay ? convert_time($row->dt_pay) : '';?>
                                </td>
                                <td>
                                    <?php echo $row->dt_expired ? convert_date($row->dt_expired) : '';?>
                                </td>
                                <td>
                                        <a href="javascript:doDisplay(<?php echo $row->id;?>);" at="<?php echo $row->id;?>" id="icon_set">Действия</a>
                                        <div id="menu_set" id2="menu" for="<?php echo $row->id;?>" style="display:none;">
                                                <ul>
                                                    <?php if ($status === '0') { ?>
                                                        <a href="/account/view-order/?id=<?php echo $row->id;?>&link=my-orders&status=<?php echo $status; ?>">Оплатить</a>
                                                    <?php } ?>
                                                        <a href="/account/view-order/?id=<?php echo $row->id;?>&link=my-orders&status=<?php echo $status; ?>">Смотреть</a>
                                                </ul>
                                        </div>
                                </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>
                </div>

    <script src="/wp-content/plugins/tzs/assets/js/jquery.stickytableheaders.min.js"></script>
            <script>
                jQuery(document).ready(function(){
                        jQuery('table').on('click', 'td', function(e) {  
                                var nonclickable = 'true' == e.delegateTarget.rows[1].cells[this.cellIndex].getAttribute('nonclickable');
                                var id = this.parentNode.getAttribute("rid");
                                if (!nonclickable)
                                        document.location = "/account/view-order/?id="+id+"&link=my-orders&status=<?php echo $status; ?>";
                        });
                        
                        jQuery("#tbl_products").stickyTableHeaders();
                });

                function doDisplay(id) {
                        var el = jQuery('div[for='+id+']');
                        if (el.attr('style') == null) {
                                el.attr('style', 'display:none;');
                                jQuery('a[at='+id+']').attr('id', 'icon_set');
                        } else {
                                el.removeAttr('style');
                                jQuery('a[at='+id+']').attr('id', 'icon_set_cur');
                        }
                        jQuery("div[id2=menu]").each(function(i) {
                                var id2 = this.getAttribute('for');
                                if (id2 != ''+id) {
                                        this.setAttribute('style', 'display:none;');
                                        jQuery('a[at='+id2+']').attr('id', 'icon_set');
                                }
                        });
                }
            </script>
                <?php
                build_pages_footer($page, $pages);
            }
        }
    }

    $output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

?>