<?php

class Happytaslim_Admin {

    private $plugin_name;

    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->load_admin_dependencies();
        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function load_admin_dependencies() {
        require_once HAPPYTASLIM_PATH . 'admin/partials/today/todayplan-functions.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/tables/table.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/happy-custom-function.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/happy-taslimajax.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/class-tdrestaurant-admin-menu.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/appointment-functions.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/class-appointment-list-table.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/class-form-handler.php';
        require_once HAPPYTASLIM_PATH . 'admin/partials/functions-appointment.php';

        new tdrestaurant_admin_menu();
        //new Form_Handler();

    }

    public function enqueue_styles( $hook ) {

        if ( 'toplevel_page_tdrestaurant' == $hook || 'toplevel_page_tdappointments' == $hook || 'toplevel_page_tdtable' == $hook || 'toplevel_page_tdtoday' == $hook ) {
            wp_register_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
            wp_enqueue_style( 'jquery-ui' );
        }

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/happytaslim-admin.css', array(), $this->version, 'all' );

    }

    public function enqueue_scripts( $hook ) {

        if ( 'toplevel_page_tdrestaurant' == $hook || 'toplevel_page_tdappointments' == $hook || 'toplevel_page_tdtable' == $hook || 'toplevel_page_tdtoday' == $hook ) {
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
        }

        wp_enqueue_script( 'happytaslim-admin', plugin_dir_url( __FILE__ ) . 'js/happytaslim-admin.js', array( 'jquery' ), $this->version, true );

        wp_localize_script( 'happytaslim-admin', 'taslimAjax', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wd-ac-admin-nonce' ),
            'confirm' => __( 'Bent u zeker dat u deze reservering wenst te verwijderen?', 'teamdigital' ),
            'error'   => __( 'Something went wrong', 'teamdigital' ),
        ] );
    }

}