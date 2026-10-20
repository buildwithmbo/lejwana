<?php
lejwana_set_page_meta(
  'Provenance | Natural Polished Diamonds from Southern Africa | Lejwana',
  'Provenance records for natural polished diamonds from Southern Africa, assessed stone by stone: GIA certification, laser inscription and Kimberley Process paperwork.'
);
get_header();
?>

<main id="main">
  <!-- Hero -->
  <section class="hero hero--page hero--split">
    <div class="container hero__split">
      <div>
        <span class="eyebrow">Services &middot; Provenance</span>
        <h1>Records around the goods.</h1>
        <p class="hero__subheading">Assessed stone by stone, shared where the record allows.</p>
      </div>
      <div class="hero__figure">
        <img class="hero__figure-image" src="<?php lejwana_the_asset( 'images/trading-documentation.jpg' ); ?>" width="1141" height="928" alt="" aria-hidden="true">
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="section">
    <div class="container">
      <div class="spec-row">
        <div>
          <h2 class="section__title">What exists</h2>
          <p>Provenance is assessed stone by stone, or parcel by parcel. What is available varies with the goods and the source: GIA certification, laser inscription, Kimberley Process certification, rough imagery, cutting and polishing material, and transaction records.</p>
        </div>
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/sourcing-checklist.jpg' ); ?>" width="1191" height="880" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="spec-row">
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/advisory-hero.jpg' ); ?>" width="1339" height="784" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
        <div>
          <h2 class="section__title">The digital journey</h2>
          <p>Where the record allows, this is consolidated into a digital journey a jeweller can pass to an end client, viewable and traceable wherever they are.</p>
        </div>
      </div>

      <div class="spec-row">
        <div>
          <h2 class="section__title">The honest limit</h2>
          <p>Completeness depends on what was captured upstream. The journey is offered stone by stone, never as a blanket feature of every diamond.</p>
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
      <h2 class="cta-band__title">Discuss provenance records.</h2>
      <a class="button button--on-dark" href="<?php lejwana_the_url( 'contact/' ); ?>">Contact us</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
