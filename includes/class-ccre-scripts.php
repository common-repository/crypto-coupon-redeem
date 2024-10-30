<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
class Ccre_Scripts {

	//class constructor
	function __construct()
	{
		
	}
	
	/**
	 * Enqueue Scripts on Admin Side
	 * 
	 * @package CRYPTO / COUPON REDEEM
	 * @since 1.0.0
	 */
	public function ccre_admin_scripts(){
		
		global $post_type, $ccre_options, $wp_version;

		// Added By Parth Mangukiya Start
		$newui = $wp_version >= '3.5' ? '1' : '0';
		// Added By Parth Mangukiya End
		
		$show_success = false;
		
		$success = '';
		$error   = '';
		
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
			$query = 'query campaigns ( $page: Int, $currencies: [Currency] ) {
						campaigns ( page: $page, currencies: $currencies ) {
							from
							to
							current_page
							has_more_pages
							data {
								uuid
								name
								language
								type
								currency
								baseline
								delay_before_exportation
								starts_at
								expires_at
								status
								coupons{
									uuid
									comment
									status
									currency
									amount
									reserved_to
									scan_url
								}
							}
						  }
						}';
			
			$variables = array('page' => 1, 'currencies' => array( $iscu_symbol ) );
			$result_obj = ccre_send_request( $query, $variables );
			
			//$result_obj = json_decode( $result, true );
			
			if( !isset( $result_obj['errors'] ) )
			{
				$show_success = true;
				
				$success = __( 'Last successful import : ', 'crypto-coupon-redeem' ).current_time( 'F j, Y h:i:s A');
			}
			else
			{
				$error = $result_obj['errors'][0]['message'];
			}
		}
		
		if( $post_type == CCRE_PT )
		{
			wp_register_style( 'ccre-select2-style', CCRE_INC_URL.'/css/select2.min.css', array(), null );
			wp_enqueue_style( 'ccre-select2-style' );
		}
		
		wp_register_style( 'ccre-admin-style', CCRE_INC_URL.'/css/ccre-admin.css', array(), 65 );
		wp_enqueue_style( 'ccre-admin-style' );
		
		wp_enqueue_script('jquery');
		
		if( $post_type == CCRE_PT )
		{
			wp_register_script( 'ccre-select2-script', CCRE_INC_URL.'/js/select2.min.js', array(), null );
			wp_enqueue_script( 'ccre-select2-script' );
		}

		// Added By Parth Mangukiya Start
		wp_enqueue_media();
		// Added By Parth Mangukiya End

		wp_register_script( 'ccre-admin-script', CCRE_INC_URL.'/js/ccre-admin.js', array(), 38, true );
		wp_enqueue_script( 'ccre-admin-script' );
		
		wp_localize_script( 'ccre-admin-script', 'Ccre', array(
			'ajaxurl' => admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
			'settings' => __( 'Settings', 'crypto-coupon-redeem' ),
			'documentation' => __( 'Documentation', 'crypto-coupon-redeem' ),
			'show_success' => $show_success,
			'success' => $success,
			'error' => $error,
			'refund_success' => __( 'Refunded successfully.', 'crypto-coupon-redeem' ),
			'refunded' => __( 'Refunded', 'crypto-coupon-redeem' ),
			'burned' => __( 'BURNED', 'crypto-coupon-redeem' ),
			// Added By Parth Mangukiya Start
			'new_media_ui' => $newui
			// Added By Parth Mangukiya End

		) );
	}
	
	/**
	 * Enqueue Scripts on Public Side
	 * 
	 * @package CRYPTO / COUPON REDEEM
	 * @since 1.0.0
	 */
	public function ccre_public_scripts(){
		
		wp_register_style( 'ccre-public-style', CCRE_INC_URL.'/css/ccre-public.css', array(), 20 );
		wp_enqueue_style( 'ccre-public-style' );
		
		wp_enqueue_script('jquery');
		
		wp_register_script( 'ccre-public-script', CCRE_INC_URL.'/js/ccre-public.js', array(), 4, true );
		wp_enqueue_script( 'ccre-public-script' );
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package CRYPTO / COUPON REDEEM
	 * @since 1.0.0
	 */
	function add_hooks(){
		
		add_action( 'admin_enqueue_scripts', array( $this, 'ccre_admin_scripts' ) );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'ccre_public_scripts' ) );
	}
}
?>