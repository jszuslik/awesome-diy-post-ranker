<?php
define('ADPR_META_NONCE', 'adpr_meta_nonce');
class AdprMetaBoxes {
	
	public function __construct() {
		// add_action('add_meta_boxes', array($this, 'adpr_rating_post_meta'));
		add_action('edit_form_after_title', array($this, 'adpr_move_above_content'));
	}
	
//	public function adpr_rating_post_meta() {
//		add_meta_box( 'adpr_rating_post_meta', __( 'DIY Evaluator', ADPR_TEXT_DOMAIN), array( $this, 'adpr_rating_post_meta_callback'), array('post'), 'normal', 'high');
//	}
	
	public function adpr_move_above_content() {
		global $post;
		$this->adpr_rating_post_meta_callback( $post );
	}
	
	public function adpr_rating_post_meta_callback( $post ) {
		wp_nonce_field(basename(__FILE__), ADPR_META_NONCE);
		$adpr_stored_page_meta = get_post_meta($post->ID);
		$dif_ratings = array('01','02','03','04','05','06','07','08','09','10');
		$fields = array(
			array(
				'type' => 'select',
				'name' => 'adpr_select_rating',
				'id' => 'adpr_select_rating',
				'options' => $dif_ratings,
				'meta_id' => $adpr_stored_page_meta,
				'label' => __('Select Difficulty', ADPR_TEXT_DOMAIN),
				'description' => 'Select the difficulty of the DIY project.'
			),
			array(
				'type' => 'time',
				'name' => 'adpr_time_estimate',
				'id' => 'adpr_time_estimate',
				'meta_id' => $adpr_stored_page_meta,
				'label' => __('Estimated Time', ADPR_TEXT_DOMAIN),
				'description' => ''
			),
			array(
				'type' => 'number',
				'name' => 'adpr_cost_estimate',
				'id' => 'adpr_cost_estimate',
				'meta_id' => $adpr_stored_page_meta,
				'label' => __('Enter Estimated Material Cost', ADPR_TEXT_DOMAIN),
				'description' => 'Enter estimated material cost of the project.'
			)
		);
		AdprMetaBoxHelper::adpr_do_meta_fields($fields);
	}
	
}
if( is_admin() )
	$page_meta = new AdprMetaBoxes();