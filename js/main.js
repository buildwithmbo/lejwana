/* ==========================================================================
   LEJWANA — main.js
   Minimal, dependency-free. No decorative animation, per brief.

   CONTENTS
   01. Navigation (mobile + Services dropdown)
   02. Copy email
   03. Enquiry form validation
   04. Cal.com lazy embed
   05. Filter pills (shared helper — hub page only)
   06. Trade ticker + What's on (hub page only)
   07. Latest intelligence filter (hub page only)
   08. Country tabs (SADC network)
   09. Scroll reveal + map entrance
   10. Map / country interlink
   11. Footer copyright year
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
     03. Enquiry form (validation + specification pre-fill from Sourcing)
     NOTE: front-end only. Wire `action` to the form handler
     (e.g. server endpoint or form service) before launch.
     ------------------------------------------------------------------ */
  var form = document.querySelector('.form');

  if (form) {
    // Sourcing's specification builder (.spec-builder, a plain method="get"
    // form) hands its fields to this page as a query string, so they arrive
    // with JS disabled too. This only mirrors them into the visible fields —
    // nothing here is required for the data itself to arrive.
    if (window.location.search) {
      var specParams = new URLSearchParams(window.location.search);
      specParams.forEach(function (value, key) {
        var field = form.elements.namedItem(key);
        if (field) { field.value = value; }
      });
    }

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
     05. Filter pills
     Shared by the hub's three filter-pill groups (region, calendar type,
     intelligence category) — one toggle behaviour, extracted once three
     call sites needed it. Each group stays hidden until wired, since the
     buttons do nothing without JS.
     ------------------------------------------------------------------ */
  function wirePillGroup(group, onChange) {
    if (!group) { return; }
    var pills = Array.prototype.slice.call(group.querySelectorAll('.pill'));

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        pills.forEach(function (p) {
          var active = p === pill;
          p.classList.toggle('pill--active', active);
          p.setAttribute('aria-pressed', String(active));
        });
        onChange(pill.getAttribute('data-filter'));
      });
    });

    group.hidden = false;
  }

  function setPillCounts(group, countFor) {
    if (!group) { return; }
    Array.prototype.slice.call(group.querySelectorAll('.pill')).forEach(function (pill) {
      var count = document.createElement('span');
      count.className = 'pill__count';
      count.textContent = countFor(pill.getAttribute('data-filter'));
      pill.appendChild(count);
    });
  }

  /* ------------------------------------------------------------------
     06. Trade ticker + What's on
     The calendar renders in full in the markup; this computes each row's
     status from real dates, filters rows by type and region, and builds
     the ticker from whatever isn't "ended". Without JS every row stands,
     its .trade-status reading CONTENT.md's plain "Confirmed" text, and
     the ticker — which would only be a duplicate of the table, minus the
     badge that's its only reason to exist — stays hidden.
     ------------------------------------------------------------------ */
  var datesTable = document.getElementById('dates-table');

  function tradeStatus(row) {
    var start = new Date(row.getAttribute('data-start') + 'T00:00:00');
    var end = new Date(row.getAttribute('data-end') + 'T00:00:00');
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var msPerDay = 86400000;

    if (today > end) {
      return { label: 'Ended', modifier: 'ended' };
    }
    if (today < start) {
      var daysUntil = Math.round((start - today) / msPerDay);
      return {
        label: 'In ' + daysUntil + (daysUntil === 1 ? ' day' : ' days'),
        modifier: 'upcoming'
      };
    }
    if (start.getTime() === end.getTime()) {
      return { label: 'Today', modifier: 'today' };
    }
    return {
      label: 'Live · day ' + (Math.round((today - start) / msPerDay) + 1) +
        ' of ' + (Math.round((end - start) / msPerDay) + 1),
      modifier: 'live'
    };
  }

  function buildTickerItem(row) {
    var item = document.createElement('li');
    item.className = 'ticker__item';

    var name = document.createElement('span');
    name.className = 'ticker__item-name';
    name.textContent = row.querySelector('td:nth-child(2) a').textContent;

    item.appendChild(name);
    item.appendChild(row.querySelector('.trade-status').cloneNode(true));
    return item;
  }

  if (datesTable) {
    var dateRows = Array.prototype.slice.call(datesTable.querySelectorAll('tbody tr'));

    dateRows.forEach(function (row) {
      var status = tradeStatus(row);
      var statusEl = row.querySelector('.trade-status');
      statusEl.textContent = status.label;
      statusEl.className = 'trade-status trade-status--' + status.modifier;
      row.dataset.status = status.modifier;
    });

    var ticker = document.querySelector('.ticker');
    var tickerTrack = document.querySelector('.ticker__track');
    var upcomingRows = dateRows.filter(function (row) {
      return row.dataset.status !== 'ended';
    });

    if (ticker && tickerTrack && upcomingRows.length) {
      // The duplicate set is only what makes the CSS animation's -50%
      // translate loop seamless — under reduced motion (§16 kills the
      // animation outright) it would just show every item twice.
      var reducesMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      var tickerRows = reducesMotion ? upcomingRows : upcomingRows.concat(upcomingRows);
      tickerRows.forEach(function (row) {
        tickerTrack.appendChild(buildTickerItem(row));
      });
      ticker.hidden = false;
    }

    var activeType = 'all';
    var activeRegion = 'all';
    var REVEAL_LIMIT = 7;
    var expanded = false;

    function applyCalendarFilters() {
      dateRows.forEach(function (row, index) {
        var typeMatch = activeType === 'all' || row.getAttribute('data-type') === activeType;
        var regionMatch = activeRegion === 'all' ||
          row.getAttribute('data-region').indexOf(activeRegion) !== -1;
        var withinLimit = expanded || index < REVEAL_LIMIT;
        row.hidden = !(typeMatch && regionMatch && withinLimit);
      });
    }

    var typeFilters = document.querySelector('.whats-on__filters');
    wirePillGroup(typeFilters, function (value) {
      activeType = value;
      applyCalendarFilters();
    });
    setPillCounts(typeFilters, function (value) {
      return dateRows.filter(function (row) {
        return value === 'all' || row.getAttribute('data-type') === value;
      }).length;
    });

    wirePillGroup(document.querySelector('.hub-subnav__region'), function (value) {
      activeRegion = value;
      applyCalendarFilters();
    });

    var calendarToggle = document.querySelector('.whats-on__toggle');
    if (calendarToggle && dateRows.length > REVEAL_LIMIT) {
      calendarToggle.addEventListener('click', function () {
        expanded = !expanded;
        calendarToggle.textContent = expanded ? 'Read less' : 'Read more';
        calendarToggle.setAttribute('aria-expanded', String(expanded));
        applyCalendarFilters();
      });
      calendarToggle.hidden = false;
    }

    applyCalendarFilters();
  }

  /* ------------------------------------------------------------------
     07. Latest intelligence filter
     ------------------------------------------------------------------ */
  var intelItems = Array.prototype.slice.call(document.querySelectorAll('.intel__item'));

  if (intelItems.length) {
    var intelFilters = document.querySelector('.intel__filters');
    var INTEL_REVEAL_LIMIT = 4;
    var intelExpanded = false;
    var activeIntelCategory = 'all';

    function applyIntelFilters() {
      intelItems.forEach(function (item, index) {
        var categoryMatch = activeIntelCategory === 'all' ||
          item.getAttribute('data-category') === activeIntelCategory;
        var withinLimit = intelExpanded || index < INTEL_REVEAL_LIMIT;
        item.parentElement.hidden = !(categoryMatch && withinLimit);
      });
    }

    wirePillGroup(intelFilters, function (value) {
      activeIntelCategory = value;
      applyIntelFilters();
    });

    setPillCounts(intelFilters, function (value) {
      return intelItems.filter(function (item) {
        return value === 'all' || item.getAttribute('data-category') === value;
      }).length;
    });

    var intelToggle = document.querySelector('.intel__toggle');
    if (intelToggle && intelItems.length > INTEL_REVEAL_LIMIT) {
      intelToggle.addEventListener('click', function () {
        intelExpanded = !intelExpanded;
        intelToggle.textContent = intelExpanded ? 'Show fewer stories' : 'Show more stories';
        intelToggle.setAttribute('aria-expanded', String(intelExpanded));
        applyIntelFilters();
      });
      intelToggle.hidden = false;
    }

    applyIntelFilters();
  }

  /* ------------------------------------------------------------------
     08. Country tabs (SADC network)
     The panels render stacked in the markup; JS reveals the tab row and
     shows one country at a time. Arrow keys, Home and End move between
     tabs, per the ARIA tabs pattern.
     ------------------------------------------------------------------ */
  var countryTabList = document.querySelector('.country-tabs__list');

  if (countryTabList) {
    var countryTabs = Array.prototype.slice.call(
      countryTabList.querySelectorAll('.country-tabs__tab')
    );

    var countryMapImages = Array.prototype.slice.call(
      document.querySelectorAll('.network__map-image')
    );

    var selectCountryTab = function (tab) {
      countryTabs.forEach(function (t) {
        var active = t === tab;
        t.setAttribute('aria-selected', String(active));
        t.tabIndex = active ? 0 : -1;
        document.getElementById(t.getAttribute('aria-controls')).hidden = !active;
      });
      var country = tab.getAttribute('aria-controls').replace('panel-', '');
      countryMapImages.forEach(function (img) {
        img.hidden = img.dataset.country !== country;
      });
    };

    countryTabList.hidden = false;
    selectCountryTab(countryTabs[0]);

    countryTabs.forEach(function (tab, index) {
      tab.addEventListener('click', function () {
        selectCountryTab(tab);
      });

      tab.addEventListener('keydown', function (event) {
        var moves = {
          ArrowLeft: index - 1,
          ArrowRight: index + 1,
          Home: 0,
          End: countryTabs.length - 1
        };
        if (!(event.key in moves)) { return; }
        event.preventDefault();
        var next = countryTabs[(moves[event.key] + countryTabs.length) % countryTabs.length];
        selectCountryTab(next);
        next.focus();
      });
    });
  }

  /* ------------------------------------------------------------------
     09. Scroll reveal + map entrance
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
     10. Map / country interlink
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
     11. Footer copyright year
     ------------------------------------------------------------------ */
  var yearEl = document.querySelector('.js-year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }
})();
