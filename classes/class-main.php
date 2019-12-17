<?php
/**
 * @package dark-mode-for-elementor
 */
namespace WPL\Dark_Mode_For_Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Settings;
use Elementor\Widget_Base;
use Elementor\Elements_Manager;
use Elementor\Plugin;

class Main {
	/**
	 * @var Updater $updater
	 */
	private $updater;

	/**
	 * Main constructor.
	 *
	 * @param Updater $updater
	 */
	public function __construct( $updater = null ) {
		$this->updater = $updater;
		$this->hooks();
	}

	/**
	 * Register hooks
	 */
	public function hooks() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'require_widgets' ] );

		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/admin/after_create_settings/elementor', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'register_plugin_updater' ] );
	}

	/**
	 * Register plugin updater.
	 */
	public function register_plugin_updater() {
		if ( ! $this->updater ) {
			// retrieve our license key from the DB
			$license_key = trim( $this->get_option( 'license_key' ) );

			// setup the updater
			$this->updater = new Updater(
				WPL_DARK_MODE_FOR_ELEMENTOR_API_URL,
				WPL_DARK_MODE_FOR_ELEMENTOR_FILE,
				array(
					'version' => WPL_DARK_MODE_FOR_ELEMENTOR_VERSION,                    // current version number
					'license' => $license_key,             // license key (used get_option above to retrieve from DB)
					'item_id' => WPL_DARK_MODE_FOR_ELEMENTOR_EDD_ID,       // ID of the product
					'author'  => 'Easy Digital Downloads', // author of this plugin
					'beta'    => false,
				)
			);
		}
	}

	/**
	 * Create Setting Tab
	 *
	 * @param Settings $settings Elementor "Settings" page in WordPress Dashboard.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function register_settings( Settings $settings ) {
		$settings->add_section(
			Settings::TAB_INTEGRATIONS,
			WPL_DARK_MODE_FOR_ELEMENTOR_SLUG,
			[
				'label'    => __( 'Dark Mode', 'dark-mode-for-elementor' ),
				'fields'   => [
					WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_license_key' => [
						'label'      => __( 'License Key', 'dark-mode-for-elementor' ),
						'field_args' => [
							'type' => 'text',
						],
					],
				],
			]
		);
	}

	/**
	 * Create a new category for widgets.
	 *
	 * @param Elements_Manager $elements_manager
	 */
	public function add_widget_categories( Elements_Manager $elements_manager ) {
		$elements_manager->add_category(
			'wpl-agency',
			[
				'title' => __( 'WPL Agency', 'dark-mode-for-elementor' ),
				'icon' => 'fa fa-adjust',
			]
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function require_widgets() {
		Plugin::instance()->widgets_manager->register_widget_type( new Widget() );
	}

	/**
	 * Get option value for plugin.
	 *
	 * @param string $key
	 * @param bool   $default
	 *
	 * @return mixed|void
	 */
	public function get_option( $key, $default = false ) {
		return get_option( 'elementor_' . WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_' . $key, $default );
	}

	/**
	 * Add required scripts.
	 */
	public function enqueue_scripts() {
		wp_register_script(
			WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_app',
			'https://cdn.jsdelivr.net/npm/darkmode-js@1.5.3/lib/darkmode-js.min.js',
			[],
			'1.5.3',
			true
		);
	}

	/**
	 * Add required styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_app',
			WPL_DARK_MODE_FOR_ELEMENTOR_URL . 'css/app.css',
			[],
			filemtime( WPL_DARK_MODE_FOR_ELEMENTOR_DIR . 'css/app.css' )
		);
	}
}

// eol.
