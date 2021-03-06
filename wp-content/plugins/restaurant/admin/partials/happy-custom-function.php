<?php

function view_table_info( $tableInfo ) {
    $td_single = '';
    if (count($tableInfo) != 0) :
        foreach ($tableInfo as $tc_customerinf ) :
             
            if ( isset( $tc_customerinf->margetable ) && $tc_customerinf->margetable == 'yes' ) {
                $alltable = get_appointments_table( $tc_customerinf->customer_id );
                $margeTable = array();
                foreach ( $alltable as $stable ) {
                    $margeTable[] = $stable->tableid;
                }
                if ( count( $margeTable ) > 1 ) {
                    $tctableNumber = '<br>[' . implode( '+', $margeTable ) . ']';
                } else {
                    $tctableNumber = '';
                }
            } else {
                $tctableNumber = '';
            }
  
        $is_birthday = ( isset( $tc_customerinf->birthday ) && $tc_customerinf->birthday == 'yes' ) ? '<img src="' . plugins_url( 'admin/images/', HAPPYTASLIM_FILE ) . 'cake.png" style="width: 15px;height: 15px;margin-top: 0px;">' : '';
        $is_margetable = ( isset( $tc_customerinf->margetable ) && $tc_customerinf->margetable == 'yes' ) ? '<span class="dashicons dashicons-admin-links" style="color: #333; font-size:15px;"></span>' : '';
        $is_allergie = ( isset( $tc_customerinf->allergie ) && $tc_customerinf->allergie == 'yes' ) ? '<img src="' . plugins_url( 'admin/images/', HAPPYTASLIM_FILE ) . 'gluten.png" style="width: 15px;height: 15px;margin-left: 3px;margin-top: 0px;">' : '';
        $is_sunny = ( isset( $tc_customerinf->sunny ) && $tc_customerinf->sunny == 'yes' ) ? '<img src="'.plugins_url( 'admin/images/', HAPPYTASLIM_FILE ).'sunny.png" style="width: 15px;height: 15px;margin-left: 3px;margin-top: 0px;">' : '';
            
            $tcname = ( isset($tc_customerinf->last_name) && !empty($tc_customerinf->last_name) ) ? $tc_customerinf->last_name : '- - -';
            $tctime = ( isset($tc_customerinf->start_date) && !empty($tc_customerinf->start_date) ) ? date( 'H:i', strtotime( $tc_customerinf->start_date ) ) . ' - ' . date( 'H:i', strtotime( $tc_customerinf->end_date ) ) : '- - -';
            $tcpeople = ( isset($tc_customerinf->quantity) && !empty($tc_customerinf->quantity) ) ? $tc_customerinf->quantity.' - '.$is_birthday.' '.$is_margetable.' '.$is_allergie.' '.$is_sunny.' '.$tctableNumber : '- - -';
            $td_single .= '<p class="tcname">'.$tcname.'</p><p class="tctime">'.$tctime.'</p><p class="tcpeople">'.$tcpeople.'</p>';
            break;
        endforeach;
    endif;
    return $td_single;
}


function reservation_info_in_table( $table = '', $td_date = '' ) {

    $startDate = wp_date( 'Y-m-d', $td_date ) . ' 12:00:00';
    $endDate = wp_date( 'Y-m-d', $td_date ) . ' 22:00:00';

    global $wpdb;

    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments  INNER JOIN {$wpdb->prefix}happy_customer ON {$wpdb->prefix}happy_appointments.customer_id = {$wpdb->prefix}happy_customer.id  WHERE {$wpdb->prefix}happy_appointments.tableid = '{$table}' AND  {$wpdb->prefix}happy_appointments.start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    $oneTime = array();
    $twoTime = array();
    $ThreeTime = array();
    if (count($query) != 0):
        foreach ($query as $value) :

            if ( strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 16:00:00') <= strtotime($value->start_date) && strtotime($value->start_date) <= strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 18:59:00') ) {
                $oneTime[] = $value;
            }

            if ( strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 12:00:00') <= strtotime($value->start_date) && strtotime($value->start_date) <= strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 15:59:00') ) {
                $twoTime[] = $value;
            }

            if ( strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 19:00:00') <= strtotime($value->start_date) && strtotime($value->start_date) <= strtotime(wp_date( 'Y-m-d',  strtotime($value->start_date) ) . ' 22:30:00') ) {
                $ThreeTime[] = $value;
            }

        endforeach; // endforloop
    endif; //end if empty

    $td_item = '';
    $td_item .= '<div class="top_customer_info tcinfotop">'.view_table_info( $oneTime ).'</div>';
    $td_item .= '<div class="top_customer_info tcinfomiddle">'.view_table_info( $twoTime ).'</div>';
    $td_item .= '<div class="top_customer_info tcinfobottom">'.view_table_info( $ThreeTime ).'</div>';

    return $td_item;
}




