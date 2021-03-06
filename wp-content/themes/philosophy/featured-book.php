<?php
/*
  * Template Name: Featured Book
  * */
?>
<?php get_header(); ?>


<!-- s-content
================================================== -->
<section class="s-content">

	<div class="row masonry-wrap">
		<h3 class="text-center"><?php _e("Featured Books",'philosophy');?></h3>

		<div class="masonry">
			<div class="grid-sizer"></div>

			<?php
			$arrgs = array(
				'post_type'=>'book',
				'meta_key'=>'is_featured',
				'meta_value'=>true,
			);
			$philosophy_featured_book = new WP_Query($arrgs);

			while($philosophy_featured_book->have_posts()) {
				$philosophy_featured_book->the_post();
				get_template_part('template-parts/post-formats/post',get_post_format());
			}
			?>
		</div> <!-- end masonry -->
	</div> <!-- end masonry-wrap -->


	<div class="row">
		<div class="col-full">
			<nav class="pgn">
				<?php  philosophy_homepage_pagination(); ?>
			</nav>
		</div>
	</div>

</section> <!-- s-content -->
<?php get_footer(); ?>

