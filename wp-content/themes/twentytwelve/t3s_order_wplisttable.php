<?php

/* 
 * Вывод списка счетов в админке
 * Author: Sergey Kerimov
 */

//Наш класс расширяет возможности класса WP_List_Table, поэтому мы должны убедиться, что родитель существует
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// Создаем класс, потомком которого выступает WP_List_Table
class Orders_List_Table extends WP_List_Table {
    var $order_status = 0;
    var $order_pay_method = array();

    /**
     * Переопределяем родительский конструктор,
     * чтобы передать наши собственные аргументы
     */
     function __construct($p_status, $p_pay_method) {
        $this->order_status = $p_status;
        $this->order_pay_method = $p_pay_method;
        
        parent::__construct( array(
            'singular'=> 'wp_list_text_order', //имя одной записи в единственном числе
            'plural' => 'wp_list_text_orders', //имя списка записей во множественном числе
            'ajax' => false //Не поддерживать Ajax для таблицы
        ) );
     }

    /**
     * Добавим дополнительную разметку в панели инструментов до и после таблицы
     * $which - параметр имеющий тип данных string,
     * позволяющий определить куда добавлять разметку:
     * до таблицы или после нее,
     * может принимать значения: top или bottom
     */
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
         //Код добавляет разметку до таблицы
            echo "Список неоплаченных счетов";
        }
        
        if ( $which == "bottom" ){
        //Код добавляет разметку после таблицы
            echo "Список неоплаченных счетов";
            
            //if ($this->order_status == 0) {
            ?>
        <!-- Modal -->
        <div id="OrderPayModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button id="OrderPayModalCloseButton" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Ручной перевод счета в оплаченные</h3>
            </div>
            <div class="modal-body">
                <div id="OrderPayCaption"><h4>Укажите дату и время оплаты счета:</h4></div>
                <form id="OrderPayForm" method="post" action="" class="pr_edit_form">
                    <div class="pr_edit_form_line">
                        <label for="order_id">ID заявки</label>
                        <input type="text" id="order_id" name="order_id" value="" disabled="disabled">
                    </div>
                    <div class="pr_edit_form_line">
                        <label for="order_number">Номер счета</label>
                        <input type="text" id="order_number" name="order_number" value="" disabled="disabled">
                    </div>
                    <div class="pr_edit_form_line">
                        <label for="order_dt_pay">Дата и время оплаты</label>
                        <input type="text" id="order_dt_pay" name="order_dt_pay" value="">
                    </div>
                    <input type="hidden" id="order_status" name="order_status" value=""/>
                </form>
                <div id="OrderPayInfo"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button id="OrderPaySubmit" class="btn btn-primary" onClick="JavaScript:doOrderPay();">Сохранить</button>
            </div>
        </div>
            <?php
            //}
        }
    }

    /**
     * Определям столбцы, которые будут использоваться в нашей таблице
     * функция возвращает массив столбцов используемых в таблице $columns
     */
    function get_columns() {
        return $columns = array(
            'col_order_id' => 'ID',
            'col_order_number' => 'Номер счета',
            'col_order_dt_create' => 'Дата создания',
            //'col_order_tbl_type' => 'Рубрика',
            //'col_order_tbl_id' => 'ID заявки',
            'col_order_dt_pay' => 'Дата оплаты',
            'col_order_cost' => 'Сумма',
            'col_order_user_login' => 'Пользователь',
            'col_order_pay' => 'Действие',
        );
    }

    /**
     * Определяем какие столбцы будут иметь функцию сортировки
     * возвращает массив столбцов ($sortable) по которым можно сортировать
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_order_id' => array('id', false),
            'col_order_user_login' => array('user_login', false),
            'col_order_number' => array('number', false),
            'col_order_dt_create' => array('dt_create', false),
            'col_order_dt_pay' => array('dt_pay', false),
            //'col_order_tbl_type' => array('tbl_type', false),
            //'col_order_tbl_id' => array('tbl_id', false),
        );
    }

    /**
     * Подготовка таблицы с различными параметрами, нумерация страниц (пагинация), столбцы  и элементы таблицы
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
        $opm = implode(',', $this->order_pay_method);

        /* -- Подготавливаем запрос к БД -- */
        $query  = "SELECT a.*, b.user_login FROM ".TZS_ORDERS_TABLE." a, ".$wpdb->prefix."users b";
        $query .= " WHERE a.status=".$this->order_status." AND b.id=a.user_id";
        $query .= " AND pay_method IN (".$opm.")";

        /* -- Упорядочение параметров -- */
        //Параметры, которые будут использоваться для упорядочения результата
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';

        if(!empty($orderby) & !empty($order)) { 
            $query .= ' ORDER BY '.$orderby.' '.$order;
        }

        /* -- Параметры для нумерации страниц -- */
        //Количество элементов таблицы?
        //возвращает общее количество задействованных строк
        $totalitems = $wpdb->query($query);

        //Сколько строк таблицы показывать на одной странице?
        $perpage = 10;

        //На какой мы странице?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';

        //Номер страницы?
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ) { 
            $paged = 1;
        }

        //Сколько страниц у нас получилось в итоге?  
        $totalpages = ceil($totalitems/$perpage);

        //настроим запрос принимая во внимание нумерацию  
        if(!empty($paged) && !empty($perpage)) {
            $offset = ($paged-1)*$perpage;  
            $query .= ' LIMIT '.(int)$offset.','.(int)$perpage;
        } 

        /* -- Регистрируем нумерацию -- */  
        $this->set_pagination_args(
            array(
                "total_items" => $totalitems,
                "total_pages" => $totalpages,
                "per_page" => $perpage,
            )
        );

        //Ссылки на страницы автоматически будут созданы в соответствии с параметрами выше

        /* -- Регистрируем колонки -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Выборка элементов -- */
        $this->items = $wpdb->get_results($query);
    }

    /**
     * Отображает строки таблицы
     * возвращает строку содержащую разметку содержимого таблицы
     */
    function display_rows() {
        //Получаем записи зарегистрированные в методе prepare_items
        $records = $this->items;

        //Получаем колонки зарегистрированные в методах get_columns и get_sortable_columns
        list( $columns, $hidden ) = $this->get_column_info();

        //Запускаем цикл по всем записям
        if(!empty($records)) {
            foreach($records as $rec) {
                //Открываем строку
                echo '<tr id="record_'.$rec->id.'">';
                // Цикл по колонкам
                foreach ( $columns as $column_name => $column_display_name ) {
                    //Применяем стили к каждой колонке
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden)) {
                        $style = 'style="display:none;"';
                    }
                    $attributes = $class . $style;

                    //ссылка для редактирования
                    //$editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->link_id;

                    //Отображаем ячейку
                    switch ( $column_name ) {
                        case "col_order_id": echo '<td '.$attributes.'>'.stripslashes($rec->id).'</td>';   break;
                        case "col_order_user_login": echo '<td '.$attributes.'>'.stripslashes($rec->user_login).'</td>';   break;
                        case "col_order_number": echo '<td '.$attributes.'>'.stripslashes($rec->number).'</td>';   break;
                        case "col_order_dt_create": echo '<td '.$attributes.'>'.convert_time($rec->dt_create).'</td>';   break;
                        //case "col_order_tbl_type": echo '<td '.$attributes.'>'.stripslashes($rec->tbl_type).'</td>';   break;
                        //case "col_order_tbl_id": echo '<td '.$attributes.'>'.stripslashes($rec->tbl_id).'</td>';   break;
                        case "col_order_dt_pay": echo '<td '.$attributes.'>'.($rec->dt_pay ? convert_time($rec->dt_pay) : '').'</td>';   break;
                        case "col_order_cost": echo '<td '.$attributes.'>'.stripslashes($rec->cost).' '.$GLOBALS['tzs_curr'][$rec->currency].'</td>';   break;
                        case "col_order_pay": echo '<td '.$attributes.'>'.($rec->status == 0 ? '<a href="JavaScript:promptOrderPay('.$rec->id.', \''.$rec->number.'\')">Оплатить</a>' : ($rec->pay_method == 4 ? '<a href="javascript:promptOrderUnPay('.$rec->id.', \''.$rec->number.'\')">Отменить</a>' : '')).'</td>';   break;
                    }
                }

                //Закрываем строку
                echo'</tr>';
            }
        }
    }

}

