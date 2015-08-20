<?php
function tzs_front_end_view_order_handler($atts) {
	ob_start();
	
	global $wpdb;
	
	$user_id = get_current_user_id();
	
	$order_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ($order_id <= 0) {
		print_error('Счет не найден');
	} else {
		$sql = "SELECT * FROM ".TZS_ORDERS_TABLE." WHERE id=$order_id;";
		$row = $wpdb->get_row($sql);
		if (count($row) == 0 && $wpdb->last_error != null) {
			print_error('Не удалось отобразить информацию о счете. Свяжитесь, пожалуйста, с администрацией сайта.');
		} else if ($row == null) {
			print_error('Счет не найден');
		} else {
                    if ($row->tbl_type === 'SH') {
                        $tbl_type_txt = 'Товар';
                        $tbl_type_link = 'shipment';
                    } else if ($row->tbl_type === 'TR') {
                        $tbl_type_txt = 'Транспорт';
                        $tbl_type_link = 'truck';
                    } else {
                        $tbl_type_txt = 'Торговля';
                        $tbl_type_link = 'product';
                    }
                    
                    ?>
            <div class="row-fluid" id="contact-block-right" >
                <div class="span2 offset10">
                    <?php 
                    if(isset($_GET['spis'])) {
                        echo "<a id='edit_search' href='/account/my-".$tbl_type_link."s/'>Назад к списку</a> <div style='clear: both'></div>";
                    } else {
                        echo "<button id='edit_search'  onclick='history.back()'>Назад к списку</button> <div style='clear: both'></div>";
                    }
                    
                    if ($row->status == 0) {?>
                        <div style="margin-top: 15px;">
                            <a id="view_edit"  onClick="javascript: window.location.href = '/account/pay-order/?id=<?php echo $row->id;?>';">Оплатить</a>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>

<div class="container" id="product-container">
            
    <div class="row-fluid" >
        <div class="span4" id="img_kart">
            <ul class="thumbnails"  style="max-height: 470px;">
                <li class="span12">
                    <a href="#" class="thumbnail" id="general-img">
                        <img id="general" src="/wp-content/plugins/tzs/assets/images/stack_of_coins.jpg" alt="">
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="span6" id="descript">
            <div class="well well-large">
                <div id="labeltxt">
                    <div class="pull-left label-txt">
                        <label><strong>ID счета:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->id; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Статус счета:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->status == 0 ? 'Неоплаченный' : ($row->status == 1 ? 'Действующий' : 'Архивный'); ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Рубрика объявления:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $tbl_type_txt; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>ID объявления:</strong></label>
                    </div>
                    <div class="pull-left">
                        <a href="/account/view-<?php echo $tbl_type_link; ?>/?id=<?php echo $row->tbl_id; ?>"><?php echo $row->tbl_id; ?></a>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Номер счета:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->number; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Сумма счета:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->cost." ".$GLOBALS['tzs_curr'][$row->currency]; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Дата создания:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->dt_create ? convert_time($row->dt_create) : ''; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Дата оплаты:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->dt_pay ? convert_time($row->dt_pay) : ''; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="pull-left label-txt">
                        <label><strong>Дата окончания:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->dt_expired ? convert_date($row->dt_expired) : ''; ?>
                    </div>
                    <div class="clearfix"></div>
                    
                </div>
            </div>
        </div>
        <div class="span2" id="left-control">
            
                            
        </div>
    </div>
   
</div>
			<?php
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}
?>