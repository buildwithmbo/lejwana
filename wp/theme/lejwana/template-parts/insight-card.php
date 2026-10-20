<?php $category = lejwana_get_primary_category( $args['post']->ID ); ?>
<a class="insight-card" href="<?php echo esc_url( get_permalink( $args['post'] ) ); ?>">
  <?php if ( $category ) : ?><span class="insight-card__category"><?php echo esc_html( $category->name ); ?></span><?php endif; ?>
  <h3 class="insight-card__title"><?php echo esc_html( get_the_title( $args['post'] ) ); ?></h3>
</a>
