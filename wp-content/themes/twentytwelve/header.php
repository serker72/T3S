<?php session_start(); ?>
<!DOCTYPE html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
        <link href="/wp-content/themes/twentytwelve/css/bootstrap.min.css" rel="stylesheet">
        <!--link href="/wp-content/themes/twentytwelve/css/dcslick.css" rel="stylesheet" type="text/css"/-->
	<?php wp_head(); ?>
	<!--script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
	<script src="https://jquery-ui.googlecode.com/svn-history/r3982/trunk/ui/i18n/jquery.ui.datepicker-ru.js"></script-->
	<script src="/wp-content/themes/twentytwelve/js/jquery-ui.min.js"></script>
	<script src="/wp-content/themes/twentytwelve/js/jquery.ui.datepicker-ru.js"></script>
	<link rel="stylesheet" href="/ui/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../info/tooltip.css"/>
        <script src="/wp-content/themes/twentytwelve/js/bootstrap.min.js"></script>
        <!--script src="/wp-content/themes/twentytwelve/js/jquery.slick.js" type="text/javascript"></script-->
	<script>
		var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                
                <?php 
                    $user_id = get_current_user_id();
                    if ($user_id == 0) {
                        echo 'var UserContacInfo = [];'."\n";
                    } else {
                        $user_info = tzs_get_user_meta($user_id);
                        $meta = explode(';', $user_info['telephone']);
                        echo 'var UserContacInfo = ["'.$user_info['fio'].'", "'.$user_info['user_email'].'", "'.$meta[0].'"];'."\n";
                    }
                ?>
                function onChatButtonClick() {
                    jivo_api.open();
                    
                    if (UserContacInfo.length > 0) {
                        jivo_api.setContactInfo(
                             {
                                name : UserContacInfo[0],
                                email : UserContacInfo[1],
                                phone : UserContacInfo[2]
                             }
                         );                        
                    }
                }
                // Вызывается при открытии окна диалога JivoSite
                function jivo_onOpen() { 
                    if (UserContacInfo.length > 0) {
                        jivo_api.setContactInfo(
                             {
                                name : UserContacInfo[0],
                                email : UserContacInfo[1],
                                phone : UserContacInfo[2]
                             }
                         );                        
                    }
                    
                }
                
            <?php if (!isset($_SESSION["timezone_offset"])) { ?>
                // Функция, отрабатывающая после готовности HTML-документа
                jQuery(document).ready(function(){
                //jQuery("#masthead").load(function(){
                    var vDate = new Date();
                    //var vTimezone = vDate.getTimezone;
                    var vTimezoneOffset = -vDate.getTimezoneOffset()/60;
                    
                    jQuery.ajax({
                            url: "/wp-admin/admin-ajax.php?action=tzs_timezone_offset_session_set",
                            type: "POST",
                            data: "timezone_offset="+vTimezoneOffset,
                            success: function(data){
                                //alert(data);
                            },
                            error: function(data){
                                if (data.responseText !== 'undefined') {
                                    alert('Ошибка записи TimezoneOffset: ' + data.responseText);
                                }
                            }			
                    });
                })
            <?php } ?>
                
                function HeaderClockUpdate() {
                    var newDate = new Date();
                    var v_day = newDate.getDate();
                    var v_month = newDate.getMonth();
                    var v_year = newDate.getFullYear();
                    var v_hour = newDate.getHours();
                    var v_minute = newDate.getMinutes();
                    
                    str_day = (v_day < 10 ? "0" : "") + v_day;
                    str_month = (v_month < 10 ? "0" : "") + v_month;
                    str_hour = (v_hour < 10 ? "0" : "") + v_hour;
                    str_minute = (v_minute < 10 ? "0" : "") + v_minute;
                    
                    var str_date = str_day + '.' + str_month + '.' + v_year;
                    
                    if (jQuery("#clock-date").html() != str_date) jQuery("#clock-date").html(str_date);
                    if (jQuery("#clock-hour").html() != str_hour) jQuery("#clock-hour").html(str_hour);
                    if (jQuery("#clock-minute").html() != str_minute) jQuery("#clock-minute").html(str_minute);
                }
	</script>
