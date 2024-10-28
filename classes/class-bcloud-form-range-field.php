<?php
/**
 * Bcloud Form Range Field
 *
 * This file contains the class Bcloud_Form_Range_Field,
 * which extends the Elementor Pro form field functionality
 * to include a custom range field.
 *
 * @package BCloud_Elementor_Form_Extender
 * @since 1.0.0
 */

/**
 * Class Bcloud_Form_range_Field
 * Custom elementor form Range field
 */
class Bcloud_Form_Range_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base {


	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return __( 'Range', 'bcloud-elementor-extender' );
	}

	/**
	 * Get Type
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_type() {
		return 'range';
	}

	/**
	 * Update form widget controls.
	 *
	 * Add input fields to allow the user to customize the credit card number field.
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget The form widget instance.
	 * @return void
	 */
	public function update_controls( $widget ) {
		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );
		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = array(
			'bcloud_range_min'     => array(
				'name'         => 'bcloud_range_min',
				'label'        => esc_html__( 'Min. Value', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::NUMBER,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_range_max'     => array(
				'name'         => 'bcloud_range_max',
				'label'        => esc_html__( 'Max. Value', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::NUMBER,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_range_step'    => array(
				'name'         => 'bcloud_range_step',
				'label'        => esc_html__( 'Step Value', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::NUMBER,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_range_default' => array(
				'name'         => 'bcloud_range_default',
				'label'        => esc_html__( 'Default Value', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::NUMBER,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'advanced',
				'inner_tab'    => 'form_fields_advanced_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_range_before'  => array(
				'name'         => 'bcloud_range_before',
				'label'        => esc_html__( 'Before', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::TEXT,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_range_after'   => array(
				'name'         => 'bcloud_range_after',
				'label'        => esc_html__( 'After', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::TEXT,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
		);

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );

		/*
		$control_data['fields']['field_value']['value'] = $this->inject_field_controls($control_data['field_value']['value'], 'range');
		*/
		$widget->update_control( 'form_fields', $control_data );
	}

	/**
	 * Render field output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access public
	 * @param mixed $item Field item.
	 * @param mixed $item_index Item index.
	 * @param mixed $form Form object.
	 * @return void
	 */
	public function render( $item, $item_index, $form ) {
		$form->add_render_attribute( 'input' . $item_index, 'type', 'range', true );
		if ( isset( $item['bcloud_range_min'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'min', $item['bcloud_range_min'], true );
		}

		if ( isset( $item['bcloud_range_max'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'max', $item['bcloud_range_max'], true );
		}

		if ( isset( $item['bcloud_range_default'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'value', $item['bcloud_range_default'], true );
			$form->add_render_attribute( 'input' . $item_index, 'default', $item['bcloud_range_default'], true );
		}

		if ( isset( $item['bcloud_range_step'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'step', $item['bcloud_range_step'], true );
		}

		$form->add_render_attribute( 'input' . $item_index, 'data-before-range', $item['bcloud_range_before'], true );
		$form->add_render_attribute( 'input' . $item_index, 'data-after-range', $item['bcloud_range_after'], true );

		$form->add_render_attribute( 'input' . $item_index, 'class', 'bcloud-range-field', true );
		?>

		<input <?php $form->print_render_attribute_string( 'input' . $item_index ); ?>>
		<label class="bcloud-range-value elementor-field-label"><?php echo esc_attr( $item['bcloud_range_before'] . $item['bcloud_range_default'] . $item['bcloud_range_after'] ); ?></label>

		<?php
	}

	/**
	 * Elementor editor preview scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function add_preview_depends() {
		wp_enqueue_script(
			'bcloud-range',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range.js',
			'jquery',
			"1.1.0",
			true
		);
		wp_enqueue_script(
			'bcloud-range-preview',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range-preview.js',
			'bcloud-range',
			"1.1.0",
			true
		);

		wp_enqueue_style(
			'bcloud-range-field',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-range-field.css',
			'',
			"1.1.0"
		);
	}

	/**
	 * Elementor editor assets scripts.
	 *
	 * @access public
	 * @param mixed $form Form object.
	 * @return void
	 */
	public function add_assets_depends( $form ) {
		wp_enqueue_script(
			'bcloud-range',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range.js',
			'jquery',
			"1.1.0",
			true
		);

		wp_enqueue_style(
			'bcloud-range-field',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-range-field.css',
			'',
			"1.1.0"
		);
	}

	/**
	 * Field validation.
	 *
	 * Validate credit card number field value to ensure it complies to certain rules.
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field Form field.
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record Form record.
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler Form Ajax Handler.
	 * @return void
	 */
	public function validation( $field, $record, $ajax_handler ) {
		return;

		if ( ! empty( $field['bcloud_range_max'] ) && ( ! is_numeric( $field['value'] ) || $field['bcloud_range_max'] < (int) $field['value'] ) ) {
			/* translators: %s: Maximum range value - will be a number. */
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be less than or equal to %s', 'bcloud-elementor-extender' ), $field['bcloud_range_max'] ) );
		}

		if ( ! empty( $field['bcloud_range_min'] ) && $field['bcloud_range_min'] > (int) $field['value'] ) {
			/* translators: %s: Minimum range value - will be a number. */
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be greater than or equal %s', 'bcloud-elementor-extender' ), $field['bcloud_range_min'] ) );
		}
	}
}
