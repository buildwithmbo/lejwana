<?php
$category = lejwana_get_primary_category( get_the_ID() );
$heading  = $args['heading'];
$classes  = 'intel-card' . ( ! empty( $args['lead'] ) ? ' intel-card--lead' : '' );
?>
<li class="<?php echo esc_attr( $classes ); ?>"<?php if ( $category ) : ?> data-category="<?php echo esc_attr( $category->slug ); ?>"<?php endif; ?>>
  <a class="intel-card__link" href="<?php the_permalink(); ?>">
    <span class="intel-card__tags"><span class="intel-card__badge">Latest</span><?php if ( $category ) : ?><span class="intel-card__category"><?php echo esc_html( $category->name ); ?></span><?php endif; ?></span>
    <<?php echo esc_html( $heading ); ?> class="intel-card__title"><?php the_title(); ?></<?php echo esc_html( $heading ); ?>>
    <p class="intel-card__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <span class="intel-card__meta"><?php echo esc_html( lejwana_get_article_card_meta( get_the_ID() ) ); ?></span>
  </a>
</li>
