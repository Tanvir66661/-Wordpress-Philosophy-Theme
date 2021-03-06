<?php

/*Plugin Name: Philosophy ShortCode
Plugin URI:
Description:
Version:
Author:
Author URI:
License: GPLv2 or Later
Text Domain:philosophy_short_code
*/

function philosophy_button($attribute){

	$default = [
		'type'=>'primary',
		'title'=>__('Button','philosophy'),
		'url'=>home_url("/")
	];

	$code_attribute = shortcode_atts($default,$attribute);
	return sprintf('<a class="btn btn--%s full-width" href="%s">%s</a>',
		$code_attribute['type'],
		$code_attribute['url'],
		$code_attribute['title']);
}

add_shortcode("button",'philosophy_button');



function philosophy_button_two($attribute,$content){
	return sprintf('<a class="btn btn--%s full-width" href="%s" target="_blank">%s</a>',
	$attribute['type'],
	$attribute['url'],
	do_shortcode($content)
	);
}

add_shortcode("button2","philosophy_button_two");


function philosophy_uper_case($attributes,$content=''){
	return strtolower(do_shortcode($content));
}
add_shortcode('uc','philosophy_uper_case');



function philosophy_uppercase($attributes,$content=''){
	return strtoupper(do_shortcode($content));
}
add_shortcode('uppercase','philosophy_uppercase');


function philosophy_google_map($location){

	$default = [
		'pb'=>'!1m18!1m12!1m3!1d943202.4092860714!2d90.58655978653965!3d22.5647215547617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b087026b81%3A0x8fa563bbdd5904c2!2sDhaka!5e0!3m2!1sen!2sbd!4v1612855082340!5m2!1sen!2sbd',
		'width'=>'100%',
		'height'=>'400'
	];
	$set_atribute = shortcode_atts($default,$location);
	$width = $set_atribute['width'];
	$height = $set_atribute['height'];
	$pb = $set_atribute['pb'];
	$map = <<<MAP
<iframe src="https://www.google.com/maps/embed?pb={$pb}" width="{$width}" height="{$height}" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
MAP;

	return $map;
}
add_shortcode('gmap','philosophy_google_map');
