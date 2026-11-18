<?php
lejwana_set_page_meta(
  'Contact | Reach Lejwana | polishedtrade@lejwana.com',
  'Email polishedtrade@lejwana.com, book a 30-minute introduction, or send a trade enquiry. We reply within one working day.'
);
get_header();
?>

<main id="main">
  <!-- Hero -->
  <section class="hero hero--page">
    <div class="container">
      <span class="eyebrow">Contact</span>
      <h1>Send us a specification.</h1>
      <p class="hero__subheading">Tell us the goods you need. The enquiry goes to sourcing, trading or advisory.</p>
    </div>
  </section>

  <!-- Two channels -->
  <section class="section bg">
    <div class="container">
      <div class="contact-channels">
        <div class="channel">
          <h2 class="channel__title">Email</h2>
          <p><a class="channel__email" href="mailto:polishedtrade@lejwana.com">polishedtrade@lejwana.com</a></p>
          <button class="channel__copy-button" type="button" data-email="polishedtrade@lejwana.com">Copy email address</button>
        </div>
        <div class="channel">
          <h2 class="channel__title">Book a call</h2>
          <ul class="channel__durations">
            <li>30-minute introduction</li>
          </ul>
          <!-- Cal.com loads on request so the page stays fast. -->
          <button class="button" type="button" data-cal-link="lejwana/30min" data-cal-target="#cal-container">Open the booking calendar</button>
          <div class="cal-embed" id="cal-container"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enquiry form -->
  <section class="section section--sky" id="enquiry">
    <div class="container">
      <h2 class="section__title section__title--centred">Send an enquiry</h2>
      <form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
        <input type="hidden" name="action" value="lejwana_enquiry">
        <?php wp_nonce_field( 'lejwana_enquiry' ); ?>
        <div class="u-visually-hidden" aria-hidden="true">
          <label for="field-website">Leave this field empty</label>
          <input type="text" id="field-website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <div class="form__row">
          <div class="form__field">
            <label class="form__label" for="field-name">Name</label>
            <input class="form__input" type="text" id="field-name" name="name" autocomplete="name" required>
            <p class="form__error">Add your name.</p>
          </div>
          <div class="form__field">
            <label class="form__label" for="field-company">Company</label>
            <input class="form__input" type="text" id="field-company" name="company" autocomplete="organization" required>
            <p class="form__error">Add your company.</p>
          </div>
        </div>
        <div class="form__field">
          <label class="form__label" for="field-email">Email</label>
          <input class="form__input" type="email" id="field-email" name="email" autocomplete="email" required>
          <p class="form__error">Add an email address we can reply to.</p>
        </div>
        <div class="form__field">
          <label class="form__label" for="field-enquiry">Enquiry</label>
          <select class="form__select" id="field-enquiry" name="enquiry" required>
            <option value="">Choose one</option>
            <option value="sourcing">Sourcing</option>
            <option value="trading">Trading</option>
            <option value="advisory">Advisory</option>
            <option value="general">General</option>
          </select>
          <p class="form__error">Choose an enquiry type.</p>
        </div>

        <fieldset class="form__fieldset">
          <legend class="form__legend">Specification (optional)</legend>

          <div class="form__row">
            <div class="form__field">
              <label class="form__label" for="field-shape">Shape</label>
              <select class="form__select" id="field-shape" name="shape">
                <option value="">No preference</option>
                <option value="round-brilliant">Round brilliant</option>
                <option value="princess">Princess</option>
                <option value="oval">Oval</option>
                <option value="pear">Pear</option>
                <option value="emerald">Emerald</option>
                <option value="cushion">Cushion</option>
                <option value="marquise">Marquise</option>
                <option value="other">Other / fancy shape</option>
              </select>
            </div>
            <div class="form__field">
              <label class="form__label" for="field-weight">Weight</label>
              <input class="form__input" type="text" id="field-weight" name="weight" placeholder="e.g. 1.00–1.50ct, or total weight for a parcel">
            </div>
          </div>

          <div class="form__row">
            <div class="form__field">
              <label class="form__label" for="field-colour">Colour</label>
              <select class="form__select" id="field-colour" name="colour">
                <option value="">No preference</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="O">O</option>
                <option value="P">P</option>
                <option value="Q">Q</option>
                <option value="R">R</option>
                <option value="S">S</option>
                <option value="T">T</option>
                <option value="U">U</option>
                <option value="V">V</option>
                <option value="W">W</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>
                <option value="fancy">Fancy colour</option>
              </select>
            </div>
            <div class="form__field">
              <label class="form__label" for="field-clarity">Clarity</label>
              <select class="form__select" id="field-clarity" name="clarity">
                <option value="">No preference</option>
                <option value="FL">FL</option>
                <option value="IF">IF</option>
                <option value="VVS1">VVS1</option>
                <option value="VVS2">VVS2</option>
                <option value="VS1">VS1</option>
                <option value="VS2">VS2</option>
                <option value="SI1">SI1</option>
                <option value="SI2">SI2</option>
                <option value="I1">I1</option>
                <option value="I2">I2</option>
                <option value="I3">I3</option>
              </select>
            </div>
          </div>

          <div class="form__row">
            <div class="form__field">
              <label class="form__label" for="field-cut">Cut, polish, symmetry</label>
              <select class="form__select" id="field-cut" name="cut">
                <option value="">No preference</option>
                <option value="excellent">Excellent</option>
                <option value="very-good">Very Good</option>
                <option value="good">Good</option>
                <option value="fair">Fair</option>
                <option value="poor">Poor</option>
              </select>
            </div>
            <div class="form__field">
              <label class="form__label" for="field-fluorescence">Fluorescence</label>
              <select class="form__select" id="field-fluorescence" name="fluorescence">
                <option value="">No preference</option>
                <option value="none">None</option>
                <option value="faint">Faint</option>
                <option value="medium">Medium</option>
                <option value="strong">Strong</option>
                <option value="very-strong">Very Strong</option>
              </select>
            </div>
          </div>

          <div class="form__row">
            <div class="form__field">
              <label class="form__label" for="field-certification">Certification</label>
              <select class="form__select" id="field-certification" name="certification">
                <option value="">No preference</option>
                <option value="GIA">GIA</option>
                <option value="IGI">IGI</option>
                <option value="HRD">HRD</option>
                <option value="AGS">AGS</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="form__field">
              <label class="form__label" for="field-provenance">Provenance</label>
              <select class="form__select" id="field-provenance" name="provenance">
                <option value="">No preference</option>
                <option value="Botswana">Botswana</option>
                <option value="Angola">Angola</option>
                <option value="South Africa">South Africa</option>
                <option value="Namibia">Namibia</option>
                <option value="Lesotho">Lesotho</option>
              </select>
            </div>
          </div>

          <div class="form__field">
            <label class="form__label" for="field-commercial">Commercial</label>
            <input class="form__input" type="text" id="field-commercial" name="commercial" placeholder="Budget or discount off the list, quantity and timing">
          </div>
        </fieldset>

        <div class="form__field">
          <label class="form__label" for="field-message">Message</label>
          <textarea class="form__textarea" id="field-message" name="message" required></textarea>
          <p class="form__error">Tell us what you need.</p>
        </div>
        <button class="button" type="submit">Send enquiry</button>
        <?php $enquiry_outcome = lejwana_get_enquiry_outcome(); ?>
        <p class="form__success<?php echo 'sent' === $enquiry_outcome ? ' form__success--visible' : ''; ?>" role="status">Received. We will reply within one working day.</p>
        <?php if ( 'invalid' === $enquiry_outcome ) : ?>
        <p class="form__failure" role="alert">Your enquiry was not sent. Check each required field and send it again.</p>
        <?php elseif ( 'failed' === $enquiry_outcome ) : ?>
        <p class="form__failure" role="alert">Your enquiry was not sent. Email polishedtrade@lejwana.com and we will reply within one working day.</p>
        <?php endif; ?>
      </form>
    </div>
  </section>

  <!-- Locations
  <section class="section section--sky">
    <div class="container">
      <h2 class="section__title">Where to find us</h2>
      <div class="location-cards">
        <div class="location-card">
          <h3 class="location-card__title">Gaborone</h3>
          <p>Lejwana's primary Southern African location.</p>
          [CONFIRM: Gaborone address details]
        </div>
        <div class="location-card">
          <h3 class="location-card__title">London Diamond Bourse</h3>
          <p>By appointment.</p>
        </div>
      </div>
      <p class="membership-line membership-line--spaced">Members of the London Diamond Bourse and the World Federation of Diamond Bourses.</p>
    </div>
  </section> -->
</main>

<?php get_footer(); ?>
