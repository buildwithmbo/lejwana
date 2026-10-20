<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_ENQUIRY_ACTION = 'lejwana_enquiry';
const LEJWANA_ENQUIRY_RECIPIENT = 'info@lejwana.com';
const LEJWANA_ENQUIRY_MAX_LENGTH = 5000;

const LEJWANA_ENQUIRY_TYPES = array(
	'sourcing' => 'Sourcing',
	'trading'  => 'Trading',
	'advisory' => 'Advisory',
	'general'  => 'General',
);

const LEJWANA_SPEC_FIELDS = array(
	'shape'         => 'Shape',
	'weight'        => 'Weight',
	'colour'        => 'Colour',
	'clarity'       => 'Clarity',
	'cut'           => 'Cut',
	'fluorescence'  => 'Fluorescence',
	'certification' => 'Certification',
	'provenance'    => 'Provenance',
	'commercial'    => 'Commercial',
);

function lejwana_posted_text( $key ) {
	return isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

function lejwana_finish_enquiry( $outcome ) {
	$referer = wp_get_referer();
	$target  = $referer ? $referer : home_url( '/contact/' );
	wp_safe_redirect( add_query_arg( 'enquiry', $outcome, $target ) . '#enquiry' );
	exit;
}

function lejwana_build_enquiry_body( $name, $company, $email, $type, $message ) {
	$lines = array(
		'Name: ' . $name,
		'Company: ' . $company,
		'Email: ' . $email,
		'Enquiry: ' . LEJWANA_ENQUIRY_TYPES[ $type ],
	);

	foreach ( LEJWANA_SPEC_FIELDS as $key => $label ) {
		$value = lejwana_posted_text( $key );
		if ( '' !== $value ) {
			$lines[] = $label . ': ' . $value;
		}
	}

	$lines[] = '';
	$lines[] = $message;
	return implode( "\n", $lines );
}

function lejwana_handle_enquiry() {
	$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, LEJWANA_ENQUIRY_ACTION ) ) {
		lejwana_finish_enquiry( 'invalid' );
	}

	// Hidden field a person never fills in; bots do. Report success so they learn nothing.
	if ( '' !== lejwana_posted_text( 'website' ) ) {
		lejwana_finish_enquiry( 'sent' );
	}

	$name    = lejwana_posted_text( 'name' );
	$company = lejwana_posted_text( 'company' );
	$email   = lejwana_posted_text( 'email' );
	$type    = lejwana_posted_text( 'enquiry' );
	$message = isset( $_POST['message'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) ) : '';

	$valid = '' !== $name && '' !== $company && is_email( $email )
		&& array_key_exists( $type, LEJWANA_ENQUIRY_TYPES )
		&& '' !== $message && strlen( $message ) <= LEJWANA_ENQUIRY_MAX_LENGTH;
	if ( ! $valid ) {
		lejwana_finish_enquiry( 'invalid' );
	}

	$sent = wp_mail(
		LEJWANA_ENQUIRY_RECIPIENT,
		'Website enquiry: ' . LEJWANA_ENQUIRY_TYPES[ $type ] . ' from ' . $company,
		lejwana_build_enquiry_body( $name, $company, $email, $type, $message ),
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	lejwana_finish_enquiry( $sent ? 'sent' : 'failed' );
}
add_action( 'admin_post_nopriv_' . LEJWANA_ENQUIRY_ACTION, 'lejwana_handle_enquiry' );
add_action( 'admin_post_' . LEJWANA_ENQUIRY_ACTION, 'lejwana_handle_enquiry' );
