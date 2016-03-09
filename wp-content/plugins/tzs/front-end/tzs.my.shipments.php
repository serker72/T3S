<?php

include_once(TZS_PLUGIN_DIR.'/front-end/tzs.tables_reload.php');

add_action( 'wp_ajax_tzs_delete_shipment', 'tzs_delete_shipment_callback' );

function tzs_delete_shipment_callback() {
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval( $_POST['id'] ) : 0;
    $is_delete = isset($_POST['is_delete']) && is_numeric($_POST['is_delete']) ? intval( $_POST['is_delete'] ) : 0;
    $user_id = get_current_user_id();
    
    if ($id <= 0) {
        echo "Груз не найден";
    } else if ($user_id == 0) {
        echo "Вход в систему обязателен";
    } else {
        global $wpdb;

        $sql = "SELECT * FROM ".TZS_SHIPMENT_TABLE." WHERE id=$id AND user_id=$user_id;";
        $row = $wpdb->get_row($sql);
        if (count($row) === 0 && $wpdb->last_error != null) {
            echo 'Не удалось получить список грузов. Свяжитесь, пожалуйста, с администрацией сайта';
            echo $wpdb->last_error;
        } else if ($row === null) {
            echo "Груз не найден (id=$id AND user_id=$user_id)";
        } else {
            if ($is_delete === 1) {
                $sql = "DELETE FROM ".TZS_SHIPMENT_TABLE." WHERE id=$id AND user_id=$user_id;";
            } else {
                $sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET active=0 WHERE id=$id AND user_id=$user_id;";
            }

            if (false === $wpdb->query($sql)) {
                if ($is_delete === 1) {
                    echo "Не удалось удалить Ваш груз. Свяжитесь, пожалуйста, с администрацией сайта ";
                } else {
                    echo "Не удалось перенести в архив Ваш груз. Свяжитесь, пожалуйста, с администрацией сайта ";
                }
            } else {
                    echo "1";
            }
        }
    }
    
    die();
}

