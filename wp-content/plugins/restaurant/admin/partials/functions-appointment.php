<?php

function add_new_customer( $args = array() ) {

    global $wpdb;

    $defaults = array(
        'id'        => null,
        'last_name' => '',
        'email'     => '',
        'phone'     => '',
        'info'      => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'happy_customer';

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( !$row_id ) {

        $args['created_date'] = current_time( 'mysql' );

        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
        }

    } else {

        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;

}

function add_new_appoinment( $args = array() ) {

    global $wpdb;

    $defaults = array(
        'id'          => null,
        'customer_id' => '',
        'quantity'    => '',
        'status'      => '',
        'fullday'     => '',
        'birthday'    => '',
        'margetable'  => '',
        'tableid'     => '',
        'start_date'  => '',
        'end_date'    => '',
        'info'        => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'happy_appointments';

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( !$row_id ) {

        $args['created_date'] = current_time( 'mysql' );

        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
        }

    } else {

        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;

}

function happy_customer_update( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'id'        => null,
        'last_name' => '',
        'email'     => '',
        'phone'     => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'happy_customer';

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( $row_id ) {
        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }

    }

    return false;
}

function add_new_tables( $args = array() ) {

    global $wpdb;

    $defaults = array(
        'id'           => null,
        'tables'       => '',
        'date'         => '',
        'created_date' => current_time( 'mysql' ),
    );

    $args = wp_parse_args( $args, $defaults );

    $table_name = $wpdb->prefix . 'happy_tables';

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( !$row_id ) {

        // $args['created_date'] = current_time( 'mysql' );
        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
        }

    } else {

        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;

}