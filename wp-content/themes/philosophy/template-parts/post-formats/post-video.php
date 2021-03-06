<?php
    $attachment_url = "";
    if(function_exists('the_field')){
        $attachment_url = get_field("source_field");
    }


?>
<article class="masonry__brick entry format-video" data-aos="fade-up">

	<div class="entry__thumb video-image">
		<a href="<?php echo esc_url($attachment_url)?>?color=01aef0&title=0&byline=0&portrait=0" data-lity>
			<?php the_post_thumbnail('philosophy_home_page');?>
		</a>
	</div>

	<?php get_template_part("template-parts/common/post/post-body"); ?>

</article> <!-- end article -->