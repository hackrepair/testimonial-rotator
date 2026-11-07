<?php
/** Inert compatibility shim for legacy theme-pack integrations. */
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

class Testimonial_Rotator_Theme_Updater {
    public $theme_slug;
    public $version;
    public $basename;

    public function __construct( $slug, $version, $basename ) {
        $this->theme_slug = $slug;
        $this->version    = $version;
        $this->basename   = $basename;
        // The abandoned service is retired; no hooks or remote requests are registered.
    }

    public function check_update( $transient ) {
        return $transient;
    }

    public function check_info( $result, $action, $args ) {
        return $result;
    }
}
