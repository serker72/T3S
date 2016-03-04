<?php
/**
 * Template Name: register-front
 *
 *
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
                    <h3>РЕГИСТРАЦИЯ</h3>
                     <div class="reg-req" style="display: block;">
                         <div class="mark" style="margin-bottom: 10px; color: #007FFF">
                             <span style="color: red">* </span>- поля помеченные этим знаком обязательны к заполнению
                             <div style="color: red" class="myerror"></div>
                         </div>
                         <div class="reg-reg">
                             
                                <?php echo do_shortcode('[wppb-register]'); ?>
                            </div>

        <div id="regForm">            
                    <div class="col-1 block-reg">
                                               
                        
                    </div>
                    <div class="col-2 block-reg">
                        
                    </div>
                    <div class="col-3 block-reg">
                        
				<!-- <label for="avatar16">Логотип</label> -->
                                <div class="img-avatar form-upload17">
                                    <img src="/wp-content/themes/twentytwelve/images/img-avatar.png"/>
                                </div>
                                <div class="txt-avatar form-upload17">
                                  <!--  <input name="avatar16" id="avatar16" size="0" type="file"> -->
                                    <input type="button" value="Загрузить изображение" class="avatar">
                                    <p style="width: auto;">Вы можете загрузить изображение, которое в дальнейшем будет отображаться в Вашем профиле</p>
                                    <span class="wppb-description-delimiter"></span>
                                </div>
                        
                        <div style="clear: both;"></div>
                        
                            <div id="pass-block" style="float: left;">
                                
                            </div>
                        <div id="show_pas" style="float: left;" class="span2 chekbox">
                                <input name="show_pass" id="show_pass" type="checkbox" value="Показать пароль" style="float: left; margin-left: 10px; margin-right: 7px;"/>
                                <label for="show_pass">Показать пароль</label>
                            </div>
			
                    </div>
            <div style="clear: both"></div>
        </div>
                             <div class="ref" style="margin-bottom: 25px; margin-top: 0px;">
                                 <span style="color: red">Обратите внимание:</span> логин и пароль не могут быть короче 6-ти знаков и должны состоять ТОЛЬКО из цифр и латинских букв
                             </div>
                             <div class="ref-submit">
                                 Регистрируясь в системе и используя данный сайт, я принимаю <a href="">пользовательское соглашение</a>
                             </div>
       
        <script>
            jQuery( document ).ready(function() {
                   jQuery('.col-1').append( jQuery('.first_name') );
                   jQuery('.col-1').append( jQuery('.form-email') );
                   jQuery('.col-1').append( jQuery('.form-username') );
                   jQuery('.first_name input').attr('placeholder','Имя *');
                   jQuery('.form-email input').attr('placeholder','Email *');
                   jQuery('.form-username input').attr('placeholder','Придумайте логин *');
                   jQuery('.col-2').append( jQuery('.last_name') );
                   jQuery('.col-2').append( jQuery('.form-input15') );
                   jQuery('.col-2').append( jQuery('.form-password') );
                   jQuery('.last_name input').attr('placeholder','Фамилия *');
                   jQuery('.form-input15 input').attr('placeholder','Номера телефонов *');
                   jQuery('.form-password input').attr('placeholder','Пароль *');
                   jQuery('.col-3 #pass-block').append( jQuery('.form-password')[1] );
                    jQuery('.col-3 .form-password input').attr('placeholder','Введите пароль повторно *');
                    jQuery('#regForm').append( jQuery('.ref') );
                    jQuery('#regForm').append( jQuery('.form-submit') );
                     jQuery('.user-forms').append( jQuery('#regForm') );
                     jQuery('.img-avatar img').attr('src', jQuery('.form-upload16 img').attr('src'));
                        jQuery('.avatar').click(function(){
                            jQuery('#avatar16').click();
                        });
                        jQuery('#avatar16').change( function(event) {
                            jQuery('.img-avatar img').attr('src', URL.createObjectURL(event.target.files[0]));
                        });
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
                          
                         if ((jQuery('#first_name').val()) == undefined){
                            jQuery('#regForm').hide();
                            jQuery('.mark').hide();
                            jQuery('.ref-submit').hide();
                        } 
                        jQuery('#addusersub').get(0).type = 'button';
                        jQuery("#input15").mask("38(999) 999-9999");
                        if (jQuery('.success').html() != undefined){
                            
                            jQuery('#regForm').hide();
                            jQuery('.mark').hide();
                            jQuery('.ref-submit').hide();
                            
                        }
                      

            });
         function subm_click()
            { 
                
                var usrLen=document.getElementById('user_name').value;
                var passLen=document.getElementById('pass1').value;
                var filter  = /^([a-zA-Z0-9_\.\-])+$/;
                var str='';
                var flag = 0;
                if (filter.test(passLen)) {
                    
                    } else {
                        flag=1;
                        str=str+'<p>Пароль содержит недопустимые символы</p>';
                        
                    }
                if ((usrLen.length) < 6) {
                    flag=1;
                    str=str+'<p>Длинна логина меньше 6 символов</p>';
                    
                }
                if ((passLen.length) < 6) {
                    flag=1;
                    str=str+'<p>Длинна пароля меньше 6 символов</p>';
                    
                }
                if (flag == 1) {
                    //alert(str);
                    jQuery('.myerror').html(str);
                    return false;
                }
                if (flag == 0) {
                document.forms["adduser"].submit();
            }
            }  
        </script>
            
                         </div>
		</div><!-- #content -->
	</div><!-- #primary -->
        </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>