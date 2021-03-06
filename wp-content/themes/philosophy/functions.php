<?php
//include_once (get_theme_file_path("/lib/cmb2-attached-posts.php"));
require_once 'customizer.php';
require_once 'lib/cmb2-attached-posts.php';
if ( class_exists( 'Attachments' ) ) {
	require_once 'lib/attachment.php';

}
if ( site_url() == "http://127.0.0.1/wordpress" ) {
	define( "VERSION", time() );
} else {
	define( "VERSION", wp_get_theme()->get( "Version" ) );
}


function philosophy_theme_setup() {

	load_theme_textdomain( "philosophy" );
	add_theme_support( "post-thumbnails" );
	add_theme_support( 'custom-logo' );
	add_theme_support( "title-tag" );
	add_theme_support( "html5", array( 'search-form', 'comment-list' ) );
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'quote', 'link', 'video', 'audio' ) );
	add_editor_style( "/assets/css/editor-style.css" );
	register_nav_menu( "topmenu", __( 'Top Menu', 'philosophy' ) );
	register_nav_menus(
		array(
			'footer-right'  => __( 'Footer Right', 'philosophy' ),
			'footer-middle' => __( 'Footer Middle', 'philosophy' ),
			'footer-left'   => __( 'Footer Left', 'philosophy' ),
		)
	);
	add_image_size( "philosophy_home_page", 400, 400, true );

}

add_action( "after_setup_theme", "philosophy_theme_setup" );


function philosophy_assets() {
	wp_enqueue_style( "fontawesome-css", get_theme_file_uri( "/assets/css/font-awesome/css/font-awesome.css" ), null, "1.0" );
	wp_enqueue_style( "fonts-css", get_theme_file_uri( "/assets/css/fonts.css" ), null, "1.0" );
	wp_enqueue_style( "base-css", get_theme_file_uri( "/assets/css/base.css" ), null, "1.0" );
	wp_enqueue_style( "vendor-css", get_theme_file_uri( "/assets/css/vendor.css" ), null, "1.0" );
	wp_enqueue_style( "main-css", get_theme_file_uri( "assets/css/main.css" ), null, "1.0" );
	wp_enqueue_style( "philosophy-css", get_stylesheet_uri(), null, VERSION );

	$custom_icon_color = get_theme_mod('philosophy_icon_color','#0c0c0c');
	$color_style=<<<COLOR
		.about_us i {
		    color: {$custom_icon_color};
		}
COLOR;

	wp_add_inline_style('philosophy-css',$color_style);

	//wp_enqueue_script('jquery') ;
	wp_enqueue_script( "modernizr-js", get_theme_file_uri( "/assets/js/modernizr.js" ), null, "1.0" );
	wp_enqueue_script( "pace-js", get_theme_file_uri( "/assets/js/pace.min/js" ), null, "1.0" );

	wp_enqueue_script( "plugins-js", get_theme_file_uri( "/assets/js/plugins.js" ), array( "jquery" ), '1.0', true );
	wp_enqueue_script( "main-js", get_theme_file_uri( "/assets/js/main.js" ), array( "jquery" ), '1.0', true );
}

add_action( "wp_enqueue_scripts", "philosophy_assets" );

function philosophy_homepage_pagination() {
	global $wp_query;
	$links = paginate_links( array(
		'current' => max( 1, get_query_var( 'paged' ) ),
		'total'   => $wp_query->max_num_pages,
		'type'    => 'list'
	) );

	$links = str_replace( "page-numbers", "pgn__num", $links );
	$links = str_replace( "<ul class='pgn__num'>", "<ul>", $links );
	$links = str_replace( "next pgn__num", "pgn__next", $links );
	$links = str_replace( "prev pgn__num", "pgn__prev", $links );
	echo $links;
}


remove_action( "term_description", "wpautop" );


