<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
class Ccre_Admin {

	public $scripts;

	//class constructor
	function __construct() {

		global $ccre_scripts;

		$this->scripts = $ccre_scripts;
	}
	
	function ccre_admin_init(){
		
		global $ccre_options;
		
		if( class_exists( 'WooCommerce' ) )
		{
			$iscu_symbol = get_woocommerce_currency();
		}
		else
		{
			$iscu_symbol = '';
		}
		
		$iscu_allowed_currencies = array( 'EUR', 'CHF' );
		
		if( isset( $_GET['post_type'] ) && !empty( $_GET['post_type'] ) && $_GET['post_type'] == CCRE_PT && !isset( $_GET['page'] ) && !isset( $_GET['paged'] ) && !empty( $ccre_options['api_end_point'] ) && !empty( $ccre_options['api_token'] ) && in_array( $iscu_symbol, $iscu_allowed_currencies ) )
		{
			$endpoint = $ccre_options['api_end_point'];
			
			$access_token = $ccre_options['api_token'];
			
			ccre_run_campaing_query( $endpoint, $access_token, 1, array( $iscu_symbol ), array() );
		}	
	}
	
	function ccre_custom_columns( $columns ){
		
		unset($columns['cb']);
		unset($columns['title']);
		unset($columns['date']);
		
		//$columns['id'] = __( 'ID', 'crypto-coupon-redeem' );
		$columns['uuid'] = __( 'UUID', 'crypto-coupon-redeem' );
		$columns['language'] = __( 'Language', 'crypto-coupon-redeem' );
		$columns['title'] = __( 'Name', 'crypto-coupon-redeem' );
		$columns['baseline'] = __( 'Baseline', 'crypto-coupon-redeem' );
		$columns['quantity'] = __( 'Quantity', 'crypto-coupon-redeem' );
		$columns['total_value'] = __( 'Total value', 'crypto-coupon-redeem' );
		$columns['currency'] = __( 'Currency', 'crypto-coupon-redeem' );
		$columns['redeemed_coupons'] = __( 'Number of redeemed coupons', 'crypto-coupon-redeem' );
		$columns['remaining_coupons'] = __( 'Number of remaining coupons', 'crypto-coupon-redeem' );
		$columns['type'] = __( 'Type', 'crypto-coupon-redeem' );
		$columns['delay_before_exportation'] = __( 'Delay before exportation', 'crypto-coupon-redeem' );
		$columns['starts_at'] = __( 'Start at', 'crypto-coupon-redeem' );
		$columns['expires_at'] = __( 'Expires at', 'crypto-coupon-redeem' );
		$columns['status'] = __( 'Campaign status', 'crypto-coupon-redeem' );
		$columns['settings'] = __( 'Settings', 'crypto-coupon-redeem' );
		
		return $columns;
	}
	
	function ccre_custom_columns_data( $column, $post_id ){
		
		if( $column == 'id' )
		{
			//echo '<a class="ccre_campaing_link" href="'.get_edit_post_link( $post_id ).'">'.$post_id.'</a>';
		}
		else if( $column == 'settings' )
		{
			echo '<a class="ccre_campaing_link" href="'.get_edit_post_link( $post_id ).'">'.__( 'View', 'crypto-coupon-redeem' ).'</a>';
		}
		else
		{
			echo '<a class="ccre_campaing_link" href="'.get_edit_post_link( $post_id ).'">'.get_post_meta( $post_id , $column, true ).'</a>';
		}
	}
	
	function ccre_add_meta_boxes(){
		
		add_meta_box( 'ccre_mb_1', __( 'Campaign Details', 'crypto-coupon-redeem' ), array( $this, 'ccre_mb_content' ), CCRE_PT, 'advanced', 'high' );
		add_meta_box( 'ccre_mb_2', __( 'Settings', 'crypto-coupon-redeem' ), array( $this, 'ccre_mb_2_content' ), CCRE_PT, 'advanced', 'high' );
		
		add_meta_box( 'ccre_mb_3', __( 'Coupons', 'crypto-coupon-redeem' ), array( $this, 'ccre_mb_3_content' ), CCRE_PT, 'advanced', 'high' );
	}
	
	function ccre_mb_content(){
		include( CCRE_ADMIN_DIR.'/forms/ccre-meta-box-1.php' );
	}
	
	function ccre_mb_2_content(){
		include( CCRE_ADMIN_DIR.'/forms/ccre-meta-box-2.php' );
	}
	
