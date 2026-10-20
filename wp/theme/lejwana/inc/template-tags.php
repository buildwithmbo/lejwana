<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_ENQUIRY_OUTCOMES = array( 'sent', 'invalid', 'failed' );

function lejwana_the_url( $path ) {
	echo esc_url( home_url( '/' . $path ) );
}

function lejwana_the_asset( $path ) {
	echo esc_url( get_template_directory_uri() . '/assets/' . $path );
}

// Each page template declares its own title and description, taken verbatim from the static pages.
function lejwana_set_page_meta( $title, $description ) {
	$GLOBALS['lejwana_page_meta'] = array(
		'title'       => $title,
		'description' => $description,
	);
}

function lejwana_get_page_title() {
	return isset( $GLOBALS['lejwana_page_meta'] ) ? $GLOBALS['lejwana_page_meta']['title'] : '';
}

function lejwana_the_social_meta() {
	if ( ! isset( $GLOBALS['lejwana_page_meta'] ) ) {
		return;
	}
	$meta = $GLOBALS['lejwana_page_meta'];
	$url  = wp_get_canonical_url();
	$tags = array(
		'description' => array( 'name', $meta['description'] ),
		'og:type' => array( 'property', 'website' ),
		'og:title' => array( 'property', $meta['title'] ),
		'og:description' => array( 'property', $meta['description'] ),
		'og:url' => array( 'property', $url ? $url : home_url( '/' ) ),
		'og:image' => array( 'property', get_template_directory_uri() . '/assets/images/og-image.jpg' ),
		'twitter:card' => array( 'name', 'summary_large_image' ),
	);
	foreach ( $tags as $key => $tag ) {
		printf( "<meta %s=\"%s\" content=\"%s\">\n  ", esc_attr( $tag[0] ), esc_attr( $key ), esc_attr( $tag[1] ) );
	}
}

function lejwana_the_nav_state( $slug ) {
	if ( is_page( $slug ) ) {
		echo ' nav__link--active';
	}
}

// The enquiry handler redirects back with ?enquiry=sent|invalid|failed.
function lejwana_get_enquiry_outcome() {
	$outcome = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
	return in_array( $outcome, LEJWANA_ENQUIRY_OUTCOMES, true ) ? $outcome : '';
}