function is_it_two_reservation_booking( $tableid = '', $date = '' ) {

    global $wpdb;

    $startDate = wp_date( 'Y-m-d', $date ) . ' 00:00:00';
    $endDate = wp_date( 'Y-m-d', $date ) . ' 23:59:59';

    //$alltableID = explode(',', $tableid);
    //
    $query = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$tableid}' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    if ( !empty( $query ) && $query >= 3 ) {
        return 'yes';
    } else {
        return '';
    }
}

function is_it_two_reservation( $tableid = '', $date = '' ) {

    global $wpdb;

    $startDate = wp_date( 'Y-m-d', $date ) . ' 00:00:00';
    $endDate = wp_date( 'Y-m-d', $date ) . ' 23:59:59';

    //$alltableID = explode(',', $tableid);
    //
    $query = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$tableid}' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    if ( !empty( $query ) && $query >= 2 ) {
        return 'yes';
    } else {
        return '';
    }
}

/*
function customer_reservation_intable( $table = '', $td_date = '', $postion = 'top' ) {
    global $wpdb;
    $startDate = wp_date( 'Y-m-d', $td_date ) . ' 17:00:00';
    $endDate = wp_date( 'Y-m-d', $td_date ) . ' 22:00:00';
    $oderby = "ORDER BY {$wpdb->prefix}happy_appointments.start_date ASC";

    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments  INNER JOIN {$wpdb->prefix}happy_customer ON {$wpdb->prefix}happy_appointments.customer_id = {$wpdb->prefix}happy_customer.id  WHERE {$wpdb->prefix}happy_appointments.tableid = '{$table}' AND  {$wpdb->prefix}happy_appointments.start_date BETWEEN '{$startDate}' AND '{$endDate}' GROUP BY customer_id {$oderby} LIMIT 2" );


   $tttime = (isset($query[0]) && !empty($query[0]))?  date('H', strtotime( $query[0]->start_date ) ) : '';

    // if (count($query) == 1 && $postion == 'top' && '19' > $tttime) {
    //     return (isset($query[0]) && !empty($query[0]))? $query[0] : '';
    // } elseif (count($query) == 1 && $postion == 'bottom' ) {
    //     return (isset($query[0]) && !empty($query[0]))? $query[0] : '';
    // } 

    if ( $postion == 'top') {
        return (isset($query[0]) && !empty($query[0]))? $query[0] : '';
    } else {
        return (isset($query[1]) && !empty($query[1]))? $query[1] : '';
    }

}
*/


function customer_reservation_intable( $table = '', $td_date = '', $postion = 'top' ) {

    if ( $postion == 'top' ) {
        $startDate = wp_date( 'Y-m-d', $td_date ) . ' 12:00:00';
        $endDate = wp_date( 'Y-m-d', $td_date ) . ' 18.59:00';
    } else {
        $startDate = wp_date( 'Y-m-d', $td_date ) . ' 19:00:00';
        $endDate = wp_date( 'Y-m-d', $td_date ) . ' 22:00:00';
    }

    global $wpdb;

    $query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments  INNER JOIN {$wpdb->prefix}happy_customer ON {$wpdb->prefix}happy_appointments.customer_id = {$wpdb->prefix}happy_customer.id  WHERE {$wpdb->prefix}happy_appointments.tableid = '{$table}' AND  {$wpdb->prefix}happy_appointments.start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

    return $query;
}

function happy_get_table( $date ) {
    $table_date = wp_date( "Y-m-d", $date );
    global $wpdb;
    return $wpdb->get_row( "SELECT * FROM td_happy_tables WHERE date = '{$table_date}'" );

}

function get_appointment_date( $id = 0 ) {

    global $wpdb;
    $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE customer_id = '{$id}'" );
    $ids = array();
    foreach ( $query as $value ) {
        $ids[] = $value->id;
    }
    $deleteid = implode( ',', $ids );
    return $query;
}

