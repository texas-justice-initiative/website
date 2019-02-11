// *******************************************************************
// * "Class" for generating a slider form modal to manage: 
// * user information collection, 
// * newsletter sign up and 
// * donation collection
// *
// * Constructor arguments as properties of props object:
// *   modal_elt_selector: jQuery object with modal DOM
// *   local_storage_key: key used to store data to localStorage
// *   show_panels: array ['whoami', 'newsletter', 'donate']
// *
// * Dependencies: jQuery, Google Analytics, Paypal checkout
// *******************************************************************

var TJISignupDonateFormModal = function(props) {
  
  var that = this;

  this.props = props;

  // panels to show
  this.panels = props.show_panels || ['whoamiparticipate', 'whoami', 'whoamidata', 'newsletter', 'donate'];
  this.panels.push('thanks');

  // key to save data to local storage / user's browser
  this.local_storage_key = props.local_storage_key;

  //properties that describe current state of app
  //ex. selected current panel/step of form
  this.state = {
    panel: 0,
    data: {},
  }

  //jquery object references to DOM elements
  this.ui = {
    $modal:   jQuery(props.modal_elt_selector),
    $loader:  jQuery(props.modal_elt_selector).find('.tji-modal__loader-overlay'),
  };

  //TODO: create attach dom method which inserts DOM instead of prewriting dom with PHP
  this.ui.$loader.hide();

  this.attach_events();
}

TJISignupDonateFormModal.prototype.attach_events = function() {
  var that = this;

  //clear validation when user interacts with inputs
  this.ui.$modal.find('input').on('focus', function(e){
    that.clear_messages();
  });

  //select the radio next to the custom radio value text input when a user is typing within that custom input
  //this is for users to send a custom text value in a group of radio buttons
  this.ui.$modal.find('.tji-modal__form-radio-group--textinput').on('focus', 'input[type="text"]', function(e){
    jQuery(e.delegateTarget).find('input[type="radio"]').prop('checked', true);
  });

  //clear the custom radio value text input when another radio is clicked
  //this is for users to send a custom text value in a group of radio buttons
  this.ui.$modal.find('.tji-modal__form-radio-group').on('click', function(e){
    jQuery(e.currentTarget).siblings('.tji-modal__form-radio-group--textinput').find('input[type="text"]').val('');
  });

  this.ui.$modal.on('click', '.js-optout', function(e){
    e.preventDefault();
    that.log('whoami_optout', 'whoamiparticipate', null, true);
    that.close();
  });
  this.ui.$modal.on('click', '.js-next', function(e){
    e.preventDefault();
    that.next();
  });
  this.ui.$modal.on('click', '.js-cancel', function(e){
    e.preventDefault();
    that.close();
  });
  this.ui.$modal.on('click', '.js-edit', function(e){
    e.preventDefault();
    that.show_panel(that.panels[that.state.panel], null, 'Please edit your information below.');
  });
  this.ui.$modal.on('click', '.js-log-whoami', function(e){
    e.preventDefault();
    that.log('I_am', 'whoami', 'Thanks for helping us better know our users!');
  });
  this.ui.$modal.on('click', '.js-log-whoamidata', function(e){
    e.preventDefault();
    that.log('I_want_data_on', 'whoamidata', 'Thanks for letting us know what type of data we can collect for you!');
  });
  this.ui.$modal.on('click', '.js-signup-newsletter', function(e){
    e.preventDefault();
    that.signup();
  });
  this.ui.$modal.on('click', '.js-donate', function(e){
    e.preventDefault();
    that.donate()
  });
}

TJISignupDonateFormModal.prototype.show_panel = function(panel_name, message, error_message) {
  //TODO: some transition animation / slide out and slide in transition?
  var $target_panel = this.ui.$modal.find('.js-formpanel-' + panel_name);
  this.ui.$modal.find('.js-formpanel').hide();
  this.clear_messages();
  if(message) {
    $target_panel.prepend('<p class="tji-modal__form-panel-success">' + message + '</p>')
  }
  if(error_message) this.render_validation_error(error_message);
  this.prefill_panel(panel_name)
  $target_panel.show();
}

