<?php
$category = lejwana_get_primary_category( get_the_ID() );
$heading  = $args['heading'];
?>
<li>
  <a class="intel__item" href="<?php the_permalink(); ?>"<?php if ( $category ) : ?> data-category="<?php echo esc_attr( $category->slug ); ?>"<?php endif; ?>>
    <?php if ( $category ) : ?><span class="intel__category"><?php echo esc_html( $category->name ); ?></span><?php endif; ?>
    <<?php echo esc_html( $heading ); ?> class="intel__item-title"><?php the_title(); ?></<?php echo esc_html( $heading ); ?>>
    <p class="intel__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <span class="intel__read" aria-hidden="true">Read More</span>
  </a>
</li>
