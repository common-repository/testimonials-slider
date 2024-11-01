<?php
/*Plugin Name: Testimonials Slider
Plugin URI: http://inboxtech.in/
Description: Testimonials
Version: 1.1
Author: Inbox Technology
Author URI: http://inboxtech.in/
License: GPLv2 or later
Text Domain: http://inboxtech.in/
 */

if(!defined('ABSPATH')) exit; // Prevent Direct Browsing

define('AWTS_INCLUDE_DIR', plugin_dir_path(__FILE__).'include/');


// CSS and JS include
function  inboxtmnl_callback_for_setting_up_scripts() {
	wp_register_style( 'style',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
	wp_enqueue_style( 'style' );
    wp_register_style( 'bootstrap-min-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap-min-css' );
	wp_enqueue_script( 'bootstrap-min-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' );
	wp_enqueue_script( 'bootstrap-min-js' );
	wp_register_script( 'main-js',plugin_dir_url( __FILE__ ) . 'js/main.js');
	wp_enqueue_script( 'main-js' );
    wp_enqueue_script('jquery' );
    wp_enqueue_style( 'testimonial-owl-style', plugins_url('/include/styles.css', __FILE__) );
    wp_enqueue_script( 'testimonial-main-js', plugins_url('/include/carousels.js', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'inboxtmnl_callback_for_setting_up_scripts' );

// Testimonial Custom Post Type
function inboxtmnl_new_testimonial_posts(){

    $labels = array(
        'name'                => _x( 'Testimonials', 'testimonial_posts' ),
        'singular_name'       => _x( 'Testimonial', 'testimonial_posts' ),
        'menu_name'           => __( 'Testimonials', 'testimonial_posts' ),
        'parent_item_colon'   => __( 'Parent Client Testimonials:', 'testimonial_posts' ),
        'all_items'           => __( 'All Testimonials', 'testimonial_posts' ),
        'view_item'           => __( 'View Testimonial', 'testimonial_posts' ),
        'add_new_item'        => __( 'Add New Testimonial', 'testimonial_posts' ),
        'add_new'             => __( 'New Testimonial', 'testimonial_posts' ),
        'edit_item'           => __( 'Edit Testimonial', 'testimonial_posts' ),
        'update_item'         => __( 'Update Testimonial', 'testimonial_posts' ),
        'search_items'        => __( 'Search Testimonials', 'testimonial_posts' ),
        'not_found'           => __( 'No Testimonials found', 'testimonial_posts' ),
        'not_found_in_trash'  => __( 'No Testimonials found in Trash', 'testimonial_posts' ),
    );
    $args = array(

        'labels'              => $labels,
        'description'         => __( 'Testimonials Post Type', 'testimonial_posts' ),
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 12,
        'menu_icon'           => 'dashicons-format-status',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'testimonial-post', $args );


}

add_action('init' , 'inboxtmnl_new_testimonial_posts');
  


function inboxtmnl_testimonial_pages() {
	add_submenu_page("edit.php?post_type=testimonial-post", "Settings", "Settings", "administrator", "testimonial-settings", "testimonial_pages");
}
add_action("admin_menu", "inboxtmnl_testimonial_pages");



function testimonial_pages() {

   $setting = AWTS_INCLUDE_DIR.$_GET["page"].'.php';
   include($setting);

}

add_action( 'admin_enqueue_scripts', 'testimonial_color_picker' );
function testimonial_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', plugins_url('include/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


// Testimonial Meta Box
function testimonial_add_meta_box(){

// add meta Box
    add_meta_box(
        'testimonial_testimonial_meta_id', 				
        __( 'Client Information', 'testimonial_posts'), 		
        'testimonial_meta_callback', 						
        'testimonial-post',								
        'normal'

    );

}
add_action('add_meta_boxes' , 'testimonial_add_meta_box');


function testimonial_meta_callback($post){

    wp_nonce_field( basename( __FILE__ ), 'testimonial_nonce' );
    $testimonial_stored_meta = get_post_meta( $post->ID );
    ?>

    <p>
        <label for="testimonial_testimonial_meta_name" class="testimonial_testimonial_meta_name"><?php _e( 'Name', 'testimonial-post' )?></label>
        <input class="widefat" type="text" name="testimonial_testimonial_meta_name" id="testimonial_testimonial_meta_name" value="<?php if ( isset ( $testimonial_stored_meta['testimonial_testimonial_meta_name'] ) ) echo $testimonial_stored_meta['testimonial_testimonial_meta_name'][0]; ?>" />
    </p>

    <p>
        <label for="testimonial_testimonial_meta_destignation" class="testimonial_testimonial_meta_destignation"><?php _e( 'Designation', 'testimonial-post' )?></label>
        <input class="widefat" type="text" name="testimonial_testimonial_meta_destignation" id="testimonial_testimonial_meta_destignation" value="<?php if ( isset ( $testimonial_stored_meta['testimonial_testimonial_meta_destignation'] ) ) echo $testimonial_stored_meta['testimonial_testimonial_meta_destignation'][0]; ?>" />
    </p>
	<p>
	<label for="testimonial_testimonial_meta_rating" class="testimonial_testimonial_meta_rating"><?php _e( 'Star Rating', 'testimonial-post')?></label>
	<?php 		
				for($i = 1; $i <= 5; $i++ ) {
					if($i <=$testimonial_stored_meta) { ?>
						<div id="<?php echo $i;?>" class="dashicons dashicons-star-filled rt-star"></div>
					<?php } else { ?>
						<div id="<?php echo $i;?>" class="dashicons dashicons-star-empty rt-star"></div>
				<?php }
				} ?>
        <input class="widefat" type="hidden" name="testimonial_testimonial_meta_rating" id="testimonial_testimonial_meta_rating" value="<?php if ( isset ( $testimonial_stored_meta['testimonial_testimonial_meta_rating'] ) ) echo $testimonial_stored_meta['testimonial_testimonial_meta_rating'][0];?>" />
    </p>
	


<?php

}
//Testimonial Save Meta Box 

function testimonial_meta_save( $post_id ) {

    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'testimonial_nonce' ] ) && wp_verify_nonce( $_POST[ 'testimonial_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    
    if( isset( $_POST[ 'testimonial_testimonial_meta_name' ] ) ) {
        update_post_meta( $post_id, 'testimonial_testimonial_meta_name', sanitize_text_field( $_POST[ 'testimonial_testimonial_meta_name' ] ) );
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'testimonial_testimonial_meta_destignation' ] ) ) {
        update_post_meta( $post_id, 'testimonial_testimonial_meta_destignation', sanitize_text_field( $_POST[ 'testimonial_testimonial_meta_destignation' ] ) );
    }
 if( isset( $_POST[ 'testimonial_testimonial_meta_rating' ] ) ) {
        update_post_meta( $post_id, 'testimonial_testimonial_meta_rating', sanitize_text_field( $_POST[ 'testimonial_testimonial_meta_rating' ] ) );
    }

}
add_action( 'save_post', 'testimonial_meta_save' );



//Testimonials Dashboard Icons
function testimonial_dashboard_icon(){
?>
 <style>
#adminmenu .menu-icon-testimonials div.wp-menu-image:before {
  content: "\f205";
}
</style>
<?php
}
add_action( 'admin_head', 'testimonial_dashboard_icon' );

