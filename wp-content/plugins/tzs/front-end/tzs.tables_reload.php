<?php

/*
 * Вывод одной строки таблицы в виде html
 */
function tzs_products_table_record_out($row, $form_type, $pr_type_array, $profile_td_text=null) {
//    $user_info = tzs_get_user_meta($row->user_id);

    $output_tbody = '<tr rid="'.$row->id.'" id=';

    if ($row->sale_or_purchase == 1) { $output_tbody .= '"tbl_auctions_tr_lot_1"'; } else { $output_tbody .= '"tbl_auctions_tr_lot_0"'; }
    
    if ($row->top_status == 2) {
        $output_tbody .= ($row->order_status == 1 ? ' class="vip_top_record"' : ($profile_td_text && $row->order_status !== null && $row->order_status == 0 ? ' class="pre_vip_top_record"' : ''));
    } else if ($row->top_status == 1) {
        $output_tbody .= ' class="top_record"';
    } else {
    }
    
    $output_tbody .= '>';
    
    if ($profile_td_text == 'no') {
        $output_tbody .= '<td><input type="radio" order-status="'.($row->order_status == null ? '' : $row->order_status).'" top-status="'.$row->top_status.'" order-id="'.$row->order_id.'" record-active="'.$row->active.'" id="r_table_record_id" name="r_table_record_id" value="'.$row->id.'"';

        if (isset($_POST['table_record_id']) && $_POST['table_record_id'] == "$row->id") $output_tbody .= 'checked="checked"';

        $output_tbody .= '></td>';
    }
    
    $dt_created = convert_time($row->created, "d.m.y (Hч:iмин)");
    $dt_created = explode(" ", $dt_created);
    
    if ($row->dt_pickup != '0000-00-00 00:00:00') {
        $dt_pickup = convert_time($row->dt_pickup, "d.m.y (Hч:iмин)");
        $dt_pickup = explode(" ", $dt_pickup);
    } else {
        $dt_pickup = '';
    }
    
                /*<div class="record_number">
                    <span class="middle" title="Номер заявки">
                           № '.$row->id.'
                    </span>
                </div>*/
    
    $output_tbody .= '
            <td>
                <div>
                    <div class="date_label" title="Дата публикации заявки">
                        '.$dt_created[0].'
                    </div>
                    <div class="time_label" title="Время публикации заявки">
                        '.$dt_created[1].'
                    </div><br>';
    
    if ($dt_pickup != '') {
        $output_tbody .= '<div class="date_label" title="Дата бесплатного поднятия заявки в ТОП">
                    '.$dt_pickup[0].'
                </div>
                <div class="time_label" title="Время бесплатного поднятия заявки в ТОП">
                    '.$dt_pickup[1].'
                </div>';
    }
    
    $output_tbody .= '
                </div>
            </td>
            <td>
                <div>
                    <span title="Тип заявки">
                        <strong>';

    if ($row->sale_or_purchase == 1) { $output_tbody .= 'Продажа'; } else { $output_tbody .= 'Покупка'; }

    $output_tbody .= '</strong>
                    </span>
                </div>
            </td>';
    
    $output_tbody .= '<td>
                <div>
                    '.convert_date_year2($row->created).'<br/>
                    <span class="expired_label" title="Дата окончания публикации">
                        '.convert_date_year2($row->expiration).'
                    </span>
                </div>
            </td>
            <td>'.$pr_type_array[$row->type_id]['title'].'
            </td>
            <td>
                <div class="ienlarger">';

                if (strlen($row->image_id_lists) > 0) {
                    $main_image_id = $row->main_image_id;
                    // Вначале выведем главное изображение
                    $attachment_info = wp_get_attachment_image_src($main_image_id, 'full');
                    if ($attachment_info !== false) {
                            $output_tbody .= '<a href="#nogo">
                                <img src="'.$attachment_info[0].'" alt="thumb" class="resize_thumb">
                                <span>
                                    '.trim($row->title).'<br/>
                                    <img src="'.$attachment_info[0].'" alt="large"/>
                                </span>
                            </a>';
                    }
                }

                $output_tbody .= '</div>
            </td>
            <td>
                <div class="title_text">
                    <span title="Краткое описание товара">
                        '.trim($row->title).'
                    </span>
                        <br><br>'.tzs_city_to_str($row->from_cid, $row->from_rid, $row->from_sid, $row->city_from, 'Местонахождение товара').'
                </div>
            </td>

            <td>
                <div>
                    <span class="price_label" title="Цена товара">
                        <strong>'.$row->price.'</strong> '.$GLOBALS['tzs_pr_curr'][$row->currency].'
                    </span>
                    <br>
                    <br>
                    <span class="copies_label" title="Количество товара">
                        <strong>'.$row->copies.'</strong> '.$GLOBALS['tzs_pr_unit'][$row->unit].'
                    </span>
                </div>
            </td>
            <td>
                <div>
                    <span class="payment_label" title="Форма оплаты">
                        '.$GLOBALS['tzs_pr_payment'][$row->payment].'<br/>
                        '.$GLOBALS['tzs_pr_nds'][$row->nds].'
                    </span>
                </div>
            </td>';
            
    
    $output_tbody .= '<td><div>';

                if ($row->fixed_or_tender == 2) {
                    //$output_tbody .= '<span class="btnGray" title="Купить товар по фиксированной цене">Купить</span>';
                    $output_tbody .= '<a class="btnBlue" title="Купить товар по фиксированной цене">Купить</a>';
                    $output_tbody .= '<a class="btnBlue" title="Предложить свою цену за товар">Предложить свою цену</a>';
                } else {
                    $output_tbody .= '<a class="btnBlue" title="Купить товар по фиксированной цене">Купить</a>';
                    $output_tbody .= '<span class="btnGray" title="Предложить свою цену за товар">Предложить свою цену</span>';
                }

    $output_tbody .= '</div></td>';
                
    if ($profile_td_text == 'no') {
        $output_tbody .= '';
    } else if ($profile_td_text) {
        $output_tbody .= '<td>'.$profile_td_text.'</td>';
    } else {
        $output_tbody .= '<td>'.tzs_print_user_contacts($row, $form_type, 0).'</td>';
    }
    
    $output_tbody .= '</tr>';
    
    return $output_tbody;
}

