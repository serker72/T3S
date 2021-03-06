<?php

/* 
 * Добавление блока опций на страницу General
 * Author: Sergey Kerimov
 */

// ------------------------------------------------------------------
// Вешаем все блоки, поля и опции на хук admin_init
// ------------------------------------------------------------------
//
add_action('admin_init', 't3s_settings_api_init');

function t3s_settings_api_init() {
    // Добавляем блок опций на базовую страницу "Чтение"
    add_settings_section(
            't3s_setting_section', // секция
            'Настройки для сайта T3S.biz',
            't3s_setting_section_callback_function',
            'general' // страница
    );

    // Добавляем поля опций. Указываем название, описание, 
    // функцию выводящую html код поля опции.
    add_settings_field(
            't3s_setting_contact_view_all',
            'Отображать контактные данные незарегистрированным пользователям',
            't3s_setting_callback_function', // можно указать ''
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_email_callback',
            'E-Mail оператора для получения данных из формы обратного звонка',
            't3s_setting_callback_function2',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_email_support',
            'E-Mail для получения данных из формы обратной связи',
            't3s_setting_callback_function3',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_pr_publication_min_days',
            'Минимальный период публикации товара, дней',
            't3s_setting_callback_function4',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_record_pickup_time',
            'Период временного поднятия объявления в ТОП, минут',
            't3s_setting_callback_function5',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_record_pickup_days',
            'Период поднятия объявления в ТОП, дней',
            't3s_setting_callback_function6',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_record_pickup_cost',
            'Стоимость поднятия объявления в ТОП, грн',
            't3s_setting_callback_function7',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_merchant_id',
            'ID мерчанта в Приват-24',
            't3s_setting_callback_function8',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_merchant_pass',
            'Пароль мерчанта в Приват-24',
            't3s_setting_callback_function9',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_pay_count',
            'Количество попыток оплаты счета в Приват-24',
            't3s_setting_callback_function10',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_signup_user_notification_page_id',
            'ID страницы с текстом письма о подтверждении регистрации пользователя',
            't3s_setting_callback_function11',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_registration_user_notification_page_id',
            'ID страницы с текстом письма о завершении регистрации пользователя',
            't3s_setting_callback_function12',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_marquee_text',
            'Текст для бегущей строки',
            't3s_setting_callback_function13',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_facebook_url',
            'Адрес страницы в сети Facebook',
            't3s_setting_callback_function14',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_vk_url',
            'Адрес страницы в сети ВКонтакте',
            't3s_setting_callback_function15',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_ok_url',
            'Адрес страницы в сети Одноклассники',
            't3s_setting_callback_function16',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_google_url',
            'Адрес страницы в сети Google+',
            't3s_setting_callback_function17',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_youtube_url',
            'Адрес страницы в Youtube',
            't3s_setting_callback_function18',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_twitter_url',
            'Адрес страницы в сети Twitter',
            't3s_setting_callback_function19',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_instagram_url',
            'Адрес страницы в сети Instagram',
            't3s_setting_callback_function20',
            'general', // страница
            't3s_setting_section' // секция
    );
    
    add_settings_field(
            't3s_setting_skype_login',
            'Логин в Skype',
            't3s_setting_callback_function21',
            'general', // страница
            't3s_setting_section' // секция
    );

    // Регистрируем опции, чтобы они сохранялись при отправке 
    // $_POST параметров и чтобы callback функции опций выводили их значение.
    register_setting('general', 't3s_setting_contact_view_all');
    register_setting('general', 't3s_setting_email_callback');
    register_setting('general', 't3s_setting_email_support');
    register_setting('general', 't3s_setting_pr_publication_min_days');
    register_setting('general', 't3s_setting_record_pickup_time');
    register_setting('general', 't3s_setting_record_pickup_days');
    register_setting('general', 't3s_setting_record_pickup_cost');
    register_setting('general', 't3s_setting_merchant_id');
    register_setting('general', 't3s_setting_merchant_pass');
    register_setting('general', 't3s_setting_pay_count');
    register_setting('general', 't3s_setting_signup_user_notification_page_id');
    register_setting('general', 't3s_setting_registration_user_notification_page_id');
    register_setting('general', 't3s_setting_marquee_text');
    register_setting('general', 't3s_setting_facebook_url');
    register_setting('general', 't3s_setting_vk_url');
    register_setting('general', 't3s_setting_ok_url');
    register_setting('general', 't3s_setting_google_url');
    register_setting('general', 't3s_setting_youtube_url');
    register_setting('general', 't3s_setting_twitter_url');
    register_setting('general', 't3s_setting_instagram_url');
    register_setting('general', 't3s_setting_skype_login');
}

