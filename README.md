<p align="center"><img src="assets/brand/thrg-heart-256.png" width="128" alt="The Hack Repair Guy heart"></p>
<h1 align="center">Testimonial Rotator (Reborn)</h1>
<p align="center"><strong>Good testimonials deserve a future.</strong><br>An abandoned WordPress favorite, brought back to life by Jim Walker, The Hack Repair Guy.</p>
<p align="center"><a href="https://hackrepair.com/">HackRepair.com</a> · <a href="https://github.com/hackrepair/testimonial-rotator/discussions">Community</a> · <a href="https://github.com/hackrepair/testimonial-rotator/issues">Issues</a> · <a href="https://hackrepair.github.io/testimonial-rotator/">Project page</a></p>

<p align="center"><a href="https://github.com/hackrepair/testimonial-rotator/actions/workflows/ci.yml"><img src="https://github.com/hackrepair/testimonial-rotator/actions/workflows/ci.yml/badge.svg" alt="Repository checks"></a></p>

## Why Reborn exists

The original Testimonial Rotator helped WordPress owners collect customer testimonials and display them with simple widgets and shortcodes. Development stopped, the original website was abandoned years ago, and site owners were left with aging code and broken documentation links.

I am Jim Walker, **The Hack Repair Guy**. I am taking over maintenance of this independent continuation to bring it forward for PHP 8.3 while preserving existing testimonial content and familiar integrations. Hal Gatewood remains credited as the original author: **Hal Gatewood (abandoned website)**.

**Future updates are unlikely.** This is limited security and PHP-version maintenance, with no commitment to new features, regular releases, or ongoing support.

## Current status: 4.0 development

This repository contains a **development candidate**, not a production release. The historical WordPress.org listing was closed for a security issue. The targeted security review and Author attack regressions have completed for the repaired paths. Populated upgrades, arbitrary custom templates and multisite coverage remain open; the name Reborn is not a security certification.

Completed local checks include PHP 7.4/8.3 syntax checks, PHP 8.3 rendering of seven bundled templates, and focused metadata-save permission/nonce tests. Actual admin HTTP checks cover the native View details page and plugin metadata. Full browser interaction, lower-privilege output tracing, and populated-site upgrade validation remain open. See [validation scope](docs/VALIDATION.md) and [project status](PROJECT.md).

Do not confuse GitHub's **Code → Download ZIP** source archive with an installable plugin package. Build a staging ZIP using the command below; its installed directory is always `testimonial-rotator/`.

## What it preserves

- Testimonial and rotator post types, existing metadata, and testimonial-to-rotator relationships.
- Original widget identity and settings.
- Seven bundled display templates, images, author fields, ratings, excerpts, and list output.
- Existing shortcode names, aliases, and theme integration points.

```text
[testimonial_rotator id="123"]
[testimonial_rotator id="123" format="list"]
[testimonial_single id="456"]
[testimonial_rotator_rating id="123"]
```

Use your own post IDs. The hyphenated rotator and single-testimonial aliases remain available.

## Install on staging

1. Back up the existing database, plugin files, widget settings, and custom testimonial templates.
2. Build an installable ZIP: `python3 scripts/build-release.py`.
3. Upload `dist/testimonial-rotator-reborn.4.0-development.zip` through **Plugins → Add New → Upload Plugin**, replacing the existing plugin files.
4. Keep the folder `testimonial-rotator`; do not uninstall the old plugin as an upgrade step.
5. Check existing testimonials, relationships, widgets, shortcodes, templates, and PHP logs. Follow [upgrade and rollback](docs/UPGRADE.md).

## Compatibility declarations

| Field | Declaration |
|---|---|
| Minimum WordPress | 6.9.4 |
| WordPress compatibility target / readme Tested up to | 7.1 |
| Minimum PHP | 7.4 |
| Modernization runtime target | PHP 8.3 |

These declarations follow the Stickies plugin format. WordPress 7.1 is a family-level compatibility declaration. Recorded local runtime evidence uses WordPress 7.0.2 and PHP 8.3.33; GitHub CI also passed on WordPress 7.1.4 and PHP 8.3.36. These are fixture results, not a production-site compatibility guarantee.

## Develop and test

```sh
php scripts/lint.php
php tests/details.php
python3 -m unittest discover -s tests -p 'test_*.py'
python3 scripts/build-release.py
python3 scripts/test-wordpress.py
```

The final command requires Docker and network access. It starts a dedicated disposable WordPress/database stack, installs the actual built ZIP, and exercises the plugin. It removes its own containers and volumes afterward. See [development](docs/DEVELOPMENT.md).

[Contributing](CONTRIBUTING.md) · [Security reporting](SECURITY.md) · [Support scope](SUPPORT.md) · [Changelog](CHANGELOG.md) · [History and provenance](docs/HISTORY.md)

## Credits and license

Original plugin: Hal Gatewood and upstream contributors. Unofficial maintenance: [Jim Walker, The Hack Repair Guy](https://hackrepair.com/). GPL v2 or later; see [LICENSE](LICENSE). The shared THRG heart and details styling are reused from the maintainer's Stickies plugin. The bundled Cycle2 JavaScript retains its upstream copyright and license notices.

The old theme-pack updater endpoint has been retired. Reborn currently provides no automatic update service. The GitHub repository and local View details dialog do not imply WordPress.org approval or reopening.
