/* ==========================================================================
   LEJWANA — main.js
   Minimal, dependency-free. No decorative animation, per brief.

   CONTENTS
   01. Navigation (mobile + Services dropdown)
   02. Copy email
   03. Enquiry form validation
   04. Cal.com lazy embed
   05. Filter pills (links that become buttons)
   06. Events: countdowns, filters, ticker (hub, calendar, homepage ticker)
   07. Intelligence archive filter
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
     Validates only. When the form has an `action` (WordPress), a valid
     form posts natively; without one (static preview) it fakes success.
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

      if (!valid) { event.preventDefault(); return; }

      if (!form.getAttribute('action')) {
        event.preventDefault();
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
     Filters are plain links first (?type=tender), so they work without JS.
     Here each link becomes a real button carrying aria-pressed, and the
     list filters in place. The URL is kept in step, so a filtered view can
     be shared and a reload keeps it.
     ------------------------------------------------------------------ */
  function linkToButton(link) {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = link.className;
    button.textContent = link.textContent;
    button.setAttribute('data-filter', link.getAttribute('data-filter'));
    link.parentNode.replaceChild(button, link);
    return button;
  }

  function setActivePill(pills, value) {
    pills.forEach(function (pill) {
      var active = pill.getAttribute('data-filter') === value;
      pill.classList.toggle('pill--active', active);
      pill.setAttribute('aria-pressed', String(active));
    });
  }

  function wirePillGroup(group, initial, onChange) {
    if (!group) { return; }
    var pills = Array.prototype.slice.call(group.querySelectorAll('.pill')).map(function (pill) {
      return pill.tagName === 'A' ? linkToButton(pill) : pill;
    });
    setActivePill(pills, initial);
    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        setActivePill(pills, pill.getAttribute('data-filter'));
        onChange(pill.getAttribute('data-filter'));
      });
    });
  }

  function getParam(name) {
    return new URLSearchParams(window.location.search).get(name) || 'all';
  }

  function setParam(name, value) {
    var url = new URL(window.location.href);
    if (value === 'all') { url.searchParams.delete(name); } else { url.searchParams.set(name, value); }
    window.history.replaceState(null, '', url);
  }

  /* ------------------------------------------------------------------
     06. Events: countdowns, filters, the ticker
     Counts are worked out here, from today in Africa/Gaborone, so a cached
     page never shows a stale "In 3 days". The server still prints a first
     answer, so the page reads correctly without JS.
     ------------------------------------------------------------------ */
  var MS_PER_DAY = 86400000;
  var HUB_EVENT_LIMIT = 5;
  var TICKER_EVENT_LIMIT = 8;
  var gaboroneToday = new Intl.DateTimeFormat('en-CA', { timeZone: 'Africa/Gaborone' }).format(new Date());

  function dayNumber(isoDate) {
    return Date.parse(isoDate + 'T00:00:00Z') / MS_PER_DAY;
  }

  function countdown(start, end) {
    var today = dayNumber(gaboroneToday);
    var from = dayNumber(start);
    var to = dayNumber(end);
    var daysUntil = from - today;

    if (today > to) { return { label: 'Ended', modifier: 'ended', days: 0 }; }
    if (today === from) { return { label: 'Today', modifier: 'today', days: 0 }; }
    if (today > from) { return { label: 'On now', modifier: 'live', days: 0 }; }
    if (daysUntil === 1) { return { label: 'Tomorrow', modifier: 'upcoming', days: 1 }; }
    return { label: 'In ' + daysUntil + ' days', modifier: 'upcoming', days: daysUntil };
  }

  function countdownFor(el) {
    return countdown(el.getAttribute('data-start'), el.getAttribute('data-end'));
  }

  var eventRows = Array.prototype.slice.call(document.querySelectorAll('.event-row'));

  eventRows.forEach(function (row) {
    var status = countdownFor(row);
    row.dataset.status = status.modifier;
    row.querySelector('.event-row__status').textContent = status.label;
  });

  var nextCard = document.querySelector('.next-card');

  if (nextCard) {
    var nextStatus = countdownFor(nextCard);
    var nextCount = nextCard.querySelector('.next-card__count');
    var nextUnit = document.createElement('span');
    nextUnit.className = 'next-card__unit';
    nextUnit.textContent = nextStatus.days > 1 ? 'days' : '';
    nextCount.textContent = nextStatus.days > 1 ? String(nextStatus.days) : nextStatus.label;
    nextCount.classList.toggle('next-card__count--word', nextStatus.days <= 1);
    nextCount.appendChild(nextUnit);

    nextCard.querySelectorAll('.next-card__then-item').forEach(function (item) {
      item.querySelector('.next-card__then-days').textContent = countdownFor(item).label;
    });
  }

  var ticker = document.querySelector('.ticker');
  var tickerTrack = document.querySelector('.ticker__track');

  function buildTickerItem(name, status) {
    var item = document.createElement('li');
    item.className = 'ticker__item';
    var label = document.createElement('span');
    label.className = 'ticker__item-name';
    label.textContent = name;
    var badge = document.createElement('span');
    badge.className = 'trade-status trade-status--' + status.modifier;
    badge.textContent = status.label;
    item.appendChild(label);
    item.appendChild(badge);
    return item;
  }

  function showTicker(items) {
    if (!ticker || !tickerTrack || !items.length) { return; }
    // The duplicate set is only what makes the CSS animation's -50%
    // translate loop seamless. Under reduced motion (§16 kills the
    // animation outright) it would just show every item twice.
    var reducesMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var looped = reducesMotion ? items : items.concat(items.map(function (item) {
      return item.cloneNode(true);
    }));
    tickerTrack.textContent = '';
    looped.forEach(function (item) { tickerTrack.appendChild(item); });
    ticker.hidden = false;
  }

  // Homepage: the items are written in the markup with their own dates.
  // Hub and calendar: they come from the event rows.
  if (ticker && tickerTrack) {
    var markupItems = Array.prototype.slice.call(tickerTrack.children);
    var tickerItems = markupItems.length ? markupItems.filter(function (item) {
      var status = countdownFor(item);
      item.querySelector('.trade-status').className = 'trade-status trade-status--' + status.modifier;
      item.querySelector('.trade-status').textContent = status.label;
      return status.modifier !== 'ended';
    }) : eventRows.filter(function (row) {
      return row.dataset.status !== 'ended';
    }).slice(0, TICKER_EVENT_LIMIT).map(function (row) {
      return buildTickerItem(row.querySelector('.event-row__title').textContent.trim(), countdownFor(row));
    });
    showTicker(tickerItems);
  }

  // Hub: the next few events, by type.
  var hubEvents = document.querySelector('[data-event-list="hub"]');

  function showHubEvents(type) {
    var shown = 0;
    eventRows.forEach(function (row) {
      var match = row.dataset.status !== 'ended' && (type === 'all' || row.getAttribute('data-type') === type);
      row.hidden = !(match && shown < HUB_EVENT_LIMIT);
      if (!row.hidden) { shown += 1; }
    });
    document.querySelector('.event-empty').hidden = shown > 0;
  }

  if (hubEvents) {
    var hubType = getParam('type');
    wirePillGroup(document.querySelector('.event-types'), hubType, function (value) {
      setParam('type', value);
      showHubEvents(value);
    });
    showHubEvents(hubType);
  }

  // Calendar: month groups, type and country filters, past events.
  var calendar = document.querySelector('[data-event-list="calendar"]');

  function pluralise(count, noun) {
    return count + ' ' + noun + (count === 1 ? '' : 's');
  }

  function reverseChildren(parent, selector) {
    Array.prototype.slice.call(parent.querySelectorAll(selector)).reverse().forEach(function (child) {
      child.parentNode.appendChild(child);
    });
  }

  if (calendar) {
    var months = Array.prototype.slice.call(calendar.querySelectorAll('.event-month'));
    var countryField = document.querySelector('.event-filter__country');
    var pastLink = document.querySelector('.event-foot__past');
    var countEl = document.querySelector('.event-filter__count');
    var calendarState = { type: getParam('type'), country: getParam('country'), past: getParam('past') === '1' };

    function applyCalendar() {
      var total = 0;
      months.forEach(function (month) {
        var visible = 0;
        month.querySelectorAll('.event-row').forEach(function (row) {
          var ended = row.dataset.status === 'ended';
          var match = ended === calendarState.past &&
            (calendarState.type === 'all' || row.getAttribute('data-type') === calendarState.type) &&
            (calendarState.country === 'all' || row.getAttribute('data-region').split(' ').indexOf(calendarState.country) !== -1);
          row.hidden = !match;
          if (match) { visible += 1; }
        });
        month.hidden = visible === 0;
        month.querySelector('.event-month__count').textContent = pluralise(visible, 'event');
        total += visible;
      });
      countEl.textContent = pluralise(total, calendarState.past ? 'past event' : 'upcoming event');
      document.querySelector('.event-empty').hidden = total > 0;
      pastLink.textContent = calendarState.past ? 'Show upcoming events' : 'Show past events';
    }

    // Past events read newest first. The server already prints them that way
    // when it renders the past view; the static pages hold every event oldest first.
    var newestFirst = calendar.hasAttribute('data-newest-first');

    function setOrder(wantNewestFirst) {
      if (wantNewestFirst === newestFirst) { return; }
      reverseChildren(calendar, '.event-month');
      months = Array.prototype.slice.call(calendar.querySelectorAll('.event-month'));
      months.forEach(function (month) { reverseChildren(month, '.event-row'); });
      newestFirst = wantNewestFirst;
    }

    wirePillGroup(document.querySelector('.event-types'), calendarState.type, function (value) {
      calendarState.type = value;
      setParam('type', value);
      applyCalendar();
    });

    countryField.value = calendarState.country;
    countryField.addEventListener('change', function () {
      calendarState.country = countryField.value;
      setParam('country', calendarState.country);
      applyCalendar();
    });
    document.querySelector('.event-filter__apply').hidden = true;

    // WordPress only prints one of the two views, so there the link stays a link.
    if (calendar.hasAttribute('data-all-events')) {
      pastLink.addEventListener('click', function (event) {
        event.preventDefault();
        calendarState.past = !calendarState.past;
        setParam('past', calendarState.past ? '1' : 'all');
        setOrder(calendarState.past);
        applyCalendar();
      });
    }

    setOrder(calendarState.past);
    applyCalendar();
  }

  /* ------------------------------------------------------------------
     07. Intelligence archive filter
     Filtering hands the "Latest" feature card to whichever article is now
     first. With more than one page of articles the list is filtered by the
     server instead, so the links stay as they are.
     ------------------------------------------------------------------ */
  var intelGrid = document.querySelector('.intel-grid[data-filterable]');
  var intelCards = Array.prototype.slice.call(document.querySelectorAll('.intel-grid[data-filterable] .intel-card'));

  function applyIntelFilter(topic, topicName) {
    var first = null;
    intelCards.forEach(function (card) {
      var match = topic === 'all' || card.getAttribute('data-category') === topic;
      card.hidden = !match;
      card.classList.remove('intel-card--lead');
      if (match && !first) { first = card; }
    });
    if (first) { first.classList.add('intel-card--lead'); }
    var empty = document.querySelector('.intel-empty');
    empty.hidden = Boolean(first);
    empty.textContent = 'No reads tagged ' + topicName + ' yet.';
  }

  if (intelGrid) {
    var intelFilter = document.querySelector('.intel-filter');
    var intelTopic = getParam('topic');
    wirePillGroup(intelFilter, intelTopic, function (value) {
      setParam('topic', value);
      applyIntelFilter(value, intelFilter.querySelector('[data-filter="' + value + '"]').textContent);
    });
    var activeTopic = intelFilter.querySelector('[data-filter="' + intelTopic + '"]');
    applyIntelFilter(intelTopic, activeTopic ? activeTopic.textContent : '');
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
