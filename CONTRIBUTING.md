# Contributing

This is a limited maintenance continuation. Future updates are unlikely, and contributions may not receive ongoing review. Keep proposals focused on security, PHP/WordPress compatibility, and preservation of existing behavior.

Before editing, read README.md, PROJECT.md, docs/DEVELOPMENT.md, and docs/VALIDATION.md. Reproduce the issue on fixture data. Preserve the installed folder, shortcode names, widget ID, post types, and metadata keys. Avoid silent data resets or unrelated redesigns.

Use four spaces for new code and the existing WordPress text domain `testimonial-rotator`. Validate inputs at boundaries, enforce object-level capabilities and nonces where appropriate, and escape output for its context. Keep upstream library notices intact.

Run the documented checks. Add a focused regression test for a meaningful defect; describe the trigger, before/after result, test environment, and remaining limits in the pull request. Update CHANGELOG.md for user-visible changes. Never commit credentials, database dumps, site logs, or customer content.

Report suspected vulnerabilities privately according to SECURITY.md, not in a public issue or pull request.
