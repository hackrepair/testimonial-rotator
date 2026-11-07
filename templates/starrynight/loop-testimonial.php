<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; }


$title_heading = thrg_tr_value( 'title_heading', $title_heading );
$rating        = thrg_tr_value( 'rating', $rating );
$cite          = wp_kses_post( $cite );
// WRAPPER
$hclass = $show_microdata ? 'hreview itemreviewed' : '';
echo '<div class="slide slide' . esc_attr( $slide_count ) . ' testimonial_rotator_slide ' . esc_attr( $hclass ) . ' item ' . esc_attr( $has_image ) . " cf-tr\">\n";

// RATING
if ( $rating && $show_stars ) {
	echo "<div class=\"testimonial_rotator_stars cf-tr\">\n";
	for ( $r = 1; $r <= $rating; $r++ ) {
		echo '	<span class="testimonial_rotator_star testimonial_rotator_star_' . esc_attr( $r ) . '"><i class="fa ' . esc_attr( $testimonial_rotator_star ) . '"></i></span>';
	}
	echo "</div>\n";
}

// IF SHOW TITLE
if ( $show_title ) {
	echo '<' . esc_attr( $title_heading ) . ' class="testimonial_rotator_slide_title">';
	if ( $show_link ) {
		echo '<a href="' . esc_url( get_permalink() ) . '">';
    }
	echo esc_html( get_the_title() );
	if ( $show_link ) {
		echo '</a>';
    }
	echo '</' . esc_attr( $title_heading ) . ">\n";
}

// DESCRIPTION
echo "<div class=\"text testimonial_rotator_description\">\n";

// CONTENT
if ( $show_body ) {
	echo "<div class=\"testimonial_rotator_quote\">\n";
	if ( ( 'full' == $show_size ) ) {
		echo do_shortcode( nl2br( wp_kses_post( get_the_content( ' ' ) ) ) );
	} else {
		testimonial_rotator_excerpt( $excerpt_length, $link_text ); }
	echo "</div>\n";
}

// AUTHOR INFO
if ( $cite && $show_author ) {
	if ( $has_image && $show_image ) {
		echo '	<div class="testimonial_rotator_img img">' . get_the_post_thumbnail( get_the_ID(), $img_size ) . "</div>\n";
    }
	echo "<div class=\"testimonial_rotator_author_info cf-tr\">\n";
	echo wp_kses_post( wpautop( $cite ) );
	echo "</div>\n";
}

echo "</div>\n";

// MICRODATA
if ( $show_microdata ) {
	if ( ! $itemreviewed ) {
		$itemreviewed = get_bloginfo( 'name' );
    }

	echo "	<div class=\"testimonial_rotator_microdata\">\n";
	if ( $itemreviewed ) {
		echo "\t<div class=\"item\"><div class=\"fn\">" . esc_html( $itemreviewed ) . "</div></div>\n";
	}
	if ( $rating ) {
		echo "\t<div class=\"rating\">" . esc_attr( $rating ) . "</div>\n";
	}

		echo '	<div class="dtreviewed"> ' . esc_html( get_the_date( 'c' ) ) . '</div>';
		echo '	<div class="reviewer"> ';
			echo '	<div class="fn"> ' . wp_kses_post( wpautop( $cite ) ) . '</div>';
	if ( has_post_thumbnail() ) {
		echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'photo' ) ); }
		echo '	</div>';
		echo '<div class="summary">';
        testimonial_rotator_excerpt( apply_filters( 'testimonial_rotator_microdata_summary_length', 300 ) );
        echo '</div>';
		echo '	<div class="permalink"> ' . esc_url( get_permalink() ) . '</div>';
	echo "	</div><!-- .testimonial_rotator_microdata -->\n";
}

echo "</div>\n";
