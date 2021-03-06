<?php get_header();?>
<body <?php body_class();?>>
<?php get_template_part("/template-parts/common/hero"); ?>
<div class="posts text-center">
	<h3>Post Under : <?php single_tag_title(); ?></h3>
	<?php
	while(have_posts()) {
		the_post();
		?>
		<h4><a href="<?php echo  the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-8">
				<?php
				the_posts_pagination(array(
					"screen_reader_text"=>' ',
					"prev_text"=>'New Post',
					"nex_text"=>'Old Post',
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
