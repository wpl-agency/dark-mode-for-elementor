<?php
/**
 * @package dark-mode-for-elementor
 */
namespace WPL\Dark_Mode_For_Elementor;

use Elementor\Element_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Widget extends Widget_Base {
	/**
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'dark-mode-for-elementor';
	}

	/**
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Dark Mode', 'dark-mode-for-elementor' );
	}

	/**
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-adjust';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'wpl-agency' ];
	}

	/**
	 * Get script depends
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'dark_mode_for_elementor_app' ];
	}

	/**
	 * Register yandex maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'dark_mode_for_elementor',
			array(
				'label' => esc_html__( 'General', 'dark-mode-for-elementor' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Label', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '🌓',
			)
		);

		$this->add_control(
			'time',
			array(
				'label'       => esc_html__( 'Time', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0.5,
				'step'        => 0.1,
				'min'         => 0.1,
				'max'         => 0.9,
				'show_label'  => true,
			)
		);

		$this->add_control(
			'width',
			array(
				'label'       => esc_html__( 'Width', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 42,
			)
		);

		$this->add_control(
			'height',
			array(
				'label'       => esc_html__( 'Height', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 42,
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 42,
			)
		);

		$this->add_control(
			'save_in_cookies',
			array(
				'label'        => esc_html__( 'Save In Cookies', 'dark-mode-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'auto_match_os_theme',
			array(
				'label'        => esc_html__( 'Auto Match Os Theme', 'dark-mode-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'position_fixed',
			array(
				'label'        => esc_html__( 'Position Fixed', 'dark-mode-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'right',
			array(
				'label'       => esc_html__( 'Right', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'condition'   => array(
					'position_fixed' => 'yes',
				),
			)
		);

		$this->add_control(
			'bottom',
			array(
				'label'       => esc_html__( 'Bottom', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'condition'   => array(
					'position_fixed' => 'yes',
				),
			)
		);

		$this->add_control(
			'mix_color',
			array(
				'label'   => esc_html__( 'Mix Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
			)
		);

//		$this->add_control(
//			'text_color',
//			array(
//				'label'   => esc_html__( 'Text Color', 'dark-mode-for-elementor' ),
//				'type'    => Controls_Manager::COLOR,
//				'default' => '#333',
//			)
//		);

		$this->add_control(
			'background_color_light',
			array(
				'label'   => esc_html__( 'Background Color Light', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
			)
		);

		$this->add_control(
			'text_color_light',
			array(
				'label'   => esc_html__( 'Text Color Light', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333',
			)
		);

		$this->add_control(
			'background_color_dark',
			array(
				'label'   => esc_html__( 'Background Color Dark', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
			)
		);

		$this->add_control(
			'text_color_dark',
			array(
				'label'   => esc_html__( 'Text Color Dark', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render yandex maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<wpl-button
			id="wpl_button_<?php echo esc_attr( $this->get_id() ); ?>"
			title="<?php echo esc_html__( 'Enable/Disable Dark Mode', 'dark-mode-for-elementor' ); ?>"
		>
		</wpl-button>
		<style>
			.darkmode-layer + div {
				display: none;
			}
			#wpl_button_<?php echo esc_attr( $this->get_id() ); ?> {
				display: inline-block;
				z-index: 500;
				user-select: none;
				text-align: center;
				cursor: pointer;
				overflow: hidden;
				white-space: nowrap;
				position: <?php echo ( 'yes' === $settings['position_fixed'] ) ? 'fixed' : 'static'; ?>;
				color: <?php echo esc_html( $settings['text_color_light'] ); ?>;
				background-color: <?php echo esc_html( $settings['background_color_light'] ); ?>;
				width: <?php echo absint( $settings['width'] ); ?>px;
				height: <?php echo absint( $settings['width'] ); ?>px;
				line-height: <?php echo absint( $settings['height'] ); ?>px;
				right: <?php echo absint( $settings['right'] ); ?>px;
				bottom: <?php echo absint( $settings['bottom'] ); ?>px;
				border-radius: <?php echo absint( $settings['border_radius'] ); ?>px;
			}
			#wpl_button_<?php echo esc_attr( $this->get_id() ); ?>::before {
				content: '<?php echo esc_html( $settings['label'] ); ?>';
			}

			.darkmode--activated #wpl_button_<?php echo esc_attr( $this->get_id() ); ?> {
				color: <?php echo esc_html( $settings['text_color_dark'] ); ?>;
				background-color: <?php echo esc_html( $settings['background_color_dark'] ); ?>;
			}

		</style>
		<script>
			jQuery( function ( $ ) {
				var options = {
					time: '<?php echo esc_js( $settings['time'] ); ?>s',
					saveInCookies: <?php echo ( 'yes' === $settings['save_in_cookies'] ) ? 1 : 0; ?>,
					autoMatchOsTheme: <?php echo ( 'yes' === $settings['auto_match_os_theme'] ) ? 1 : 0; ?>,
					label: '',
					mixColor: '<?php echo esc_js( $settings['mix_color'] ); ?>',
				},
					wpl_button_<?php echo esc_attr( $this->get_id() ); ?> = new Darkmode( options );

				$( '#wpl_button_<?php echo esc_attr( $this->get_id() ); ?>' ).on(
					'click',
					function () {
						wpl_button_<?php echo esc_attr( $this->get_id() ); ?> . toggle();
					}
				);
			});
		</script>
		<?php
	}
}