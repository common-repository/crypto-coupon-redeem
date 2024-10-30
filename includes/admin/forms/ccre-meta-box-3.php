<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$coupons = get_post_meta( $post->ID, 'coupons', true );
?>

<table class="wp-list-table widefat striped">
	<thead>
		<tr>
			<th>
				<?php echo __( 'UUID', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'Comment', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'QR code', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'Amount', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'Currency', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'Status', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th>
				<?php echo __( 'Email of the buyer', 'crypto-coupon-redeem' ); ?>
			</th>
			
			<th style="text-align:center;">
				<?php echo __( 'Action', 'crypto-coupon-redeem' ); ?>
			</th>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if( !empty( $coupons ) )
		{
			foreach( $coupons as $key=>$value ){
		?>
				<tr>
					<td>
						<?php echo esc_html( $value['uuid'] ); ?>
					</td>
					
					<td>
						<?php echo esc_html( $value['comment'] ); ?>
					</td>
					
					<td  class="ccre_reserved">
						<?php
						if( $value['status'] == 'RESERVED' )
						{
							echo '<img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.esc_url( $value['scan_url'] ).'&choe=UTF-8" />';
						}
						?>
					</td>
					
					<td>
						<?php echo esc_html( $value['amount'] ); ?>
					</td>
					
					<td>
						<?php echo esc_html( $value['currency'] ); ?>
					</td>
					
					<td class="ccre_status">
						<?php echo esc_html( $value['status'] ); ?>
					</td>
					
					<td class="ccre_reserved">
						<?php
						if( $value['status'] == 'RESERVED' || $value['status'] == 'EXPORTED' )
						{
							echo esc_html( $value['reserved_to'] );
						}
						?>
					</td>
					
					<td style="text-align:center;">
						<?php
						if( $value['status'] == 'RESERVED' )
						{
						?>
							<a class="ccre_refund" data-uuid="<?php echo esc_html( $value['uuid'] ); ?>" data-camp="<?php echo esc_html( $post->ID ); ?>">
								<?php echo __( 'Refund', 'crypto-coupon-redeem' ); ?>
							</a>
							
							<img src="<?php echo CCRE_INC_URL.'/images/ccre-loader.gif'; ?>" class="ccre_loader">
						<?php
						}
						else if( $value['status'] == 'BURNED' )
						{
							echo __( 'Refunded', 'crypto-coupon-redeem' );
						}
						?>
					</td>
				</tr>
		<?php
			}
		}
		else
		{
		?>
			<tr>
				<td colspan="8" style="text-align:center;">
					<?php echo __( 'No coupon found.', 'crypto-coupon-redeem' ); ?>
				</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>