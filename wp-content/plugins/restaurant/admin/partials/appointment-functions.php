<?php

// function tdrestaurant_get_all_appointment( $args = array() ) {
//     global $wpdb;

//     $defaults = array(
//         'number'     => 20,
//         'offset'     => 0,
//         'orderby'    => 'id',
//         'order'      => 'DESC',
//     );

//     $args      = wp_parse_args( $args, $defaults );
//     $cache_key = 'appointment-all';
//     $items     = wp_cache_get( $cache_key, 'tdrestaurant' );

//     if ( false === $items ) {
//         $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'happy_appointments ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

//         wp_cache_set( $cache_key, $items, 'tdrestaurant' );
//     }

//     return $items;
// }

// function tdrestaurant_get_appointment_count() {
//     global $wpdb;

//     return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'happy_appointments' );
// }

// function tdrestaurant_get_appointment( $id = 0 ) {
//     global $wpdb;

//     return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_appointments WHERE id = %d', $id ) );
// }

//Get appointment List

function happytaslim_get_all_appointment( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'           => 20,
        'offset'           => 0,
        'orderby'          => $wpdb->prefix . 'happy_customer.id',
        'order'            => 'DESC',
        'reservation_date' => '',
        'customer_name'    => '',
        'customer_phone'   => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $cache_key = 'happy_appointments-all';
    $items = wp_cache_get( $cache_key, 'happy_appointments' );

    if ( false === $items ) {

        $sqlcondition = '';

        if ( !empty( $args['reservation_date'] ) ) {
            $startDate = wp_date( "Y-m-d H:i:s", strtotime( $args['reservation_date'] ) );
            $endDate = wp_date( 'Y-m-d', strtotime( $args['reservation_date'] ) ) . ' 23:59:59';
            $sqlcondition = 'WHERE ' . " {$wpdb->prefix}happy_appointments.start_date BETWEEN '{$startDate}' AND '{$endDate}' ";
        }

        if ( !empty( $args['customer_name'] ) ) {
            $sqlcondition = 'WHERE ' . $wpdb->prefix . 'happy_customer.last_name LIKE ' . "'%{$args['customer_name']}%'";
        }

        if ( !empty( $args['customer_phone'] ) ) {
            $sqlcondition = 'WHERE ' . $wpdb->prefix . 'happy_customer.phone = ' . $args['customer_phone'];
        }

        $table_happy_appointments = $wpdb->prefix . 'happy_appointments';
        $table_happy_customer = $wpdb->prefix . 'happy_customer';

        $get_column = "{$table_happy_appointments}.id,{$table_happy_appointments}.customer_id,{$table_happy_appointments}.quantity,{$table_happy_appointments}.status,{$table_happy_appointments}.fullday,{$table_happy_appointments}.birthday,{$table_happy_appointments}.margetable,{$table_happy_appointments}.tableid,{$table_happy_appointments}.start_date,{$table_happy_appointments}.end_date,{$table_happy_appointments}.chair,{$table_happy_appointments}.table_location,{$table_happy_appointments}.info,{$table_happy_appointments}.customer_in,{$table_happy_appointments}.customer_out,{$table_happy_appointments}.created_date,{$table_happy_customer}.last_name,{$table_happy_customer}.email,{$table_happy_customer}.phone";

        $sqlQuery = 'SELECT ' . $get_column . ' FROM ' . $wpdb->prefix . 'happy_appointments INNER JOIN ' . $wpdb->prefix . 'happy_customer ON ' . $wpdb->prefix . 'happy_appointments.customer_id = ' . $wpdb->prefix . 'happy_customer.id ' . $sqlcondition . ' GROUP BY customer_id ORDER BY ' . $args['orderby'] . ' ' . $args['order'] . ' LIMIT ' . $args['offset'] . ', ' . $args['number'];

        $items = $wpdb->get_results( $sqlQuery );

        wp_cache_set( $cache_key, $items, 'happy_appointments' );
    }

    return $items;
}

function happytaslim_get_appointment_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(DISTINCT customer_id) FROM ' . $wpdb->prefix . 'happy_appointments INNER JOIN ' . $wpdb->prefix . 'happy_customer ON ' . $wpdb->prefix . 'happy_appointments.customer_id = ' . $wpdb->prefix . 'happy_customer.id' );
}

