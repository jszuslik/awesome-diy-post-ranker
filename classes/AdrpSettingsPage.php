<?php
class AdrpSettingsPage {

    private $options;

    public function __construct() {
        add_action('admin_menu', array( $this,'adrp_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_adrp_admin_settings' ) );
    }

    public function adrp_settings_page() {
        add_menu_page(
            'DIY Post Ratings',
            'DIY',
            'manage_options',
            'adpr-settings',
            array( $this, 'adrp_functions')
        );
    }

    public function adrp_functions() {

    }

    public function register_adrp_admin_settings()
    {
        register_setting(
            'adrp_admin_group',
            'adrp_admin_options',
            array($this, 'sanitize')
        );
    }

}
if(is_admin())
    $adrp_settings = new AdrpSettingsPage();