/** ************************ Регистрация тестовой страницы *****************************/
function t3s_add_menu_items(){
    add_menu_page('Plugin Orders List Table', 'Список счетов', 'activate_plugins', 't3s_new_order_list_page', 't3s_render_new_order_list_page');
    $page1 = add_submenu_page('t3s_new_order_list_page', 'Список неоплаченных счетов', 'Неоплаченные', 'activate_plugins', 't3s_new_order_list_page', 't3s_render_new_order_list_page');
    $page2 = add_submenu_page('t3s_new_order_list_page', 'Список оплаченных счетов', 'Оплаченные', 'activate_plugins', 't3s_pay_order_list_page', 't3s_render_pay_order_list_page');
    
    add_action('admin_print_scripts-'.$page1, 't3s_order_list_page_header');
    add_action('admin_print_scripts-'.$page2, 't3s_order_list_page_header');
    
    add_action('admin_footer-'.$page1, 't3s_order_list_page_footer');
    add_action('admin_footer-'.$page2, 't3s_order_list_page_footer');
} 

add_action('admin_menu', 't3s_add_menu_items');


/***************************** Отрисовка таблицы на странице ********************************/
function t3s_render_new_order_list_page() {
    //Инициализация класса и заполнение таблицы в классе полями
    $wp_list_table = new Orders_List_Table(0, array(0));
    $wp_list_table->prepare_items();
    
    // Вывод таблицы с элементами
    $wp_list_table->display();
}

