<?php

class USIN_Woocommerce_Query{

	protected $order_post_type;
	protected $has_ordered_join_applied = false;
	protected $has_order_status_join_applied = false;

	public function __construct($order_post_type){
		$this->order_post_type = $order_post_type;
	}

	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_query_filter', array($this, 'apply_filters'), 10, 2);
		add_filter('usin_custom_select', array($this, 'filter_query_select'), 10, 2);
	}

	public function filter_db_map($db_map){
		$db_map['order_num'] = array('db_ref'=>'order_num', 'db_table'=>'orders', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['has_ordered'] = array('db_ref'=>'', 'db_table'=>'postmeta', 'no_select'=>true);
		$db_map['has_order_status'] = array('db_ref'=>'', 'db_table'=>'orders', 'no_select'=>true);
		$db_map['last_order'] = array('db_ref'=>'last_order', 'db_table'=>'orders', 'nulls_last'=>true, 'cast'=>'DATETIME');
		$db_map['lifetime_value'] = array('db_ref'=>'value', 'db_table'=>'lifetime_values', 'null_to_zero'=>true, 'custom_select'=>true, 'cast'=>'DECIMAL', 'set_alias'=>true);
		return $db_map;
	}
	
	public function filter_query_select($query_select, $field){
		if($field == 'lifetime_value'){
			$query_select='CAST(IFNULL(lifetime_values.value, 0) AS DECIMAL(10,2))';
		}
		return $query_select;
	}

	public function filter_query_joins($query_joins, $table){
		global $wpdb;

		if($table === 'orders'){
			$query_joins .= " LEFT JOIN (SELECT count(ID) as order_num, MAX(post_date) as last_order, $wpdb->postmeta.meta_value as user_id FROM $wpdb->posts".
				" INNER JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id".
				" WHERE $wpdb->postmeta.meta_key = '_customer_user' AND $wpdb->posts.post_type = '$this->order_post_type'";
				
			$allowed_statuses = USIN_Helper::get_allowed_post_statuses('sql_string');
			if(!empty($allowed_statuses)){
				$query_joins .= " AND $wpdb->posts.post_status IN ($allowed_statuses)";
			}
			$query_joins .=" GROUP BY user_id) as orders ON $wpdb->users.ID = orders.user_id";
		}elseif ($table === 'lifetime_values') {
			$query_joins .= " LEFT JOIN (
				SELECT SUM(meta2.meta_value) AS value, meta.meta_value AS user_id
				FROM $wpdb->posts as posts
				LEFT JOIN $wpdb->postmeta AS meta ON posts.ID = meta.post_id
				LEFT JOIN $wpdb->postmeta AS meta2 ON posts.ID = meta2.post_id
				WHERE   meta.meta_key       = '_customer_user'
				AND     posts.post_type     = '$this->order_post_type'
				AND     posts.post_status   IN ( 'wc-completed', 'wc-processing' )
				AND     meta2.meta_key      = '_order_total'
				GROUP BY meta.meta_value) 
				AS lifetime_values ON $wpdb->users.ID = lifetime_values.user_id";
		}

		return $query_joins;
	}



	public function apply_filters($custom_query_data, $filter){

		
		if(in_array($filter->operator, array('include', 'exclude'))){
			global $wpdb;
			
			$operator = $filter->operator == 'include' ? '>' : '=';

			if($filter->by == 'has_ordered'){
				
				if(!$this->has_ordered_join_applied){
					//apply the joins only once, even when this type of filter is applied multiple times
					$custom_query_data['joins'] .= 
						" INNER JOIN $wpdb->postmeta AS wpm ON $wpdb->users.ID = wpm.meta_value".
						" INNER JOIN $wpdb->posts AS woop ON wpm.post_id = woop.ID".
						" INNER JOIN ".$wpdb->prefix."woocommerce_order_items AS woi ON woop.ID =  woi.order_id".
						" INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id";

					$this->has_ordered_join_applied = true;
				}
				

				$custom_query_data['where'] = " AND wpm.meta_key = '_customer_user' AND woim.meta_key = '_product_id'";

				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(woim.meta_value IN (%d)) $operator 0", $filter->condition);


			}elseif($filter->by == 'has_order_status'){

				if(!$this->has_order_status_join_applied){
					//apply the joins only once, even when this type of filter is applied multiple times
					$custom_query_data['joins'] .=
						" INNER JOIN $wpdb->postmeta AS wsm ON $wpdb->users.ID = wsm.meta_value".
						" INNER JOIN $wpdb->posts AS wsp ON wsm.post_id = wsp.ID";

					$this->has_order_status_join_applied = true;
				}


				$custom_query_data['where'] = " AND wsm.meta_key = '_customer_user'";

				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(wsp.post_status IN (%s)) $operator 0", $filter->condition);
			
			}
		}

		return $custom_query_data;
	}

}