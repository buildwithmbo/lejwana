<?php
lejwana_set_page_meta(
  'All intelligence | The Southern Cut | Lejwana',
  'Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.'
);
get_header();

$base       = home_url( '/the-southern-cut/insights/' );
$categories = get_categories(
  array(
    'exclude' => array( lejwana_get_explainers_category_id() ),
    'orderby' => 'term_id',
  )
);
$topic      = isset( $_GET['topic'] ) ? sanitize_key( wp_unslash( $_GET['topic'] ) ) : '';
$topic      = in_array( $topic, wp_list_pluck( $categories, 'slug' ), true ) ? $topic : '';
$paged      = max( 1, (int) get_query_var( 'paged' ) );
$articles   = new WP_Query(
  array(
    'posts_per_page'   => LEJWANA_ARCHIVE_PER_PAGE,
    'paged'            => $paged,
    'category__not_in' => array( lejwana_get_explainers_category_id() ),
    'category_name'    => $topic,
  )
);
$page_count = (int) $articles->max_num_pages;
// With one page the browser can filter in place; with more, the server does it.
$filterable = 1 === $page_count && ! $topic;
$first      = true;
?>

<main id="main">
  <?php get_template_part( 'template-parts/section-nav', null, array( 'current' => 'intel' ) ); ?>

  <section class="section">
    <div class="container">
      <h1 class="section__title">All intelligence</h1>
      <p class="section__intro">Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.</p>
      <div class="pill-group intel-filter" role="group" aria-label="Filter by topic">
        <a class="pill<?php echo $topic ? '' : ' pill--active'; ?>" href="<?php echo esc_url( $base ); ?>" data-filter="all"<?php echo $topic ? '' : ' aria-current="true"'; ?>>All stories</a>
        <?php foreach ( $categories as $category ) : ?>
          <a class="pill<?php echo $category->slug === $topic ? ' pill--active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'topic', $category->slug, $base ) ); ?>" data-filter="<?php echo esc_attr( $category->slug ); ?>"<?php echo $category->slug === $topic ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $category->name ); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--sky">
    <div class="container container--wide">
      <ul class="intel-grid"<?php echo $filterable ? ' data-filterable' : ''; ?>>
        <?php while ( $articles->have_posts() ) : $articles->the_post(); ?>
          <?php get_template_part( 'template-parts/intel-card', null, array( 'heading' => 'h2', 'lead' => 1 === $paged && $first ) ); ?>
          <?php $first = false; ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
      <p class="event-empty intel-empty"<?php echo $articles->have_posts() ? ' hidden' : ''; ?>>No reads published yet.</p>
      <?php if ( $page_count > 1 ) : ?>
        <nav class="pager" aria-label="Pages of intelligence">
          <?php if ( $paged > 1 ) : ?>
            <a class="pager__link" href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>" rel="prev">&larr; Newer</a>
          <?php else : ?>
            <span class="pager__link pager__link--off">&larr; Newer</span>
          <?php endif; ?>
          <?php for ( $number = 1; $number <= $page_count; $number++ ) : ?>
            <a class="pager__link<?php echo $number === $paged ? ' pager__link--current' : ''; ?>" href="<?php echo esc_url( get_pagenum_link( $number ) ); ?>"<?php echo $number === $paged ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $number ); ?></a>
          <?php endfor; ?>
          <?php if ( $paged < $page_count ) : ?>
            <a class="pager__link" href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" rel="next">Older &rarr;</a>
          <?php else : ?>
            <span class="pager__link pager__link--off">Older &rarr;</span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
