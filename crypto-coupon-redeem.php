<?php
/*
Plugin Name: CRYPTO / COUPON REDEEM
Plugin URI: https://www.ridime.com/plugin
Description: CRYPTO / COUPON REDEEM
Version: 1.0.0
Author: RIDIME
Author URI: https://www.ridime.com
Text Domain: crypto-coupon-redeem
*/

/**
 * Basic plugin definitions 
 * 
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
if( !defined( 'CCRE_DIR' ) ) {
  define( 'CCRE_DIR', dirname( __FILE__ ) );      // Plugin dir
}
if( !defined( 'CCRE_URL' ) ) {
  define( 'CCRE_URL', plugin_dir_url( __FILE__ ) );   // Plugin url
}
if( !defined( 'CCRE_INC_DIR' ) ) {
  define( 'CCRE_INC_DIR', CCRE_DIR.'/includes' );   // Plugin include dir
}
if( !defined( 'CCRE_INC_URL' ) ) {
  define( 'CCRE_INC_URL', CCRE_URL.'includes' );    // Plugin include url
}
if( !defined( 'CCRE_ADMIN_DIR' ) ) {
  define( 'CCRE_ADMIN_DIR', CCRE_INC_DIR.'/admin' );  // Plugin admin dir
}
if(!defined('CCRE_PT')) {
  define('CCRE_PT', 'ccre-campaigns'); // Plugin Text Domain
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
load_plugin_textdomain( 'crypto-coupon-redeem', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'ccre_install' );

function ccre_install(){
	
	$ccre_options = get_option('ccre_options');
	
	if( empty( $ccre_options ) )
	{	
		ccre_default_settings();
	}
}

function ccre_default_settings(){
	
	$options = array(
						'api_end_point' => '',
						'api_token' => '',
						'ccre_cmb_fr' => '',
						'ccre_cmp_fr' => '',
						'ccre_etm_fr' => '',
						'ccre_cmb_en' => '',
						'ccre_cmp_en' => '',
						'ccre_etm_en' => '',
						'ccre_cmb_it' => '',
						'ccre_cmp_it' => '',
						'ccre_etm_it' => '',
						'ccre_cmb_de' => '',
						'ccre_cmp_de' => '',
						'ccre_etm_de' => '',
						// Added By Parth Start
						'ccre_logo' => '',
						// Added By Parth End
					);
					
	update_option( 'ccre_options', $options );
}

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package CRYPTO / COUPON REDEEM
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'ccre_uninstall');

function ccre_uninstall(){
  
}

// Global variables
global $ccre_scripts, $ccre_admin, $ccre_options;

$ccre_options = get_option('ccre_options');

// MISC functions
include_once( CCRE_INC_DIR.'/ccre-misc-functions.php' );

// Script class handles most of script functionalities of plugin
include_once( CCRE_INC_DIR.'/class-ccre-scripts.php' );
$ccre_scripts = new Ccre_Scripts();
$ccre_scripts->add_hooks();

// Admin class handles most of admin panel functionalities of plugin
include_once( CCRE_ADMIN_DIR.'/class-ccre-admin.php' );
$ccre_admin = new Ccre_Admin();
$ccre_admin->add_hooks();

// Post type
include_once( CCRE_INC_DIR.'/ccre-post-type.php' );
?>