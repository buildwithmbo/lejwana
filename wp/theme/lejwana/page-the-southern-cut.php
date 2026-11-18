<?php
lejwana_set_page_meta(
  'The Southern Cut | Southern Africa’s Diamond-Trade Intelligence | Lejwana',
  'Southern Africa’s diamond-trade intelligence, in one place. Verified sales dates, market signals, producer updates, policy and beneficiation across Botswana, Angola, South Africa, Namibia and Lesotho.'
);
get_header();

$articles  = get_posts(
  array(
    'posts_per_page'   => 3,
    'category__not_in' => array( lejwana_get_explainers_category_id() ),
    'no_found_rows'    => true,
  )
);
$lead      = array_shift( $articles );
$most_read = lejwana_get_most_read();
$upcoming  = lejwana_get_events( true );
$next      = $upcoming ? $upcoming[0] : null;
$then      = array_slice( $upcoming, 1, LEJWANA_HUB_THEN_COUNT );
$type      = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
$type      = array_key_exists( $type, LEJWANA_EVENT_TYPES ) ? $type : '';
$shown     = array_slice( array_filter( $upcoming, function ( $event ) use ( $type ) { return lejwana_event_matches( $event, $type, '' ); } ), 0, LEJWANA_HUB_EVENT_COUNT );
$shown_ids = wp_list_pluck( $shown, 'ID' );
$type_tabs = array( '' => 'All', 'result' => 'Results', 'tender' => 'Tenders', 'sale' => 'Sales', 'sight' => 'Sights' );
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

