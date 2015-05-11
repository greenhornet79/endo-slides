<?php
/**
 * Plugin Name: Endo Slides
 * Plugin URI: http://www.endocreative.com
 * Description: A custom post type for slides
 * Version: 1.0
 * Author: Endo Creative
 * Author URI: http://www.endocreative.com
 * License: GPL2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Plugin folder Path
define( 'EN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
// require_once CN_PLUGIN_DIR . 'js/cpt.php';

add_action( 'wp_enqueue_scripts', 'en_slide_scripts' );

function en_slide_scripts() {

	if ( !is_admin() ) {

		wp_enqueue_script( 'cycle2', EN_PLUGIN_URL . 'js/jquery.cycle2.min.js', array('jquery'), '216', true );
		wp_enqueue_script( 'cycle-swipe', EN_PLUGIN_URL . 'js/jquery.cycleswipe.min.js', array('jquery'), '10', true );

	}

}


// create cpt
function en_slide_register_cpt() {
	$args = array(
		'labels' => array(
				'name' => 'Slides',
				'singular_name' => 'Slide',
				'all_items' => 'All Slides',
				'add_new' => 'Add New Slide',
				'add_new_item' => 'Add New Slide',
				'edit_item' => 'Edit Slide',
				'new_item' => 'Add New Slide',
				'view_item' => 'View Slide',
				'search_items' => 'Search Slides',
				'not_found' => 'No Slides Found',
				'not_found_in_trash' => 'No Slides Found in Trash'
			),
			'public' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'menu_position' => 20,
			'query_var'    => true,
			'supports' => array(
				'title',
				'thumbnail',
				'page-attributes'
			),
			

	);

	register_post_type('en_slide', $args);
}

add_action('init', 'en_slide_register_cpt');


// meta boxes
add_action( 'add_meta_boxes', 'en_slide_meta_box_create' );
add_action( 'save_post', 'en_slide_save_meta');

function en_slide_meta_box_create() {

	add_meta_box( 'en_slide_details', 'Slide Details', 'en_slide_details', 'en_slide', 'normal', 'high' );

}

function en_slide_details( $post ) {

	$link = get_post_meta( $post->ID, '_en_slide_link', true );
	$mobile = get_post_meta( $post->ID, '_en_slide_mobile', true );
	$slide_cb = get_post_meta( $post->ID, '_en_slide_mobile_cb', true );
	$slide_select = get_post_meta( $post->ID, '_en_slide_select', true );
	
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' ); 
			?>

			<table class="form-table">

				<tr>
					<th scope="row">
						<label for="en_slide_link">Link: </label>
					</th>
					<td>
						<input type="text" class="large-text" id="en_slide_link" name="en_slide_link" value="<?php echo $link; ?>" />
						
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="en_slide_mobile">Mobile IMG URL: </label>
					</th>
					<td>
						<input type="text" class="large-text" id="en_slide_mobile" name="en_slide_mobile" value="<?php echo $mobile; ?>" />
						
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="en_slide_mobile">A checkbox: </label>
					</th>
					<td>
						<input type="checkbox" class="large-text" id="en_slide_mobile_cb" name="en_slide_mobile_cb" value="<?php echo $slide_cb; ?>" <?php checked( $slide_cb, 'on' ); ?>/>
						
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="en_slide_mobile">A select box: </label>
					</th>
					<td>
						<select id="en_slide_select" name="en_slide_select">
							<option value="">Choose a location</option>
							<option value="1" <?php selected( '1', $slide_select); ?>>Position 1</option>
							<option value="2" <?php selected( '2', $slide_select); ?>>Position 2</option>
							<option value="3" <?php selected( '3', $slide_select); ?>>Position 2</option>
							<option value="4" <?php selected( '4', $slide_select); ?>>Position 2</option>
							<option value="5" <?php selected( '5', $slide_select); ?>>Position 2</option>
						</select>
						
					</td>
				</tr>
			</table>
<?php 
}

function en_slide_save_meta( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  

	if ( isset( $_POST['en_slide_link'] ) ) {

		update_post_meta( $post_id, '_en_slide_link',strip_tags( $_POST['en_slide_link'] ) );
	}

	if ( isset( $_POST['en_slide_mobile'] ) ) {

		update_post_meta( $post_id, '_en_slide_mobile',strip_tags( $_POST['en_slide_mobile'] ) );
	}

	if ( $_POST['en_slide_select'] ) {
		update_post_meta( $post_id, '_en_slide_select', $_POST['en_slide_select'] );
	} 

	$chk = isset( $_POST['en_slide_mobile_cb'] ) ? 'on' : 'off';
    update_post_meta( $post_id, '_en_slide_mobile_cb', $chk );
	
}