// ------------------------------------------------------------------
// Сallback функция для секции
// ------------------------------------------------------------------
//
// Функция срабатывает в начале секции, если не нужно вывдить 
// никакой текст или делать что-то еще до того как выводить опции, 
// то функцию можно не использовать для этого укажите '' в третьем 
// параметре add_settings_section
//
function t3s_setting_section_callback_function() {
	echo '<p>В данном блоке необходимо указать параметры для сайта T3S.biz</p>';
}

// ------------------------------------------------------------------
// Callback функции выводящие HTML код опций
// ------------------------------------------------------------------
//
// Создаем checkbox и text input теги
//
function t3s_setting_callback_function() {
	echo '<input 
		name="t3s_setting_contact_view_all" 
		type="checkbox" 
		' . checked( 1, get_option('t3s_setting_contact_view_all'), false ) . ' 
		value="1" 
		class="code" 
	/>';
}

function t3s_setting_callback_function2() {
	echo '<input 
		name="t3s_setting_email_callback"  
		type="text" 
		value="' . get_option('t3s_setting_email_callback') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function3() {
	echo '<input 
		name="t3s_setting_email_support"  
		type="text" 
		value="' . get_option('t3s_setting_email_support') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function4() {
	echo '<input 
		name="t3s_setting_pr_publication_min_days"  
		type="text" 
		value="' . get_option('t3s_setting_pr_publication_min_days') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function5() {
	echo '<input 
		name="t3s_setting_record_pickup_time"  
		type="text" 
		value="' . get_option('t3s_setting_record_pickup_time') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function6() {
	echo '<input 
		name="t3s_setting_record_pickup_days"  
		type="text" 
		value="' . get_option('t3s_setting_record_pickup_days') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function7() {
	echo '<input 
		name="t3s_setting_record_pickup_cost"  
		type="text" 
		value="' . get_option('t3s_setting_record_pickup_cost') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function8() {
	echo '<input 
		name="t3s_setting_merchant_id"  
		type="text" 
		value="' . get_option('t3s_setting_merchant_id') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function9() {
	echo '<input 
		name="t3s_setting_merchant_pass"  
		type="text" 
		value="' . get_option('t3s_setting_merchant_pass') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function10() {
	echo '<input 
		name="t3s_setting_pay_count"  
		type="text" 
		value="' . get_option('t3s_setting_pay_count') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function11() {
	echo '<input 
		name="t3s_setting_signup_user_notification_page_id"  
		type="text" 
		value="' . get_option('t3s_setting_signup_user_notification_page_id') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function12() {
	echo '<input 
		name="t3s_setting_registration_user_notification_page_id"  
		type="text" 
		value="' . get_option('t3s_setting_registration_user_notification_page_id') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function13() {
	echo '<input 
		name="t3s_setting_marquee_text"  
		type="text" 
		value="' . get_option('t3s_setting_marquee_text') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function14() {
	echo '<input 
		name="t3s_setting_facebook_url"  
		type="text" 
		value="' . get_option('t3s_setting_facebook_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function15() {
	echo '<input 
		name="t3s_setting_vk_url"  
		type="text" 
		value="' . get_option('t3s_setting_vk_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function16() {
	echo '<input 
		name="t3s_setting_ok_url"  
		type="text" 
		value="' . get_option('t3s_setting_ok_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function17() {
	echo '<input 
		name="t3s_setting_google_url"  
		type="text" 
		value="' . get_option('t3s_setting_google_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function18() {
	echo '<input 
		name="t3s_setting_youtube_url"  
		type="text" 
		value="' . get_option('t3s_setting_youtube_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function19() {
	echo '<input 
		name="t3s_setting_twitter_url"  
		type="text" 
		value="' . get_option('t3s_setting_twitter_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function20() {
	echo '<input 
		name="t3s_setting_instagram_url"  
		type="text" 
		value="' . get_option('t3s_setting_instagram_url') . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function21() {
	echo '<input 
		name="t3s_setting_skype_login"  
		type="text" 
		value="' . get_option('t3s_setting_skype_login') . '" 
		class="code2"
	 />';
}
