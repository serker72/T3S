<?php

/* 
 * Функции для плагина T3S
 * Author: Sergey Kerimov
 */

/*
 * Добавление телефона
 */
function add_tel(){
    $name = $_POST['name-tel'];
    $fam  = $_POST['fam-tel'];
    $tel  = $_POST['tel-tel'];
    $time_from = $_POST['tel-time-from'];
    $time_to  = $_POST['tel-time-to'];
    
    $EOF = "\r\n";
    $admin_email = get_option( 'admin_email' );
    $operator_email = get_option( 't3s_setting_email_callback' );
    $msg_body = "Поступил запрос обратного звонка от посетителя $name $fam на номер телефона $tel".$EOF."Удобное время для звонка с $time_from до $time_to.";
    
    $ret_flag = ksk_sendMailAttachments($operator_email, $admin_email, "Заказ звонка", $msg_body, '', array());
    
    //if (mail(get_option( 't3s_setting_email_callback' ), "Заказ звонка", "Заказ звонка от ".$name." ".$fam." телефон - ".$tel)) echo "Message send";
    if ($ret_flag) echo "Message send";
    else echo "Error sending";
    
    wp_die();
}
add_action("wp_ajax_add_tel", "add_tel");
add_action("wp_ajax_nopriv_add_tel", "add_tel");

/*
 * Добавление сообщения
 */
function add_message(){
    $name = $_POST['ninja_forms_field_1'];
    $email  = $_POST['ninja_forms_field_2'];
    $tel  = $_POST['ninja_forms_field_3'];
    $msg_body  = $_POST['ninja_forms_field_6'];
    
    $EOF = "\r\n";
    $admin_email = get_option( 'admin_email' );
    $support_email = get_option('t3s_setting_email_support');
    
    if ($name && $email && $msg_body) {
        $msg_body_full  = "С помощью формы обратной связи отправлено новое сообщение.".$EOF;
        $msg_body_full .= "----------------------------------------------------------".$EOF;
        $msg_body_full .= " Имя посетителя: $name".$EOF;
        $msg_body_full .= " E-Mail: $email".$EOF;
        $msg_body_full .= " Номер телефона: $tel".$EOF.$EOF;
        $msg_body_full .= " Текст сообщения:".$EOF;
        $msg_body_full .= "----------------------------------------------------------".$EOF;
        $msg_body_full .= $msg_body;

        $ret_flag = ksk_sendMailAttachments($support_email, $admin_email, "Сообщение сайта t3s: форма обратной связи", $msg_body_full, $email, array());
    } else {
        $ret_flag = false;
    }
    
    //if (mail(get_option( 't3s_setting_email_callback' ), "Заказ звонка", "Заказ звонка от ".$name." ".$fam." телефон - ".$tel)) echo "Message send";
    if ($ret_flag) echo "Message send";
    else echo "Error sending";
    
    wp_die();
}
add_action("wp_ajax_add_message", "add_message");
add_action("wp_ajax_nopriv_add_message", "add_message");

/* 
 * add the bet
 */
function add_bet(){
    global $wpdb;
    $ID = $_POST['user_id'];
    $name_user = $_POST['user_nemr'];
    $text_bet = $_POST['text_bet'];
    $auction_id = $_POST['auction_id'];
    $rate = $_POST['bet_user'];
    $create = $_POST['created'];
    $currency = $_POST['currency'];
    $fl = $wpdb->insert( 
        'wp_tzs_product_rates', 
        array( 
        'product_id' => $auction_id,
        'user_id' => $ID,
        'created' => $create,
        'rate' => $rate,
        'currency' => $currency,
        'active' => '1',
            'description' => $text_bet 
        ) 
    ); 

    if ($fl) {
        $sql = "SELECT * FROM ".TZS_PRODUCT_RATES_TABLE." WHERE product_id=$auction_id ORDER BY active DESC,created DESC;";
        $res = $wpdb->get_results($sql);
        if (count($res) == 0 && $wpdb->last_error != null) {
            echo $sql;
            print_error('Не удалось отобразить информацию о ставках. Свяжитесь, пожалуйста, с администрацией сайта.');
        } else if (count($res) == 0) {
            print_error('Ставки не найдены');
        } else {
            $str_table='<table border="0" id="tbl_products" style="float: none !important;">
                <tr>
                    <th>Статус</th>
                    <th id="tbl_products_dtc">Размещена <br /> Отозвана</th>
                    <!-- <th id="tbl_products_dtc">Дата и время отзыва</th> -->
                    <th id="price">Предложенная стоимость</th>
                    <th id="price">Комментарий</th>';
            
            if (($user_id !== 0) || ($GLOBALS['tzs_au_contact_view_all'] !== false)) {
            $str_table.='<th id="price">Автор <br /> Контактные данные</th></tr>';}
            $i=0;
            foreach ( $res as $row ) {
                $user_info = get_userdata($row->user_id);
                if ($row->reviewed == null) $reviewed="&nbsp"; else{$reviewed=convert_time($row->reviewed);}
                if ($row->active == 1) {$active_bet='Активна';} 
                else {$active_bet='Отозвана';}
                $str_table.='<tr id="'.$active_bet.'">
                    <td>'.$active_bet.'</td>
                    <td>'.convert_time($row->created).'<br />'.$reviewed.'</td>
                    <td>'.$row->rate.' '.$GLOBALS["tzs_pr_curr"][$row->currency].'</td>
                    <td>'.$row->description.'</td>';
                    if (($user_id !== 0) || ($GLOBALS['tzs_au_contact_view_all'] !== false)) {
                    $str_table.='<td>';
                        $meta = get_user_meta($row->user_id, 'fio'); $str_table.=$meta[0].'<br />'; 
                        $meta = get_user_meta($row->user_id, 'telephone');  if ($meta[0] == null)  $str_table.=''; else $str_table.='Номера телефонов: '.htmlspecialchars($meta[0]).'<br/>';
                        if ($user_info->user_email == null) $str_table.=''; else $str_table.='E-mail: '.htmlspecialchars($user_info->user_email).'<br/>';
                        $meta = get_user_meta($row->user_id, 'skype'); if ($meta[0] == null) $str_table.=''; else $str_table.='Skype: '.htmlspecialchars($meta[0]).'<br/>';
                    $str_table.='</td>';
                    } 
                $str_table.='</tr>';
                if ($i==0) $str_table.='<input id="act_rate" style="display: none;" value="'.round($max,2).'" />'; 
                $i++;
            } 
            $str_table.='</table>';
        }

        echo $str_table;
    } else echo "All the bad";
    
    wp_die();
}
add_action("wp_ajax_add_bet", "add_bet");
add_action("wp_ajax_nopriv_add_bet", "add_bet");

