=== Storefront Visual Hook Guide ===

Contributors: archtkt
Tags: storefront, woocommerce, theme, filters, markup
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 5.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find Storefront and WooCommerce hooks (action hooks) quick and easily by seeing their actual locations inside your theme.

== Description ==

**Storefront theme required.**

**Please report any bug or layout break you find.**

Once installed this plugin adds a drop down menu to the frontend admin bar showing different options.

= With this plugin you can: =

1. Show Storefront Hooks: Select this option to see the Storefront theme's hooks in their actual locations.
2. Show Storefront Filters: Select this option to see the Storefront theme's filters list.
3. Show WooCommerce Hooks: Select this option to see the WooCommerce plugin's hooks in their actual locations on your current theme. WooCommerce plugin must be active for you to see this option.
4. Display functions hooked in Storefront and WooCommerce action hooks.
5. Display remove_action() hints for hooked functions in Storefront and WooCommerce action hooks.

This plugin is built to work with the Storefront Theme.

= Roadmap =

1. Include Storefront Markup to help identifying HTML containers.
2. Include WooCommerce Markup to help identifying HTML containers.

**Feel free to ask for new features.**

== Installation ==

**Install via the WordPress Dashboard:**

1. Login to your WordPress Dashboard.
2. Navigate to Plugins, and select "Add new" to go to the "Add Plugins" page.
3. In the right side, enter "Storefront Visual Hook Guide" in the search input bar, and hit your enter key.
4. Click install, and wait for the plugin to download. Once done, activate the plugin.

**Install via FTP:**

1. Extract the zip file, login using your ftp client, and upload the entire `storefront-visual-guide` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Will this plugin work for themes other than Storefront? =
Unfortunately, no. This plugin was designed to work only with the Storefront theme, using Storefront theme's action hooks and filters. Activating the plugin while using a different theme will trigger a warning.

= Will this plugin work with Storefront child themes? =
Yes, this plugin will work perfectly with any Storefront child theme.

= I've activated the plugin, where can I access the settings? =
This plugin doesn't have a settings page. Just go to the frontend and you'll see the menu in the admin bar.

== Screenshots ==

1. Storefront Visual Hook Guide Menu Closeup.
2. Active Storefront theme's Action Hooks in Front Page.
3. Active WooCommerce plugin's Action Hooks in Single Product Page.
4. Funtions hooked in Front Page hooks and remove_action hint.
5. Listed filters in homepage with their description.

== Changelog ==

= 2.0.1 =
* Fixed problem with Template name in Uppercase

= 2.0 =
* Added display Storefront filters lists.
* Display `storefront_menu_toggle_text` filter in the handheld menu button.
* Display `storefront_copyright_text` filter in footer.

= 1.2.1 =
* Fixed `action-hooks-box` default sizes to prevent overlapping.
* Changed button html tags with span tags to prevent WooCommerce JS malfunction.

= 1.2 =
* Added hooked functions display button.
* Display functions hooked in Storefront action hooks.
* Display functions hooked in WooCommerce action hooks.
* Added `remove_action` display button.
* Added `remove_action` adaptation to classes.

= 1.1 =
* OOP Code Optimization.
* Avoid non logged in users using the query strings.
* Added notice when plugin is disabled due to theme change.
* WooCommerce conditional menu.
* Clear conditional menus.

= 1.0.2 =
* Added internationalization.
* Modified artwork.

= 1.0.1 =
* Fixed "Requires at least" version.

= 1.0.0 =
* Initial release
* Added: Storefront Hooks detector.
* Added: WooCommerce Hooks detector.
