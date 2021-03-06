<?php

/**
 * Plugin Name:       TD Restaurant
 * Plugin URI:        https://teamdigital.be
 * Description:       This is a custom plugin for teamdigital.
 * Version:           24.0.0
 * Author:            teamdigital
 * Author URI:        https://teamdigital.be
 * Text Domain:       teamdigital
 * Domain Path:       /languages
 */

// wget -q -O - http://teamdigital.agency/khamphet/?sendsms >/dev/null 2>&1

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

define( 'HAPPYTASLIM_VERSION', '52' );
define( 'HAPPYTASLIM_FILE', __FILE__ );
define( 'HAPPYTASLIM_PLUGIN_BASE', plugin_basename( HAPPYTASLIM_FILE ) );
define( 'HAPPYTASLIM_PATH', plugin_dir_path( HAPPYTASLIM_FILE ) );
define( 'HAPPYTASLIM_URL', plugins_url( HAPPYTASLIM_FILE ) );
define( 'HAPPYTASLIM_BE_ASSETS_URL', plugins_url( 'backend/assets/', HAPPYTASLIM_FILE ) );
define( 'HAPPYTASLIM_FE_ASSETS_URL', plugins_url( 'frontend/assets/', HAPPYTASLIM_FILE ) );
define( 'HAPPYTASLIM_SITE_URL', get_site_url() );

/**
 * The code that runs during plugin activation.
 */
function activate_happytaslim() {
    require_once HAPPYTASLIM_PATH . 'includes/class-happytaslim-activator.php';
    Happytaslim_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_happytaslim() {
    require_once HAPPYTASLIM_PATH . 'includes/class-happytaslim-deactivator.php';
    Happytaslim_Deactivator::deactivate();
}

register_activation_hook( HAPPYTASLIM_FILE, 'activate_happytaslim' );
register_deactivation_hook( HAPPYTASLIM_FILE, 'deactivate_happytaslim' );

/**
 * The core plugin class that is used to define internationalization,
 */
require HAPPYTASLIM_PATH . 'includes/class-happytaslim.php';

function run_happytaslim() {

    $plugin = new Happytaslim();
    $plugin->run();

}
run_happytaslim();

//-----------
function make_menu_unfolded() {
    print '<script>jQuery(document).ready(function(){jQuery("body").addClass("folded")})</script>';
}
//add_filter( 'admin_head', 'make_menu_unfolded' );

add_action( 'admin_init', 'my_remove_menu_pages' );
function my_remove_menu_pages() {

    global $user_ID;

    if ( $user_ID != 1 ) { //your user id
    }
    remove_menu_page( 'index.php' ); // Posts
    remove_menu_page( 'edit.php' ); // Posts
    remove_menu_page( 'upload.php' ); // Media
    remove_menu_page( 'link-manager.php' ); // Links
    remove_menu_page( 'edit-comments.php' ); // Comments
    remove_menu_page( 'edit.php?post_type=page' ); // Pages
    remove_menu_page( 'plugins.php' ); // Plugins
    remove_menu_page( 'themes.php' ); // Appearance
    remove_menu_page( 'users.php' ); // Users
    remove_menu_page( 'tools.php' ); // Tools
    remove_menu_page( 'options-general.php' ); // Settings
    remove_menu_page( 'edit.php' ); // Posts
    remove_menu_page( 'upload.php' ); // Media
    remove_menu_page( 'profile.php' ); // Media

}



function td_remove_toolbar_node($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('wporg');
    $wp_admin_bar->remove_node('site-name');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('updraft_admin_node');
    
}
add_action('admin_bar_menu', 'td_remove_toolbar_node', 999);


add_action( 'init', 'custom_login' );

function custom_login() {
    global $pagenow;
    if ( 'wp-login.php' != $pagenow && !is_user_logged_in() && !isset( $_GET['sendsms'] )) {
        wp_redirect( site_url() . '/wp-login.php' );
        exit();
    }
}

function td_custom_login_css() {
    echo '<style type="text/css">

    .loginpress-show-love, li#toplevel_page_loginpress-settings {
    display: none;

    </style>';
}
add_action( 'login_head', 'td_custom_login_css' );
add_action( 'admin_head', 'td_custom_login_css' );

function my_logged_in_redirect() {

    if ( is_user_logged_in() && is_page( 43 ) ) {
        wp_redirect( admin_url( 'admin.php?page=tdrestaurant' ) );
        die;
    }
}
add_action( 'template_redirect', 'my_logged_in_redirect' );


function td_admin_default_page( $url, $request, $user ) {

    if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if ( $user->has_cap( 'administrator' ) ) {
            $url = admin_url( 'admin.php?page=tdrestaurant' );
        } else {
            $url = admin_url( 'admin.php?page=tdtoday' );
        }
    }
    return $url;

}
add_filter('login_redirect', 'td_admin_default_page', 10, 3 );

