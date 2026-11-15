<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_ARTICLE_NONCE = 'lejwana_article_search_fields';
const LEJWANA_FEATURED_META = 'lj_featured';

// Empty means the theme falls back to the article title and excerpt.
function lejwana_article_search_fields() {
	return array(
		'lj_search_title'       => array( 'label' => 'Search title', 'multiline' => false ),
		'lj_search_description' => array( 'label' => 'Search description', 'multiline' => true ),
	);
}

function lejwana_register_article_fields() {
	foreach ( array_keys( lejwana_article_search_fields() ) as $key ) {
		register_post_meta(
			'post',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => false,
			)
		);
	}
}
add_action( 'init', 'lejwana_register_article_fields' );

function lejwana_register_featured_field() {
	register_post_meta(
		'post',
		LEJWANA_FEATURED_META,
		array(
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => function ( $value ) {
				return '1' === $value ? '1' : '';
			},
			'show_in_rest'      => false,
		)
	);
}
add_action( 'init', 'lejwana_register_featured_field' );

function lejwana_add_article_meta_box() {
	add_meta_box( 'lejwana-article-search', 'Search results text', 'lejwana_render_article_meta_box', 'post', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'lejwana_add_article_meta_box' );

function lejwana_render_article_meta_box( $post ) {
	wp_nonce_field( LEJWANA_ARTICLE_NONCE, LEJWANA_ARTICLE_NONCE );
	echo '<p>Shown by search engines. Leave empty to use the article title and summary.</p>';
	echo '<table class="form-table" role="presentation">';

	foreach ( lejwana_article_search_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		if ( $field['multiline'] ) {
			echo '<textarea class="large-text" rows="3" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input class="large-text" type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</td></tr>';
	}

	echo '</table>';

	$featured = get_post_meta( $post->ID, LEJWANA_FEATURED_META, true );
	echo '<p><label><input type="checkbox" name="' . esc_attr( LEJWANA_FEATURED_META ) . '" value="1"' . checked( $featured, '1', false ) . '> Show in the "Most read" list on The Southern Cut (the three newest ticked articles appear)</label></p>';
}

function lejwana_save_article_fields( $post_id ) {
	if ( ! isset( $_POST[ LEJWANA_ARTICLE_NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ LEJWANA_ARTICLE_NONCE ] ) ), LEJWANA_ARTICLE_NONCE ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( lejwana_article_search_fields() ) as $key ) {
		$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		update_post_meta( $post_id, $key, $value );
	}
	update_post_meta( $post_id, LEJWANA_FEATURED_META, isset( $_POST[ LEJWANA_FEATURED_META ] ) ? '1' : '' );
}
add_action( 'save_post_post', 'lejwana_save_article_fields' );
