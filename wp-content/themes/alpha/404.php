<?php
	wp_head();
?>

<body <?php body_class();?>>
<div class="container">
	<div class="row not-found-page">
		<div class="col-md-12 text-center">
			<h3>404</h3>
			<h2><?php _e("Sorry we couldn't find what you are looking for",'alpha');?></h2>
		</div>
	</div>
</div>
</body>

<?php
	wp_footer();
?>
