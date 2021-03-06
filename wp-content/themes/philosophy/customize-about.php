<?php
/*Template Name: About Page Customizer*/
?>
<?php get_header();?>

<!-- s-content
   ================================================== -->
<section class="s-content s-content--narrow">

	<div class="row">



		<div class="s-content__media col-full">
			<div class="s-content__post-thumb">
				<?php esc_url(the_post_thumbnail("large")); ?>
			</div>
		</div> <!-- end s-content__media -->

		<div class="col-full s-content__main">
			<h3 class="about_us_heading text-center" id="about_heading">
				<?php echo esc_html(get_theme_mod('philosophy_about_heading',__('This is Heading','philosophy')));?>
			</h3>
            <?php
                if(get_theme_mod('philosophy_about_desc_showOrhide')) :
            ?>
			<p id="about_description"><?php echo esc_html(get_theme_mod('philosophy_about_desc'),'Lorem ipsum Nisi amet fugiat eiusmod et aliqua ad qui ut nisi Ut aute anim mollit fugiat qui sit ex occaecat et eu mollit nisi pariatur','philosophy')?></p>
            <?php endif; ?>
			<div  class="row block-<?php echo esc_html(get_theme_mod('philosophy_about_company'),'1-2')?> block-tab-full">
				<div class="col-block">
					<h3 class="quarter-top-margin about_us"><i class="fa fa-user"></i> Who We Are.</h3>
					<p>Lorem ipsum Nisi amet fugiat eiusmod et aliqua ad qui ut nisi Ut aute anim mollit fugiat qui sit ex occaecat et eu mollit nisi pariatur fugiat deserunt dolor veniam reprehenderit aliquip magna nisi consequat aliqua veniam in aute ullamco Duis laborum ad non pariatur sit.</p>
				</div>

				<div class="col-block">
					<h3 class="quarter-top-margin about_us"><i class="fa fa-bar-chart"></i> Our Mission.</h3>
					<p>Lorem ipsum Nisi amet fugiat eiusmod et aliqua ad qui ut nisi Ut aute anim mollit fugiat qui sit ex occaecat et eu mollit nisi pariatur fugiat deserunt dolor veniam reprehenderit aliquip magna nisi consequat aliqua veniam in aute ullamco Duis laborum ad non pariatur sit.</p>
				</div>

				<div class="col-block">
					<h3 class="quarter-top-margin about_us"><i class="fa fa-bar-chart"></i> Our Vision.</h3>
					<p>Lorem ipsum Nisi amet fugiat eiusmod et aliqua ad qui ut nisi Ut aute anim mollit fugiat qui sit ex occaecat et eu mollit nisi pariatur fugiat deserunt dolor veniam reprehenderit aliquip magna nisi consequat aliqua veniam in aute ullamco Duis laborum ad non pariatur sit.</p>
				</div>

				<div class="col-block">
					<h3 class="quarter-top-margin about_us"><i class="fa fa-bar-chart"></i> Our Values.</h3>
					<p>Lorem ipsum Nisi amet fugiat eiusmod et aliqua ad qui ut nisi Ut aute anim mollit fugiat qui sit ex occaecat et eu mollit nisi pariatur fugiat deserunt dolor veniam reprehenderit aliquip magna nisi consequat aliqua veniam in aute ullamco Duis laborum ad non pariatur sit.</p>
				</div>

			</div>


		</div> <!-- end s-content__main -->

	</div> <!-- end row -->

</section> <!-- s-content -->


<?php get_footer(); ?>
