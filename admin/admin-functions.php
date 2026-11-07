<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* ADDS META BOXES TO THE ADD NEW TESTIMONIAL PAGE */
function testimonial_rotator_create_metaboxes() {
	// TESTIMONIAL OPTIONS
	add_meta_box( 'testimonial_rotator_metabox_select', esc_html__( 'Testimonial Options', 'testimonial-rotator' ), 'testimonial_rotator_metabox_select', 'testimonial', 'normal', 'default' );

	// TESTIMONIAL IMAGE
	add_meta_box( 'postimagediv', esc_html__( 'Testimonial Image', 'testimonial-rotator' ), 'post_thumbnail_meta_box', 'testimonial', 'normal', 'default' );

	// TESTIMONIAL ORDER
	add_meta_box( 'pageparentdiv', esc_html__( 'Testimonial Order', 'testimonial-rotator' ), 'page_attributes_meta_box', 'testimonial', 'normal', 'default' );

	// IF ON EDIT SHOW THE SHORTCODE
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only editor screen selection; no write.
	if ( isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) {
		// ASSOCIATED TESTIMONIALS
		add_meta_box( 'testimonial_rotator_testimonial_count_meta', esc_html__( 'Associated Testimonials', 'testimonial-rotator' ), 'testimonial_rotator_testimonial_count_meta', 'testimonial_rotator', 'side', 'default' );

		// SHORTCODE HELPERS
		add_meta_box( 'testimonial_rotator_shortcode_metabox', esc_html__( 'Rotator Shortcode', 'testimonial-rotator' ), 'testimonial_rotator_shortcode_metabox', 'testimonial_rotator', 'side', 'default' );
	}

	// ROTATOR OPTIONS
	add_meta_box( 'testimonial_rotator_metabox_effects', esc_html__( 'Rotator Options', 'testimonial-rotator' ), 'testimonial_rotator_metabox_effects', 'testimonial_rotator', 'normal', 'default' );
}


/* TITLE INPUT FOR TESTIMONIALS */
function register_testimonial_form_title( $title ) {
     $screen = get_current_screen();
	if ( $screen && 'testimonial' === $screen->post_type ) {
		 return esc_html__( 'Enter Highlight Here', 'testimonial-rotator' );
	}
    return $title;
}


/* ADMIN COLUMNS IN LIST VIEW */
function testimonial_rotator_columns( $columns ) {
	$columns = array(
		'cb'          => '<input type="checkbox" />',
		'image'       => esc_html__( 'Image', 'testimonial-rotator' ),
		'title'       => esc_html__( 'Title', 'testimonial-rotator' ),
		'rating'      => esc_html__( 'Rating', 'testimonial-rotator' ),
		'ID'          => esc_html__( 'Rotator', 'testimonial-rotator' ),
		'order'       => esc_html__( 'Order', 'testimonial-rotator' ),
		'author_info' => esc_html__( 'Author Information', 'testimonial-rotator' ),
		'shortcode'   => esc_html__( 'Shortcode', 'testimonial-rotator' ),
	);

	return $columns;
}

function testimonial_rotator_column_sort( $columns ) {
	$columns = array(
		'ID'    => esc_html__( 'Rotator', 'testimonial-rotator' ),
		'title' => 'title',
		'order' => 'menu_order',
	);

	return $columns;
}


function testimonial_rotator_add_columns( $column, $post_id ) {
	$edit_link   = get_edit_post_link( $post_id );
	$rotator_ids = testimonial_rotator_break_piped_string( get_post_meta( $post_id, '_rotator_id', true ) );

	$rotator_title_array = array();
	foreach ( $rotator_ids as $rotator_id ) {
		$rotator_title_array[] = "<a href='post.php?action=edit&post=" . absint( $rotator_id ) . "'>" . esc_html( get_the_title( $rotator_id ) ) . '</a>'; }

	$this_testimonial = get_post( $post_id );

	if ( 'ID' == $column ) {
		echo wp_kses_post( implode( ', ', $rotator_title_array ) );
	} elseif ( 'image' == $column ) {
		echo '<a href="' . esc_url( $edit_link ) . '">' . get_the_post_thumbnail( $post_id, array( 50, 50 ) ) . '</a>';
	} elseif ( 'order' == $column ) {
		echo '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $this_testimonial->menu_order ) . '</a>';
	} elseif ( 'rating' == $column ) {
		echo esc_html( thrg_tr_value( 'rating', get_post_meta( $post_id, '_rating', true ) ) );
	} elseif ( 'author_info' == $column ) {
		echo wp_kses_post( get_post_meta( $post_id, '_cite', true ) );
	} elseif ( 'shortcode' == $column ) {
		echo '<b>' . esc_html__( 'Display as Single', 'testimonial-rotator' ) . '</b><br />';
		echo '[testimonial_single id="' . absint( $post_id ) . '"]';
	}
}


