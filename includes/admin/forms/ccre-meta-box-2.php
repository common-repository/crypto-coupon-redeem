<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;
	
$ccre_enable_min_amount = get_post_meta( $post->ID, 'ccre_enable_min_amount', true );
$ccre_min_amount 		= get_post_meta( $post->ID, 'ccre_min_amount', true );
$ccre_enable_products   = get_post_meta( $post->ID, 'ccre_enable_products', true );
$ccre_products	 		= get_post_meta( $post->ID, 'ccre_products', true );
// Added By Parth Start
$ccre_category	 		= get_post_meta( $post->ID, 'ccre_category', true );
// added By Parth Ends
$class1 = '';

if( $ccre_enable_min_amount != 1 )
{
	$class1 = ' ccre_min_amount_wrap_hide';
}

$class2 = '';

if( $ccre_enable_products != 1 )
{
	$class2 = ' ccre_products_wrap_hide';
}
?>

<div class="ccre_mb2_desc">
	<?php echo __( 'Conditions to trigger in checkout', 'crypto-coupon-redeem' ); ?>
</div>

<?php
if( $ccre_enable_min_amount != 1 && $ccre_enable_products != 1 )
{
?>
	<div class="ccre_mb2_alert">
		<?php echo __( 'Please enable conditions.', 'crypto-coupon-redeem' ); ?>
	</div>
<?php
}
?>

<table class="form-table ccre_mb2">
	<tbody>
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Minimum cart amount', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<div class="ccre_enable_min_amount_wrap">
					<input type="checkbox" name="ccre_enable_min_amount" id="ccre_enable_min_amount" value="1" <?php checked( 1, $ccre_enable_min_amount ); ?>>
				</div>
				
				<div class="ccre_min_amount_wrap<?php echo esc_html( $class1 ); ?>">
					<input type="text" name="ccre_min_amount" class="regular-text" value="<?php echo esc_html( $ccre_min_amount ); ?>">
					<!-- Added By Parth Start -->
					<?php 
					$ccre_currency = get_woocommerce_currency();
					echo esc_html( $ccre_currency );
					?>
					<!-- Added By Parth End -->
					<div class="ccre_tooltip">?
						<span class="ccre_tooltiptext ccre_tooltip-top"><?php echo __( 'If the cart amount is greater than this value, then the coupon is triggered in checkout.', 'crypto-coupon-redeem' ); ?></span>
					</div>
				</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Products', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<div class="ccre_enable_products_wrap">
					<input type="checkbox" name="ccre_enable_products" id="ccre_enable_products" value="1" <?php checked( 1, $ccre_enable_products ); ?>>
				</div>
				
				<div class="ccre_products_wrap<?php echo esc_html( $class2 ); ?>">
					<label class="ccre_products_label"><strong><?php echo __( 'Search Products', 'crypto-coupon-redeem' ); ?></strong></label>
					
					<select name="ccre_products[]" id="ccre_products" multiple="multiple">
						<?php
						if( !empty( $ccre_products ) )
						{
							foreach( $ccre_products as $key=>$value ){
								echo '<option value="'.esc_html( $value ).'" selected="selected">'.get_the_title( esc_html( $value ) ).'</option>';
							}
						}
						?>
					</select>

					<label class="ccre_category_label"><strong><?php echo __( 'Search Categories', 'crypto-coupon-redeem' ); ?></strong></label>
					
					<select name="ccre_category[]" id="ccre_category" multiple="multiple">
						<?php
						if( !empty( $ccre_category ) )
						{
							foreach( $ccre_category as $key=>$value ){
								echo '<option value="'.esc_html( $value ).'" selected="selected">'.get_term( esc_html( $value ) )->name.'</option>';
							}
						}
						?>
					</select>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<div class="ccre_update">
	<input type="submit" name="save" id="publish" class="button button-primary button-large" value="<?php echo __( 'Update', 'crypto-coupon-redeem' ); ?>">
</div>