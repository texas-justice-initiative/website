/**
 * Donation form functionality
 */
 
// Button functionality
jQuery(document).ready(function($){	
	$('.donation-btn').on('click', function(e) {
		e.preventDefault();
		$('#js-donation-form__error-amount').css('display','none');
		$('.donation-btn').removeClass('selected');
		$('#other_amount')
			.removeClass('selected')
			.val('');
		$('.amount-sign').removeClass('amount-sign--focus')
		$(this).addClass('selected');
	});
	
	$("#other_amount").focus(function() {
		$('#js-donation-form__error-amount').css('display','none');
		$('.donation-btn').removeClass('selected');
		$(this).addClass('selected');
		$('.amount-sign').addClass('amount-sign--focus');
	});

	// Verify form details upon submit
	$('#js-donation_form').on("submit", function(e) {
		e.preventDefault();
		//console.log(parseFloat($('.selected').val()));

		if ($('.selected').length) {
			var payFee = document.getElementById('tax').checked,
					donation = parseFloat($('.selected').val()),
					fee = 0,
					total;

			/* Check for valid donation amount */
			if (isNaN(donation) || donation <= 0) {
				$('#js-donation-form__error-amount').css('display','inline-block');
				$('#other_amount').css("border-color", "red");
				return false;
			}
			
			/* Determine the total donation */
			if (payFee) {
				fee = Math.fround(donation * 0.022 + 0.30);
				fee = parseFloat(fee.toFixed(2));
			}
			
			total = donation + fee;
			total = total.toFixed(2);
							
			$('.donor_name').html($('#first_name').val() + ' ' + $('#last_name').val());
			$('.donor_email').html($('#email').val());
			$('.donor_amount').html(donation.toFixed(2));
			$('.donor_fee').html(fee.toFixed(2));
			$('.donor_total').html(total);
			
			$('.donation-form').hide();
			$('.donation-confirm').show(); 
			
		} else {
			$('label[for="amount"]').children('.donation-form__error').css("display", "inline-block");
			return false;
		}
	});

	$('.donation-confirm__back-button').on('click', function(e) {
		e.preventDefault();
		$('.donation-confirm').hide(); 
		$('.donation-form').show();  
	});
});

// Render PayPal donation button
paypal.Button.render({			
  // Configure environment
  env: 'production',
  client: {
    sandbox: 'AZ2LDJwEbuFjH45Izqk5pmxHtyzxtooUPBCrvrn7tjKXIbv-xGxXsflhCMGl6dy2tRBEliztwiPzCckc',
    production: 'ATVsibuGpsWpkiSPBnC3hyrqeQFZ-YlLW9BYzg53mOehgbTUv6ZESr0lX-u30xmgxxUosQX5QtQNpT81'
  },
  // Customize button (optional)
  locale: 'en_US',
  style: {
	  label: 'checkout',
    size: 'medium',
    color: 'blue',
    shape: 'rect',
  },
	// Set up a payment
	payment: function (data, actions) {
	  return actions.payment.create({
	    transactions: [{
	      amount: {
	        total: $('.donor_total').html(),
	        currency: 'USD',
	        details: {
	          subtotal: $('.donor_total').html(),
	          tax: '0.00',
	          shipping: '0.00',
	          handling_fee: '0.00',
	          shipping_discount: '0.00',
	          insurance: '0.00'
	        }
	      },
	      description: 'A donation supporting the Texas Justice Initiative.',
	      payment_options: {
	        allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
	      },
	      item_list: {
	        items: [
	          {
	            name: 'donation',
	            description: 'A donation in support of the Texas Justice Initiative.',
	            quantity: '1',
	            price: $('.donor_total').html(),
	            tax: '0.00',
	            sku: '1',
	            currency: 'USD'
	          }
	        ]
	      }
	    }],
	    note_to_payer: 'Thank you for supporting the Texas Justice Initiative. We greatly appreciate your generous donation!'
	  });
	},
  // Execute the payment
  onAuthorize: function (data, actions) {
    return actions.payment.execute()
      .then(function () {
        // Redirect user to confirmation page.
        //Setup variables
				var first_name = $('#first_name').val(),
						last_name = $('#last_name').val(),
						email = $('#email').val(),
						donation = $('.donation-btn.selected').val();

        var confirmUrl = './donate/?action=confirm&first_name=' + first_name +
        		'&last_name=' + last_name +
        		'&email=' + email;
        window.location.href = confirmUrl;
      });
  }
}, '#paypal-button');