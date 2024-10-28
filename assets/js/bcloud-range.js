jQuery( document ).ready( bcloud_range_update_value() );


function bcloud_range_update_value(){
	jQuery( '.bcloud-range-field' ).on(
		'input',
		function () {
			jQuery( this ).siblings( '.bcloud-range-value' ).text(
				jQuery( this ).attr( 'data-before-range' ) +
				String( jQuery( this ).val() ) + jQuery( this ).attr( 'data-after-range' )
			);
		}
	);
	jQuery( '.bcloud-range-field' ).each(
		function () {
			jQuery( this ).siblings( '.bcloud-range-value' ).text(
				jQuery( this ).attr( 'data-before-range' ) +
				String( jQuery( this ).val() ) + jQuery( this ).attr( 'data-after-range' )
			);
		}
	)

		jQuery( '.elementor-form' ).on(
			'reset',
			function () {
				jQuery( '.bcloud-range-field' ).each(
					function () {
						jQuery( this ).siblings( '.bcloud-range-value' ).text(
							jQuery( this ).attr( 'data-before-range' ) +
							String( jQuery( this ).attr( 'default' ) ) + jQuery( this ).attr( 'data-after-range' )
						);
					}
				)
			}
		)
}