<article class="masonry__brick entry format-link" data-aos="fade-up">

	<div class="entry__thumb">
		<div class="link-wrap">
			<?php the_content();?>
            <cite>
                <a target="_blank" href="<?php esc_url(the_permalink());?>"><?php esc_html(the_title());?></a>
            </cite>
		</div>
	</div>

</article> <!-- end article -->