TJISignupDonateFormModal.prototype.prefill_panel = function(panel_name) {
  var $target_panel = this.ui.$modal.find('.js-formpanel-' + panel_name);

  if(panel_name === 'newsletter') {
    if(this.state.data.email) {
      $target_panel.find('input[name="email"]').val(this.state.data.email);
      $target_panel.find('input[name="fname"]').val(this.state.data.fname);
      return;
    }
    if(this.state.data.donor_email) {
      $target_panel.find('input[name="email"]').val(this.state.data.donor_email);
      $target_panel.find('input[name="fname"]').val(this.state.data.donor_fname);
      return;
    }
  } 
  if(panel_name == 'donate') {
    if(this.state.data.donor_email) {
      $target_panel.find('input[name="donor_email"]').val(this.state.data.donor_email);
      $target_panel.find('input[name="donor_fname"]').val(this.state.data.donor_fname);
      return;
    }
    if(this.state.data.email) {
      $target_panel.find('input[name="donor_email"]').val(this.state.data.email);
      $target_panel.find('input[name="donor_fname"]').val(this.state.data.fname);
      return;
    }
  }
  if(panel_name == 'donate-confirmation') {
    $target_panel.find('input[name="donor_email"]').val(this.state.data.donor_email);
    $target_panel.find('input[name="donor_fname"]').val(this.state.data.donor_fname);
    $target_panel.find('input[name="donor_lname"]').val(this.state.data.donor_lname);
    $target_panel.find('input[name="donation"]').val(this.state.data.donation);
    return;
  }
}

TJISignupDonateFormModal.prototype.next = function(message) {
  this.state.panel++;
  this.show_panel(this.panels[this.state.panel], message);
}

TJISignupDonateFormModal.prototype.open = function() {
  this.ui.$modal.find('.js-formpanel').hide();
  this.ui.$modal.find('.js-formpanel-' + this.panels[this.state.panel]).show();
  this.ui.$modal.addClass('opened');
}

TJISignupDonateFormModal.prototype.close = function() {
  var that = this;
  this.ui.$modal
    .hide(function(){
      jQuery(this)
        .removeClass('opened')
        .show(0)
        .find('.tji-modal__form-panel-success').remove();
      that.clear_messages();
      that.clear_fields();
    });
    
  this.state.panel = 0;
  this.state.data = {};
}

TJISignupDonateFormModal.prototype.render_validation_error = function(error_message, panel) {
  var panel = panel || this.panels[this.state.panel];
  var $error = jQuery('<div class="tji-modal__form-panel-error" />').text(error_message);
  this.ui.$modal.find('.js-formpanel-' + panel)
    .find('fieldset')
    .before($error);
}

TJISignupDonateFormModal.prototype.clear_fields = function() {
  this.ui.$modal.find('form').trigger('reset')
}

TJISignupDonateFormModal.prototype.clear_messages = function() {
  this.ui.$modal.find('.tji-modal__form-panel-error').remove();
  this.ui.$modal.find('.tji-modal__form-panel-success').remove();
}

TJISignupDonateFormModal.prototype.set_data_and_validate = function() {
  this.clear_messages();
  
  var data = this.ui.$modal.find('.js-formpanel-' + this.panels[this.state.panel]).find('[name]').serializeArray();
  _.assign(this.state.data, _.mapValues(_.keyBy(data, 'name'), 'value'));

  if(this.panels[this.state.panel] === 'whoami') {
    this.state.data.whoami = (this.state.data.whoami === 'other') ? this.state.data.whoami_other : this.state.data.whoami;
    if (!this.state.data.whoami) {
      this.render_validation_error('Please let us know who you are.');
      return false;
    }
  }
  if(this.panels[this.state.panel] === 'whoamidata') {
    if (!this.state.data.whoamidata) {
      this.render_validation_error('Please let us know what type of data you\'re searching for.');
      return false;
    }
    if (!this.state.data.whoamidata_yesno) {
      this.render_validation_error('Please let us know if we had the data you were looking for.');
      return false;
    }
    this.state.data.whoamidata = this.state.data.whoamidata_yesno + '-' + this.state.data.whoamidata;
  }
  if(this.panels[this.state.panel] === 'newsletter') {
    this.state.data.fname = this.state.data.fname.trim();
    if(!this.state.data.fname) {
      this.render_validation_error('Please enter your first name.');
      return false;
    }
    if(!/\S+@\S+\.\S+/.test(this.state.data.email)) {
      this.render_validation_error('Please enter a valid email address.');
      return false;
    }
  }
  if(this.panels[this.state.panel] === 'donate') {
    this.state.data.donor_fname = this.state.data.donor_fname.trim();
    if(!this.state.data.donor_fname) {
      this.render_validation_error('Please enter the donor\'s first name.');
      return false;
    }
    this.state.data.donor_lname = this.state.data.donor_lname.trim();
    if(!this.state.data.donor_lname) {
      this.render_validation_error('Please enter the donor\'s last name.');
      return false;
    }
    if(!/\S+@\S+\.\S+/.test(this.state.data.donor_email)) {
      this.render_validation_error('Please enter a valid email address');
      return false;
    }
    this.state.data.donation = this.state.data.donation === 'other' ? this.state.data.donation_other : this.state.data.donation;
    this.state.data.donation = parseFloat(this.state.data.donation).toFixed(2);
    if(isNaN(this.state.data.donation) || !this.state.data.donation) {
      this.render_validation_error('Please enter a numeric dollar amount more than 0');
      return false;
    }
  }

  localStorage.setItem(this.local_storage_key, JSON.stringify(this.state.data));
  return true;
}

