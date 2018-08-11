<?php
	/* Template Name: Donation Form
	 ** A page for users to donate to TJI through PayPal
	 */

get_header();
?>

<style>
	.error {
		display: none;
		color: red;
	}
</style>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<form id="donation_form" name="donation_form" method="post" onsubmit="checkForm()">
				<label for="first_name">First Name <span class="error">Please enter your first name</span>
					<input type="text" name="first_name" id="first_name" autocomplete="on" required></label>
				<label for="last_name">Last Name <span class="error">Please enter your last name</span>
					<input type="text" name="last_name" id="last_name" autocomplete="on" required></label>
				<label for="email">Email Address <span class="error">Please enter your valid email address</span>
					<input type="email" name="email" id="email" autocomplete="on" maxlength="60" required></label>
				<label for="comment">Let us know why you are contributing to TJI.
					<textarea name="comment" id="comment"></textarea></label>
				<div id="donation_amount" class="form-group form-inline">
					<button class="donation-btn" value="500">$500</button>
					<button class="donation-btn" value="250">$250</button>
					<button class="donation-btn" value="100">$100</button>
					<button class="donation-btn" value="50">$50</button>
					<button class="donation-btn" value="25">$25</button>
					<div class="input-group">
						<div class="amount_sign">$</div>
						<input type="text" name="other_amount" id="other_amount" placeholder="Other Amount" pattern="\d+(\.\d{2})?">
					</div>
				</div>
				<input type="submit" class="next-btn">
			</form>
			
			<div class="donation_confirmation">
				<h2>Confirm your donation</h2>
				<p>Name: <span class="order_name"></span></p>
				<p>Email: <span class="order_email"></span></p>
				<p>Amount: <span class="order_amount"></span></p>
				<div id="paypal-button"></div>
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