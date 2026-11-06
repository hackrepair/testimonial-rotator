# Development

The repository root is the plugin source. Its installed basename stays `testimonial-rotator/testimonial-rotator.php`; a GitHub source archive is not an installation package.

## Tools
PHP 7.4 or later, Python 3.9 or later, curl, and Docker Engine/Compose v2 for the isolated runtime lab. The PHP 8.3 image is the modernization test runtime. No production credentials are needed.

## Checks
```
php scripts/lint.php
php tests/details.php
python3 -m unittest discover -s tests -p 'test_*.py'
python3 scripts/build-release.py
python3 scripts/test-wordpress.py
```

The WordPress script creates a uniquely named Compose project, disposable database, random loopback-only HTTP port, and synthetic credentials. It downloads WP-CLI from the official builds repository, installs the generated ZIP, runs fixtures, requests the native admin details page, and removes its containers/volumes in a finally block. The WordPress image is not pinned to a particular WordPress version; the test prints its actual version. Use a preloaded site clone separately for real upgrade verification.

Offline details tests use small WordPress API fixtures. They do not replace the real WordPress suite. The Python packaging test builds twice and verifies identical bytes, runtime-only membership, installed paths, file bytes, permissions, and checksums.

## Standards review
With the existing WPCS/PHPCompatibility tools available, run `phpcs --standard=phpcs.xml.dist`. Inherited code currently produces many leads; this is advisory and is not silently treated as a passing security audit. The manual Advisory standards review workflow records findings separately from the gating CI suite.

## CI and packaging
Pushes and pull requests run lint/details on PHP 7.4 and 8.3, deterministic packaging tests, and the isolated WordPress/PHP 8.3 suite. CI uploads a development artifact; it does not create tags or public releases.

The builder explicitly includes runtime directories and root runtime files, sets fixed ZIP timestamps and 0644 file permissions, and verifies file contents. Docs, tests, Git data, local logs, and scripts stay out. Header/readme/details versions must agree. See RELEASE.md before publishing anything stable.