<?php get_template_part( 'template-parts/section-nav', null, array( 'current' => 'hub' ) ); ?>

  <?php if ( $lead || $next ) : ?>
  <!-- Lead story + next event -->
  <section class="section" aria-labelledby="lead-title">
    <div class="container container--wide front__grid">
      <?php if ( $lead ) : ?>
        <article class="front__lead">
          <?php $lead_category = lejwana_get_primary_category( $lead->ID ); ?>
          <?php if ( $lead_category ) : ?><span class="intel-card__category"><?php echo esc_html( $lead_category->name ); ?></span><?php endif; ?>
          <h2 class="intel-card__title" id="lead-title"><?php echo esc_html( get_the_title( $lead ) ); ?></h2>
          <p class="intel-card__standfirst"><?php echo esc_html( get_the_excerpt( $lead ) ); ?></p>
          <p class="intel-card__meta"><?php echo esc_html( lejwana_get_article_card_meta( $lead->ID ) ); ?></p>
          <a class="text-link" href="<?php echo esc_url( get_permalink( $lead ) ); ?>">Read the briefing &rarr;</a>
        </article>
      <?php endif; ?>
      <?php if ( $next ) : ?>
        <?php
        $next_start  = get_post_meta( $next->ID, 'lj_start_date', true );
        $next_end    = lejwana_get_event_end( $next->ID );
        $next_status = lejwana_get_countdown( $next_start, $next_end );
        $next_type   = get_post_meta( $next->ID, 'lj_type', true );
        $is_count    = $next_status['days'] > 1;
        ?>
        <aside class="next-card" aria-labelledby="next-title" data-start="<?php echo esc_attr( $next_start ); ?>" data-end="<?php echo esc_attr( $next_end ); ?>">
          <h2 class="next-card__label" id="next-title">Next in the trade</h2>
          <p class="next-card__count<?php echo $is_count ? '' : ' next-card__count--word'; ?>"><?php echo esc_html( $is_count ? $next_status['days'] : $next_status['label'] ); ?><span class="next-card__unit"><?php echo $is_count ? 'days' : ''; ?></span></p>
          <h3 class="next-card__title"><?php echo esc_html( get_the_title( $next ) ); ?></h3>
          <p class="next-card__meta"><?php echo esc_html( date_create( $next_start )->format( 'D j M Y' ) . ' · ' . get_post_meta( $next->ID, 'lj_place', true ) . ( $next_type ? ' · ' . lejwana_get_event_type_label( $next_type ) : '' ) ); ?></p>
          <a class="button button--on-dark" href="<?php echo esc_url( lejwana_get_calendar_url( $next->ID ) ); ?>">Add to calendar</a>
          <div class="next-card__then">
            <?php if ( $then ) : ?>
              <p class="next-card__then-label">Then</p>
              <ul class="next-card__then-list">
                <?php foreach ( $then as $event ) : ?>
                  <li class="next-card__then-item" data-start="<?php echo esc_attr( get_post_meta( $event->ID, 'lj_start_date', true ) ); ?>" data-end="<?php echo esc_attr( lejwana_get_event_end( $event->ID ) ); ?>"><span><?php echo esc_html( get_the_title( $event ) ); ?></span><span class="next-card__then-days"><?php echo esc_html( lejwana_get_countdown( get_post_meta( $event->ID, 'lj_start_date', true ), lejwana_get_event_end( $event->ID ) )['label'] ); ?></span></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <a class="next-card__link" href="<?php lejwana_the_url( 'the-southern-cut/events/' ); ?>">Full calendar</a>
          </div>
        </aside>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Latest intelligence -->
  <section class="section" id="intelligence" aria-labelledby="intelligence-title">
    <div class="container container--wide">
      <div class="latest__head">
        <h2 class="section__title" id="intelligence-title">Latest intelligence</h2>
        <a class="text-link" href="<?php lejwana_the_url( 'the-southern-cut/insights/' ); ?>">All intelligence &rarr;</a>
      </div>
      <p class="section__intro">Data-driven insights into Southern Africa’s diamond market, covering industry trends, policy changes and key developments shaping the region’s future.</p>
      <div class="latest">
        <ul class="intel-grid intel-grid--pair">
          <?php global $post; ?>
          <?php foreach ( $articles as $post ) : setup_postdata( $post ); ?>
            <?php get_template_part( 'template-parts/intel-card', null, array( 'heading' => 'h3' ) ); ?>
          <?php endforeach; wp_reset_postdata(); ?>
        </ul>
        <?php if ( $most_read ) : ?>
          <aside class="most-read" aria-labelledby="most-read-title">
            <h3 class="most-read__title" id="most-read-title">Most read</h3>
            <ol class="most-read__list">
              <?php foreach ( $most_read as $article ) : ?>
                <li class="most-read__item"><a class="most-read__link" href="<?php echo esc_url( get_permalink( $article ) ); ?>"><?php echo esc_html( get_the_title( $article ) ); ?></a></li>
              <?php endforeach; ?>
            </ol>
          </aside>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- What's on -->
  <section class="section section--sky" id="dates" aria-labelledby="dates-title">
    <div class="container container--wide">
      <h2 class="section__title" id="dates-title">What&rsquo;s on</h2>
      <p class="section__intro">Track upcoming ODC Spot Auctions, De Beers Sights and regional diamond sales, with verified dates and details from primary producers and organisers.</p>
      <div class="pill-group event-types" role="group" aria-label="Filter by type">
        <?php foreach ( $type_tabs as $slug => $label ) : ?>
          <a class="pill<?php echo $slug === $type ? ' pill--active' : ''; ?>" href="<?php echo esc_url( $slug ? add_query_arg( 'type', $slug, get_permalink() ) : get_permalink() ); ?>" data-filter="<?php echo esc_attr( $slug ? $slug : 'all' ); ?>"<?php echo $slug === $type ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $label ); ?></a>
        <?php endforeach; ?>
      </div>
      <ul class="event-list" data-event-list="hub">
        <?php foreach ( $upcoming as $event ) : ?>
          <?php get_template_part( 'template-parts/event-row', null, array( 'event' => $event, 'hidden' => ! in_array( $event->ID, $shown_ids, true ) ) ); ?>
        <?php endforeach; ?>
      </ul>
      <p class="event-empty"<?php echo $shown ? ' hidden' : ''; ?>>Nothing of this type in the next 30 days.</p>
      <a class="text-link event-more" href="<?php lejwana_the_url( 'the-southern-cut/events/' ); ?>">See full calendar &rarr;</a>
      <p class="calendar-note">Dates can change. Confirm participation, customer registration, viewing rights and auction terms directly with the organiser before acting.</p>
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
      <form class="form brief-band__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
        <input type="hidden" name="action" value="lejwana_brief">
        <?php wp_nonce_field( 'lejwana_brief' ); ?>
        <div class="u-visually-hidden" aria-hidden="true"><label for="field-website">Leave this field empty</label><input type="text" id="field-website" name="website" tabindex="-1" autocomplete="off"></div>
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
        <?php $brief_outcome = lejwana_get_brief_outcome(); ?>
        <p class="form__success<?php echo 'sent' === $brief_outcome ? ' form__success--visible' : ''; ?>" role="status">Thank you. You have subscribed to The Southern Cut Brief.</p>
        <?php if ( 'invalid' === $brief_outcome ) : ?>
        <p class="form__failure" role="alert">Your subscription was not completed. Check each required field and subscribe again.</p>
        <?php elseif ( 'failed' === $brief_outcome ) : ?>
        <p class="form__failure" role="alert">Your subscription was not completed. Please try again shortly, or <a class="text-link" href="<?php lejwana_the_url( 'contact/' ); ?>">contact us</a> and we will add you to the list.</p>
        <?php endif; ?>
      </form>
    </div>
  </section>
</main>

<?php get_footer(); ?>
