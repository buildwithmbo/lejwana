<?php
lejwana_set_page_meta(
  'Latest intelligence | The Southern Cut | Lejwana',
  'Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.'
);
get_header();

$categories = get_categories(
  array(
    'exclude' => array( lejwana_get_explainers_category_id() ),
    'orderby' => 'term_id',
  )
);
$articles   = new WP_Query(
  array(
    'posts_per_page'   => -1,
    'category__not_in' => array( lejwana_get_explainers_category_id() ),
    'no_found_rows'    => true,
  )
);
?>

<main id="main">
  <section class="section section--sky hub-grid-section">
    <div class="container container--wide">
      <a class="text-link listing-back" href="<?php lejwana_the_url( 'the-southern-cut/' ); ?>">Back to The Southern Cut</a>
      <div class="intel">
        <h1 class="section__title">Latest intelligence</h1>
        <p class="intel__intro">Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.</p>
        <div class="intel__filters pill-group" role="group" aria-label="Filter by category" hidden>
          <button type="button" class="pill pill--active" data-filter="all" aria-pressed="true">All stories</button>
          <?php foreach ( $categories as $category ) : ?>
            <button type="button" class="pill" data-filter="<?php echo esc_attr( $category->slug ); ?>" aria-pressed="false"><?php echo esc_html( $category->name ); ?></button>
          <?php endforeach; ?>
        </div>
        <ul class="intel__list" id="intel-list">
          <?php while ( $articles->have_posts() ) : $articles->the_post(); ?>
            <?php get_template_part( 'template-parts/intel-item', null, array( 'heading' => 'h2' ) ); ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
