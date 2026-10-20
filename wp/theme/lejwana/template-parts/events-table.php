<div class="network-table__scroll">
  <table class="network-table whats-on__table" id="dates-table">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Item</th>
        <th scope="col">Place</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $args['events'] as $event ) : ?>
        <?php get_template_part( 'template-parts/event-row', null, array( 'event' => $event ) ); ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
