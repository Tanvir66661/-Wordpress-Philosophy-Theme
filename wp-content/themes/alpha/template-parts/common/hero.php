<?php
    //$alpha_page_header_img = get_the_post_thumbnail_url(null,"large");
?>
<div class="header page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <?php if(current_theme_supports('custom-logo')): ?>
                    <div class="header-logo text-center">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>
				<h3 class="tagline"> <?php bloginfo("description")?></h3>
				<h1 class="align-self-center display-1 text-center heading hero-head">
					<a class="" href="<?php echo site_url(); ?>"><?php bloginfo("name");?></a>
				  </h1>
			</div>

            <div class="col-md-12" style="padding-top: 20px">
                <?php
                    wp_nav_menu(
                            array(
                                'theme_location'=>"topmenu",
                                'menu_id'=>"topmenucontainer",
                                "menu_class"=>"list-inline text-center"
                            )
                    );
                ?>
            </div>

		</div>
	</div>

    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
				<?php
				if(is_search()){

					?>
                    <h4><?php _e('You search for','alpha');?> : <?php  the_search_query(); ?></h4>

				<?php }  ?>
				<?php
				    echo get_search_form();
				?>
            </div>
        </div>
    </div>
</div>