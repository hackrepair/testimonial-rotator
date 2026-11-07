<?php
/** Shared validation for stored and request display values. */
if ( ! defined( 'ABSPATH' ) ) {
	exit; }
function thrg_tr_value( $key, $value ) {
    $value = is_scalar( $value ) ? (string) $value : '';
    switch ( $key ) {
        case 'title_heading':
            return in_array( $value, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ), true ) ? $value : 'h2';
        case 'fx':
            return preg_match( '/^[A-Za-z][A-Za-z0-9_]*$/D', $value ) && in_array( $value, testimonial_rotator_base_transitions(), true ) ? $value : 'fade';
        case 'template':
            return preg_match( '/^[a-zA-Z0-9_-]+$/D', $value ) && array_key_exists( $value, testimonial_rotator_available_themes() ) ? $value : 'default';
        case 'format':
            return 'list' === $value ? 'list' : 'rotator';
        case 'img_size':
            return in_array( $value, get_intermediate_image_sizes(), true ) || 'full' === $value ? $value : 'thumbnail';
        case 'rating':
            return max( 0, min( 5, (int) $value ) );
        case 'timeout':
		case 'speed':
		case 'excerpt_length':
            return max( 0, (int) $value );
        case 'limit':
            return max( -1, (int) $value );
        case 'extra_classes':
            return implode( ' ', array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', $value ) ) ) );
        case 'auto_height':
            return preg_match( '/^(calc|container|false|[0-9]+|[1-9][0-9]*:[1-9][0-9]*)$/D', $value ) ? $value : 'calc';
        case 'div_selector':
            return '' !== $value && ! preg_match( '/[<>\x00-\x1f]/', $value ) ? $value : '> div.slide';
        default:
            return sanitize_text_field( $value );
    }
}
function thrg_tr_template( $name, $prefix, $theme_files ) {
    $name       = thrg_tr_value( 'template', $name );
    $roots      = array( get_stylesheet_directory(), get_template_directory() );
    $candidates = array();
    foreach ( $roots as $root ) {
		foreach ( $theme_files as $file ) {
			$candidates[] = array( $root, $root . '/' . $file ); }
	}
    if ( ! in_array( $name, array( 'default', 'longform' ), true ) ) {
        foreach ( array( dirname( TESTIMONIAL_ROTATOR_DIR ) . '/testimonial-rotator-themes/templates', dirname( TESTIMONIAL_ROTATOR_DIR ) . '/testimonial-rotator-' . $name . '/templates' ) as $root ) {
            $candidates[] = array( $root, $root . ( strpos( $root, '-themes/' ) !== false ? '/' . $name : '' ) . '/' . $prefix . '-testimonial.php' );
        }
    }
    foreach ( array_unique( array( $name, 'default' ) ) as $slug ) {
		$candidates[] = array( TESTIMONIAL_ROTATOR_DIR . 'templates', TESTIMONIAL_ROTATOR_DIR . 'templates/' . $slug . '/' . $prefix . '-testimonial.php' ); }
    foreach ( $candidates as $candidate ) {
        $root = realpath( $candidate[0] );
		$file = realpath( $candidate[1] );
        if ( $root && $file && strpos( $file, $root . DIRECTORY_SEPARATOR ) === 0 && is_file( $file ) && is_readable( $file ) ) {
			return $file; }
    }
    return false;
}
function thrg_tr_css( $value ) {
	return is_string( $value ) ? str_replace( array( '<', '>' ), '', $value ) : ''; }

/** Normalize known widget fields; empty overrides retain the rotator defaults. */
function thrg_tr_widget_instance( $instance ) {
    $instance = is_array( $instance ) ? $instance : array();
    $defaults = array(
		'title'                     => '',
		'rotator_id'                => '',
		'format'                    => 'rotator',
		'show_size'                 => 'excerpt',
		'limit'                     => 5,
		'excerpt_length'            => '',
		'override_rotator_settings' => 0,
	);
    $result   = array();
    foreach ( array_merge( $defaults, array_fill_keys( array( 'template', 'fx', 'img_size', 'timeout', 'speed', 'title_heading', 'shuffle', 'verticalalign', 'prev_next', 'itemreviewed', 'show_link', 'link_text', 'hidefeaturedimage', 'hide_microdata', 'hide_title', 'hide_stars', 'hide_body', 'hide_author' ), '' ) ) as $key => $default ) {
        $value = isset( $instance[ $key ] ) && is_scalar( $instance[ $key ] ) ? $instance[ $key ] : $default;
        if ( 'rotator_id' === $key ) {
			$value = '' === $value ? '' : absint( $value ); } elseif ( 'show_size' === $key ) {
			$value = 'full' === $value ? 'full' : 'excerpt'; } elseif ( in_array( $key, array( 'override_rotator_settings', 'shuffle', 'verticalalign', 'prev_next', 'show_link', 'hidefeaturedimage', 'hide_microdata', 'hide_title', 'hide_stars', 'hide_body', 'hide_author' ), true ) ) {
				$value = '' === $value ? '' : ( in_array( $value, array( 1, '1', true, 'true' ), true ) ? 1 : 0 ); } elseif ( '' !== $value ) {
				$value = thrg_tr_value( $key, $value ); }
				$result[ $key ] = $value;
    }
    return $result;
}
