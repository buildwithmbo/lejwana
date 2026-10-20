<?php
lejwana_set_page_meta(
  'What’s on | The Southern Cut | Lejwana',
  'Officially published sales, tenders, reporting dates and events, checked against organiser and producer sources. Access requirements vary — confirm before acting.'
);
get_header();
?>

<main id="main">
  <section class="section section--sky hub-grid-section">
    <div class="container container--wide">
      <a class="text-link listing-back" href="<?php lejwana_the_url( 'the-southern-cut/' ); ?>">Back to The Southern Cut</a>
      <div class="whats-on">
        <h1 class="section__title">What&rsquo;s on</h1>
        <p class="whats-on__intro">Officially published sales, tenders, reporting dates and events, checked against organiser and producer sources. Access requirements vary &mdash; confirm before acting.</p>
        <div class="whats-on__filters pill-group" role="group" aria-label="Filter by type" hidden>
          <button type="button" class="pill pill--active" data-filter="all" aria-pressed="true">All</button>
          <button type="button" class="pill" data-filter="sale" aria-pressed="false">Sales &amp; auctions</button>
          <button type="button" class="pill" data-filter="tender" aria-pressed="false">Tenders</button>
          <button type="button" class="pill" data-filter="result" aria-pressed="false">Results</button>
          <button type="button" class="pill" data-filter="event" aria-pressed="false">Events</button>
        </div>
        <div class="whats-on__region pill-group" role="group" aria-label="Filter the calendar by country" hidden>
          <span class="pill-group__label">Country</span>
          <button type="button" class="pill pill--active" data-filter="all" aria-pressed="true">All countries</button>
          <button type="button" class="pill" data-filter="botswana" aria-pressed="false">Botswana</button>
          <button type="button" class="pill" data-filter="angola" aria-pressed="false">Angola</button>
          <button type="button" class="pill" data-filter="south-africa" aria-pressed="false">South Africa</button>
          <button type="button" class="pill" data-filter="namibia" aria-pressed="false">Namibia</button>
          <button type="button" class="pill" data-filter="lesotho" aria-pressed="false">Lesotho</button>
        </div>
        <?php get_template_part( 'template-parts/events-table', null, array( 'events' => lejwana_get_events() ) ); ?>
        <p class="calendar-note">Dates can change. Confirm participation, customer registration, viewing rights and auction terms directly with the organiser before acting.</p>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
