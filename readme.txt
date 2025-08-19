=== Emanda – Featured Image in RSS ===
Contributors: iurimoura
Tags: rss, feed, featured-image, media-rss, enclosure
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds the post’s featured image to the default WordPress RSS feed. Optional Media RSS/enclosure, fallbacks, and emoji cleanup.

== Description ==
This plugin ensures each RSS item has a consistent image:

* Prepends the post **featured image** to the feed content (optional).
* Adds **Media RSS** (`<media:content>`) and/or **`<enclosure>`** (optional).
* **Removes emojis** in feeds to avoid emoji images.
* **Fallbacks**: first image found in the content or a **custom default URL**.
* Choose the **image size** (thumbnail, medium, large, full, etc.).

**No new feed URL is created.** It augments the default WordPress RSS feed (`/feed/`).

== Installation ==
1. Upload the ZIP via *Plugins → Add New → Upload Plugin*.
2. Activate the plugin.
3. Go to *Settings → RSS – Featured Image* and configure options.

== Frequently Asked Questions ==
= Does it change the feed URL? =
No. It works on the default WordPress feed.

= Do I need to clear caches? =
If you use a cache plugin or CDN, clear the cache after activation or option changes.

= Is it theme-dependent? =
No, it works regardless of the active theme.

== Screenshots ==
1. Settings screen with image options and feed tag toggles.

== Changelog ==
= 1.1.2 =
* English readme and headers; metadata updates.

= 1.1.1 =
* Removed `load_plugin_textdomain()` (not needed on WordPress.org).
* Escaping improvements for HTML/RSS output.
* Ensured `Domain Path: /languages` with language files present.

= 1.1.0 =
* Option to strip `<img class="wp-smiley">` from feed HTML.
* i18n and sanitization improvements.

= 1.0.0 =
* Initial public release.