function happytaslim_get_appointment( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_customer WHERE id = %d', $id ) );
}

//Get Customer List

function happytaslim_get_all_customer( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'         => 20,
        'offset'         => 0,
        'orderby'        => 'id',
        'order'          => 'DESC',
        'customer_name'  => '',
        'customer_phone' => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $cache_key = 'happy_customer-all';
    $items = wp_cache_get( $cache_key, 'happy_customer' );

    if ( false === $items ) {

        $sqlcondition = '';

        if ( !empty( $args['customer_name'] ) ) {
            $sqlcondition = 'WHERE last_name LIKE ' . "'%{$args['customer_name']}%'";
        }

        if ( !empty( $args['customer_phone'] ) ) {
            $sqlcondition = 'WHERE phone = ' . $args['customer_phone'];
        }

        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'happy_customer ' . $sqlcondition . ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'] . ' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, 'happy_customer' );
    }

    return $items;
}

function happytaslim_get_customer_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'happy_customer' );
}

function happytaslim_get_customer( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_customer WHERE id = %d', $id ) );
}

// delete item
function db_delete_table( $id ) {
    if( !empty(get_option( 'tddefault_table_id' )) && get_option( 'tddefault_table_id' ) == $id ){
       delete_option( 'tddefault_table_id' ); 
    }
    
    global $wpdb;
    return $wpdb->delete(
        $wpdb->prefix . 'happy_tables',
        ['id' => $id],
        ['%d']
    );
}

// delete item
function tdcustomer_delete( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'happy_customer',
        ['id' => $id],
        ['%d']
    );
}

// delete item
function dbappointment_delete( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'happy_appointments',
        ['id' => $id],
        ['%d']
    );
}

// delete item
function taslim_dbappointment_delete( $id ) {
    global $wpdb;
    $query = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}happy_appointments WHERE customer_id = '{$id}'" );
    $ids = array();
    foreach ( $query as $value ) {
        $ids[] = $value->id;
    }
    $deleteid = implode( ',', $ids );

    $deleteresutl = $wpdb->query( "DELETE FROM {$wpdb->prefix}happy_appointments WHERE id IN ($deleteid)" );

    $wpdb->delete(
        $wpdb->prefix . 'happy_customer',
        ['id' => $id],
        ['%d']
    );

    return $deleteresutl;
}

// get appoinment by date

function get_the_appointments( $args = array() ) {
    global $wpdb;

    $defaults = array(
        //'number'     => 20,
    );

    $args = wp_parse_args( $args, $defaults );
    $cache_key = 'get_the_appointments-all';
    $items = wp_cache_get( $cache_key, 'get_the_appointments' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'happy_appointments' );

        wp_cache_set( $cache_key, $items, 'get_the_appointments' );
    }

    return $items;
}

function get_returning_customer( $phonenumber = 0 ) {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'happy_customer WHERE phone = ' . $phonenumber );

    //return $items;
}

// function td_get_currentstatus($tableNuber, $date) {

//     $startDate = date('Y-m-d', strtotime( $date )).' 00:00:00';
//     $endDate = date('Y-m-d', strtotime( $date )).' 23:59:59';

//     global $wpdb;
//     $query = $wpdb->get_results( "SELECT customer_in, customer_out, tableid FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$tableNuber}' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

//     if (!empty( $query[0] )) {
//         return $query[0];
//     }else {
//         return '0';
//     }

// }

function td_get_currentstatus( $date ) {

    $startDate = wp_date( 'Y-m-d', $date ) . ' 00:00:00';
    $endDate = wp_date( 'Y-m-d', $date ) . ' 23:59:59';

    global $wpdb;
    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  customer_in = 'yes' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    if ( !empty( $query ) ) {
        return $query;
    } else {
        return '0';
    }

}

function td_is_itfullday( $tablenumber, $date ) {

    $startDate = wp_date( 'Y-m-d', $date ) . ' 00:00:00';
    $endDate = wp_date( 'Y-m-d', $date ) . ' 23:59:59';

    global $wpdb;
    $query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$tablenumber}' AND fullday = 'yes' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    if ( !empty( $query ) ) {
        return $query;
    } else {
        return '0';
    }

}