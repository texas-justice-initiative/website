<?php
	/* Template Name: Donation Form
	 ** A page for users to donate to TJI through PayPal
	*/

?>

<?php get_header(); ?>

<form id="donation_form" name="donation_form" method="post">
</form>

<div id="paypal-button"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
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
	        total: '30.11',
	        currency: 'USD',
	        details: {
	          subtotal: '30.00',
	          tax: '0.07',
	          shipping: '0.03',
	          handling_fee: '1.00',
	          shipping_discount: '-1.00',
	          insurance: '0.01'
	        }
	      },
	      description: 'The payment transaction description.',
	      //custom: '90048630024435',
	      //invoice_number: '12345', Insert a unique invoice number
	      payment_options: {
	        allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
	      },
	      //soft_descriptor: 'ECHI5786786',
	      item_list: {
	        items: [
	          {
	            name: 'hat',
	            description: 'Brown hat.',
	            quantity: '5',
	            price: '3',
	            tax: '0.01',
	            sku: '1',
	            currency: 'USD'
	          },
	          {
	            name: 'handbag',
	            description: 'Black handbag.',
	            quantity: '1',
	            price: '15',
	            tax: '0.02',
	            sku: 'product34',
	            currency: 'USD'
	          }
	        ],
	        shipping_address: {
	          recipient_name: 'Brian Robinson',
	          line1: '4th Floor',
	          line2: 'Unit #34',
	          city: 'San Jose',
	          country_code: 'US',
	          postal_code: '95131',
	          phone: '011862212345678',
	          state: 'CA'
	        }
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
</script>

<?php get_footer(); ?>