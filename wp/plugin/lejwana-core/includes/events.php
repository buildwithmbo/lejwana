<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_EVENT_POST_TYPE = 'lj_event';
const LEJWANA_COUNTRY_TAXONOMY = 'lj_country';
const LEJWANA_EVENT_NONCE = 'lejwana_event_fields';

// Slugs match the data-region values the front-end filter already reads.
const LEJWANA_COUNTRIES = array(
	'botswana'     => 'Botswana',
	'angola'       => 'Angola',
	'south-africa' => 'South Africa',
	'namibia'      => 'Namibia',
	'lesotho'      => 'Lesotho',
);

const LEJWANA_EVENT_TYPES = array(
	'sale'   => 'Sale',
	'tender' => 'Tender',
	'result' => 'Result',
	'sight'  => 'Sight',
	'event'  => 'Event',
);

function lejwana_event_fields() {
	return array(
		'lj_start_date' => array( 'label' => 'Start date', 'kind' => 'date' ),
		'lj_end_date'   => array( 'label' => 'End date (leave empty for a single day)', 'kind' => 'date' ),
		'lj_type'       => array( 'label' => 'Type', 'kind' => 'type' ),
		'lj_link_url'   => array( 'label' => 'Link URL', 'kind' => 'url' ),
		'lj_place'      => array( 'label' => 'Place', 'kind' => 'text' ),
		'lj_source_url' => array( 'label' => 'Source URL (where the date was verified)', 'kind' => 'url' ),
		'lj_status'     => array( 'label' => 'Status note', 'kind' => 'text' ),
	);
}

function lejwana_sanitise_date( $value ) {
	$date = DateTime::createFromFormat( '!Y-m-d', (string) $value );
	return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';
}

function lejwana_sanitise_type( $value ) {
	return array_key_exists( $value, LEJWANA_EVENT_TYPES ) ? $value : '';
}

function lejwana_register_events() {
	register_post_type(
		LEJWANA_EVENT_POST_TYPE,
		array(
			'labels'              => array(
				'name'          => 'Events',
				'singular_name' => 'Event',
				'add_new_item'  => 'Add event',
				'edit_item'     => 'Edit event',
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
		)
	);

	register_taxonomy(
		LEJWANA_COUNTRY_TAXONOMY,
		LEJWANA_EVENT_POST_TYPE,
		array(
			'labels'            => array(
				'name'          => 'Countries',
				'singular_name' => 'Country',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'meta_box_cb'       => 'post_categories_meta_box',
		)
	);

	$sanitisers = array(
		'date' => 'lejwana_sanitise_date',
		'type' => 'lejwana_sanitise_type',
		'url'  => 'esc_url_raw',
		'text' => 'sanitize_text_field',
	);

	foreach ( lejwana_event_fields() as $key => $field ) {
		register_post_meta(
			LEJWANA_EVENT_POST_TYPE,
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => $sanitisers[ $field['kind'] ],
				'show_in_rest'      => false,
			)
		);
	}
}
add_action( 'init', 'lejwana_register_events' );

function lejwana_seed_countries() {
	lejwana_register_events();
	foreach ( LEJWANA_COUNTRIES as $slug => $name ) {
		if ( ! term_exists( $slug, LEJWANA_COUNTRY_TAXONOMY ) ) {
			wp_insert_term( $name, LEJWANA_COUNTRY_TAXONOMY, array( 'slug' => $slug ) );
		}
	}
	flush_rewrite_rules();
}
register_activation_hook( dirname( __DIR__ ) . '/lejwana-core.php', 'lejwana_seed_countries' );

function lejwana_add_event_meta_box() {
	add_meta_box( 'lejwana-event-fields', 'Event details', 'lejwana_render_event_meta_box', LEJWANA_EVENT_POST_TYPE, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'lejwana_add_event_meta_box' );

function lejwana_render_event_meta_box( $post ) {
	wp_nonce_field( LEJWANA_EVENT_NONCE, LEJWANA_EVENT_NONCE );
	echo '<table class="form-table" role="presentation">';

	foreach ( lejwana_event_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';

		if ( 'type' === $field['kind'] ) {
			echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
			foreach ( LEJWANA_EVENT_TYPES as $slug => $label ) {
				echo '<option value="' . esc_attr( $slug ) . '"' . selected( $value, $slug, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		} else {
			$input_type = array( 'date' => 'date', 'url' => 'url', 'text' => 'text' )[ $field['kind'] ];
			echo '<input class="regular-text" type="' . esc_attr( $input_type ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		}

		echo '</td></tr>';
	}

	echo '</table>';
}

function lejwana_save_event_fields( $post_id ) {
	if ( ! isset( $_POST[ LEJWANA_EVENT_NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ LEJWANA_EVENT_NONCE ] ) ), LEJWANA_EVENT_NONCE ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( lejwana_event_fields() ) as $key ) {
		$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post_' . LEJWANA_EVENT_POST_TYPE, 'lejwana_save_event_fields' );

// Admin list shows the soonest event first, not the newest entry.
function lejwana_order_events_by_start_date( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || LEJWANA_EVENT_POST_TYPE !== $query->get( 'post_type' ) || $query->get( 'orderby' ) ) {
		return;
	}
	$query->set( 'meta_key', 'lj_start_date' );
	$query->set( 'orderby', 'meta_value' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'lejwana_order_events_by_start_date' );
