# Release procedure

Version 4.0 is currently a development candidate. Do not publish a stable release until the PROJECT.md acceptance work is complete. Future updates are unlikely; this is limited maintenance.

1. Review security findings and unresolved defects. Record exact WordPress/PHP versions and populated upgrade results.
2. Update the main header Version, THRG_TR_VERSION constant, readme Stable tag, changelog, and details tests together. Change compatibility declarations only with supporting evidence or explicitly documented targets.
3. Run all documented checks and wait for GitHub CI. Validate real browser interactions and the intended deployment site separately.
4. Run `python3 scripts/build-release.py`. Inspect the runtime-only ZIP, its checksum, root folder, and byte correspondence.
5. Install that exact ZIP on staging. Recheck plugin identity, previous data, settings, shortcodes, widget settings, details dialog, and error logs.
6. Prepare release notes distinguishing completed fixes from remaining limits. Development candidates must be marked as prereleases, never latest stable.
7. Only after release authorization, create a version tag at the tested commit and attach the verified installable ZIP plus SHA-256 file to a GitHub release. A tag or source archive alone is not the installable package.

No automatic updater service is bundled. A public repository, green CI, or a project page does not establish production deployment or a security guarantee.
