<?php
$event       = $args['event'];
$link        = get_post_meta( $event->ID, 'lj_link_url', true );
$type        = get_post_meta( $event->ID, 'lj_type', true );
$start       = get_post_meta( $event->ID, 'lj_start_date', true );
$end         = lejwana_get_event_end( $event->ID );
$regions     = wp_get_post_terms( $event->ID, 'lj_country', array( 'fields' => 'slugs' ) );
$title       = get_the_title( $event );
$from        = date_create( $start );
$to          = date_create( $end );
$status      = lejwana_get_countdown( $start, $end );
?>
<li class="event-row" data-type="<?php echo esc_attr( $type ); ?>" data-region="<?php echo esc_attr( is_array( $regions ) ? implode( ' ', $regions ) : '' ); ?>" data-start="<?php echo esc_attr( $start ); ?>" data-end="<?php echo esc_attr( $end ); ?>"<?php echo ! empty( $args['hidden'] ) ? ' hidden' : ''; ?>>
  <span class="event-row__date"><span class="event-row__month"><?php echo esc_html( $from->format( 'M' ) ); ?></span><span class="event-row__day"><?php echo esc_html( $from->format( 'j' ) ); ?></span><?php if ( $end !== $start ) : ?><span class="event-row__to">to <?php echo esc_html( $to->format( 'j M' ) ); ?></span><?php endif; ?></span>
  <div class="event-row__main">
    <?php if ( $type ) : ?><span class="event-label event-label--<?php echo esc_attr( $type ); ?>"><?php echo esc_html( lejwana_get_event_type_label( $type ) ); ?></span><?php endif; ?>
    <h3 class="event-row__title">
      <?php if ( $link ) : ?>
        <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $title ); ?></a>
      <?php else : ?>
        <?php echo esc_html( $title ); ?>
      <?php endif; ?>
    </h3>
    <p class="event-row__place"><?php echo esc_html( get_post_meta( $event->ID, 'lj_place', true ) ); ?></p>
  </div>
  <span class="event-row__status"><?php echo esc_html( $status['label'] ); ?></span>
  <a class="event-row__add" href="<?php echo esc_url( lejwana_get_calendar_url( $event->ID ) ); ?>" aria-label="<?php echo esc_attr( 'Add ' . $title . ' to calendar' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="1"/><path d="M3.5 10h17M8 3v4M16 3v4M12 13v5M9.5 15.5h5"/></svg></a>
</li>