function t3s_render_pay_order_list_page() {
    //Инициализация класса и заполнение таблицы в классе полями
    $wp_list_table = new Orders_List_Table(1, array(4));
    $wp_list_table->prepare_items();
    
    // Вывод таблицы с элементами
    $wp_list_table->display();
}

function t3s_order_list_page_header() {
    wp_enqueue_script('jquery',
        get_bloginfo('template_url') . '/js/jquery.js',
        array('jquery')
    );
    
    wp_enqueue_script("bootstrap",
        get_bloginfo('template_url') . '/js/bootstrap.min.js',
        array('jquery')
    );
    wp_enqueue_script(
        "jquery-ui",
        get_bloginfo('template_url') . '/js/jquery-ui.min.js',
        array('jquery')
    );
    wp_enqueue_script(
        "jquery_timepicker",
        get_bloginfo('template_url') . '/js/jquery_timepicker.js',
        array('jquery')
    );
    /*wp_enqueue_script(
        "jquery.ui.datepicker-ru",
        get_bloginfo('template_url') . '/js/jquery.ui.datepicker-ru.js',
        array('jquery')
    );*/
    /*wp_enqueue_script(
            'bootstrap-datetimepicker',
            get_bloginfo('template_url') . '/js/bootstrap-datetimepicker.min.js',
            array('jquery')
    );*/
    /*wp_enqueue_script(
        'jquery-ui-timepicker-addon',
        get_bloginfo('template_url') . '/js/jquery-ui-timepicker-addon.js',
        array('jquery')
    );*/
    
    ?>
        <link href="/wp-content/themes/twentytwelve/css/bootstrap.min.css" rel="stylesheet">
        <link href="/wp-content/themes/twentytwelve/css/jquery-ui-1.8.16.custom.css" rel="stylesheet">
        <!--link href="/wp-content/themes/twentytwelve/css/jquery-ui-timepicker-addon.css" rel="stylesheet"-->
        
  	<!--script src="/wp-content/themes/twentytwelve/js/jquery.js"></script>
	<script src="/wp-content/themes/twentytwelve/js/jquery.ui.datepicker-ru.js"></script>
        <script src="/wp-content/themes/twentytwelve/js/bootstrap.min.js"></script-->
    <?php
}
//add_action('in_admin_header', 't3s_order_list_page_header');
//add_action('admin_print_scripts-toplevel_page_t3s_new_order_list_page', 't3s_order_list_page_header');
//add_action('admin_print_scripts', 't3s_order_list_page_header');

