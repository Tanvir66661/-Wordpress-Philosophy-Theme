<?php

define( 'ATTACHMENTS_SETTINGS_SCREEN', false ); // disable the Settings screen
add_filter( 'attachments_default_instance', '__return_false' ); // disable the default instance


function alpha_attachments($attachments ){
	$fields = array(
		array(
			'name'      => 'title',                         // unique field name
			'type'      => 'text',                          // registered field type
			'label'     => __( 'Title', 'alpha' ),          // label to display

		),
	);

	$args = array(
		"label"       => "Feature Slider",
		"posttype"    => array( 'post' ),
		"filetype"    => array( 'image' ),
		"note"        => 'Add Slider Image',
		"button_text" => __( 'Attach File', 'alpha' ),
		"fields"      => $fields
	);

	$attachments->register( 'slider', $args );   // unique instance name
}


function alpha_testimonial_attachments($attachments ){
	$fields = array(
		array(
			'name'      => 'name',
			'type'      => 'text',
			'label'     => __( 'Name', 'alpha' ),

		),
		array(
			'name'=>'position',
			'type'=>'text',
			'label'=>'Position'
		),
		array(
			'name'=>'company',
			'type'=>'text',
			'label'=>'Company',
		),
		array(
			'name'=>'testimonial',
			'type'=>'textarea',
			'label'=>'Testimonial'
		)
	);

	$args = array(
		"label"       => "Testimonial",
		"posttype"    => array('page' ),
		"filetype"    => array( 'image' ),
		"note"        => 'Add Testimonial Image',
		"button_text" => __( 'Add Testimonial', 'alpha' ),
		"fields"      => $fields
	);

	$attachments->register( 'testimonial', $args );   // unique instance name
}

add_action("attachments_register","alpha_testimonial_attachments");


function alpha_team_attachments($attachments ){
	$fields = array(
		array(
			'name'      => 'name',
			'type'      => 'text',
			'label'     => __( 'Name', 'alpha' ),

		),
		array(
			'name'=>'position',
			'type'=>'text',
			'label'=>'Position'
		),
		array(
			'name'=>'company',
			'type'=>'text',
			'label'=>'Company',
		),
		array(
			'name'=>'bio',
			'type'=>'textarea',
			'label'=>'Bio'
		),
		array(
			'name'=>'email',
			'type'=>'text',
			'label'=>'E-Mail'
		)
	);

	$args = array(
		"label"       => "Team",
		"posttype"    => array('page' ),
		"filetype"    => array( 'image' ),
		"note"        => 'Add Team Image',
		"button_text" => __( 'Add Team', 'alpha' ),
		"fields"      => $fields
	);

	$attachments->register( 'team', $args );   // unique instance name
}

add_action("attachments_register","alpha_team_attachments");