/**
 * Функция подбора шаблона для вывода записи по category_nicename
 */
function tzs_post_category_nicename_single() {
    global $wp_query, $post;
    
    // Пробежимся по массиву категорий поста
    // Вернем первый существующий файл с шаблоном single-category_nicename.php
    foreach((get_the_category()) as $category) {
        if(file_exists(TEMPLATEPATH . '/single-' . $category->category_nicename . '.php')) {
            return TEMPLATEPATH . '/single-' . $category->category_nicename . '.php';
        }
    }
    
    // Если не обнаружены файлы с шаблонами, то вернем шаблон по умолчанию
    return TEMPLATEPATH . '/single.php';
}
// Добавим фильтр для 
add_filter('single_template', 'tzs_post_category_nicename_single');

/**
 * Загрузка данных в таблицу с помощью Ajax
 */
function tzs_tables_reload() {
    include_once(WP_PLUGIN_DIR . '/tzs/front-end/tzs.tables_reload.php');
    $output = tzs_front_end_tables_reload();
    //echo 'Test tzs_products_reload';
    echo json_encode($output);
	//print_r($output);
    wp_die();
}
add_action("wp_ajax_tzs_tables_reload", "tzs_tables_reload");
add_action("wp_ajax_nopriv_tzs_tables_reload", "tzs_tables_reload");


/**
 * Загрузка данных регионов в select с помощью Ajax
 */
function tzs_regions_reload() {
    include_once(WP_PLUGIN_DIR . '/tzs/functions/tzs.functions.php');
    tzs_get_regions();
    wp_die();
}
add_action("wp_ajax_tzs_regions_reload", "tzs_regions_reload");
add_action("wp_ajax_nopriv_tzs_regions_reload", "tzs_regions_reload");

/**
 * Запись часового пояса в $_SESSION
 */
function tzs_timezone_offset_session_set() {
    global $wpdb;
    
    
    $timezone_offset = isset($_POST['timezone_offset']) && is_numeric($_POST['timezone_offset']) ? intval( $_POST['timezone_offset'] ) : 0;
    $query_str = "SET time_zone = '".($timezone_offset > 0 ? '+'.$timezone_offset : $timezone_offset).":00';";

    $row1 = $wpdb->get_row("SELECT now()+0 AS ct;");
    $wpdb->query($query_str);
    $row2 = $wpdb->get_row("SELECT now()+0 AS ct;");
    $cto = round(($row2->ct - $row1->ct)/10000, 0);
    
    $timezone_offset_enabled = ($cto == $timezone_offset);
    
    if (!isset($_SESSION['timezone_offset']) || ($_SESSION['timezone_offset'] != $timezone_offset)) {
        $_SESSION['timezone_offset'] = $timezone_offset;
        //echo 'timezone_offset set to session, ct1 = '.$row1->ct.', ct2 = '.$row2->ct.', cto = '.$cto;
    }
        
    if (!isset($_SESSION['timezone_offset_enabled']) || ($_SESSION['timezone_offset_enabled'] != $timezone_offset_enabled)) {
        $_SESSION['timezone_offset_enabled'] = $timezone_offset_enabled;
        //echo 'timezone_offset_enabled set to session, ct1 = '.$row1->ct.', ct2 = '.$row2->ct.', cto = '.$cto;
    }
        
    wp_die();
}
add_action("wp_ajax_tzs_timezone_offset_session_set", "tzs_timezone_offset_session_set");
add_action("wp_ajax_nopriv_tzs_timezone_offset_session_set", "tzs_timezone_offset_session_set");

