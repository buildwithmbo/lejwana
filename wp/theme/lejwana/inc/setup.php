<?php
defined( 'ABSPATH' ) || exit;

// Slug => title. Each slug has a matching page-{slug}.php template.
const LEJWANA_PAGES = array(
	'about'       => 'About',
	'sourcing'    => 'Sourcing',
	'trading'     => 'Trading',
	'provenance'  => 'Provenance',
	'advisory'    => 'Advisory',
	'the-network' => 'The Network',
	'contact'     => 'Contact',
	'privacy'     => 'Privacy',
);

function lejwana_insert_page( $slug, $title ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return $existing->ID;
	}
	return wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_name'   => $slug,
			'post_title'  => $title,
		)
	);
}

function lejwana_setup_site() {
	foreach ( LEJWANA_PAGES as $slug => $title ) {
		lejwana_insert_page( $slug, $title );
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', lejwana_insert_page( 'home', 'Home' ) );
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'lejwana_setup_site' );
