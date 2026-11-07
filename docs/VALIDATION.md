# Validation scope

## November 7 replacement prerelease

The installed ZIP was tested on WordPress 7.0.2 / PHP 8.3.33 in a disposable local fixture. All seven bundled templates rendered in loop and single modes. Save guards, pre-existing malicious values, rating bounds, malformed widget inputs, cite normalization, aggregate averages, caller-post preservation, explicit false overrides and template symlink containment passed. Authenticated plugin/details screens, local stylesheet/font delivery and a clean PHP debug log also passed.

Both original Author stored-XSS payloads were saved through normal WordPress HTTP requests by an Author without unfiltered_html. Administrator browser visits produced no injected event handler or executed marker. A two-slide rotator initialized through the actual cycletwo.opts data key and completed a next-slide transition. Raw payload/log evidence is retained outside the public repository.

Full source PHPCS reports **0 errors and 112 warnings** with the existing ruleset. The remaining warnings concern legacy coercing comparisons and public API parameters. Security-only package PHPCS reports **0 errors and 0 warnings**. Package Plugin Check reports one GitHub Update URI policy error and 96 inherited naming/query/translation warnings. The Update URI is retained for this GitHub continuation; it is not permitted in a WordPress.org-hosted edition. None of these counts is a vulnerability total.

Local PHP lint, the dedicated PHPCompatibilityWP 7.4–8.3 scan, offline details contracts and the deterministic package unittest pass. The installed compatibility checker has incomplete PHP 8 coverage. The repository CI matrix separately checks PHP 7.4 and PHP 8.3 syntax and records its actual WordPress/PHP runtime image versions.

## Earlier GitHub fixture evidence

The preceding development baseline passed four Checks jobs and recorded WordPress 7.1.4 / PHP 8.3.36. This is historical fixture evidence, distinct from the latest local checks. Readme Tested up to uses 7.1 as the family-level declaration; it does not certify every patch release. The replacement commit must pass fresh CI before its release assets are replaced.

## Limits and remaining work

This evidence does not cover every shortcode combination, complete widget-administration workflows, all transition effects, arbitrary custom theme overrides, multisite, a populated original-to-Reborn database upgrade, every declared PHP runtime, or a production security review. Unlimited lists and aggregate metadata queries remain a performance concern. The user reported a successful live installation, but that report is not an independent production audit.

No automatic update service is bundled. Before release, consider running Codex Security in addition to these checks; it is not run automatically. The GitHub release remains a development prerelease with no commitment to regular future updates.
