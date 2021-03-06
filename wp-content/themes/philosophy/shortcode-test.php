<?php
/*Template Name:Test Short Code*/
?>

<?php
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
		<div class="row">

			<div class="col-twelve tab-full">
				<div class="s-content__post-thumb">
					<?php echo 'hello world'; ?>
					<?php
                        the_content();
                    ?>
				</div>
			</div>

		</div>

	</article>


	<!-- comments
	================================================== -->
	<?php
	if ( ! post_password_required() ) {
		comments_template();
	}
	?>

</section> <!-- s-content -->


<?php get_footer(); ?>