function testimonial_rotator_rotator_columns( $columns ) {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => esc_html__( 'Title', 'testimonial-rotator' ),
		'theme'     => esc_html__( 'Theme', 'testimonial-rotator' ),
		'count'     => esc_html__( 'Testimonial Count', 'testimonial-rotator' ),
		'aggregate' => esc_html__( 'Aggregate Rating', 'testimonial-rotator' ),
		'shortcode' => esc_html__( 'Shortcodes', 'testimonial-rotator' ),
	);

	return $columns;
}

function testimonial_rotator_rotator_add_columns( $column, $post_id ) {
	if ( 'shortcode' == $column ) {
		echo '
			<b>' . esc_html__( 'Use Rotator Settings', 'testimonial-rotator' ) . '</b><br />
			[testimonial_rotator id=' . absint( $post_id ) . ']<br /><br />

			<b>' . esc_html__( 'Display as List', 'testimonial-rotator' ) . '</b><br />
			[testimonial_rotator id=' . absint( $post_id ) . ' format=list]
		';
	} elseif ( 'theme' == $column ) {
		$theme = get_post_meta( $post_id, '_template', true );
		if ( ! $theme ) {
			$theme = 'default';
        }
		echo esc_html( ucwords( thrg_tr_value( 'template', $theme ) ) );
	} elseif ( 'aggregate' == $column ) {
		echo esc_html( testimonial_rotator_rating( $post_id, 'rating' ) );
	} elseif ( 'count' == $column ) {
		$meta_query  = array(
			'relation' => 'OR',
			array(
				'key'   => '_rotator_id',
				'value' => $post_id,
			),
			array(
				'key'     => '_rotator_id',
				'value'   => '|' . absint( $post_id ) . '|',
				'compare' => 'LIKE',
			),
		);
		$args        = array(
			'posts_per_page' => -1,
			'post_type'      => 'testimonial',
			'meta_query'     => $meta_query,
		);
		$count_query = new WP_Query( $args );

		if ( ! $count_query->found_posts ) {
			echo esc_html__( 'None assigned yet', 'testimonial-rotator' );
		} else {
			echo '<a href="edit.php?post_type=testimonial&rotator_id=' . absint( $post_id ) . '">' . number_format( $count_query->found_posts ) . '</a>';
		}
		wp_reset_postdata();
	}
}


/* PARSE TESTIMONIALS BY ROTATOR ID */
function testimonial_rotator_parse_testimonials_by_rotator_id( $query ) {
	global $pagenow;

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin list filtering.
	if ( 'edit.php' == $pagenow && isset( $query->query['post_type'] ) && 'testimonial' == $query->query['post_type'] && isset( $_GET['rotator_id'] ) ) {
		// GET TESTIMONIALS ONLY FOR THIS ROTATOR
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list filter, bounded to an integer.
        $id                              = absint( $_GET['rotator_id'] );
		$query->query_vars['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'   => '_rotator_id',
				'value' => $id,
			),
			array(
				'key'     => '_rotator_id',
				'value'   => '|' . $id . '|',
				'compare' => 'LIKE',
			),
		);
	}
}


/* ROTATOR SUBMENU PAGE */
function register_testimonial_rotator_submenu_page() {
	global $current_user;

	// ABILITY TO EDIT ROTATORS FOR ADMINS
	add_submenu_page( 'edit.php?post_type=testimonial', esc_html__( 'Add Rotator', 'testimonial-rotator' ), esc_html__( 'Add Rotator', 'testimonial-rotator' ), 'manage_options', 'post-new.php?post_type=testimonial_rotator' );

	// SETTINGS PAGE
	add_submenu_page( 'edit.php?post_type=testimonial', esc_html__( 'Settings', 'testimonial-rotator' ), esc_html__( 'Settings', 'testimonial-rotator' ), 'manage_options', 'testimonial-rotator', 'testimonial_rotator_settings_callback' );

	if ( ! current_user_can( 'manage_options' ) ) {
		$current_user_roles = (array) $current_user->roles;

		// ADD THE EDIT ROTATOR PAGE FOR OTHER ROLES THAT ARE SELECTED IN SETTINGS
		$creator_setting = (array) get_option( 'testimonial-rotator-creator-role' );

		if ( $creator_setting && $current_user_roles ) {

			foreach ( $current_user_roles as $role ) {
				if ( in_array( $role, $creator_setting, true ) ) {
					add_submenu_page( 'edit.php?post_type=testimonial', esc_html__( 'Rotators', 'testimonial-rotator' ), esc_html__( 'Rotators', 'testimonial-rotator' ), $role, 'edit.php?post_type=testimonial_rotator' );
					add_submenu_page( 'edit.php?post_type=testimonial', esc_html__( 'Add New', 'testimonial-rotator' ), esc_html__( 'Add New', 'testimonial-rotator' ), $role, 'post-new.php?post_type=testimonial_rotator' );
					break;
				}
			}
		}
	}
}




