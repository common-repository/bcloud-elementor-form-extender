<?php
/**
 * Bcloud Form Calculator Field
 *
 * This file contains the class Bcloud_Form_Calculator_Field,
 * which extends the Elementor Pro form field functionality
 * to include a custom calculator field.
 *
 * @package BCloud_Elementor_Form_Extender
 * @since 1.0.0
 */

/**
 * Class Bcloud_Form_Calculator_Field
 * Custom elementor form Calculator field
 */
class Bcloud_Form_Calculator_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base {


	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return __( 'Calculator', 'bcloud-elementor-extender' );
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
		return 'calculator';
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
			'bcloud_calculator'        => array(
				'name'         => 'bcloud_calculator',
				'label'        => esc_html__( 'Formula', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::TEXTAREA,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_calculator_before' => array(
				'name'         => 'bcloud_calculator_before',
				'label'        => esc_html__( 'Before', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::TEXT,
				'condition'    => array(
					'field_type' => $this->get_type(),
				),
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'bcloud_calculator_after'  => array(
				'name'         => 'bcloud_calculator_after',
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
		$form_settings               = $form->get_settings_for_display();
		$formula                     = $item['bcloud_calculator'];
		$formula_parts               = explode( ' ', $formula );
		$formula_parts               = array_filter(
			$formula_parts,
			function ( $value ) {
				return ! is_null( $value ) && '' !== $value;
			}
		);
		$form_fields                 = $form_settings['form_fields'];
		$formula_field_id            = $item['custom_id'];
		$all_field_custom_ids        = array();
		$all_field_custom_ids_values = array();
		foreach ( $form_fields as $form_field ) {
			if ( $form_field['custom_id'] !== $formula_field_id ) {
				array_push( $all_field_custom_ids, $form_field['custom_id'] );
				if ( 'range' === $form_field['field_type'] ) {
					$all_field_custom_ids_values[ $form_field['custom_id'] ] = $form_field['bcloud_range_default'];
				} else {
					$all_field_custom_ids_values[ $form_field['custom_id'] ] = $form_field['field_value'];
				}
			}
		}
		$eval_string = '';
		foreach ( $formula_parts as $formula_part ) {
			if ( in_array( $formula_part, $all_field_custom_ids, true ) ) {
				$field_value = $all_field_custom_ids_values[ $formula_part ];
				if ( is_numeric( $field_value ) ) {
					$eval_string .= strval( $field_value );
				} else {
					$eval_string .= strval( 0 );
				}
			} elseif ( is_numeric( $formula_part ) ) {
				$eval_string .= strval( $formula_part );
			} else {
				switch ( $formula_part ) {
					case '+':
						$eval_string .= $formula_part;
						break;
					case '-':
						$eval_string .= $formula_part;
						break;
					case '/':
						$eval_string .= $formula_part;
						break;
					case '*':
						$eval_string .= $formula_part;
						break;
					case '**':
						$eval_string .= $formula_part;
						break;
					case '%':
						$eval_string .= $formula_part;
						break;
					case '(':
						$eval_string .= $formula_part;
						break;
					case ')':
						$eval_string .= $formula_part;
						break;
				}
			}
		}

		$result = '';
		try {
			$result = eval( 'return ' . $eval_string . ';' );
		} catch ( ParseError $e ) {
			echo esc_html( 'Message: ' . $e->getMessage() );
		}

		if ( is_float( $result ) ) {
			$result = round( $result, 2 );
		}
		$form->add_render_attribute( 'input' . $item_index, 'type', 'hidden', true );
		$form->add_render_attribute( 'input' . $item_index, 'value', $result, true );
		$form->add_render_attribute( 'input' . $item_index, 'default', $result, true );
		$form->add_render_attribute( 'input' . $item_index, 'class', 'bcloud-calculator-input-field', true );
		// $form->add_render_attribute('input' . $item_index, 'disabled', null, true);
		$form->add_render_attribute( 'input' . $item_index, 'data-formula', $formula, true );
		$form->add_render_attribute( 'input' . $item_index, 'data-before-formula', $item['bcloud_calculator_before'], true );
		$form->add_render_attribute( 'input' . $item_index, 'data-after-formula', $item['bcloud_calculator_after'], true );

		?>

		<input <?php $form->print_render_attribute_string( 'input' . $item_index ); ?>>
		<label class="elementor-field-label bcloud-calculator-field"><?php echo esc_attr( $item['bcloud_calculator_before'] . $result . $item['bcloud_calculator_after'] ); ?></label>

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
			'bcloud-calculator',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field.js',
			'jquery',
			"1.1.0",
			true
		);
		wp_enqueue_script(
			'bcloud-calculator-preview',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field-preview.js',
			'bcloud-calculator',
			"1.1.0",
			true
		);

		wp_enqueue_style(
			'bcloud-calculator-field',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-calculator-field.css',
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
			'bcloud-calculator-field',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field.js',
			'jquery',
			"1.1.0",
			true
		);

		wp_enqueue_style(
			'bcloud-calculator-field',
			BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-calculator-field.css',
			'',
			"1.1.0"
		);
	}
}
