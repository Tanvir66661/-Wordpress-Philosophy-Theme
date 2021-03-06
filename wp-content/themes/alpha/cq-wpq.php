<?php
/*
 * Template Name: Custom Query WP Query
 * */
?>

<?php get_header(); ?>
<body <?php body_class(); ?>>
<?php get_template_part( "/template-parts/common/hero" ); ?>
<div class="posts text-center">

	<?php
	$paged          = get_query_var( 'paged' ) ? get_query_var( "paged" ) : 1;
	$post_ids       = array( 127, 123, 13, 5, 112, 107, 133, 136 );
	$posts_per_page = 3;
	$totalposts     = 9;
	/*$_P = new WP_Query( array(
		'posts_per_page' => $posts_per_lage,
		'post__in'      =>$post_ids,

		'numberofposts'=>5,
		'category'=>0,
        'numberposts'=>$totalposts,
		'author__in'=>array(1),
		'orderby'       => 'post__in',
		'order'    => 'asc',
        'paged'=>$paged,

	) );*/
	/*$_query = new WP_Query(array(
	     'post_type'=>'post',
         'posts_per_page'=>$posts_per_page,
         'tag'=>'Toyota',
         'tax_query'=>array(

            array(
	             'taxonomy' => 'category',
	             'field'    => 'slug',
	             'terms'    => array( 'default' )
             ),
         )
    ));*/
	/*$_query = new WP_Query(array(
		'post_type'=>'post',
		'posts_per_page'=>$posts_per_page,
		'paged'=>$paged,
		'tax_query'=>array(
		        'relation'=>'AND',

			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( 'default' )
			),
            array(
	            'taxonomy' => 'post_tag',
	            'field'    => 'slug',
	            'terms'    => array( 'bmw' )
            )
		)
	));*/
	/*$_query = new WP_Query(array(
		'posts_per_page'=>$posts_per_page,
		'paged'=>$paged,
		'monthnum'=>12,
        'year'=>2020,
        'post_status'=>'draft'
	));*/

	/*$_query = new WP_Query(array(
		'posts_per_page'=>$posts_per_page,
		'paged'=>$paged,

		'post_format'=>array('post-format-video','post-format-audio'),

	));*/
	/*$_query = new WP_Query(array(
		'posts_per_page'=>$posts_per_page,
		'paged'=>$paged,
        'tax_query'=>array(
	        'toxonomy' => 'post_format',
	        'field'    => 'slug',
	        'terms'    => array( 'post-format-video', 'post-format-audio' ),
	        'operator' => 'NOT IN'
        )

	));*/

	/*$_query = new WP_Query(array(
		'posts_per_page'=>$posts_per_page,
		'paged'=>$paged,
		'meta_key'=>'featured',
        'meta_value'=>'1',

	));*/

	$_query = new WP_Query( array(
		'posts_per_page' => $posts_per_page,
		'paged'          => $paged,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => 'featured',
				'value'   => '1',
				'compare' => '='
			),
			array(
				'key'     => 'homepage',
				'value'   => '1',
				'compare' => '='
			)
		)

	) );

	while ( $_query->have_posts() ) {
		$_query->the_post();
		?>
        <h4><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php
	}
	wp_reset_query();
	?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
				<?php
				echo paginate_links( array(
					"total"     => $_query->max_num_pages,
					'current'   => $paged,
					'prev_next' => false,
				) );
				?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
