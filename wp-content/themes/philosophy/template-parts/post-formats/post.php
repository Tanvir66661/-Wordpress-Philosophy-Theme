<article class="masonry__brick entry format-standard" data-aos="fade-up">

	<div class="entry__thumb">
		<a href="<?php the_permalink();?>" class="entry__thumb-link">
			<!--<img src="<?php /*echo get_template_directory_uri()*/?>/assets/images/thumbs/masonry/lamp-400.jpg"
			     srcset="<?php /*echo get_template_directory_uri()*/?>/assets/images/thumbs/masonry/lamp-400.jpg 1x, <?php /*echo get_template_directory_uri()*/?>/assets/images/thumbs/masonry/lamp-800.jpg 2x" alt="">-->

        <?php the_post_thumbnail('philosophy_home_page');?>
        </a>
	</div>

	<?php get_template_part("template-parts/common/post/post-body"); ?>

</article> <!-- end article -->