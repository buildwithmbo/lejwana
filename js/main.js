/* ==========================================================================
   LEJWANA — main.js
   Minimal, dependency-free. No decorative animation, per brief.

   CONTENTS
   01. Navigation (mobile + Services dropdown)
   02. Copy email
   03. Enquiry form validation
   04. Cal.com lazy embed
   05. Insight category filter
   06. Scroll reveal + map entrance
   07. Map / country interlink
   08. Footer copyright year
   ========================================================================== */

(function () {
  'use strict';

  // Progressive enhancement: without this class the reveal styles never apply,
  // so content is visible when JavaScript is unavailable.
  document.documentElement.classList.add('js');

  /* ------------------------------------------------------------------
     01. Navigation
     ------------------------------------------------------------------ */
  var burger = document.querySelector('.nav__burger');
  var navList = document.querySelector('.nav__list');

  if (burger && navList) {
    burger.addEventListener('click', function () {
      var open = navList.classList.toggle('nav__list--open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  var dropdownToggle = document.querySelector('.nav__toggle');
  var dropdown = document.querySelector('.nav__dropdown');

  if (dropdownToggle && dropdown) {
    dropdownToggle.addEventListener('click', function () {
      var open = dropdown.classList.toggle('nav__dropdown--open');
      dropdownToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', function (event) {
      if (!event.target.closest('.nav__item--dropdown')) {
        dropdown.classList.remove('nav__dropdown--open');
        dropdownToggle.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        dropdown.classList.remove('nav__dropdown--open');
        dropdownToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ------------------------------------------------------------------
     02. Copy email
     ------------------------------------------------------------------ */
  var copyButton = document.querySelector('.channel__copy-button');

  if (copyButton) {
    copyButton.addEventListener('click', function () {
      var email = copyButton.getAttribute('data-email');
      navigator.clipboard.writeText(email).then(function () {
        var original = copyButton.textContent;
        copyButton.textContent = 'Copied';
        window.setTimeout(function () {
          copyButton.textContent = original;
        }, 2000);
      });
    });
  }

  /* ------------------------------------------------------------------
     03. Enquiry form validation
     NOTE: front-end only. Wire `action` to the form handler
     (e.g. server endpoint or form service) before launch.
     ------------------------------------------------------------------ */
  var form = document.querySelector('.form');

  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var valid = true;
      var fields = form.querySelectorAll('[required]');

      fields.forEach(function (field) {
        var wrapper = field.closest('.form__field');
        var fieldValid = field.value.trim() !== '';

        if (field.type === 'email' && fieldValid) {
          fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value.trim());
        }

        wrapper.classList.toggle('form__field--invalid', !fieldValid);
        if (!fieldValid) { valid = false; }
      });

      if (valid) {
        // Replace with real submission before launch.
        form.querySelector('.form__success').classList.add('form__success--visible');
        form.reset();
      }
    });
  }

  /* ------------------------------------------------------------------
     04. Cal.com lazy embed
     Loads only when requested, so the Contact page stays fast.
     ------------------------------------------------------------------ */
  document.querySelectorAll('[data-cal-link]').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      var container = document.querySelector(trigger.getAttribute('data-cal-target'));
      if (!container || container.querySelector('iframe')) { return; }

      var iframe = document.createElement('iframe');
      iframe.className = 'cal-embed__frame';
      iframe.src = 'https://cal.com/' + trigger.getAttribute('data-cal-link') + '?embed=true';
      iframe.title = 'Book a call with Lejwana';
      iframe.loading = 'lazy';
      container.appendChild(iframe);
      trigger.textContent = 'Booking calendar loaded below';
      trigger.disabled = true;
    });
  });

  /* ------------------------------------------------------------------
     05. Insight category filter
     ------------------------------------------------------------------ */
  var filterButtons = document.querySelectorAll('.insight-filter__button');
  var insightCards = document.querySelectorAll('.insight-grid .insight-card');

  filterButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var category = button.getAttribute('data-category');

      filterButtons.forEach(function (b) {
        b.classList.toggle('insight-filter__button--active', b === button);
        b.setAttribute('aria-pressed', b === button ? 'true' : 'false');
      });

      insightCards.forEach(function (card) {
        var match = category === 'all' || card.getAttribute('data-category') === category;
        card.style.display = match ? '' : 'none';
      });
    });
  });

  /* ------------------------------------------------------------------
     06. Scroll reveal + map entrance
     ------------------------------------------------------------------ */
  // Deliberately not IntersectionObserver: a fast scroll can carry an element
  // from below the viewport to above it between two intersection computations,
  // so it never reports as intersecting and the section stays invisible for
  // good. A position check on scroll cannot skip an element.
  var pending = Array.prototype.slice.call(
    document.querySelectorAll('.reveal, .network-map')
  );
  var queued = false;

  function revealPassed() {
    queued = false;
    pending = pending.filter(function (target) {
      if (target.getBoundingClientRect().top > window.innerHeight * 0.9) {
        return true;
      }
      target.classList.add('is-visible');
      return false;
    });

    if (!pending.length) {
      window.removeEventListener('scroll', onScroll);
      window.removeEventListener('resize', onScroll);
    }
  }

  function onScroll() {
    if (queued) { return; }
    queued = true;
    window.requestAnimationFrame(revealPassed);
  }

  if (pending.length) {
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    revealPassed();
  }

  /* ------------------------------------------------------------------
     07. Map / country interlink
     Hovering or focusing a map node highlights the matching table row and
     panel, and the reverse. Country is matched on the data-country value.
     ------------------------------------------------------------------ */
  var linkables = document.querySelectorAll('[data-country]');

  function setLinked(country, on) {
    linkables.forEach(function (el) {
      if (el.getAttribute('data-country') === country) {
        el.classList.toggle('is-linked', on);
      }
    });
  }

  linkables.forEach(function (el) {
    var country = el.getAttribute('data-country');
    ['mouseenter', 'focusin'].forEach(function (type) {
      el.addEventListener(type, function () { setLinked(country, true); });
    });
    ['mouseleave', 'focusout'].forEach(function (type) {
      el.addEventListener(type, function () { setLinked(country, false); });
    });
  });

  /* ------------------------------------------------------------------
     08. Footer copyright year
     ------------------------------------------------------------------ */
  var yearEl = document.querySelector('.js-year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }
})();
