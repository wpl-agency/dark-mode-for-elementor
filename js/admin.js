(function( $ ){
	'use strict';
	var wp = window.wp,
		$buttons = $( '.js-wpl-ajax' );

	$buttons.on(
		'click',
		function ( e ) {
			e.preventDefault();

			var $button = $( this );

			$button
				.removeClass( 'error' )
				.removeClass( 'success' )
				.addClass( 'loading' );

			wp.ajax.send(
				$button.data( 'action' ),
				{
					success: function ( response ) {
						$button
							.addClass( 'success' )
							.removeClass( 'loading' );

						console.log( response );
					},
					error:   function ( response ) {
						$button
							.addClass( 'error' )
							.removeClass( 'loading' );

						console.log( response );
					},
					data: {
						_nonce: $button.data( 'nonce' ),
						license_key: $( '#' + $button.data( 'id' ) ).val()
					}
				}
			);
		}
	);
})( window.jQuery );