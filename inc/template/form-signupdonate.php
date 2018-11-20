<div id="<?= $signupdonate_id ?>" class="tji-modal">
  <div class="tji-modal__body">
    <form class="tji-modal__form">

      <div class="js-formpanel js-formpanel-whoami tji-modal__form-panel">
        <h2>What's your deal?</h2>
        <p>Tji blah blah Groom yourself 4 hours - checked, have your beauty sleep 18 hours - checked, be fabulous for the rest of the day. </p>
        <fieldset>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-researcher" type="radio" name="whoami" value="researcher"/>
            <label for="whoami-researcher">Researcher</label>
          </div>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-reporter" type="radio" name="whoami" value="reporter"/>
            <label for="whoami-reporter">Reporter</label>
          </div>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-policy" type="radio" name="whoami" value="policy"/>
            <label for="whoami-policy">Policy</label>
          </div>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-legal" type="radio" name="whoami" value="legal"/>
            <label for="whoami-legal">Legal</label>
          </div>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-personal" type="radio" name="whoami" value="personal"/>
            <label for="whoami-personal">Personal</label>
          </div>
          <div class="tji-modal__form-radio-group">
            <input id="whoami-nodisclosure" type="radio" name="whoami" value="no discloser"/>
            <label for="whoami-nodisclosure">Prefer not to disclose</label>
          </div>
          <div class="tji-modal__form-radio-group tji-modal__form-radio-group--textinput">
            <input id="whoami-other" type="radio" name="whoami" value="other" />
            <label for="whoami-other"><input type="text" name="whoami_other" placeholder="Other" /></label>
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-log-whoami btn">Continue</a>
        </div>
      </div>
      
      <div class="js-formpanel js-formpanel-newsletter tji-modal__form-panel">
        <h2>What's your email?</h2>
        <p>Did you want to sign up for our newsletter?</p>
        <fieldset>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="fname" placeholder="First Name" />
          </div>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="email" placeholder="Email" />
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-next link link--cancel">Nope</a>
          <a href="#" class="js-signup-newsletter btn">Yep</a>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-donate tji-modal__form-panel">
        <h2>Please give us your $$$</h2>
        <p>TJI could really use your money. We'll use it to pay for cheetos for our volunteers? I dunno. </p>
        <fieldset>
          <div class="tji-modal__form-text-group">
            <input type="text" name="donor-fname" placeholder="Donor First Name" />
          </div>
          <div class="tji-modal__form-text-group">
            <input type="text" name="donor-email" placeholder="Donor Email" />
          </div>
          <p class="font-blue font-bold">Choose a donation amount: </p>
          <div class="tji-modal__fieldset-flex">
            <div class="tji-modal__form-col-3 tji-modal__form-radio-group">
              <input id="donate-500" type="radio" name="donation" value="500"/>
              <label for="donate-500">$500</label>
            </div>
            <div class="tji-modal__form-col-3 tji-modal__form-radio-group">
              <input id="donate-250" type="radio" name="donation" value="250"/>
              <label for="donate-250">$250</label>
            </div>
            <div class="tji-modal__form-col-3 tji-modal__form-radio-group">
              <input id="donate-100" type="radio" name="donation" value="100"/>
              <label for="donate-100">$100</label>
            </div>
            <div class="tji-modal__form-col-3 tji-modal__form-radio-group">
              <input id="donate-50" type="radio" name="donation" value="50"/>
              <label for="donate-50">$50</label>
            </div>
            <div class="tji-modal__form-col-3 tji-modal__form-radio-group">
              <input id="donate-25" type="radio" name="donation" value="25"/>
              <label for="donate-25">$25</label>
            </div>
            <div class="tji-modal__form-col-1 tji-modal__form-radio-group tji-modal__form-radio-group--textinput">
              <input id="donate-other" type="radio" name="donation" value="other" />
              <label for="donate-other"><span>$</span><input type="text" name="donation_other" placeholder="10" /></label>
            </div>
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-next link link--cancel">Nope</a>
          <a href="#" class="js-donate btn">Yep</a>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-donate-confirmation tji-modal__form-panel">
        <h2>Do you really wanna give us $$$???</h2>
        <p>Please confirm that all the stuff below is legit</p>
        <fieldset>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="donor-fname" readonly />
          </div>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="donor-email" readonly />
          </div>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <span>$</span><input type="text" name="donation" readonly />
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-next link link--cancel">Nope</a>
          <div id="<?= $signupdonate_id ?>-paypal"></div>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-thanks tji-modal__form-panel">
        <h2>Thanks!</h2>
        <p>We really appreciate you and your work with the criminal justice system!</p>

        <div class="tji-modal__buttons">
          <a href="#" class="js-cancel btn">You're Welcome!</a>
        </div>
      </div>
      
    </form>
  </div>
</div>