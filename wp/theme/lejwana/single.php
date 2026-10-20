<?php
$article_id = get_queried_object_id();
$search_title       = get_post_meta( $article_id, 'lj_search_title', true );
$search_description = get_post_meta( $article_id, 'lj_search_description', true );
lejwana_set_page_meta(
  ( $search_title ? $search_title : get_the_title( $article_id ) ) . ' | The Southern Cut | Lejwana',
  $search_description ? $search_description : get_the_excerpt( $article_id )
);
get_header();
$category = lejwana_get_primary_category( $article_id );
$related  = lejwana_get_related_posts( $article_id );
?>

<main id="main">
  <?php while ( have_posts() ) : the_post(); ?>
    <article class="article">
      <header class="article__header">
        <?php if ( $category ) : ?><span class="article__category"><?php echo esc_html( $category->name ); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p class="article__meta"><?php echo esc_html( lejwana_get_article_meta_line( $article_id ) ); ?></p>
      </header>
      <div class="article__body">
        <?php if ( lejwana_is_explainer( $article_id ) ) : ?>
          <p class="article__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
        <?php endif; ?>
        <?php the_content(); ?>
      </div>
      <p class="article__closing">For questions on this, <a class="text-link" href="mailto:polished@lejwana.com">write to us</a>.</p>
      <?php if ( $related ) : ?>
        <div class="container related">
          <h2 class="related__title"><?php echo lejwana_is_explainer( $article_id ) ? 'Next explainers' : 'Related'; ?></h2>
          <div class="related__grid">
            <?php foreach ( $related as $post_item ) : ?>
              <?php get_template_part( 'template-parts/insight-card', null, array( 'post' => $post_item ) ); ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
