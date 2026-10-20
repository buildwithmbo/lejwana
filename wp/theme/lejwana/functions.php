<?php
defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/editor.php';

const LEJWANA_FONT_URLS = array(
	'lejwana-fonts'    => 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;1,400&family=Work+Sans:wght@300;400;500&display=swap',
	'lejwana-wordmark' => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&text=LEJWANA&display=swap',
);

function lejwana_enqueue_assets() {
	foreach ( LEJWANA_FONT_URLS as $handle => $url ) {
		wp_enqueue_style( $handle, $url, array(), null );
	}

	$theme_dir = get_template_directory();
	$theme_url = get_template_directory_uri();
	wp_enqueue_style( 'lejwana', $theme_url . '/assets/css/main.css', array( 'lejwana-fonts' ), filemtime( $theme_dir . '/assets/css/main.css' ) );
	wp_enqueue_script( 'lejwana', $theme_url . '/assets/js/main.js', array(), filemtime( $theme_dir . '/assets/js/main.js' ), array( 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'lejwana_enqueue_assets' );

function lejwana_add_font_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => '',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'lejwana_add_font_hints', 10, 2 );

function lejwana_use_british_language_code() {
	return 'lang="en-GB"';
}
add_filter( 'language_attributes', 'lejwana_use_british_language_code' );

add_filter( 'pre_get_document_title', 'lejwana_get_page_title' );
