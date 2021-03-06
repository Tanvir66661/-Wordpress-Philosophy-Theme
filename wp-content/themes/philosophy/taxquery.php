<?php
/*Template Name: Taxonomy Query*/
echo "<h3> Single Tax Query  </h3>";
echo "</br>";
$args = array(
	'post_type'      => 'book',
	'posts_per_page' => - 1,
	'tax_query'      => [
		[
			'taxonomy' => 'language',
			'field'    => 'slug',
			'terms'    => [ 'bangla', 'english' ]
		]
	]
);

$philosophy_taxonomy_query = new WP_Query( $args );
while ( $philosophy_taxonomy_query->have_posts() ) {
	$philosophy_taxonomy_query->the_post();
	the_title();
	echo "</br>";
}

wp_reset_query();
echo "</br>";
echo "<h3> Tax Query Relation (Only Bangla Language) </h3>";

$relation_args = array(

	'post_type'      => 'book',
	'posts_per_page' => - 1,
	'tax_query'      => [
		'relation' => 'AND',
		[
			'taxonomy' => 'language',
			'field'    => 'slug',
			'terms'    => [ 'bangla' ]
		],
		[
			'taxonomy' => 'language',
			'field'    => 'slug',
			'terms'    => [ 'english' ],
			'operator'=>"NOT IN"
		]
	]

);

$relation_query = new WP_Query( $relation_args );

while ( $relation_query->have_posts() ) {
	$relation_query->the_post();
	the_title();
	echo "</br>";

}

wp_reset_query();

echo "</br>";
echo "<h3> Tax Query Relation (Only English Language) </h3>";

$relation_args = array(

	'post_type'      => 'book',
	'posts_per_page' => - 1,
	'tax_query'      => [
		'relation' => 'AND',
		[
			'taxonomy' => 'language',
			'field'    => 'slug',
			'terms'    => [ 'english' ]
		],
		[
			'taxonomy' => 'language',
			'field'    => 'slug',
			'terms'    => [ 'bangla' ],
			'operator'=>"NOT IN"
		]
	]

);

$relation_query = new WP_Query( $relation_args );

while ( $relation_query->have_posts() ) {
	$relation_query->the_post();
	the_title();
	echo "</br>";

}

wp_reset_query();


echo "</br>";
echo "<h3> Tax Query Relation Between Two Taxonomy (Language Bangla AND Genre Classic) </h3>";

$relation_args = array(

	'post_type'      => 'book',
	'posts_per_page' => - 1,
	'tax_query'      => [
		'relation'=>'AND',
		[
			'relation' => 'AND',
			[
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => [ 'bangla' ]
			],
			[
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => [ 'english' ],
				'operator'=>"NOT IN"
			]
		],
		[
			'taxonomy'=>'genre',
			'field'=>'slug',
			'terms'=>['classic']
		]
	]

);

$relation_query = new WP_Query( $relation_args );

while ( $relation_query->have_posts() ) {
	$relation_query->the_post();
	the_title();
	echo "</br>";

}

wp_reset_query();

echo "</br>";
echo "<h3> Tax Query Relation Between Two Taxonomy (Language Only Bangla AND Genre Only Classic) </h3>";

$relation_args = array(

	'post_type'      => 'book',
	'posts_per_page' => - 1,
	'tax_query'      => [
		'relation'=>'AND',
		[
			'relation'=>'AND',
			[
				'relation' => 'AND',
				[
					'taxonomy' => 'language',
					'field'    => 'slug',
					'terms'    => [ 'bangla' ]
				],
				[
					'taxonomy' => 'language',
					'field'    => 'slug',
					'terms'    => [ 'english' ],
					'operator'=>"NOT IN"
				]
			],
			[
				'taxonomy'=>'genre',
				'field'=>'slug',
				'terms'=>['classic']
			]
		],
		[
			'taxonomy'=>'genre',
			'field'=>'slug',
			'terms'=>['horror'],
			'operator'=>"NOT IN"
		]
	]

);

$relation_query = new WP_Query( $relation_args );

while ( $relation_query->have_posts() ) {
	$relation_query->the_post();
	the_title();
	echo "</br>";

}

wp_reset_query();

