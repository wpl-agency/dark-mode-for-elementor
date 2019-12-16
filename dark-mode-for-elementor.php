<?php

/**
 * Dark Mode For Elementor
 *
 * @package           dark-mode-for-elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Dark Mode For Elementor
 * Plugin URI:        https://wordpress.org/plugins/dark-mode-for-elementor/
 * Description:       Dark Mode For Elementor
 * Version:           1.0
 * Author:            wpl.agency
 * Author URI:        https://wpl.agency/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dark-mode-for-elementor
 * Domain Path:       /languages
 */

namespace WPL\Dark_Mode_For_Elementor;

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPL_DARK_MODE_FOR_ELEMENTOR_VERSION', '1.0' );
define( 'WPL_DARK_MODE_FOR_ELEMENTOR_SLUG', 'dark_mode_for_elementor' );
define( 'WPL_DARK_MODE_FOR_ELEMENTOR_FILE', __FILE__ );
define( 'WPL_DARK_MODE_FOR_ELEMENTOR_DIR', __DIR__ );
define( 'WPL_DARK_MODE_FOR_ELEMENTOR_URL', plugin_dir_url( WPL_DARK_MODE_FOR_ELEMENTOR_FILE ) );

/**
 * Load gettext translate for our text domain.
 *
 * @return void
 * @since 1.1
 *
 */
function dark_mode_for_elementor() {

	load_plugin_textdomain( 'dark-mode-for-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\dark_mode_for_elementor_fail_load' );

		return;
	}

	require_once WPL_DARK_MODE_FOR_ELEMENTOR_DIR . '/vendor/autoload.php';

	$main = new Main();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\dark_mode_for_elementor' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @return void
 * @since 1.1
 *
 */
function dark_mode_for_elementor_fail_load() {
	$message = sprintf(
	/* translators: 1: Plugin name 2: Elementor */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'dark-mode-for-elementor' ),
		'<strong>' . esc_html__( 'Dark Mode For Elementor', 'dark-mode-for-elementor' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'dark-mode-for-elementor' ) . '</strong>'
	);

	echo '<div class="error"><p>' . $message . '</p></div>';
}

// eol.
