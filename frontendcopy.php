<?php
/**
 * Plugin Name: Front End Copy For Elementor
 * Plugin URI: https://element.how/
 * Description: Front End Copy For Elementor
 * Version: 1.0.0
 * Author: Maxime
 * Author URI: https://element.how
 * Text Domain: front-end-copy-for-elementor
 */

 if( ! defined( 'ABSPATH' ) ) exit();

 /**
 * Elementor Extension main CLass
 * @since 1.0.0
 */
final class FRONT_END_COPY {

    // Plugin version
    const VERSION = '1.0.0';

    // Minimum Elementor Version
    const MINIMUM_ELEMENTOR_VERSION = '3.11.0';

    // Minimum PHP Version
    const MINIMUM_PHP_VERSION = '7.0';

    // Instance
    private static $_instance = null;

    /**
    * Singletone Instance Method
    * @since 1.0.0
    */
    public static function instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
    * Construct Method
    * @since 1.0.0
    */
    public function __construct() {
        // Call Constants Method
        $this->define_constants();
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
    * Define Plugin Constants
    * @since 1.0.0
    */
    public function define_constants() {
        define( 'FRONT_END_COPY_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
        define( 'FRONT_END_COPY_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    }

    /**
    * Load Scripts & Styles
    * @since 1.0.0
    */
    public function scripts_styles() {

        /* no external files needed
        wp_register_style( 'fecfe-style', FRONT_END_COPY_PLUGIN_URL . 'files/dist/css/public.min.css', [], rand(), 'all' );
        wp_enqueue_style( 'fecfe-style' );
        */

    }

    /**
    * Load Text Domain
    * @since 1.0.0
    */
    public function i18n() {
       load_plugin_textdomain( 'front-end-copy-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
    }

    /**
    * Initialize the plugin
    * @since 1.0.0
    */
    public function init() {
        // Check if the ELementor installed and activated
        if( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        if( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        if( ! version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        add_action( 'elementor/init', [ $this, 'init_category' ] );
        add_action( 'elementor/init', [ $this, 'init_extensions' ] );
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
      
    }


    /**
    * Init Widgets
    * @since 1.0.0
    */
    public function init_widgets() {
        
    }
    

    /**
    * Init Extensions
    * @since 1.0.0
    */
    public function init_extensions() {
        require_once FRONT_END_COPY_PLUGIN_PATH . '/extensions/frontendcopy.php';
    }



    /**
    * Init Category Section
    * @since 1.0.0
    */
    public function init_category() {
        Elementor\Plugin::instance()->elements_manager->add_category(
            'front-end-copy-for-elementor',
            [
                'title' => 'Front End Copy For Elementor',
            ],
            1
        );
    }

    /**
    * Admin Notice
    * Warning when the site doesn't have Elementor installed or activated
    * @since 1.0.0
    */
    public function admin_notice_missing_main_plugin() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated', 'front-end-copy-for-elementor' ),
            '<strong>'.esc_html__( 'Front End Copy For Elementor', 'front-end-copy-for-elementor' ).'</strong>',
            '<strong>'.esc_html__( 'Elementor', 'front-end-copy-for-elementor' ).'</strong>'
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin Notice
    * Warning when the site doesn't have the minimum required Elementor version.
    * @since 1.0.0
    */
    public function admin_notice_minimum_elementor_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater', 'front-end-copy-for-elementor' ),
            '<strong>'.esc_html__( 'Front End Copy For Elementor', 'front-end-copy-for-elementor' ).'</strong>',
            '<strong>'.esc_html__( 'Elementor', 'front-end-copy-for-elementor' ).'</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin Notice
    * Warning when the site doesn't have the minimum required PHP version.
    * @since 1.0.0
    */
    public function admin_notice_minimum_php_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater', 'front-end-copy-for-elementor' ),
            '<strong>'.esc_html__( 'Front End Copy For Elementor', 'front-end-copy-for-elementor' ).'</strong>',
            '<strong>'.esc_html__( 'PHP', 'front-end-copy-for-elementor' ).'</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    

}

FRONT_END_COPY::instance();