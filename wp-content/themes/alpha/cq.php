<?php
/*
 * Template Name: Custom Query
 * */
?>

<?php get_header();?>
<body <?php body_class();?>>
<?php get_template_part("/template-parts/common/hero"); ?>
<div class="posts text-center">

	<?php
    $paged = get_query_var('paged')? get_query_var("paged"):1;
    $post_ids = array( 127, 123, 13, 5,112,107,133,136 );
    $posts_per_lage = 2;
    $totalposts = 9;
	$_P = get_posts( array(
		'posts_per_page' => $posts_per_lage,
		/*'post__in'      =>$post_ids,*/
		/*'numberofposts'=>5,
		'category'=>0,*/
        'numberposts'=>$totalposts,
		'author__in'=>array(1),
		'orderby'       => 'post__in',
		/*'order'    => 'asc'*/
        'paged'=>$paged
	) );
	foreach($_P as $post) {
		setup_postdata($post);
		?>
		<h4><a href="<?php echo  the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php
	}
	wp_reset_postdata();
	/*foreach($_P as $all_post) {
		*/?><!--
        <h4><a href="<?php /*echo esc_url($all_post->guid)*/?>">
				<?php /*echo apply_filters("the_title",$all_post->post_title);*/?>
            </a>
        </h4>
		--><?php
/*	}*/
	?>
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-8">
				<?php
				    echo paginate_links(array(
				            /*"total"=> ceil(count($post_ids)/($posts_per_lage)),*/
				            "total"=> ceil($totalposts/($posts_per_lage)),
                    ));
				?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
