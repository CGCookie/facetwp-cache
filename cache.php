<?php

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FACETWP_CACHE', true );

$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
$data = isset( $_POST['data'] ) ? $_POST['data'] : array();

if ( 'facetwp_refresh' == $action ) {

    global $table_prefix;
    $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
    $wpdb->prefix = $table_prefix;

    // Timestamp
    $now = date( 'Y-m-d H:i:s' );

    // MD5 hash
    $cache_name = md5( json_encode( $data ) );

    // Check for a cached version
    $sql = "
    SELECT value
    FROM {$wpdb->prefix}facetwp_cache
    WHERE name = '$cache_name' AND expire >= '$now'
    LIMIT 1";
    $value = $wpdb->get_var( $sql );

    // Return cached version and EXIT
    if ( null !== $value ) {
        echo $value;
        exit;
    }
}
