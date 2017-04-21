<?php
define('ADPR_META_NONCE', 'adpr_meta_nonce');
class AdprMetaBoxes {
	
	public function __construct() {
		add_action('edit_form_after_title', array($this, 'adpr_move_above_content'));
        add_action('save_post', array( $this, 'adpr_save_post_meta' ) );
	}

	public function adpr_move_above_content() {
		global $post;
		if($post->post_type == 'post'){
            $this->adpr_rating_post_meta_callback( $post );
        }
	}
	
	public function adpr_rating_post_meta_callback( $post ) {
		wp_nonce_field(basename(__FILE__), ADPR_META_NONCE);
		$adpr_stored_page_meta = get_post_meta($post->ID);
		$dif_ratings = array('1','2','3','4','5','6','7','8','9','10');
		$fields = array(
			array(
				'type' => 'select',
				'name' => 'adpr_select_rating',
				'id' => 'adpr_select_rating',
				'options' => $dif_ratings,
				'meta_id' => $adpr_stored_page_meta,
				'label' => __('Rating', ADPR_TEXT_DOMAIN),
				'description' => 'Select Difficulty'
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
				'label' => __('$', ADPR_TEXT_DOMAIN),
				'description' => 'Enter estimated material cost of the project.'
			)
		);
		AdprMetaBoxHelper::adpr_do_meta_fields($fields);
	}

	public function adpr_save_post_meta( $post_id ) {
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[NRW_PAGE_NONCE] ) && wp_verify_nonce( $_POST[NRW_PAGE_NONCE], basename(__FILE__) ) ) ? 'true' : 'false';
        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }
        if (isset($_POST['adpr_select_rating'])) {
            update_post_meta( $post_id, 'adpr_select_rating', sanitize_text_field($_POST['adpr_select_rating'] ) );
        }
        if (isset($_POST['adpr_hours'])) {
            update_post_meta( $post_id, 'adpr_hours', sanitize_text_field($_POST['adpr_hours'] ) );
        }
        if (isset($_POST['adpr_minutes'])) {
            update_post_meta( $post_id, 'adpr_minutes', sanitize_text_field($_POST['adpr_minutes'] ) );
        }
        if (isset($_POST['adpr_cost_estimate'])) {
            update_post_meta( $post_id, 'adpr_cost_estimate', sanitize_text_field($_POST['adpr_cost_estimate'] ) );
        }
    }
	
}
if( is_admin() )
	$page_meta = new AdprMetaBoxes();