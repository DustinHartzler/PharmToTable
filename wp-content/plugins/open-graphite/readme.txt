=== Open Graphite ===
Plugin Name:        Open Graphite
Contributors:       mikeyott
Author URI:         https://rocketapps.com.au/
Plugin URI:         https://wordpress.org/plugins/open-graphite/
Tags:               open graph, social, facebook, twitter, thumbnail
Requires at least:  4.7
Tested up to:       6.2
Stable tag:         trunk
License:            GPLv3 or later
License URI:        https://www.gnu.org/licenses/gpl-3.0.html

Control how your content is viewed when shared on social media.

== Description ==

Open Graphite lets you control how your WordPress content is viewed when shared on social media.

Typically when one of your pages is shared on social media (Facebook for example), the platform will use the first image and text it can find on the page. This is usually not ideal and often truncates the content into something nonsensical, resulting in the page being less likely to be engaged with.

Open Graphite solves this problem, by allowing you to customise the image, title and description of any of your pages that are shared on social media, all without compromising the original content.

== Installation ==

1) Go to 'Add New' plugins in WordPress admin
2) Search for 'Open Graphite'
3) Click 'Install Now' and wait for it to finish installing
4) Click 'Activate'

== How to use ==

For the homepage...

* Go to Open Graphite (admin side menu)
* Set your homepage title, description, image and object type
* Enter a Facebook app ID (optional)
* Enter a Twitter username (optional)
* Choose a Twitter card type
* Select which post types to enable for

For posts and pages...

* Create a new post or page (or edit an existing one)
* Scroll down to the Open Graphite metabox
* Enter your desired title, description, image and object type

== Frequently Asked Questions ==

= Can I customise how each page or post is previewed when shared on social media? =

Yes. You can either set the option to automatically use the existing title, text (or excerpt) and image, or specify them manually on each page or post in the Open Graphite metabox.

= There appears to be duplicate sets of open graph tags =

Any other plug-in that inserts open graph tags into your website (Yoast is one that comes to mind) may conflict with Open Graphite to not work properly.

To test if you have a conflict, simply view the source code of your home page in your browser and search for any instances of og: within. Typically a plug-in will output the meta tags into it's own group. For this plug-in, they will be directly below the <code><!--/ Open Graphite /--></code> comment but other plug-ins will output something else (if at all). The only solution to resolve a conflict is to disable one of the plug-ins.

= Facebook isn't showing the correct information when I share my page or post =

Facebook (and possibly other social networks) will cache the open graph properties of a post that has previously been shared for up to 24 hours (maybe longer). During this time, any changes you make to your open graph properties will not be picked up by Facebook immediately. But there are a couple of solutions.

1) <a href="https://rocketapps.com.au/product/open-graphite-pro/" target="_blank">Upgrade to Pro</a> and get a Facebook Access Token, or...

2) Use the official <a href="https://developers.facebook.com/tools/debug/" target="_blank">Facebook Debugger</a>, paste in the URL of your page or post and hit the Debug button. When it has finished, hit the Scrape Again button. This will force Facebook to fetch the latest open graph data from your post. Twitter has a similar tool called the <a href="https://cards-dev.twitter.com/validator/" target="_blank">Card Validator</a> for this purpose as well.

Facebook might complain about the lack of an App ID when you pass your URL through their debugger, but this will not prevent anything from working correctly.

= Do I need a Facebook App ID? =

You don't need a Facebook App ID for this plugin to work. That said, there may be instances where having one is helpful. <a href="https://developers.facebook.com/docs/development/create-an-app" target="_blank">Decide for yourself</a>. But if you plan on using the Facebook Access Token feature (highly recommended) then you will absolutely need a Facebook App ID.

= Will my current settings be inherited if I upgrade to pro? =

Yes. If you <a href="https://rocketapps.com.au/product/open-graphite-pro/" target="_blank">Upgrade to Pro</a> all your current configurations will be preserved.

= Where can I get support for this plugin? =

Leave a message at the <a href="https://wordpress.org/support/plugin/open-graphite/" target="_blank">plug-in support page</a>.

== Screenshots ==

1. screenshot-1.png

== Premium Support ==

While the free version of Open Graphite is supported in the <a href="https://wordpress.org/support/plugin/open-graphite">WordPress support page</a>, one-on-one priority support is given to <a href="https://rocketapps.com.au/open-graphite-pro/?origin=open-graphite">Open Graphite Pro</a> license holders.

<strong><a href="https://rocketapps.com.au/open-graphite-pro/?origin=open-graphite">Open Graphite Pro</a> has additional features, such as:</strong>

* The ability to use with any custom post type
* Force Facebook to automatically and immediately update your title, description or featured image when you make edits
* Mobile and desktop previews for Facebook, Twitter and Linkedin
* WooCommerce product support
* Pinterest specific options
* Enhanced Slack sharing
* Enable automatic defaults for titles, descriptions, featured images and object types
* Open Graph content indicators
* Limit the number of characters for titles and descriptions (prevent your titles and descriptions getting truncated)
* Open graph check tool
* Additional open graph options
* Priority support

== Changelog ==

= 1.7.0 =

New setting: Specify which roles can access the Open Graphite interface.
Fix: Issue where meta box had transparent background.
Help: Updated help topics.
Tweak: Settings interface.

= 1.6.1 =

Fixed reported security issue with the help page.

= 1.6.0 =

General maintenance.

= 1.5.1 =

Fixed a couple of stray PHP warnings.

= 1.5.0 =

A few minor improvements.

= 1.4.3 =

Fixed PHP warnings.
Minor UX tweak.

= 1.4.2 =

Updated broken external links.

= 1.4.1 =

A few minor, non critical edits.

= 1.4.0 =

Admin UI tweaks.

= 1.3.3 =

Removed links to opengraphcheck.com (no longer exists).
Fixed function name error that occurred when Open Graphite Pro is activated.
Admin UI tweaks.

= 1.3.2 =

Fixed missing translation strings.
Fixed PHP error.
Replaced missing load_plugin_textdomain() function.
Minor admin UI tweaks.

= 1.3.1 =

Updated help topics with most relevant information and correct external links.
Minor admin UI tweak.

= 1.3 =

New option to integrate with Facebook API to force immediate Open Graph data updates when creating or editing.

= 1.2 =

Added Linkedin Post Inspector, iFramely and Open Graph Check debugging links.
Fixed broken Facebook and Twitter dubugging link URLs.
Removed colour from admin icon.
Language file tweaks.

= 1.1 =

Minor UI and language consistency tweaks.

= 1.0.10 =

Fixed issue where Jetpack conflict option was not being honoured.

= 1.0.9 =

Fixed issue where Woocommerce shop page was using product open graph data.
Removed Woocommerce post types from enabled post types list.

= 1.0.8 =

Added locale to open graph meta.

= 1.0.7 =

Added missing text domain function.

= 1.0.6 =

Added option to avoid Jetpack conflict.

= 1.0.5 =

Added troubleshooting option to potentially help solve issues with some themes.
Fixed issue where titles with quotes would not output.

= 1.0.4 =

Changed twitter image markup to twitter:image.

= 1.0.3 =

Removed required attribute from object type selector.
Moved position in nav.

= 1.0.2 =

Added resource links.

= 1.0.1 =

Minor presentation tweaks.

= 1.0 =

Initial release.