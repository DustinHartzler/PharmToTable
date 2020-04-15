=== Shortlinks by Pretty Links - Best WordPress Link Tracking Plugin ===
Contributors: supercleanse, cartpauj
Donate link: https://prettylinks.com
Tags: links, link, url, urls, affiliate, affiliates, pretty, marketing, redirect, redirection, forward, plugin, twitter, tweet, rewrite, shorturl, hoplink, hop, shortlink, short, shorten, shortening, click, clicks, track, tracking, tiny, tinyurl, budurl, shrinking, domain, shrink, mask, masking, cloak, cloaking, slug, slugs, admin, administration, stats, statistics, stat, statistic, email, ajax, javascript, ui, csv, download, page, post, pages, posts, shortcode, seo, automation, widget, widgets, dashboard
Requires at least: 5.1
Tested up to: 5.4
Stable tag: 3.1.0

Shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!

== Description ==

= Pretty Links Pro =

[Upgrade to Pretty Links Pro](https://prettylinks.com/why-upgrade/ "Upgrade to Pretty Links Pro")

*Pretty Links Pro* is a **significant upgrade** that adds many tools and redirection types that will allow you to create pretty links automatically, cloak links, replace keywords thoughout your blog with pretty links, categorize & tag your pretty links and much more.  You can learn more about *Pretty Links Pro* here:

[Learn More](https://prettylinks.com/about "Learn More") | [Pricing](https://prettylinks.com/pricing/plans/ "Pricing")

= Pretty Links =

Pretty Links enables you to shorten links using your own domain name (as opposed to using tinyurl.com, bit.ly, or any other link shrinking service)! In addition to creating clean links, Pretty Links tracks each hit on your URL and provides a full, detailed report of where the hit came from, the browser, os and host. Pretty Links is a killer plugin for people who want to clean up their affiliate links, track clicks from emails, their links on Twitter to come from their own domain, or generally increase the reach of their website by spreading these links on forums or comments on other blogs.

= Link Examples =

This is a link setup using Pretty Links that redirects to the Pretty Links Homepage where you can find more info about this Plugin:

http://blairwilliams.com/pl

Here's a named pretty link (I used the slug 'aweber') that does a 307 redirect to my affiliate link for aweber.com:

http://blairwilliams.com/aweber

Here's a link that Pretty Links generated a random slug for (similar to what bit.ly or tinyurl would do):

http://blairwilliams.com/w7a

= Features =

* Gives you the ability to create clean, simple URLs on your website that redirect to any other URL (allows for 301, 302, and 307 redirects only)
* Generates random 3-4 character slugs for your URL or allows you to name a custom slug for your URL
* Tracks the Number of Clicks per link
* Tracks the Number of Unique Clicks per link
* Provides a reporting interface where you can see a configurable chart of clicks per day. This report can be filtered by the specific link clicked, date range, and/or unique clicks.
* View click details including ip address, remote host, browser (including browser version), operating system, and referring site
* Download hit details in CSV format
* Intuitive Javascript / AJAX Admin User Interface
* Pass custom parameters to your scripts through pretty link and still have full tracking ability
* Exclude IP Addresses from Stats
* Enables you to send your pretty links via Email directly from your WordPress admin
* Select Temporary (302 or 307) or Permanent (301) redirection for your pretty links
* Cookie based system for tracking visitor activity across clicks
* Create nofollow/noindex links
* Turn tracking on / off on each link
* Pretty Links Bookmarklet

== Installation ==

1. Upload 'pretty-link.zip' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Make sure you have changed your permalink Common Settings in Settings -> Permalinks away from "Default" to something else. I prefer using custom and then "/%year%/%month%/%postname%/" for the simplest possible URL slugs with the best performance.

== Changelog ==
= 3.1.0 =
* Add support for sponsored rel tag
* Add sponsored toggle support in gutenberg and tinymce editors
* Fix report graphs not loading for some
* PRO: Fix bug with keywords replacements
* PRO: Cleaned up GA support

= 3.0.10 =
* Add support for nofollow in Gutenberg popup
* Fix post_author being set to 0 on CPT
* Fix for duplicate links being created
* Fix clicks ordering on links list
* Fix Redirection dropdown display WP 3.5

= 3.0.9 =
* Change shortcut key to CTRL + P instead of CTRL + K
* Support for Nofollow and Tracking Quick Edit and Bulk Edit on the links list page
* Fix keyword replacement regex

= 3.0.8 =
* PRO: Fix replacements checkbox getting unchecked when using page builders

= 3.0.7 =
* Fix broken Add New link page - theme conflicts

= 3.0.6 =
* Disable "enter" key on the Target URL field
* Add copy to clipboard to the add/edit link page
* Fix the incorrect message being display when updating a link
* Redirect to the links list page after creating or updating a link
* Add the ability to sort by Clicks to the links list table

= 3.0.5 =
* Fix description conflict with the WP Product Review plugin
* Avoid menu position conflicts
* Add Target column to links list table
* Disable auto-saving on links
* Fix links having Draft status on creation
* Fix not being able to Create/Edit links until saving options
* Fix Prettybar color issue when saving plugin options

= 3.0.4 =
* Fix clicks pagination

= 3.0.3 =
* Fix Avada Fusion Builder conflict
* Fix redirect caching on update/welcome page
* Fix conflict with WP Product Review plugin

= 3.0.2 =
* Minor fixes

= 3.0.1 =
* Fix issues when accessing the plugin as a non-administrator
* Simplify the checks in is_slug_available
* Add slug and url as searchable fields in the links list
* Fix for high CPU issues and huge log sizes
* Fix layout issue on the update page
* Fix category migration not running
* Fix errors when dates are null
* Call wp_cache_delete after update/delete_option
* Only redirect if delete succeeds, redirect status code 307
* Fix failed category/cpt migrations

= 3.0.0 =
* Re-designed Admin UI especially the Admin Links UI including links listing, adding new links and editing links 
* Added an Insert Pretty Link button for Gutenberg Paragraph Blocks
* Security fixes and hardening
* Fixed Slug is not Available Error
* Removed Pretty Links Groups (lite users will still be able to access legacy groups on the links listing page but pro users & lite users who upgrade to pro will have their groups migrated to become link categories)
* PRO ONLY: Added Pretty Link Categories
* PRO ONLY: Added Pretty Link Tags
* PRO ONLY: Fixed some issues with Pretty Link Imports and Exports
* Many other small fixes and enhancements

= 2.1.10 =
* More security hardening

= 2.1.9 =
* Fixed some security issues

= 2.1.8 =
* Fixed broken charts in dashboard widget
* Fixed duplicate social buttons
* Fixed warning of "invalid argument supplied to foreach"

= 2.1.7 =
* Changed clipboard library
* Fixed an issue with non-countable strings for PHP 7.2
* Fixed issue with large indexes utf8mb4 innodb
* Fixed issue with ampersands in Keywords
* Fixed issue with keywords being replaced
* Fixed issue with '+' sign not allowed in slugs
* Fixed self-closing tags for keyword replacement
* Fixed Arabic (UTF-8) characters

= 2.1.6 =
* Fixed a conflict with Mulit-site variable name

= 2.1.5 =
* Fixed Target URL Rotations
* Fixed domain mapping and home_url in Multi-site
* Fixed GeoRedirect for IPv6

= 2.1.4 =
* Fixed IP conflict with Flywheel
* Fixed wp_redirect race condition

= 2.1.3 =
* Addressed security vulnerabilities
* Fixed content-type header
* Fixed -1 PHP memory_limit error
* Updated redirects to use wp_redirect()
* PRO Enhanced WooCommerce keyword replacements
* PRO Fixed replacing keywords in header and other HTML tags
* PRO Fixed link expiration dates being 1 day off

= 2.1.2 =
* Updated user manual link
* Rebranded from Pretty Link to Pretty Links

= 2.1.1 =
* Redeploy to WP Repo

= 2.1.0 =
* Fixed bug breaking TinyMCE

= 2.0.9 =
* Fixed pixel redirect type tracking
* Fixed ordering for geo redirects
* Fixed URL validation
* Fixed split test reporting

= 2.0.8 =
* Fixed a regular expression warning
* Use PHP's url validation in utils

= 2.0.7 =
* Enhanced database performance
* Added code to automatically remove click data from the database that is no longer being used
* Fixed numerous bugs
* PRO Prevent keywords autolinking from creating circular links

= 2.0.6 =
* Fixed numerous bugs

= 2.0.4 =
* Fix URI params not sticking
* PRO Fix apostrophe in keywords

= 2.0.3 =
* *Important* performance fix
* PRO Fixed an issue with Google Analytics integration

= 2.0.2 =
* Fixed a small javascript issue
* Fixed a small issue with Keyword Replacements
* Fixed an issue with the pro automatic update code that was affecting lite users

= 2.0.1 =
* Fixed Link titles on the Pretty Link listing admin screen
* Fixed a small collation issue
* Added convenience links on the plugin listing admin screen

= 2.0.0 =
* Added an Insert Pretty Link editor popup to create and insert pretty links while editing a page, post or custom post type
* Added a base slug prefix feature so that new Pretty Links can be prefixed
* Added auto-trimming for clicks to keep click databases operating at full performance
* Refactored entire codebase
* Completely new UI
* Tools have been better separated out into it's own admin page
* Now fully translatable
* Fixed numerous bugs including "Slug Not Available" issue
* Numerous stability, security and performance fixes
* Removed banner advertisements on the Pretty Link list admin page for lite users
* PRO Added support for automatically created links on Custom Post Types
* PRO Added automatic link disclosures for keyword replacements
* PRO Added pretty link keyword replacement indexing for better performance
* PRO Added Geographic redirects
* PRO Added Technology-based redirects
* PRO Added Time-based redirects
* PRO Added Link Expirations
* PRO Enhanced Link Rotations to accept more target URLs
* PRO Enhanced Social Share buttons to look better and support modern social sites
* PRO Enhanced QR codes code that produces them quicker and at larger sizes
* PRO Added an auto url replacement blacklist to ensure some URLs won't ever be replaced
* PRO Added the ability to add custom head scripts to redirect types that support it (Javascript, Cloaked, Pretty Bar, Meta Refresh, etc)
* PRO Enhanced the reliability and amount of data that can be imported and exported
* PRO Changed auto update system to use a license key instead of username / password
* PRO Consolidated the "Pro" Options to appear on the main Pretty Link Options admin page
* PRO Removed Double Redirects
* PRO Removed the Twitter Badge option ... this is now handled better with the social share bar or through another plugin like Social Warfare
* PRO Removed the Auto-Tweet capability ... auto-tweeting is handled better on a service like Buffer or Hootsuite

== Upgrade Notice ==

= 2.0.6 =
* Important bug fixes, every user should upgrade.

= 2.0.3 =
* Important performance fix ... every user should upgrade.

= 2.0.2 =
* Fixed several bugs ... one of which could affect site performance so everyone should upgrade immediately.

= 2.0.1 =
* Fixed a few small issues. People should generally upgrade.

= 2.0.0 =
* This is a major new release. To take advantage of the stability, security and performance fixes ... as well as the new features.

