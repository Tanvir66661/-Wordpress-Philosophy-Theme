<?php
/*
 * Template Name: About Page Template
 */
get_header();
?>
<body <?php body_class(); ?>>
<?php get_template_part( "/template-parts/page-parts/hero-page" ); ?>

<div class="posts">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="post" <?php post_class(); ?>>
			<div class="container">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<h2 class="post-title text-center">
							<?php the_title(); ?>
						</h2>
						<p class="text-center">
							<em><?php the_author(); ?></em><br/>
							<?php echo get_the_date(); ?>
						</p>
					</div>
				</div>
				<div class="row">
                    <?php
                    $attachments = new Attachments( 'testimonial' );
                    if(class_exists('Attachments') && $attachments->exist() ) : ?>
                    <div class="row">

                        <div class="col-md-8 offset-md-2">
                            <h2 class="text-center">
		                        <?php _e('Testimonial');?>
                            </h2>
                            <div class="testimonial slider text-center">
		                        <?php
		                        $attachments = new Attachments( 'testimonial' );
		                        if ( $attachments->exist() ) {
			                        while ( $attachment = $attachments->get() ) {
				                        ?>
                                        <div>
					                        <?php echo $attachments->image( 'thumbnail' ); ?>
                                            <h4><?php echo esc_html($attachments->field('name'))?></h4>
                                            <p><?php echo esc_html($attachments->field('testimonial'));?></p>
                                            <p>
                                                <?php echo esc_html($attachments->field('position'));?>,
                                                <strong>
                                                    <?php echo esc_html($attachments->field('company'));?>
                                                </strong>
                                            </p>
                                        </div>
			                        <?php }
		                        } ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
					<div class="col-md-10 offset-md-1">
						<p>
							<?php
							if ( has_post_thumbnail() ) {
								$thumbnail_url = get_the_post_thumbnail_url( null, "large" );
								printf( '<a class="popup" href="%s" data-featherlight="image">', $thumbnail_url );
								the_post_thumbnail( "large", array( "class" => "img-fluid" ) );
								echo '</a>';
							}

							the_content();

							/*next_post_link();
							echo "<br/>";
							previous_post_link();*/

							?>
						</p>

					</div>
				</div>
                <?php if(class_exists('Attachments')) :?>
                    <div class="row">

	                        <?php
	                        $attachments = new Attachments( 'team' );
	                        if ( $attachments->exist() ) {
		                        while ( $attachment = $attachments->get() ) {
			                        ?>
                                    <div class="col-md-4">
				                        <?php echo $attachments->image( 'medium' ); ?>
                                        <h4><?php echo esc_html($attachments->field('name'))?></h4>
                                        <p>
                                            <?php echo esc_html($attachments->field('position'));?>,
	                                        <?php echo esc_html($attachments->field('company'));?>
                                        </p>
                                        <p> <?php echo esc_html($attachments->field('bio'));?></p>
                                        <p> <?php echo esc_html($attachments->field('email'));?></p>
                                    </div>
		                        <?php }
	                        } ?>

                    </div>
                <?php endif;?>
			</div>
		</div>
	<?php
	endwhile;
	?>

	<div class="container post-pagination">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-8">
				<?php
				the_posts_pagination( array(
					"screen_reader_text" => ' ',
					"prev_text"          => "New Posts",
					"next_text"          => "Old Posts"
				) );
				?>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>
