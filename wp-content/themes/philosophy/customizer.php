<?php

function philosophy_customize_settings($wp_customize){

	$wp_customize->add_section('philosophy_about',array(
		'title'=>__('About Us','philosophy'),
		'priority'=>'30',
		'active_callback'=>function(){
			return is_page_template('customize-about.php');
		}
	));

	$wp_customize->add_setting('philosophy_about_heading',array(
		'default'=>__('About Us Heading.','philosophy'),
		'transport'=>'postMessage', // refresh
		//'type'=>'option'  // Default - theme_mod
	));

	$wp_customize->add_control('philosophy_about_heading_ctrl',array(
		'label'=>__('About Us Heading','philosophy'),
		'section'=>'philosophy_about',
		'settings'=>'philosophy_about_heading',
		'type'=>'text'
	));


	$wp_customize->add_setting('philosophy_about_desc',array(
		'transport'=>'postMessage'
	));

	$wp_customize->add_control('philosophy_about_desc_ctrl',array(
		'label'=>__('About Us Description','philosophy'),
		'section'=>'philosophy_about',
		'settings'=>'philosophy_about_desc',
		'type'=>'textarea',
		'active_callback'=>'manage_description_area'
	));

	/*Display or hide about us details*/
	$wp_customize->add_setting('philosophy_about_desc_showOrhide',array(
		'default'=>1,
		'transport'=>'refresh'
	));

	$wp_customize->add_control('philosophy_about_desc_showOrhide_ctrl',array(
		'label'=>__('Show Description','philosophy'),
		'section'=>'philosophy_about',
		'settings'=>'philosophy_about_desc_showOrhide',
		'type'=>'checkbox'
	));


	$wp_customize->add_setting('philosophy_about_company',array(
		'default'=>'1-2',
		'transport'=>'refresh'
	));

	$wp_customize->add_control('philosophy_about_company_ctrl',array(
		'label'=>__('Company Details Show In a Row','philosophy'),
		'section'=>'philosophy_about',
		'settings'=>'philosophy_about_company',
		'type'=>'select',
		'choices'=>[
			'1-2'=>'Two in a row',
			'1-3'=>'Three in a row',
		]
	));

	$wp_customize->add_setting('philosophy_icon_color',array(
		'default'=>'#0c0c0c',
		'transport'=>'postMessage'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'philosophy_icon_color_ctrl',array(
		'label'=>'Change Icon Color',
		'section'=>'philosophy_about',
		'settings'=>'philosophy_icon_color'
	)));


	/*service page customizer*/

	$wp_customize->add_section('philosophy_service',array(
		'title'=>__('Services','philosophy'),
		'priority'=>'30',
		'active_callback'=>function(){
			return is_page_template('service.php');
		}
	));

	$wp_customize->add_setting('philosophy_service_heading',array(
		'default'=>__('Service Heading.','philosophy'),
		'transport'=>'postMessage', // refresh
		//'type'=>'option'  // Default - theme_mod
	));

	$wp_customize->add_control('philosophy_service_heading_ctrl',array(
		'label'=>__('Service Heading','philosophy'),
		'section'=>'philosophy_service',
		'settings'=>'philosophy_service_heading',
		'type'=>'text'
	));


}

add_action("customize_register",'philosophy_customize_settings');

function manage_description_area(){
	if(get_theme_mod('philosophy_about_desc_showOrhide') == 1){
		return true;
	}
	return false;
}


