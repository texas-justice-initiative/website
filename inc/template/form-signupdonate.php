<div id="<?= $signupdonate_id ?>" class="tji-modal">
  <div class="tji-modal__body">
    <form class="tji-modal__form">

      <div class="js-formpanel js-formpanel-whoamiparticipate tji-modal__form-panel">
        <h2>Thanks for visiting TJI!</h2>
        <p>Can you answer a few quick questions to help us make TJI as useful as possible?</p>
        <input type="hidden" name="whoamiparticipate" value=""/>
        <div class="tji-modal__buttons">
          <a href="#" class="js-optout link link--cancel">No Thanks</a>
          <a href="#" class="js-next btn">Yes, of course!</a>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-whoami tji-modal__form-panel">
        <h2>I am a ...</h2>
        <p>To better understand how are data is used, it's helpful for us to know who you are. Please let us know what your background is by selecting a user type below.</p>
        <fieldset>
          <div class="tji-modal__fieldset-flex">
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-civilian" type="radio" name="whoami" value="civilian"/>
              <label for="whoami-civilian">Civilian</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-lawyer" type="radio" name="whoami" value="lawyer"/>
              <label for="whoami-lawyer">Lawyer</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-peaceofficer" type="radio" name="whoami" value="peaceofficer"/>
              <label for="whoami-peaceofficer">Peace officer</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-policymaker" type="radio" name="whoami" value="policymaker"/>
              <label for="whoami-policymaker">Policy Maker</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-researcher" type="radio" name="whoami" value="researcher"/>
              <label for="whoami-researcher">Researcher</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoami-reporter" type="radio" name="whoami" value="reporter"/>
              <label for="whoami-reporter">Reporter</label>
            </div>
            <div class="tji-modal__form-radio-group">
              <input id="whoami-nondiscloser" type="radio" name="whoami" value="nondiscloser"/>
              <label for="whoami-nondiscloser">Prefer not to disclose</label>
            </div>
            <div class="tji-modal__form-radio-group tji-modal__form-radio-group--textinput">
              <input id="whoami-other" type="radio" name="whoami" value="other" />
              <label for="whoami-other"><input type="text" name="whoami_other" placeholder="Other" /></label>
            </div>
          </div> 
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-log-whoami btn">Continue</a>
        </div>
      </div>

      <div class="js-formpanel js-formpanel-whoamidata tji-modal__form-panel">
        <h2>I'm looking for data on...</h2>
        <p>To ensure we're collecting data that is useful to you, we need feedback on the type of data you're searching for. Use the input below to describe the type of data you're looking for.</p>
        <fieldset>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="whoamidata" placeholder="ex. Officers shot" />
          </div>

          <p>I found the data I was looking for...</p>
          <div class="tji-modal__fieldset-flex">
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoamidata_yesno-yes" type="radio" name="whoamidata_yesno" value="yes"/>
              <label for="whoamidata_yesno-yes">Yes!</label>
            </div>
            <div class="tji-modal__form-col-2 tji-modal__form-radio-group">
              <input id="whoamidata_yesno-no" type="radio" name="whoamidata_yesno" value="no"/>
              <label for="whoamidata_yesno-no">No</label>
            </div>
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-log-whoamidata btn">Continue</a>          
        </div>
      </div>
      
      <div class="js-formpanel js-formpanel-newsletter tji-modal__form-panel">
        <h2>I'm interested in TJI updates...</h2>
        <p>Thank you for your interest in TJI! For regular updates on TJI’s data in use, our latest data offerings and the most recent revelations, sign up for our newsletter, a short read that hits inboxes monthly. We promise not to sell your email address.</p>
        <fieldset>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="fname" placeholder="First Name" />
          </div>
          <div class="tji-modal__form-text-group tji-modal__form-group--center">
            <input type="text" name="email" placeholder="Email" />
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-next link link--cancel">No Thanks</a>
          <a href="#" class="js-signup-newsletter btn">Sign up!</a>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-donate tji-modal__form-panel">
        <h2>I'd like to contribute to TJI...</h2>
        <p>TJI is a nonprofit organization that seeks to serve as a data resource. Donations help pay for records and staff time to expand our portal. Please consider donating $5 today through PayPal. Don't have PayPal? Donate via  <a href="https://www.facebook.com/TXJusticeInitiative/">Facebook</a>.</p>
        <fieldset>
          <div class="tji-modal__form-text-group">
            <input type="text" name="donor_fname" placeholder="Donor First Name" />
          </div>
          <div class="tji-modal__form-text-group">
            <input type="text" name="donor_lname" placeholder="Donor Last Name" />
          </div>
          <div class="tji-modal__form-text-group">
            <input type="text" name="donor_email" placeholder="Donor Email" />
          </div>
          <p class="font-blue font-bold">Choose a donation amount: </p>
          <div class="tji-modal__fieldset-flex">
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
              <label for="donate-other"><span>$</span><input type="text" name="donation_other" placeholder="5" /></label>
            </div>
          </div>
        </fieldset>
        <div class="tji-modal__buttons">
          <a href="#" class="js-next link link--cancel">No Thanks</a>
          <a href="#" class="js-donate btn">Donate!</a>          
        </div>
      </div>

      <div class="js-formpanel js-formpanel-donate-confirmation tji-modal__form-panel">
        <h2>Choose a payment type:</h2>
        <p>Currently we only use paypal to accept payments. Click Paypal to donate below.</p>
        <fieldset></fieldset>
        <div class="tji-modal__buttons">
          <div>
            <div id="<?= $signupdonate_id ?>-paypal" class="tji-modal__paypal-button"></div>
            <a href="#" class="js-next link link--cancel">No thanks</a>
          </div>
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
    <div class="tji-modal__loader-overlay">
      <div class="tji-modal__loader"></div>
    </div>
  </div>
</div>