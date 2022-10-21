<form id="milton-testimonial-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

	<div class="field-container">
		<input type="text" class="form-field" placeholder="Your Name" id="name" name="name">
		<small class="field-msg error" data-error="InvalidName">Your Name is Required</small>
	</div>

	<div class="field-container">
		<input type="email" class="form-field" placeholder="Your Email" id="email" name="email">
		<small class="field-msg error" data-error="InvalidEmail">Your Email is Required</small>
	</div>
	<div class="field-container">
		<ul class="rating" id="rating">
			<li><span class="dashicons dashicons-star-filled" id="star0" data-index="0"></span></li>
			<li><span class="dashicons dashicons-star-filled" id="star1" data-index="1"></span></li>
			<li><span class="dashicons dashicons-star-filled" id="star2" data-index="2"></span></li>
			<li><span class="dashicons dashicons-star-filled" id="star3" data-index="3"></span></li>
			<li><span class="dashicons dashicons-star-filled" id="star4" data-index="4"></span></li>
		</ul>
	</div>
	<input type="hidden" class="rating-value" name="rating" value="0">
	<div class="field-container">
		<textarea name="message" id="message" class="form-field" placeholder="Your Message"></textarea>
		<small class="field-msg error" data-error="InvalidMessage">A Message is Required</small>
	</div>
	
	<div class="text-center">
		<div>
            <button type="stubmit" class="btn btn-default btn-lg btn-sunset-form">Submit</button>
        </div>
		<small class="field-msg js-form-submission">Submission in process, please wait&hellip;</small>
		<small class="field-msg success js-form-success">Message Successfully submitted, thank you!</small>
		<small class="field-msg error js-form-error">There was a problem with the Contact Form, please try again!</small>
	</div>
	<input type="hidden" name="action" value="submit_testimonial">

	<?php $nonce=wp_create_nonce('testimonial-nonce'); ?>

	<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
</form>