</head>
<body <?php body_class(); ?>>
<header id="masthead" class="site-header" role="banner">
<div style="clear: both;"></div>
	<div id="header">
		<?php if (!is_front_page()) {?>
			<div id="logo_head" style="float: left;">
                		<a href="/"><img src="/wp-content/themes/twentytwelve/images/logo_footer_3.png"></a>    
            		</div>
		<?php } ?>
		<div id="time">
			<!--script type="text/javascript">
				var l = new Date();
				document.write (l.toLocaleString());
			</script-->
                        <div id="clock">
                            <ul>
                                <li id="clock-date"></li>
                                <li>,</li>
                                <li id="clock-hour"></li>
                                <li>:</li>
                                <li id="clock-minute"></li>
                            </ul>
                        </div>
		</div>
		<div id="lang">
			<a href="#"><!-- Русский --></a>
		</div>
		<div id="chat">
                    <a class="btn-chat" href="javascript:onChatButtonClick();">он-лайн помощник</a>
		</div>
                <div id="tel" >
                    <a href="#modal" role="button" class="btn-tel" data-toggle="modal">Заказать звонок</a>
                    <!--a href="skype:<?php //echo get_option( 't3s_setting_skype_login' ); ?>"><img src="/wp-content/themes/twentytwelve/images/skype.png"/><?php //echo "  ".get_option( 't3s_setting_skype_login' ); ?></a-->
                </div>
		<?php if (get_current_user_id() == 0) {?>
		<div id="registr">
			<a href="/account/registration/">Регистрация</a>
		</div>
		<div id="login">
			<a href="/account/login">Войти в систему</a>
		</div>
		<?php } else {?>
		<div id="profile">
			<a href="/account/profile">Личный кабинет</a>
		</div>
		<?php }?>
        <?php if (get_current_user_id() != 0) {
            $tel = $user_info['telephone'];?>
            <input type="hidden" value="<?php echo $tel; ?>" id="tel-user" />
        <?php }?>
                


<?php if (is_front_page()) {?>
<div style="clear: both;"></div>
	 <div id="sliders">
		<div id="logo-front" style="float: left;">
			<a href="/"><img src="/wp-content/themes/twentytwelve/images/logo_footer_3.png"></a>
		</div>
		<div id="front-image" style="float: left;">
                	<a href="/"><img src="/wp-content/themes/twentytwelve/images/image-front.png"></a>
		</div>
		<div style="clear: both;"></div>
		<div id="front-text">
			<p style="font-size: 35px; text-align: center;">НАЦИОНАЛЬНАЯ ТОРГОВАЯ ТОВАРО-ТРАНСПОРТНАЯ СИСТЕМА Т3С</p>
		</div>
            </div>
<?php } ?>


        <?php if (!dynamic_sidebar("Тендеры и Товары") ) : ?>
        <?php endif; ?>
		<?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Слайдер") ) : ?>
		<?php //endif; ?>
           
	</div>
</header>
    <div id="modal" class="modal hide" style="width: 270px;">
	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h2>Заказать звонок</h2>
	</div>
	<div class="modal-body">
            <span id="tel_error" style="color: red;"></span>
            <label>Имя</label> <input id="name-tel" class="" />
            <label>Фамилия</label> <input id="fam-tel" class="" />
            <label>Телефон</label> <input id="tel-tel" type="" class="" />
            <label>Удобное время</label> <input id="tel-time-from" type="" class="" style="width: 60px;" value="09:00"/> &nbsp;-&nbsp; <input id="tel-time-to" type="" class="" style="width: 60px;" value="20:00"/><br>
	</div>
        <div class="modal-footer">
            <center><button class="btn-success" style="margin: 5px;" onclick="tel_click();" ata-dismiss="modal" aria-hidden="true">Заказать</button></center>
	</div>
