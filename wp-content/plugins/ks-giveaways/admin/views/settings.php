<div class="wrap">
  <h2><?php _e('KingSumo Giveaways'); ?></h2>

  <h2 class="nav-tab-wrapper">
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=general') ?>" class="nav-tab<?php $active_tab == 'general' and print ' nav-tab-active' ?>">General</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=email') ?>" class="nav-tab<?php $active_tab == 'email' and print ' nav-tab-active' ?>">Emails</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=settings') ?>" class="nav-tab<?php $active_tab == 'settings' and print ' nav-tab-active' ?>">Settings</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=services') ?>" class="nav-tab<?php $active_tab == 'services' and print ' nav-tab-active' ?>">Services</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=advanced') ?>" class="nav-tab<?php $active_tab == 'advanced' and print ' nav-tab-active' ?>">Advanced</a>
  </h2>

  <form method="post" action="options.php">
    <input type="hidden" name="tab" value="<?php echo $active_tab ?>" />

    <?php settings_fields('ks_giveaways_options'); ?>
    <?php do_settings_sections('ks-giveaways-options') ?>

    <?php submit_button(); ?>
  </form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		/*
		$('input[name="ks_giveaways_license_key"]').on('keyup', function () {
			$('#ks-license-container').hide();
		});
		*/

		$('#ks-license-container').on('click', '.ks-giveaways-activate', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$('#ks-license-container button').prop('disabled', 'disabled');

			$.post(
				ajaxurl,
				{
					action: 'ks_activate_giveaways_license',
					license: $('input[name="ks_giveaways_license_key"]').val()
				},
				function (response) {
					$('#ks-license-container').html(response);

					if (response.indexOf('ks-giveaways-deactivate') > -1) {
						$('input[name="ks_giveaways_license_key"]').prop('readonly', 'readonly');
					}
				}
			);
		});

		$('#ks-license-container').on('click', '.ks-giveaways-deactivate', function (e) {
			e.preventDefault();
			e.stopPropagation();

			if (!confirm('Are you sure you want to deactivate your license key?')) {
				return;
			}

			$('#ks-license-container button').prop('disabled', 'disabled');

			$.post(
				ajaxurl,
				{
					action: 'ks_deactivate_giveaways_license'
				},
				function (response) {
					$('#ks-license-container').html(response);
					$('input[name="ks_giveaways_license_key"]').prop('readonly', null);
				}
			);
		});

		$('#ks-giveaways-test-services-subscription-button').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			var input = $('#ks-giveaways-test-services-subscription-input');

			if(input.val() !== "" && input.val().match(/^.+@.+\.+.+$/)) {
				input.prop('disabled', 'disabled');
				$('#ks-giveaways-test-services-subscription-button').prop('disabled', 'disabled');

				$('#ks-giveaways-test-services-subscription-status').text("Testing email address...");

				$.post(
					ajaxurl,
					{
						action: 'ks_giveaways_test_services_subscription',
						"ks-giveaways-test-services-subscription-email-address": input.val()
					},
					function (response) {
						$('#ks-giveaways-test-services-subscription-container').html(response);
						$('#ks-giveaways-test-services-subscription-input').prop('disabled', false);
						$('#ks-giveaways-test-services-subscription-button').prop('disabled', false);
					}
				);
			} else {
				alert("Please enter a valid email address.");
			}
		});

    var $testEl = $('h3:contains(Test Integration Configuration)');
    var $testFormEl = $testEl.next('table.form-table');
    var $submitEl = $testFormEl.next('p.submit');

    $testEl.before($submitEl);
		$testEl.wrap('<div style="background-color:#f9f9f9;padding:10px 20px;border:1px solid #ccc;"></div>');
		$testEl.after($testFormEl);
		$testEl.css({'text-align':'center'});
	});
</script>