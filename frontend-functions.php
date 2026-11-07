<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// SINGLE TESTIMONIAL
function testimonial_rotator_single( $content ) {
	global $post;

	// SINGLE TESTIMONIAL
	if ( is_single() && get_post_type() == 'testimonial' ) {
		$testimonial_id = isset( $testimonial_id ) ? $testimonial_id : $post->ID;

		// IF NO ROTATOR ID IS FOUND, GRAB THE FIRST ROTATOR ASSOCIATED
		if ( ! isset( $rotator_id ) ) {
			$rotator_id  = get_post_meta( $testimonial_id, '_rotator_id', true );
			$rotator_ids = (array) testimonial_rotator_break_piped_string( $rotator_id );
			$rotator_id  = reset( $rotator_ids );
		}

		$itemreviewed  = get_post_meta( $rotator_id, '_itemreviewed', true );
		$img_size      = thrg_tr_value( 'img_size', get_post_meta( $rotator_id, '_img_size', true ) );
		$cite          = get_post_meta( $testimonial_id, '_cite', true );
		$has_image     = has_post_thumbnail() ? 'has-image' : false;
		$template_name = thrg_tr_value( 'template', get_post_meta( $rotator_id, '_template', true ) );

		$global_rating = 0;

		$rating        = (int) get_post_meta( $testimonial_id, '_rating', true );
		$title_heading = thrg_tr_value( 'title_heading', get_post_meta( $rotator_id, '_title_heading', true ) );

		// SANITIZE
		if ( ! trim( $template_name ) ) {
			$template_name = 'default';
        }
		if ( ! trim( $title_heading ) ) {
			$title_heading = apply_filters( 'testimonial_rotator_title_heading', 'h2', $template_name, $rotator_id );
        }

		$is_single = true;
		if ( ! $template_name ) {
			$template_name = apply_filters( 'testimonial_rotator_single_page_theme', 'default', $post->ID );
        }

		// SET THE STARS FONT ICON
		$testimonial_rotator_star = apply_filters( 'testimonial_rotator_star', 'fa-star', $template_name, $rotator_id );

        $template_name = thrg_tr_value( 'template', $template_name );
        $title_heading = thrg_tr_value( 'title_heading', $title_heading );
        $rating        = thrg_tr_value( 'rating', $rating );
        $template      = thrg_tr_template( $template_name, 'single', array( 'content-testimonial.php' ) );
		if ( $template ) {
			// NO CUSTOM TEMPLATE, MODIFY THE CONTENT
			ob_start();

			$show_title     = apply_filters( 'testimonial_rotator_single_show_title', true, $template_name, $testimonial_id, $rotator_id );
			$show_image     = apply_filters( 'testimonial_rotator_single_show_image', true, $template_name, $testimonial_id, $rotator_id );
			$show_body      = true;
			$show_stars     = true;
			$show_author    = true;
			$show_microdata = true;

			if ( $template ) {
				echo '<div class="testimonial_rotator template-' . esc_attr( $template_name ) . '">';
				include $template;
				echo '</div>';
			}

			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			/* translators: %s: Selected template name. */
			return testimonial_rotator_error( sprintf( esc_html__( 'The template: %s could be found', 'testimonial-rotator' ), $template_name ) );
		}
	}

	return $content;
}

if ( ! is_admin() ) {
	add_filter( 'the_content', 'testimonial_rotator_single' );
}



// READ MORE, WHEN EXCERPT IT USED
function testimonial_rotator_excerpt_more( $more ) {
	if ( get_post_type() == 'testimonial' ) {
		return ' <a href="' . esc_url( get_permalink() ) . '" class="testimonial-rotator-view-more">' . esc_html( apply_filters( 'testimonia_rotator_view_more', __( 'View Full', 'testimonial-rotator' ) ) ) . ' &rarr;</a>';
	}
    return $more;
}
add_filter( 'excerpt_more', 'testimonial_rotator_excerpt_more' );



// ERROR HANDLING
function testimonial_rotator_error( $msg ) {
	$error_handling = get_option( 'testimonial-rotator-error-handling' );
	if ( ! $error_handling ) {
		$error_handling = 'source';
    }
	if ( ! $msg ) {
		$msg = esc_html__( 'Something unknown went wrong', 'testimonial-rotator' );
    }

	if ( 'display-admin' == $error_handling ) {
		// DISPLAY ADMIN
		if ( current_user_can( 'manage_options' ) ) {
			echo "<div class='testimonial-rotator-error'>" . esc_html( $msg ) . '</div>';
		}
	} elseif ( 'display-all' == $error_handling ) {
		// DISPLAY ALL
		echo "<div class='testimonial-rotator-error'>" . esc_html( $msg ) . '</div>';
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Legacy trusted PHP filter receives an inert comment, not raw author content.
		echo apply_filters( 'testimonial_rotator_error', '<!-- TESTIMONIAL ROTATOR ERROR: ' . str_replace( '--', '- -', esc_html( $msg ) ) . ' -->' );
	}
}
