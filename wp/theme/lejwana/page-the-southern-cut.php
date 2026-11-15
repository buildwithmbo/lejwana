<?php
lejwana_set_page_meta(
  'The Southern Cut | Southern Africa’s Diamond-Trade Intelligence | Lejwana',
  'Southern Africa’s diamond-trade intelligence, in one place. Verified sales dates, market signals, producer updates, policy and beneficiation across Botswana, Angola, South Africa, Namibia and Lesotho.'
);
get_header();

$events = lejwana_get_events( true, LEJWANA_HUB_EVENT_COUNT );
$intel  = new WP_Query(
  array(
    'posts_per_page' => LEJWANA_HUB_INTEL_COUNT,
    'category__not_in' => array( lejwana_get_explainers_category_id() ),
    'no_found_rows'  => true,
  )
);
?>

<!-- In the trade: ticker. Duplicates the calendar below for sighted users
     only — aria-hidden, and hidden outright until JS can compute it. -->
<div class="ticker" hidden aria-hidden="true">
  <div class="container container--wide ticker__inner">
    <span class="ticker__label"><span class="ticker__dot" aria-hidden="true"></span>In the trade</span>
    <div class="ticker__viewport">
      <ul class="ticker__track"></ul>
    </div>
  </div>
</div>

<main id="main">
  <!-- Masthead -->
  <section class="masthead">
    <div class="container">
      <h1 class="masthead__name">The Southern Cut</h1>
      <p class="masthead__line">Southern Africa&rsquo;s diamond-trade intelligence, in one place.</p>
      <div class="masthead__standfirst">
        <p>Verified sales, tender and reporting dates, plus short reads on what&rsquo;s moving in the region.</p>
      </div>
    </div>
  </section>

  <!-- What's on + Latest intelligence -->
  <section class="section section--sky hub-grid-section">
    <div class="container container--wide">
      <div class="hub-grid">
        <div class="hub-grid__primary whats-on" id="dates">
          <h2 class="section__title" id="dates-title">What&rsquo;s on</h2>
          <p class="whats-on__intro">Track upcoming ODC Spot Auctions, De Beers Sights and regional diamond sales, with verified dates and details from primary producers and organisers.</p>
          <?php get_template_part( 'template-parts/events-table', null, array( 'events' => $events ) ); ?>
          <a class="button button--ghost hub-grid__more" href="<?php lejwana_the_url( 'the-southern-cut/events/' ); ?>" aria-label="Read more about events and dates">Read more</a>
          <p class="calendar-note">Dates can change. Confirm participation, customer registration, viewing rights and auction terms directly with the organiser before acting.</p>
        </div>

        <div class="hub-grid__secondary intel" id="intelligence" role="region" aria-labelledby="intelligence-title">
          <h2 class="section__title" id="intelligence-title">Latest intelligence</h2>
          <p class="intel__intro">Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.</p>
          <ul class="intel__list" id="intel-list">
            <?php while ( $intel->have_posts() ) : $intel->the_post(); ?>
              <?php get_template_part( 'template-parts/intel-item', null, array( 'heading' => 'h3' ) ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
          </ul>
          <a class="button button--ghost hub-grid__more" href="<?php lejwana_the_url( 'the-southern-cut/insights/' ); ?>" aria-label="Read more about latest intelligence">Read more</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Trade explainers -->
  <section class="section explainer-band" id="explainers" aria-labelledby="explainers-title">
    <div class="container">
      <div class="explainer-grid__head">
        <div>
          <span class="eyebrow">New to the trade?</span>
          <h2 class="section__title" id="explainers-title">Start with the trade explainers</h2>
        </div>
        <p class="section__intro">The diamond trade has a vocabulary of its own. These short guides make the route from mine to market easier to understand.</p>
      </div>
      <ol class="explainer-grid__list">
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">01</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/what-is-a-sight/' ); ?>">What is a Sight?</a></h3>
          <p class="explainer-grid__desc">How De Beers&rsquo; scheduled rough sales work.</p>
        </li>
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">02</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/auction-tender-or-contract-sale/' ); ?>">Auction, tender or contract sale?</a></h3>
          <p class="explainer-grid__desc">The main ways rough reaches buyers.</p>
        </li>
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">03</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/rough-polished-and-yield/' ); ?>">Rough, polished and yield.</a></h3>
          <p class="explainer-grid__desc">What changes when a parcel goes to the factory.</p>
        </li>
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">04</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/what-does-provenance-prove/' ); ?>">What does provenance prove?</a></h3>
          <p class="explainer-grid__desc">The documents, controls and limits behind an origin claim.</p>
        </li>
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">05</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/how-to-read-price-per-carat/' ); ?>">How to read price per carat.</a></h3>
          <p class="explainer-grid__desc">Why size, quality, assortment and sales channel matter.</p>
        </li>
        <li class="explainer-grid__item">
          <span class="explainer-grid__number">06</span>
          <h3 class="explainer-grid__title"><a class="explainer-grid__link" href="<?php lejwana_the_url( 'the-southern-cut/from-botswana-to-the-buyer/' ); ?>">From Botswana to the buyer.</a></h3>
          <p class="explainer-grid__desc">The trade, logistics and documentation journey.</p>
        </li>
      </ol>
      <!-- [CONFIRM: "Start with the explainers" button — the brief's destination /the-southern-cut/explainers
           does not exist yet, so the button is held back until the guides are published] -->
    </div>
  </section>

  <!-- The Southern Cut Brief -->
  <section class="section brief-band" id="brief" aria-labelledby="brief-title">
    <div class="container container--wide brief-band__grid">
      <div class="brief-band__intro">
        <h2 class="section__title" id="brief-title">The Southern Cut Brief</h2>
        <p class="section__intro">A concise regional diamond-trade briefing, sent only when there is something worth knowing.</p>
      </div>
      <!-- [CONFIRM: newsletter handler — front-end validation only; wire to a list provider before launch] -->
      <form class="form brief-band__form" novalidate>
        <div class="form__field">
          <label class="form__label" for="field-email">Email address</label>
          <input class="form__input" type="email" id="field-email" name="email" autocomplete="email" required>
          <p class="form__error">Enter your email address.</p>
        </div>
        <div class="form__row">
          <div class="form__field">
            <label class="form__label" for="field-first-name">First name</label>
            <input class="form__input" type="text" id="field-first-name" name="first-name" autocomplete="given-name" required>
            <p class="form__error">Add your first name.</p>
          </div>
          <div class="form__field">
            <label class="form__label" for="field-company">Company</label>
            <input class="form__input" type="text" id="field-company" name="company" autocomplete="organization" required>
            <p class="form__error">Add your company.</p>
          </div>
        </div>
        <div class="form__field">
          <label class="form__label" for="field-role">Role (optional)</label>
          <input class="form__input" type="text" id="field-role" name="role" autocomplete="organization-title">
        </div>
        <button class="button" type="submit">Subscribe</button>
        <p class="form__note">By subscribing, you agree to receive Lejwana&rsquo;s diamond-trade updates. Unsubscribe at any time.</p>
        <p class="form__success" role="status">Thank you. You have subscribed to The Southern Cut Brief.</p>
      </form>
    </div>
  </section>
</main>

<?php get_footer(); ?>