function philosophy_about_page_widgets() {
	register_sidebar( array(
		"name"          => __( "About Page Widget", 'philosophy' ),
		"id"            => "about_page",
		"description"   => __( "This is about page widget", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="col-block %2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '<h3 class="quarter-top-margin">',
		"after_title"   => '</h3>'
	) );

	register_sidebar( array(
		"name"          => __( "Contact Page Google Map", 'philosophy' ),
		"id"            => "google_map",
		"description"   => __( "This is contact page map widget", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="%2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '<h3>',
		"after_title"   => '</h3>'
	) );

	register_sidebar( array(
		"name"          => __( "Contact Page Info", 'philosophy' ),
		"id"            => "contact_info",
		"description"   => __( "This is contact page info widget", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="col-six tab-full %2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '<h3>',
		"after_title"   => '</h3>'
	) );

	register_sidebar( array(
		"name"          => __( "Before Footer", 'philosophy' ),
		"id"            => "before_footer",
		"description"   => __( "Before footer section", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="%2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '<h3>',
		"after_title"   => '</h3>'
	) );

	register_sidebar( array(
		"name"          => __( "Newsletter", 'philosophy' ),
		"id"            => "newsletter",
		"description"   => __( "Newsletter section", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="%2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '<h4>',
		"after_title"   => '</h4>'
	) );

	register_sidebar( array(
		"name"          => __( "Copyright", 'philosophy' ),
		"id"            => "copyright",
		"description"   => __( "Copyright section", 'philosophy' ),
		"before_widget" => '<div id="%1$s" class="%2$s">',
		"after_widget"  => '</div>',
		"before_title"  => '',
		"after_title"   => ''
	) );

	register_sidebar( array(
		"name"          => __( "Header Social Media", 'philosophy' ),
		"id"            => "header-social-media",
		"description"   => __( "Social media header section", 'philosophy' ),
		"before_widget" => '<ul id="%1$s" class="header__social %2$s">',
		"after_widget"  => '</ul>',
		"before_title"  => '',
		"after_title"   => ''
	) );

	register_sidebar( array(
		"name"          => __( "Social Media", 'philosophy' ),
		"id"            => "social-media",
		"description"   => __( "Social media footer section", 'philosophy' ),
		"before_widget" => '<ul id="%1$s" class="about__social %2$s">',
		"after_widget"  => '</ul>',
		"before_title"  => '',
		"after_title"   => ''
	) );
}

add_action( "widgets_init", "philosophy_about_page_widgets" );

function philosophy_search_form( $form ) {
	$search_lebel = __( 'Search for:', 'philosophy' );
	$submit_value = __( 'Search', 'philosophy' );
	$action       = home_url( "/" );
	$post_type    = <<<PT
<input type="hidden" name="post_type" value="post">
PT;
	if ( is_post_type_archive( 'book' ) ) {
		$post_type = <<<PT
<input type="hidden" name="post_type" value="book">
PT;
	}

	$searchform = <<<FORM
	<form role="search" method="get" class="header__search-form" action="{$action}">
        <label>
            <span class="hide-content">{$search_lebel}</span>
            <input type="search" class="search-field" placeholder="Type Keywords" value="" name="s"
                   title="{$search_lebel}" autocomplete="off">
        </label>
        {$post_type}
        <input type="submit" class="search-submit" value="{$submit_value}">
    </form>
FORM;

	return $searchform;
}

add_filter( "get_search_form", 'philosophy_search_form' );


function philosophy_chapter_slug_fix($post_link,$id){
	$post = get_post($id);
	if(is_object($post) && 'chapter' === get_post_type($id)){
		$parent_post_id = get_field('parent_book');
		$parent_post = get_post($parent_post_id);

		if($parent_post){
			$post_link = str_replace("%book%",$parent_post->post_name,$post_link);
		}

		if(is_object($post) && 'book'== get_post_type($post)){
			$genre = wp_get_post_terms($post->ID,'genre');
			if(is_array($genre) && count($genre) > 0){
				$slug = $genre[0]->slug;
				$post_link = str_replace("%genre%",$slug,$post_link);
			}else{
				$slug = 'generic';
				$post_link = str_replace("%genre%",$slug,$post_link);
			}
		}
	}
	return $post_link;
}
add_filter("post_type_link","philosophy_chapter_slug_fix",1,2);

/*function append_id_string( $link, $post ) {
	if ( 'chapter' == get_post_type( $post ) ) {
		$parent_post_id = get_field('parent_book');
		$parent_post = get_post($parent_post_id);
		$link = str_replace("%book%",$parent_post->post_name,$link);
	}
	return $link;
}

add_filter( 'post_type_link', 'append_id_string', 1, 2 );*/


function philosophy_footer_language_tag($title){
	if(is_post_type_archive('book') || is_tax('language') || is_page_template('single-book.php')){
		$title = __('Language');
	}

	return $title;
}

add_filter('philosophy_footer_tag_heading','philosophy_footer_language_tag');


function philosophy_footer_language_terms_tag($tag){

	if(is_post_type_archive('book') || is_tax('language') || is_page_template('single-book.php')){
		$tag = get_terms([
			'taxonomy'=>'language',
			'hide_empty'=>false
		]);
	}

	return $tag;

}
add_filter('philosophy_footer_tag_items','philosophy_footer_language_terms_tag');


function philosophy_customizer_assets(){
	wp_enqueue_script('philosophy_customizer_js',get_theme_file_uri('/assets/js/customizer.js'),array(
		'jquery',
		'customize-preview'
	),time(),true);
}

add_action('customize_preview_init','philosophy_customizer_assets');
