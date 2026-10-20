<?php
defined( 'ABSPATH' ) || exit;

const LEJWANA_ARTICLE_BLOCKS = array( 'core/paragraph', 'core/heading', 'core/list', 'core/list-item', 'core/quote' );
const LEJWANA_ARTICLE_HEADING_LEVELS = array( 2, 3 );

function lejwana_setup_editor() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/main.css', 'editor.css' ) );
}
add_action( 'after_setup_theme', 'lejwana_setup_editor' );

// Articles use only the styles the front end has; everything else stays out of the inserter.
function lejwana_limit_article_blocks( $allowed, $context ) {
	if ( ! $context->post || 'post' !== $context->post->post_type ) {
		return $allowed;
	}
	return LEJWANA_ARTICLE_BLOCKS;
}
add_filter( 'allowed_block_types_all', 'lejwana_limit_article_blocks', 10, 2 );

// h1 is the article title and the page never skips a level, so headings start at h2.
function lejwana_limit_heading_levels( $args, $block_type ) {
	if ( 'core/heading' === $block_type ) {
		$args['attributes']['levelOptions']['default'] = LEJWANA_ARTICLE_HEADING_LEVELS;
	}
	return $args;
}
add_filter( 'register_block_type_args', 'lejwana_limit_heading_levels', 10, 2 );

// Block markup carries wp-block-* classes; the front end styles plain elements, so strip them.
function lejwana_strip_block_classes( $content ) {
	return is_singular( 'post' ) ? preg_replace( '/\s?class="wp-block-[^"]*"/', '', $content ) : $content;
}
add_filter( 'the_content', 'lejwana_strip_block_classes', 20 );
