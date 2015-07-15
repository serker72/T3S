<?php
function tzs_front_end_view_shipment_handler($atts) {
	ob_start();
	
	global $wpdb;
	
	$user_id = get_current_user_id();
	
	$sh_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	
	if ($sh_id <= 0) {
		print_error('Груз не найден');
	} else {
		$sql = "SELECT * FROM ".TZS_SHIPMENT_TABLE." WHERE id=$sh_id;";
		$row = $wpdb->get_row($sql);
		if (count($row) == 0 && $wpdb->last_error != null) {
			print_error('Не удалось отобразить информацию о грузе. Свяжитесь, пожалуйста, с администрацией сайта');
		} else if ($row == null) {
			print_error('Груз не найден');
		} else {
			$type = isset($GLOBALS['tzs_tr_types'][$row->trans_type]) ? $GLOBALS['tzs_tr_types'][$row->trans_type] : "";
			?>
			<script src="/wp-content/plugins/tzs/assets/js/distance.js"></script>
                        <div class="row-fluid" id="contact-block-right" >
                <div class="span2 offset10">
                    <?php
                        echo "<img src='".get_user_meta($row->user_id, 'company_logo',true)."'/>";
                        $form_type = 'shipments';
                        echo tzs_print_user_contacts($row, $form_type);
                    ?>
                    <?php if(isset($_GET['spis'])) echo "<a id='edit_search' href='/account/my-shipments/'>Назад к списку</a> <div style='clear: both'></div>";
                        else echo "<button id='edit_search'  onclick='history.back()'>Назад к списку</button> <div style='clear: both'></div>"; ?>
            <?php if (($user_id == $row->user_id)) {?>
                <div style="margin-top: 15px;">
                    <a id="view_edit"  onClick="javascript: window.location.href = '/account/edit-shipment/?id=<?php echo $row->id;?>';">Изменить</a>
                </div>
                    
            <?php } ?>
                </div>
            </div>

<div class="container" id="product-container">
    <div class="row-fluid" >
        <div class="span4" id="img_kart">
            <div class="well well-large">
                    <div class="pull-left label-txt">
                        <label><strong>Номер груза:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->id; ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Активно:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->active == 1 ? 'Да' : 'Нет'; ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Дата размещения:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo convert_time($row->time); ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($row->last_edited != null) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Дата <!--последнего -->изменения:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo convert_time($row->last_edited); ?>
                    </div>
                    <div class="clearfix"></div>
                
                   <?php } ?>
        </div>
        </div>
        <div class="span6" id="descript">
              <div class="well well-large">
                    <div class="pull-left label-txt">
                        <label><strong>Дата погрузки:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo convert_date($row->sh_date_from); ?> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Дата выгрузки:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo convert_date($row->sh_date_to); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Пункт погрузки:</strong></label>
                    </div>
                    <div class="pull-left" id="bet_label">
                        <?php echo tzs_city_to_str($row->from_cid, $row->from_rid, $row->from_sid, $row->sh_city_from); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Пункт выгрузки:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo tzs_city_to_str($row->to_cid, $row->to_rid, $row->to_sid, $row->sh_city_to); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Описание груза:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo htmlspecialchars($row->sh_descr); ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($row->sh_weight > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Вес:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->sh_weight; ?> т
                    </div>
                    <div class="clearfix"></div>
                   <?php } ?> 
                    <?php if ($row->sh_volume > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Объем:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->sh_volume; ?> м³
                    </div>
                    <div class="clearfix"></div>
                   <?php } ?> 
                    <div class="pull-left label-txt">
                        <label><strong>Количество машин:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $row->trans_count; ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php if (strlen($type) > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Тип транспорта:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo $type; ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php }?>
                    <?php if ($row->sh_length > 0 || $row->sh_height > 0 || $row->sh_width > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Габариты:</strong></label>
                    </div>
                    <div class="pull-left">
                        Длинна=<?php echo $row->sh_length; ?>м Ширина=<?php echo $row->sh_width; ?>м Высота=<?php echo $row->sh_height; ?>м
                    </div>
                    <div class="clearfix"></div>
                    <?php }?>
                    <?php $cost=tzs_cost_to_str($row->cost); if (strlen($cost) > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Цена:</strong></label>
                    </div>
                    <div class="pull-left" style="width: 60%">
                        <?php echo $cost;?>
                    </div>
                    <div class="clearfix"></div>
                    <?php }?>
                    <?php if ($row->distance > 0) {?>
                    <div class="pull-left label-txt">
                        <label><strong>Расстояние:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo tzs_make_distance_link($row->distance, false, array($row->sh_city_from, $row->sh_city_to)); ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php }?>
                </div>
            </div>
        </div>
        
    </div>
			
			
			<script>
				function promptDelete(id) {
					jQuery('<div></div>').appendTo('body')
						.html('<div><h6>Удалить запись '+id+'?</h6></div>')
						.dialog({
							modal: true,
							title: 'Удаление',
							zIndex: 10000,
							autoOpen: true,
							width: 'auto',
							resizable: false,
							buttons: {
								'Да': function () {
									jQuery(this).dialog("close");
									doDelete(id);
								},
								'Нет': function () {
									jQuery(this).dialog("close");
								}
							},
							close: function (event, ui) {
								jQuery(this).remove();
							}
						});
				}
				function doDelete(id) {
					var data = {
						'action': 'tzs_delete_shipment',
						'id': id
					};
					
					jQuery.post(ajax_url, data, function(response) {
						if (response == '1') {
							window.open('/account/my-shipments/', '_self');
						} else {
							alert('Не удалось удалить: '+response);
						}
					});
				}
			</script>
			<?php
		}
	}
	
	$output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}
?>