<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_BRIEF_ACTION = 'lejwana_brief';
const LEJWANA_BRIEF_OUTCOMES = array( 'sent', 'invalid', 'failed' );
const LEJWANA_BRIEF_ENDPOINT = 'https://connect.mailerlite.com/api/subscribers';
const LEJWANA_BRIEF_TIMEOUT = 10;
const LEJWANA_BRIEF_MAX_LENGTH = 200;

// Redirects back to the hub so a refresh never resubmits.
function lejwana_finish_brief( $outcome ) {
	$referer = wp_get_referer();
	$target  = $referer ? $referer : home_url( '/the-southern-cut/' );
	wp_safe_redirect( add_query_arg( 'brief', $outcome, remove_query_arg( 'brief', $target ) ) . '#brief' );
	exit;
}

// Key and group ID live in wp-config.php so they never enter the repo.
function lejwana_subscribe_to_mailerlite( $email, $name, $company, $role ) {
	if ( ! defined( 'LEJWANA_MAILERLITE_KEY' ) || '' === LEJWANA_MAILERLITE_KEY ) {
		return false;
	}

	$body = array(
		'email'  => $email,
		'fields' => array(
			'name'    => $name,
			'company' => $company,
			'role'    => $role,
		),
	);
	if ( defined( 'LEJWANA_MAILERLITE_GROUP' ) && '' !== LEJWANA_MAILERLITE_GROUP ) {
		$body['groups'] = array( LEJWANA_MAILERLITE_GROUP );
	}

	$response = wp_remote_post(
		LEJWANA_BRIEF_ENDPOINT,
		array(
			'timeout' => LEJWANA_BRIEF_TIMEOUT,
			'headers' => array(
				'Authorization' => 'Bearer ' . LEJWANA_MAILERLITE_KEY,
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
			),
			'body'    => wp_json_encode( $body ),
		)
	);

	// 200 updates an existing subscriber, 201 creates one.
	return ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), array( 200, 201 ), true );
}

function lejwana_handle_brief() {
	$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, LEJWANA_BRIEF_ACTION ) ) {
		lejwana_finish_brief( 'invalid' );
	}

	// Hidden field a person never fills in; bots do. Report success so they learn nothing.
	if ( '' !== lejwana_posted_text( 'website' ) ) {
		lejwana_finish_brief( 'sent' );
	}

	$email   = lejwana_posted_text( 'email' );
	$name    = lejwana_posted_text( 'first-name' );
	$company = lejwana_posted_text( 'company' );
	$role    = lejwana_posted_text( 'role' );

	$valid = is_email( $email ) && '' !== $name && '' !== $company
		&& strlen( $email ) <= LEJWANA_BRIEF_MAX_LENGTH && strlen( $name ) <= LEJWANA_BRIEF_MAX_LENGTH
		&& strlen( $company ) <= LEJWANA_BRIEF_MAX_LENGTH && strlen( $role ) <= LEJWANA_BRIEF_MAX_LENGTH;
	if ( ! $valid ) {
		lejwana_finish_brief( 'invalid' );
	}

	lejwana_finish_brief( lejwana_subscribe_to_mailerlite( $email, $name, $company, $role ) ? 'sent' : 'failed' );
}
add_action( 'admin_post_nopriv_' . LEJWANA_BRIEF_ACTION, 'lejwana_handle_brief' );
add_action( 'admin_post_' . LEJWANA_BRIEF_ACTION, 'lejwana_handle_brief' );
