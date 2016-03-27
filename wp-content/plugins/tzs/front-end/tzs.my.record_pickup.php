                <!-- Modal -->
                <div id="RecordPickUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button id="RecordPickUpModalCloseButton" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Бесплатное поднятие объявления в ТОП</h3>
                    </div>
                    <div class="modal-body">
                        <h4>Добавить бесплатно заявку в ТОП на время ?</h4>
                        <form id="RecordPickUpForm" method="post" action="" class="pr_edit_form">
                            <div class="pr_edit_form_line">
                                <label for="pickup_tbl_type">Префикс таблицы</label>
                                <input type="text" id="pickup_tbl_type" name="pickup_tbl_type" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="pickup_tbl_id">ID заявки</label>
                                <input type="text" id="pickup_tbl_id" name="pickup_tbl_id" value="" disabled="disabled">
                            </div>
                            <div class="pr_edit_form_line">
                                <label for="pickup_time">Период поднятия объявления, минут</label>
                                <input type="text" id="pickup_time" name="pickup_time" value="<?php echo get_option('t3s_setting_record_pickup_time', '30');?>" disabled="disabled">
                            </div>
                        </form>
                        <div id="RecordPickUpInfo"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button id="RecordPickUpSubmit" class="btn btn-primary" onClick="doPickUp();">Поднять заявку</button>
                    </div>
                </div>

            <script>
                function promptPickUp(id, table_prefix) {
                    jQuery("#pickup_tbl_type").attr('value', table_prefix);
                    jQuery("#pickup_tbl_id").attr('value', id);
                    jQuery("#RecordPickUpModal").modal('show');
                }
                
                function doPickUp() {
                    jQuery("#RecordPickUpSubmit").attr('disabled', 'disabled');
                    jQuery('#RecordPickUpInfo').html('Подождите, идет добавление заявки в ТОП...');
                    //fd = jQuery('#RecordPickUpModal form#RecordPickUpForm').serialize();
                    fd = 'pickup_tbl_type=' + jQuery("#pickup_tbl_type").val() + '&pickup_tbl_id=' + jQuery("#pickup_tbl_id").val();
                    jQuery.ajax({
                        url: "/wp-admin/admin-ajax.php?action=tzs_record_pickup",
                        type: "POST",
                        data: fd,
                        dataType: 'json',
                        success: function(data) {
                            if ((data.output_error !== 'undefined') && (data.output_error !== '')) {
                                jQuery('#RecordPickUpInfo').html(data.output_error);
                            }
                            if ((data.ret_flag !== 'undefined') && (data.ret_flag !== '0')) {
                                location.reload();
                            }
                        },
                        error: function(data) {
                            if (data.responseText !== 'undefined') {
                                jQuery('#RecordPickUpInfo').html(data.responseText);
                            }
                        }			
                    });
                    
                }
            </script>