function tzs_front_end_my_shipments_handler($atts) {
    ob_start();

    global $wpdb;

    $user_id = get_current_user_id();
    $url = current_page_url();
    $page = current_page_number();
    $pp = TZS_RECORDS_PER_PAGE;
    $active = isset($_GET['active']) ? trim($_GET['active']) : '1';
	
    if ($user_id == 0) {
        ?>
        <div>Для просмотра необходимо <a href="/account/login/">войти</a> или <a href="/account/registration/">зарегистрироваться</a></div>
        <script>
            jQuery(document).ready(function(){
                jQuery('#menu-profile').hide();
            });
        </script>
        <?php
    } else {
        $sql = "SELECT COUNT(*) as cnt FROM ".TZS_SHIPMENT_TABLE." WHERE user_id=$user_id AND active=$active;";
        $res = $wpdb->get_row($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
                print_error('Не удалось отобразить список грузов. Свяжитесь, пожалуйста, с администрацией сайта');
        } else {
            $records = $res->cnt;
            $pages = ceil($records / $pp);
            if ($pages == 0)
                    $pages = 1;
            if ($page > $pages)
                    $page = $pages;

            $from = ($page-1) * $pp;
            
            // Добавим отбор счетов и сортировку по ним для активных записей
            if ($active == 0) {
                $sql = "SELECT * FROM ".TZS_SHIPMENT_TABLE."  WHERE user_id=$user_id AND active=$active ORDER BY time DESC LIMIT $from,$pp;";
            } else {
                $sql  = "SELECT a.*,";
                $sql .= " b.id AS order_id,";
                $sql .= " b.number AS order_number,";
                $sql .= " b.status AS order_status,";
                $sql .= " b.dt_pay AS order_dt_pay,";
                $sql .= " b.dt_expired AS order_dt_expired,";
                $sql .= " IFNULL(b.dt_pay, a.time) AS dt_sort,";
                $sql .= " IF(b.status IS NOT NULL, 2, IF(a.dt_pickup <> '0000-00-00 00:00:00', 1, 0)) AS top_status,";
                $sql .= " LOWER(c.code) AS from_code, LOWER(d.code) AS to_code";
                $sql .= " FROM ".TZS_SHIPMENT_TABLE." a";
                $sql .= " LEFT OUTER JOIN wp_tzs_orders b ON (b.tbl_type = 'SH' AND a.id = b.tbl_id AND ((b.status=1 AND b.dt_expired > NOW()) OR b.status=0) )";
                $sql .= " LEFT OUTER JOIN wp_tzs_countries c ON (a.from_cid = c.country_id)";
                $sql .= " LEFT OUTER JOIN wp_tzs_countries d ON (a.to_cid = d.country_id)";
                $sql .= " WHERE a.user_id=$user_id AND a.active=$active";
                $sql .= " ORDER BY top_status DESC, order_status DESC, dt_sort DESC";
                $sql .= " LIMIT $from,$pp;";
            }
            
            $res = $wpdb->get_results($sql);
            if (count($res) == 0 && $wpdb->last_error != null) {
                print_error('Не удалось отобразить список транспорта. Свяжитесь, пожалуйста, с администрацией сайта');
            } else {
                ?>
                <script src="/wp-content/plugins/tzs/assets/js/distance.js"></script>
                <script src="/wp-content/plugins/tzs/assets/js/ksk_show_msg.js" type="text/javascript"></script>
                <div id="my_products_wrapper">
                    <div id="my_products_table">
                        <!--form method="" id="form_tbl_products" action=""-->
                        <input type="hidden" id="table_record_id" name="table_record_id" value="0"/>
                        <input type="hidden" id="table_record_order_id" name="table_record_order_id" value=""/>
                        <input type="hidden" id="table_record_order_status" name="table_record_order_status" value=""/>
                        <input type="hidden" id="table_record_top_status" name="table_record_top_status" value=""/>
                        <table id="tbl_products">
                        <thead>
                            <tr id="tbl_thead_records_per_page">
                                <th colspan="9" style="border: 0;">
                                    <div class="div_td_left">
                                        <?php echo ($active === '1') ? 'Публикуемые' : 'Архивные'; ?> грузы
                                    </div>
                                    <div id="my_products_button">
                                        <?php if ($active === '1') { ?>
                                            <button id="pickup_button">ТОП</button>
                                            <button id="vip_pickup_button">ТОП $</button>
                                            <button id="view_button">Смотреть</button>
                                            <button id="edit_button"">Изменить</button>
                                            <button id="delete_button">Удалить</button>
                                            <button id="all_list_button" onClick="javascript: window.open('/cargo', '_self');">Общий список</button>
                                            <button id="" onClick="javascript: window.open('/account/my-shipments/?active=0', '_self');">Архивные</button>
                                        <?php } else { ?>
                                            <button id="duplicate_button">Дублировать</button>
                                            <button id="view_button">Смотреть</button>
                                            <button id="delete_button">Удалить</button>
                                            <button id="all_list_button" onClick="javascript: window.open('/cargo', '_self');">Общий список</button>
                                            <button id="" onClick="javascript: window.open('/account/my-shipments/?active=1', '_self');">Публикуемые</button>
                                        <?php } ?>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th id="tbl_trucks_rb">&nbsp;</th>
                                <th id="tbl_trucks_id">Опубликовано<br>Поднято</th>
                                <th nonclickable="true" style="min-width: 260px; padding: 0; margin: 0;">
                                    <div class="tbl_trucks_path">Пункты погрузки /<br/>выгрузки<br/>&nbsp;</div>
                                    <div class="tbl_trucks_dtc">Даты погрузки /<br>выгрузки</div>
                                </th>
                                <th id="tbl_trucks_tc">Тип груза /<br>Желаемый тип ТС</th>
                                <th id="tbl_trucks_wv">Вес,<br>объём</th>
                                <th id="tbl_trucks_comm">Описание груза</th>
                                <th id="tbl_trucks_cost">Cтоимость,<br/>цена 1 км</th>
                                <th id="tbl_trucks_payment" nonclickable="true">Форма оплаты</th>
                                <!--th id="actions" nonclickable="true">Действия</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ( $res as $row ) {
                                //$profile_td_text = '<a href="javascript:doDisplay('.$row->id.');" at="'.$row->id.'" id="icon_set">Действия</a>
                                //        <div id="menu_set" id2="menu" for="'.$row->id.'" style="display:none;">
                                $profile_td_text = '<div id="menu_set" id2="menu" for="'.$row->id.'">
                                            <ul>
                                                <a href="/account/view-shipment/?id='.$row->id.'&link=my-shipments&active='.$active.'">Смотреть</a>
                                                <a href="/account/edit-shipment/?id='.$row->id.'">Изменить</a>';
                                
                                if ($row->active && ($row->order_status === null)) {
                                    $profile_td_text .= '<a href="javascript:promptPickUp('.$row->id.', \'SH\');">В ТОП</a>';
                                }

                                if ($row->active && ($row->order_status !== null) && ($row->order_status == 0)) {
                                    $profile_td_text .= '<a href="/account/view-order/?id='.$row->order_id.'">Счет ТОП</a>';
                                }
                                
                                $profile_td_text .= '<a href="javascript: promptDelete('.$row->id.', '.$row->active.');" id="red">Удалить</a>
                                            </ul>
                                        </div>';
                                
                                $profile_td_text = 'no';
                                
                                echo tzs_tr_sh_table_record_out($row, 'shipments', $profile_td_text);
                            }
                            ?>
                        </tbody>
                        </table>
                        <!--/form-->
                    </div>
                </div>
                    
                <?php include_once WP_PLUGIN_DIR.'/tzs/front-end/tzs.my.new_order.php'; ?>
    
    <script src="/wp-content/plugins/tzs/assets/js/jquery.stickytableheaders.min.js"></script>
                    <script>
                    // Функция, отрабатывающая после готовности HTML-документа
                    jQuery(document).ready(function(){
                        jQuery('.entry-title').hide();
                        /*jQuery('#tbl_products').on('click', 'td', function(e) {  
                                var nonclickable = 'true' == e.delegateTarget.rows[1].cells[this.cellIndex].getAttribute('nonclickable');
                                var id = this.parentNode.getAttribute("rid");
                                //alert('Тыц-тыц: cellIndex - '+this.cellIndex+', textContent -'+this.textContent+', id -'+id+', nonclickable - '+nonclickable);
                                if (!nonclickable && (id != null)) {
                                    document.location = "/account/view-shipment/?id="+id+"&link=my-shipments&active=<?php echo $active; ?>";
                                }
                        });*/
                        
                        jQuery("#tbl_products").stickyTableHeaders();
                        /*jQuery("#icon_set").hover(function(e) { 
                            //alert('e.pageY='+e.pageY+'\ne.pageX'+e.pageX);
                            var id = jQuery(this).attr('at');
                            jQuery('div[for='+id+']').css({
                                'top': e.pageY,
                                'left': e.pageX,
                            });
                            doDisplay(id);
                        });*/
    
                        jQuery("input[type=radio]").change(function (e) {
                            var order_id = e.target.getAttribute('order-id');
                            var order_status = e.target.getAttribute('order-status');
                            var top_status = e.target.getAttribute('top-status');
                            
                            jQuery("#table_record_id").attr('value', e.target.value);
                            jQuery("#table_record_order_id").attr('value', order_id);
                            jQuery("#table_record_order_status").attr('value', order_status);
                            jQuery("#table_record_top_status").attr('value', top_status);
                            
                            if (top_status == 2) {
                                jQuery("#pickup_button").hide();
                                
                                if (order_status != '') {
                                    jQuery("#vip_pickup_button").text('Счет ТОП $');
                                } else {
                                    jQuery("#vip_pickup_button").text('');
                                }
                            } else if (top_status == 1) {
                                jQuery("#vip_pickup_button").text('ТОП $');
                                jQuery("#pickup_button").hide();
                                jQuery("#vip_pickup_button").show();
                            } else {
                                jQuery("#vip_pickup_button").text('ТОП $');
                                jQuery("#pickup_button").show();
                                jQuery("#vip_pickup_button").show();
                            }
                        });
                        
                        jQuery("#pickup_button").on('click', function(event) {  
                            id = jQuery("#table_record_id").attr('value');
                            if (id !== '0') {
                            } else {
                                ksk_show_msg('Необходимо выбрать запись с помощью переключателя в первом столбце', 'Ошибка');
                                event.preventDefault();
                            }
                        });
                        
                        jQuery("#vip_pickup_button").on('click', function(event) {  
                            var id = jQuery("#table_record_id").attr('value');
                            var order_id = jQuery("#table_record_order_id").attr('value');
                            var order_status = jQuery("#table_record_order_status").attr('value');
                            //alert('id='+id+', order_id='+order_id+', order_status='+order_status);
                            
                            if (id !== '0') {
                                if (order_status == '') {
                                    promptPickUp(id, 'SH');
                                } else {
                                    window.location.replace("<?php echo get_site_url(); ?>/account/view-order/?id=" + order_id);
                                }
                            } else {                            
                                ksk_show_msg('Необходимо выбрать запись с помощью переключателя в первом столбце', 'Ошибка');
                                event.preventDefault();
                            }
                        });
                        
                        jQuery("#view_button").on('click', function(event) {  
                            id = jQuery("#table_record_id").attr('value');
                            if (id !== '0') {
                                window.location.replace("<?php echo get_site_url(); ?>/account/view-shipment/?id="+id);
                            } else {
                                ksk_show_msg('Необходимо выбрать запись с помощью переключателя в первом столбце', 'Ошибка');
                                event.preventDefault();
                            }
                        });
                        
                        jQuery("#edit_button").on('click', function(event) {  
                            id = jQuery("#table_record_id").attr('value');
                            if (id !== '0') {
                                document.location = "<?php echo get_site_url(); ?>/account/edit-shipment/?id="+id;
                            } else {                            
                                ksk_show_msg('Необходимо выбрать запись с помощью переключателя в первом столбце', 'Ошибка');
                                event.preventDefault();
                            }
                        });
                        
                        jQuery("#delete_button").on('click', function(event) {  
                            id = jQuery("#table_record_id").attr('value');
                            if (id !== '0') {
                                promptDelete(id, <?php echo $active; ?>);
                            } else {                            
                                ksk_show_msg('Необходимо выбрать запись с помощью переключателя в первом столбце', 'Ошибка');
                                event.preventDefault();
                            }
                        });
                    });
                    
                    function doDisplay(id) {
                        //alert('doDisplay: id=' + id);
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

                    function promptDelete(id, active) {
                    if (active === 1) {
                        var s_text = '<div><h2>Удалить запись '+id+' или перенести в архив ?</h2><hr/><p>Запись из архива можно в любой момент снова опубликовать.</p></div>';
                        buttons1 = new Object({
                                                'В архив': function () {
                                                        jQuery(this).dialog("close");
                                                        doDelete(id, 0);
                                                },
                                                'Удалить': function () {
                                                        jQuery(this).dialog("close");
                                                        doDelete(id, 1);
                                                },
                                                'Отменить': function () {
                                                        jQuery(this).dialog("close");
                                                }
                                            });
                    } else {
                        var s_text = '<div><h2>Удалить запись '+id+' из архива ?</h2><hr/><p>Запись из архива можно в любой момент снова опубликовать.</p></div>';
                        buttons1 = new Object({
                                                'Удалить': function () {
                                                        jQuery(this).dialog("close");
                                                        doDelete(id, 1);
                                                },
                                                'Отменить': function () {
                                                        jQuery(this).dialog("close");
                                                }
                                            });
                    }
                            jQuery('<div></div>').appendTo('body')
                                    .html(s_text)
                                    .dialog({
                                            modal: true,
                                            title: 'Удаление',
                                            zIndex: 10000,
                                            autoOpen: true,
                                            width: 'auto',
                                            resizable: false,
                                            buttons: buttons1,
                                            close: function (event, ui) {
                                                    jQuery(this).remove();
                                            }
                                    });
                    }

                    function doDelete(id, is_delete) {
                            var data = {
                                    'action': 'tzs_delete_shipment',
                                    'id': id,
                                'is_delete': is_delete
                            };

                            jQuery.post(ajax_url, data, function(response) {
                                    if (response == '1') {
                                            location.reload();
                                    } else {
                                            alert('Не удалось удалить: '+response);
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