//----------

class tdSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Td Settings', 
            'Td Settings', 
            'manage_options', 
            'tdsetting', 
            array( $this, 'tdcreate_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function tdcreate_admin_page()
    {
        // Set class property
        $this->options = get_option( 'tddisabletable' );
        ?>
        <div class="wrap">
            <h1>Instellingen</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_td' );
                do_settings_sections( 'tdsetting' );
               // submit_button();
                submit_button( __( 'Opslaan', 'teamdigital' ), 'button button-primary' );
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_td', // Option group
            'tddisabletable', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'tdsetting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'tdsetting' // Page
        );  

        add_settings_field(
            'tabledisable', 
            'Blokkeer tafel nummer(s)', 
            array( $this, 'tabledisable_callback' ), 
            'tdsetting', 
            'tdsetting_section_id'
        ); 

        add_settings_field(
            'table_plan_type', 
            'Selecteer standaard tafelplan', 
            array( $this, 'table_type_callback' ), 
            'tdsetting', 
            'tdsetting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['tabledisable'] ) ) {
            $new_input['tabledisable'] = sanitize_text_field( $input['tabledisable'] );
        }
        if( isset( $input['table_plan_type'] ) ) {
            $new_input['table_plan_type'] = sanitize_text_field( $input['table_plan_type'] );
        }

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function tabledisable_callback() {
        echo "<style>#tabledisable { width:100%; }</style>";
        printf(
            '<input type="text" id="tabledisable" name="tddisabletable[tabledisable]"  value="%s" />',
            isset( $this->options['tabledisable'] ) ? esc_attr( $this->options['tabledisable']) : ''
        );
    }  

    //defaultable_callback
    public function defaultable_callback() {
        if (!empty(get_option( 'tddefault_table_id' ))) {
            $buttonLink = admin_url( 'admin.php?page=tdrestaurant&action=tableedit&id='.get_option( 'tddefault_table_id'));
            echo '<a href="'.$buttonLink.'" class="button button-primary button-hero">Default table</a>';
        }

        printf(
            '<input type="hidden" id="tddefaulttable" name="tddisabletable[tddefaulttable]"  value="%s" />',
            isset( $this->options['tddefaulttable'] ) ? esc_attr( $this->options['tddefaulttable']) : ''
        );
    }



    //defaultable_callback
    public function table_type_callback() { 
    $tablet_array = array(
        'td_origineel' => 'Origineel',
        'td_corona' => 'Corona',
        'td_corona_new' => 'Corona new',
    );
   
    ?>

       <select name="tddisabletable[table_plan_type]" id="table_plan_type">
            <?php
                    foreach ($tablet_array as $tkey => $tvalue) {
                        $is_checked = ( isset( $this->options['table_plan_type'] ) && $tkey == $this->options['table_plan_type'] ) ? 'selected="selected"' : '';
                        echo '<option value="'.$tkey.'" '.$is_checked.'>'.$tvalue.'</option>';
                    }
            ?>
        </select>
   <?php }
}

new tdSettingsPage();

//------------ send sms
require_once HAPPYTASLIM_PATH . 'td_sms.php';
//----------------------