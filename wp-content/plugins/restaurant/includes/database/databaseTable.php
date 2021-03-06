<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

class HappyTaslim_DatabaseTables {

    public function __construct() {

    }

    public static function HappyTaslim_add_tables() {
        global $wpdb;
        //Do not show errors

        $wpdb->show_errors();

        // //$wpdb->hide_errors();
        error_reporting( 1 );
        ini_set( 'display_errors', 1 );

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $DatabaseTable_Version = '6.0';

        if ( $schema = self::HappyTaslim_Get_Database_Schema() ) {

            // record the activation date/time if not exists
            $installed = get_option( 'HappyTaslim_Installed' );

            if ( !$installed ) {
                update_option( 'HappyTaslim_Installed', time() );
            }

            dbDelta( $schema );
            add_option( 'HappyTaslim_db_version', $DatabaseTable_Version );
        }
    }

    public static function HappyTaslim_Get_Database_Schema() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $query = '';

        // happy_customer
        $table_name = $wpdb->prefix . 'happy_customer';
        $sql = "CREATE TABLE `$table_name`(
				`id`						INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`last_name`      	    	VARCHAR(255) DEFAULT NULL,
				`email`          			VARCHAR(255) DEFAULT NULL,
				`phone`          			VARCHAR(255) DEFAULT NULL,
				`info`           			TEXT DEFAULT NULL,
				`created_date`				DATETIME NOT NULL
		) {$charset_collate};";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {$query .= $sql;}

        // happy_appointments
        $table_name = $wpdb->prefix . 'happy_appointments';
        $sql = "CREATE TABLE `$table_name`(
				`id`						INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`customer_id`         		INT UNSIGNED DEFAULT NULL,
				`quantity`      	   		INT UNSIGNED DEFAULT NULL,
				`status`     	        	ENUM('approved', 'pending', 'canceled') NOT NULL DEFAULT 'pending',
				`fullday`     	        	ENUM('yes', 'no') NOT NULL DEFAULT 'no',
				`birthday`     	        	ENUM('yes', 'no') NOT NULL DEFAULT 'no',
				`allergie`     	        	ENUM('yes', 'no') NOT NULL DEFAULT 'no',
				`margetable`     	        ENUM('yes', 'no') NOT NULL DEFAULT 'no',
                `sunny`                     ENUM('yes', 'no') NOT NULL DEFAULT 'no',
				`tableid`      	    		VARCHAR(255) DEFAULT NULL,
				`start_date`				DATETIME DEFAULT NULL,
				`end_date`					DATETIME DEFAULT NULL,
				`chair`           			VARCHAR(255) DEFAULT NULL,
                `table_slot`                VARCHAR(255) DEFAULT NULL,
				`table_location`           	VARCHAR(255) DEFAULT NULL,
				`info`           			TEXT DEFAULT NULL,
                `sendsms`                   TEXT DEFAULT NULL,
				`customer_in`     	        ENUM('yes', 'no') DEFAULT NULL,
				`customer_out`     	        ENUM('yes', 'no') DEFAULT NULL,
				`created_date`				DATETIME NOT NULL
		) {$charset_collate};";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {$query .= $sql;}

        // happy_tables
        $table_name = $wpdb->prefix . 'happy_tables';
        $sql = "CREATE TABLE `$table_name`(
				`id`						INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`tables`      	    		TEXT DEFAULT NULL,
				`date`						DATETIME DEFAULT NULL,
                `default`                   ENUM('yes', 'no') NOT NULL DEFAULT 'no',
				`created_date`				DATETIME NOT NULL
		) {$charset_collate};";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {$query .= $sql;}

        // Return Queries
        // echo $query;
        // die();
        return $query;

    }
}

//ALTER TABLE td_happy_appointments ADD `sunny`   ENUM('yes', 'no') NOT NULL DEFAULT 'no'
//ALTER TABLE td_happy_appointments ADD `sendsms` TEXT DEFAULT NULL,