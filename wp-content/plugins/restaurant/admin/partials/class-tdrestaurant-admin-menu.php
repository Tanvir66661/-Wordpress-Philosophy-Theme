<?php

/**
 * Admin Menu
 */
class tdrestaurant_admin_menu {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {

        $parent_slug = 'tdrestaurant';
        $capability = 'manage_options';
        $capability_staff = 'publish_posts';

        /** Top Menu **/
        add_menu_page( __( 'Tdrestaurant', 'tdrestaurant' ), __( 'Tdrestaurant', 'tdrestaurant' ), $capability, $parent_slug, array( $this, 'plugin_page' ), 'dashicons-plugins-checked', 2 );

        add_submenu_page( $parent_slug, __( 'Nieuwe reservatie', 'tdrestaurant' ), __( 'Nieuwe reservatie', 'tdrestaurant' ), $capability, $parent_slug, array( $this, 'plugin_page' ) );

        add_menu_page( __( 'Overzicht reservaties', 'tdrestaurant' ), __( 'Overzicht reservaties', 'tdrestaurant' ), $capability, 'tdappointments', array( $this, 'appointments_plugin_page' ), 'dashicons-plugins-checked', 3 );

        add_menu_page( __( 'Overzicht bezetting', 'tdrestaurant' ), __( 'Overzicht bezetting', 'tdrestaurant' ), $capability_staff, 'tdtoday', array( $this, 'today_plan' ), 'dashicons-plugins-checked', 4 );
       
        add_menu_page( __( 'Instellingen', 'tdrestaurant' ), __( 'Instellingen', 'tdrestaurant' ), $capability, 'options-general.php?page=tdsetting', '', 'dashicons-plugins-checked', 5 );

        //  add_submenu_page( $parent_slug, __( 'Overzicht reservaties', 'tdrestaurant' ), __( 'Overzicht reservaties', 'tdrestaurant' ), $capability, 'tdappointments', array( $this, 'appointments_plugin_page' ) );

        // add_submenu_page( $parent_slug, __( 'Overzicht bezetting', 'tdrestaurant' ), __( 'Overzicht bezetting', 'tdrestaurant' ), $capability_staff, 'tdtoday', array( $this, 'today_plan' ) );

        //add_submenu_page( $parent_slug, __( 'Lijst klanten', 'tdrestaurant' ), __( 'Lijst klanten', 'tdrestaurant' ), $capability, 'tdcustomerlist', array( $this, 'customer_plugin_page' ) );

        // add_submenu_page( $parent_slug, __( 'Tables', 'tdrestaurant' ), __( 'Tables', 'tdrestaurant' ), $capability, 'tdtable', array( $this, 'table_admin_page' ) );
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {
        case 'view':

            $template = dirname( __FILE__ ) . '/views/tdrestaurant-single.php';
            break;

        case 'edit':
            $template = dirname( __FILE__ ) . '/views/tdrestaurant-edit.php';
            break;

        case 'new':
            $template = dirname( __FILE__ ) . '/views/tdrestaurant-new.php';
            break;

        case 'list':
            $template = dirname( __FILE__ ) . '/views/tdrestaurant-list.php';
            break;

        case 'tableedit':
            $template = dirname( __FILE__ ) . '/tables/table-edit.php';
            break;

        default:
            // $template = dirname( __FILE__ ) . '/views/tdrestaurant-list.php';
            $template = dirname( __FILE__ ) . '/views/tdrestaurant-design.php';
            break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function appointments_plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {

        case 'edit':
            $template = dirname( __FILE__ ) . '/views/tdrestaurant-edit.php';
            break;

        case 'list':
            $template = dirname( __FILE__ ) . '/views/appointments-list.php';
            break;

        default:
            $template = dirname( __FILE__ ) . '/views/appointments-list.php';
            break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function customer_plugin_page() {

        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {

        case 'edit':
            $template = dirname( __FILE__ ) . '/views/customer-edit.php';
            break;

        case 'list':
            $template = dirname( __FILE__ ) . '/views/tdcustomer-list.php';
            break;

        default:
            $template = dirname( __FILE__ ) . '/views/tdcustomer-list.php';
            break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function table_admin_page() {

        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {

        case 'edit':
            $template = dirname( __FILE__ ) . '/tables/table-edit.php';
            break;

        case 'list':
            $template = dirname( __FILE__ ) . '/tables/table-list.php';
            break;

        default:
            $template = dirname( __FILE__ ) . '/tables/table-list.php';
            break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function today_plan() {

        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {

        case 'list':
            $template = dirname( __FILE__ ) . '/today/list.php';
            break;

        default:
            echo $template = dirname( __FILE__ ) . '/today/list.php';
            break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }


    public function td_settings_callback() {

    // wp_redirect( home_url() );
    //         exit();

    }
}