function get_the_customer( $id = 0, $field = 'last_name' ) {

    global $wpdb;

    $value = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $field . ' FROM ' . $wpdb->prefix . 'happy_customer WHERE id = %d', $id ) );
    if ( !empty( $value ) ) {
        return $value->$field;
    } else {
        return 'Customer account deleted';
    }
}

function get_the_appointment( $condition = array() ) {

    global $wpdb;

    $startDate = ( !empty( $condition['start_date'] ) ) ? wp_date( 'Y-m-d', $condition['start_date'] ) . ' 00:00:00' : wp_date( 'Y-m-d' ) . ' 00:00:00';
    $endDate = ( !empty( $condition['end_date'] ) ) ? wp_date( 'Y-m-d', $condition['end_date'] ) . ' 23:59:59' : wp_date( 'Y-m-d', $condition['start_date'] ) . ' 23:59:59';

    $query = "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE";

    foreach ( $condition as $key => $value ) {

        if ( $key != 'start_date' and $key != 'end_date' ) {
            $query .= " {$key} = '{$value}' AND ";
        }
    }

    if ( !empty( $condition['start_date'] ) ) {
        $query .= " start_date BETWEEN '{$startDate}' AND '{$endDate}' ";
    }

    $value = $wpdb->get_row( $query );

    if ( !empty( $value ) ) {
        return $value;
    } else {
        return '0';
    }
}

function get_edit_appointment( $tableid = 0, $customerid = 0 ) {

    global $wpdb;

    $query = "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$tableid}' AND customer_id = '{$customerid}'";

    $value = $wpdb->get_row( $query );

    if ( !empty( $value ) ) {
        return $value;
    } else {
        return '0';
    }
}

function is_it_fullday_appointment( $tableid = '', $date = '' ) {

    global $wpdb;

    $startDate = wp_date( 'Y-m-d', strtotime( $date ) ) . ' 00:00:00';
    $endDate = wp_date( 'Y-m-d', strtotime( $date ) ) . ' 23:59:59';

    $alltableID = explode( ',', $tableid );

    foreach ( $alltableID as $table ) {
        $query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  tableid = '{$table}' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

        if ( !empty( $query ) ) {
            return 'yes';
            break;
        } else {
            return 'no';
        }

    }
}

function is_it_booked_same_time( $tableid = '', $startDate = '', $endDate = '' ) {

    global $wpdb;
    $alltableID = explode( ',', $tableid );
    foreach ( $alltableID as $table ) {
        // $query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  status = 'approved' AND tableid = '{$table}' AND  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );
        // $query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  status = 'approved' AND tableid = '{$table}' AND  start_date <= '{$startDate}' " );
       // abc $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  status = 'approved' AND tableid = '{$table}' AND start_date <= '{$startDate}' AND end_date >= '{$endDate}' " );
        $query = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  status = 'approved' AND tableid = '{$table}' AND start_date <= '{$endDate}' AND end_date >= '{$startDate}' " );

        if ( !empty( $query ) ) {
            return 'yes';
            break;
        } else {
            return 'no';
        }
    }
}

function get_appointments( $condition = array() ) {

    global $wpdb;

    $startDate = ( !empty( $condition['start_date'] ) ) ? wp_date( 'Y-m-d', $condition['start_date'] ) . ' 00:00:00' : '';
    $endDate = ( !empty( $condition['end_date'] ) ) ? $condition['end_date'] : wp_date( 'Y-m-d', $condition['start_date'] ) . ' 23:59:59';

    $query = "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE";

    foreach ( $condition as $key => $value ) {

        if ( $key != 'start_date' and $key != 'end_date' ) {
            $query .= " {$key} = '{$value}' AND ";
        }

    }

    if ( !empty( $condition['start_date'] ) ) {
        $query .= " start_date BETWEEN '{$startDate}' AND '{$endDate}' ";
    }

    $query .= "ORDER BY 'id' ";
    $value = $wpdb->get_results( $query );

    if ( !empty( $value ) ) {
        return $value;
    } else {
        return '0';
    }
}

function get_appointments_table( $id = 0 ) {

    global $wpdb;

    $query = "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE";

    $query .= " customer_id = '{$id}' ";

    $query .= "ORDER BY 'customer_id' ASC";

    $value = $wpdb->get_results( $query );

    if ( !empty( $value ) ) {
        return $value;
    } else {
        return '0';
    }
}

