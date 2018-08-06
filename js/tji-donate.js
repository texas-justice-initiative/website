/**
 * Donation form functionality
 */
 

$(document).ready(function() {
	
	$('.donation-btn').on('click', function() {
		event.preventDefault();
		$('.donation-btn').removeClass('selected');
		$(this).addClass('selected');
	});
	
	$('.next-btn').on('click', function() {
		event.preventDefault();

	  var isValid = true;  // Set the isValid to flag true initially
	
    $('input.required').each(function() {   // Loop thru all the elements
        var name = $('input.required').val();
        console.log(name);
        if(name != '') {  // If not empty do nothing
				
        } else {          
            isValid = false; // If one loop is empty set the isValid flag to false
            return false;    // Break out of .each loop 
        }
    });
	
    if(isValid){    // If valid submit form else show error
	    $('.order_name').html($('#first_name').val() + ' ' + $('#last_name').val());
      $('.order_email').html($('#email').val());
      $('.order_amount').html($('.donation-btn.selected').val());
    }
    else{
       $('.error').show();
    return false;
	}
		
	});
	
});

let amount = '30.11';


paypal.Button.render({
  // Configure environment
  env: 'sandbox',
  client: {
    sandbox: 'demo_sandbox_client_id',
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
		      payer_info: {
			      first_name: $('#first_name').val(),
			      last_name: $('#last_name').val(),
			      email: $('#email').val()
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
	    note_to_payer: 'Contact us for any questions on your order.'
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