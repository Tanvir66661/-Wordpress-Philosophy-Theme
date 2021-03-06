<?php

function get_all_today( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'orderby'           => $wpdb->prefix . 'happy_appointments.start_date',
        'order'             => 'ASC',
        'reservation_start' => '',
        'reservation_end'   => '',
        'customer_name'     => '',
        'customer_phone'    => '',
    );

    $args = wp_parse_args( $args, $defaults );

    $sqlcondition = '';

    if ( !empty( $args['reservation_start'] ) ) {
        $startDate = date( "Y-m-d H:i:s", $args['reservation_start'] );
        $endDate = date( "Y-m-d H:i:s", $args['reservation_end'] );
        $sqlcondition .= " {$wpdb->prefix}happy_appointments.start_date BETWEEN '{$startDate}' AND '{$endDate}' ";
    }

    if ( !empty( $args['customer_name'] ) ) {
        $sqlcondition .= 'AND ' . $wpdb->prefix . 'happy_customer.last_name LIKE ' . "'%{$args['customer_name']}%'";
    }

    if ( !empty( $args['customer_phone'] ) ) {
        $sqlcondition .= 'AND ' . $wpdb->prefix . 'happy_customer.phone = ' . $args['customer_phone'];
    }

    $table_happy_appointments = $wpdb->prefix . 'happy_appointments';
    $table_happy_customer = $wpdb->prefix . 'happy_customer';

    $get_column = "{$table_happy_appointments}.id,{$table_happy_appointments}.customer_id,{$table_happy_appointments}.quantity,{$table_happy_appointments}.status,{$table_happy_appointments}.fullday,{$table_happy_appointments}.birthday,{$table_happy_appointments}.allergie,{$table_happy_appointments}.sunny,{$table_happy_appointments}.margetable,{$table_happy_appointments}.tableid,{$table_happy_appointments}.start_date,{$table_happy_appointments}.end_date,{$table_happy_appointments}.info,{$table_happy_appointments}.customer_in,{$table_happy_appointments}.customer_out,{$table_happy_appointments}.created_date,{$table_happy_appointments}.chair,{$table_happy_appointments}.table_location,{$table_happy_customer}.last_name,{$table_happy_customer}.email,{$table_happy_customer}.phone";

    $sqlQuery = 'SELECT ' . $get_column . ' FROM ' . $wpdb->prefix . 'happy_appointments INNER JOIN ' . $wpdb->prefix . 'happy_customer ON ' . $wpdb->prefix . 'happy_appointments.customer_id = ' . $wpdb->prefix . 'happy_customer.id WHERE ' . $wpdb->prefix . 'happy_appointments.status = "approved" AND ' . $sqlcondition . ' GROUP BY customer_id ORDER BY ' . $args['orderby'] . ' ' . $args['order'];

    $items = $wpdb->get_results( $sqlQuery );

    return $items;
}

function get_count_today() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'happy_tables' );
}

function get_single_today( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_tables WHERE id = %d', $id ) );
}

function get_total_people( $startDate, $endDate ) {
    global $wpdb;

    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  status = 'approved' AND start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    if ( !empty( $query ) ) {
        return 'yes';
    } else {
        return 'no';
    }

}

function get_customer_out( $startDate, $endDate ) {
    global $wpdb;

    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments INNER JOIN {$wpdb->prefix}happy_customer ON " . $wpdb->prefix . "happy_appointments.customer_id =" . $wpdb->prefix . "happy_customer.id WHERE end_date BETWEEN '{$startDate}' AND '{$endDate}' GROUP BY customer_id  " );

    if ( !empty( $query ) ) {
        return $query;
    } else {
        return $query;
    }

}