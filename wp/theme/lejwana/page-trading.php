<?php
lejwana_set_page_meta(
  'Trading | Natural Polished Diamonds, GIA Graded | Lejwana',
  'Natural polished diamonds from four producing countries. Documented from origin, every stone GIA graded, laser inscribed and provenance-verified. Enquire about a stone.'
);
get_header();
?>

<main id="main">
  <!-- Hero -->
  <section class="hero hero--page hero--split">
    <div class="container hero__split">
      <div>
        <span class="eyebrow">Services &middot; Trading</span>
        <h1>Certified natural polished diamonds.</h1>
        <p class="hero__subheading">Supplied through local trade relationships, against your requirement.</p>
      </div>
      <div class="hero__figure">
        <img class="hero__figure-image" src="<?php lejwana_the_asset( 'images/trading-hero.jpg' ); ?>" width="1357" height="768" alt="" aria-hidden="true">
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="section">
    <div class="container">
      <div class="spec-row">
        <div>
          <h2 class="section__title">The goods</h2>
          <p>Natural polished only, cut from rough mined in Botswana, Angola, South Africa and Namibia. Singles, matched pairs, parcels and layouts, with particular strength in fancy colour. No laboratory-grown, no treated stones.</p>
        </div>
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/trading-goods.jpg' ); ?>" width="1167" height="896" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="spec-row">
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/trading-documentation.jpg' ); ?>" width="1141" height="928" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
        <div>
          <h2 class="section__title">Documentation</h2>
          <p>GIA certification and laser inscription, with Kimberley Process paperwork and country-of-origin records travelling with the goods where they exist.</p>
        </div>
      </div>

      <div class="spec-row">
        <div>
          <h2 class="section__title">How goods move</h2>
          <p>By enquiry rather than a public list. Tell us the requirement and we come back with what is available and where it sits against the list. Standing requirements are kept on file and goods offered as they come in.</p>
        </div>
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/trading-process.jpg' ); ?>" width="1151" height="912" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-band">
    <div class="container">
      <h2 class="cta-band__title">Discuss polished goods.</h2>
      <a class="button button--on-dark" href="<?php lejwana_the_url( 'contact/' ); ?>">Contact us</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
