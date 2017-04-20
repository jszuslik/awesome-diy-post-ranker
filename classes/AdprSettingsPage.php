<?php
class AdprSettingsPage {

    private $options;
    
    private $screen;

    public function __construct() {
	    add_action('admin_menu', array( $this,'adpr_settings_page' ) );
	    add_action( 'admin_init', array( $this, 'register_adpr_admin_settings' ) );
	    add_action('admin_enqueue_scripts', array( $this, 'adpr_enqueue_scripts' ) );
	    add_action('wp_print_styles', array($this, 'adpr_print_custom_styles'));
    }

    public function adpr_settings_page() {
        add_menu_page(
            'DIY Post Ratings',
            'DIY',
            'manage_options',
            'adpr_settings',
            array($this, 'adpr_functions'),
	        '',
	        6
        );
    }

    public function adpr_functions() {
	    $this->options = get_option( 'adpr_admin_options' ); ?>
	    <div class="wrap">
		    <h1>Awesome DIY Post Ratings</h1>
		    <?php settings_errors(); ?>
		    <form method="post" action="options.php">
			    <?php
			        settings_fields('adpr_admin_group');
			        do_settings_sections('adpr-setting-admin');
			        submit_button();
			    ?>
		    </form>
	    </div>

	<?php
    }

    public function register_adpr_admin_settings()
    {
        register_setting(
            'adpr_admin_group',
            'adpr_admin_options',
            array($this, 'sanitize')
        );
	    add_settings_section(
		    'adpr_style_settings',
		    'DIY Post Rating Styles',
		    array( $this, 'print_section_info' ),
		    'adpr-setting-admin'
	    );
	    add_settings_field(
		    'adpr_bg_color',
		    'DIY Ratings Backround Color',
		    array( $this, 'adpr_bg_settings_field'),
		    'adpr-setting-admin',
		    'adpr_style_settings'
	    );
	    add_settings_field(
		    'adpr_font_color',
		    'DIY Ratings Font Color',
		    array( $this, 'adpr_font_settings_field'),
		    'adpr-setting-admin',
		    'adpr_style_settings'
	    );
	    add_settings_field(
	    	'adpr_css_field',
		    'Add Custom CSS to DIY Ratings',
		    array( $this, 'adpr_css_settings_field'),
		    'adpr-setting-admin',
		    'adpr_style_settings'
	    );
    }
	
	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['adpr_bg_color'] ) ) {
			if(!$this->adpr_check_color($input['adpr_bg_color'])) {
				add_settings_error( 'adpr_admin_options', esc_attr( 'adpr_bg_color_error'), __('Insert a valid color for Background', ADPR_TEXT_DOMAIN), 'error' );
				$new_input['adpr_bg_color'] = $this->options['adpr_bg_color'];
			} else {
				$new_input['adpr_bg_color'] = sanitize_text_field( $input['adpr_bg_color'] );
			}
		}
		if( isset( $input['adpr_font_color'] ) ) {
			if(!$this->adpr_check_color($input['adpr_font_color'])) {
				add_settings_error( 'adpr_admin_options', esc_attr( 'adpr_font_color_error'), __('Insert a valid color for Font', ADPR_TEXT_DOMAIN), 'error' );
				$new_input['adpr_font_color'] = $this->options['adpr_font_color'];
			} else {
				$new_input['adpr_font_color'] = sanitize_text_field( $input['adpr_font_color'] );
			}
		}
		if( isset( $input['adpr_css_field'] ) )
			$new_input['adpr_css_field'] = sanitize_text_field( $input['adpr_css_field'] );
		return $new_input;
	}
	
	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Enter your settings below:';
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function adpr_bg_settings_field() {
		printf(
			'<input type="text" id="adpr_bg_color" name="adpr_admin_options[adpr_bg_color]" value="%s" class="adrp-color-field"/>',
            isset( $this->options['adpr_bg_color'] ) ? esc_attr( $this->options['adpr_bg_color']) : ''
		);
	}
	public function adpr_font_settings_field() {
		printf(
			'<input type="text" id="adpr_font_color" name="adpr_admin_options[adpr_font_color]" value="%s" class="adrp-color-field"/>',
			isset( $this->options['adpr_font_color'] ) ? esc_attr( $this->options['adpr_font_color']) : ''
		);
	}
	
	public function adpr_css_settings_field() {
		$style = 'position: absolute;';
		$style .= 'top: 0;';
		$style .= 'right: 0;';
		$style .= 'bottom: 0;';
		$style .= 'left: 0;';
		printf(
			'<pre id="embedded_ace_code" style="height: 406px;" class=" ace_editor ace-tm" draggable="false"><div id="adpr_editor" style="%s"></div></pre><textarea id="adpr_css_field" name="adpr_admin_options[adpr_css_field]" wrap="hard" class="adpr-css-editor">%s</textarea>',
			$style,isset( $this->options['adpr_css_field'] ) ? esc_attr( $this->options['adpr_css_field']) : ''
		);
	}
	
	public function adpr_enqueue_scripts($hook_suffix) {
		if($hook_suffix == 'toplevel_page_adpr_settings') {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_script('adpr_ace', plugins_url('../js/vendor/ace-builds/src-min-noconflict/ace.js', __FILE__), array('jquery'), false, true);
			wp_enqueue_script('adpr_beautify', plugins_url('../js/vendor/cssbeautify.js', __FILE__), array('jquery'), false, true);
			wp_enqueue_script('adpr_settings_scripts', plugins_url('../js/adpr_settings.js', __FILE__), array('jquery'), false, true);
		}
	}
	
	public function adpr_print_custom_styles() {
		if(is_single()) {
			$options = get_option('adpr_admin_options');
			$custom_css = '<style type="text/css">';
			foreach ($options as $key => $value) {
				if ($key == 'adpr_bg_color') {
					$custom_css .= '.adpr-display {background-color:' . $value . ';}';
				} else if ($key == 'adpr_font_color') {
					$custom_css .= '.adpr-display p {color:' . $value . ';}';
				} else {
					$custom_css .= $value;
				}
			}
			$custom_css .= '</style>';
			echo $custom_css;
		}
	}
	
	/**
	 * Function that will check if value is a valid HEX color.
	 */
	public function adpr_check_color( $value ) {
		
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}
		
		return false;
	}
}
$adpr_settings_page = new AdprSettingsPage();