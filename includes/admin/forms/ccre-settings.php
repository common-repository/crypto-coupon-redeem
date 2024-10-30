<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $ccre_options;

//check settings updated or not
if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) {
	
	echo '<div class="updated" id="message" style="margin-left:0px; margin-top:20px;">
		<p><strong>'. __( "Changes Saved Successfully.", 'crypto-coupon-redeem' ) .'</strong></p>
	</div>';
}
?>

<div class="wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'ccre_settings' );
		?>
		
		<div class="post-box-container ccre-settings">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="settings" class="postbox">
						<div class="handlediv" title="<?php echo __( 'Click to toggle', 'crypto-coupon-redeem' ) ?>"><br /></div>			
							
						<h3 class="hndle">					
							<span style="vertical-align: top;"><?php echo __( 'Settings', 'crypto-coupon-redeem' ) ?></span>
						</h3>

						<div class="inside">
							<table class="form-table"> 
								<tbody>
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'API End Point', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<input type="text" name="ccre_options[api_end_point]" value="<?php echo esc_html( $ccre_options['api_end_point'] ); ?>" class="regular-text">
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'API Token', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<input type="text" name="ccre_options[api_token]" value="<?php echo esc_html( $ccre_options['api_token'] ); ?>" class="regular-text">
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'WooCommerce', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<?php
											if( class_exists( 'WooCommerce' ) )
											{
												$products = get_posts( array( 'post_type' => 'product' ) );
											?>
												<select class="regular-text">
													<option><?php echo __( 'Products', 'crypto-coupon-redeem' ); ?></option>
													
													<?php
													if( !empty( $products ) )
													{
														foreach( $products as $prk=>$prv ){
															echo '<option>'.esc_html( $prv->post_title ).'</option>';
														}
													}
													?>
												</select>
												
												<a class="ccre_view_all_pro" href="<?php echo get_admin_url().'edit.php?post_type=product'; ?>"><?php echo __( 'View All', 'crypto-coupon-redeem' ) ?></a>
											<?php
											}
											else
											{
												echo '<div class="ccre_alert">'.__( 'Please install WooCommerce.', 'crypto-coupon-redeem' ).'</div>';
											}
											?>
										</td>
									</tr>

                                    <!-- Added By Parth Start -->
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Cart/Checkout Icon','crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<input type="text" id="ccre_logo" name="ccre_options[ccre_logo]" value="<?php echo esc_url( $ccre_options['ccre_logo'] ); ?>" size="63" />
											
											<input type="button" class="button-secondary ccre_logo_uploader" id="ccre_logo_uploader" name="ccre_logo_uploader" value="<?php echo __( 'Choose Icon','crypto-coupon-redeem' ); ?>">
											<br />
											

											<?php
											if ( isset( $ccre_options['ccre_logo'] ) ) 
											{
												?>
													<div class="ccre_options_logo_class" id="ccre_options_logo"><img class="ccre_img_show" src="<?php echo esc_url( $ccre_options['ccre_logo'] ); ?>" alt=""></div>
												<?php
											}
											?>
											
										</td>
									</tr>
									<!-- Added By Parth End  -->

									<tr>
										<td colspan="2" class="ccre_options_save_wrap">
											<input type="submit" class="button-primary" name="ccre_options_save" value="<?php echo __( 'Save Changes', 'crypto-coupon-redeem' ) ?>" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="post-box-container ccre-settings">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="settings" class="postbox">
						<div class="handlediv" title="<?php echo __( 'Click to toggle', 'crypto-coupon-redeem' ) ?>"><br /></div>			
							
						<h3 class="hndle">					
							<span style="vertical-align: top;"><?php echo __( 'Language Labels', 'crypto-coupon-redeem' ) ?></span>
						</h3>

						<div class="inside">
							<table class="form-table"> 
								<tbody>
									<tr>
										<th scope="row">
											<label style="text-decoration:underline; font-size:16px;"><strong><?php echo __( 'French', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message body', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmb_fr]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmb_fr'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message popup', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmp_fr]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmp_fr'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Email thank message', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_etm_fr]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_etm_fr'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label style="text-decoration:underline; font-size:16px;"><strong><?php echo __( 'English', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message body', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmb_en]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmb_en'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message popup', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmp_en]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmp_en'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Email thank message', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_etm_en]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_etm_en'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label style="text-decoration:underline; font-size:16px;"><strong><?php echo __( 'Italian', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message body', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmb_it]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmb_it'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message popup', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmp_it]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmp_it'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Email thank message', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_etm_it]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_etm_it'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label style="text-decoration:underline; font-size:16px;"><strong><?php echo __( 'German', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message body', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmb_de]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmb_de'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Checkout message popup', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_cmp_de]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_cmp_de'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<th scope="row">
											<label><strong><?php echo __( 'Email thank message', 'crypto-coupon-redeem' ) ?></strong></label>
										</th>
										<td>
											<textarea name="ccre_options[ccre_etm_de]" class="regular-text"><?php echo esc_textarea( $ccre_options['ccre_etm_de'] ); ?></textarea>
										</td>
									</tr>
									
									<tr>
										<td colspan="2" class="ccre_options_save_wrap">
											<input type="submit" class="button-primary" name="ccre_options_save" value="<?php echo __( 'Save Changes', 'crypto-coupon-redeem' ) ?>" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>