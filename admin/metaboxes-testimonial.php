<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* MAIN TESTIMONIAL META BOX (SELECT BOX OF ROTATORS) */
function testimonial_rotator_metabox_select() {
	global $post;
	wp_nonce_field( 'testimonial_rotator_save_' . $post->ID, 'testimonial_rotator_nonce' );
	$rotator_ids = testimonial_rotator_break_piped_string( get_post_meta( $post->ID, '_rotator_id', true ) );

	$rating = get_post_meta( $post->ID, '_rating', true );
	$cite   = get_post_meta( $post->ID, '_cite', true );

	$rotators = get_posts(
        array(
			'post_type'   => 'testimonial_rotator',
			'numberposts' => -1,
			'orderby'     => 'title',
			'order'       => 'ASC',
        )
    );
	?>

	<?php if ( ! count( $rotators ) ) { ?>
		<p style="color: red;">
			<b><?php esc_html_e( 'No Testimonial Rotators have been created.', 'testimonial-rotator' ); ?></b><br />
			<?php esc_html_e( "You need to publish this testimonial so you don't lose your work and then go create a Testimonial Rotator. You can then edit this testimonial and select the rotator here.", 'testimonial-rotator' ); ?>
		</p>
	<?php } else { ?>
		<p>
		<?php esc_html_e( 'Attach to Rotator: ', 'testimonial-rotator' ); ?> &nbsp;

		<?php foreach ( $rotators as $rotator ) { ?>
			<input id="testimonial_rotator_id_checkbox_<?php echo esc_attr( $rotator->ID ); ?>" type="checkbox" name="rotator_id[]" <?php echo in_array( (int) $rotator->ID, array_map( 'absint', $rotator_ids ), true ) ? ' CHECKED' : ''; ?> value="<?php echo esc_attr( $rotator->ID ); ?>"/> <label for="testimonial_rotator_id_checkbox_<?php echo esc_attr( $rotator->ID ); ?>"><?php echo esc_html( $rotator->post_title ); ?></label> &nbsp; &nbsp;
		<?php } ?>
		</p>
	<?php } ?>

	<div style="padding: 10px 0; margin: 10px 0; border-top: solid 1px #ccc; border-bottom: solid 1px #ccc;">
		<label for="stars"><?php esc_html_e( 'Star Rating:', 'testimonial-rotator' ); ?></label> &nbsp;
		<input id="testimonial_rotator_star_1" type="radio" name="rating" value="1"
        <?php
        if ( 1 == $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_1"> 1 </label>&nbsp;
		<input id="testimonial_rotator_star_2" type="radio" name="rating" value="2"
        <?php
        if ( 2 == $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_2"> 2 </label>&nbsp;
		<input id="testimonial_rotator_star_3" type="radio" name="rating" value="3"
        <?php
        if ( 3 == $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_3"> 3 </label>&nbsp;
		<input id="testimonial_rotator_star_4" type="radio" name="rating" value="4"
        <?php
        if ( 4 == $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_4"> 4 </label>&nbsp;
		<input id="testimonial_rotator_star_5" type="radio" name="rating" value="5"
        <?php
        if ( 5 == $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_5"> 5 </label>&nbsp;
		<input id="testimonial_rotator_star_0" type="radio" name="rating" value="0"
        <?php
        if ( ! $rating ) {
			echo " checked='checked'";}
		?>
        /><label for="testimonial_rotator_star_0"> <?php echo esc_html__( "Don't Show", 'testimonial-rotator' ); ?></label>
	</div>

	<p>
		<label for="cite"><?php esc_html_e( 'Author Information', 'testimonial-rotator' ); ?></label><br>
		<small><?php esc_html_e( "Company Name, Credentials, etc. (this info shows below the author's testimonial by default)", 'testimonial-rotator' ); ?></small>
	</p>

	<?php

	wp_editor(
        $cite,
        'testimonial-rotator-cite',
        array(
			'tinymce'       => array( 'theme_advanced_buttons1' => 'bold,italic,link,unlink' ),
			'textarea_name' => 'cite',
			'media_buttons' => false,
			'textarea_rows' => 3,
			'quicktags'     => false,
			'teeny'         => true,

        )
    );
}


function testimonial_rotator_mce_buttons( $buttons, $editor_id ) {
	if ( 'testimonial-rotator-cite' == $editor_id ) {
		return array( 'bold', 'italic', 'link', 'unlink' );
	}
	return $buttons;
}
add_filter( 'teeny_mce_buttons', 'testimonial_rotator_mce_buttons', 10, 2 );


/* SAVE TESTIMONIAL META DATA */
function testimonial_rotator_save_testimonial_meta( $post_id, $post ) {
	$post = get_post( $post_id );
    if ( ! $post || 'testimonial' !== $post->post_type || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( ! isset( $_POST['testimonial_rotator_nonce'] ) || ! is_string( $_POST['testimonial_rotator_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['testimonial_rotator_nonce'] ) ), 'testimonial_rotator_save_' . $post_id ) ) {
        return;
    }
	if ( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == 'testimonial' ) {
		// SAVE
		if ( isset( $_POST['rotator_id'] ) ) {
			if ( is_array( $_POST['rotator_id'] ) ) {
                $rotator_ids = array();
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each scalar member is converted to absint below, after the object/nonce guards.
                foreach ( wp_unslash( $_POST['rotator_id'] ) as $rotator_id ) {
                    if ( is_scalar( $rotator_id ) ) {
						$rotator_ids[] = absint( $rotator_id ); }
                }
                update_post_meta( $post_id, '_rotator_id', testimonial_rotator_make_piped_string( $rotator_ids ) );
			} else {
				$rotator_id = is_scalar( $_POST['rotator_id'] ) ? absint( wp_unslash( $_POST['rotator_id'] ) ) : 0;
				update_post_meta( $post_id, '_rotator_id', $rotator_id );
			}
		} else {
			update_post_meta( $post_id, '_rotator_id', '' );
		}

		if ( isset( $_POST['rating'] ) ) {
			$rating = thrg_tr_value( 'rating', sanitize_text_field( wp_unslash( $_POST['rating'] ) ) );
			update_post_meta( $post_id, '_rating', $rating );
		}

		if ( isset( $_POST['cite'] ) ) {
			update_post_meta( $post_id, '_cite', wp_kses( is_string( $_POST['cite'] ) ? wp_unslash( $_POST['cite'] ) : '', wp_kses_allowed_html() ) );
		}
	}
}
