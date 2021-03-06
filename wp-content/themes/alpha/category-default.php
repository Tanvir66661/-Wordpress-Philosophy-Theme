<?php
single_cat_title();
echo '</br>';

$get_current_thumb = get_queried_object();

$thumbnail = get_field('thumbnail',$get_current_thumb);

if($thumbnail){
	$thumb_preview = wp_get_attachment_image_src($thumbnail);
	echo "<img src='".esc_url($thumb_preview[0])."'>";
}
