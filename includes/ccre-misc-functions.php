<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function ccre_send_request($query, $variables){
	
	global $ccre_options;
	
	$access_token = $ccre_options['api_token'];
	$endpoint = $ccre_options['api_end_point'];
	
	$headers = array(
					"Token" => $access_token,
					"Content-Type" => "application/json",
				);
	
	$args = array(
				'method' => 'POST',
        		'headers' => $headers,
        		'httpversion' => '1.0',
        		'sslverify' => false,
        		'body' => json_encode(compact('query', 'variables'))
			);
	
	$post   = wp_remote_post( $endpoint, $args );
	
	$result = json_decode( wp_remote_retrieve_body( $post ), true );
	
	return $result;
}

function ccre_if_campaign_exist( $uuid ){
	
	$args = array(
				'post_type' => CCRE_PT,
				'numberposts' => -1,
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'key' => 'uuid',
						'value' => $uuid
					)
				)
			);
	
	$posts = get_posts( $args );
	
	if( !empty( $posts ) )
	{
		return $posts[0];
	}
	
	return '';
}

function ccre_run_campaing_query( $endpoint, $access_token, $page = 1, $currencies = array( 'CHF' ), $current_campaings = array() ){
	
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
	
	$variables = array('page' => $page, 'currencies' => $currencies );
	$result_obj = ccre_send_request( $query, $variables );
			
		//$result_obj = json_decode( $result, true );
		
		if( isset( $result_obj['data'] ) && !empty( $result_obj['data'] ) && isset( $result_obj['data']['campaigns'] ) && !empty( $result_obj['data']['campaigns'] ) && isset( $result_obj['data']['campaigns']['data'] ) && !empty( $result_obj['data']['campaigns']['data'] ) )
		{
			$data = $result_obj['data']['campaigns']['data'];
			
			foreach( $data as $data_k=>$data_v ){
				
				$post_id = ccre_if_campaign_exist( $data_v['uuid'] );
				
				if( $post_id == '' )
				{
					$args = array(
								'post_type' => CCRE_PT,
								'post_title' => $data_v['name'],
								'post_status' => 'publish',
							);
							
					$post_id = wp_insert_post( $args );
				}
				else
				{
					$args = array(
								'ID' => $post_id,
								'post_type' => CCRE_PT,
								'post_title' => $data_v['name'],
								'post_status' => 'publish',
							);
							
					wp_update_post( $args );
				}
				
				if( !is_wp_error( $post_id ) )
				{
					$current_campaings[] = $post_id;
					
					foreach( $data_v as $data_vk=>$data_vv ){
						
						if( $data_vk != 'coupons' && $data_vk != 'name' )
						{
							update_post_meta( $post_id, $data_vk, $data_vv );
						}
						else if( $data_vk == 'coupons' )
						{
							update_post_meta( $post_id, 'coupons', $data_vv );
							
							update_post_meta( $post_id, 'quantity', count( $data_vv ) );
							
							if( !empty( $data_vv ) )
							{
								update_post_meta( $post_id, 'total_value', count( $data_vv )*$data_vv[0]['amount'] );
								
								update_post_meta( $post_id, 'coupon_amount', $data_vv[0]['amount'] );
							}
							else
							{
								update_post_meta( $post_id, 'total_value', 0 );
								
								update_post_meta( $post_id, 'coupon_amount', 0 );
							}
							
							$redeemed  = 0;
							$remaining = 0;
							
							foreach( $data_vv as $data_vv_k=>$data_vv_v ){
								
								if( $data_vv_v['status'] == 'RESERVED' )
								{
									$redeemed += 1;
								}
								
								if( $data_vv_v['status'] == 'UNUSED' )
								{
									$remaining += 1;
								}
							}
							
							update_post_meta( $post_id, 'redeemed_coupons', $redeemed );
							update_post_meta( $post_id, 'remaining_coupons', $remaining );
						}
					}
				}
			}
		}
		
		if( isset( $result_obj['data'] ) && !empty( $result_obj['data'] ) && isset( $result_obj['data']['campaigns'] ) && !empty( $result_obj['data']['campaigns'] ) && isset( $result_obj['data']['campaigns']['has_more_pages'] ) && $result_obj['data']['campaigns']['has_more_pages'] )
		{
			ccre_run_campaing_query( $endpoint, $access_token, $page+1, $current_campaings );
		}
		else
		{
			// Delete campaigns
			$posts = get_posts( array( 'post_type' => CCRE_PT, 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'publish' ) );
			
			$diff = array_diff( $posts, $current_campaings );
		
			if( !empty( $diff ) )
			{
				foreach( $diff as $diff_k=>$diff_v ){
					wp_delete_post( $diff_v, true );
				}
			}
		}
	
}
?>