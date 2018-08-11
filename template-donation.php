<?php
	/* Template Name: Donation Form
	 ** A page for users to donate to TJI through PayPal
	 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			
			<h1>Support TJI</h1>
			
			<p>Texas Justice Initiative is entirely supported through public donations. If you feel like this is a useful resource, please help us through a generous donation. Funding helps us continue to grow and improve the data we provide.</p>

			<form id="donation_form" name="donation_form" class="donation-form" method="post" onsubmit="checkForm()">
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--medium">
						<label for="first_name" class="donation-form__label">First Name <span class="donation-form__error">Please enter your first name</span></label>
						<input type="text" name="first_name" id="first_name" autocomplete="on" required>
					</div>
					<div class="donation-form__field donation-form__field--medium">
						<label for="last_name" class="donation-form__label">Last Name <span class="donation-form__error">Please enter your last name</span></label>
						<input type="text" name="last_name" id="last_name" autocomplete="on" required>
					</div>
				</div>
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--medium">
						<label for="email" class="donation-form__label">Email Address <span class="donation-form__error">Please enter your valid email address</span></label>
						<input type="email" name="email" id="email" autocomplete="on" maxlength="60" required>
					</div>
				</div>
				<div class="donation-form__row">
					<div class="donation-form__field donation-form__field--large">
						<label for="comment" class="donation-form__label">Let us know why you are contributing to TJI.</label>
						<textarea name="comment" id="comment"></textarea>
					</div>
				</div>
				<div class="donation-form__row">
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
					<div id="donation_amount" class="donation-form__field">
						<input type="submit" class="next-btn tji-form-submit" value="Confirm">
					</div>
				</div>
			</form>
			
			<div class="donation-confirm">
				<h2>Confirm your donation</h2>
				<p>Name: <span class="order_name"></span></p>
				<p>Email: <span class="order_email"></span></p>
				<p>Amount: <span class="order_amount"></span></p>
				<div id="paypal-button">PayPal</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//Default sidebar
get_sidebar('about');

?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/tji-donate.js"></script>

<?php get_footer(); ?>