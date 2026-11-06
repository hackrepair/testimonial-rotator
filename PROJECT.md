# Testimonial Rotator (Reborn) 4.0

## Objective
Maintain the archived Testimonial Rotator code for security and PHP-version compatibility while preserving existing data and integration identifiers. Future updates are unlikely; this is not an ongoing feature-development commitment.

## Source and repository
- Repository: https://github.com/hackrepair/testimonial-rotator
- Original baseline: upstream 3.0.3; SVN export revision 3788813, trunk last changed at 2506179.
- Installed basename: `testimonial-rotator/testimonial-rotator.php`.
- Working version: 4.0 development; no stable release is approved by this file.

## Completed development milestones
- Reborn name, original-author plain-text credit, maintenance-scope notice, current support links.
- Metadata-save request checks; missing-rotator handling; scalar shortcode normalization; widget limit cast correction.
- Native readme-driven View details dialog using THRG heart branding; obsolete site links and legacy update endpoint retired.
- Recorded local PHP 8.3 template/save checks and PHP 7.4/8.3 lint checks.
- Repository docs, policies, CI, isolated WordPress tests, repeatable packaging, and public project-page source.

## Required before production release
- [ ] Reproduce the actual site's original failure against its installed plugin version.
- [ ] Complete security advisory research and contextual output/authorization review.
- [ ] Exercise all shortcode variants and widget saves through real browser workflows.
- [ ] Verify rotation, previous/next controls, responsive layout, and custom template overrides.
- [ ] Upgrade a populated original installation and verify data/settings relationships survive.
- [ ] Validate the declared WordPress 7.1.3 target independently.
- [ ] Complete site-specific staging tests and review PHP logs.
- [ ] Review the final ZIP, version references, checksum, and release notes.

CI checks cover a subset of these requirements; green CI is not release approval. Keep raw logs and site-specific evidence outside the public repository. Documentation must distinguish declarations, local observations, CI results, and actual production deployment.
