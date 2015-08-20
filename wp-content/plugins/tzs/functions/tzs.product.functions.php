<?php
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

?>