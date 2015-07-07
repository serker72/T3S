<?php

/* 
 * Добавление блока опций на страницу General
 * Author: Sergey Kerimov
 */

// ------------------------------------------------------------------
// Вешаем все блоки, поля и опции на хук admin_init
// ------------------------------------------------------------------
//
add_action( 'admin_init', 't3s_settings_api_init' );

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
            't3s_setting_skype_login',
            'Логин Skype',
            't3s_setting_callback_function3',
            'general', // страница
            't3s_setting_section' // секция
    );

    // Регистрируем опции, чтобы они сохранялись при отправке 
    // $_POST параметров и чтобы callback функции опций выводили их значение.
    register_setting( 'general', 't3s_setting_contact_view_all' );
    register_setting( 'general', 't3s_setting_email_callback' );
    register_setting( 'general', 't3s_setting_skype_login' );
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
		' . checked( 1, get_option( 't3s_setting_contact_view_all' ), false ) . ' 
		value="1" 
		class="code" 
	/>';
}

function t3s_setting_callback_function2() {
	echo '<input 
		name="t3s_setting_email_callback"  
		type="text" 
		value="' . get_option( 't3s_setting_email_callback' ) . '" 
		class="code2"
	 />';
}

function t3s_setting_callback_function3() {
	echo '<input 
		name="t3s_setting_skype_login"  
		type="text" 
		value="' . get_option( 't3s_setting_skype_login' ) . '" 
		class="code2"
	 />';
}