function get_appointment_info( $data = array() ) {
    $tallday_booked = ( $data['birthday'] == 'yes' ) ? '<span class="dashicons dashicons-buddicons-community" style="color: #0073aa;"></span>' : '';
    $tallday_booked .= ( $data['margetable'] == 'yes' ) ? '<span class="dashicons dashicons-image-filter" style="color: #0073aa;"></span>' : '';
    return $tallday_booked;
}

function get_appointment_margetable( $data = '' ) {

    if ( $data == 'yes' ) {
        $table_area_div = '<span class="border_add">';
    } else {
        $table_area_div = '</span>';
    }

    return $table_area_div;
}

function happy_time_slots() {
    $time_slot = '+15mins';
    $start_time = strtotime( '12:00' );
    $end_time = strtotime( '23:00' );
    $alltimeslot = array();
    for ( $t = $start_time; $t <= $end_time; $t = strtotime( $time_slot, $t ) ) {
        $alltimeslot[] = date( "H:i", $t );
    }
    return $alltimeslot;
}

function happy_time_slots_end() {
    $time_slot = '+15mins';
    $start_time = strtotime( '12:59' );
    $end_time = strtotime( '23:00' );
    $alltimeslot = array();
    for ( $t = $start_time; $t <= $end_time; $t = strtotime( $time_slot, $t ) ) {
        $alltimeslot[] = date( "H:i", $t );
    }
    return $alltimeslot;
}

