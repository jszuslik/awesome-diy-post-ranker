<?php
class AdprDisplay {

    public function __construct() {
        add_filter( 'the_content', array( $this, 'adpr_display_before_content'), 20 );
    }

    function adpr_display_before_content( $content ) {
        global $post;
        if(is_single()) {
            $adpr_stored_meta = get_post_meta($post->ID);
            if(isset($adpr_stored_meta['adpr_select_rating'][0]) || $adpr_stored_meta['adpr_hours'][0] != '' || $adpr_stored_meta['adpr_minutes'][0] != '' || $adpr_stored_meta['adpr_cost_estimate'][0] != ''){
                $adpr_meta = '<div class="adpr-display">';
                if(isset($adpr_stored_meta['adpr_select_rating'][0])) {
                    $adpr_meta .= '<p><strong>Difficulty: </strong>'. $adpr_stored_meta['adpr_select_rating'][0] . '</p>';
                }
                if($adpr_stored_meta['adpr_hours'][0] != '' && $adpr_stored_meta['adpr_minutes'][0] != '') {
                    $adpr_meta .= '<p><strong>Estimated Time: </strong>'. $adpr_stored_meta['adpr_hours'][0] . ' hrs '. $adpr_stored_meta['adpr_minutes'][0] .' mins</p>';
                } elseif($adpr_stored_meta['adpr_hours'][0] != '') {
                    $adpr_meta .= '<p><strong>Estimated Time: </strong>'. $adpr_stored_meta['adpr_hours'][0] . ' hrs</p>';
                } elseif($adpr_stored_meta['adpr_minutes'][0] != '') {
                    $adpr_meta .= '<p><strong>Estimated Time: </strong>'. $adpr_stored_meta['adpr_minutes'][0] . ' mins</p>';
                }
                if($adpr_stored_meta['adpr_cost_estimate'][0] != '') {
                    $adpr_meta .= '<p><strong>Estimated Cost: </strong>$'. $adpr_stored_meta['adpr_cost_estimate'][0] . '</p>';
                }
                $adpr_meta .= '</div>';

                $adpr_meta .= $content;

                return $adpr_meta;
            } else {
                return $content;
            }
        }

    }

}
$display_meta = new AdprDisplay();