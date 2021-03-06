<?php
//require_once get_theme_file_path('/inc/tgm.php');
require_once get_theme_file_path('/inc/acf-mb.php');
if(class_exists('Attachments')){
    require_once 'lib/attachment.php';
}
if(site_url()== "http://127.0.0.1/wordpress"){
	define("VERSION",time());
}else{
	define("VERSION",wp_get_theme()->get("Version"));
}
function alpha_bootstraping(){
	load_theme_textdomain("alpha");
	add_theme_support("post-thumbnails");
	add_theme_support("title-tag");

	$custom_header_details=array(
		'header-text'        => true,
		'default-text-color' => '#000000',
        'width'=>1200,
        'height'=>600,
        'flex-height'=>true,
        'flex-width'=>true,
    );
	$alpha_custom_logo_defaults = array(
		"width"=>'70',
		"height"=>'70',
	);
	add_theme_support('custom-background');
	add_theme_support("custom-logo",$alpha_custom_logo_defaults);
	add_theme_support("custom-header",$custom_header_details);
	register_nav_menu("topmenu",__("Top Menu","alpha"));
	register_nav_menu("footermenu",__("Footer Menu","alpha"));
	add_theme_support('post-formats',array('image','link','video','audio','quote'));
	add_theme_support( 'html5', array( 'search-form' ) );

	add_image_size('alpha-square',400,400,true);
	add_image_size('alpha-portrait',400,900);
	add_image_size('alpha-landscape',900,400);
	add_image_size('alpha-landscape-hard-crop',900,500,true);
	add_image_size("alpha-square-new1",600,600, array("left","toop"));
	add_image_size("alpha-square-new2",600,600, array("center","center"));
}
add_action("after_setup_theme","alpha_bootstraping");

function alpha_assets(){
	wp_enqueue_style("bootstrap","//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");
	wp_enqueue_style("fatherlight-css","//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.css");
	wp_enqueue_style("dashicons");
	wp_enqueue_style("tns","//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css");
	wp_enqueue_style("alpha",get_stylesheet_uri());

	wp_enqueue_script("featherlight-js","//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.js",array('jquery'),VERSION,true);
	wp_enqueue_script( "alpha-main", get_theme_file_uri( "/assets/js/main.js" ), array(
		"jquery",
		"featherlight-js"
	), VERSION, true );
	wp_enqueue_script("tns-script","//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js",null,'0.0.1',true);

}
add_action("wp_enqueue_scripts","alpha_assets");


function alpha_sidebar(){
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'alpha' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Right Sidebar', 'alpha' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Left', 'alpha' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Footer Left', 'alpha' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Right', 'alpha' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'Footer Right', 'alpha' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);

}

add_action("widgets_init","alpha_sidebar");

function alpha_the_excerpt($excerpt){
	if(!post_password_required()){
		return $excerpt;
	}else{
		echo get_the_password_form();
	}
}
add_filter("the_excerpt","alpha_the_excerpt");

function alpha_protected_title_format(){
	return "%s";
}
add_filter("protected_title_format","alpha_protected_title_format");

function alpha_menu_item_class($clases,$item){
	$clases[] = "list-inline-item";
	return $clases;
}
add_action("nav_menu_css_class","alpha_menu_item_class",10, 2);

function alpha_page_header(){
	if ( is_page() ) {
		$header_image = get_the_post_thumbnail_url( null, 'large' );
		?>
		<style>
            .page-header {
                background-image: url(<?php echo $header_image; ?>);
            }
		</style>
		<?php
	}

	if(is_front_page()){
		if(current_theme_supports("custom-header")){
			?>
			<style>
				.header{
					background-image: url(<?php  echo header_image(); ?>);
					background-size: cover;
                    margin-bottom: 50px;

				}
                .header h1.heading a, h3.tagline {
                    color: #<?php echo get_header_textcolor();?>;
                    text-decoration: none;

                    <?php
                        if(!display_header_text()){
                            echo "display: none;";
                        }
                    ?>
                }
            </style>
<?php		}
	}
}

add_action("wp_head","alpha_page_header",11);


function alpha_body_class($classes){
    //unset($classes[array_search("wp-custom-logo",$classes)]);
    unset($classes[array_search("body_class_two",$classes)]);
    return $classes;
}

add_filter("body_class","alpha_body_class");


function alpha_post_class($classes){
    //unset($classes[array_search("post type-post",$classes)]);
    return $classes;
}
add_filter("post_class","alpha_post_class");


function alpha_highlight_search_result($text){
    if(is_search()){
        $pattern = '/('.join('|',explode(' ',get_search_query())).')/i';
        $text = preg_replace($pattern,'<span class="search-highlight">\0</span>',$text);
    }
    return $text;
}

add_filter("the_content","alpha_highlight_search_result");
add_filter("the_excerpt","alpha_highlight_search_result");
add_filter("the_title","alpha_highlight_search_result");

 /*prevent custom image size from srcset*/
/*function alpha_image_srcset(){
    return null;
}

add_filter("wp_calculate_image_srcset","alpha_image_srcset");*/
add_filter("wp_calculate_image_srcset","__return_null");


function alpha_modify_main_query($query){
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'tag__not_in', array( 4 ) );

	}
}

add_action("pre_get_post","alpha_modify_main_query");

 /********use this filter before submit the theme********/

//add_filter('acf/settings/show_admin','__return_false');


