<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BRANDBERRY_THEME_UPDATER' ) ) {

    class BRANDBERRY_THEME_UPDATER {

        public function __construct() {
           
            add_filter(
                'pre_set_site_transient_update_themes',
                [ $this, 'check_update' ]
            );

            add_filter(
                'themes_api',
                [ $this, 'theme_info' ],
                20,
                3
            );
        }

        /**
         * Check for theme update
         */
        public function check_update( $transient ) {
          
            if ( empty( $transient->checked ) ) {
                return $transient;
            }

            $response = wp_remote_get(
                BRANDBERRY_THEME_UPDATER_API,
                [ 'timeout' => 15 ]
            );

            if ( is_wp_error( $response ) ) {
                return $transient;
            }

            $data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( empty( $data->version ) ) {
                return $transient;
            }

            if (
                version_compare(
                    BRANDBERRY_THEME_UPDATER_VERSION,
                    $data->version,
                    '<'
                )
            ) {

                $transient->response[
                    BRANDBERRY_THEME_UPDATER_SLUG
                ] = [
                    'theme'       => BRANDBERRY_THEME_UPDATER_SLUG,
                    'new_version' => $data->version,
                    'url'         => BRANDBERRY_ESSENTIAL_ELEMENTOR_SECTION_BASE,
                    'package'     => esc_url( $data->download_url ),
                    'tested'      => $data->tested ?? '',
                    'requires'    => $data->requires ?? '',
                ];
            }

            return $transient;
        }

        /**
         * Theme info popup (View details)
         */
        public function theme_info( $result, $action, $args ) {

            if ( $action !== 'theme_information' ) {
                return $result;
            }

            if (
                empty( $args->slug ) ||
                $args->slug !== BRANDBERRY_THEME_UPDATER_SLUG
            ) {
                return $result;
            }

            $response = wp_remote_get( BRANDBERRY_THEME_UPDATER_API );

            if ( is_wp_error( $response ) ) {
                return $result;
            }

            $data = json_decode( wp_remote_retrieve_body( $response ) );

            return (object) [
                'name'     => 'Brandberry',
                'slug'     => BRANDBERRY_THEME_UPDATER_SLUG,
                'version'  => $data->version,
                'author'   => '<a href="https://yourcompany.com">Your Company</a>',
                'homepage' => 'https://yourcompany.com',
                'requires' => $data->requires,
                'tested'   => $data->tested,
                'download_link' => $data->download_url,
                'sections' => [
                    'description' =>
                        'Premium Brandberry WordPress Theme.',
                    'changelog'   => $data->changelog ?? '',
                ],
            ];
        }
    }
}
