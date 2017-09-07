/* jshint asi: true */
jQuery( document ).ready(function($) {
	(function prepare_events() {
		for( var i = 0; i < fcaPcEvents.length; i++ ) {
			add_auto_event_params( fcaPcEvents[i] )
			
			var eventName = fcaPcEvents[i].event
			var parameters = fcaPcEvents[i].parameters
			var triggerType = fcaPcEvents[i].triggerType
			var trigger = fcaPcEvents[i].trigger
			var apiAction = fcaPcEvents[i].apiAction
			
			switch ( triggerType ) {
				case 'css':
					$( trigger ).bind('click', { name: eventName, params: parameters, apiAction: apiAction }, function( e ){
						fbq( e.data.apiAction, e.data.name, e.data.params )
					})
					break
					
				case 'post':
					if ( fcaPcEvents[i].hasOwnProperty('delay') ) {
						setTimeout( fbq, fcaPcEvents[i].delay * 1000, apiAction, eventName, parameters  )
					} else {
						fbq( apiAction, eventName, parameters )
					}
					break
					
				case 'url':
				
					$('a').each(function(){
						if ( $(this).attr('href') === trigger ) {
							$(this).bind('click', { name: eventName, params: parameters, apiAction: apiAction }, function( e ){
								fbq( e.data.apiAction, e.data.name, e.data.params )
							})
						}
					})
														
					break
				
				
			}

		}
	})()
	
	//SEARCH INTEGRATION
	if ( typeof fcaPcSearchQuery !== 'undefined' ) {
		fbq('track', 'Search', fcaPcSearchQuery )
	}
	
	//QUIZ CAT INTEGRATION
	if ( typeof fcaPcQuizCatEnabled !== 'undefined' ) {

		$( '.fca_qc_start_button' ).click( function( e ){
			var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
			var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
			fbq('trackCustom', 'QuizStart', { 'quiz_id': id, 'quiz_name': name } )
			return true
		})
		
		$( '.fca_qc_share_link' ).click( function( e ){
			var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
			var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
			fbq('trackCustom', 'QuizShare', { 'quiz_id': id, 'quiz_name': name } )
			return true
		})
		
		$( '.fca_qc_submit_email_button' ).click( function( e ){
			if ( $(this).siblings('#fca_qc_email_input').val() ) {
				var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
				var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
				fbq('track', 'Lead', { 'quiz_id': id, 'quiz_name': name } )
				return true
			}
		})
		
		$( '.fca_qc_score_title' ).on('DOMSubtreeModified', function( e ){
			if( !$(this).data('pixelcat') ) {
				$(this).data('pixelcat', true)
				var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
				var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
				fbq('trackCustom', 'QuizCompletion', { 'quiz_id': id, 'quiz_name': name, 'quiz_result': $(this).text() } )
			}
			return true
		})
	}
	
	//WOO INTEGRATION
	if ( typeof fcaPcWooAddToCart !== 'undefined' ) {
		fbq( 'track', 'AddToCart', fcaPcWooAddToCart )
	}
	
	if ( typeof fcaPcWooCheckoutCart !== 'undefined' ) {
		fbq( 'track', 'InitiateCheckout', fcaPcWooCheckoutCart)
		
		$( 'form.checkout' ).on( 'checkout_place_order', function( e ){
			fbq('track', 'AddPaymentInfo', fcaPcWooCheckoutCart )
			return true
		})
	}
	
	if ( typeof fcaPcWooPurchase !== 'undefined' ) {
		fbq( 'track', 'Purchase', fcaPcWooPurchase)
	}
	
	if ( typeof fcaPcWooProduct !== 'undefined' ) {
		fbq( 'track', 'ViewContent', fcaPcWooProduct )
		
		//WISHLIST
		$( '.wl-add-to, .add_to_wishlist' ).click( function( e ){
			fbq( 'track', 'AddToWishlist', fcaPcWooProduct )
		})
	}
	
	if ( fcaPcDebug.debug ) {
		console.log ( 'pixel cat events:' )
		console.log ( fcaPcEvents )
		console.log ( 'pixel cat post:' )
		console.log ( fcaPcPost )
	}
	
	function add_auto_event_params( event ) {
		for ( var prop in event.parameters ) {
			event.parameters[prop] = event.parameters[prop].replace( '{post_id}', fcaPcPost.id )
														 .replace( '{post_title}', fcaPcPost.title )
														 .replace( '{post_type}', fcaPcPost.type )
														 .replace( '{post_category}', fcaPcPost.categories.join(', ') )
		}
	}	


})