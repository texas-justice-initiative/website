<?php
	/* Template Name: Donation Form */

get_header();

// Determine how a user has reached this page.
$action = $_GET["action"];

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php

			if ($action == null) {

			// *******************************************************************
			// * Default Donation Page
			// * 
			// * Action: null
			// *
			// * Displays the basic donation form.
			// *
			// * Data collected here will be routed to a confirm screen
			// * and then to PayPal to complete the payment processing. PayPay
			// * will then redirect back to this page with a new action.
			// *
			// *******************************************************************
			
			?>			

			<h1>Support TJI</h1>
			
			<p>Texas Justice Initiative is entirely supported through public donations. If you feel like this is a useful resource, please help us through a generous donation. Funding helps us continue to grow and improve the data we provide.</p>
			
			<p>You can also donate conveniently through our <a href="https://www.facebook.com/donate/605145886526139/10106361188494357/" target="_blank">Facebook Page</a>.</p>
			
			<hr>
		
			<form id="js-donation_form" class="donation-form">
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--medium">
						<label for="first_name" class="donation-form__label">First Name <span class="donation-form__error">Please enter your first name</span></label>
						<input type="text" name="first_name" id="first_name" class="required" autocomplete="on" required>
					</div>
					<div class="donation-form__field donation-form__field--medium">
						<label for="last_name" class="donation-form__label">Last Name <span class="donation-form__error">Please enter your last name</span></label>
						<input type="text" name="last_name" id="last_name" class="required" autocomplete="on" required>
					</div>
				</div>
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--medium">
						<label for="email" class="donation-form__label">Email Address <span class="donation-form__error">Please enter your valid email address</span></label>
						<input type="email" name="email" id="email" class="required" autocomplete="on" maxlength="60" required>
					</div>
				</div>
				<!-- Temporarily removed until we decide email functionality
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--large">
						<label for="comment" class="donation-form__label">Let us know why you are contributing to TJI.</label>
						<textarea name="comment" id="comment"></textarea>
					</div>
				</div>
				-->
				<div class="donation-form__row">
					<label for="amount" class="donation-form__label"><span class="donation-form__error">Please enter the amount you wish to donate.</span></label>
					<div id="donation_amount" class="donation-form__field donation-form__field--inline donation-amount">
						<button class="donation-btn" value="500">$500</button>
						<button class="donation-btn" value="250">$250</button>
						<button class="donation-btn" value="100">$100</button>
						<button class="donation-btn" value="50">$50</button>
						<button class="donation-btn" value="25">$25</button>
						<div class="donation-form__group donation-form__group--inline other-amount">
							<div class="amount-sign">$</div>
							<input type="text" name="other_amount" id="other_amount" placeholder="Other Amount" pattern="\d+(\.\d{2})?">
						</div>
					</div>
				</div>
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--inline">
						<input type="checkbox" name="tax" id="tax" value="yes">
						<label for="tax" class="donation-form__label"> I would like to add 2.2% plus $0.30 to my donation to cover PayPal processing costs.</label>
					</div>
				</div>

				<div class="donation-form__row">
					<div id="donation_amount" class="donation-form__field">
						<input type="submit" class="next-btn tji-form-submit tji-donation-submit" value="Confirm">
					</div>
				</div>
			</form>
			
			<div class="donation-confirm">
				<div class="donation-confirm__confirm-box">
					<h2>Confirm your donation</h2>
					<p>Name: <span class="donor_name"></span></p>
					<p>Email: <span class="donor_email"></span></p>
					<p>Donation: $<span class="donor_amount"></span></p>
					<p>Paypal Fee: $<span class="donor_fee"></span></p>
					<p><strong>Total: $<span class="donor_total"></span></strong></p>
					<div id="paypal-button"></div>
				</div>
				<button type="submit" class="tji-btn-primary donation-confirm__back-button">GO BACK</button>
			</div>

			<?php
				
			} else if ($action == "confirm") {

			// *******************************************************************
			// * Default Donation Page
			// * 
			// * Action: confirm
			// *
			// * Displayed upon successful payment.
			// *
			// * Here we will give the user the option to subscribe to the 
			// * TJI mailing list.
			// *
			// *******************************************************************

				$user = array(
					'first_name' 	=> $_GET["first_name"],
					'last_name' 	=> $_GET["last_name"],
					'email' 			=> $_GET["email"]
				);
				//print_r($user);
			?>

			<h1>Before You Go...</h1>
			
			<p>We appreciate your generous donation and support for Texas Justice Initiative! If you would like to keep up to date on the latest TJI news, please take a moment to subscribe to our mailing list.</p>
			
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
			<form action="https://texasjusticeinitiative.us18.list-manage.com/subscribe/post?u=fd262cb4a5fc0bafb38da2e22&amp;id=2663621fac" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			    <div id="mc_embed_signup_scroll">
				
			<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
			<div class="donation-form__row">
				<div class="mc-field-group donation-form__field donation-form__field--large">
					<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
				</label>
					<input type="email" value="<?php echo $user['email']; ?>" name="EMAIL" class="required email" id="mce-EMAIL">
				</div>
			</div>
			<div class="donation-form__row">
				<div class="mc-field-group donation-form__field donation-form__field--medium">
					<label for="mce-FNAME">First Name </label>
					<input type="text" value="<?php echo $user['first_name']; ?>" name="FNAME" class="" id="mce-FNAME">
				</div>
				<div class="mc-field-group donation-form__field donation-form__field--medium">
					<label for="mce-LNAME">Last Name </label>
					<input type="text" value="<?php echo $user['last_name']; ?>" name="LNAME" class="" id="mce-LNAME">
				</div>
			</div>
			<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
			    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_fd262cb4a5fc0bafb38da2e22_2663621fac" tabindex="-1" value=""></div>
			    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="tji-btn-primary tji-form-submit"></div>
			</div>
			</form>
			</div>
			
			<!--End mc_embed_signup-->		
			<?php
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//Default sidebar
get_sidebar('about');

?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/tji-donate.js"></script>

<?php get_footer(); ?>