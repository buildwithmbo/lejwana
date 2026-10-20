<?php
/**
 * Moves the static What's on rows and Southern Cut articles into WordPress.
 * Run on the local or staging site: wp eval-file scripts/migrate-content.php
 * Safe to re-run: events and articles are matched and updated, never duplicated.
 */
defined( 'ABSPATH' ) || exit;

const MIGRATION_ROOT       = __DIR__ . '/..';
const MIGRATION_TITLE_TAIL = ' | The Southern Cut | Lejwana';
const MIGRATION_CATEGORIES = array(
	'The Region'         => 'the-region',
	'On Provenance'      => 'on-provenance',
	'Rough and Polished' => 'rough-and-polished',
	'Journeys'           => 'journeys',
	'Explainers'         => 'explainers',
);
// Explainers carry no date on the page, so these only fix their order.
const MIGRATION_EXPLAINER_ORDER = array(
	'what-is-a-sight',
	'auction-tender-or-contract-sale',
	'rough-polished-and-yield',
	'what-does-provenance-prove',
	'how-to-read-price-per-carat',
	'from-botswana-to-the-buyer',
);
const MIGRATION_EXPLAINER_DATE = '2026-10-08 09:00:00';

function migration_load( $path ) {
	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="UTF-8">' . file_get_contents( $path ) );
	libxml_clear_errors();
	return new DOMXPath( $dom );
}

function migration_text( $xpath, $query, $context = null ) {
	$node = $xpath->query( $query, $context )->item( 0 );
	return $node ? trim( preg_replace( '/\s+/', ' ', $node->textContent ) ) : '';
}

function migration_inner_html( $node ) {
	$html = '';
	foreach ( $node->childNodes as $child ) {
		$html .= $node->ownerDocument->saveHTML( $child );
	}
	return trim( $html );
}

function migration_events() {
	$xpath  = migration_load( MIGRATION_ROOT . '/the-southern-cut/events.html' );
	$events = array();
	foreach ( $xpath->query( '//table[@id="dates-table"]/tbody/tr' ) as $row ) {
		$link     = $xpath->query( './td[2]/a', $row )->item( 0 );
		$start    = $row->getAttribute( 'data-start' );
		$end      = $row->getAttribute( 'data-end' );
		$events[] = array(
			'title'   => trim( $link->textContent ),
			'url'     => $link->getAttribute( 'href' ),
			'type'    => $row->getAttribute( 'data-type' ),
			'regions' => array_filter( explode( ' ', $row->getAttribute( 'data-region' ) ) ),
			'start'   => $start,
			'end'     => $end === $start ? '' : $end,
			'place'   => migration_text( $xpath, './td[3]', $row ),
			'status'  => migration_text( $xpath, './td[4]', $row ),
		);
	}
	return $events;
}

