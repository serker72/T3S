<?php
/**
 *Template Name: page-products
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<?php
    $args = array(
	'post_type' => 'page',
    'post_per_page' => 15,
    'showposts' => 20,
	//'cat' => get_option('category-post'),
    'paged' => get_query_var( 'paged' ),
    'orderby' => array( 'title' => 'ASC'),
    'post_parent' => 164
    
);
$query = new WP_Query( $args );
$temp_query = $wp_query;
$wp_query = NULL;
$wp_query = $query;
$total_pages = $wp_query->max_num_pages;
if ( $total_pages > 1) {
    $the_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $pagination = array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '?paged=%#%',
        'mid-size' => 1,
        'current' => $the_paged,
        'total' => $total_pages,
        'prev_next' => True,
        'prev_text' => '<< Предыдущая',
        'next_text' =>  'Следующая >>'
    );
//        'prev_text' => __( '<< Previous' ),
//        'next_text' => __( 'Next >>' )
}
?>
<div id="left-block" style="float: left; margin: 15px; width:70%;">
            <ul class="tiled-menu">
                
            <li class="menu-item-pr">
                    <a href="/products/">
                        <span class="menu-tile-pr"> <center>
                                <div class="cub">
                                    Все
                                </div>
                        </center></span>
                    </a>
             </li>    
<?php
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post(); ?>
        
                <li class="menu-item-pr">
                    <a href="<?php the_permalink(); ?>">
                        <span class="menu-tile-pr"> <center>
                                <div class="cub">
                                    <?php the_title(); ?>
                                </div>
                        </center></span>
                    </a>
                </li>
                
         
                
		<?php
	}
?>
   </ul>
</div>
<?php    
    
} else {
	// страниц не найдено
}?>
<div style="clear: both;"></div>
<nav>
    <div id="pages_container">

        <?php echo paginate_links( $pagination );
                $wp_query = NULL;
                $wp_query = $temp_query; ?>
    </div>
</nav>

<?php wp_reset_postdata(); ?>
 





<?php get_sidebar(); ?>
<?php get_footer(); ?>
