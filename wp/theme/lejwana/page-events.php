<?php
lejwana_set_page_meta(
  'What’s on | The Southern Cut | Lejwana',
  'Track upcoming ODC Spot Auctions, De Beers Sights and regional diamond sales, with verified dates and details from primary producers and organisers.'
);
get_header();

$base     = home_url( '/the-southern-cut/events/' );
$type     = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
$type     = array_key_exists( $type, LEJWANA_EVENT_TYPES ) ? $type : '';
$country  = isset( $_GET['country'] ) ? sanitize_key( wp_unslash( $_GET['country'] ) ) : '';
$country  = array_key_exists( $country, LEJWANA_COUNTRIES ) ? $country : '';
$past     = isset( $_GET['past'] ) && '1' === $_GET['past'];
$events   = $past ? lejwana_get_past_events() : lejwana_get_events( true );
// Every event is rendered; the ones outside the filter are hidden, so the browser can change the filter without a reload.
$matches  = array_filter(
  $events,
  function ( $event ) use ( $type, $country ) {
    return lejwana_event_matches( $event, $type, $country );
  }
);
$match_ids = wp_list_pluck( $matches, 'ID' );
$months   = array();
foreach ( $events as $event ) {
  $months[ date_create( get_post_meta( $event->ID, 'lj_start_date', true ) )->format( 'Y-m' ) ][] = $event;
}
$type_tabs  = array( '' => 'All', 'result' => 'Results', 'tender' => 'Tenders', 'sale' => 'Sales', 'sight' => 'Sights' );
$count      = count( $matches );
?>

<main id="main">
  <?php get_template_part( 'template-parts/section-nav', null, array( 'current' => 'cal' ) ); ?>

  <section class="section">
    <div class="container">
      <h1 class="section__title">What&rsquo;s on</h1>
      <p class="section__intro">Track upcoming ODC Spot Auctions, De Beers Sights and regional diamond sales, with verified dates and details from primary producers and organisers.</p>
    </div>
  </section>

  <section class="section section--sky">
    <div class="container container--wide">
      <form class="event-filter" method="get" action="<?php echo esc_url( $base ); ?>">
        <?php if ( $type ) : ?><input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>"><?php endif; ?>
        <?php if ( $past ) : ?><input type="hidden" name="past" value="1"><?php endif; ?>
        <div class="event-filter__group">
          <span class="pill-group__label" id="type-label">Type</span>
          <div class="pill-group event-types" role="group" aria-labelledby="type-label">
            <?php foreach ( $type_tabs as $slug => $label ) : ?>
              <a class="pill<?php echo $slug === $type ? ' pill--active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array_filter( array( 'type' => $slug, 'country' => $country, 'past' => $past ? '1' : '' ) ), $base ) ); ?>" data-filter="<?php echo esc_attr( $slug ? $slug : 'all' ); ?>"<?php echo $slug === $type ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $label ); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="event-filter__group">
          <label class="pill-group__label" for="country">Country</label>
          <select class="form__select event-filter__country" id="country" name="country">
            <option value="all">All countries</option>
            <?php foreach ( LEJWANA_COUNTRIES as $slug => $name ) : ?>
              <option value="<?php echo esc_attr( $slug ); ?>"<?php selected( $country, $slug ); ?>><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button class="button button--ghost event-filter__apply" type="submit">Apply</button>
        <p class="event-filter__count" role="status"><?php echo esc_html( $count . ( $past ? ' past event' : ' upcoming event' ) . ( 1 === $count ? '' : 's' ) ); ?></p>
      </form>

      <div data-event-list="calendar"<?php echo $past ? ' data-newest-first' : ''; ?>>
        <?php foreach ( $months as $key => $month_events ) : ?>
          <?php
          $label   = date_create( $key . '-01' )->format( 'F Y' );
          $visible = count( array_intersect( wp_list_pluck( $month_events, 'ID' ), $match_ids ) );
          ?>
          <section class="event-month" aria-labelledby="month-<?php echo esc_attr( $key ); ?>"<?php echo $visible ? '' : ' hidden'; ?>>
            <header class="event-month__head">
              <h2 class="event-month__title" id="month-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></h2>
              <p class="event-month__count"><?php echo esc_html( $visible . ( 1 === $visible ? ' event' : ' events' ) ); ?></p>
            </header>
            <ul class="event-list">
              <?php foreach ( $month_events as $event ) : ?>
                <?php get_template_part( 'template-parts/event-row', null, array( 'event' => $event, 'hidden' => ! in_array( $event->ID, $match_ids, true ) ) ); ?>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endforeach; ?>
      </div>
      <p class="event-empty"<?php echo $count ? ' hidden' : ''; ?>>Nothing scheduled for this filter yet.</p>
      <p class="calendar-note">Dates can change. Confirm participation, customer registration, viewing rights and auction terms directly with the organiser before acting.</p>
      <div class="event-foot">
        <a class="text-link event-foot__past" href="<?php echo esc_url( $past ? $base : add_query_arg( 'past', '1', $base ) ); ?>"><?php echo $past ? 'Show upcoming events' : 'Show past events'; ?></a>
        <p>Spotted a date we&rsquo;re missing? <a class="text-link" href="<?php lejwana_the_url( 'contact/' ); ?>">Tell us</a></p>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
