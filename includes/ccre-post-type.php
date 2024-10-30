<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function ccre_register_post_type(){
	
	$labels = array(
						'name' 				=> __( 'Campaigns', 'crypto-coupon-redeem' ),
						'singular_name' 	=> __( 'Campaign', 'crypto-coupon-redeem' ),
						'add_new' 			=> __( 'Add New', 'crypto-coupon-redeem' ),
						'add_new_item' 		=> __( 'Add New Campaign', 'crypto-coupon-redeem' ),
						'edit_item' 		=> __( 'Edit Campaign', 'crypto-coupon-redeem' ),
						'new_item' 			=> __( 'New Campaign', 'crypto-coupon-redeem' ),
						'all_items' 		=> __( 'All Campaigns', 'crypto-coupon-redeem' ),
						'view_item' 		=> __( 'View Campaign', 'crypto-coupon-redeem' ),
						'search_items' 		=> __( 'Search Campaign', 'crypto-coupon-redeem' ),
						'not_found' 		=> __( 'No campaign found.', 'crypto-coupon-redeem' ),
						'not_found_in_trash'=> __( 'No campaign found in trash.', 'crypto-coupon-redeem' ), 
						'parent_item_colon' => '',
						'menu_name' 		=> __( 'CRYPTO-COUPON RIDIME', 'crypto-coupon-redeem' )
					);
							
	$args = array(
						'labels' 			=> $labels,
						'public' 			=> false,
						'publicly_queryable'=> false,
						'show_ui' 			=> true, 
						'show_in_menu' 		=> true, 
						'query_var' 		=> true,
						'rewrite' 			=> array( 'slug' => CCRE_PT ),
						'capability_type' 	=> 'post',
						'capabilities' 		=> array(
									            	'create_posts' => 'do_not_allow'
									        	),
						'map_meta_cap' 		=> true,
						'has_archive' 		=> true, 
						'hierarchical' 		=> false,
						'supports' 			=> array( 'title' ),
						'menu_icon'			=> 'dashicons-screenoptions'
					);
						
	register_post_type( CCRE_PT, $args );
}
add_action( 'init', 'ccre_register_post_type' );

function ccre_updated_messages( $messages ) {
		
	global $post, $post_ID;

	$messages[CCRE_PT] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Campaign updated.', 'crypto-coupon-redeem' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'crypto-coupon-redeem' ),
		3 => __( 'Custom field deleted.', 'crypto-coupon-redeem' ),
		4 => __( 'Campaign updated.', 'crypto-coupon-redeem' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Campaign restored to revision from %s', 'crypto-coupon-redeem' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Campaign published.', 'crypto-coupon-redeem' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Campaign saved.' ),
		8 => sprintf( __( 'Campaign submitted. <a target="_blank" href="%s">Preview Campaign</a>', 'crypto-coupon-redeem' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Campaign scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Review</a>', 'crypto-coupon-redeem'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Campaign draft updated. <a target="_blank" href="%s">Preview Campaign</a>', 'crypto-coupon-redeem' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}
	
add_filter( 'post_updated_messages', 'ccre_updated_messages' );
?>