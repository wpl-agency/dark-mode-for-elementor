<?php
/**
 * @package dark-mode-for-elementor
 */
namespace WPL\Dark_Mode_For_Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;
use Elementor\Elements_Manager;
use Elementor\Plugin;

class Main {
	/**
	 * @var Options $options
	 */
	private $options;

	/**
	 * Main constructor.
	 *
	 * @param Options $options
	 */
	public function __construct( $options = null ) {
		$this->options = $options;

		if ( ! $this->options ) {
			//$this->options = new Options();
		}

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
}

// eol.