function get_table_list() {
    $tableList = array(
        // array(
        //     'tableid' => '1',
        //     'tablefor' => 'inside',
        //     'tableName' => 'Table 2 Chair',
        //     'chair' => '2',
        //     'image' => 'tafel-vierkant-2.png',
        //     'style' => 'position: absolute; top: 300px; left: 10px;',
        // ),
        array(
            'tableid'   => '2',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 560px; left: 20px;',
        ),

        array(
            'tableid'   => '3',
            'tablefor'  => 'inside',
            'tableName' => 'Round Table 7 Chair',
            'chair'     => '7',
            'image'     => 'tafel-rond-7.png',
            'style'     => 'position: absolute; top: 330px; left: 20px;',
        ),
        array(
            'tableid'   => '4',
            'tablefor'  => 'inside',
            'tableName' => 'Round Table 7 Chair',
            'chair'     => '7',
            'image'     => 'tafel-rond-7.png',
            'style'     => 'position: absolute; top: 87px; left: 20px;',
        ),

        array(
            'tableid'   => '5',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 87px; left: 180px',
        ),
        array(
            'tableid'   => '5A',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 320px; left: 180px',
        ),

        array(
            'tableid'   => '6',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 87px; left: 260px',
        ),

        array(
            'tableid'   => '6A',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 320px; left: 260px',
        ),

        array(
            'tableid'   => '7',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 87px; left: 360px',
        ),

        array(
            'tableid'   => '8',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 87px; left: 460px',
        ),

        array(
            'tableid'   => '9',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 87px; left: 560px',
        ),
        array(
            'tableid'   => '10',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 87px; left: 660px',
        ),
        array(
            'tableid'   => '11',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 87px; left: 760px',
        ),
        array(
            'tableid'   => '12',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 87px; left: 860px',
        ),

        array(
            'tableid'   => '13',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 87px; left: 980px',
        ),
        array(
            'tableid'   => '14',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 87px; left: 1100px',
        ),

        array(
            'tableid'   => '15',
            'tablefor'  => 'inside',
            'tableName' => 'Round Table 5 Chair',
            'chair'     => '5',
            'image'     => 'tafel-rond-5.png',
            'style'     => 'position: absolute; top: 470px; left: 1100px',
        ),

        array(
            'serial_number' => '16',
            'tablefor'      => 'inside',
            'tableid'       => '16',
            'tableName'     => 'Table 4 Chair',
            'chair'         => '4',
            'image'         => 'tafel-vierkant-4v.png',
            'style'         => 'position: absolute; top: 470px; left: 980px'
        ),

        array(
            'tableid'   => '17',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 470px; left:860px',
        ),

        array(
            'tableid'   => '18',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 470px; left: 760px',
        ),

        array(
            'tableid'   => '19',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 470px; left: 560px',
        ),
        array(
            'tableid'   => '20',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 470px; left: 460px',
        ),

        array(
            'tableid'   => '23',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 330px; left: 10px;',
        ),

        array(
            'tableid'   => '22',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 570px; left: 10px;',
        ),
        
        array(
            'tableid'   => '21',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 800px; left: 10px;',
        ),

        array(
            'tableid'   => '26',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 330px; left: 170px;',
        ),

        array(
            'tableid'   => '25',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 570px; left: 170px;',
        ),

        array(
            'tableid'   => '24',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 800px; left: 170px;',
        ),

        array(
            'tableid'   => '29',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 330px; left: 340px;',
        ),

        array(
            'tableid'   => '28',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 570px; left: 340px;',
        ),

        array(
            'tableid'   => '27',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 800px; left: 340px;',
        ),

        array(
            'tableid'   => '32',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 330px; left: 510px;',
        ),

        array(
            'tableid'   => '31',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 570px; left: 510px;',
        ),

        array(
            'tableid'   => '30',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 800px; left: 510px;',
        ),

        array(
            'tableid'   => '37',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 680px;',
        ),

        array(
            'tableid'   => '36',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 680px;',
        ),

        array(
            'tableid'   => '35',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 680px;',
        ),

        array(
            'tableid'   => '34',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 800px; left: 680px;',
        ),

        array(
            'tableid'   => '33',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 1030px; left: 680px;',
        ),

        array(
            'tableid'   => '42',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 850px;',
        ),

        array(
            'tableid'   => '41',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 850px;',
        ),

        array(
            'tableid'   => '40',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 850px;',
        ),

        array(
            'tableid'   => '39',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 800px; left: 850px;',
        ),

        array(
            'tableid'   => '38',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 1030px; left: 850px;',
        ),

        

    );


    $coronatableList = array(

        array(
            'tableid'   => '2',
            'tablefor'  => 'inside',
            'tableName' => 'Table 7 Chair',
            'chair'     => '7',
            'image'     => 'tafel-rond-7.png',
            'style'     => 'position: absolute; top: 330px; left: 20px;',
        ),

        array(
            'tableid'   => '3',
            'tablefor'  => 'inside',
            'tableName' => 'Table 5 Chair',
            'chair'     => '5',
            'image'     => 'tafel-rond-5.png',
            'style'     => 'position: absolute; top: 80px; left: 160px;',
        ),

        array(
            'tableid'   => '4',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 80px; left: 320px;',
        ),

        array(
            'tableid'   => '5',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 520px;',
        ),

        array(
            'tableid'   => '6',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 680px;',
        ),

        array(
            'tableid'   => '7',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 850px;',
        ),

        array(
            'tableid'   => '8',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 1000px;',
        ),

        array(
            'tableid'   => '9',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 460px; left: 1000px;',
        ),

        array(
            'tableid'   => '10',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 460px; left: 680px;',
        ),

        array(
            'tableid'   => '11',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 460px; left: 520px;',
        ),

        array(
            'tableid'   => '12',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 460px; left: 320px;',
        ),

        array(
            'tableid'   => '13',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 460px; left: 160px;',
        ),

        array(
            'tableid'   => '22',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 10px;',
        ),

        array(
            'tableid'   => '21',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 10px;',
        ),

        array(
            'tableid'   => '24',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 170px;',
        ),

        array(
            'tableid'   => '23',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 170px;',
        ),

        array(
            'tableid'   => '26',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 340px;',
        ),

        array(
            'tableid'   => '25',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 340px;',
        ),

        array(
            'tableid'   => '28',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 510px;',
        ),

        array(
            'tableid'   => '27',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 510px;',
        ),

        array(
            'tableid'   => '32',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 680px;',
        ),

        array(
            'tableid'   => '31',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 680px;',
        ),

        array(
            'tableid'   => '30',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 680px;',
        ),

        array(
            'tableid'   => '29',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 800px; left: 680px;',
        ),

        array(
            'tableid'   => '36',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 850px;',
        ),

        array(
            'tableid'   => '35',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 850px;',
        ),

        array(
            'tableid'   => '34',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 850px;',
        ),

        array(
            'tableid'   => '33',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 800px; left: 850px;',
        ),

    );


    $coronatableList_one = array(

        array(
            'tableid'   => '1',
            'tablefor'  => 'inside',
            'tableName' => 'Table 1 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 370px; left: 20px;',
        ),

        array(
            'tableid'   => '2',
            'tablefor'  => 'inside',
            'tableName' => 'Table 7 Chair',
            'chair'     => '7',
            'image'     => 'tafel-rond-7.png',
            'style'     => 'position: absolute; top: 170px; left: 30px;',
        ),

        array(
            'tableid'   => '3',
            'tablefor'  => 'inside',
            'tableName' => 'Table 7 Chair',
            'chair'     => '7',
            'image'     => 'tafel-rond-7.png',
            'style'     => 'position: absolute; top: 80px; left: 160px;',
        ),

        array(
            'tableid'   => '4',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 80px; left: 320px;',
        ),

        array(
            'tableid'   => '5',
            'tablefor'  => 'inside',
            'tableName' => 'Table 5 Chair',
            'chair'     => '5',
            'image'     => 'tafel-rond-5.png',
            'style'     => 'position: absolute; top: 80px; left: 520px;',
        ),

        array(
            'tableid'   => '6',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 680px;',
        ),

        array(
            'tableid'   => '7',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 850px;',
        ),

        array(
            'tableid'   => '8',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 80px; left: 1000px;',
        ),

        array(
            'tableid'   => '9',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 460px; left: 1000px;',
        ),

        array(
            'tableid'   => '10',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 460px; left: 775px;',
        ),

        array(
            'tableid'   => '11',
            'tablefor'  => 'inside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '4',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 460px; left: 560px;',
        ),

        array(
            'tableid'   => '12',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '5',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 460px; left: 320px;',
        ),

        array(
            'tableid'   => '13',
            'tablefor'  => 'inside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '5',
            'image'     => 'tafel-vierkant-2.png',
            'style'     => 'position: absolute; top: 460px; left: 160px;',
        ),

        array(
            'tableid'   => '22',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 10px;',
        ),

        array(
            'tableid'   => '21',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 10px;',
        ),

        array(
            'tableid'   => '24',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 170px;',
        ),

        array(
            'tableid'   => '23',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 170px;',
        ),

        array(
            'tableid'   => '26',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 340px;',
        ),

        array(
            'tableid'   => '25',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 340px;',
        ),

        array(
            'tableid'   => '28',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 220px; left: 510px;',
        ),

        array(
            'tableid'   => '27',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4v.png',
            'style'     => 'position: absolute; top: 695px; left: 510px;',
        ),

        array(
            'tableid'   => '32',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 680px;',
        ),

        array(
            'tableid'   => '31',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 680px;',
        ),

        array(
            'tableid'   => '30',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 680px;',
        ),

        array(
            'tableid'   => '29',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 800px; left: 680px;',
        ),

        array(
            'tableid'   => '36',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 90px; left: 850px;',
        ),

        array(
            'tableid'   => '35',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 330px; left: 850px;',
        ),

        array(
            'tableid'   => '34',
            'tablefor'  => 'outside',
            'tableName' => 'Table 2 Chair',
            'chair'     => '2',
            'image'     => 'tafel-vierkant-2v.png',
            'style'     => 'position: absolute; top: 570px; left: 850px;',
        ),

        array(
            'tableid'   => '33',
            'tablefor'  => 'outside',
            'tableName' => 'Table 4 Chair',
            'chair'     => '6',
            'image'     => 'tafel-vierkant-4.png',
            'style'     => 'position: absolute; top: 800px; left: 850px;',
        ),

    );

    $tdoptions = get_option( 'tddisabletable' );
    $tdoptions_selectedt = isset( $tdoptions['table_plan_type'] ) ? esc_attr( $tdoptions['table_plan_type']) : '';
    if ( $tdoptions_selectedt == 'td_corona') {
        $tablefromcode = $coronatableList;
    } elseif ( $tdoptions_selectedt == 'td_corona_new' ) {
        $tablefromcode = $coronatableList_one;
    }  else {
        $tablefromcode = $tableList;
    }


    if (!empty(get_option( 'tddefault_table_id' ))) {
        global $wpdb;
        $tabledata = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_tables WHERE id = %d', get_option( 'tddefault_table_id' ) ) );
        $table_plan =  (!empty($tabledata->tables)) ? unserialize($tabledata->tables) : $tablefromcode;
    }else {
        $table_plan = $tablefromcode;
    }

    return $table_plan;
}


function disable_table() {

    $tdsettingls = get_option( 'tddisabletable' );
    if (!empty($tdsettingls['tabledisable'])) {
        $dtablelist = explode(',', $tdsettingls['tabledisable']);
    }else {
        $dtablelist = array();
    }
    return $dtablelist;
}