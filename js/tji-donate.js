/**
 * Donation form functionality
 */
 
// Button functionality
$(document).ready(function() {
	
	$('.donation-btn').on('click', function(e) {
		e.preventDefault();
		$('.donation-btn').removeClass('selected');
		$('#other_amount').removeClass('selected');
		$('.amount-sign').removeClass('amount-sign--focus');
		$('#other_amount').val('');
		$(this).addClass('selected');
	});
	
	$("#other_amount").focus(function() {
		$('.donation-btn').removeClass('selected');
		$(this).addClass('selected');
		$('.amount-sign').addClass('amount-sign--focus');
	});
	
});

// Verify form details upon submit
function checkForm(event) {
	event.preventDefault();
	
		if ($('.selected').length) {
			var payFee = document.getElementById('tax').checked,
					donation = parseInt($('.selected').val()),
					fee = 0,
					total;
			
			/* Determine the total donation */
			if (payFee) {
				fee = Math.fround(donation * 0.022 + 0.30);
				fee = parseFloat(fee.toFixed(2));
			}
			
			total = donation + fee;
			total = total.toFixed(2);
			
			console.log('Donation: ' + donation + '; Fee: ' + fee + 'Total: ' +  total);
				
	    $('.donor_name').html($('#first_name').val() + ' ' + $('#last_name').val());
	    $('.donor_email').html($('#email').val());
	    $('.donor_amount').html(donation);
	    $('.donor_fee').html(fee);
	    $('.donor_total').html(total);
	    
	    $('.donation-form').hide();
	    $('.donation-confirm').show(); 
	    
		} else {
			console.log('No amount selected.');
		}
	
};

$('.donation-confirm__back-button').on('click', function(e) {
	e.preventDefault();
  $('.donation-confirm').hide(); 
  $('.donation-form').show();  
});

// Render PayPal donation button
paypal.Button.render({			
  // Configure environment
  env: 'sandbox',
  client: {
    sandbox: 'AZ2LDJwEbuFjH45Izqk5pmxHtyzxtooUPBCrvrn7tjKXIbv-xGxXsflhCMGl6dy2tRBEliztwiPzCckc',
    production: 'demo_production_client_id'
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

        var confirmUrl = 'http://localhost:8888/tji/donate/?action=confirm&first_name=' + first_name +
        		'&last_name=' + last_name +
        		'&email=' + email;
        window.location.href = confirmUrl;
      });
  }
}, '#paypal-button');