<?php
    $audio_url = "";
    if(function_exists("the_field")){
        $audio_url = get_field("source_field");
    }

?>
<article class="masonry__brick entry format-audio" data-aos="fade-up">

	<div class="entry__thumb">
		<a href="<?php the_permalink();?>" class="entry__thumb-link">
			<?php the_post_thumbnail('philosophy_home_page');?>
		</a>
		<div class="audio-wrap">
			<audio id="player" src="<?php echo esc_url($audio_url); ?> " width="100%" height="42" controls="controls"></audio>
		</div>
	</div>

	<?php get_template_part("template-parts/common/post/post-body"); ?>

</article> <!-- end article -->