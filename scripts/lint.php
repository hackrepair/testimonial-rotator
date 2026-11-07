<?php
// Syntax-check runtime and tooling PHP using the selected PHP executable.
$root     = dirname( __DIR__ );
$failures = 0;
$count    = 0;
foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ) ) as $file ) {
    $relative = substr( $file->getPathname(), strlen( $root ) + 1 );
    if ( preg_match( '~^(?:vendor|dist|\.git)/~', $relative ) || $file->getExtension() !== 'php' ) {
		continue;
    }
    // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_passthru -- CLI-only lint tool; executable and local file path are shell-escaped.
    passthru( escapeshellarg( PHP_BINARY ) . ' -l ' . escapeshellarg( $file->getPathname() ), $status );
    $failures += 0 !== $status ? 1 : 0;
    ++$count;
}
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI-only status text, never an HTTP response.
echo "Checked $count PHP files on PHP " . PHP_VERSION . "\n";
exit( $failures ? 1 : 0 );
