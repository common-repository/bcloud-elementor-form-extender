jQuery( document ).ready(
	function ($) {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/calculator', bcloud_render_calcultor_in_preview, 10, 4 );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', bcloud_calculator_init );
	}
);

function bcloud_render_calcultor_in_preview(inputField, item, i, settings ){
	var result = '<input type="hidden" class="bcloud-calculator-input-field" data-formula="${item.bcloud_calculator}"><label class="elementor-field-label bcloud-calculator-field">Calculated value will appear here.</label>';
	return result;
}