function migration_find_event( $event ) {
	$matches = get_posts(
		array(
			'post_type'      => 'lj_event',
			'post_status'    => 'any',
			'title'          => $event['title'],
			'meta_key'       => 'lj_start_date',
			'meta_value'     => $event['start'],
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	return $matches ? $matches[0] : 0;
}

function migration_save_event( $event ) {
	$id = migration_find_event( $event );
	$id = wp_insert_post(
		array(
			'ID'          => $id,
			'post_type'   => 'lj_event',
			'post_status' => 'publish',
			'post_title'  => $event['title'],
		)
	);
	$meta = array(
		'lj_type'       => $event['type'],
		'lj_start_date' => $event['start'],
		'lj_end_date'   => $event['end'],
		'lj_link_url'   => $event['url'],
		'lj_place'      => $event['place'],
		'lj_status'     => $event['status'],
	);
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}
	wp_set_object_terms( $id, array_values( $event['regions'] ), 'lj_country' );
	return $id;
}

// The standfirst of each Insights card, keyed by article slug.
function migration_card_standfirsts() {
	$xpath = migration_load( MIGRATION_ROOT . '/the-southern-cut/insights.html' );
	$cards = array();
	foreach ( $xpath->query( '//a[contains(@class,"intel__item")]' ) as $card ) {
		$slug           = basename( $card->getAttribute( 'href' ), '.html' );
		$cards[ $slug ] = migration_text( $xpath, './/p[@class="intel__standfirst"]', $card );
	}
	return $cards;
}

function migration_block( $name, $attrs, $html ) {
	$json = $attrs ? ' ' . wp_json_encode( $attrs ) : '';
	return "<!-- wp:$name$json -->\n$html\n<!-- /wp:$name -->";
}

function migration_list_block( $node ) {
	$tag   = $node->nodeName;
	$items = '';
	foreach ( $node->getElementsByTagName( 'li' ) as $li ) {
		$items .= migration_block( 'list-item', array(), '<li>' . migration_inner_html( $li ) . '</li>' ) . "\n";
	}
	$attrs = 'ol' === $tag ? array( 'ordered' => true ) : array();
	return migration_block( 'list', $attrs, "<$tag>\n$items</$tag>" );
}

function migration_convert_node( $node ) {
	if ( XML_COMMENT_NODE === $node->nodeType ) {
		return '<!--' . $node->textContent . '-->';
	}
	if ( XML_ELEMENT_NODE !== $node->nodeType ) {
		return '';
	}
	$inner = migration_inner_html( $node );
	switch ( $node->nodeName ) {
		case 'p':
			return migration_block( 'paragraph', array(), "<p>$inner</p>" );
		case 'h2':
		case 'h3':
			$level = (int) substr( $node->nodeName, 1 );
			$attrs = 2 === $level ? array() : array( 'level' => $level );
			return migration_block( 'heading', $attrs, "<{$node->nodeName} class=\"wp-block-heading\">$inner</{$node->nodeName}>" );
		case 'ul':
		case 'ol':
			return migration_list_block( $node );
		case 'blockquote':
			$paragraph = migration_block( 'paragraph', array(), "<p>$inner</p>" );
			return migration_block( 'quote', array(), "<blockquote class=\"wp-block-quote\">$paragraph</blockquote>" );
	}
	WP_CLI::error( 'Unhandled element <' . $node->nodeName . '> in an article body.' );
}

function migration_article( $slug, $standfirsts ) {
	$xpath    = migration_load( MIGRATION_ROOT . "/the-southern-cut/$slug.html" );
	$category = migration_text( $xpath, '//span[@class="article__category"]' );
	$meta     = migration_text( $xpath, '//p[@class="article__meta"]' );
	$body     = $xpath->query( '//div[@class="article__body"]' )->item( 0 );
	$blocks   = array();
	$lead     = '';
	foreach ( $body->childNodes as $node ) {
		if ( XML_ELEMENT_NODE === $node->nodeType && 'article__standfirst' === $node->getAttribute( 'class' ) ) {
			$lead = trim( $node->textContent );
			continue;
		}
		$blocks[] = migration_convert_node( $node );
	}
	$title = migration_text( $xpath, '//title' );
	return array(
		'slug'        => $slug,
		'title'       => migration_text( $xpath, '//h1' ),
		'category'    => MIGRATION_CATEGORIES[ $category ],
		'date'        => migration_article_date( $slug, $meta ),
		'excerpt'     => $lead ? $lead : $standfirsts[ $slug ],
		'content'     => implode( "\n\n", array_filter( $blocks ) ),
		'search_title' => substr( $title, -strlen( MIGRATION_TITLE_TAIL ) ) === MIGRATION_TITLE_TAIL ? substr( $title, 0, -strlen( MIGRATION_TITLE_TAIL ) ) : $title,
		'search_description' => $xpath->query( '//meta[@name="description"]/@content' )->item( 0 )->nodeValue,
	);
}

// "Lejwana · April 2026" becomes the first of that month; explainers use their fixed order.
function migration_article_date( $slug, $meta ) {
	$position = array_search( $slug, MIGRATION_EXPLAINER_ORDER, true );
	if ( false !== $position ) {
		return gmdate( 'Y-m-d H:i:s', strtotime( MIGRATION_EXPLAINER_DATE ) - $position * MINUTE_IN_SECONDS );
	}
	preg_match( '/([A-Za-z]+ \d{4})$/', $meta, $month );
	return gmdate( 'Y-m-d 09:00:00', strtotime( '1 ' . $month[1] ) );
}

function migration_save_article( $article ) {
	$existing = get_page_by_path( $article['slug'], OBJECT, 'post' );
	$id       = wp_insert_post(
		array(
			'ID'           => $existing ? $existing->ID : 0,
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_name'    => $article['slug'],
			'post_title'   => $article['title'],
			'post_excerpt' => $article['excerpt'],
			'post_content' => $article['content'],
			'post_date'    => $article['date'],
		),
		true
	);
	wp_set_object_terms( $id, array( $article['category'] ), 'category' );
	update_post_meta( $id, 'lj_search_title', $article['search_title'] );
	update_post_meta( $id, 'lj_search_description', $article['search_description'] );
	return $id;
}

foreach ( migration_events() as $event ) {
	migration_save_event( $event );
}
WP_CLI::log( 'Events migrated: ' . count( migration_events() ) );

$standfirsts = migration_card_standfirsts();
$slugs       = array();
foreach ( glob( MIGRATION_ROOT . '/the-southern-cut/*.html' ) as $path ) {
	$slug = basename( $path, '.html' );
	if ( ! in_array( $slug, array( 'events', 'insights' ), true ) ) {
		$slugs[] = $slug;
	}
}
foreach ( $slugs as $slug ) {
	migration_save_article( migration_article( $slug, $standfirsts ) );
}
WP_CLI::log( 'Articles migrated: ' . count( $slugs ) );
