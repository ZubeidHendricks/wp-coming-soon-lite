=== Coming Soon & Maintenance Mode Lite ===
Contributors: zubeidhendricks
Tags: coming soon, maintenance mode, under construction, landing page
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Show a clean coming-soon or maintenance page to visitors while you build. Admins still see the live site.

== Description ==

Flip one switch and visitors see a polished, fast, branded splash page while
you keep working behind the scenes. Logged-in admins always see the real site,
and a toolbar warning reminds you it's on.

Choose **Coming Soon** (HTTP 200, good before launch) or **Maintenance**
(HTTP 503 + Retry-After, correct for SEO during temporary downtime).

**Free features**

* One-click on/off.
* Custom headline, message and background colour.
* Coming-soon (200) or maintenance (503) mode.
* Admins bypass automatically; login page never blocked.

**Pro features**

* Logo and full branding.
* Email capture / launch list.
* Priority support.

== Installation ==

1. Install via Plugins → Add New, or upload the zip.
2. Activate.
3. Go to Settings → Coming Soon, customise, and tick "Enable".

== Frequently Asked Questions ==

= Will Google see a maintenance page? =

In Maintenance mode the page returns HTTP 503 with Retry-After, which tells
search engines the downtime is temporary. Coming Soon mode returns 200.

= Can I still access wp-admin? =

Yes. Admins and the login page are never blocked.

== Changelog ==

= 1.0.0 =
* Initial release.