TJISignupDonateFormModal.prototype.log = function(analytics_category, data_name, message, stop) {
  if(!this.set_data_and_validate())
    return;
console.log('EVENT', analytics_category, this.state.data[data_name]);
  ga('send', 'event', analytics_category, this.state.data[data_name]);
  
  if(stop) 
    return;
  
  this.next(message);
}

TJISignupDonateFormModal.prototype.signup = function() {
  if(!this.set_data_and_validate())
    return;

  var that = this;
  this.ui.$loader.show();
  jQuery.post('/wp-json/newsletter/signup/', this.state.data)
    .done(function(response){      
      that.next('Thanks, ' + response +'! You\'re all signed up for our newsletter!');
      that.ui.$loader.hide();
    })
    .fail(function(error){
      that.render_validation_error('OH NO! ' + error.responseJSON.message);
      that.ui.$loader.hide();
    });
}

TJISignupDonateFormModal.prototype.donate = function() {
  if(!this.set_data_and_validate())
    return;
  this.initialize_paypal();
  this.show_panel('donate-confirmation');
}

TJISignupDonateFormModal.prototype.initialize_paypal = function() {

  var that = this;
  
  jQuery(this.props.modal_elt_selector + '-paypal').empty();
  
  paypal.Button.render({

      // Set your environment

    env: 'sandbox', // sandbox | production

    // Specify the style of the button

    style: {
      label: 'checkout',
      size:  'medium',    // small | medium | large | responsive
      shape: 'rect',     // pill | rect
      color: 'gold',      // gold | blue | silver | black
      tagline: false
    },

    // PayPal Client IDs - replace with your own
    // Create a PayPal app: https://developer.paypal.com/developer/applications/create

    client: {
      sandbox: 'AZ2LDJwEbuFjH45Izqk5pmxHtyzxtooUPBCrvrn7tjKXIbv-xGxXsflhCMGl6dy2tRBEliztwiPzCckc',
      production: 'ATVsibuGpsWpkiSPBnC3hyrqeQFZ-YlLW9BYzg53mOehgbTUv6ZESr0lX-u30xmgxxUosQX5QtQNpT81'
    },

    payment: function(data, actions) {
      that.ui.$loader.show();
      return actions.payment.create({
        payment: {
          transactions: [
            {
              amount: { total: that.state.data.donation, currency: 'USD' },
              description: 'A donation supporting the Texas Justice Initiative.',
              payment_options: {
                allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
              },
            }
          ],
          payer: {
            payer_info: {
              email: that.state.data.donor_email,
              first_name: that.state.data.donor_fname,
              last_name: that.state.data.donor_lname,
            },
          },
          note_to_payer: 'Thank you for supporting the Texas Justice Initiative. We greatly appreciate your generous donation!'
        },
        experience: {
          input_fields: {
            no_shipping: 1
          }
        }
      });
    },

    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        that.ui.$loader.hide();
        that.next('Thanks for your donation!');
      });
    },
    onCancel: function (data, actions) {
      that.ui.$loader.hide();
      that.render_validation_error('Looks like you\'re trying to cancel your donation. ' + 
        'If you changed your mind, please click \'No Thanks\' below. If not, please click a payment type below.', 'donate-confirmation');
    },
    onError: function (err) { 
      that.render_validation_error('OH NO! Something went wrong. Try clicking a payment type below again.', 'donate-confirmation');
    }

  }, this.props.modal_elt_selector + '-paypal');
}