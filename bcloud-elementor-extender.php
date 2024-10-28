<?php
/**
 * Plugin Name: BCloud Elementor Form Extender
 * Description: Plugin to extend Elementor Form to allow users to add or edit post or custom posts from frontend. Adds Range & Calculated fields.
 * Requires at least: 5.0
 * Plugin URI:  https://blue-cloud.io/
 * Version:     1.3.0
 * License:     GPL-2.0-or-later
 * Author:      Salil Agarwal
 * Author URI:  https://blue-cloud.io
 * Text Domain: bcloud-elementor-extender
 *
 * @package     BCloud_Elementor_Form_Extender
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'BCLOUD_ELEMENTOR_EXTENDER_URL', plugin_dir_url( __FILE__ ) );


/**
 * Main Elementor New Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Bcloud_Elementor_Extender {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.3.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_New_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_New_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'bcloud-elementor-extender' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Add Plugin actions.
		add_action(
			'elementor_pro/init',
			function () {
				// Here its safe to include our action class file.
				include_once __DIR__ . '/classes/class-bcloud-custom-post-form-action.php';

				// Instantiate the action class.
				$elementor_form_custom_post_action = new Bcloud_Custom_Post_Form_Action();

				// Register the action with form widget.
				\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $elementor_form_custom_post_action->get_name(), $elementor_form_custom_post_action );
			}
		);

		// add custom form fields - Range field.
		add_action(
			'elementor_pro/forms/fields/register',
			function ( $bcloud_field_registrar_manager ) {
				include_once __DIR__ . '/classes/class-bcloud-form-range-field.php';

				$bcloud_field_registrar_manager->register( new Bcloud_Form_Range_Field() );
			}
		);

		// add custom form fields - Calculator field.
		add_action(
			'elementor_pro/forms/fields/register',
			function ( $bcloud_field_registrar_manager ) {
				include_once __DIR__ . '/classes/class-bcloud-form-calculator-field.php';

				$bcloud_field_registrar_manager->register( new Bcloud_Form_Calculator_Field() );
			}
		);

		/*
		Commented code
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		*/
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'bcloud-elementor-extender' ),
			'<strong>' . esc_html__( 'BCloud Elementor Form Extender Plugin', 'bcloud-elementor-extender' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor & Elementor Pro', 'bcloud-elementor-extender' ) . '</strong>'
		);

		printf( esc_html( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>' ), esc_html( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bcloud-elementor-extender' ),
			'<strong>' . esc_html__( 'BCloud Elementor Form Extender Plugin', 'bcloud-elementor-extender' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'bcloud-elementor-extender' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( esc_html( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>' ), esc_html( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bcloud-elementor-extender' ),
			'<strong>' . esc_html__( 'BCloud Elementor Form Extender Plugin', 'bcloud-elementor-extender' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'bcloud-elementor-extender' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( esc_html( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>' ), esc_html( $message ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files.
		// require_once( __DIR__ . '/widgets/test-widget.php' );.

		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_New_Widget() );
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls() {

		// Include Control files.
		// require_once( __DIR__ . '/controls/test-control.php' );.

		// Register control.
		\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \New_Control() );
	}


	/**
	 * For Enqueuing styles and scripts
	 */
	public function enqueue_styles_scripts() {
		/*
		Commented code.
		wp_register_script('bcloud-range-js', BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range.js',
		'jquery', "1.1.0", true);
		*/
	}
}

Bcloud_Elementor_Extender::instance();
