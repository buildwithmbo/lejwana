<?php
lejwana_set_page_meta(
  'Sourcing | Natural Polished Diamonds from Southern Africa | Lejwana',
  'Send the specification. We work the network, view the goods, and report back with what fits. Sourced to your specification across Botswana, Angola, South Africa and Namibia.'
);
get_header();
?>

<main id="main">
  <!-- Hero -->
  <section class="hero hero--page hero--split">
    <div class="container hero__split">
      <div>
        <span class="eyebrow">Services &middot; Sourcing</span>
        <h1>Sourced to your specification.</h1>
        <p class="hero__subheading">Send the specification. We work the network, view the goods, and report back with what fits.</p>
      </div>
      <div class="hero__figure">
        <img class="hero__figure-image" src="<?php lejwana_the_asset( 'images/sourcing-hero.jpg' ); ?>" width="1327" height="784" alt="" aria-hidden="true">
      </div>
    </div>
  </section>

  <!-- Process steps -->
  <section class="section">
    <div class="container">
      <div class="spec-row">
        <div>
          <h2 class="section__title">The specification</h2>
          <p>Shape, weight, colour, clarity, cut, fluorescence, certification, budget, timing and any provenance requirement. Fancy colour and matched layouts are handled on the same basis.</p>
        </div>
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/sourcing-spec-tags.jpg' ); ?>" width="1185" height="880" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="spec-row">
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/sourcing-network.jpg' ); ?>" width="1202" height="880" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
        <div>
          <h2 class="section__title">Working the network</h2>
          <p>We go to the cutting and polishing partners and dealers we hold relationships with across Botswana, Angola, South Africa and Namibia. Goods are viewed on the ground before anything is put to you.</p>
        </div>
      </div>

      <div class="spec-row">
        <div>
          <h2 class="section__title">What comes back</h2>
          <p>What fits the specification, what it prices at against the list, and what certification and provenance records sit behind it. Where a parcel is close but not exact, we say where it differs and what that means for price.</p>
        </div>
        <div class="spec-row__panel">
          <img class="spec-row__image" src="<?php lejwana_the_asset( 'images/sourcing-checklist.jpg' ); ?>" width="1191" height="880" alt="" aria-hidden="true" loading="lazy" decoding="async">
        </div>
      </div>

      <p class="spec-cta"><a class="text-link" href="#spec-builder">Submit your specification &rarr;</a></p>
    </div>
  </section>

  <!-- What to send -->
  <section class="section section--sky" id="spec-builder">
    <div class="container">
      <h2 class="section__title section__title--centred">What to send</h2>
      <p class="section__intro section__intro--centred">The more of the specification we have, the more precise the first response. Every field is optional.</p>

      <form class="spec-builder" method="get" action="<?php lejwana_the_url( 'contact/' ); ?>">
        <input type="hidden" name="enquiry" value="sourcing">

        <div class="form__row">
          <div class="form__field">
            <label class="form__label" for="spec-shape">Shape</label>
            <select class="form__select" id="spec-shape" name="shape">
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
            <label class="form__label" for="spec-weight">Weight</label>
            <input class="form__input" type="text" id="spec-weight" name="weight" placeholder="e.g. 1.00–1.50ct, or total weight for a parcel">
          </div>
        </div>

        <div class="form__row">
          <div class="form__field">
            <label class="form__label" for="spec-colour">Colour</label>
            <select class="form__select" id="spec-colour" name="colour">
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
            <label class="form__label" for="spec-clarity">Clarity</label>
            <select class="form__select" id="spec-clarity" name="clarity">
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
            <label class="form__label" for="spec-cut">Cut, polish, symmetry</label>
            <select class="form__select" id="spec-cut" name="cut">
              <option value="">No preference</option>
              <option value="excellent">Excellent</option>
              <option value="very-good">Very Good</option>
              <option value="good">Good</option>
              <option value="fair">Fair</option>
              <option value="poor">Poor</option>
            </select>
          </div>
          <div class="form__field">
            <label class="form__label" for="spec-fluorescence">Fluorescence</label>
            <select class="form__select" id="spec-fluorescence" name="fluorescence">
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
            <label class="form__label" for="spec-certification">Certification</label>
            <select class="form__select" id="spec-certification" name="certification">
              <option value="">No preference</option>
              <option value="GIA">GIA</option>
              <option value="IGI">IGI</option>
              <option value="HRD">HRD</option>
              <option value="AGS">AGS</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form__field">
            <label class="form__label" for="spec-provenance">Provenance</label>
            <select class="form__select" id="spec-provenance" name="provenance">
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
          <label class="form__label" for="spec-commercial">Commercial</label>
          <input class="form__input" type="text" id="spec-commercial" name="commercial" placeholder="Budget or discount off the list, quantity and timing">
        </div>

        <button class="button" type="submit">Continue to enquiry &rarr;</button>
      </form>
    </div>
  </section>
</main>

<?php get_footer(); ?>
