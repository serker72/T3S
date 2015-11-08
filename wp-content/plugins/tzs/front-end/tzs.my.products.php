<?php

include_once(TZS_PLUGIN_DIR.'/front-end/tzs.tables_reload.php');

add_action( 'wp_ajax_tzs_delete_product', 'tzs_delete_product_callback' );

function tzs_delete_product_callback() {
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval( $_POST['id'] ) : 0;
    $is_delete = isset($_POST['is_delete']) && is_numeric($_POST['is_delete']) ? intval( $_POST['is_delete'] ) : 0;
    $user_id = get_current_user_id();
    $errors = array();
    
    if ($id <= 0) {
        echo "Товар/услуга не найден";
    } else if ($user_id == 0) {
        echo "Вход в систему обязателен";
    } else {
        global $wpdb;

        // Вначале попытаемся удалить изображения
        $sql = "SELECT * FROM ".TZS_PRODUCTS_TABLE." WHERE id=$id AND user_id=$user_id;";
        $row = $wpdb->get_row($sql);
        if (count($row) === 0 && $wpdb->last_error != null) {
            echo 'Не удалось получить список товаров. Свяжитесь, пожалуйста, с администрацией сайта';
            echo $wpdb->last_error;
        } else if ($row === null) {
            echo "Товар/услуга не найден (id=$id AND user_id=$user_id)";
        } else {
            if ((strlen($row->image_id_lists) > 0) && ($is_delete === 1)) {
                $img_names = explode(';', $row->image_id_lists);
                
                for ($i=0;$i < count($img_names);$i++) {
                    if( false === wp_delete_attachment($img_names[$i], true) ) {
                        echo "Не удалось удалить файл с изображением: ".$img_names[$i]->get_error_message();
                        array_push($errors, "Не удалось удалить файл с изображением: ".$img_names[$i]->get_error_message());
                    }
                }
            }
            
            if (count($errors) === 0) {
                if ($is_delete === 1) {
                    $sql = "DELETE FROM ".TZS_PRODUCTS_TABLE." WHERE id=$id AND user_id=$user_id;";
                } else {
                    $sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET active=0 WHERE id=$id AND user_id=$user_id;";
                }

                if (false === $wpdb->query($sql)) {
                    if ($is_delete === 1) {
                        echo "Не удалось удалить Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта ";
                    } else {
                        echo "Не удалось перенести в архив Ваш товар/услугу. Свяжитесь, пожалуйста, с администрацией сайта ";
                    }
                } else {
                        echo "1";
                }
            }
        }
    }
    die();
}

