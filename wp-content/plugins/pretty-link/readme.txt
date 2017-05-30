=== Pretty Link Lite ===
Contributors: supercleanse
Donate link: http://prettylinkpro.com
Tags: links, link, url, urls, affiliate, affiliates, pretty, marketing, redirect, forward, plugin, twitter, tweet, rewrite, shorturl, hoplink, hop, shortlink, short, shorten, click, clicks, track, tracking, tiny, tinyurl, budurl, shrinking, domain, shrink, mask, masking, cloak, cloaking, slug, slugs, admin, administration, stats, statistics, stat, statistic, email, ajax, javascript, ui, csv, download, page, post, pages, posts, shortcode, seo, automation, widget, widgets, dashboard
Requires at least: 4.7
Tested up to: 4.7.2
Stable tag: 2.0.9

Shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!

== Description ==

= Pretty Link Pro =

[Upgrade to Pretty Link Pro](http://prettylinkpro.com "Upgrade to Pretty Link Pro")

*Pretty Link Pro* is a **significant upgrade** to *Pretty Link Lite* that adds many tools and redirection types that will allow you to create pretty links automatically, cloak links, replace keywords thoughout your blog with pretty links and much more.  You can learn more about *Pretty Link Pro* here:

[About](http://prettylinkpro.com/about "About") | [Features](http://prettylinkpro.com/features "Features") | [Pricing](http://prettylinkpro.com/pricing "Pricing")

= Pretty Link Lite =

Pretty Link enables you to shorten links using your own domain name (as opposed to using tinyurl.com, bit.ly, or any other link shrinking service)! In addition to creating clean links, Pretty Link tracks each hit on your URL and provides a full, detailed report of where the hit came from, the browser, os and host. Pretty Link is a killer plugin for people who want to clean up their affiliate links, track clicks from emails, their links on Twitter to come from their own domain, or generally increase the reach of their website by spreading these links on forums or comments on other blogs.

= Link Examples =

This is a link setup using Pretty Link that redirects to the Pretty Link Homepage where you can find more info about this Plugin:

http://blairwilliams.com/pl

Here's a named Pretty Link (I used the slug 'aweber') that does a 307 redirect to my affiliate link for aweber.com:

http://blairwilliams.com/aweber

Here's a link that Pretty Link generated a random slug for (similar to what bit.ly or tinyurl would do):

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
* Enables you to send your Pretty Links via Email directly from your WordPress admin
* Select Temporary (302 or 307) or Permanent (301) redirection for your Pretty Links
* Cookie based system for tracking visitor activity across clicks
* Organize Links into Groups
* Create nofollow/noindex links
* Turn tracking on / off on each link
* Pretty Link Bookmarklet

== Installation ==

1. Upload 'pretty-link.zip' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Make sure you have changed your permalink Common Settings in Settings -> Permalinks away from "Default" to something else. I prefer using custom and then "/%year%/%month%/%postname%/" for the simplest possible URL slugs with the best performance.

== Changelog ==

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

= 1.6.5 =
* Fixed bug with some reports not showing
* Fixed twitter auto-posting issue
* Fixed scheduled posts not auto tweeting
* Upgraded code to work with PHP 5.4+
* Other minor bug fixes and code improvements

= 1.6.4 =
* i18n enhancements
* Small fix to auto tweeting in pro
* Fixed small IP address issue in hits
* Added help text
* Fixed small Google Analytics issue in pro
* Fixed issue with meta refresh / javascript redirect in pro
* Added titles to keywords in pro
* Added redirect header hooks in pro
* Security Fixes

= 1.6.3 =
* Security Fixes
* Removed Open Flash Charts in favor of Google Charts
* Fixed some javascript conflicts

= 1.6.2 =
* Additional Fixes

= 1.6.1 =
* Security Fixes
* Fixed some issues with Pretty Link running in WordPress Multisite
* Altered the way Keyword Replacements can be disabled on individual pages in Pro
* Fixed an issue with import feature in Pro
* Fixed an issue causing "ghost" update messages in Pro
* Updated browscap file for more accurate click tracking
* Fixed an issue that interfered with Pretty Link's option saving for some users

= 1.6.0 =
* *Fix* Fixed some potential security vulnerabilities

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

= 1.6.4 =
* This adds some security fixes. Everyone should upgrade.

= 1.6.3 =
* This adds some security fixes. Everyone should upgrade.

= 1.6.2 =
* Some additional fixes. Everyone should upgrade.

= 1.6.1 =
* This adds some security fixes. Everyone should upgrade.

= 1.6.0 =
* This adds some security fixes. Everyone should upgrade.
