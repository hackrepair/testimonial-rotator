# Staging upgrade and rollback

Version 4.0 is a development candidate. Do not treat a successful activation as proof of compatibility or security.

1. Back up the database, original plugin directory, custom templates, widget settings, and relevant uploads.
2. Restore those into isolated staging with the site's theme/plugins and target PHP version.
3. Record testimonial/rotator counts, relationships, shortcode placement, and widget assignments before upgrading.
4. Build the ZIP, verify its checksum, and replace the existing files using WordPress's plugin upload replacement. Preserve `testimonial-rotator/` and its main filename. Do not uninstall.
5. Check existing posts, metadata, rotator ordering, widgets, every used shortcode, images, ratings, author markup, pagination, custom templates, and browser controls.
6. Check PHP logs under debug logging with display disabled. Check lower-privilege save permissions and missing/empty content paths.

If rollback is needed, deactivate Reborn and restore the prior plugin files. Restore the database backup if data/settings changed; restoring files alone does not undo database writes. Recheck the site under its previous PHP version if that original plugin cannot execute on PHP 8.3.

There is no automatic updater service in this edition. Installing a development ZIP does not enroll the site in future updates.
