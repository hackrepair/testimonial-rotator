<?php
// Offline contract tests for the local plugin-information provider.
error_reporting(E_ALL);
set_error_handler(function ($level, $message, $file, $line) { throw new ErrorException($message, 0, $level, $file, $line); });
define('ABSPATH', __DIR__ . '/');
define('TESTIMONIAL_ROTATOR_DIR', dirname(__DIR__) . '/');
define('THRG_TR_FILE', TESTIMONIAL_ROTATOR_DIR . 'testimonial-rotator.php');
define('THRG_TR_VERSION', '4.0');
function plugin_basename($file) { return 'testimonial-rotator/' . basename($file); }
function add_query_arg($args, $url) { return $url . '?' . http_build_query($args); }
function admin_url($path) { return 'https://example.test/wp-admin/' . $path; }
function esc_html($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return esc_html($value); }
function esc_url($value) { return esc_attr($value); }
function __($value, $domain = '') { return $value; }
function esc_html__($value, $domain = '') { return esc_html($value); }
function wpautop($value) { return '<p>' . $value . '</p>'; }
function plugins_url($path, $file) { return 'https://example.test/wp-content/plugins/testimonial-rotator/' . $path; }
function wp_enqueue_style() {}
function check($condition, $message) { if (!$condition) throw new RuntimeException($message); }
require TESTIMONIAL_ROTATOR_DIR . 'includes/class-thrg-tr-details.php';
$sentinel = new stdClass();
check(THRG_TR_Details::info($sentinel, 'query_plugins', (object) array('slug' => 'testimonial-rotator')) === $sentinel, 'Other API actions must pass through');
check(THRG_TR_Details::info($sentinel, 'plugin_information', (object) array('slug' => 'another-plugin')) === $sentinel, 'Other plugins must pass through');
$info = THRG_TR_Details::info(false, 'plugin_information', (object) array('slug' => 'testimonial-rotator'));
check($info->name === 'Testimonial Rotator (Reborn)' && $info->version === '4.0', 'Identity mismatch');
foreach (array('description', 'history', 'installation', 'faq', 'changelog', 'support') as $section) check(!empty($info->sections[$section]), 'Missing tab ' . $section);
check(strpos($info->sections['description'], 'Future updates are unlikely.') !== false, 'Maintenance scope missing');
check(strpos(implode('', $info->sections), 'halgatewood.com') === false, 'Abandoned website link remains');
check(strpos(THRG_TR_Details::section('<script>alert(1)</script>'), '<script>') === false, 'Unsafe markup');
$unchanged = array('Version 4.0');
check(THRG_TR_Details::row_meta($unchanged, 'other/plugin.php') === $unchanged, 'Unrelated row changed');
$links = THRG_TR_Details::row_meta($unchanged, 'testimonial-rotator/testimonial-rotator.php');
check(count($links) === 2 && strpos($links[1], 'thickbox') !== false, 'Details link missing');
check(THRG_TR_Details::row_meta($links, 'testimonial-rotator/testimonial-rotator.php') === $links, 'Duplicate details link');
$header = file_get_contents(THRG_TR_FILE);
check(strpos($header, 'Author: Hal Gatewood (abandoned website)') !== false && strpos($header, 'Author URI:') === false, 'Original author must be plain text');
check(!preg_match('/^Plugin URI:/m', $header), 'Visit plugin site header must stay absent');
echo "PASS details contracts, readme content, escaping, and metadata\n";
