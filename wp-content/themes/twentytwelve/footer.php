</div>
<?php if (is_front_page()) {?>
<div class="testimonials-block">
    <div class="container">
        <div><?php echo do_shortcode('[testimonial_view id=1]'); ?></div>
		<div id="testimonial-form" style="display: none"><?php echo do_shortcode('[testimonial_view id=2]'); ?></div>
    </div>
</div>
<?php } ?>

<div id="footer">
    <div class="social-block">
        <div class="container">
            <div class="social-layer">
                <a href="<?php echo (get_option('t3s_setting_facebook_url') == '') ? '' : get_option('t3s_setting_facebook_url'); ?>" class="link-social"  data-toggle="tooltip" data-placement="bottom" title="Facebook" id="facebook"></a>
                <a href="<?php echo (get_option('t3s_setting_vk_url') == '') ? '' : get_option('t3s_setting_vk_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="VK" id="vk"></a>
                <a href="<?php echo (get_option('t3s_setting_ok_url') == '') ? '' : get_option('t3s_setting_ok_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="OK" id="ok"></a>
                <a href="<?php echo (get_option('t3s_setting_google_url') == '') ? '' : get_option('t3s_setting_google_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="Google +" id="google"></a>
                <a href="<?php echo (get_option('t3s_setting_youtube_url') == '') ? '' : get_option('t3s_setting_youtube_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="YouTube" id="youtube"></a>
                <a href="<?php echo (get_option('t3s_setting_twitter_url') == '') ? '' : get_option('t3s_setting_twitter_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="Twitter" id="twitter"></a>
                <a href="<?php echo (get_option('t3s_setting_instagram_url') == '') ? '' : get_option('t3s_setting_instagram_url'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="Instagram" id="insta"></a>
                <a href="<?php echo (get_option('t3s_setting_skype_login') == '') ? '' : 'skype:' . get_option('t3s_setting_skype_login'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="Skype" id="skype"></a>
                <a href="<?php echo (get_option('t3s_setting_email_support') == '') ? '' : 'mailto:' . get_option('t3s_setting_email_support'); ?>" class="link-social" data-toggle="tooltip" data-placement="bottom" title="Mail" id="mail"></a>
            </div>
        </div>>
    </div>
	<div id="footer_info">
		<div id="footer_logo">
			<a href="/"></a>
			<p>Национальная торговая торгово-транспортная система</p>
		</div>
		 <div id="footer_menu_1">
			<?php wp_nav_menu('menu=footer_1'); ?>
             <?php //wp_nav_menu('menu=tender-menu'); ?>

		</div>
		<!--<div id="footer_menu_2">
			 <?php //wp_nav_menu('menu=footer_2'); ?>

		</div> -->
		<div id="social">
			
		</div>
	</div>
</div>
</div>
<?php wp_footer(); ?>
<script>
    jQuery(document).ready(function(){
        //jQuery('[data-toggle="tooltip"]').tooltip();
        jQuery('div.simplePagerList').after('<div><a href="" onclick="javascript:kskTestimonialNav(\'left\');return false;">&lt;</a>&nbsp;<a href="" onclick="javascript:kskTestimonialNav(\'right\');return false;">&gt;</a></div>');
        jQuery('div.simplePagerList').hide();
    });
    function kskTestimonialNav(navPath) {
        var allPage = jQuery('div.testimonial');
        var firstPage = allPage.first();
        var lastPage = allPage.last();
        var currentPage = allPage.filter(':visible');

        
        if (navPath == 'left') {
            var isFirstVisible = firstPage.is(':visible');
            if (isFirstVisible) {
                var nextPage = lastPage;
            } else {
                var nextPage = currentPage.prev();
            }
        } else {
            var isLastVisible = lastPage.is(':visible');
            if (isLastVisible) {
                var nextPage = firstPage;
            } else {
                var nextPage = currentPage.next();
            }
        }

        currentPage.hide();
        nextPage.show();
    }
</script>
</body>
</html>