</div>
    <script>
 jQuery(document).ready(function(){
     // Clock start
     HeaderClockUpdate();
     // Clock update interval 60 seconds
     setInterval(function() { HeaderClockUpdate(); }, 60000);
     
    if(document.getElementById('ninja_forms_form_2')) {
	if(document.getElementById('tel-user')){
	   document.getElementById('ninja_forms_field_3').value=document.getElementById('tel-user').value;   
	}
        
        jQuery("#ninja_forms_form_2").attr('action', '');
        jQuery("#ninja_forms_field_7").attr('type', 'button');
        jQuery("#ninja_forms_form_2_response_msg").hide();
    
        jQuery("#ninja_forms_field_7").click(function() {
            if ((jQuery('#ninja_forms_field_1').val() == '') || (jQuery('#ninja_forms_field_2').val() == '') || (jQuery('#ninja_forms_field_6').val() == '')) {
                //alert('Не заполнены обязательные поля формы');
                jQuery("#ninja_forms_form_2_response_msg").html('<p>Не заполнены обязательные поля формы</p>');
                jQuery("#ninja_forms_form_2_response_msg").show();
                return false;
            } else {
                jQuery("#ninja_forms_form_2_response_msg").hide();
            }

            paramstr = "ninja_forms_field_1=" + encodeURIComponent(jQuery('#ninja_forms_field_1').val()) + "&ninja_forms_field_2="+encodeURIComponent(jQuery('#ninja_forms_field_2').val()) + "&ninja_forms_field_3=" + encodeURIComponent(jQuery('#ninja_forms_field_3').val()) + "&ninja_forms_field_6=" + encodeURIComponent(jQuery('#ninja_forms_field_6').val());
            //paramstr=jQuery("#ninja_forms_form_2").serialize();
            //alert('paramstr: '+paramstr);
            jQuery("#ninja_forms_form_2_response_msg").hide();
            jQuery("#ninja_forms_field_7").val('Идет отправка');   
            jQuery.ajax({
                        url: "/wp-admin/admin-ajax.php?action=add_message",
                        type: "POST",
                        data: paramstr,
                        success: function(data){
                                //document.forms["bet_form"].submit();
                                document.getElementById('ninja_forms_form_2').submit();
                                document.getElementById('ninja_forms_form_2_cont').style.display='none';
                                jQuery("#well-form").html('<h2>Спасибо за Ваше обращение!</h2>');

                        },
                        error: function(data){
                                if (data.responseText !== 'undefined') {
                                    jQuery("#ninja_forms_form_2_response_msg").html('<p>Ошибка отправки формы:<br>' + data.responseText + '</p>');
                                    jQuery("#ninja_forms_form_2_response_msg").show();
                                    //alert('Ошибка отправки формы: ' + data.responseText);
                                }
                        }			
            });
        });		   
    }           
});       

function tel_click()
{
    flag_subs = 0;

    paramstr = document.getElementById('name-tel').id+"=" + encodeURIComponent(document.getElementById('name-tel').value) + "&"+document.getElementById('fam-tel').id+"="+encodeURIComponent(document.getElementById('fam-tel').value)+"&"+document.getElementById('tel-tel').id+"="+encodeURIComponent(document.getElementById('tel-tel').value)+"&"+document.getElementById('tel-time-from').id+"="+encodeURIComponent(document.getElementById('tel-time-from').value)+"&"+document.getElementById('tel-time-to').id+"="+encodeURIComponent(document.getElementById('tel-time-to').value);

    if  ((document.getElementById('name-tel').value != "") )
    {
        flag_subs = flag_subs+1;
    }
    else
    {

      document.getElementById('tel_error').innerHTML="Заполните поле имя !";
      return false;  
    }
    
    if  ((document.getElementById('tel-tel').value != "") )
    {
        flag_subs=flag_subs+1;
    }
    else
    {
      document.getElementById('tel_error').innerHTML="Заполните номер телефона !";
      return false;  
    }
    
    if  ((document.getElementById('tel-time-from').value != "") && (document.getElementById('tel-time-to').value != ""))
    {
        flag_subs=flag_subs+1;
    }
    else
    {
      document.getElementById('tel_error').innerHTML="Заполните удобное время !";
      return false;  
    }

    if(flag_subs>=3)
    {
        jQuery.ajax({
                    url: "/wp-admin/admin-ajax.php?action=add_tel",
           // url: "/wp-content/plugins/tzs/functions/tzs.functions.php?action=add_bet",
                    type: "POST",
                    data: paramstr,
                    success: function(data){
                            //document.forms["bet_form"].submit();
                            alert(data);
                            jQuery('#modal').modal('hide');

                    },
                    error: function(data){
                            //document.forms["bet_form"].submit();
                        alert(data);
                    }			
            });		   
    }
}
    </script>
<div id="page" class="hfeed site">
	<div id="main" class="wrapper">