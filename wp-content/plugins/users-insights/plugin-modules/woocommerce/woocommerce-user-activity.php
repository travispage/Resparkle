<?php

class USIN_Woocommerce_User_Activity{

	protected $order_post_type;

	public function __construct($order_post_type){
		$this->order_post_type = $order_post_type;
	}

	public function init(){
		add_filter('usin_user_activity', array($this, 'add_orders_to_user_activity'), 10, 2);
		add_action('pre_get_posts', array($this, 'admin_orders_filter'));
	}
	
	public function add_orders_to_user_activity($activity, $user_id){

		$args = array(
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id,
			'post_type'   => $this->order_post_type,
			'post_status' => 'any',
			'numberposts'=>-1
		);

		$all_orders = get_posts($args);
		$count = sizeof($all_orders);

		$args['numberposts'] = 5;
		$orders = get_posts($args);


		if(!empty($orders)){
			$list = array();
			foreach ($orders as $order) {

				$title = get_the_date( get_option('date_format'), $order->ID);

				if(class_exists('WC_Order')){
					$wc_order = new WC_Order($order->ID);
					
					if(method_exists($wc_order, 'get_status') && function_exists('wc_get_order_status_name')){
						$status = $wc_order->get_status();
						$title .= sprintf('<span class="usin-tag usin-tag-%s">%s</span>',
							$status, wc_get_order_status_name($status));
					}
					
				
					if(method_exists($wc_order, 'get_items')){
						$order_items = $wc_order->get_items();
						$details = array();
						
						if(!empty($order_items) && is_array($order_items)){
							foreach ($order_items as $item ) {
								if(is_array($item)){
									$details[]=$item['name'];
								}elseif(get_class($item) == 'WC_Order_Item_Product' && method_exists($item, 'get_name')){
									$details[]=$item->get_name();
								}
							}
						}
					}
					
				}
				
				
				$order_info=array('title'=>$title, 'link'=>get_edit_post_link( $order->ID, ''));
				if(!empty($details)){
					$order_info['details'] = $details;
				}
				$list[]= $order_info;
			}

			$post_type_data = get_post_type_object($this->order_post_type);

			$activity[] = array(
				'type' => 'order',
				'for' => $this->order_post_type,
				'label' => $count == 1 ? $post_type_data->labels->singular_name : $post_type_data->labels->name,
				'count' => $count,
				'link' => admin_url('edit.php?post_type=shop_order&usin_customer='.$user_id),
				'list' => $list,
				'icon' => 'woocommerce'
			);
		}
		
		return $activity;
	}


	public function admin_orders_filter($query){
		if( is_admin() && isset($_GET['usin_customer']) && $query->get('post_type') == $this->order_post_type){
			$user_id = intval($_GET['usin_customer']);

			if($user_id){
				$query->set('meta_key', '_customer_user');
				$query->set('meta_value', $user_id);
			}
		}
	}
	
}