<?php
defined( 'ABSPATH' ) || exit;

// Registered by the lejwana-core plugin; named here so the theme survives it being off.
const LEJWANA_EVENT_POST_TYPE_SLUG = 'lj_event';
const LEJWANA_HUB_SLUG = 'the-southern-cut';
const LEJWANA_EXPLAINERS_CATEGORY = 'explainers';
const LEJWANA_HUB_EVENT_COUNT = 7;
const LEJWANA_HUB_INTEL_COUNT = 4;
const LEJWANA_TICKER_EVENT_COUNT = 8;
const LEJWANA_RELATED_COUNT = 2;

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
	if ( ! $url && is_home() ) {
		$url = get_permalink( get_option( 'page_for_posts' ) );
	}
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

function lejwana_is_southern_cut() {
	$hub = get_page_by_path( LEJWANA_HUB_SLUG );
	return is_home() || is_singular( 'post' ) || ( $hub && is_page() && ( is_page( $hub->ID ) || in_array( $hub->ID, get_post_ancestors( get_queried_object_id() ), true ) ) );
}

function lejwana_the_nav_state( $slug ) {
	$active = LEJWANA_HUB_SLUG === $slug ? lejwana_is_southern_cut() : is_page( $slug );
	if ( $active ) {
		echo ' nav__link--active';
	}
}

// The enquiry handler redirects back with ?enquiry=sent|invalid|failed.
function lejwana_get_enquiry_outcome() {
	$outcome = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
	return in_array( $outcome, LEJWANA_ENQUIRY_OUTCOMES, true ) ? $outcome : '';
}

function lejwana_get_event_end( $event_id ) {
	$end = get_post_meta( $event_id, 'lj_end_date', true );
	return $end ? $end : get_post_meta( $event_id, 'lj_start_date', true );
}

// All events soonest first. $upcoming_only drops events that finished before today.
function lejwana_get_events( $upcoming_only = false, $limit = -1 ) {
	if ( ! post_type_exists( LEJWANA_EVENT_POST_TYPE_SLUG ) ) {
		return array();
	}
	$events = get_posts(
		array(
			'post_type'      => LEJWANA_EVENT_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'meta_key'       => 'lj_start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
		)
	);
	if ( $upcoming_only ) {
		$today  = wp_date( 'Y-m-d' );
		$events = array_filter(
			$events,
			function ( $event ) use ( $today ) {
				return lejwana_get_event_end( $event->ID ) >= $today;
			}
		);
	}
	return array_slice( $events, 0, $limit > 0 ? $limit : null );
}

// "12–13 Oct 2026", "29 Nov–9 Dec 2026", "30 Dec 2026–2 Jan 2027".
function lejwana_format_event_dates( $start, $end ) {
	$from = date_create( $start );
	$to   = date_create( $end ? $end : $start );
	if ( ! $from || ! $to ) {
		return '';
	}
	if ( $from == $to ) {
		return $from->format( 'j M Y' );
	}
	if ( $from->format( 'Y' ) !== $to->format( 'Y' ) ) {
		return $from->format( 'j M Y' ) . "\u{2013}" . $to->format( 'j M Y' );
	}
	if ( $from->format( 'n' ) !== $to->format( 'n' ) ) {
		return $from->format( 'j M' ) . "\u{2013}" . $to->format( 'j M Y' );
	}
	return $from->format( 'j' ) . "\u{2013}" . $to->format( 'j M Y' );
}

function lejwana_get_primary_category( $post_id ) {
	$categories = get_the_category( $post_id );
	return $categories ? $categories[0] : null;
}

function lejwana_is_explainer( $post_id ) {
	return has_category( LEJWANA_EXPLAINERS_CATEGORY, $post_id );
}

function lejwana_get_article_meta_line( $post_id ) {
	$when = lejwana_is_explainer( $post_id ) ? 'Trade explainer' : get_the_date( 'F Y', $post_id );
	return 'Lejwana · ' . $when;
}

// Same category first; a category with too few posts is topped up from the latest elsewhere.
function lejwana_get_related_posts( $post_id ) {
	$category = lejwana_get_primary_category( $post_id );
	$args     = array(
		'posts_per_page' => LEJWANA_RELATED_COUNT,
		'post__not_in'   => array( $post_id ),
		'no_found_rows'  => true,
	);
	$related  = $category ? get_posts( $args + array( 'cat' => $category->term_id ) ) : array();
	if ( count( $related ) < LEJWANA_RELATED_COUNT ) {
		$taken   = array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) );
		$topup   = get_posts(
			array(
				'posts_per_page' => LEJWANA_RELATED_COUNT - count( $related ),
				'post__not_in'   => $taken,
				'no_found_rows'  => true,
			)
		);
		$related = array_merge( $related, $topup );
	}
	return $related;
}

// Insights lists everything except the trade explainers.
function lejwana_get_explainers_category_id() {
	$term = get_category_by_slug( LEJWANA_EXPLAINERS_CATEGORY );
	return $term ? $term->term_id : 0;
}
