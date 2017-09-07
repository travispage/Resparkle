jQuery(document).ready(function($) {
jQuery('input#place_order').attr('disabled', 'disabled');
	jQuery('body.woocommerce-checkout').on('keyup','input[id^=pin_payments-card]',function() {
        setButtonStatus();
	});
  	jQuery('body.woocommerce-checkout').on('updated_checkout',function() {
       	setButtonStatus();
  	});
  	jQuery('body.woocommerce-checkout').on('change','input[name=payment_method]',function() {
        setButtonStatus();      
  	});

  	/* jQuery('body.woocommerce-checkout').on('keyup','input#pin_payments-card-expiry',function(e) {
        //cleanExpiryField(e);
  	});

  	function cleanExpiryField(e) {
  		if (e.which == 0 || e.ctrlKey || e.metaKey || e.altKey || e.which == 8 || (e.which > 36 && e.which < 41) || e.which == 91 || e.which == 93) return;
  		var expiryVal = jQuery('input#pin_payments-card-expiry').val();
  		expiryVal = expiryVal.replace(" ","").replace("//","/");
  		if (expiryVal.length == 2) {
  			expiryVal = expiryVal + '/';
  		}
  		jQuery('input#pin_payments-card-expiry').val(expiryVal);
  	} */

  	function setButtonStatus() {
        if (areFieldsValid() || !jQuery('#payment_method_pin_payments').is(':checked') || jQuery('input[name=pin_customer_token]:checked').val() != "new") {
            jQuery('input#place_order').removeAttr('disabled');
        } else {
            jQuery('input#place_order').attr('disabled', 'disabled');
        }
  	}

  	function areFieldsValid() {
        var isValid = true;

        var number = jQuery('input#pin_payments-card-number').val();
        number = number.replace(/[^0-9]/g, '');

        var validCardType = false;

        var re = new RegExp("^4[0-9]{12}(?:[0-9]{3})?$");
        if (number.match(re) != null) {
              validCardType = true;
        }
        re = new RegExp("^5[1-5][0-9]{14}$");
        if (number.match(re) != null) {
              validCardType = true;
        }
        re = new RegExp("^3[47][0-9]{13}$");
        if (number.match(re) != null) {
              validCardType = true;
        }
        re = new RegExp("^3(?:0[0-5]|[68][0-9])[0-9]{11}$");
        if (number.match(re) != null) {
              validCardType = true;
        }

        // Check the expiry
        var validExpiry = false;
        if (jQuery('input#pin_payments-card-expiry').val().length < 8) {
        	var expiry = jQuery('#pin_payments-card-expiry').val();
			var expiry_month = '';
			var expiry_year = '';
			var expiry_parts = expiry.split("/");	
			if (expiry_parts.length > 1) {
				expiry_month = expiry_parts[0].trim();
				expiry_year = expiry_parts[1].trim();	
				if (expiry_year.length == 2) {
					expiry_year = "20"+expiry_year;
				}
			}
			var today = new Date();
			if (parseInt(expiry_year) > today.getFullYear() || (parseInt(expiry_year) == today.getFullYear() && parseInt(expiry_month) >= (today.getMonth() + 1))) {
            	validExpiry = true;
            }
        }

        // Check the CCV
        var validSecurityCode = false;
        if (jQuery('input#pin_payments-card-cvc').val().length == 3 || jQuery('input#pin_payments-card-cvc').val().length == 4) {
              validSecurityCode = true;
        }

        // Check the card name
        var validCardName = false;
        if (jQuery('input#pin_payments-card-name').val().length > 2) {
              validCardName = true;
        }

        if (validCardType && validExpiry && validSecurityCode && validCardName) {
              return true;
        }
        return false;
    }

	// Below is from Pin's JS version of Direct Post, taken from https://pin.net.au/docs/guides/payment-forms
	// It has been modified for use with WooCommerce's ajax checkout process
	$(function() {

		Pin.setPublishableKey(WooPinPayments.publishable_key);

		// Now we can call Pin.js on form submission to retrieve a card token and submit
		// it to the server

		var $form = $('form#order_review, form.checkout'),
			$submitButton = $form.find(":submit"),
			$errors = $form.find('.errors');

		$('form.checkout').on('checkout_place_order_pin_payments',function(e) {
			return checkoutFormSubmit(e);
		});

		$('form#order_review').submit(function(e) {
			return checkoutFormSubmit(e);
		});

		// Reset Pin Card Token on change of details
		$("form.checkout, form#order_review").on('change', 'input#pin_payments-card-cvc, select#pin_payments-card-expiry, input#pin_payments-card-number, input#pin_payments-card-name', function( event ) {
			$('.woocommerce_error, .woocommerce-error, .woocommerce-message, .woocommerce_message').remove();
			$('input[name=card_token]').remove();
			$('input[name=ip_address]').remove();
		});

		// Show/hide for new CC box
		$("form.checkout, form#order_review").change('input[name=pin_customer_token]', function() {
			if ($('input[name=pin_customer_token]:checked').val() == 'new' ) {
				$('div.pin_new_card.has_cards').slideDown( 200 );
			} else {
				$('div.pin_new_card.has_cards').slideUp( 200 );
			}
			setButtonStatus();
		});

		function checkoutFormSubmit(e) {
			if ( jQuery('#payment_method_pin_payments').is(':checked') && ( jQuery('input[name=pin_customer_token]:checked').size() == 0 || jQuery('input[name=pin_customer_token]:checked').val() == 'new' )) {
				if ( jQuery( 'input[name=card_token]' ).size() == 0 ) {
					e.preventDefault();
					$errors.hide();

					// Disable the submit button to prevent multiple clicks
					$submitButton.attr('disabled','disabled');

					// Fetch details required for the createToken call to Pin

					var expiry = $('#pin_payments-card-expiry').val();
					var expiry_month = '';
					var expiry_year = '';
					var expiry_parts = expiry.split("/");	
					if (expiry_parts.length > 1) {
						expiry_month = expiry_parts[0].trim();
						expiry_year = expiry_parts[1].trim();	
						if (expiry_year.length == 2) {
							expiry_year = "20"+expiry_year;
						}
					} 

					var card = {
						number: $('#pin_payments-card-number').val(),
						name: $('#pin_payments-card-name').val(),
						expiry_month: expiry_month,
						expiry_year: expiry_year,
						cvc: $('#pin_payments-card-cvc').val(),
						address_line1: $('input#billing_address_1').val(),
						address_line2: $('input#billing_address_2').val(),
						address_city: $('input#billing_city').val(),
						address_state: $('select#billing_state,input#billing_state').val(),
						address_postcode: $('input#billing_postcode').val(),
						address_country: $('select#billing_country,input#billing_country').val()
					};

					$('.pin_new_card').addClass('getting-token');

					// Request a token for the card from Pin
					Pin.createToken(card, handlePinResponse);
					return false;
				}
			}
			return true;
		};

		function handlePinResponse(response) {

			var $form = $('form#order_review, form.checkout');

			$('.pin_new_card').removeClass('getting-token');
					
			if (response.response) {
				// Add the card token and ip address of the customer to the form
				// You will need to post these to Pin when creating the charge.
				$('<input>')
					.attr({type: 'hidden', name: 'card_token'})
					.val(response.response.token)
					.appendTo($form);
				$('<input>')
					.attr({type: 'hidden', name: 'ip_address'})
					.val(response.ip_address)
					.appendTo($form);

				// Resubmit the form
				$form.submit();

			} else {

				// show the errors on the form
		        $('.woocommerce_error, .woocommerce-error, .woocommerce-message, .woocommerce_message, input[name=card_token], input[name=ip_address]').remove();
		        
				$('#pin_payments-card-name').closest('p').before('<ul class="woocommerce_error woocommerce-error"></ul>');
				
				if (response.messages) {
					$.each(response.messages, function(index, errorMessage) {
						$('ul.woocommerce_error',$form).append($('<li>').text(errorMessage.message));
					});
				} else {
					$('ul.woocommerce_error',$form).append($('<li>').text('Sorry, we were unable to communicate with Pin. Please check your API keys.'));
				}

				$submitButton.removeAttr('disabled');
				return false;
				
			}
		};
	});
});