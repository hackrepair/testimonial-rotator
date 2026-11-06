# Validation scope

## Recorded local evidence before repository setup
- WordPress 7.0.2 / PHP 8.3.33: seven bundled templates rendered fixture content; missing rotator handled; rotator save without nonce and unauthenticated save rejected; authorized save succeeded.
- All working PHP files linted under PHP 7.4 and PHP 8.3.
- Authenticated HTTP: original author plain text; no Visit plugin site link; one native View details link; six information tabs; local stylesheet; current compatibility fields.

Raw logs are intentionally excluded from the public repository. These observations describe fixtures, not a production deployment.

## Repository checks
- CI syntax matrix: PHP 7.4 and 8.3.
- Offline details tests: scoped API handling, readme tab generation, row-link deduplication, escaping, author/metadata consistency.
- Packaging tests: fixed plugin root, no development artifacts, byte-for-byte contents, checksum, and repeatable build output.
- Disposable WordPress integration: install the built ZIP, run shortcode/template/save-handler fixtures under E_ALL, and verify actual authenticated plugin/detail screens.

The WordPress integration workflow records the runtime image's actual version. It does not certify the declared 7.1.3 target unless that exact version was tested.

## Open work
Historical advisory mapping, full output-context review, all privilege boundaries, real browser rotation/navigation, complete widget administration, populated original-to-Reborn upgrade, custom templates, and actual-site integration remain open. Syntax success does not prove PHP 7.4 runtime support. Automated scanner counts are leads rather than vulnerability counts. No overall security sign-off is claimed.

## Repository setup verification — November 6, 2026
The repeatable isolated lab installed the exact newly built ZIP on WordPress 7.0.2 / PHP 8.3.33. Seven templates, missing rotator handling, nonce/capability save checks, the authenticated Plugins row, six details tabs, and an empty PHP debug log passed. The lab removed its own containers afterward. Offline details and deterministic packaging tests also passed. These are fixture checks; the open work above remains open.
