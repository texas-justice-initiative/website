<?php
	/* Template Name: Donation Form
	 ** A page for users to donate to TJI through PayPal
	 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<form id="donation_form" name="donation_form" method="post">
				<label for="first_name">
					<input type="text" name="first_name" id="first_name" autocomplete="on" required></label>
					<input type="text" name="last_name" id="last_name" autocomplete="on" required>
					<input type="text" name="zip" id="zip" autocomplete="on" maxlength="10" pattern="(\d{5}([\-]\d{4})?)">
					<textarea name="comment" id="comment"></textarea>
				<div id="donation_amount" class="form-group form-inline">
					<button class="donation-btn">$500</button>
					<button class="donation-btn">$250</button>
					<button class="donation-btn">$100</button>
					<button class="donation-btn">$50</button>
					<button class="donation-btn">$25</button>
				</div>
				<div class="input-group">
					<div class="amount_sign">$</div>
					<input type="text" name="other_amount" id="other_amount" placeholder="Other Amount" pattern="\d+(\.\d{2})?">
				</div>
				<div id="paypal-button"></div>

			</form>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//Default sidebar
get_sidebar('about');

?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/tji-donate.js"></script>

<?php get_footer(); ?>