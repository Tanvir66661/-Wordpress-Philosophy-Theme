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
                <ul class="s-content__header-meta">
                    <li class="date"><?php the_date(); ?></li>
                    <?php if(has_category()):?>
                    <li class="cat">
						<?php _e( "In", "philosophy" ) ?>
						<?php echo get_the_category_list( " " ); ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div> <!-- end s-content__header -->
            <div class="row">
                <div class="col-eight tab-full">
					<?php
					the_content();
					wp_link_pages();

					?>
                </div>
                <div class="col-four tab-full">
                    <div class="s-content__post-thumb">
						<?php the_post_thumbnail( 'medium' ); ?>
                    </div>
                </div>

            </div>
            <?php
                if(has_tag()):
            ?>
            <p class="s-content__tags">
                <span><?php _e('Post Tags','philosophy');?></span>

                <span class="s-content__tag-list">
                        <?php echo get_the_tag_list(); ?>
                    </span>
            </p> <!-- end s-content__tags -->
            <?php endif; ?>
            <div class="s-content__author">
				<?php echo get_avatar( get_the_author_meta( "ID" ) ); ?>

                <div class="s-content__author-about">
                    <h4 class="s-content__author-name">
                        <a href="#0"><?php the_author(); ?></a>
                    </h4>

                    <p>
						<?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
                    </p>

                    <ul class="s-content__author-social">
						<?php
						$facebookHandle = get_the_author_meta( 'facebook', get_the_author_meta( 'ID' ) );
						$twitterHandle  = get_the_author_meta( 'twiter', get_the_author_meta( 'ID' ) );

						?>

						<?php if ( $facebookHandle ) : ?>
                            <li><a href="<?php echo esc_url( $facebookHandle ); ?>">Facebook</a></li>
						<?php endif; ?>
						<?php if ( $twitterHandle ) : ?>
                            <li><a href="<?php echo esc_url( $twitterHandle ); ?>">Twiter</a></li>
						<?php endif; ?>


                    </ul>
                </div>
            </div>

            <div class="s-content__pagenav">
                <div class="s-content__nav">
                    <div class="s-content__prev">
						<?php

						$philosophy_prev_post = get_previous_post();
						if ( $philosophy_prev_post ):
							?>
                            <a href="<?php echo get_the_permalink( $philosophy_prev_post ); ?>" rel="prev">
                                <span><?php _e( 'Previous Post', 'philosophy' ); ?></span>
								<?php echo esc_html( get_the_title( $philosophy_prev_post ) ) ?>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="s-content__next">
						<?php

						$philosophy_next_post = get_next_post();
						if ( $philosophy_next_post ):
							?>
                            <a href="<?php echo get_the_permalink( $philosophy_next_post ); ?>" rel="next">
                                <span><?php _e( 'Next Post', 'philosophy' ); ?></span>
								<?php echo esc_html( get_the_title( $philosophy_next_post ) ) ?>
                            </a>
						<?php endif; ?>
                    </div>
                </div>
            </div> <!-- end s-content__pagenav -->

            </div> <!-- end s-content__main -->

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