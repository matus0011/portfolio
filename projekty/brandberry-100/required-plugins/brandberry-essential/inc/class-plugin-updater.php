<?php

defined( 'ABSPATH' ) || exit;

class Brandberry_Essential_Updater {

    public function __construct() {
        add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ] );
        add_filter( 'plugins_api', [ $this, 'plugin_info' ], 20, 3 );
    }

    /**
     * Check for plugin update
     */
    public function check_update( $transient ) {

        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $response = wp_remote_get( BRANDBERRY_UPDATE_API, [
            'timeout' => 15,
        ]);

        if ( is_wp_error( $response ) ) {
            return $transient;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( empty( $data->version ) ) {
            return $transient;
        }

        if ( version_compare( BRANDBERRY_ESSENTIAL_VERSION, $data->version, '<' ) ) {

            $transient->response[ BRANDBERRY_ESSENTIAL_PLUGIN_FILE ] = (object) [
                'slug'        => BRANDBERRY_ESSENTIAL_SLUG,
                'plugin'      => BRANDBERRY_ESSENTIAL_PLUGIN_FILE,
                'new_version' => $data->version,
                'package'     => esc_url( $data->download_url ),
                'tested'      => $data->tested ?? '',
                'requires'    => $data->requires ?? '',
            ];
        }

        return $transient;
    }

    /**
     * Plugin info popup (View details)
     */
    public function plugin_info( $result, $action, $args ) {

        if ( $action !== 'plugin_information' ) {
            return $result;
        }

        if ( $args->slug !== BRANDBERRY_ESSENTIAL_SLUG ) {
            return $result;
        }

        $response = wp_remote_get( BRANDBERRY_UPDATE_API );

        if ( is_wp_error( $response ) ) {
            return $result;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ) );

        return (object) [
            'name'        => 'Brandberry Essential',
            'slug'        => BRANDBERRY_ESSENTIAL_SLUG,
            'version'     => $data->version,
            'author'      => '<a href="https://brandberry.com">Brandberry</a>',
            'homepage'    => 'https://brandberry.com',
            'requires'    => $data->requires,
            'tested'      => $data->tested,
            'download_link' => $data->download_url,
            'sections'    => [
                'description' => 'Essential companion plugin for Brandberry theme.',
                'changelog'   => $data->changelog ?? '',
            ],
        ];
    }
}
