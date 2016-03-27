                <!-- Modal -->
                <div id="RecordVipPickUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button id="RecordVipPickUpModalCloseButton" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Платное поднятие объявления в ТОП</h3>
                    </div>
                    <div class="modal-body">
                        <h4>Создать счет на оплату услуг добавления заявки в ТОП ?</h4>
                        <form id="RecordVipPickUpForm" method="post" action="" class="pr_edit_form">
                            <div class="pr_edit_form_line">
                                <label for="order_tbl_type">Префикс таблицы</label>
                                <input type="text" id="order_tbl_type" name="order_tbl_type" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="order_tbl_id">ID заявки</label>
                                <input type="text" id="order_tbl_id" name="order_tbl_id" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="order_cost">Стоимость услуги, грн</label>
                                <input type="text" id="order_cost" name="order_cost" value="<?php echo get_option('t3s_setting_record_pickup_cost');?>" disabled="disabled">
                            </div>
                        </form>
                        <div id="RecordVipPickUpInfo"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button id="RecordVipPickUpSubmit" class="btn btn-primary" onClick="doVipPickUp();">Создать счет</button>
                    </div>
                </div>

            <script>
                function promptVipPickUp(id, table_prefix) {
                    jQuery("#order_tbl_type").attr('value', table_prefix);
                    jQuery("#order_tbl_id").attr('value', id);
                    jQuery("#RecordVipPickUpModal").modal('show');
                }
                
                function doVipPickUp() {
                    jQuery("#RecordVipPickUpSubmit").attr('disabled', 'disabled');
                    jQuery('#RecordVipPickUpInfo').html('Подождите, идет формирование счета на оплату...');
                    //fd = jQuery('#RecordVipPickUpModal form#RecordVipPickUpForm').serialize();
                    fd = 'order_tbl_type=' + jQuery("#order_tbl_type").val() + '&order_tbl_id=' + jQuery("#order_tbl_id").val();
                    jQuery.ajax({
                        url: "/wp-admin/admin-ajax.php?action=tzs_order_add",
                        type: "POST",
                        data: fd,
                        dataType: 'json',
                        success: function(data) {
                            if ((data.output_error !== 'undefined') && (data.output_error !== '')) {
                                jQuery('#RecordVipPickUpInfo').html(data.output_error);
                            }
                            if ((data.order_id !== 'undefined') && (data.order_id !== '')) {
                                location.href = "<?php echo get_site_url(); ?>/account/view-order/?id=" + data.order_id + "&spis=new";
                            }
                        },
                        error: function(data) {
                            if (data.responseText !== 'undefined') {
                                jQuery('#RecordVipPickUpInfo').html(data.responseText);
                            }
                        }			
                    });
                    
                }
            </script>
