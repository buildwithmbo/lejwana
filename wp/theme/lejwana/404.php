<?php
lejwana_set_page_meta(
  'Page not found | Lejwana',
  'The page you asked for could not be found. Return to the Lejwana homepage or contact us.'
);
get_header();
?>

<main id="main">
  <section class="section" aria-labelledby="not-found-title">
    <div class="container container--reading">
      <h1 class="section__title" id="not-found-title">This page cannot be found.</h1>
      <p class="section__intro">The address may have changed, or the page may have moved. Return to the homepage, or contact us and we will point you to what you need.</p>
      <a class="button" href="<?php lejwana_the_url( '' ); ?>">Go to the homepage</a>
      <a class="button button--ghost" href="<?php lejwana_the_url( 'contact/' ); ?>">Contact us</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
