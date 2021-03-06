<?php
/**
 * This file config of custom control for customizer.
 *
 * @package Xpertiz
 */

$xpertiz_customizer_settings['topbar-switch'] = array(
	'section'     => 'top-bar',
	'type'        => 'custom',
	'custom_type' => 'xpertiz-switcher',
	'label'       => esc_html__( 'Top Bar', 'xpertiz' ),
	'default'     => 'false',
	'choices'     => array(
		'false' => esc_html__( 'Hidden' , 'xpertiz' ),
		'true'  => esc_html__( 'Show' , 'xpertiz' ),
	),
);

$xpertiz_customizer_settings['topbar-left'] = array(
	'section'     => 'top-bar',
	'type'        => 'textarea',
	'transport'   => 'refresh',
	'default'     => '',
	'label'       => esc_html__( 'Left Top Bar Content', 'xpertiz' ),
);

$xpertiz_customizer_settings['topbar-right'] = array(
	'section'     => 'top-bar',
	'type'        => 'textarea',
	'transport'   => 'refresh',
	'default'     => '',
	'label'       => esc_html__( 'Right Top Bar Content', 'xpertiz' ),
);

$xpertiz_customizer_settings['topbar-home-font-color'] = array(
	'section'     => 'top-bar',
	'type'        => 'color',
	'default'     => '#333333',
	'transport'   => 'refresh',
	'label'       => esc_html__( 'Font Color', 'xpertiz' ),
);

$xpertiz_customizer_settings['topbar-home-background-color'] = array(
	'section'     => 'top-bar',
	'type'        => 'color-alpha',
	'default'     => '#f5f5f5',
	'capability'  => 'edit_theme_options',
	'transport'   => 'refresh',
	'show_opacity'  => true,
	'label'       => esc_html__( 'Background Color', 'xpertiz' ),
);

$xpertiz_customizer_settings['topbar-home-border-color'] = array(
	'section'     => 'top-bar',
	'type'        => 'color-alpha',
	'default'     => '#e3e3e3',
	'transport'   => 'refresh',
	'label'       => esc_html__( 'Border Bottom Color', 'xpertiz' ),
);
