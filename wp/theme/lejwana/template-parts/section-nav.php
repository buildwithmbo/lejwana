<?php
$current = $args['current'];
$pages   = array(
  'hub'   => array( 'The Southern Cut', 'the-southern-cut/' ),
  'intel' => array( 'Intelligence', 'the-southern-cut/insights/' ),
  'cal'   => array( 'Calendar', 'the-southern-cut/events/' ),
);
?>
<nav class="section-nav" aria-label="The Southern Cut">
  <div class="container">
    <?php if ( 'hub' !== $current ) : ?>
      <a class="section-nav__name" href="<?php lejwana_the_url( 'the-southern-cut/' ); ?>">The Southern Cut</a>
    <?php endif; ?>
    <ul class="section-nav__list">
      <?php foreach ( $pages as $key => $page ) : ?>
        <li><a class="section-nav__link<?php echo $key === $current ? ' section-nav__link--active' : ''; ?>" href="<?php lejwana_the_url( $page[1] ); ?>"<?php echo $key === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $page[0] ); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
