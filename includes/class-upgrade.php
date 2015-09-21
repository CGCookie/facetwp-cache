<?php

class FacetWP_Cache_Upgrade
{
    function __construct() {
        $this->version = FACETWP_CACHE_VERSION;
        $this->last_version = get_option( 'facetwp_cache_version' );

        if ( version_compare( $this->last_version, $this->version, '<' ) ) {
            if ( version_compare( $this->last_version, '0.1.0', '<' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                $this->clean_install();
            }
            else {
                $this->run_upgrade();
            }

            update_option( 'facetwp_cache_version', $this->version );
        }
    }


    private function clean_install() {
        global $wpdb;

        $sql = "
        CREATE TABLE {$wpdb->prefix}facetwp_cache (
            id BIGINT unsigned not null auto_increment,
            name VARCHAR(32),
            uri VARCHAR(255),
            value LONGTEXT,
            expire DATETIME,
            PRIMARY KEY (id)
        ) DEFAULT CHARSET=utf8";
        dbDelta( $sql );
    }


    private function run_upgrade() {
        global $wpdb;

        if ( version_compare( $this->last_version, '1.1', '<' ) ) {
            $wpdb->query( "ALTER TABLE {$wpdb->prefix}facetwp_cache ADD COLUMN uri VARCHAR(255) AFTER name" );
        }
    }
}