	function ccre_mb_3_content(){
		include( CCRE_ADMIN_DIR.'/forms/ccre-meta-box-3.php' );
	}
	
	function ccre_search(){
		
		global $wpdb;
		
		$return = array();
		
		$q   = sanitize_text_field( $_GET['q'] );
		
		if( !empty( $q ) )
		{
			if( is_numeric( $q ) )
			{
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'numberposts' => 100,
					'exclude' => array( $pid ),
					'meta_key' => '_sku',
					'meta_value' => $q,
					//'meta_compare' => 'LIKE'
				);
			}
			else
			{
				$args = array(
					's'=> $q,
					'post_type' => 'product',
					'post_status' => 'publish',
					'numberposts' => 100,
					'exclude' => array( $pid ),
				);
			}
			
			$products = get_posts( $args );
			
			if( !empty( $products ) )
			{
				foreach( $products as $key=>$value ){
					$return[] = array( $value->ID, $value->post_title );
				}
			}
		}
		
		echo json_encode( $return );
		exit;
	}

	function ccre_search_category(){
		
		global $wpdb;
		
		$return = array();
		
		$q   = sanitize_text_field( $_GET['q'] );
		
		if( !empty( $q ) )
		{
			
				$args = array(

					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'name__like'    => $q
				);
			
			
			$category = get_terms( $args );
			
			if( !empty( $category ) )
			{
				foreach( $category as $key=>$value ){
					$return[] = array( $value->term_id, $value->name );
				}
			}
		}
		
		echo json_encode( $return );
		exit;
	}
	
	public function ccre_save_post( $post_id ) {
		
		global $post_type;
		
		$post_type_object = get_post_type_object( $post_type );
		
		// Check for which post type we need to add the meta box
		$pages = array( CCRE_PT );

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
		|| ( ! in_array( $post_type, $pages ) )              // Check if current post type is supported.
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )       // Check permission
		{
		  return $post_id;
		}
		
		$ccre_enable_min_amount = isset( $_POST['ccre_enable_min_amount'] ) ? sanitize_text_field( $_POST['ccre_enable_min_amount'] ) : '';
		$ccre_enable_products   = isset( $_POST['ccre_enable_products'] ) ? sanitize_text_field( $_POST['ccre_enable_products'] ) : '';
		$ccre_min_amount        = isset( $_POST['ccre_min_amount'] ) ? sanitize_text_field( $_POST['ccre_min_amount'] ) : '';
		$ccre_products   		= isset( $_POST['ccre_products'] ) ? array_map( 'sanitize_text_field', $_POST['ccre_products'] ) : array();
		$ccre_category 			= isset( $_POST['ccre_category'] ) ? array_map( 'sanitize_text_field', $_POST['ccre_category'] ) : array();
		
		update_post_meta( $post_id, 'ccre_enable_min_amount', $ccre_enable_min_amount );
		update_post_meta( $post_id, 'ccre_min_amount', $ccre_min_amount );
		update_post_meta( $post_id, 'ccre_enable_products', $ccre_enable_products );
		update_post_meta( $post_id, 'ccre_products', $ccre_products );

		// Added By Parth Start
		update_post_meta( $post_id, 'ccre_category', $ccre_category );

		$products = array();

		foreach( $ccre_category as $key=>$value  ){
			
			$paroduct_args = array(

				'post_type' => 'product',
				'post_status' => 'publish',
				'numberposts' => -1,
				'fields' => 'ids',
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $value,
					),
				),
			);

			$posts = get_posts($paroduct_args);

			$products = array_merge( $products, $posts );
		}
		
		$new_products = array();
		
		foreach( $products as $pk=>$pv ){
			$new_products[] = (string)$pv;
		}

		$products = array_merge( $ccre_products, $new_products );

		$products = array_unique( $products );
		
		update_post_meta( $post_id, 'ccre_products_str', serialize( $products ) );
		// Added By Parth End
	}
	 
	public function ccre_admin_menu(){
		
		add_submenu_page( 'edit.php?post_type=ccre-campaigns', __( 'Settings', 'crypto-coupon-redeem' ), __( 'Settings', 'crypto-coupon-redeem' ), 'manage_options', 'ccre-settings', array( $this, 'ccre_settings_page' ) );
	}
	
	public function ccre_register_settings(){
		
		register_setting( 'ccre_settings', 'ccre_options' );
	}
	
	public function ccre_settings_page(){
		
		include_once( CCRE_ADMIN_DIR.'/forms/ccre-settings.php' );
	}
	
	public function ccre_woocommerce_before_checkout_form(){
		
		include_once( CCRE_ADMIN_DIR.'/forms/ccre-checkout.php' );
	}
	
	public function ccre_woocommerce_checkout_update_order_meta( $order_id ){
		
		$language = get_bloginfo('language');
	
		$language_arr = explode( '-', $language );
		
		$language_slug = $language_arr[0];
		
		if( $language_slug == 'fr' )
		{
			$camp_lang = 'FRENCH';
		}
		else if( $language_slug == 'en' )
		{
			$camp_lang = 'ENGLISH';
		}
		else if( $language_slug == 'it' )
		{
			$camp_lang = 'ITALIAN';
		}
		else if( $language_slug == 'de' )
		{
			$camp_lang = 'GERMAN';
		}
		else
		{
			$camp_lang = '';
		}
		
		if( !empty( $camp_lang ) )
		{
			$order = wc_get_order( $order_id );
			
			$cart_amount = $order->get_total();
			
			$products_array = array(
									'relation' => 'OR'
								);
			
			foreach( $order->get_items() as $cart_item ){
				
			    $products_array[] = array(
										'key' => 'ccre_products_str',
										'value' => '"'.$cart_item['product_id'].'"',
										'compare' => 'LIKE'
									);
			}
			
			$args = array(
				'post_type' => CCRE_PT,
				'numberposts' => -1,
				'fields' => 'ids',
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'status',
						'value' => 'ACTIVE'
					),
					array(
						'key' => 'language',
						'value' => $camp_lang
					),
					array(
						'key' => 'remaining_coupons',
						'value' => 0,
						'compare' => '!='
					),
					array(
						'relation' => 'OR',
						array(
							'relation' => 'AND',
							
							array(
								'key' => 'ccre_enable_min_amount',
								'value' => 1
							),
							array(
								'key' => 'ccre_min_amount',
								'value' => $cart_amount,
								'compare' => '<=',
								'type' => 'NUMERIC'
							)
						),
						 array(
							'relation' => 'AND',
							array(
								'key' => 'ccre_enable_products',
								'value' => 1
							),
							$products_array
						)/* ,
						array(
							'relation' => 'AND',
							array(
								'key' => 'ccre_enable_products',
								'value' => 1
							),
							$cats_array
						) */
					)
				)
			);
			
			
					
			$posts = get_posts( $args );
			
			if( !empty( $posts ) )
			{
				$camp_id = $posts[0];
				
				$uuid 		   = get_post_meta( $camp_id, 'uuid', true );
				$coupon_amount = get_post_meta( $camp_id, 'coupon_amount', true );
				
				global $ccre_options;
			
				if( !empty( $ccre_options['api_end_point'] ) && !empty( $ccre_options['api_token'] ) )
				{
					$endpoint = $ccre_options['api_end_point'];
					
					$access_token = $ccre_options['api_token'];
					
					$query = '{book_coupon(campaign_uuid: "'.$uuid.'", amount: '.$coupon_amount.', reserved_to: "'.sanitize_email( $_POST['billing_email'] ).'") {
					    currency
					    uuid
					    amount
					    status
					    scan_url
					    campaign{
					      uuid
					      name
					    }
					  }
					}
					';
					
					$variables = array();
					$result_obj = ccre_send_request( $query, $variables );
					
					if( !isset( $result_obj['errors'] ) )
					{
						update_post_meta( $order_id, 'ccre_scan_url', $result_obj['data']['book_coupon']['scan_url'] );
					}
				}
			}
		}
	}
	
	public function ccre_woocommerce_email_order_meta( $order, $sent_to_admin, $plain_text, $email ){
		
		if( !$sent_to_admin && ( $email->id == 'customer_processing_order' || $email->id == 'customer_completed_order' || $email->id == 'customer_invoice' ) )
		{
			$order_id = $order->get_id();
			
			$ccre_scan_url = get_post_meta( $order_id, 'ccre_scan_url', true );
			
			if( !empty( $ccre_scan_url ) )
			{
				global $ccre_options;
				
				$language = get_bloginfo('language');
		
				$language_arr = explode( '-', $language );
				
				$language_slug = $language_arr[0];
				
				$email_message = isset( $ccre_options['ccre_etm_'.$language_slug] ) ? $ccre_options['ccre_etm_'.$language_slug] : '';
				
				if( !empty( $email_message ) )
				{
					echo '<div>'.nl2br( esc_html( $email_message ) ).'</div>';
				}
				
				echo '<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.esc_url( $ccre_scan_url ).'&choe=UTF-8" />';
				echo '<div style="margin-bottom:15px;"><a href="'.esc_url( $ccre_scan_url ).'">'.esc_url( $ccre_scan_url ).'</a></div>';
			}
		}
	}
	
	public function ccre_refund(){
		
		global $ccre_options;
			
		if( !empty( $ccre_options['api_end_point'] ) && !empty( $ccre_options['api_token'] ) )
		{
			$uuid 	 = sanitize_text_field( $_POST['uuid'] );
			$camp_id = sanitize_text_field( $_POST['camp_id'] );
			
			$endpoint = $ccre_options['api_end_point'];
			
			$access_token = $ccre_options['api_token'];
			
			$query = '{
					  burn_coupon(uuid: "'.$uuid.'", reason: "reimbursement"){
					    currency
					    uuid
					    amount
					    status
					    campaign{
					      uuid
					      name
					    }
					  }
					}
			';
			
			
			$headers = array(
							"Token" => $access_token,
							"Content-Type" => "application/json",
						);
			
			$args = array(
						'method' => 'POST',
		        		'headers' => $headers,
		        		'httpversion' => '1.0',
		        		'sslverify' => false,
		        		'body' => json_encode(compact('query'))
					);
			
			$post   = wp_remote_post( $endpoint, $args );
			
			$result_obj = json_decode( wp_remote_retrieve_body( $post ), true );
			
			if( !isset( $result_obj['errors'] ) )
			{
				echo 'success';
				
				$coupons = get_post_meta( $camp_id, 'coupons', true );
				
				if( !empty( $coupons ) )
				{
					foreach( $coupons as $key=>$value ){
						
						if( $value['uuid'] == $uuid )
						{
							$coupons[$key]['status'] = 'BURNED';
						}
					}
				}
				
				update_post_meta( $camp_id, 'coupons', $coupons );
			}
			else
			{
				echo esc_html( $result_obj['errors'][0]['extensions']['validation']['uuid'][0] );
			}
			
			exit;
			
		}
		
		echo 'error';
		exit;
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package CRYPTO / COUPON REDEEM
	 * @since 1.0.0
	 */
	function add_hooks(){
		
		add_action( 'admin_init', array( $this, 'ccre_admin_init' ) );
		
		add_filter( 'manage_ccre-campaigns_posts_columns', array( $this, 'ccre_custom_columns' ) );
		
		add_action( 'manage_ccre-campaigns_posts_custom_column', array( $this, 'ccre_custom_columns_data' ), 10, 2 );
		
		add_action( 'add_meta_boxes', array( $this, 'ccre_add_meta_boxes' ) );
		
		add_action( 'wp_ajax_ccre_search', array( $this, 'ccre_search' ) );
		add_action( 'wp_ajax_nopriv_ccre_search', array( $this, 'ccre_search' ) );

		add_action( 'wp_ajax_ccre_search_category', array( $this, 'ccre_search_category' ) );
		add_action( 'wp_ajax_nopriv_ccre_search_category', array( $this, 'ccre_search_category' ) );
		
		add_action( 'save_post', array( $this, 'ccre_save_post' ) );
		
		add_action( 'admin_menu', array( $this, 'ccre_admin_menu' ) );
		
		add_action( 'admin_init', array( $this, 'ccre_register_settings') );
		
		add_action( 'woocommerce_before_checkout_form', array( $this, 'ccre_woocommerce_before_checkout_form') );

		// Added By Parth Start
		add_action( 'woocommerce_before_cart_table', array( $this, 'ccre_woocommerce_before_checkout_form') );
		// Added By Parth End
		
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'ccre_woocommerce_checkout_update_order_meta' ) );
		
		add_action( 'woocommerce_email_order_meta', array( $this, 'ccre_woocommerce_email_order_meta' ), 10, 4 );
		
		add_action( 'wp_ajax_ccre_refund', array( $this, 'ccre_refund' ) );
		add_action( 'wp_ajax_nopriv_ccre_refund', array( $this, 'ccre_refund' ) );
	}
}
?>