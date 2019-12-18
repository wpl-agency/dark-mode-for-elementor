<?php
/**
 * @package dark-mode-for-elementor
 */
namespace WPL\Dark_Mode_For_Elementor;

use Elementor\Element_Base;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
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
			'section_dark',
			array(
				'label' => esc_html__( 'Dark Mode', 'dark-mode-for-elementor' ),
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

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'dark-mode-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'dark-mode-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'dark-mode-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'dark-mode-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'time',
			array(
				'label'       => esc_html__( 'Animation Duration', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0.5,
				'step'        => 0.1,
				'min'         => 0.1,
				'max'         => 3,
			)
		);

		$this->add_control(
			'width',
			array(
				'label'      => esc_html__( 'Button Width', 'dark-mode-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 42,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpl_button' => 'width: {{SIZE}}{{UNIT}};',
				],
			)
		);

		$this->add_control(
			'height',
			array(
				'label'       => esc_html__( 'Button Height', 'dark-mode-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 42,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpl_button' => 'height: {{SIZE}}{{UNIT}};',
				],
			)
		);

		$this->add_control(
			'line_height',
			array(
				'label'       => esc_html__( 'Button Line Height', 'dark-mode-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 300,
						'step' => 1,
					],
					'em'  => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 42,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpl_button' => 'line-height: {{SIZE}}{{UNIT}};',
				],
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
			'position',
			array(
				'label'        => esc_html__( 'Position', 'dark-mode-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'static'   => esc_html__( 'Static', 'dark-mode-for-elementor' ),
					'relative' => esc_html__( 'Relative', 'dark-mode-for-elementor' ),
					'absolute' => esc_html__( 'Absolute', 'dark-mode-for-elementor' ),
					'fixed'    => esc_html__( 'Fixed', 'dark-mode-for-elementor' ),
				],
				'default'      => 'fixed',
				'selectors'    => [
					'{{WRAPPER}} .wpl_button' => 'position: {{VALUE}}',
				],
			)
		);

		$this->add_control(
			'right',
			array(
				'label'       => esc_html__( 'Right', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'condition'   => array(
					'position' => [ 'fixed', 'relative', 'absolute' ],
				),
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'right: {{VALUE}}px;',
				],
			)
		);

		$this->add_control(
			'bottom',
			array(
				'label'       => esc_html__( 'Bottom', 'dark-mode-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'condition'   => array(
					'position' => [ 'fixed', 'relative', 'absolute' ],
				),
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'bottom: {{VALUE}}px;',
				],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Dark Mode', 'dark-mode-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpl_button',
			]
		);

		$this->add_control(
			'mix_color',
			array(
				'label'   => esc_html__( 'Mix Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
			)
		);

		$this->start_controls_tabs( 'tabs_dark_mode_style' );

		$this->start_controls_tab(
			'tab_dark_mode_light',
			[
				'label' => __( 'Light', 'dark-mode-for-elementor' ),
			]
		);

		$this->add_control(
			'background_color_light',
			array(
				'label'   => esc_html__( 'Background Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'background-color: {{VALUE}};',
				],
			)
		);

		$this->add_control(
			'text_color_light',
			array(
				'label'   => esc_html__( 'Text Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'color: {{VALUE}};',
				],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dark_mode_dark',
			[
				'label' => __( 'Dark', 'dark-mode-for-elementor' ),
			]
		);

		$this->add_control(
			'background_color_dark',
			array(
				'label'   => esc_html__( 'Background Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'.darkmode--activated {{WRAPPER}} .wpl_button' => 'background-color: {{VALUE}};',
				],
			)
		);

		$this->add_control(
			'text_color_dark',
			array(
				'label'   => esc_html__( 'Text Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'.darkmode--activated {{WRAPPER}} .wpl_button' => 'color: {{VALUE}};',
				],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dark_mode_hover',
			[
				'label' => __( 'Hover', 'dark-mode-for-elementor' ),
			]
		);

		$this->add_control(
			'background_color_hover',
			array(
				'label'   => esc_html__( 'Background Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ccc',
				'selectors' => [
					'{{WRAPPER}} .wpl_button:hover' => 'background-color: {{VALUE}};',
				],
			)
		);

		$this->add_control(
			'text_color_hover',
			array(
				'label'   => esc_html__( 'Text Color', 'dark-mode-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .wpl_button:hover' => 'color: {{VALUE}};',
				],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpl_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
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
			class="wp-exclude-emoji wpl_button"
			id="wpl_button_<?php echo esc_attr( $this->get_id() ); ?>"
			title="<?php echo esc_html__( 'Enable/Disable Dark Mode', 'dark-mode-for-elementor' ); ?>"
			data-label="<?php echo esc_attr( $settings['label'] ); ?>">
		</wpl-button>
		<script>
			document.addEventListener(
				'DOMContentLoaded',
				function () {
					var options = {
						time: '<?php echo esc_js( $settings['time'] ); ?>s',
						saveInCookies: <?php echo ( 'yes' === $settings['save_in_cookies'] ) ? 1 : 0; ?>,
						autoMatchOsTheme: <?php echo ( 'yes' === $settings['auto_match_os_theme'] ) ? 1 : 0; ?>,
						label: '',
						mixColor: '<?php echo esc_js( $settings['mix_color'] ); ?>'
					},
						wpl_button_<?php echo esc_attr( $this->get_id() ); ?> = new Darkmode( options );
						wpl_button_<?php echo esc_attr( $this->get_id() ); ?>.showWidget();

					document.getElementById( 'wpl_button_<?php echo esc_attr( $this->get_id() ); ?>' ).addEventListener(
						'click',
						function () {
							document.getElementsByClassName( 'darkmode-toggle' )[0].click();
						}
					);
				}
			);
		</script>
		<?php
	}
}