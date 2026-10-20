<?php
lejwana_set_page_meta(
  'Page not found | Lejwana',
  '[CONFIRM: 404 meta description]'
);
get_header();
?>

<main id="main">
  <section class="section" aria-labelledby="not-found-title">
    <div class="container container--reading">
      <h1 class="section__title" id="not-found-title">[CONFIRM: 404 headline]</h1>
      <p class="section__intro">[CONFIRM: 404 message]</p>
      <a class="button" href="<?php lejwana_the_url( '' ); ?>">Go to the homepage</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
