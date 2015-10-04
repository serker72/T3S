<?php

include_once(TZS_PLUGIN_DIR.'/functions/privat24api.php');

/*******************************************************************************
 * 
 * tzs_find_latest_product_rec - получение ID последней добавленной записи в таблицу products
 * 
 *******************************************************************************/
function tzs_find_latest_product_rec() {
    global $wpdb;

    $user_id = get_current_user_id();

    $sql = "SELECT id FROM ".TZS_PRODUCTS_TABLE." WHERE user_id=$user_id ORDER BY id DESC LIMIT 1;";

    $row = $wpdb->get_row($sql);
    if ($row != null && count($row) != 0 && $wpdb->last_error == null)
            return $row->id;
    return 0;
}

/*******************************************************************************
 * 
 * tzs_find_latest_order_rec - получение ID последней добавленной записи в таблицу orders
 * 
 *******************************************************************************/
function tzs_find_latest_order_rec() {
    global $wpdb;

    $user_id = get_current_user_id();

    $sql = "SELECT id FROM ".TZS_ORDERS_TABLE." WHERE user_id=$user_id ORDER BY id DESC LIMIT 1;";

    $row = $wpdb->get_row($sql);
    if ($row != null && count($row) != 0 && $wpdb->last_error == null)
            return $row->id;
    return 0;
}


/*******************************************************************************
 * 
 * tzs_get_user_meta - получение информации из таблицы wp_user_meta
 * 
 *******************************************************************************/
function tzs_get_user_meta($user_id) {
    if ($user_id && ($user_id > 0)) {
	$user_info = get_userdata($user_id);
        
        return array(
            'id' => $uid,
            'user_login' => $user_info->user_login,
            'user_nicename' => $user_info->user_nicename,
            'user_email' => $user_info->user_email,
            'user_status' => $user_info->user_status,
            'fio' => get_user_meta($user_id, 'fio', true),
            'skype' => get_user_meta($user_id, 'skype', true),
            'telephone' => get_user_meta($user_id, 'telephone', true),
            'company' => get_user_meta($user_id, 'company', true),
            'company_description' => get_user_meta($user_id, 'company_description', true),
            'company_logo' => get_user_meta($user_id, 'company_logo', true),
            'description' => get_user_meta($user_id, 'description', true),
            'kod_edrpou' => get_user_meta($user_id, 'kod_edrpou', true),
            'adress' => get_user_meta($user_id, 'adress', true),
            'tel_fax' => get_user_meta($user_id, 'tel_fax', true),
        );
//            '' => get_user_meta($user_id, '', true),
    } else {
        return array();
    }
}


/*******************************************************************************
 * 
 * tzs_new_order_add - добавленной записи в таблицу orders
 * 
 *******************************************************************************/
function tzs_new_order_add() {
    global $wpdb;
    
    $errors = array();
    $order_id = 0;

    // ID последнего счета для пользователя до добавления
    $old_last_rec = tzs_find_latest_order_rec();
    
    $user_id = get_current_user_id();
    
    $order_tbl_type = get_param('order_tbl_type');
    $order_tbl_id = get_param('order_tbl_id');

    $sql = $wpdb->prepare("INSERT INTO ".TZS_ORDERS_TABLE.
            " (user_id, tbl_type, tbl_id, status, number, dt_create, cost, currency)".
            " VALUES (%d, %s, %d, 0, %s, now(), %f, 1);",
            $user_id, stripslashes_deep($order_tbl_type), intval($order_tbl_id), 
            stripslashes_deep($order_tbl_type.'.x.'.$order_tbl_id), intval(get_option('t3s_setting_record_pickup_cost')));
    
    if (false === $wpdb->query($sql)) {
        array_push($errors, "Не удалось создать счет. Свяжитесь, пожалуйста, с администрацией сайта");
        array_push($errors, $wpdb->last_error);
    } else {
        $new_last_rec = tzs_find_latest_order_rec();
        if ($new_last_rec <= $old_last_rec) {
            
        } else {
            $sql = $wpdb->prepare("UPDATE ".TZS_ORDERS_TABLE." SET ".
                    " number=CONCAT(%s, DATE_FORMAT(dt_create, %s))".
                    "  WHERE id=%d AND user_id=%d;",
                    stripslashes_deep($order_tbl_type.'.'.$new_last_rec.'.'.$order_tbl_id.'.'), '%Y%m%d', $new_last_rec, $user_id);
            if (false === $wpdb->query($sql)) {
                array_push($errors, "Не удалось создать счет. Свяжитесь, пожалуйста, с администрацией сайта");
                array_push($errors, $wpdb->last_error);
            } else {
                array_push($errors, "Успешно создан счет на оплату, ID: $new_last_rec");
                array_push($errors, "Подождите, выполняется переход на страницу просмотра счета...");
                $order_id = $new_last_rec;
            }
        }
    }
    
    
    $output = array(
        'output_error' => implode('<br>', $errors),
        'order_id' => $order_id,
    );

    return $output;
}


