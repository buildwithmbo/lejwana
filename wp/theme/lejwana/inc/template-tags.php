<?php
defined( 'ABSPATH' ) || exit;

// Registered by the lejwana-core plugin; named here so the theme survives it being off.
const LEJWANA_EVENT_POST_TYPE_SLUG = 'lj_event';
const LEJWANA_HUB_SLUG = 'the-southern-cut';
const LEJWANA_EXPLAINERS_CATEGORY = 'explainers';
const LEJWANA_HUB_EVENT_COUNT = 5;
const LEJWANA_HUB_THEN_COUNT = 2;
const LEJWANA_MOST_READ_COUNT = 3;
const LEJWANA_ARCHIVE_PER_PAGE = 12;
const LEJWANA_WORDS_PER_MINUTE = 200;
const LEJWANA_TIMEZONE = 'Africa/Gaborone';
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

// The Brief handler redirects back with ?brief=sent|invalid|failed.
function lejwana_get_brief_outcome() {
	$outcome = isset( $_GET['brief'] ) ? sanitize_key( wp_unslash( $_GET['brief'] ) ) : '';
	return in_array( $outcome, LEJWANA_BRIEF_OUTCOMES, true ) ? $outcome : '';
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

// Same wording as main.js; the script recomputes it so a cached page never goes stale.
function lejwana_get_countdown( $start, $end ) {
	$today = new DateTimeImmutable( 'today', new DateTimeZone( LEJWANA_TIMEZONE ) );
	$from  = new DateTimeImmutable( $start, new DateTimeZone( LEJWANA_TIMEZONE ) );
	$to    = new DateTimeImmutable( $end ? $end : $start, new DateTimeZone( LEJWANA_TIMEZONE ) );
	$days  = (int) $today->diff( $from )->format( '%r%a' );

	if ( $today > $to ) {
		return array( 'label' => 'Ended', 'days' => 0 );
	}
	if ( $today == $from ) {
		return array( 'label' => 'Today', 'days' => 0 );
	}
	if ( $today > $from ) {
		return array( 'label' => 'On now', 'days' => 0 );
	}
	return array(
		'label' => 1 === $days ? 'Tomorrow' : 'In ' . $days . ' days',
		'days'  => $days,
	);
}

function lejwana_get_event_type_label( $type ) {
	$labels = defined( 'LEJWANA_EVENT_TYPES' ) ? LEJWANA_EVENT_TYPES : array();
	return isset( $labels[ $type ] ) ? $labels[ $type ] : ucfirst( $type );
}

function lejwana_get_calendar_url( $event_id ) {
	return add_query_arg( 'lj_ics', $event_id, home_url( '/' ) );
}

// Events that finished before today, most recent first.
function lejwana_get_past_events() {
	$upcoming = wp_list_pluck( lejwana_get_events( true ), 'ID' );
	$past     = array_filter(
		lejwana_get_events(),
		function ( $event ) use ( $upcoming ) {
			return ! in_array( $event->ID, $upcoming, true );
		}
	);
	return array_reverse( $past );
}

function lejwana_event_matches( $event, $type, $country ) {
	$type_match    = ! $type || get_post_meta( $event->ID, 'lj_type', true ) === $type;
	$country_match = ! $country || has_term( $country, 'lj_country', $event );
	return $type_match && $country_match;
}

function lejwana_get_read_time( $post_id ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) round( $words / LEJWANA_WORDS_PER_MINUTE ) );
}

function lejwana_get_article_card_meta( $post_id ) {
	return get_the_date( 'F Y', $post_id ) . ' · ' . lejwana_get_read_time( $post_id ) . ' min read';
}

// Articles the client has ticked "Most read" in the editor, newest first.
function lejwana_get_most_read() {
	return get_posts(
		array(
			'posts_per_page'   => LEJWANA_MOST_READ_COUNT,
			'meta_key'         => 'lj_featured',
			'meta_value'       => '1',
			'category__not_in' => array( lejwana_get_explainers_category_id() ),
			'no_found_rows'    => true,
		)
	);
}
