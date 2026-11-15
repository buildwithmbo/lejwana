<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_ICS_QUERY_VAR = 'lj_ics';
const LEJWANA_ICS_LINE_LIMIT = 74;

function lejwana_register_ics_query_var( $vars ) {
	$vars[] = LEJWANA_ICS_QUERY_VAR;
	return $vars;
}
add_filter( 'query_vars', 'lejwana_register_ics_query_var' );

function lejwana_ics_escape( $text ) {
	return str_replace( array( '\\', ';', ',', "\r\n", "\n" ), array( '\\\\', '\;', '\,', '\n', '\n' ), $text );
}

// RFC 5545 folds lines longer than 75 octets; continuation lines start with a space.
function lejwana_ics_fold( $line ) {
	$lines = array();
	while ( strlen( $line ) > LEJWANA_ICS_LINE_LIMIT ) {
		$cut = LEJWANA_ICS_LINE_LIMIT;
		while ( $cut > 0 && ( ord( $line[ $cut ] ) & 0xC0 ) === 0x80 ) {
			--$cut;
		}
		$lines[] = substr( $line, 0, $cut );
		$line    = ' ' . substr( $line, $cut );
	}
	$lines[] = $line;
	return implode( "\r\n", $lines );
}

function lejwana_ics_event( $event ) {
	$start    = get_post_meta( $event->ID, 'lj_start_date', true );
	$end      = lejwana_get_event_end( $event->ID );
	$place    = get_post_meta( $event->ID, 'lj_place', true );
	$all_days = array(
		'DTSTART;VALUE=DATE:' . str_replace( '-', '', $start ),
		// All-day DTEND is exclusive, so the last day needs a day added.
		'DTEND;VALUE=DATE:' . gmdate( 'Ymd', strtotime( $end . ' +1 day' ) ),
	);
	$lines    = array_merge(
		array(
			'BEGIN:VEVENT',
			'UID:lejwana-event-' . $event->ID . '@' . wp_parse_url( home_url(), PHP_URL_HOST ),
			'DTSTAMP:' . gmdate( 'Ymd\THis\Z' ),
		),
		$all_days,
		array( 'SUMMARY:' . lejwana_ics_escape( get_the_title( $event ) ) )
	);
	if ( $place ) {
		$lines[] = 'LOCATION:' . lejwana_ics_escape( $place );
	}
	$lines[] = 'URL:' . home_url( '/the-southern-cut/events/' );
	$lines[] = 'END:VEVENT';
	return $lines;
}

function lejwana_serve_calendar() {
	$requested = get_query_var( LEJWANA_ICS_QUERY_VAR );
	// The event helpers live in the theme; without it there is nothing to serve.
	if ( '' === $requested || ! function_exists( 'lejwana_get_events' ) ) {
		return;
	}

	if ( 'all' === $requested ) {
		$events = lejwana_get_events( true );
	} else {
		$event  = get_post( absint( $requested ) );
		$events = ( $event && LEJWANA_EVENT_POST_TYPE === $event->post_type && 'publish' === $event->post_status ) ? array( $event ) : array();
	}
	if ( ! $events ) {
		status_header( 404 );
		exit;
	}

	$lines = array( 'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Lejwana//The Southern Cut//EN', 'CALSCALE:GREGORIAN', 'X-WR-CALNAME:The Southern Cut calendar' );
	foreach ( $events as $event ) {
		$lines = array_merge( $lines, lejwana_ics_event( $event ) );
	}
	$lines[] = 'END:VCALENDAR';

	nocache_headers();
	header( 'Content-Type: text/calendar; charset=utf-8' );
	header( 'Content-Disposition: inline; filename="the-southern-cut' . ( 'all' === $requested ? '' : '-' . absint( $requested ) ) . '.ics"' );
	echo implode( "\r\n", array_map( 'lejwana_ics_fold', $lines ) ) . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	exit;
}
add_action( 'template_redirect', 'lejwana_serve_calendar', 1 );
