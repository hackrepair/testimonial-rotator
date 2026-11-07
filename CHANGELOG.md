# Changelog

## 4.0-rc.1 — refreshed November 7, 2026

Replacement of the existing development prerelease; plugin header remains 4.0.

### Standards and regression follow-up

- Resolve project standards errors while preserving legacy comparison behavior and public include paths.
- Correct a sole testimonial's aggregate rating and preserve caller post state during aggregation.
- Escape aggregate icon classes, honor explicit false shortcode overrides, and preserve unrelated title placeholders.
- Verify template symlink containment, original Author attack regressions and live slider advancement.

### Scanner hardening

- Escape admin form labels, widget fields, template tags, counters and rich-text output at their sinks.
- Normalize widget instances and malformed save inputs, preserve apostrophes in cite metadata, and return the original excerpt suffix for other post types.
- Add direct-access guards and bundle licensed Font Awesome 4.7.0 assets locally.
- Retire legacy updater hooks, correct text-domain/translation metadata and normalize the Tested up to header.

### Security repair

- Validate and escape rotator output, headings, labels and template markup, including existing stored values.
- Confine template resolution, clamp ratings and harden settings/widget inputs and forms.
- Add stored-XSS regression coverage; retain existing content and identifiers.

## 4.0 — development
- Continue the abandoned plugin as Testimonial Rotator (Reborn), preserving its installed identity.
- Add metadata-save nonce, permission, post-type, autosave, and revision checks.
- Normalize shortcode input; handle missing/wrong-type rotators; correct widget limit conversion.
- Add native local View details content and THRG branding, history, FAQ, and maintenance-scope notices.
- Remove broken original-site links, replace the donation row action with current support, and retire the legacy updater request.
- Add repository documentation, CI, disposable WordPress integration tests, and verified ZIP packaging.

This remains a development checkpoint. Targeted security/browser regressions passed; populated upgrades, arbitrary custom templates and broader integration remain open.

## 3.0.3 — June 15, 2020 (upstream)
The original changelog records security fixes. Trunk still identifies itself as 3.0.3; its last recorded SVN change was March 30, 2021. See docs/HISTORY.md.
