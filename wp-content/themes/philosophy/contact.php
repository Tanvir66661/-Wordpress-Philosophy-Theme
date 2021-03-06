<?php
/*Template Name: About Us Page*/
the_post();
get_header();
?>


	<!-- s-content
	================================================== -->
	<section class="s-content s-content--narrow s-content--no-padding-bottom">

		<article class="row format-standard">

			<div class="s-content__header col-full">
				<h1 class="s-content__header-title">
					<?php the_title(); ?>
				</h1>

			</div> <!-- end s-content__header -->

			<div class="s-content__media col-full">
				<?php
                    if(is_active_sidebar("google_map")){
                        dynamic_sidebar('google_map');
                    }
                ?>
			</div> <!-- end s-content__media -->

			<?php esc_html( the_content() ); ?>

			<div class="row block-1-2 block-tab-full">
				<?php
				if ( is_active_sidebar( 'contact_info' ) ) {
					dynamic_sidebar('contact_info');
				}
				?>
			</div>

			<h3>Say Hello.</h3>

			<form name="cForm" id="cForm" method="post" action="">
				<fieldset>

					<div class="form-field">
						<input name="cName" type="text" id="cName" class="full-width" placeholder="Your Name" value="">
					</div>

					<div class="form-field">
						<input name="cEmail" type="text" id="cEmail" class="full-width" placeholder="Your Email" value="">
					</div>

					<div class="form-field">
						<input name="cWebsite" type="text" id="cWebsite" class="full-width" placeholder="Website"  value="">
					</div>

					<div class="message form-field">
						<textarea name="cMessage" id="cMessage" class="full-width" placeholder="Your Message" ></textarea>
					</div>

					<button type="submit" class="submit btn btn--primary full-width">Submit</button>

				</fieldset>
			</form> <!-- end form -->


		</article>


	</section> <!-- s-content -->


<?php get_footer(); ?>