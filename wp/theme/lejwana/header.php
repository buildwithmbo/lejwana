<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php lejwana_the_social_meta(); ?>
  <link rel="icon" href="<?php lejwana_the_asset( 'images/favicon.svg' ); ?>" type="image/svg+xml">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="container container--wide site-header__inner">
    <a class="wordmark" href="<?php lejwana_the_url( '' ); ?>" aria-label="Lejwana home">
      <img class="wordmark__logo" src="<?php lejwana_the_asset( 'images/lejwana-logo.svg' ); ?>" alt="" aria-hidden="true" width="136" height="32">
    </a>
    <nav class="nav" aria-label="Primary">
      <button class="nav__burger" aria-expanded="false" aria-controls="nav-list">
        <span class="u-visually-hidden">Menu</span>
        <span class="nav__burger-line" aria-hidden="true"></span>
        <span class="nav__burger-line" aria-hidden="true"></span>
        <span class="nav__burger-line" aria-hidden="true"></span>
      </button>
      <ul class="nav__list" id="nav-list">
        <li class="nav__item nav__item--dropdown">
          <button class="nav__toggle" aria-expanded="false" aria-controls="services-menu">Services</button>
          <ul class="nav__dropdown" id="services-menu">
            <li><a class="nav__dropdown-link" href="<?php lejwana_the_url( 'sourcing/' ); ?>">Sourcing</a></li>
            <li><a class="nav__dropdown-link" href="<?php lejwana_the_url( 'trading/' ); ?>">Trading</a></li>
            <li><a class="nav__dropdown-link" href="<?php lejwana_the_url( 'advisory/' ); ?>">Advisory</a></li>
            <li><a class="nav__dropdown-link" href="<?php lejwana_the_url( 'provenance/' ); ?>">Provenance</a></li>
          </ul>
        </li>
        <li><a class="nav__link<?php lejwana_the_nav_state( 'the-network' ); ?>" href="<?php lejwana_the_url( 'the-network/' ); ?>">The Network</a></li>
        <li><a class="nav__link<?php lejwana_the_nav_state( 'the-southern-cut' ); ?>" href="<?php lejwana_the_url( 'the-southern-cut/' ); ?>">The Southern Cut</a></li>
        <li><a class="nav__link<?php lejwana_the_nav_state( 'about' ); ?>" href="<?php lejwana_the_url( 'about/' ); ?>">About</a></li>
        <li><a class="nav__link<?php lejwana_the_nav_state( 'contact' ); ?>" href="<?php lejwana_the_url( 'contact/' ); ?>">Contact</a></li>
      </ul>
    </nav>
  </div>
</header>