/*******************************************************************************
 * 
 * tzs_hand_pay_order - ручная оплата счета
 * 
 *******************************************************************************/
function tzs_hand_pay_order() {
    global $wpdb;
    
    $errors = array();
    $order_id = 0;

    $user_id = get_current_user_id();
    
    $order_id = get_param('order_id');
    $order_dt_pay = get_param('order_dt_pay');
    $order_status = get_param('order_status');
    if ($order_status == '1') {
        $ts = strtotime($order_dt_pay);
        $dt = date('Y-m-d H:i:s', $ts);
        $tse = new DateTime($dt);
        date_add($tse, date_interval_create_from_date_string((get_option('t3s_setting_record_pickup_days') + 1).' days'));
        $dte = date_format($tse, 'Y-m-d');
        $order_pay_method = 4;
    } else {
        $dt = 'null';
        $dte = 'null';
        $order_pay_method = 0;
    }

    $sql = $wpdb->prepare("UPDATE ".TZS_ORDERS_TABLE." SET ".
            " dt_pay=%s, dt_expired=%s, status=%d, pay_method=%d".
            " WHERE id=%d AND user_id=%d;",
            stripslashes_deep($dt), stripslashes_deep($dte), intval($order_status), intval($order_pay_method), 
            intval($order_id), $user_id);
    $sql = str_replace("'null'", "null", $sql);
    
    if (false === $wpdb->query($sql)) {
        array_push($errors, "Не удалось обновить счет. Свяжитесь, пожалуйста, с администрацией сайта");
        array_push($errors, $wpdb->last_error);
    } else {
        array_push($errors, "Удачно обновлен счет.");
        array_push($errors, "Подождите, выполняется обновление страницы...");
        //array_push($errors, $sql);
    }
    
    
    $output = array(
        'output_error' => implode('<br>', $errors),
        'order_id' => $order_id,
    );

    return $output;
}


/*******************************************************************************
 * 
 * tzs_pay_order_p24 - оплата счета через Приват-24
 * 
 *******************************************************************************/