/* 
 * Функция для отправки письма с вложенными файлами
 */
function ksk_sendMailAttachments($mail_to, $mail_from, $mail_subject, $mail_message, $mail_reply_to = '', $mail_attachments = array()) {
    if (($mail_to === null) || ($mail_to === '')) {
        return array(false, 'Не задана обязательный параметр $mail_to');
    }
    
    if (($mail_from === null) || ($mail_from === '')) {
        return array(false, 'Не задана обязательный параметр $mail_from');
    }
    
    if (($mail_subject === null) || ($mail_subject === '')) {
        return array(false, 'Не задана обязательный параметр $mail_subject');
    }
    
    if (($mail_message === null) || ($mail_message === '')) {
        return array(false, 'Не задана обязательный параметр $mail_message');
    }
    
    // Если на задан адрес Reply-To, то он совпадает с From
    if ($mail_reply_to === '') {
        $mail_reply_to = $mail_from;
    }
    
    $EOF = "\r\n";
    
    //Письмо с вложением состоит из нескольких частей, которые разделяются разделителем
    // Генерируем разделитель    
    $boundary = md5(uniqid(time()));
    
    // разделитель указывается в заголовке в параметре boundary
    $mailheaders = "MIME-Version: 1.0;".$EOF; 
    $mailheaders .= "Content-Type: multipart/mixed; boundary=$boundary".$EOF; 
    
    $mailheaders .= "From: $mail_from".$EOF; 
    $mailheaders .= "Reply-To: $mail_reply_to".$EOF; 
    
    // первая часть само сообщение    
    $multipart = "--$boundary".$EOF; 
    $multipart .= "Content-Type: text/plain; charset=UTF-8".$EOF;
    $multipart .= "Content-Transfer-Encoding: base64".$EOF;    
    $multipart .= $EOF;
    $multipart .= chunk_split(base64_encode($mail_message));
    
    // Цикл по кол-ву вложений
    for ($i=0;$i<count($mail_attachments);$i++) {
        // чтение файла        
        if (file_exists($mail_attachments[$i])) {
            $fp = fopen($mail_attachments[$i],"r"); 
            if (!$fp) { 
                return array(false, 'Не удается открыть файл '.$mail_attachments[$i]); 
            } 
            $file = fread($fp, filesize($mail_attachments[$i])); 
            fclose($fp);
            
            $fn = basename($mail_attachments[$i]);

            $message_part = "--$boundary".$EOF; 
            $message_part .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($fn)."?=".$EOF;  
            $message_part .= "Content-Transfer-Encoding: base64".$EOF; 
            $message_part .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($fn)."?=".$EOF; 
            $message_part .= "".$EOF;
            $message_part .= chunk_split(base64_encode($file)) . $EOF;

            // второй частью прикрепляем файл, можно прикрепить два и более файла
            $multipart .= $message_part;
        }
    }
    
    $multipart .= "--$boundary--".$EOF; 
    
    // отправляем письмо 
    $result = mail($mail_to, $mail_subject, $multipart, $mailheaders);
    if ($result) {
        return array($result, 'Письмо отправлено успешно по адресу '.$mail_to); 
    } else {
        return array($result, 'Ошибка при отправке письма по адресу '.$mail_to); 
    }
}

/**
 * Поднятие объявления в ТОП с помощью Ajax
 */
function tzs_record_pickup() {
    $output = tzs_record_pickup_top();
    echo json_encode($output);
    wp_die();
}
add_action("wp_ajax_tzs_record_pickup", "tzs_record_pickup");
add_action("wp_ajax_nopriv_tzs_record_pickup", "tzs_record_pickup");

/**
 * Создание счета на оплату услуг поднятия объявления в ТОП с помощью Ajax
 */
function tzs_order_add() {
    //include_once(WP_PLUGIN_DIR . '/tzs/front-end/tzs.tables_reload.php');
    $output = tzs_new_order_add();
    //echo 'Test tzs_products_reload';
    echo json_encode($output);
    wp_die();
}
add_action("wp_ajax_tzs_order_add", "tzs_order_add");
add_action("wp_ajax_nopriv_tzs_order_add", "tzs_order_add");

/**
 * Загрузка данных в таблицу с помощью Ajax
 */
function tzs_order_hand_pay() {
    //include_once(WP_PLUGIN_DIR . '/tzs/front-end/tzs.tables_reload.php');
    $output = tzs_hand_pay_order();
    //echo 'Test tzs_products_reload';
    echo json_encode($output);
    wp_die();
}
add_action("wp_ajax_tzs_order_hand_pay", "tzs_order_hand_pay");
add_action("wp_ajax_nopriv_tzs_order_hand_pay", "tzs_order_hand_pay");

/**
 * Проверка существования счета в Приват-24 с помощью Ajax
 */
function tzs_check_order_p24() {
    $output = tzs_check_order_exist_p24();
    echo json_encode($output);
    wp_die();
}
add_action("wp_ajax_tzs_check_order_p24", "tzs_check_order_p24");
add_action("wp_ajax_nopriv_tzs_check_order_p24", "tzs_check_order_p24");
