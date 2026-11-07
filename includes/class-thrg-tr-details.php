<?php
/**
 * Native "View details" dialog on the Plugins screen, built from the bundled readme.
 *
 * @package Testimonial_Rotator_Reborn
 */

defined( 'ABSPATH' ) || exit;

/** Plugin information from local files; no network access needed. */
final class THRG_TR_Details {

    const NAME = 'Testimonial Rotator (Reborn)';

    /**
     * Register hooks.
     *
     * @return void
     */
    public static function boot() {
        add_filter( 'plugin_row_meta', array( __CLASS__, 'row_meta' ), 20, 2 );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'thickbox' ) );
        add_filter( 'plugins_api', array( __CLASS__, 'info' ), 99, 3 );
    }

    /**
     * The installed folder name, which is this plugin's slug.
     *
     * @return string
     */
    public static function slug() {
        return dirname( plugin_basename( THRG_TR_FILE ) );
    }

    /**
     * Add a View details link when none is present.
     *
     * @param string[] $links       Existing links.
     * @param string   $plugin_file Plugin entry point.
     * @return string[]
     */
    public static function row_meta( $links, $plugin_file ) {
        if ( plugin_basename( THRG_TR_FILE ) !== $plugin_file ) {
            return $links;
        }
        foreach ( $links as $existing ) {
            if ( is_string( $existing ) && false !== strpos( $existing, 'tab=plugin-information' ) ) {
                return $links;
            }
        }
        $url     = add_query_arg(
            array(
                'tab'       => 'plugin-information',
                'plugin'    => self::slug(),
                'TB_iframe' => 'true',
                'width'     => 600,
                'height'    => 550,
            ),
            admin_url( 'plugin-install.php' )
        );
        $links[] = sprintf(
            '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
            esc_url( $url ),
            /* translators: %s: plugin name */
            esc_attr( sprintf( __( 'More information about %s', 'testimonial-rotator' ), self::NAME ) ),
            esc_attr( self::NAME ),
            esc_html__( 'View details', 'testimonial-rotator' )
        );
        return $links;
    }

    /**
     * Make sure the dialog script is on the Plugins screen.
     *
     * @param string $hook Admin page.
     * @return void
     */
    public static function thickbox( $hook ) {
        if ( 'plugins.php' === $hook ) {
            add_thickbox();
        }
    }

    /**
     * Inline readme markup: **bold** and [text](https://link "tooltip"), after escaping.
     *
     * @param string $text Text.
     * @return string
     */
    public static function inline( $text ) {
        $text = esc_html( $text );
        $text = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text );
        $text = preg_replace( '/`([^`]+)`/', '<code>$1</code>', $text );
        return preg_replace_callback(
            '/\[([^\]]+)\]\((https?:\/\/[^\s)"\'<>]+)(?:\s+&quot;(.+?)&quot;)?\)/',
            static function ( $m ) {
                $title = isset( $m[3] ) && '' !== $m[3] ? ' title="' . $m[3] . '"' : '';
                return '<a href="' . $m[2] . '"' . $title . ' target="_blank" rel="noopener noreferrer">' . $m[1] . '</a>';
            },
            $text
        );
    }

    /**
     * Readme section to safe HTML: paragraphs, = headings =, bullet and numbered lists.
     *
     * @param string $source Section text.
     * @return string
     */
    public static function section( $source ) {
        $lines     = preg_split( '/\r\n|\r|\n/', trim( $source ) );
        $output    = '';
        $paragraph = array();
        $list_type = '';

        $flush = static function () use ( &$output, &$paragraph ) {
            if ( $paragraph ) {
                $output   .= wpautop( self::inline( implode( "\n", $paragraph ) ) );
                $paragraph = array();
            }
        };
        $close = static function () use ( &$output, &$list_type ) {
            if ( $list_type ) {
                $output   .= '</' . $list_type . '>';
                $list_type = '';
            }
        };

        foreach ( $lines as $line ) {
            $line = trim( $line );
            if ( '' === $line ) {
                $flush();
                $close();
            } elseif ( preg_match( '/^=\s*(.*?)\s*=$/', $line, $heading ) ) {
                $flush();
                $close();
                $output .= '<h4>' . esc_html( $heading[1] ) . '</h4>';
            } elseif ( preg_match( '/^(?:\*\s+|([0-9]+)\.\s+)(.*)$/', $line, $item ) ) {
                $flush();
                $type = isset( $item[1] ) && '' !== $item[1] ? 'ol' : 'ul';
                if ( $list_type !== $type ) {
                    $close();
                    $list_type = $type;
                    $output   .= '<' . $list_type . '>';
                }
                $output .= '<li>' . self::inline( $item[2] ) . '</li>';
            } else {
                $close();
                $paragraph[] = $line;
            }
        }
        $flush();
        $close();
        return $output;
    }

    /**
     * Plugin information for our slug, unless a newer release is waiting.
     *
     * @param mixed  $result Previous result.
     * @param string $action API action.
     * @param object $args   Arguments.
     * @return mixed
     */
    public static function info( $result, $action, $args ) {
        if ( 'plugin_information' !== $action || ! is_object( $args ) || ! isset( $args->slug ) || self::slug() !== $args->slug ) {
            return $result;
        }
        $readme_path = TESTIMONIAL_ROTATOR_DIR . '/readme.txt';
        if ( ! is_readable( $readme_path ) ) {
            return $result;
        }
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a local bundled file.
        $readme = file_get_contents( $readme_path );
        if ( false === $readme ) {
            return $result;
        }

        $parts    = preg_split( '/^==\s*([^\r\n]+?)\s*==\s*$/m', $readme, -1, PREG_SPLIT_DELIM_CAPTURE );
        $tabs     = array(
            'description'                => 'description',
            'installation'               => 'installation',
            'frequently asked questions' => 'faq',
            'changelog'                  => 'changelog',
            'support'                    => 'support',
            'history'                    => 'history',
        );
        $sections = array();
        $count    = count( $parts );
        for ( $i = 1; $i + 1 < $count; $i += 2 ) {
            $key = strtolower( trim( $parts[ $i ] ) );
            if ( isset( $tabs[ $key ] ) ) {
                $sections[ $tabs[ $key ] ] = self::section( $parts[ $i + 1 ] );
            }
        }
        if ( ! isset( $sections['description'] ) ) {
            return $result;
        }

        $url = plugins_url( 'assets/', THRG_TR_FILE );

        wp_enqueue_style( 'thrg-tr-brand', $url . 'brand/thrg-brand.css', array(), THRG_TR_VERSION );
        wp_enqueue_style( 'thrg-tr-brand-details', $url . 'brand/thrg-details.css', array( 'thrg-tr-brand' ), THRG_TR_VERSION );

        $info               = new stdClass();
        $info->name         = self::NAME;
        $info->slug         = self::slug();
        $info->version      = THRG_TR_VERSION;
        $info->author       = 'Hal Gatewood (abandoned website); maintained by <a href="https://hackrepair.com/" title="The Hack Repair Guy: WordPress security and hack repair">The Hack Repair Guy</a>';
        $info->homepage     = 'https://github.com/hackrepair/testimonial-rotator';
        $info->external     = true;
        $info->requires     = '6.9.4';
        $info->tested       = '7.1';
        $info->requires_php = '7.4';
        $info->last_updated = gmdate( 'Y-m-d H:i:s', (int) filemtime( THRG_TR_FILE ) );
        $info->sections     = $sections;
        $info->banners      = array(
            'low'  => $url . 'brand/banner.svg',
            'high' => $url . 'brand/banner.svg',
        );
        return $info;
    }
}
