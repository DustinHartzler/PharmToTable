<div class="row text-left">
  <div class="small-12 columns">
    <?php echo ks_giveaways_get_description() ?>
  </div>
</div>

<div data-ng-controller="signupForm">
  <!-- Contest Question -->
  <div class="row text-center contest-question" data-ng-hide="!KSGlobals.enableQuestion || entryForm.qualifyAnswer === 'right'">
    <div class="small-12 columns">
      <h4><span class="step">1</span> <?php _e('Answer correctly to qualify', KS_GIVEAWAYS_TEXT_DOMAIN); ?></h4>
    </div>
    <div class="small-1 columns">&nbsp;</div>
    <div class="small-10 columns">
      <?php echo ks_giveaways_question() ?>
      <span class="form-error ng-cloak" data-ng-class="{'is-visible' : entryForm.qualifyAnswer === 'wrong'}">
        <?php _e('Incorrect answer, try again!', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
      </span>
    </div>
    <div class="small-1 columns">&nbsp;</div>
  </div>
  <!-- End Contest Question -->

  <!-- Contest Entry -->
  <div class="row text-center contest-entry ng-cloak" data-ng-show="!KSGlobals.enableQuestion || entryForm.qualifyAnswer === 'right'">
    <div class="small-12 columns">
      <h4>
        <span class="step" data-ng-show="KSGlobals.enableQuestion">2</span> 
        <?php if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)): ?>
          <?php _e('Enter your details', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
        <?php else: ?>
          <?php _e('Enter your email address', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
        <?php endif; ?>
      </h4>
    </div>
    <div class="small-12 columns">
      <form id="giveaways_form" name="giveaways_form" method="post" ng-submit="giveaways_form.$valid && submit()" novalidate>
        <?php if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)): ?>
          <input type="hidden" name="first_name_field_active" id="first_name_field_active" value="true" />
        <?php else: ?>
          <input type="hidden" name="first_name_field_active" id="first_name_field_active" value="false" />
        <?php endif; ?>
        <div data-ng-if="KSGlobals.askName == 1">
          <div class="input-group">
            <span class="input-group-label" style="min-width:115px;"><?php _e('First Name', KS_GIVEAWAYS_TEXT_DOMAIN); ?></span>
            <input type="text" required name="giveaways_first_name" id="giveaways_first_name" value="<?php echo esc_attr(ks_giveaways_cookie_first_name()) ?>" class="input-group-field" data-ng-class="{'is-invalid-input' : giveaways_form.$submitted && giveaways_form.giveaways_first_name.$invalid}" data-ng-model="entryForm.name" data-ng-change="validate()" />
          </div>
          <span class="form-error" data-ng-class="{'is-visible': giveaways_form.$submitted && giveaways_form.giveaways_first_name.$invalid}">
            <?php _e('Please enter your first name', KS_GIVEAWAYS_TEXT_DOMAIN); ?>.
          </span>
          </div>
          <div class="input-group">
            <span class="input-group-label" style="min-width:115px;"><?php _e('Email', KS_GIVEAWAYS_TEXT_DOMAIN); ?></span>
            <input type="email" required name="giveaways_email" id="giveaways_email" value="<?php echo esc_attr(ks_giveaways_cookie_email()) ?>" class="input-group-field" data-ng-class="{'is-invalid-input' : giveaways_form.$submitted && giveaways_form.giveaways_email.$invalid}" data-ng-model="entryForm.email" />
          </div>
        <span class="form-error" data-ng-class="{'is-visible': giveaways_form.$submitted && giveaways_form.giveaways_email.$invalid}">
          <?php _e('An email address is required', KS_GIVEAWAYS_TEXT_DOMAIN); ?>.
        </span>
        <div class="row">
          <div class="small-12 columns text-center">
            <div id="giveaways_email_hint" style="display:none">
              <p><?php _e('Did you mean', KS_GIVEAWAYS_TEXT_DOMAIN); ?> <a href="javascript:void(0)"></a>?</p>
            </div>
          </div>
        </div>
        <?php if (get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY)): ?>
          <div class="row collapse" style="margin-bottom:12px;">
            <div class="small-1 columns">&nbsp;</div>
            <div class="small-10 columns text-center">
              <div vc-recaptcha key="'<?php echo get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) ?>'" data-ng-model="entryForm.recaptchaResponse" on-create="alignRecaptcha()"></div>
            </div>
            <div class="small-1 columns">&nbsp;</div>
          </div>
        <?php endif ?>
        <div class="row">
          <div class="small-12 columns text-center">
            <p class="form-error" data-ng-class="{'is-visible':giveaways_form.$submitted && giveaways_form.$error.hasOwnProperty('recaptcha')}">Please complete reCaptcha above.</p>
            <button type="submit" class="button large radius" style="padding:19px 36px;" data-ng-disabled="entryForm.submitting">
              {{entryForm.submitting ? '<?php _e('Please Wait', KS_GIVEAWAYS_TEXT_DOMAIN); ?>' : '<?php _e('Enter', KS_GIVEAWAYS_TEXT_DOMAIN); ?>'}}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- End Contest Entry -->
</div>