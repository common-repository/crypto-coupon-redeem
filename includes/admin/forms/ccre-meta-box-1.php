<?php
// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) exit;

global $post;
	
$uuid 					  = get_post_meta( $post->ID, 'uuid', true );
$language 				  = get_post_meta( $post->ID, 'language', true );
$baseline 				  = get_post_meta( $post->ID, 'baseline', true );
$description 			  = get_post_meta( $post->ID, 'description', true );
$quantity 				  = get_post_meta( $post->ID, 'quantity', true );
$total_value 			  = get_post_meta( $post->ID, 'total_value', true );
$currency 				  = get_post_meta( $post->ID, 'currency', true );
$redeemed_coupons 		  = get_post_meta( $post->ID, 'redeemed_coupons', true );
$remaining_coupons 		  = get_post_meta( $post->ID, 'remaining_coupons', true );
$type 					  = get_post_meta( $post->ID, 'type', true );
$delay_before_exportation = get_post_meta( $post->ID, 'delay_before_exportation', true );
$starts_at 				  = get_post_meta( $post->ID, 'starts_at', true );
$expires_at 			  = get_post_meta( $post->ID, 'expires_at', true );
?>

<?php
if( $remaining_coupons == 0 )
{
?>
	<div class="ccre_mb2_alert">
		<?php echo __( 'Warning: The campaign has no more coupon.', 'crypto-coupon-redeem' ); ?>
	</div>
<?php
}
?>

<table class="form-table ccre_campaign_details">
	<tbody>
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'ID', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $post->ID ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'UUID', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $uuid ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Language', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $language ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Name', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $post->post_title ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Baseline', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $baseline ); ?>
			</td>
		</tr>
		
		<!--<tr>
			<th scope="row">
				<label><strong><?php //echo __( 'Description', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php //echo $description; ?>
			</td>
		</tr>-->
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Quantity', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $quantity ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Total value', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $total_value ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Currency', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $currency ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Number of redeemed coupons', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $redeemed_coupons ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Number of remaining coupons', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $remaining_coupons ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Type', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $type ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Delay before exportation', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $delay_before_exportation ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Start at', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $starts_at ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Expires at', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
				<?php echo esc_html( $expires_at ); ?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label><strong><?php echo __( 'Credit message', 'crypto-coupon-redeem' ); ?></strong></label>
			</th>
			<td>
			</td>
		</tr>
	</tbody>
</table>