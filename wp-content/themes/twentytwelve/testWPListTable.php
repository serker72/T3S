<?php
/*
Plugin Name: List Table
Description: Демонстрационный плагин
Version: 1.0
Author: Batek
*/

//Наш класс расширяет возможности класса WP_List_Table, поэтому мы должны убедиться, что родитель существует
if(!class_exists('WP_List_Table')){
 require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Links_List_Table extends WP_List_Table {
 
/**
 * Переопределяем родительский конструктор,
 * чтобы передать наши собственные аргументы
*/
 function __construct() {
 parent::__construct( array(
 'singular'=> 'wp_list_text_link', //имя одной записи в единственном числе
 'plural' => 'wp_list_text_links', //имя списка записей во множественном числе
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
 
 echo "Привет, я нахожусь до таблицы";
 }
 if ( $which == "bottom" ){
 //Код добавляет разметку после таблицы
 
echo "Привет, я нахожусь после таблицы";
 }
}

/**
 * Определям столбцы, которые будут использоваться в нашей таблице
 * функция возвращает массив столбцов используемых в таблице $columns
 */
function get_columns() {
 return $columns= array(
 'col_link_id'=>__('ID'),
 'col_link_name'=>__('Name'),
 'col_link_url'=>__('Url'),
 'col_link_description'=>__('Description'),
 'col_link_visible'=>__('Visible')
 );
}


/**
 * Определяем какиестолбцы будут иметь функцию сортировки
 * возвращает массив столбцов ($sortable) по которым можно сортировать
 */
public function get_sortable_columns() {
    return $sortable = array(
        'col_link_id'=>array('link_id', false),
        'col_link_name'=>array('link_name', false),
        'col_link_visible'=>array('link_visible', false)
    );
}


/**
 * Подготовка таблицы с различными параметрами, нумерация страниц (пагинация), столбцы  и элементы таблицы
 */
function prepare_items() {
 global $wpdb, $_wp_column_headers;
 $screen = get_current_screen();
 
/* -- Подготавливаем запрос к БД -- */
 $query = "SELECT * FROM $wpdb->links";
 
/* -- Упорядочение параметров -- */
 //Параметры, которые будут использоваться для упорядочения результата
 $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
 $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
 if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
 
/* -- Параметры для нумерации страниц -- */
 //Количество элементов таблицы?
 $totalitems = $wpdb->query($query); //возвращает общее количество задействованных строк
 //Сколько строк таблицы показывать на одной странице?
 $perpage = 5;
 //На какой мы странице?
 $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
 //Номер страницы?
 if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }  //Сколько страниц у нас получилось в итоге?  
 $totalpages = ceil($totalitems/$perpage);  //настроим запрос принимая во внимание нумерацию  
 if(!empty($paged) && !empty($perpage)){  $offset=($paged-1)*$perpage;  
 $query.=' LIMIT '.(int)$offset.','.(int)$perpage;  } 
 /* -- Регистрируем нумерацию -- */  
 $this->set_pagination_args( array(
 "total_items" => $totalitems,
 "total_pages" => $totalpages,
 "per_page" => $perpage,
 ) );
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
    if(!empty($records)){foreach($records as $rec){
 
        //Открываем строку
        echo '<tr id="record_'.$rec->link_id.'">';
        foreach ( $columns as $column_name => $column_display_name ) {
 
            //Применяем стили к каждой колонке
            $class = "class='$column_name column-$column_name'";
            $style = "";
            if ( in_array( $column_name, $hidden ) ) $style = 'style="display:none;"';
            $attributes = $class . $style;
 
            //ссылка для редактирования
            $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->link_id;
 
            //Отображаем ячейку
            switch ( $column_name ) {
                case "col_link_id": echo '<td '.$attributes.'>'.stripslashes($rec->link_id).'</td>';   break;
                case "col_link_name": echo '<td '.$attributes.'><strong><a title="Edit" href="'.$editlink.'">'.stripslashes($rec->link_name).'</a></strong></td>'; break;
                case "col_link_url": echo '<td '.$attributes.'>'.stripslashes($rec->link_url).'</td>'; break;
                case "col_link_description": echo '<td '.$attributes.'>'.$rec->link_description.'</td>'; break;
                case "col_link_visible": echo '<td '.$attributes.'>'.$rec->link_visible.'</td>'; break;
            }
        }
 
        //Закрываем строку
        echo'</tr>';
    }}
}

}

/** ************************ Регистрация тестовой страницы *****************************/
function tt_add_menu_items(){
    add_menu_page('Example Plugin List Table', 'Table', 'activate_plugins', 'tt_list_test', 'tt_render_list_page');
} 

add_action('admin_menu', 'tt_add_menu_items');


/***************************** Отрисовка таблицы на странице ********************************/
function tt_render_list_page(){
    
    //Инициализация класса и заполнение таблицы в классе полями
	$wp_list_table = new Links_List_Table();
	$wp_list_table->prepare_items();
	// Вывод таблицы с элементами
	$wp_list_table->display();
    }