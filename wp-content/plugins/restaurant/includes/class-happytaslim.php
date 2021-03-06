<?php

class Happytaslim {

    protected $loader;

    protected $plugin_name;

    protected $version;

    public function __construct() {

        $this->version = HAPPYTASLIM_VERSION;
        $this->plugin_name = 'happytaslim';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    private function load_dependencies() {

        require_once HAPPYTASLIM_PATH . 'includes/class-happytaslim-loader.php';
        require_once HAPPYTASLIM_PATH . 'includes/class-happytaslim-i18n.php';
        require_once HAPPYTASLIM_PATH . 'admin/class-happytaslim-admin.php';
        require_once HAPPYTASLIM_PATH . 'public/class-happytaslim-public.php';

        $this->loader = new Happytaslim_Loader();

    }

    private function set_locale() {

        $plugin_i18n = new Happytaslim_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    private function define_admin_hooks() {

        $plugin_admin = new Happytaslim_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

    }

    private function define_public_hooks() {

        $plugin_public = new Happytaslim_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

}