function tzs_front_end_my_products_handler($atts) {
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
            <?php
    } else {
        $sql = "SELECT COUNT(*) as cnt FROM ".TZS_PRODUCTS_TABLE." WHERE user_id=$user_id AND active=$active;";
        $res = $wpdb->get_row($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
            print_error('Не удалось отобразить список товаров/услуг. Свяжитесь, пожалуйста, с администрацией сайта');
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
                $sql = "SELECT * FROM ".TZS_PRODUCTS_TABLE."  WHERE user_id=$user_id AND active=$active ORDER BY created DESC LIMIT $from,$pp;";
            } else {
                $sql  = "SELECT a.*,";
                $sql .= " b.id AS order_id,";
                $sql .= " b.number AS order_number,";
                $sql .= " b.status AS order_status,";
                $sql .= " b.dt_pay AS order_dt_pay,";
                $sql .= " b.dt_expired AS order_dt_expired,";
                $sql .= " IFNULL(b.dt_pay, a.created) AS dt_sort,";
                $sql .= " c.code AS from_code";
                $sql .= " FROM ".TZS_PRODUCTS_TABLE." a";
                $sql .= " LEFT OUTER JOIN wp_tzs_orders b ON (b.tbl_type = 'PR' AND a.id = b.tbl_id AND ((b.status=1 AND b.dt_expired > NOW()) OR b.status=0) )";
                $sql .= " LEFT OUTER JOIN wp_tzs_countries c ON (a.from_cid = c.country_id)";
                $sql .= " WHERE a.user_id=$user_id AND a.active=$active";
                $sql .= " ORDER BY order_status DESC, dt_sort DESC";
                $sql .= " LIMIT $from,$pp;";
            }
            
            $res = $wpdb->get_results($sql);
            if (count($res) == 0 && $wpdb->last_error != null) {
                print_error('Не удалось отобразить список товаров/услуг. Свяжитесь, пожалуйста, с администрацией сайта');
            } else {
                ?>
                <div id="my_products_wrapper">
                    <div id="my_products_table">
                        <table id="tbl_products">
                        <thead>
                            <tr id="tbl_thead_records_per_page">
                                <!--th colspan="10" id="thead_h1"-->
                                <!--th colspan="4" id="thead_h1"-->
                                <th colspan="4" style="border: 0;">
                                    <div class="div_td_left">
                                        <h3><?php echo ($active === '1') ? 'Публикуемые' : 'Архивные'; ?> товары</h3>
                                    </div>
                                </th>
                                
                                <th colspan="6">
                                    <div id="my_products_button">
                                        <?php if ($active === '1') { ?>
                                            <button id="view_del" onClick="javascript: window.open('/account/my-products/?active=0', '_self');">Показать архивные</button>
                                        <?php } else { ?>
                                            <button id="view_edit" onClick="javascript: window.open('/account/my-products/?active=1', '_self');">Показать публикуемые</button>
                                        <?php } ?>
                                        <button id="view_add" onClick="javascript: window.open('/account/add-product/', '_self');">Добавить товар</button>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th id="tbl_products_id">Номер, дата и время заявки</th>
                                <th id="tbl_products_sale">Покупка<br/>Продажа</th>
                                <th id="tbl_products_dtc">Период публи-<br/>кации</th>
                                <th id="tbl_products_type">Тип товара</th>
                                <th id="tbl_products_img">Фото товара</th>
                                <th id="tbl_products_title">Название, описание и местонахождение товара</th>
                                <th id="tbl_products_price">Цена<br/>Кол-во</th>
                                <th id="tbl_products_payment">Форма оплаты</th>
                                <th id="tbl_products_cost">Купить / Предложить цену</th>
                                <th id="actions" nonclickable="true">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ( $res as $row ) {
                            $profile_td_text = '<a href="javascript:doDisplay('.$row->id.');" at="'.$row->id.'" id="icon_set">Действия</a>
                                    <div id="menu_set" id2="menu" for="'.$row->id.'" style="display:none;">
                                        <ul>
                                            <a href="/account/view-product/?id='.$row->id.'&link=my-products&active='.$active.'">Смотреть</a>
                                            <a href="/account/edit-product/?id='.$row->id.'">Изменить</a>';

                            if ($row->active && ($row->order_status === null)) {
                                $profile_td_text .= '<a href="javascript:promptPickUp('.$row->id.', \'PR\');">В ТОП</a>';
                            }

                            if ($row->active && ($row->order_status !== null) && ($row->order_status == 0)) {
                                $profile_td_text .= '<a href="/account/view-order/?id='.$row->order_id.'">Счет ТОП</a>';
                            }

                            $profile_td_text .= '<a href="javascript: promptDelete('.$row->id.', '.$row->active.');" id="red">Удалить</a>
                                        </ul>
                                    </div>';

                            echo tzs_products_table_record_out($row, 'products', tzs_get_children_pages(TZS_PR_ROOT_CATEGORY_PAGE_ID), $profile_td_text);
                        }
                        ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            
                <!-- Modal -->
                <!--div id="RecordPickUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button id="RecordPickUpModalCloseButton" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Поднятие объявления в ТОП</h3>
                    </div>
                    <div class="modal-body">
                        <h4>Создать счет на оплату услуг добавления заявки в ТОП ?</h4>
                        <form id="RecordPickUpForm" method="post" action="" class="pr_edit_form">
                            <div class="pr_edit_form_line">
                                <label for="order_tbl_type">Префикс таблицы</label>
                                <input type="text" id="order_tbl_type" name="order_tbl_type" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="order_tbl_id">ID заявки</label>
                                <input type="text" id="order_tbl_id" name="order_tbl_id" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="order_cost">Стоимость услуги, грн</label>
                                <input type="text" id="order_cost" name="order_cost" value="<?php echo get_option('t3s_setting_record_pickup_cost');?>" disabled="disabled">
                            </div>
                        </form>
                        <div id="RecordPickUpInfo"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button id="RecordPickUpSubmit" class="btn btn-primary" onClick="doPickUp();">Создать счет</button>
                    </div>
                </div-->

                <?php include_once WP_PLUGIN_DIR.'/tzs/front-end/tzs.my.new_order.php'; ?>
                
    <script src="/wp-content/plugins/tzs/assets/js/jquery.stickytableheaders.min.js"></script>
            <script>
/*                function promptPickUp(id, table_prefix) {
                    jQuery("#order_tbl_type").attr('value', table_prefix);
                    jQuery("#order_tbl_id").attr('value', id);
                    jQuery("#RecordPickUpModal").modal('show');
                }
                
                function doPickUp() {
                    jQuery("#RecordPickUpSubmit").attr('disabled', 'disabled');
                    jQuery('#RecordPickUpInfo').html('Подождите, идет формирование счета на оплату...');
                    //fd = jQuery('#RecordPickUpModal form#RecordPickUpForm').serialize();
                    fd = 'order_tbl_type=' + jQuery("#order_tbl_type").val() + '&order_tbl_id=' + jQuery("#order_tbl_id").val();
                    jQuery.ajax({
                        url: "/wp-admin/admin-ajax.php?action=tzs_order_add",
                        type: "POST",
                        data: fd,
                        dataType: 'json',
                        success: function(data) {
                            if ((data.output_error !== 'undefined') && (data.output_error !== '')) {
                                jQuery('#RecordPickUpInfo').html(data.output_error);
                            }
                            if ((data.order_id !== 'undefined') && (data.order_id !== '')) {
                                location.href = "<?php //echo get_site_url(); ?>/account/view-order/?id=" + data.order_id + "&spis=new";
                            }
                        },
                        error: function(data) {
                            if (data.responseText !== 'undefined') {
                                jQuery('#RecordPickUpInfo').html(data.responseText);
                            }
                        }			
                    });
                    
                }
*/                
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

                function promptDelete(id, active) {
                    if (active === 1) {
                        var s_text = '<div><h2>Удалить запись '+id+' или перенести в архив ?</h2><hr/><p>Запись из архива можно в любой момент снова опубликовать.</p><p>При удалении записи будут так же удалены все прикрепленные изображения.</p></div>';
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
                        var s_text = '<div><h2>Удалить запись '+id+' из архива ?</h2><hr/><p>Запись из архива можно в любой момент снова опубликовать.</p><p>При удалении записи будут так же удалены все прикрепленные изображения.</p></div>';
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
                                'action': 'tzs_delete_product',
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
                
                jQuery(document).ready(function(){
                        jQuery('table').on('click', 'td', function(e) {  
                                var nonclickable = 'true' == e.delegateTarget.rows[1].cells[this.cellIndex].getAttribute('nonclickable');
                                var id = this.parentNode.getAttribute("rid");
                                if (!nonclickable)
                                        document.location = "/account/view-product/?id="+id+"&link=my-products&active=<?php echo $active; ?>";
                        });
                        
                        jQuery("#tbl_products").stickyTableHeaders();
                });

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