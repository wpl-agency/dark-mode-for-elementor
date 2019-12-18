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
	 *
	 */
	const OPTION_NAME_LICENSE_KEY = 'dark_mode_for_elementor_license_key';

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

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/admin/after_create_settings/elementor', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'register_plugin_updater' ] );
		add_filter( 'plugin_action_links', [ $this, 'add_settings_link' ], 10, 2 );
		add_action( 'wp_ajax_' . self::OPTION_NAME_LICENSE_KEY . '_validate', [ $this, 'ajax_validate_license_key' ] );
	}

	/**
	 * Add plugin action links
	 *
	 * @param array $actions
	 * @param string $plugin_file
	 *
	 * @return array
	 */
	public function add_settings_link( $actions, $plugin_file ) {
		if ( 'dark-mode-for-elementor/dark-mode-for-elementor.php' === $plugin_file ) {
			$actions[] = sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=elementor#tab-integrations' ),
				esc_html__( 'Settings', 'dark-mode-for-elementor' )
			);
		}

		return $actions;
	}

	/**
	 * Check license
	 */
	public function ajax_validate_license_key() {
		check_ajax_referer( self::OPTION_NAME_LICENSE_KEY, '_nonce' );

		if ( ! isset( $_POST['license_key'] ) ) {
			wp_send_json_error();
		}

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $_POST['license_key'],
			'item_id'    => WPL_DARK_MODE_FOR_ELEMENTOR_EDD_ID, // The ID of the item in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post(
			WPL_DARK_MODE_FOR_ELEMENTOR_API_URL,
			[
				'timeout' => 15,
				'sslverify' => false,
				'body' => $api_params
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json_success($response);
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.' );
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.' );
						break;
					case 'item_name_mismatch' :
						$message = __( 'This appears to be an invalid license key.' );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.' );
						break;
					default :
						$message = __( 'An error occurred, please try again.' );
						break;
				}
			}
		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_send_json_error( $message );
		}

		wp_send_json_success( $response);
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
					'author'  => 'WPL Agency', // author of this plugin
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
							'desc' => sprintf( __( 'To integrate with our widget you need an <a href="%s" target="_blank">License Key</a>.', 'dark-mode-for-elementor' ), 'https://wpl.agency/dark-mode-for-elementor/' ),
						],
					],
					'validate_api_data' => [
						'field_args' => [
							'type' => 'raw_html',
							'html' => sprintf(
								'<button type="button" data-action="%s" data-nonce="%s" data-id="%s" class="button elementor-button-spinner js-wpl-ajax">%s</button>',
								self::OPTION_NAME_LICENSE_KEY . '_validate',
								wp_create_nonce( self::OPTION_NAME_LICENSE_KEY ),
								'elementor_' . WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_license_key',
								__( 'Validate License Key', 'dark-mode-for-elementor' )
							),
						],
					],
				],
			]
		);
	}

	/**
	 * Add admin script.
	 *
	 * @param $hook_suffix
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( 'toplevel_page_elementor' === $hook_suffix ) {

			wp_enqueue_script(
				WPL_DARK_MODE_FOR_ELEMENTOR_SLUG . '_admin',
				WPL_DARK_MODE_FOR_ELEMENTOR_URL . 'js/admin.js',
				[ 'wp-util' ],
				null,
				true
			);
		}
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
