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

// Southern Cut child pages: slug => title. Insights is also the posts page.
const LEJWANA_HUB_CHILDREN = array(
	'events'   => 'What’s on',
	'insights' => 'Latest intelligence',
);

// Slug => name. Explainers are articles too, but stay out of the Insights list.
const LEJWANA_CATEGORIES = array(
	'the-region'         => 'The Region',
	'on-provenance'      => 'On Provenance',
	'rough-and-polished' => 'Rough and Polished',
	'journeys'           => 'Journeys',
	'explainers'         => 'Explainers',
);

function lejwana_insert_page( $slug, $title, $parent_id = 0 ) {
	$path     = $parent_id ? get_page_uri( $parent_id ) . '/' . $slug : $slug;
	$existing = get_page_by_path( $path );
	if ( $existing ) {
		return $existing->ID;
	}
	return wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_name'   => $slug,
			'post_title'  => $title,
			'post_parent' => $parent_id,
		)
	);
}

function lejwana_setup_southern_cut() {
	$hub_id = lejwana_insert_page( LEJWANA_HUB_SLUG, 'The Southern Cut' );
	foreach ( LEJWANA_HUB_CHILDREN as $slug => $title ) {
		$child_id = lejwana_insert_page( $slug, $title, $hub_id );
		if ( 'insights' === $slug ) {
			update_option( 'page_for_posts', $child_id );
		}
	}
	foreach ( LEJWANA_CATEGORIES as $slug => $name ) {
		if ( ! term_exists( $slug, 'category' ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}
}

function lejwana_setup_site() {
	foreach ( LEJWANA_PAGES as $slug => $title ) {
		lejwana_insert_page( $slug, $title );
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', lejwana_insert_page( 'home', 'Home' ) );
	lejwana_setup_southern_cut();
	// Pages keep their own paths; only articles live under the hub.
	update_option( 'permalink_structure', '/' . LEJWANA_HUB_SLUG . '/%postname%/' );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'lejwana_setup_site' );
