<div <?php post_class();?>>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<a href="<?php the_permalink();?>"><h2 class="post-title"><?php the_title(); ?></h2></a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<p>
					<strong><?php the_author(); ?></strong><br/>
					<?php
					//the_date();
					echo get_the_date();
					?>
				</p>
				<?php echo get_the_tag_list("<ul class='list-unstyled'><li>","</li><li>","</li></ul>"); ?>
				<span class="dashicons dashicons-format-video"></span>

			</div>
			<div class="col-md-8">
				<p>
					<?php
					if(has_post_thumbnail()){
						//$thumbnil_image = get_the_post_thumbnail_url(null,'large');
						//printf('<a href="%s" data-featherlight="image">',$thumbnil_image);
						echo '<a class="popup" href="#" data-featherlight="image">';
						the_post_thumbnail("medium_large",array(
							"class"=>"img-fluid"
						));
						echo '</a>';
					}
					?>

				</p>
				<p>
					<?php
					the_excerpt();
					/*if(!post_password_required()){
						the_excerpt();
					}else{
					   echo get_the_password_form();
					}*/
					/*if(is_single()){
						the_content();
					}else {
						the_excerpt();
					}*/
					?>
				</p>


			</div>
		</div>

	</div>
</div>