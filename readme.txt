=== Testimonial Rotator (Reborn) ===
Contributors: hackrepair
Tags: testimonials, testimonial rotator, reviews, widgets, shortcode
Requires at least: 6.9.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An abandoned WordPress favorite, brought back to life by Jim Walker, The Hack Repair Guy.

== Description ==

= Good testimonials deserve a future =

Your customers took the time to say something wonderful about your business. The plugin displaying those words should not be the reason you cannot move your website forward.

The original Testimonial Rotator was a useful, straightforward WordPress plugin. Then development stopped. Its website was abandoned years ago, its documentation links went stale, and site owners were left carrying an aging plugin into a changing WordPress and PHP world.

I am Jim Walker, **The Hack Repair Guy**. I am taking over maintenance of this unofficial continuation and bringing it back to life as **Testimonial Rotator (Reborn)**.

The goal is to keep what people liked about the original—simple testimonial management, flexible displays, and familiar shortcodes—while bringing the code forward for PHP 8.3 and current WordPress use.

= A new chapter, with respect for the original =

Hal Gatewood created the original Testimonial Rotator. That credit stays. The old website links do not: this edition includes its own plugin information and points to current maintenance resources.

Reborn is maintained by [Jim Walker, The Hack Repair Guy — HackRepair.com](https://hackrepair.com "WordPress security, hack repair, and ongoing maintenance"). It is an independent continuation, not a claim of endorsement or an official transfer from the original author.

= Keep the testimonials. Renew the foundation. =

* Create and organize testimonials in WordPress.
* Group testimonials into rotators and use them in multiple places.
* Display testimonials through widgets and familiar shortcodes.
* Choose from seven bundled display templates.
* Use ratings, author information, images, excerpts, and list displays.
* Keep the original content identifiers and shortcode aliases as maintenance work progresses.

= Maintenance scope and future updates =

**Future updates are unlikely.** Reborn is a limited security and PHP-version maintenance release intended to extend the useful life of an abandoned plugin. It is not a commitment to ongoing development, new features, or regular updates. Please plan accordingly when choosing software for a new site or maintaining an existing one.

= Where version 4.0 stands =

**Version 4.0 remains a development prerelease.** The November 7 replacement adds stored-XSS repairs, validated headings and template selection, contextual escaping, bounded ratings, hardened settings/widget inputs, local icon assets, and corrected aggregate-rating behavior. Existing post types, relationships, widget identity and shortcode aliases are preserved.

Compatibility declarations: WordPress 6.9.4 minimum, WordPress 7.1 family target, and PHP 7.4 minimum. Local installed-ZIP tests use WordPress 7.0.2 / PHP 8.3.33. Earlier GitHub fixture checks used WordPress 7.1.4 / PHP 8.3.36; the CI workflow records the actual runtime for each build. PHP 7.4 syntax checks do not establish complete runtime compatibility.

Targeted security review and regression tests covered both original Author stored-XSS paths in an administrator browser, all seven loop/single templates, request guards, malformed widget input, rating bounds, template containment and slider advancement. Full project PHPCS reports zero errors and 112 legacy warnings; security-only checks report zero errors/warnings. Populated-site upgrades, complete widget administration, arbitrary custom templates, multisite and broader production behavior remain outside that evidence.

== History ==

= The original =

Testimonial Rotator was created by Hal Gatewood to make customer testimonials easy to manage and display in WordPress. Over time it gained rotator controls, star ratings, author information, widgets, shortcodes, and additional templates. Version 3.0 brought the separate theme pack into the main plugin.

= The years in between =

The archived upstream changelog lists version **3.0.3**, dated June 15, 2020, as its final numbered release. SVN trunk still identifies itself as 3.0.3, with its last recorded change on March 30, 2021. The original website was subsequently abandoned, leaving site owners with broken links and no dependable maintenance path.

The [original WordPress.org listing](https://wordpress.org/plugins/testimonial-rotator/) records closure on February 25, 2021 for a security issue. Reborn does not erase that history or claim that renaming a plugin fixes its security. The closure and applicable code paths are part of the ongoing review.

= Reborn =

In November 2026, I recovered the archived source and began the 4.0 maintenance project after PHP 8.3 compatibility became a practical problem for a site using the plugin.

This continuation has a new name and a clear purpose: preserve useful testimonial content, repair aging code, and give the plugin a maintained future. **Testimonial Rotator (Reborn)** is that fresh start.

The project repository is [hackrepair/testimonial-rotator](https://github.com/hackrepair/testimonial-rotator). Installable development prereleases are available in the repository Releases section. This edition is an unofficial continuation; the original WordPress.org listing has not been reopened by this work.

== Installation ==

= Test the development build on staging =

1. Back up the database, existing plugin directory, settings, and any custom testimonial templates.
2. Use a staging copy of your website with PHP 8.3.
3. Replace the existing testimonial-rotator plugin files with the Reborn package. Keep the folder name testimonial-rotator.
4. Activate Testimonial Rotator (Reborn), then check your existing testimonials, rotators, widgets, shortcodes, and custom templates.
5. Review the PHP error log and test the display controls before considering a production upgrade.

Do not uninstall the existing plugin as an upgrade step. Preserve your backup so you can restore the previous files and database if necessary.

= Familiar shortcodes =

* `[testimonial_rotator id="123"]` displays a rotator.
* `[testimonial_rotator id="123" format="list"]` displays its testimonials as a list.
* `[testimonial_single id="456"]` displays an individual testimonial.
* `[testimonial_rotator_rating id="123"]` displays the aggregate rating.

Replace the example IDs with your own rotator or testimonial IDs. Existing hyphenated aliases remain available.

== Frequently Asked Questions ==

= Who maintains Reborn? =
Jim Walker, The Hack Repair Guy, is taking over maintenance of this independent continuation. Hal Gatewood remains credited as the original author.

= Why remove the old website links? =
The original website was abandoned years ago, and those links no longer provide dependable documentation or support. Reborn bundles this information locally and uses HackRepair.com for current maintenance contact.

= Will I have to re-enter my testimonials? =
Preserving existing testimonial posts, rotator relationships, metadata, widget identity, and shortcode names is a core project requirement. The plugin directory remains testimonial-rotator. Verify your actual data on staging; a complete populated-site upgrade test is still pending.

= Is 4.0 ready for a live website? =
The current build remains a prerelease for staging validation. Installed-ZIP PHP 8.3 tests and targeted security/browser regressions have passed. Broader upgrade, custom-template and multisite coverage remains open; see the repository validation guide.

= Should I expect more updates? =
Future updates are unlikely. This work is limited to security and PHP-version maintenance, with no promise of new features, regular releases, or continuing support. The development status and remaining validation work described above still apply.

= Is this an official update from the original developer? =
No. Reborn is an unofficial maintenance continuation. Original authorship and licensing are preserved.

= Does it require Genesis or Dynamik? =
The plugin is separate from those themes. The development project includes Genesis/Dynamik integration checks, but custom themes and testimonial templates still need site-specific testing.

== Changelog ==

= 4.0 — Reborn development =
* Renamed to Testimonial Rotator (Reborn).
* Began PHP 8.3 modernization under Jim Walker, The Hack Repair Guy.
* Added per-post nonce, capability, post-type, autosave, and revision checks to metadata saves.
* Improved missing-rotator and shortcode-input handling.
* Corrected widget limit conversion.
* Added a local WordPress View details dialog with description, history, installation, FAQ, changelog, and support tabs.
* Removed abandoned website links and retired the legacy theme-pack update request to that website.
* Preserved existing shortcode names, widget identity, post types, and metadata keys.
* November 7 replacement: repair stored-XSS output paths, confine templates, harden settings/widgets, clamp ratings, bundle icons locally, and correct aggregate ratings. Targeted security/browser regressions passed; populated upgrades and broader integration coverage remain open.

= Archived upstream milestones =
* 3.0.3 — June 15, 2020: original changelog reports security fixes.
* 3.0 — October 28, 2019: theme pack merged into the main plugin.
* 2.2.5 — September 27, 2017: original changelog reports replacing create_function for PHP compatibility.
* 2.0 — May 15, 2014: expanded rotator controls, ratings, author fields, and display options.

The unmodified upstream source and full original changelog are preserved separately in the development archive.

== Bundled assets ==

Font Awesome 4.7.0 is served locally with this plugin; no default third-party CDN request is required. Its fonts use SIL OFL 1.1 and its CSS uses MIT; license texts are included under assets/font-awesome. The Hide Font Awesome setting still lets the active theme supply icons. The former CDN-version filter no longer selects an external asset.

== Support ==

= A plugin with someone working on it again =

Maintenance contact: [Jim Walker, The Hack Repair Guy — HackRepair.com](https://hackrepair.com "WordPress security and hack repair").

Project repository: [hackrepair/testimonial-rotator](https://github.com/hackrepair/testimonial-rotator).

When reporting a problem, include the Reborn version, WordPress and PHP versions, theme, affected shortcode or widget, reproduction steps, and relevant PHP log entries. Remove passwords, tokens, and private customer information before sharing logs.
