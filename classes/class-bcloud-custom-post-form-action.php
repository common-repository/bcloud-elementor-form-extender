<?php
/**
 * Bcloud Custom Post Form Action
 *
 * This file contains the class Bcloud_Custom_Post_Form_Action,
 * a custom form action for Elementor to add, edit, and delete a post.
 *
 * @package BCloud_Elementor_Form_Extender
 * @since 1.0.0
 */

/**
 * Class Bcloud_Custom_Post_Form_Action
 *
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add, edit and delete a post
 */
class Bcloud_Custom_Post_Form_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'create_custom_post';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Create Custom Post', 'bcloud-elementor-extender' );
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget Elementor Widget object.
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_create_custom_post',
			array(
				'label'     => __( 'Create Custom Post', 'bcloud-elementor-extender' ),
				'condition' => array(
					'submit_actions' => $this->get_name(),
				),
			)
		);
		$args           = array(
			'public' => true,
		);
		$all_post_types = get_post_types( $args );
		$widget->add_control(
			'post_type',
			array(
				'label'       => __( 'Post Type', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'post',
				'options'     => $all_post_types,
				'label_block' => true, // make the input field full width.
				'separator'   => 'before',
				'description' => __( 'Select the Post Type of the post you wanted to create.', 'bcloud-elementor-extender' ),
			)
		);

		$widget->add_control(
			'post_id',
			array(
				'label'       => __( 'Elementor Field ID for Post ID', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'separator'   => 'before',
				'description' => __( 'Enter the ID of elementor form field for Post ID. Left it empty for new posts.', 'bcloud-elementor-extender' ),
			)
		);

		$widget->add_control(
			'post_title',
			array(
				'label'       => __( 'Elementor Field ID for Post Title', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of elementor form field for Post title', 'bcloud-elementor-extender' ),
			)
		);

		$widget->add_control(
			'post_content',
			array(
				'label'       => __( 'Elementor Field ID for Post Content', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of elementor form field for Post content', 'bcloud-elementor-extender' ),
			)
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'custom_field_elementor_id',
			array(
				'label'       => __( 'Elementor field Id', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the id of the Elementor form field', 'bcloud-elementor-extender' ),
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'custom_field_acf_id',
			array(
				'label'       => __( 'ACF field id', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of the ACF field', 'bcloud-elementor-extender' ),
				'label_block' => true,
			)
		);

		$widget->add_control(
			'post_custom_fields',
			array(
				'label'         => __( 'ACF Custom Fields', 'bcloud-elementor-extender' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ custom_field_elementor_id }}}',
				'separator'     => 'before',
				'prevent_empty' => false,
			)
		);

		$tax_repeater = new \Elementor\Repeater();

		$all_taxonomies = get_taxonomies();
		$tax_repeater->add_control(
			'taxonomy_name',
			array(
				'label'       => __( 'Select Taxonomy', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'categories',
				'options'     => $all_taxonomies,
				'description' => __( 'Select taxonomy to assign', 'bcloud-elementor-extender' ),
				'label_block' => true,
			)
		);
		$tax_repeater->add_control(
			'taxonomy_term',
			array(
				'label'       => __( 'Elementor field id', 'bcloud-elementor-extender' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of elementor form field for Taxonomy Term', 'bcloud-elementor-extender' ),
				'label_block' => true,
			)
		);

		$tax_repeater->add_control(
			'create_new_term',
			array(
				'label'        => esc_html__( 'Create New Term', 'bcloud-elementor-extender' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'bcloud-elementor-extender' ),
				'label_off'    => esc_html__( 'No', 'bcloud-elementor-extender' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$widget->add_control(
			'post_taxonomies',
			array(
				'label'         => __( 'Taxonomies', 'bcloud-elementor-extender' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $tax_repeater->get_controls(),
				'title_field'   => '{{{ taxonomy_name }}}',
				'separator'     => 'before',
				'prevent_empty' => false,
			)
		);
		$widget->end_controls_section();
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record Form record object.
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler Ajax Handler object.
	 */
	public function run( $record, $ajax_handler ) {
		/*
		Check if current_user has permission.
		if ( !current_user_can( 'edit_posts' ) ) {
			return;
		}
		*/
		$current_user = wp_get_current_user();
		$settings     = $record->get( 'form_settings' );

		$post_type = 'post';
		if ( ! empty( $settings['post_type'] ) ) {
			$post_type = $settings['post_type'];
		}

		// return if post type does not exists.
		if ( ! post_type_exists( $post_type ) ) {
			$ajax_handler->add_error_message( __( 'Post type does not exist!', 'bcloud-elementor-extender' ) );
			return;
		}

		// Get submited Form data.
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data.
		$fields = array();
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		// Make sure that there is a title or post id.
		if ( empty( $fields[ $settings['post_title'] ] ) && empty( $fields[ $settings['post_id'] ] ) ) {
			$ajax_handler->add_error_message( __( 'Post title and post id both cannot be empty', 'bcloud-elementor-extender' ) );
			return;
		}
		$post_title = $fields[ $settings['post_title'] ];

		$post_content = $fields[ $settings['post_content'] ];

		$custom_fields_len = count( $settings['post_custom_fields'] );
		$meta_input        = array();
		for ( $index = 0; $index < $custom_fields_len; $index++ ) {
			$custom_elementor_id          = $settings['post_custom_fields'][ $index ]['custom_field_elementor_id'];
			$custom_acf_id                = $settings['post_custom_fields'][ $index ]['custom_field_acf_id'];
			$meta_input[ $custom_acf_id ] = $fields[ $custom_elementor_id ];
		}

		$tax_terms_len = count( $settings['post_taxonomies'] );
		$tax_input     = array();
		for ( $index = 0; $index < $tax_terms_len; $index++ ) {
			$taxonomy_name          = $settings['post_taxonomies'][ $index ]['taxonomy_name'];
			$taxonomy_term_field_id = $settings['post_taxonomies'][ $index ]['taxonomy_term'];
			$taxonomy_term          = $fields[ $taxonomy_term_field_id ];
			$create_new_term        = $settings['post_taxonomies'][ $index ]['create_new_term'];
			$term_obj               = get_term_by( 'name', $taxonomy_term, $taxonomy_name );
			$term_id                = null;
			if ( ! $term_obj ) {
				if ( 'yes' === $create_new_term ) {
					$term_obj_arr = wp_insert_term( $taxonomy_term, $taxonomy_name );
					if ( ! is_wp_error( $term_obj_arr ) ) {
						$term_id = $term_obj_arr['term_id'];
					}
				}
			} else {
				$term_id = $term_obj->term_id;
			}
			if ( $term_id ) {
				$tax_input[ $taxonomy_name ] = $term_id;
			}
		}
		$post_arg = array(
			'post_type'  => $post_type, // here is my custom post type, you can change it to your own type there.
			'meta_input' => $meta_input,
			'tax_input'  => $tax_input,
		);

		if ( ! empty( $post_content ) ) {
			$post_arg['post_content'] = $post_content;
		}

		if ( ! empty( $post_title ) ) {
			$post_arg['post_title'] = $post_title;
		}

		if ( ! empty( $fields[ $settings['post_id'] ] ) ) {
			$post_id = $fields[ $settings['post_id'] ];
			if ( get_post_type( (int) $post_id ) !== $post_type ) {
				$ajax_handler->add_error_message( __( 'Post type is not equal to the post type of post_id', 'bcloud-elementor-extender' ) );
				return;
			}

			$post_obj = get_post( (int) $post_id );
			if ( ( $current_user && $current_user->ID === $post_obj->post_author ) || current_user_can( 'edit_others_posts' ) ) {
				$post_arg['ID'] = $post_id;
				wp_update_post( $post_arg );
			} else {
				$ajax_handler->add_error_message( __( 'You do not have the required permission to edit this post', 'bcloud-elementor-extender' ) );
				return;
			}
		} else {
			$post_arg['post_status'] = 'publish'; // make it also dynamic - added by Salil on 20 Oct 2021.
			$bcloud_post_id          = wp_insert_post( $post_arg );
		}
	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 *
	 * @access Public
	 * @param array $element Element with all settings.
	 */
	public function on_export( $element ) {
		unset(
			$element['post_id'],
			$element['post_type'],
			$element['post_title'],
			$element['post_content'],
			$element['post_custom_fields'],
			$element['custom_field_elementor_id'],
			$element['custom_field_acf_id'],
			$element['taxonomy_name'],
			$element['taxonomy_term'],
			$element['post_taxonomies']
		);
	}
}
