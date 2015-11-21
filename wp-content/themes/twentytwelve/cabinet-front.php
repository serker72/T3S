<?php
/**
 * Template Name: cabinet-front
 *
 *
 */

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <div class="reg-req" style="display: block;">
            <div class="cab-req"> 
                <div class="headerForm">
                    <h3 id="header-user"></h3>
                </div>
                <?php echo do_shortcode('[wppb-edit-profile]'); ?>

                <div id="regForm">            
                    <div class="col-1 block-reg">
                       <p class="form-passwordold">
                            <label for="pass1">Текущий пароль</label>
                            <input class="text-input" name="passold" type="password" id="passold" placeholder="Текущий пароль">
			</p>

                    </div>
                    <div class="col-2 block-reg">

                    </div>
                    <div class="col-3 block-reg">




                    </div>
                    <div class="col-4 block-reg">
                        <div class="img-avatar" >
                            <img src="/wp-content/themes/twentytwelve/images/img-avatar.png"/>
                        </div>
                        <div class="txt-avatar">
                            <input type="button" value="Изменить изображение" style="float: right" class="avatar">
                            <p style="width: auto; text-align: right">Вы можете загрузить изображение, которое в дальнейшем будет отображаться в Вашем профиле</p>
                            <span class="wppb-description-delimiter"></span>
                        </div>

                        <div style="clear: both;"></div>

                        <div id="pass-block" style="float: left;">

                        </div>
                        <div id="show_pas" style="float: left; margin-left: 0px;" class="span2 chekbox">
                                <input name="show_pass" id="show_pass" type="checkbox" value="Показать пароль" style="float: left; margin-left: 10px; margin-right: 7px;"/>
                                <label for="show_pass">Показать пароль</label>
                        </div>
                        <div class="changePassword">
                            <input type="button" value="Изменить пароль" style="float: right; margin-top: -5px; padding: 4px 40px;">
                        </div>
                    </div>
                </div>
                <div class="dopForm">
                    <div class="coll-1 block-reg">

                    </div>
                    <div class="coll-2 block-reg">

                    </div>
                    <div class="coll-3 block-reg">

                    </div>
                    <div class="coll-4 block-reg">

                    </div>
                    <div style="clear: both"></div>
                    
                </div>
                <div class="cab-ref-submit">Сохранить все изменения и выйти в кабинет пользователя</div>


                <script>
                    jQuery( document ).ready(function() {
                        jQuery('.col-1').append(jQuery('.first_name'));
                        jQuery('.col-1').append(jQuery('.form-email'));
                        jQuery('.col-1').append(jQuery('.form-username'));
                        jQuery('.col-1').append(jQuery('.form-passwordold'));
                        jQuery('.first_name input').attr('placeholder', 'Имя');
                        jQuery('.form-email input').attr('placeholder', 'Email');
                        jQuery('.form-username input').attr('placeholder', 'Придумайте логин');
                        jQuery('.col-2').append(jQuery('.last_name'));
                        jQuery('.col-2').append(jQuery('.form-input15'));
                        jQuery('.col-2').append(jQuery('.form-password'));
                        jQuery('.last_name input').attr('placeholder', 'Фамилия');
                        jQuery('.form-input15 input').attr('placeholder', 'Номера телефонов');
                        jQuery('.form-password input').attr('placeholder', 'Пароль');
                        //jQuery('.col-3 #pass-block').append( jQuery('.form-password')[1] );
                        jQuery('.col-3').append(jQuery('.form-password')[1]);
                        jQuery('.col-3 .form-password input').attr('placeholder', 'Введите пароль повторно');
                        jQuery('.dopForm').append(jQuery('.form-submit'));
                        jQuery('.dopForm').append(jQuery('.cab-ref-submit'));
                        jQuery('.user-forms').append(jQuery('#regForm'));

                        jQuery('#regForm').append(jQuery('.extraFieldHeading'));

                        jQuery('.coll-1').append(jQuery('.form-input2'));
                        jQuery('.coll-1').append(jQuery('.form-input19'));
                        jQuery('.form-input2 input').attr('placeholder', 'Код ЕДРПОУ');
                        jQuery('.form-input19 input').attr('placeholder', 'Область');
                        jQuery('.coll-2').append(jQuery('.form-input18'));
                        jQuery('.coll-2').append(jQuery('.form-input20'));
                        jQuery('.form-input18 input').attr('placeholder', 'ИНН');
                        jQuery('.form-input20 input').attr('placeholder', 'Город');
                        jQuery('.coll-3').append(jQuery('.form-input13'));
                        jQuery('.coll-3').append(jQuery('.form-input21'));
                        jQuery('.form-input13 input').attr('placeholder', 'Название');
                        jQuery('.form-input21 input').attr('placeholder', 'Улица, дом');
                        jQuery('.coll-4').append(jQuery('.form-input22'));
                        jQuery('.form-input22 input').attr('placeholder', 'Форма');
                        jQuery('.dopForm').append(jQuery('.form-submit'));
                        jQuery('.form-submit input').val('СОХРАНИТЬ ИЗМЕНЕНИЯ');
                        jQuery('#header-user').html('ДАННЫЕ ПРОФИЛЯ ПОЛЬЗОВАТЕЛЯ: ' + jQuery('.last_name input').val() + ' ' + jQuery('.first_name input').val());
                        jQuery('.username label').html('логин пользователя');
                        jQuery('.img-avatar img').attr('src', jQuery('.form-upload16 img').attr('src'));
                        jQuery('.avatar').click(function(){
                            jQuery('#avatar16').click();
                        });
                        jQuery('#avatar16').change( function(event) {
                            jQuery('.img-avatar img').attr('src', URL.createObjectURL(event.target.files[0]));
                        });
                        jQuery('.user-forms').append(jQuery('.dopForm'));
                        jQuery('#show_pas').click( function(event) {
                            if (jQuery('#show_pass').prop('checked')){
                          //  jQuery('#pass1').attr('type', 'text');
                         //  jQuery('#pass1').prop("type", "text");
                            jQuery('#pass1').get(0).type = 'text';
                            jQuery('#pass2').get(0).type = 'text';
                        }
                        if (!jQuery("#show_pass").prop('checked')){
                            jQuery('#pass1').get(0).type = 'password';
                            jQuery('#pass2').get(0).type = 'password';
                        }
                        });
                         
                    });

                </script> 

            </div>
        </div><!-- #content -->
    </div><!-- #primary -->
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>