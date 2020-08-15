/* ==========================================================
 * settings.js
 * ==========================================================
 * Copyright 2020 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
jQuery(document).ready(function ($) {

	// Initialize Select2.
	omapiSelect();

	// Hide/show any state specific settings.
	omapiToggleSettings();

	// Support Toggles on content
	omapiSettingsToggle();

	// Confirm resetting settings.
	omapiResetSettings();

	// Copy to clipboard Loading
	omapiClipboard();

	// Recognize Copy to Clipboard Buttons
	omapiCopytoClipboardBtn();

	// Support PDF generation
	omapiBuildSupportPDF();

	// Run Tooltip lib on any tooltips
	omapiFindTooltips();

	// Add "Connect to OptinMonster" functionality
	omapiHandleApiKeyButtons();

	omapiInitInstallButtons();
	omapiRemoveQueryVars();

	/**
	 * Add the listeners necessary for the connect to OptinMonster button
	 */
	function omapiHandleApiKeyButtons() {

		// Also initialize the "Click Here to enter an API Key" link
		$('#omapiShowApiKey').click(function (e) {
			e.preventDefault();
			$('#omapi-form-api .omapi-hidden').removeClass('omapi-hidden');
			$('#omapi-field-apikey').focus().select();
		});

		$('#omapiShowApiKeys').click(function (e) {
			e.preventDefault();
			$('#omapi-form-woocommerce .omapi-hidden').removeClass('omapi-hidden');
			$('.manually-connect-wc').hide();
			$('#omapi-field-consumer_key').focus().select();
		});

		// Add the listener for disconnecting the API Key.
		$('#omapiDisconnectButton').click(function (e) {
			e.preventDefault();
			OMAPI.updateForm('', $(this));
		});
	}

	/**
	 * Dynamic Toggle functionality
	 */
	function omapiSettingsToggle() {

		$('.omapi-ui-toggle-controller').click(function (e) {
			var thisToggle = e.currentTarget;
			$(thisToggle).toggleClass("toggled");
			$(thisToggle).next(".omapi-ui-toggle-content").toggleClass("visible");
		});

	}

	/**
	 * Confirms the settings reset for the active tab.
	 *
	 * @since 1.0.0
	 */
	function omapiResetSettings() {
		$(document).on('click', 'input[name=reset]', function (e) {
			return confirm(OMAPI.confirm);
		});
	}

	/**
	 * Toggles the shortcode list setting.
	 *
	 * @since 1.1.4
	 */
	function omapiToggleSettings() {
		var $automatic = $('#omapi-field-automatic');
		var $mpSetting = $('#omapi-field-mailpoet');
		var $mpPhone   = $('#omapi-field-mailpoet_use_phone');

		var toggleAutoSetting = function() {
			var method = $automatic.is(':checked') ? 'hide' : 'show';
			$('.omapi-field-box-automatic_shortcode')[method]();
		};

		var toggleMpPhoneSetting = function() {
			var method = $mpPhone.is(':checked') ? 'show' : 'hide';
			$('.omapi-field-box-mailpoet_phone_field')[method]();
		};

		var toggleMpSettings = function() {
			var show = $mpSetting.is(':checked');
			var method = show ? 'show' : 'hide';
			$('.omapi-field-box-mailpoet_list')[method]();
			$('.omapi-field-box-mailpoet_use_phone')[method]();
			$('.omapi-field-box-mailpoet_phone_field')[method]();

			if ( show ) {
				toggleMpPhoneSetting();
			}
		};

		toggleAutoSetting();
		toggleMpSettings();
		$(document).on('change', '#omapi-field-automatic', toggleAutoSetting);
		$(document).on('change', '#omapi-field-mailpoet', toggleMpSettings);
		$(document).on('change', '#omapi-field-mailpoet_use_phone', toggleMpPhoneSetting);
	}

	/**
	 * Initializes the Select2 replacement for select fields.
	 *
	 * @since 1.0.0
	 */
	function omapiSelect() {
		$('.omapi-select').each(function (i, el) {
			var data = $(this).attr('id').indexOf('taxonomies') > -1 ? OMAPI.tags : OMAPI.posts;
			$(this).select2({
				minimumInputLength: 1,
				multiple: true,
				data: data,
				initSelection: function (el, cb) {
					var ids = $(el).val();
					ids = ids.split(',');
					var items = data.filter(function(d) {
						return ids.indexOf(d.id) > -1;
					});
					cb(items);
				}
			}).on('change select2-removed', function () {});
		});
	}

	/**
	 * Generate support PDF from localized data
	 *
	 * @since 1.1.5
	 */
	function omapiBuildSupportPDF() {
		var $selector = $('#js--omapi-support-pdf');

		const generateDoc = data => {
			var doc = new jsPDF('p', 'mm', 'letter');

			// Doc Title
			doc.text(10, 10, 'OptinMonster Support Assistance');

			// Server Info
			var i = 10;
			$.each(data.server, function (key, value) {
				i += 10;
				doc.text(10, i, key + ' : ' + value);
			});

			// Optin Info
			$.each(data.campaigns, function (key, value) {

				// Move down 10mm
				var i = 10;
				// Add a new page
				doc.addPage();
				// Title as slug
				doc.text(10, 10, key);
				$.each(value, function (key, value) {

					// Keep from outputing ugly Object text
					var output = ( $.isPlainObject(value) ? '' : value );
					// new line
					i += 10;
					doc.text(10, i, key + ' : ' + output);
					// Output any object data from the value
					if ($.isPlainObject(value)) {
						$.each(value, function (key, value) {
							i += 10;
							doc.text(20, i, key + ' : ' + value);
						});
					}
				});

			});

			// Save the PDF
			doc.save('OMSupportHelp.pdf');
		}

		$selector.click(function (e) {
			e.preventDefault();

			// Start spinner.
			$('.om-api-key-spinner').remove();
			$selector.after('<div class="om-api-key-spinner spinner is-active" style="float: none;margin-top:7px;"></div>');

			$.ajax( {
				url: OMAPI.root + 'omapp/v1/support?format=pdf',
				beforeSend: xhr => xhr.setRequestHeader( 'X-WP-Nonce', OMAPI.nonce ),
				dataType: 'json',
				data: { format : 'pdf' },
				success: generateDoc,
			} )
			.done( () => $('.om-api-key-spinner').remove() )
			.fail( ( jqXHR, textStatus ) => console.error({ jqXHR, textStatus }) );

		});
	}

	/**
	 * Clipboard Helpers
	 *
	 * @since 1.1.5
	 */
	function omapiClipboard() {
		var ompaiClipboard = new Clipboard('.omapi-copy-button');

		ompaiClipboard.on('success', function (e) {
			setTooltip(e.trigger, 'Copied to Clipboard!');
			hideTooltip(e.trigger);
		});
		ompaiClipboard.on('error', function (e) {
			var fallbackMessage = '';

			if(/iPhone|iPad/i.test(navigator.userAgent)) {
				fallbackMessage = 'Unable to Copy on this device';
			}
			else if (/Mac/i.test(navigator.userAgent)) {
				fallbackMessage = 'Press ⌘-C to Copy';
			}
			else {
				fallbackMessage = 'Press Ctrl-C to Copy';
			}
			setTooltip(e.trigger, fallbackMessage);
			hideTooltip(e.trigger);
		});
	}

	/**
	 * Standardize Copy to clipboard button
	 *
	 * @since 1.1.5
	 */
	function omapiCopytoClipboardBtn() {
		$('omapi-copy-button').tooltip({
			trigger: 'click',
			placement: 'top',

		});
	}
	/**
	 * Set BS Tooltip based on Clipboard data
	 *
	 * @since 1.1.5
	 * @param btn
	 * @param message
	 */
	function setTooltip(btn, message) {
		$(btn).attr('data-original-title', message)
			.tooltip('show');
	}

	/**
	 * Remove tooltip after Clipboard message shown
	 *
	 * @since 1.1.5
	 * @param btn
	 */
	function hideTooltip(btn) {
		setTimeout(function() {
			$(btn).tooltip('destroy');
		}, 2000);
	}

	function omapiFindTooltips() {
		$('[data-toggle="tooltip"]').tooltip()
	}

	function omapiInitInstallButtons() {
		$('.install-plugin-form').submit((e) => {
			e.preventDefault();
			let fields          = $(e.currentTarget).serializeArray();
			let nonce           = fields.find((field) => 'nonce' === field.name).value;
			let plugin          = fields.find((field) => 'plugin' === field.name).value;
			let pluginClassName = plugin.replace('.', '').replace('/', '');
			let installAction   = fields.find((field) => 'action' === field.name).value;
			let url             = fields.find((field) => 'url' === field.name).value;
			let el              = $(`.omapi-plugin-recommendation--${pluginClassName}`);

			if (! el.length) {
				el = $('html')
			}

			$('.button-install', el).html('Installing...');
			$('.button-activate', el).html('Activating...');
			$('#om-plugin-alerts').hide();

			$.post(ajaxurl, {
				action: 'om_plugin_install',
				'optin-monster-ajax-route': true,
				nonce,
				plugin,
				installAction,
				url
			}, function (data) {
				if (data.success) {
					window.location.reload();
				} else {
					$('.button-install', el).html('Install Plugin');
					$('.button-activate', el).html('Activate Plugin');

					$('#om-plugin-alerts').show().html($( '<p/>' ).html( data.data || 'Something went wrong!' ));
				}
			});
		})
	}

	/**
	 * Helper to remove the OM query vars.
	 *
	 * @since  1.9.9
	 *
	 * @return {void}
	 */
	function omapiRemoveQueryVars() {
		if ( window.history.replaceState && window.location.search ) {
			function removeParam(parameter, url) {
				var urlparts = url.split('?');
				if ( urlparts.length < 2) {
					return url;
				}

				var prefix = encodeURIComponent(parameter) + '=';
				var pars   = urlparts[1].split(/[&;]/g);
				for ( var i = pars.length; i-- > 0;) {
					if (pars[i].lastIndexOf(prefix, 0) !== -1) {
						pars.splice(i, 1);
					}
				}

				return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
			}

			const toRemove = [
				'optin_monster_api_action_done',
				'optin_monster_api_action_type',
				'optin_monster_api_action_id',
			];
			let url = document.location.href;

			window.location.search.split('&').forEach( bit => {
				toRemove.forEach( key => {
					if ( 0 === bit.indexOf( key ) ) {
						url = removeParam( bit.split('=')[0], url );
					}
				} );
			});

			window.history.replaceState( null, null, url );
		}
	}

});