/* ADMIN ICON */
function testimonial_rotator_cpt_icon() {
	global $wp_version;

	if ( $wp_version >= 3.8 ) {
		echo '
			<style>
				#adminmenu #menu-posts-testimonial div.wp-menu-image:before { content: "\f205"; }
			</style>
		';
	}
}


// SET SETTINGS LINK ON PLUGIN PAGE
function testimonial_rotator_plugin_action_links( $links, $file ) {
	$donate_link   = '<a href="https://hackrepair.com" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support', 'testimonial-rotator' ) . '</a>';
	$settings_link = '<a href="' . admin_url( 'edit.php?post_type=testimonial&page=testimonial-rotator' ) . '">' . esc_html__( 'Settings', 'testimonial-rotator' ) . '</a>';

	if ( 'testimonial-rotator/testimonial-rotator.php' == $file ) {
		array_unshift( $links, $settings_link );
		array_unshift( $links, $donate_link );
	}

	return $links;
}
add_filter( 'plugin_action_links', 'testimonial_rotator_plugin_action_links', 10, 2 );


// MEDIA BUTTON
function testimonial_rotator_button() {
	global $pagenow, $typenow, $wp_version;
	$output = '';
	if ( version_compare( $wp_version, '3.5', '>=' ) && in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ), true ) && 'testimonial' != $typenow ) {
		$img    = '<style>#testimonial-rotator-media-button::before { font: 400 18px/1 dashicons; content: \'\f205\'; }</style><span class="wp-media-buttons-icon" id="testimonial-rotator-media-button"></span>';
		$output = '<a href="#TB_inline?width=640&inlineId=add-testimonial-rotator" class="thickbox button testimonial-rotator-thickbox" title="' . esc_html__( 'Add Rotator', 'testimonial-rotator' ) . '" style="padding-left: .4em;"> ' . $img . esc_html__( 'Add Rotator', 'testimonial-rotator' ) . '</a>';
	}
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed markup with escaped translated labels.
}
add_action( 'media_buttons', 'testimonial_rotator_button', 11 );


// MEDIA BUTTON FUNCTIONALITY
function testimonial_rotator_admin_footer_for_thickbox() {
	global $pagenow, $typenow, $wp_version;

	// Only run in post/page creation and edit screens
	if ( version_compare( $wp_version, '3.5', '>=' ) && in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ), true ) && 'testimonial' != $typenow ) { ?>
		<script type="text/javascript">
            function insertTestimonialRotator()
            {
            	var id = jQuery('#testimonial-rotator-select-box').val();
                if ('' === id)
                {
                    alert(<?php echo wp_json_encode( __( 'You must choose a rotator', 'testimonial-rotator' ) ); ?>);
                    return;
                }
                window.send_to_editor('[testimonial_rotator id="' + id + '"]');
            }
		</script>

		<div id="add-testimonial-rotator" style="display: none;">
			<div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">

				<?php

				$rotators = get_posts(
                    array(
						'post_type'   => 'testimonial_rotator',
						'numberposts' => -1,
						'orderby'     => 'title',
						'order'       => 'ASC',
                    )
                );

				if ( $rotators ) {
					?>
					<select id="testimonial-rotator-select-box" style="clear: both; display: block; margin-bottom: 1em;">
						<option value=""><?php esc_html_e( 'Choose a Rotator', 'testimonial-rotator' ); ?></option>
						<?php
						foreach ( $rotators as $rotator ) {
							echo '<option value="' . esc_attr( $rotator->ID ) . '">' . esc_html( $rotator->post_title ) . '</option>';
						}
						?>
					</select>
					<?php
                } else {
					echo esc_html__( 'No rotators have been created yet. Please create one first and then you will be able to select it here.', 'testimonial-rotator' ); }
				?>

				<p class="submit">
					<input type="button" id="testimonial-rotator-insert-download" class="button-primary" value="<?php echo esc_html__( 'Insert Rotator', 'testimonial-rotator' ); ?>" onclick="insertTestimonialRotator();" />
					<a id="testimonial-rotator-cancel-add" class="button-secondary" onclick="tb_remove();" title="<?php esc_html_e( 'Cancel', 'testimonial-rotator' ); ?>"><?php esc_html_e( 'Cancel', 'testimonial-rotator' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_footer', 'testimonial_rotator_admin_footer_for_thickbox' );