/*
 * Вывод одной строки таблицы в виде html
 */
function tzs_tr_sh_table_record_out($row, $form_type, $profile_td_text=null) {
//    $user_info = tzs_get_user_meta($row->user_id);

    if ($form_type === 'shipments') { $prefix = 'sh';}
    else { $prefix = 'tr'; }
    
    $type = trans_types_to_str($row->trans_type, $row->tr_type);
    $path_segment_cities = explode(";", $row->path_segment_cities);
    
    //$cost = tzs_cost_to_str($row->cost, true);
    $cost = tzs_price_query_to_str($row);
    
    $dt_created = convert_time($row->time, "d.m.Y (Hч:iмин)");
    $dt_created = explode(" ", $dt_created);
    
    if ($row->dt_pickup != '0000-00-00 00:00:00') {
        $dt_pickup = convert_time($row->dt_pickup, "d.m.Y (Hч:iмин)");
        $dt_pickup = explode(" ", $dt_pickup);
    } else {
        $dt_pickup = '';
    }
    
    // Определение статуса записи
    $output_tbody = '<tr rid="'.$row->id.'"';
    
    if ($row->top_status == 2) {
        $output_tbody .= ($row->order_status == 1 ? ' class="vip_top_record"' : ($profile_td_text && $row->order_status !== null && $row->order_status == 0 ? ' class="pre_vip_top_record"' : ''));
    } else if ($row->top_status == 1) {
        $output_tbody .= ' class="top_record"';
    } else {
    }
    
    $output_tbody .= '>';

    if ($profile_td_text == 'no') {
        $output_tbody .= '<td><input type="radio" order-status="'.($row->order_status == null ? '' : $row->order_status).'" top-status="'.$row->top_status.'" order-id="'.$row->order_id.'" record-active="'.$row->active.'" id="r_table_record_id" name="r_table_record_id" value="'.$row->id.'"';

        if (isset($_POST['table_record_id']) && $_POST['table_record_id'] == "$row->id") $output_tbody .= 'checked="checked"';

        $output_tbody .= '></td>';
    }
    
    /*
                <div class="record_number">
                    <span class="middle" title="Номер заявки">
                           № '.$row->id.'
                    </span>
                </div><br>
     */
    
    $output_tbody .= '
            <td>
                <div class="date_label" title="Дата публикации заявки">
                    '.$dt_created[0].'
                </div>
                <div class="time_label" title="Время публикации заявки">
                    '.str_replace(':', ' : ', $dt_created[1]).'
                </div><br>';
    
    if ($dt_pickup != '') {
        $output_tbody .= '<div class="date_label" title="Дата бесплатного поднятия заявки в ТОП">
                    '.$dt_pickup[0].'
                </div>
                <div class="time_label" title="Время бесплатного поднятия заявки в ТОП">
                    '.str_replace(':', ' : ', $dt_pickup[1]).'
                </div>';
    }
    
    $output_tbody .= '</td>
            <td style="min-width: 260px; width: 260px;">
                <div class="tbl_trucks_path_td">
                    <div class="city_label">'.htmlspecialchars(tzs_get_city($row->from_sid)).((count($path_segment_cities) > 2) ? '...' : '').'</div>
                    <div class="country_flag"><img id ="first_city_flag" src="/wp-content/plugins/tzs/assets/images/flags/'.$row->from_code.'.png"  width=18 height=12 alt=""></div>
                </div>
                <div class="tbl_trucks_dtc_td">
                    <div class="date_from_label" title="Дата погрузки">
                        '.convert_date_year2(($prefix === 'tr') ? $row->tr_date_from : $row->sh_date_from).'<br/>
                    </div>
                </div>
                <div class="tbl_trucks_path_td">
                    <div class="region_label">'.(($row->from_rid != NULL && $row->from_rid > 0 && $row->from_rid != 20070188) ? str_replace('область', 'обл.', htmlspecialchars(tzs_get_region($row->from_rid))) : '&nbsp;&nbsp;').'</div>
                </div>
                <div class="tbl_distance_td2">
                    <div class="distance_label">
            ';
    
    if (($row->distance > 0) && ($prefix === 'tr')) {
        //$output_tbody .= '&nbsp;расстояние '.tzs_make_distance_link($row->distance, false, array($row->tr_city_from, $row->tr_city_to));
        $output_tbody .= '&nbsp;расстояние '.tzs_make_distance_link($row->distance, false, explode(";", $row->path_segment_cities));
    }
    else if (($row->distance > 0) && ($prefix === 'sh')) {
        //$output_tbody .= '&nbsp;расстояние '.tzs_make_distance_link($row->distance, false, array($row->sh_city_from, $row->sh_city_to));
        $output_tbody .= '&nbsp;расстояние '.tzs_make_distance_link($row->distance, false, explode(";", $row->path_segment_cities));
    }

    $output_tbody .= ' (см. карту)</div>';
    $output_tbody .='            </div>
                <div class="tbl_trucks_path_td">
                    <div class="city_label">'.((count($path_segment_cities) > 2) ? '...' : '').htmlspecialchars(tzs_get_city($row->to_sid)).'</div>
                    <div class="country_flag"><img id ="second_city_flag" src="/wp-content/plugins/tzs/assets/images/flags/'.$row->to_code.'.png"  width=18 height=12 alt=""></div>
                </div>
                <div class="tbl_trucks_dtc_td">
                    <div class="date_to_label" title="Дата выгрузки">
                        '.convert_date_year2(($prefix === 'tr') ? $row->tr_date_to : $row->sh_date_to).'
                    </div>
                </div>
                <div class="tbl_trucks_path_td">
                    <div class="region_label">'.(($row->to_rid != NULL && $row->to_rid > 0 && $row->to_rid != 20070188) ? str_replace('область', 'обл.', htmlspecialchars(tzs_get_region($row->to_rid))) : '&nbsp;&nbsp;').'</div>';
    
    if (($row->cash + $row->nocash + $row->way_ship + $row->way_debark + $row->soft + $row->way_prepay) > 5) {
        $output_tbody .= '<div>&nbsp;<div>';
    }
    
    $output_tbody .= '            </div>
            </td>';
    
    if ($prefix === 'sh') {
        $output_tbody .= '<td>
                <div title="Тип груза">'.(isset($GLOBALS['tzs_sh_types'][$row->sh_type]) ? $GLOBALS['tzs_sh_types'][$row->sh_type] : '').'</div><br>
                <div class="tr_type_label" title="Тип транспортного средства">'.$type.'</div>
            </td>';
        
        $output_tbody .= '<td><div>';
        if (($row->tr_weight > 0) || ($row->sh_weight > 0)) {
            $output_tbody .= '<span title="Вес груза">'.remove_decimal_part(($prefix === 'tr') ? $row->tr_weight : $row->sh_weight).' т</span><br>';
        }

        if (($row->tr_volume > 0) || ($row->sh_volume > 0)) {
            $output_tbody .= '<span title="Объем груза">'.remove_decimal_part(($prefix === 'tr') ? $row->tr_volume : $row->sh_volume).' м³</span>';
        }
        $output_tbody .= '</div></td>
            <td><div title="Описание груза">'.$row->sh_descr.'</div></td>';
    } else {
        $output_tbody .= '<td>
                <div class="tr_type_label" title="Тип транспортного средства">'.$type.'</div>
            </td>
            <td><div title="Описание транспортного средства">';
        
        $tr_ds1 = '';
        $tr_ds2 = '';
        if ($row->tr_length > 0) {
            $tr_ds1 .= 'Д';
            $tr_ds2 .= intval($row->tr_length);
        }
        
        if ($row->tr_width > 0) {
            if ($tr_ds1 !== '') $tr_ds1 .= 'x';
            if ($tr_ds2 !== '') $tr_ds2 .= 'x';
            $tr_ds1 .= 'Ш';
            $tr_ds2 .= intval($row->tr_width);
        }
        
        if ($row->tr_height > 0) {
            if ($tr_ds1 !== '') $tr_ds1 .= 'x';
            if ($tr_ds2 !== '') $tr_ds2 .= 'x';
            $tr_ds1 .= 'В';
            $tr_ds2 .= intval($row->tr_height);
        }
            
        if (($tr_ds1 !== '') && ($tr_ds2 !== '')) 
            $output_tbody .= $tr_ds1.': '.$tr_ds2.' м<br>';
        
        if ($row->tr_weight > 0)
            $output_tbody .= remove_decimal_part($row->tr_weight).' т<br>';

        if($row->tr_volume > 0)
            $output_tbody .= remove_decimal_part($row->tr_volume).' м³<br>';
                                    
        if ($row->tr_descr && (strlen($row->tr_descr) > 0))
            $output_tbody .= $row->tr_descr.'<br>';
            
        $output_tbody .= '</div></td>
            <td><div title="Желаемый груз">'.$row->sh_descr.'</div></td>';
    }

                
    

    $output_tbody .= '<td>';
    //if ($row->price > 0) {
//                round($row->price / $row->distance, 2).' '.$GLOBALS['tzs_curr'][$row->price_val].
//        number_format($row->cost, 0, '.', ' ').' '.$GLOBALS['tzs_curr'][$row->price_val].'<div><br>
//                $row->price.' '.$GLOBALS['tzs_curr'][$row->price_val].
//                '/км)</div>'; 
        $output_tbody .= '<div class="price_label" title="Стоимость перевозки груза">'.$cost[0].'<div><br>';
        if (strlen($cost[1]) > 0)
            $output_tbody .= '<div class="cost_label" title="Цена за 1 км перевозки груза">('.$cost[1].')</div>'; 
    //} else {
    //    $output_tbody .= '<div  class="price_label" title="Стоимость перевозки груза">'.$cost[0].'</div>';
    //}

//                <div  class="payment_label" title="Форма оплаты услуг по перевозке груза">'.$cost[1].'</div>
    $output_tbody .= '
            </td>
            <td>
                <div  class="payment_label" title="Форма оплаты услуг по перевозке груза">'.str_replace(', ', ',<br>', $cost[2]).'</div>
            </td>';
                //<div  class="payment_label" title="Форма оплаты услуг по перевозке груза">'.str_replace(', ', ',<br>', $cost[1]).'</div>
    
    if ($prefix === 'tr') {
        //$output_tbody .= '<td><div title="Комментарии">'.$row->comment.'</div></td>';
    }
    
    if ($profile_td_text == 'no') {
        $output_tbody .= '';
    } else if ($profile_td_text) {
        $output_tbody .= '<td>'.$profile_td_text.'</td>';
    } else {
        $output_tbody .= '<td>'.tzs_print_user_contacts($row, $form_type, 0).'</td>';
    }
    
    $output_tbody .= '</tr>';
    
    return $output_tbody;
}

