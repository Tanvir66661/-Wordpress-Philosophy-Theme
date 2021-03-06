<?php
$alpha_layout_class = "col-md-8";
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	$alpha_layout_class = "col-md-10 offset-1";
}
?>
<?php get_header(); ?>
<body <?php body_class( array( "body_class_one", "body_class_two" ) ); ?>>
<?php get_template_part( "/template-parts/common/hero" ); ?>
    <div class="posts">
		<?php
		while ( have_posts() ):
			the_post();
			?>
            <div <?php post_class( array( "custom_post_class" ) ); ?>>
                <div class="container">

                    <div class="row">
                        <div class="<?php echo $alpha_layout_class; ?>>">
                            <div class="col-md-12">
                                <h2 class="post-title"><?php the_title(); ?></h2>

                                <p>
                                    <!--<strong><?php /*the_author(); */
									?></strong><br/>-->
                                    <strong><?php the_author_posts_link(); ?></strong><br/>

									<?php
									//the_date();
									echo get_the_date();
									?>
                                </p>


                            </div>
                            <div class="col-md-12">
								<?php
								if ( class_exists( 'Attachments' ) ) {
									?>
                                    <div class="slider">
										<?php
										$attachments = new Attachments( 'slider' );
										if ( $attachments->exist() ) {
											while ( $attachment = $attachments->get() ) {
												?>
                                                <div>
													<?php echo $attachments->image( 'large' ); ?>
                                                </div>
											<?php }
										} ?>
                                    </div>

								<?php } else {
									?>

                                    <div>
										<?php
										if ( has_post_thumbnail() ) {
											//$thumbnil_image = get_the_post_thumbnail_url(null,'large');
											//printf('<a href="%s" data-featherlight="image">',$thumbnil_image);
											echo '<a class="popup" href="#" data-featherlight="image">';
											the_post_thumbnail( "medium_large", array(
												"class" => "img-fluid"
											) );
											echo '</a>';
										}
										?>

                                    </div>

								<?php } ?>
                                <p class="">
									<?php
									the_content();
									wp_link_pages();
									next_post_link();

									?>
                                </p>
                            </div>
							<?php if ( function_exists( 'the_field' ) ): ?>
								<?php
                                //$related_post = get_field('related_post');
                                $related_post = get_post_meta(get_the_ID(),'related_post',true);
								//print_r($related_post);
								if ($related_post) {
									echo '<h3>Related Posts</h3>';
									echo '<br>';

									$args = array(
										'post__in' => $related_post,
									);
									$my_query = new WP_Query($args);

                                    //var_dump($my_query->have_posts());
									if( $my_query->have_posts() ) {
										while ($my_query->have_posts()) :
                                            $my_query->the_post();
										?>
                                            <!--<a href="<?php /*the_permalink() */?>" rel="bookmark" title="Permanent Link to <?php /*the_title_attribute(); */?>"><?php /*the_title(); */?></a>-->
                                           <p> <?php the_title(); ?></p>

										<?php
										endwhile;
									}
									wp_reset_query();
								}
								?>
							<?php endif; ?>

                            <div class="col-md-12 mb-3">
								<?php
                                $camera_model = esc_html(get_post_meta(get_the_ID(),'camera_model',true));
								if ( get_post_format() == 'image' && function_exists( "the_field" ) ):
									//$camera_model = esc_html( get_field( 'camera_model' ) );
									$loction = esc_html( get_field( 'location' ) );
									$date = get_field( 'date' );
									?>
                                    <div class="metainfo">
                                        <strong>Camera Model : </strong><?php echo $camera_model; ?>
                                        <br>
                                        <strong>Location : </strong><?php echo $loction; ?>
                                        <br>
                                        <strong>Date : </strong><?php echo $date; ?>
                                        <br>
										<?php if ( get_field( 'licensed' ) ): ?>
                                            <strong>License
                                                : </strong><?php echo apply_filters( "the_content", the_field( 'license_info' ) ); ?>
										<?php endif; ?>
                                        <br>
										<?php
										/*$image = get_field('image');
										$alpha_image_details = wp_get_attachment_image_src($image,'medium');
										echo "<img src='".esc_url($alpha_image_details[0])."'/>";*/
										?>
                                        <br>

										<?php
										$file = get_field( 'attachment' );
										if ( $file ) {
											$file_url  = wp_get_attachment_url( $file );
											$thumbnail = get_field( "thumbnail", $file );

											if ( $thumbnail ) {
												$image = wp_get_attachment_image_src( $thumbnail );

												if ( is_array( $thumbnail ) ) {
													echo "<a href='{$file_url}' target='_blank'><img height='150' src='" . esc_url( $thumbnail['url'] ) . "'/></a>";

												}
											} else {
												echo "<a href='{$file_url}' target='_blank'>$file_url</a>";

											}
										}
										?>

                                    </div>
								<?php endif; ?>
                            </div>
                            <div class="row authorsection">
                                <div class="col-md-2 authorimage">
									<?php echo get_avatar( get_the_author_meta( "ID" ) ); ?>
                                </div>
                                <div class="col-md-10">
									<?php echo get_the_author_meta( "display_name" ); ?>
                                    <p> <?php echo get_the_author_meta( "description" ); ?></p>
                                </div>
								<?php if ( function_exists( 'the_field' ) ): ?>
                                    <p>Facebook URL
                                        : <?php echo the_field( "facebook", "user_" . get_the_author_meta( 'ID' ) ) ?>
                                        <br>

                                        Twiter URL
                                        : <?php echo the_field( "twiter", "user_" . get_the_author_meta( 'ID' ) ) ?></p>
								<?php endif; ?>
                            </div>
							<?php
							if ( ! post_password_required() ): ?>
                                <div class="col-md-12">
									<?php
									//comments_template();
									?>
                                </div>
							<?php endif; ?>
                        </div>
						<?php if ( is_active_sidebar( 'sidebar-1' ) ): ?>
                            <div class="col-md-4">
								<?php if ( is_active_sidebar( 'sidebar-1' ) ): ?>
                                    <ul id="sidebar">
										<?php dynamic_sidebar( 'sidebar-1' ); ?>
                                    </ul>
								<?php endif; ?>
                            </div>
						<?php endif; ?>
                    </div>

                </div>
            </div>
		<?php
		endwhile;
		?>
    </div>
<?php get_footer(); ?>