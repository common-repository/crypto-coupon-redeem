<?php
// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) exit;

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

	$cart_amount = WC()->cart->total;
	
	$products_array = array(
							'relation' => 'OR'
						);

	$cats_array = array(
							'relation' => 'OR'
						);						
	
	foreach( WC()->cart->get_cart() as $cart_item ){
		
	    $products_array[] = array(
								'key' => 'ccre_products_str',
								'value' => '"'.$cart_item['product_id'].'"',
								'compare' => 'LIKE'
							);

		$cats_array[] = array(
								'key' => 'ccre_cats_str',
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
		
		global $ccre_options;
		
		$message 	   = '';
		$popup_message = '';
		
		if( $language_slug == 'fr' )
		{
			$message 	   = $ccre_options['ccre_cmb_fr'];
			$popup_message = $ccre_options['ccre_cmp_fr'];
		}
		if( $language_slug == 'en' )
		{
			$message 	   = $ccre_options['ccre_cmb_en'];
			$popup_message = $ccre_options['ccre_cmp_en'];
		}
		if( $language_slug == 'it' )
		{
			$message 	   = $ccre_options['ccre_cmb_it'];
			$popup_message = $ccre_options['ccre_cmp_it'];
		}
		if( $language_slug == 'de' )
		{
			$message 	   = $ccre_options['ccre_cmb_de'];
			$popup_message = $ccre_options['ccre_cmp_de'];
		}
		
		if( !empty( $message ) )
		{
?>
			<div class="ccre_checkout_msg">
				<!-- Added By Parth Start -->
				<div class="ccre_msg_wrpp">
					<div class="ccre_msg_left">
						<div class="ccre_img_wrapp">
							<img class="ccre_img" src="<?php echo esc_url( $ccre_options['ccre_logo'] ); ?>" alt="">
						</div>
						
						
					</div>
					<div class="ccre_msg_right">
					<!-- Added By Parth End -->
						<span class="ccre_checkout_msg_inner">
							<?php echo nl2br( esc_html( $message ) ); ?>
						</span>
						
				<?php
				if( !empty( $popup_message ) )
				{
					$iscu_camp_type = get_post_meta( $posts[0], 'type', true );
					
				?>
						<span class="ccre_checkout_msg_popup" id="ccre_checkout_msg_popup">?</span>
						
						<?php
						if( $iscu_camp_type == 'STANDARD' )
						{
						?>
							<div class="ccre_coupon_data">
								<?php echo __('You are going to win ').get_post_meta( $posts[0], 'coupon_amount', true ).get_post_meta( $posts[0], 'currency', true ).'.'; ?>
							</div>
						<?php
						}
						?>
					<!-- Added By Parth Start -->
					</div>
				</div>
				<!-- Added By Parth End -->
					
					<div id="ccre_modal" class="ccre_modal">
						<div class="ccre_modal_content">
							<span class="ccre_close">&times;</span>
							
							<div class="ccre_popup_msg">
								<?php echo nl2br( esc_html( $popup_message ) ); ?>
							</div>
						</div>
					</div>
				<?php
				}
				?>
				<!-- Added By Parth Start -->
				<div class="ccre_powerby">
					Powered by Ridime
				</div>
				<!-- Added By Parth End -->
			</div>
<?php
		}
	}
}
?>