//Client Testimonial Shortcode 

add_shortcode( 'Testimonial_Slider', 'inboxtmnl_display_plugin' );
function inboxtmnl_display_plugin( $atts ) {
    ob_start();
	
	//get saved settings
    $main_box_bg_color= get_option('main_box_bg_color'); 
    $all_text_color= get_option('all_text_color'); 
    $client_content_box_color= get_option('client_content_box_color');
    $testimonial_heading_text= get_option('testimonial_heading_text'); 
	
	//default color settings
	if($main_box_bg_color == ''){ $main_box_bg_color='#405448'; }
	if($all_text_color == ''){ $all_text_color='#fff'; }
	if($client_content_box_color == ''){ $client_content_box_color='#2a4126'; }
	if($testimonial_heading_text == ''){ $testimonial_heading_text='Client Testimonials'; }
	?>
	<style>
	#wp-client-testimonials { background: <?php echo $main_box_bg_color;?> none repeat scroll 0 0; }
	#wp-client-testimonials .carousel-wrap .contextt { background:<?php echo $client_content_box_color;?>; }
	#wp-client-testimonials .carousel-wrap .contextt::after {
		border-color: <?php echo $client_content_box_color;?> transparent transparent;
		border-style: solid;
		border-width: 20px 18px 0;
		content: "";
		height: 0;
		left: 20px;
		position: relative;
		top: 44px;
		width: 0;
    }
	#wp-client-testimonials h2 { color: <?php echo $all_text_color;?>; }
	#wp-client-testimonials .prevbtnn, #wp-client-testimonials .nextbtnn { color: <?php echo $all_text_color;?>;}
	#wp-client-testimonials .student p { color: <?php echo $all_text_color;?> !important; }
	#wp-client-testimonials .context1 > p { color: <?php echo $all_text_color;?> !important; }
	</style>
	<script language="JavaScript">
				jQuery(document).ready(function() {
					
					jQuery(".rt-star").click(function() {
						var cntStar = jQuery(this).attr('id');
						for(var i=1; i <= 5; i++) {
							if(i <=cntStar) {
								jQuery("#"+i).removeClass("dashicons-star-empty");
								jQuery("#"+i).addClass("dashicons-star-filled");
							}
							else {
								jQuery("#"+i).removeClass("dashicons-star-filled");
								jQuery("#"+i).addClass("dashicons-star-empty");
							}
						}
						jQuery("input[name='testimonial_testimonial_meta_rating']").val(cntStar);
					});
				});
			</script>

	
	<?php
	
    extract( shortcode_atts( array (
        'type' => 'testimonial-post',
        'order' => 'date',
        'orderby' => 'title',
        'posts' => -1,
    
    ), $atts ) );
    $options = array(
        'post_type' => $type,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $posts,
  		
    );
    $query = new WP_Query( $options );?>
    <?php if ( $query->have_posts() ) { ?>
	
	
	 <div id="wp-client-testimonials">
	 
        <div class="carousel-nav clearfix">
		 <a id="prv-testimonial" class="prevbtnt" href="javascript:void(0)">.</a>
		 <a id="nxt-testimonial" class="nextbtnt" href="javascript:void(0)">.</a>
        </div>
        <div class="carousel-wrap">
          <ul id="testimonial-list" class="clearfix">
          <?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<li>
			
              <div class="context1" style="text-align:center;">  
			  <h2><?php the_title();?></div></h2>
			  <div class="row">
			  <div class="col-sm-12">
			  <div class="author">
				<div class="photo"><?php the_post_thumbnail('thumbnail'); ?> </div></div>
				<p><?php the_content();?></p>
				<div class="author_info">
				<p><?php echo  get_post_meta( get_the_ID(), 'testimonial_testimonial_meta_name', true );?></p>
				<p><?php echo  get_post_meta( get_the_ID(), 'testimonial_testimonial_meta_destignation', true );?></p>
				<div class="rt-star">
					<p><?php echo  get_post_meta( get_the_ID(), 'testimonial_testimonial_meta_rating', true );?></p></div>
				</div>
				</div>
			</div>
			
            </li>   
            <?php endwhile;
            wp_reset_postdata(); ?>
			<?php $myvariable = ob_get_clean();
			return $myvariable;
			?>
	     </ul>
		 
       </div>
    </div>
	<div style="clear:both"></div>
	<?php
    } else{
		
		echo 'No Testimonials!';
	}  
}



?>