function tzs_pay_order_p24($order_pay_method) {
    global $wpdb;
    
    $errors = array();

    $user_id = get_current_user_id();
    $order_status = 1;

    $PrivatAPI = new privat24api(get_option('t3s_setting_merchant_id'), get_option('t3s_setting_merchant_pass'));
    $pay_res = $PrivatAPI->GetPaymentResult();
    
    // Если результирующий массив пустой
    if (count($pay_res) > 0) {
        $order_array = explode('.', $pay_res['order']);

        $order_id = $order_array[1];

        $order_dt_pay = substr($pay_res['date'], 0, 2).'.'.substr($pay_res['date'], 2, 2).'.20'.substr($pay_res['date'], 4, 2).' '.substr($pay_res['date'], 6, 2).':'.substr($pay_res['date'], 8, 2).':'.substr($pay_res['date'], 10, 2);

        // Временное смещение
        if (isset($_SESSION['timezone_offset_enabled']) && isset($_SESSION['timezone_offset']) && $_SESSION['timezone_offset_enabled'] && is_numeric($_SESSION['timezone_offset'])) {
            $timezone_offset = $_SESSION['timezone_offset'] * (-1);
        } else {
            $timezone_offset = 0;
        }
        
        $ts = strtotime($order_dt_pay)+ ($timezone_offset*3600);
        $dt = date('Y-m-d H:i:s', $ts);
        $tse = new DateTime($dt);
        date_add($tse, date_interval_create_from_date_string((get_option('t3s_setting_record_pickup_days') + 1).' days'));
        $dte = date_format($tse, 'Y-m-d');

        $sql = $wpdb->prepare("UPDATE ".TZS_ORDERS_TABLE." SET ".
                " dt_pay=%s, dt_expired=%s, status=%d, pay_method=%d".
                " WHERE id=%d AND user_id=%d;",
                stripslashes_deep($dt), stripslashes_deep($dte), intval($order_status), intval($order_pay_method), 
                intval($order_id), $user_id);
        $sql = str_replace("'null'", "null", $sql);

        if (false === $wpdb->query($sql)) {
            array_push($errors, "Не удалось обновить счет. Свяжитесь, пожалуйста, с администрацией сайта");
            array_push($errors, $wpdb->last_error);
        } else {
        }
    } else {
        array_push($errors, $PrivatAPI->errmess);
    }
    
    
    $output = array(
        'output_error' => implode('<br>', $errors),
        'order_id' => $order_id,
        'order_dt_pay' => $order_dt_pay,
        'ts' => $ts,
        'dt' => $dt,
        'dte' => $dte,
        'tse' => $tse,
    );

    return $output;
}

/*
 * Проверка существования документа в системе Приват-24
 */
function tzs_check_order_exist_p24() {
    global $wpdb;
    
    $errors = array();
    $new_order = '';
    
    $user_id = get_current_user_id();
    
    $order = get_param('order');
    $pay_count = get_param('pay_count');
    $order_id = get_param('order_id');
    $order_number = get_param('order_number');
    
    $PrivatAPI = new privat24api(get_option('t3s_setting_merchant_id'), get_option('t3s_setting_merchant_pass'));
    $order_status = $PrivatAPI->CheckOrPaymentOrder($order, 1);
    $order_error = $PrivatAPI->getErrorMessage();
    $order_state = $PrivatAPI->getPaymentState();
    
    if ($order_error !== "no error") {
        array_push($errors, $order_error);
    }
    
    // Если статус = incomplete - пользователь не оплатил документ и с ним уже ничего не сделаешь
    if ($order_status == 0) {
        array_push($errors, "Предыдущая попытка оплаты счета не увенчалась успехом.");
        
        // Если не превышено кол-во попыток оплаты - увеличиваем счетчик попыток и изменяем номер счета
        if ($pay_count < get_option('t3s_setting_pay_count')) {
            $sql = $wpdb->prepare("UPDATE ".TZS_ORDERS_TABLE." SET ".
                    " pay_count=pay_count+1".
                    " WHERE id=%d AND user_id=%d;",
                    intval($order_id), $user_id);
    
            if (false === $wpdb->query($sql)) {
                array_push($errors, "Не удалось увеличить количество попыток оплаты счета. Свяжитесь, пожалуйста, с администрацией сайта");
                array_push($errors, $wpdb->last_error);
            } else {
                array_push($errors, "Увеличено количество попыток оплаты счета.");
                $new_order = $order_number . '.' . ($pay_count + 1);
            }
        } else {
            array_push($errors, "Исчерпано количество попыток оплаты счета.");
        }
    } elseif ($order_status == 1) {
    } elseif ($order_status == 2) {
        array_push($errors, "Счет уже оплачен.");
    } elseif ($order_status == 3) {
        array_push($errors, "Документ на оплату счета забракован системой Приват-24.");
    } elseif ($order_status == 4) {
        array_push($errors, "Обнаружен тестовый документ на оплату счета.");
    } elseif ($order_status == 5) {
        array_push($errors, "Документ на оплату счета находится в очереди системы Приват-24.");
    }
    
    $output = array(
        'order_number' => $order,
        'order_status' => $order_status,
        'order_state' => $order_state,
        'order_new_number' => $new_order,
        'output_error' => implode('<br>', $errors),
    );

    return $output;
}

?>