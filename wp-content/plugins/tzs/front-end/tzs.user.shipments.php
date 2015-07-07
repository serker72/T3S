<?php

function tzs_front_end_user_shipments_handler($atts) {
    // Определяем атрибуты 
    // [tzs-view-user-products user_id="1"] - указываем на странице раздела
    // [tzs-view-products] - указываем на страницах подразделов
    extract( shortcode_atts( array(
            'user_id' => '0',
    ), $atts, 'tzs-view-user-shipments' ) );
        
    ob_start();

    $sql1 = ' AND user_id='.$user_id;
        global $wpdb;
        $page = current_page_number();
        $url = current_page_url();
         $pp = TZS_RECORDS_PER_PAGE; 
         $sql = "SELECT COUNT(*) as cnt FROM ".TZS_SHIPMENT_TABLE." WHERE active=1 $sql1 ";
        $res = $wpdb->get_row($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
            print_error('Не удалось отобразить список грузов. Свяжитесь, пожалуйста, с администрацией сайта -count');
            print_r( $wpdb->last_error);
        } else {
            $records = $res->cnt;
            $pages = ceil($records / $pp);
            if ($pages == 0)
                    $pages = 1;
            if ($page > $pages)
                    $page = $pages;
            $from = ($page-1) * $pp;
            $sql = "SELECT * FROM ".TZS_SHIPMENT_TABLE." WHERE active=1 $sql1 ORDER BY time DESC LIMIT $from,$pp; ";
            $res = $wpdb->get_results($sql);
            if (count($res) == 0 && $wpdb->last_error != null) {
                print_error('Не удалось отобразить список грузов. Свяжитесь, пожалуйста, с администрацией сайта - record');
                 print_r( $wpdb->last_error);
            } else {
                if (count($res) == 0) {
                    ?>
                    <div style="clear: both;"></div>
                    <div class="errors">
                        <div id="info error">По Вашему запросу ничего не найдено.</div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div>
                        <table id="tbl_products">
                        <tr>
                            <th id="tbl_trucks_id">N, дата и время заявки</th>
                            <th id="tbl_trucks_path" nonclickable="true">Пункты погрузки /<br/>выгрузки</th>
                            <th id="tbl_trucks_dtc">Даты погрузки /<br>выгрузки</th>
                            <th id="tbl_trucks_tc">Тип груза</th>
                            <th id="tbl_trucks_wv">Вес,<br>объём</th>
                            <th id="tbl_trucks_comm">Описание груза</th>
                            <th id="tbl_trucks_cost">Cтоимость,<br/>цена 1 км</th>
                            <th id="tbl_trucks_payment">Форма оплаты</th>
                        </tr>
                        <?php
                        foreach ( $res as $row ) {
                            echo tzs_tr_sh_table_record_out_cont($row, 'shipments');
                        }
                        ?>
                        </table>
                    </div>
                <?php
                }

                build_pages_footer($page, $pages);
            }
        }
    
////
	?>
        <script src="/wp-content/plugins/tzs/assets/js/search.js"></script>
        <script>
                var post = [];
                <?php
                    echo "// POST dump here\n";
                    foreach ($_POST as $key => $value) {
                        echo "post[".tzs_encode2($key)."] = ".tzs_encode2($value).";\n";
                    }
                    if (!isset($_POST['type_id'])) {
                        echo "post[".tzs_encode2("type_id")."] = ".tzs_encode2($p_id).";\n";
                    }
                    if (!isset($_POST['cur_type_id'])) {
                        echo "post[".tzs_encode2("cur_type_id")."] = ".tzs_encode2($p_id).";\n";
                    }
                ?>

                function showSearchDialog() {
                        doSearchDialog('products', post, null);
                        //doSearchDialog('auctions', post, null);
                }

                jQuery(document).ready(function(){
                        jQuery('#tbl_products').on('click', 'td', function(e) {  
                                var nonclickable = 'true' == e.delegateTarget.rows[0].cells[this.cellIndex].getAttribute('nonclickable');
                                var id = this.parentNode.getAttribute("rid");
                                if (!nonclickable)
                                        document.location = "/account/view-product/?id="+id;
                        });
                        hijackLinks(post);
                });
        </script>
	<?php
////
    
    $output = ob_get_contents();
    
    ob_end_clean();
	
    return $output;
}
?>