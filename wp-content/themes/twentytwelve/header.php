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
	</script>
</head>
<body <?php body_class(); ?>>
<header id="masthead" class="site-header" role="banner">
	<div id="header">
		<?php if (!is_front_page()) {?>
			<div id="logo_head" style="float: left;">
                		<a href="/"><img src="/wp-content/themes/twentytwelve/images/logo_footer_3.png"></a>    
            		</div>
		<?php } ?>
		<div id="time">
			<script type="text/javascript">
				var l = new Date();
				document.write (l.toLocaleString());
			</script> 
		</div>
		<div id="lang">
			<a href="#"><!-- Русский --></a>
		</div>
		<?php if (get_current_user_id() == 0) {?>
		<div id="login">
			<a href="/account/login">Войти в систему</a>
		</div>
		<div id="registr">
			<a href="/account/registration/">Регистрация</a>
		</div>
		<?php } else {?>
		<div id="profile">
			<a href="/account/profile">Личный кабинет</a>
		</div>
		<?php }?>
                <div id="tel" >
                    <a href="#modal" role="button" class="btn-tel" data-toggle="modal">Заказать звонок</a>
                    <a href="skype:<?php echo get_option( 't3s_setting_skype_login' ); ?>"><img src="/wp-content/uploads/2015/07/skype.png"/><?php echo "  ".get_option( 't3s_setting_skype_login' ); ?></a>
                </div>
		<div id="chat">
                    <a href="javascript:onChatButtonClick();">ОН-ЛАЙН помощник</a>
		</div>
                


<?php if (is_front_page()) {?>
<div style="clear: both;"></div>
	 <div id="sliders">
                <a href="/"><img src="/wp-content/themes/twentytwelve/images/Head-pic.png"></a>    
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
            <div style="clear:both"></div>
            <center><button class="btn-success" style="margin: 5px;" onclick="tel_click();" ata-dismiss="modal" aria-hidden="true">Заказать</button></center>
	</div>
</div>
    <script>
        

function tel_click()
{
flag_subs=0;

paramstr=document.getElementById('name-tel').id+"=" + encodeURIComponent(document.getElementById('name-tel').value) + "&"+document.getElementById('fam-tel').id+"="+encodeURIComponent(document.getElementById('fam-tel').value)+"&"+document.getElementById('tel-tel').id+"="+encodeURIComponent(document.getElementById('tel-tel').value);

if  ((document.getElementById('name-tel').value != "") )
{
    flag_subs=flag_subs+1;
}
else
{

  document.getElementById('tel_error').innerHTML="Заполните поле имя!";
  return false;  
}
if  ((document.getElementById('tel-tel').value != "") )
{
    flag_subs=flag_subs+1;
}
else
{

  document.getElementById('tel_error').innerHTML="Заполните номер телефона!";
  return false;  
}

if(flag_subs>=2)
{
jQuery.ajax({
		url: "/wp-admin/admin-ajax.php?action=add_tel",
       // url: "/wp-content/plugins/tzs/functions/tzs.functions.php?action=add_bet",
		type: "POST",
		data: paramstr,
		success: function(data){
			//document.forms["bet_form"].submit();
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