function t3s_order_list_page_footer() {
    //if (!empty($_GET["page"]) && ($_GET["page"] === 't3s_order_list_page')) {
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($){
                //jQuery.datetimepicker.setDefaults(jQuery.datepicker.regional['ru']);
                jQuery("#OrderPaySubmit").removeAttr('disabled');
                jQuery( "#order_dt_pay" ).datetimepicker({
                    closeText: 'Закрыть',
                    prevText: '&#x3c;Пред',
                    nextText: 'След&#x3e;',
                    currentText: 'Сегодня',
                    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                    'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                    'Июл','Авг','Сен','Окт','Ноя','Дек'],
                    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
                    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
                    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                    weekHeader: 'Не',
                    dateFormat: "dd.mm.yy",
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: true,
                    yearSuffix: '',                
                    amNames: ['AM', ''],
                    pmNames: ['PM', ''],
                    timeFormat: 'HH:mm',
                    timeSuffix: '',
                    timeOnlyTitle: 'Укажите время',
                    timeText: 'Время',
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    secondText: 'Секунды',
                    millisecText: 'Миллисекунды',
                    timezoneText: 'Часовой пояс',                     
                    showSecond: true 
                });
            });
            
            function promptOrderPay(order_id, order_number) {
                jQuery("#OrderPayCaption").html('<h4>Укажите дату и время оплаты счета:</h4>');
                jQuery("#order_status").attr('value', '1');
                jQuery("#order_id").attr('value', order_id);
                jQuery("#order_number").attr('value', order_number);
                jQuery("#order_dt_pay").removeAttr('disabled');
                jQuery("#OrderPaySubmit").removeAttr('disabled');
                jQuery("#OrderPayModal").modal('show');
            }
            
            function promptOrderUnPay(order_id, order_number) {
                jQuery("#OrderPayCaption").html('<h4>Вернуть счет в неоплаченные:</h4>');
                jQuery("#order_status").attr('value', '0');
                jQuery("#order_id").attr('value', order_id);
                jQuery("#order_number").attr('value', order_number);
                jQuery("#order_dt_pay").attr('disabled', 'disabled');
                jQuery("#OrderPaySubmit").removeAttr('disabled');
                jQuery("#OrderPayModal").modal('show');
            }
            
            function doOrderPay() {
                jQuery("#OrderPaySubmit").attr('disabled', 'disabled');
                jQuery('#OrderPayInfo').html('Подождите, идет обновление счета на оплату...');
                fd = 'order_id=' + jQuery("#order_id").val() + '&order_dt_pay=' + jQuery("#order_dt_pay").val() + '&order_status=' + jQuery("#order_status").val();
                jQuery.ajax({
                    url: "/wp-admin/admin-ajax.php?action=tzs_order_hand_pay",
                    type: "POST",
                    data: fd,
                    dataType: 'json',
                    success: function(data) {
                        if ((data.output_error !== 'undefined') && (data.output_error !== '')) {
                            jQuery('#OrderPayInfo').html(data.output_error);
                        }
                        if ((data.order_id !== 'undefined') && (data.order_id !== '')) {
                            location.reload();
                        }
                    },
                    error: function(data) {
                        if (data.responseText !== 'undefined') {
                            jQuery('#OrderPayInfo').html(data.responseText);
                        }
                    }			
                });
            }
        </script>
        <?php
    //}
}

//add_action('admin_print_scripts', 't3s_order_list_page_footer');
//add_action('admin_footer', 't3s_order_list_page_footer');
//add_action('admin_footer-toplevel_page_t3s_new_order_list_page', 't3s_order_list_page_footer');
//add_action('admin_footer-список-счетов_page_t3s_new_order_list_page', 't3s_order_list_page_footer');
//add_action('admin_footer--d1-81-d0-bf-d0-b8-d1-81-d0-be-d0-ba--d1-81-d1-87-d0-b5-d1-82-d0-be-d0-b2_page_t3s_new_order_list_page', 't3s_order_list_page_footer');
