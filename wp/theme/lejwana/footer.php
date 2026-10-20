<footer class="site-footer site-footer--flush">
  <div class="container container--wide">
    <div class="site-footer__grid">
      <div>
        <a class="wordmark site-footer__wordmark" href="<?php lejwana_the_url( '' ); ?>" aria-label="Lejwana home">
          <img class="wordmark__logo" src="<?php lejwana_the_asset( 'images/lejwana-logo-light.svg' ); ?>" alt="" aria-hidden="true" width="136" height="32">
        </a>
        <address class="site-footer__address">
          Gaborone, Botswana<br>
          <!-- [CONFIRM: full Gaborone street address] -->
          London Diamond Bourse, by appointment
        </address>
      </div>
      <div>
        <h2 class="site-footer__heading">Services</h2>
        <ul class="site-footer__list">
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'sourcing/' ); ?>">Sourcing</a></li>
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'trading/' ); ?>">Trading</a></li>
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'advisory/' ); ?>">Advisory</a></li>
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'provenance/' ); ?>">Provenance</a></li>
        </ul>
      </div>
      <div>
        <h2 class="site-footer__heading">Contact</h2>
        <ul class="site-footer__list">
          <li><a class="site-footer__link" href="mailto:polished@lejwana.com">polished@lejwana.com</a></li>
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'contact/' ); ?>">Book a call</a></li>
          <li><a class="site-footer__link" href="<?php lejwana_the_url( 'privacy/' ); ?>">Privacy</a></li>
        </ul>
      </div>
    </div>
    <p class="site-footer__membership">Members of the London Diamond Bourse and the World Federation of Diamond Bourses.</p>
    <p class="site-footer__copyright">&copy; <span class="js-year"></span> Lejwana. All rights reserved.</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
