/**
 * Donation form functionality
 */
 

$(document).ready(function() {
	
	$('.donation-btn').on('click', function() {
		event.preventDefault();
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

function checkForm() {
	event.preventDefault();		
	
		if ($('.selected').length) {	
			$('.order_amount').html($('.selected').val());	
	    $('.order_name').html($('#first_name').val() + ' ' + $('#last_name').val());
	    $('.order_email').html($('#email').val());
	    
	    $('.donation-confirm').show();
	    $('.donation-confirm').get(0).scrollIntoView();
	    
		} else {
			console.log('No amount selected.');
		}
	
};

let amount = '30.11';

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
    size: 'small',
    color: 'gold',
    shape: 'pill',
  },
	// Set up a payment
	payment: function (data, actions) {
	  return actions.payment.create({
	    transactions: [{
	      amount: {
	        total: $('.donation-btn.selected').val(),
	        currency: 'USD',
	        details: {
	          subtotal: $('.donation-btn.selected').val(),
	          tax: '0.00',
	          shipping: '0.00',
	          handling_fee: '0.00',
	          shipping_discount: '0.00',
	          insurance: '0.00'
	        }
	      },
	      description: 'The payment transaction description.',
	      payment_options: {
	        allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
	      },
	      item_list: {
	        items: [
	          {
	            name: 'donation',
	            description: 'Donation in support of Texas Justice Initiative.',
	            quantity: '1',
	            price: $('.donation-btn.selected').val(),
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
        // Show a confirmation message to the buyer
        window.alert('Thank you for your purchase!');
      });
  }
}, '#paypal-button');