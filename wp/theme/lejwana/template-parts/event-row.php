<?php
$event      = $args['event'];
$link       = get_post_meta( $event->ID, 'lj_link_url', true );
$type       = get_post_meta( $event->ID, 'lj_type', true );
$start      = get_post_meta( $event->ID, 'lj_start_date', true );
$end        = lejwana_get_event_end( $event->ID );
$regions    = wp_get_post_terms( $event->ID, 'lj_country', array( 'fields' => 'slugs' ) );
$type_label = ucfirst( $type );
?>
<tr data-type="<?php echo esc_attr( $type ); ?>" data-region="<?php echo esc_attr( is_array( $regions ) ? implode( ' ', $regions ) : '' ); ?>" data-start="<?php echo esc_attr( $start ); ?>" data-end="<?php echo esc_attr( $end ); ?>">
  <td><?php echo esc_html( lejwana_format_event_dates( $start, $end ) ); ?></td>
  <td>
    <?php if ( $link ) : ?>
      <a class="text-link link-external" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><?php echo esc_html( get_the_title( $event ) ); ?></a>
    <?php else : ?>
      <span class="whats-on__name"><?php echo esc_html( get_the_title( $event ) ); ?></span>
    <?php endif; ?>
    <?php if ( $type_label ) : ?><span class="whats-on__type"><?php echo esc_html( $type_label ); ?></span><?php endif; ?>
  </td>
  <td><?php echo esc_html( get_post_meta( $event->ID, 'lj_place', true ) ); ?></td>
  <td><span class="trade-status"><?php echo esc_html( get_post_meta( $event->ID, 'lj_status', true ) ); ?></span></td>
</tr>