/*
 * Выборка данных на основании фильтра и формирование строк таблицы с данными
 */
function tzs_front_end_tables_reload() {
    // Возвращаемые переменные
    $output_info = '';
    $output_error = '';
    $output_tbody = '';
    $output_pnav = '';
    $lastrecid = 0;
    
    $form_type = get_param_def('form_type', '');
    $type_id = get_param_def('type_id', '0');
    $rootcategory = get_param_def('rootcategory', '0');
    $cur_type_id = get_param_def('cur_type_id', '0');
    $cur_post_name = get_param_def('cur_post_name', '');
    $p_title = get_param_def('p_title', '');
    $page = get_param_def('page', '1');
    $records_per_page = get_param_def('records_per_page', ''.TZS_RECORDS_PER_PAGE);
    $record_pickup_time = get_option('t3s_setting_record_pickup_time', '30');
    

    //$p_id = get_the_ID();
    //$p_title = the_title('', '', false);
    
    // Если указан параметр rootcategory, то выводим все товары раздела
    // Иначе - товары категории
    if (($rootcategory === '1') && ($type_id === '0')) {
        $sql1 = ' AND type_id IN ('.tzs_build_product_types_id_str($cur_type_id).')';
        $p_name = '';
    } else {
        //$sql1 = ' AND type_id='.$type_id;
        $sql1 = '';
        $p_name = get_post_field( 'post_name', $type_id );
    }
    
    if ($form_type === 'products') {
        $sp = tzs_validate_pr_search_parameters();
    } else {
        $sp = tzs_validate_search_parameters();
    }
    
    $errors = $sp['errors'];

    switch ($form_type) {
        case 'products': {
            $pr_type_array = tzs_get_children_pages(TZS_PR_ROOT_CATEGORY_PAGE_ID);
            $table_name = TZS_PRODUCTS_TABLE;
            $table_error_msg = 'товаров';
            $table_order_by = 'created';
            $order_table_prefix = 'PR';
            break;
        }

        case 'trucks': {
            $table_name = TZS_TRUCK_TABLE;
            $table_error_msg = 'транспорта';
            $table_order_by = 'time';
            $table_prefix = 'tr';
            $order_table_prefix = 'TR';
            break;
        }

        case 'shipments': {
            $table_name = TZS_SHIPMENT_TABLE;
            $table_error_msg = 'грузов';
            $table_order_by = 'time';
            $table_prefix = 'sh';
            $order_table_prefix = 'SH';
            break;
        }        
        
        default: {
            array_push($errors, "Неверно указан тип формы");
        }
    }
    
    if (count($errors) > 0) {
        $output_error = print_errors($errors);
    }
        
    
    if (count($errors) == 0) {
        if ($form_type === 'products') {
            $s_sql = tzs_search_pr_parameters_to_sql($sp, '');
            $s_title = tzs_search_pr_parameters_to_str($sp);
        } else {
            $s_sql = tzs_search_parameters_to_sql($sp, $table_prefix);
            $s_title = tzs_search_parameters_to_str($sp);
        }
	
	$output_info = $p_title;
        if (strlen($s_title) > 0) {
            $output_info .= ' * '. $s_title;
        }
        
        //$page = current_page_number();

        global $wpdb;

        //$url = current_page_url();

        $pp = floatval($records_per_page);

        $sql = "SELECT COUNT(*) as cnt FROM ".$table_name." a WHERE active=1 $sql1 $s_sql;";
        $res = $wpdb->get_row($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
            $output_error .= '<div>Не удалось отобразить список '.$table_error_msg.'. Свяжитесь, пожалуйста, с администрацией сайта.<br>'.$sql.'<br>'.$wpdb->last_error.'</div>';
        } else {
            $records = $res->cnt;
            $pages = ceil($records / $pp);
            if ($pages == 0)
                    $pages = 1;
            if ($page > $pages)
                    $page = $pages;

            $from = ($page-1) * $pp;
            //$sql = "SELECT * FROM ".$table_name." WHERE active=1 $sql1 $s_sql ORDER BY ".$table_order_by." DESC LIMIT $from,$pp;";
            // Хитрый запрос для отбора ТОП
            $sql  = "SELECT a.*,";
            $sql .= " b.number AS order_number,";
            $sql .= " b.status AS order_status,";
            $sql .= " b.dt_pay AS order_dt_pay,";
            $sql .= " b.dt_expired AS order_dt_expired,";
            $sql .= " IFNULL(b.dt_pay, a.".$table_order_by.") AS dt_sort,";
            $sql .= " IF(b.status IS NOT NULL, 2, IF(ROUND((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(a.dt_pickup))/60, 0) <= ".$record_pickup_time.", 1, 0)) AS top_status,";
            $sql .= " LOWER(c.code) AS from_code";
            if ($form_type != 'products') 
                $sql .= ", LOWER(d.code) AS to_code";
            $sql .= " FROM ".$table_name." a";
            $sql .= " LEFT OUTER JOIN wp_tzs_orders b ON (b.tbl_type = '".$order_table_prefix."' AND a.id = b.tbl_id AND b.status = 1 AND b.dt_expired > NOW())";
            $sql .= " LEFT OUTER JOIN wp_tzs_countries c ON (a.from_cid = c.country_id)";
            if ($form_type != 'products')
                $sql .= " LEFT OUTER JOIN wp_tzs_countries d ON (a.to_cid = d.country_id)";
            $sql .= " WHERE active=1 $sql1 $s_sql";
            $sql .= " ORDER BY top_status DESC, order_status DESC, dt_sort DESC";
            $sql .= " LIMIT $from,$pp;";
            $res = $wpdb->get_results($sql);
            if (count($res) == 0 && $wpdb->last_error != null) {
                $output_error .= '<div>Не удалось отобразить список '.$table_error_msg.'. Свяжитесь, пожалуйста, с администрацией сайта.<br>'.$sql.'<br>'.$wpdb->last_error.'</div>';
            } else {
                if (count($res) == 0) {
                    $output_error .= '<div>По Вашему запросу ничего не найдено.</div>';
                } else {
                    foreach ( $res as $row ) {
                        if ($form_type === 'products') {
                            $output_tbody .= tzs_products_table_record_out($row, $form_type, $pr_type_array);
                        } else {
                            $output_tbody .= tzs_tr_sh_table_record_out($row, $form_type);
                        }
                        
                        $lastrecid = $row->id;
                    }
                }

                // Пагинация
                if ($pages > 1) {
                    if ($page > 1) {
                        $page0 = $page - 1;
                        $output_pnav .= '<a tag="page" page="'.$page0.'" href="javascript:TblTbodyReload('.$page0.')">« Предыдущая</a>&nbsp;';
                    }
                    
                    $start = 1;
                    $stop = $pages;
                    
                    for ($i = $start; $i <= $stop; $i++) {
                        if ($i == $page) {
                            $output_pnav .= '&nbsp;&nbsp;<span>'.$i.'</span>&nbsp;';
                        } else {
                            $output_pnav .= '&nbsp;&nbsp;<a tag="page" page="'.$i.'" href="javascript:TblTbodyReload('.$i.')">'.$i.'</a>&nbsp;';
                        }
                    }
                    
                    if ($page < $pages) {
                        $page1 = $page + 1;
                        $output_pnav .= '&nbsp;&nbsp;<a tag="page" page="'.$page1.'" href="javascript:TblTbodyReload('.$page1.')">Следующая »</a>';
                    }
                }
            }
        }
    }

    $output = array(
        'output_info' => $output_info, 
        'output_error' => $output_error, 
        'output_tbody' => $output_tbody,
        'output_pnav' => $output_pnav,
        'output_tbody_cnt' => count($res),
        'lastrecid' => $lastrecid,
        'type_id' => $type_id,
        'rootcategory' => $rootcategory,
        'sql' => $sql,
        'sql1' => $sql1,
        's_sql' => $s_sql,
    );

   //echo json_encode($output);
  // print_r($output_tbody);
    return $output;
}
?>