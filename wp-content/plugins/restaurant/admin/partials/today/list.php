<?php
$td_date = isset( $_REQUEST['td_date'] ) ? sanitize_text_field( strtotime( $_REQUEST['td_date'] ) ) : strtotime( wp_date( 'd-m-Y' ) );
$customer_name = isset( $_REQUEST['customer_name'] ) ? sanitize_text_field( $_REQUEST['customer_name'] ) : '';
$customer_phone = isset( $_REQUEST['customer_phone'] ) ? sanitize_text_field( $_REQUEST['customer_phone'] ) : '';
?>

<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e( 'Overzicht bezetting', 'tdrestaurant' );?></h1>
    <form method="post">
    <input type="hidden" name="page" value="tdtoday">
    <div class="tablenav top">
        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" name="td_date" id="selectdate" class="datefield notranslate" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="<?php echo wp_date( 'd-m-Y', $td_date ); ?>" autocomplete="off">
                <input type="submit" name="date-submit" value="Zoek" class="button">
            </p>
        </div>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_name'] ) ) ? $_REQUEST['customer_name'] : ''; ?>" name="customer_name" placeholder="Naam">
                <input type="submit" name="name-submit" value="Zoek" class="button">
            </p>
        </div>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_phone'] ) ) ? $_REQUEST['customer_phone'] : ''; ?>" name="customer_phone" placeholder="Telefoonnummer">
                <input type="submit" name="phone-submit" value="Zoek" class="button">
            </p>
        </div>
        <br class="clear">
    </div>

    <div class="td_reservation_list_area">

        <div class="td_reservation_header_area">
            <span class="reservation_time">Tijd</span>
            <span class="reservation_customer">Naam</span>
            <span class="reservation_phone">Telefoonnummer</span>
            <span class="reservation_people">Aantal</span>
            <span class="reservation_table">Tafel</span>
            <span class="reservation_table_position">Locatie</span>
            <span class="reservation_birthday">Extra info</span>
            <span class="returning_customer">Vaste klant</span>
            <span class="reservation_status">Status</span>
        </div>

<?php

$tdgetstatus = td_get_currentstatus( $td_date );

$td_isnotfree = array();
if ( !empty( $tdgetstatus ) ) {
    foreach ( $tdgetstatus as $tdgetstatu ) {
        $td_isnotfree[] = $tdgetstatu->tableid;
    }
}
$time_slot = '+30mins';
$start_time = strtotime( '12:00', $td_date );
$end_time = strtotime( '22:00', $td_date );
$alltimeslot = array();
for ( $t = $start_time; $t <= $end_time; $t = strtotime( $time_slot, $t ) ) {
    $endTime_slot = date( "H:i", strtotime( '+29mins', $t ) );

    $args = array(
        'reservation_start' => $t,
        'reservation_end'   => strtotime( '+29mins', $t ),
        'customer_name'     => $customer_name,
        'customer_phone'    => $customer_phone,
    );
    $peopleOout = get_customer_out( wp_date( "Y-m-d H:i:s", $t ), wp_date( "Y-m-d H:i:s", strtotime( '+29mins', $t ) ) );

    $outPeople = 0;
    foreach ( $peopleOout as $value ) {
        $outPeople++;
    }

    $tdorder = get_all_today( $args );

    if ( count( $tdorder ) != 0 ):

        $totlalPeople = 0;
        $inPeople = 0;
        foreach ( $tdorder as $value ) {
            $totlalPeople += $value->quantity;

            if ( wp_date( "H:i" ) >= date( "H:i", strtotime( $value->start_date ) ) && wp_date( "H:i" ) <= date( "H:i", strtotime( '+29mins', strtotime( $value->start_date ) ) ) ) {
                $inPeople++;
            }
        }

        $tableMearge = '';
        foreach ( $tdorder as $value ) {
            $tableMearge = $value->margetable;
            if ( $tableMearge == 'yes' ) {
                break;
            }
        }

        if ( wp_date( "H:i" ) >= date( "H:i", $t ) && wp_date( "H:i" ) <= date( "H:i", strtotime( '+29mins', $t ) ) ) {
            $active_time = 'active_now';
        } else {
            $active_time = '';
        }

        ?>

		        <div class="td_reservation_list">

		            <div class="td_be_day_separator <?php echo $active_time; ?>">
		                <span class="date_holder">
		                    <span class="dashicons dashicons-clock"></span><strong><?php echo date( "H:i", $t ); ?> - <?php echo $endTime_slot; ?></strong></span>
		                <span class="status_holder">
		                    <span class="app_count">
		                        <span class="app_count_c"><?php echo $totlalPeople; ?></span>
		                        <span class="app_count_t">PERSONEN AANWEZIG</span>
		                    </span>
		                    <?php if ( $tableMearge == 'yes' ): ?>
		    <!--                 <span class="mergetable">
		                        <span class="app_count_t">SAMENGEVOEGDE TAFELS</span>
		                    </span> -->
		                    <?php endif;?>
            </span>
        </div>

        <div class="td_item_list">
            <?php

    if ( !empty( $tdorder ) ):
        foreach ( $tdorder as $order ):
             $alltable = get_appointments_table( $order->customer_id );
            $margeTable = array();
            foreach ( $alltable as $stable ) {
                $margeTable[] = $stable->tableid;
            }
        ?>
		                <div class="tdheader_in">
		                    <div class="td_reservation_info">
		                        <span class="reservation_time">
		                        <?php echo date( 'H:i', strtotime( $order->start_date ) ); ?> -
		                        <?php echo date( 'H:i', strtotime( $order->end_date ) ); ?></span>
		                        <span class="reservation_customer" style="line-height: 24px;">
                                    <?php echo $order->last_name; ?>
                                    <a href="#" class="staff_edit button button-small button-primary" 
                                    customerId="<?php echo $order->customer_id; ?>"
                                    tableLocation="<?php echo $order->table_location; ?>"
                                    tableList="<?php echo implode(',', $margeTable); ?>"
                                    rpeople="<?php echo $order->quantity; ?>"
                                    staffnonce="<?php echo wp_create_nonce( 'staff_edit_reservation' );?>"
                                     >VERHUIS</a>                           
                                </span>
		                        <span class="reservation_phone"><?php echo $order->phone; ?></span>
		                        <span class="reservation_people"><?php echo $order->quantity; ?></span>
		                        <span class="reservation_table">
		                            <?php
   

        if ( count( $margeTable ) > 1 ) {
            echo $order->tableid . ' - [' . implode( '+', $margeTable ) . ']';
            $td_isfree = array_intersect( $td_isnotfree, $margeTable );
        } else {
            echo $order->tableid;
            $margeTables = array( $order->tableid );
            $td_isfree = array_intersect( $td_isnotfree, $margeTables );
        }
        ?>
		                        </span>
		                        <span class="reservation_table_position">
		                            <?php echo ( $order->table_location == 'inside' ) ? 'Restaurant' : 'Terras'; ?>
		                        </span>
		                        <span class="reservation_birthday" style="text-align: left;">
<?php
if ( $order->birthday == 'yes' ) {
echo '<img style="width: 20px;" src="' . plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/cake.png' . '">';
}
?>
<?php
if ( $order->allergie == 'yes' ) {
echo ' <img style="width: 20px;" src="' . plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/gluten.png' . '">';
}
?>
<?php
if ( $order->sunny == 'yes' ) {
echo ' <img style="width: 20px;" src="' . plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/sunny.png' . '">';
}
?>
		                        </span>
		                        <span class="returning_customer" style="text-align: center;"><?php echo get_returning_customer( $order->phone ); ?></span>
		                        <span class="reservation_status" style="text-align: left;">
		                            <?php
    if ( count( $td_isfree ) != 0 ) {
            $get_status = 'itisnotfree';
            $get_status_class = 'itisnotfree';
            $for_status_button = 'VRIJ';
        } else {
            $get_status = 'itisfree';
            $get_status_class = 'itisfree';
            $for_status_button = 'BEZET';
        }
        ?>
		                            <span id="inoutstatus<?php echo $order->customer_id; ?>" class="in_out_status <?php echo $get_status_class; ?>"></span>
		                            <span style="margin-left: 10px;" class="app_count_t"><a href="#" status="<?php echo $get_status; ?>" parentid="inoutstatus<?php echo $order->customer_id; ?>" customerid= "<?php echo $order->customer_id; ?>" class="button button-small button-primary customer_in_out"><?php echo $for_status_button; ?></a></span>

		                        </span>

		                    </div>
		                </div>
		                <?php
endforeach;
    endif;
    ?>
        </div>

    </div>

<?php

    endif;

} //end time slot for loop ?>

</div>


    </form>
</div>

<?php
if ( !empty( $td_date ) ) {
    $tabledata = happy_get_table( $td_date );
    $tableList = ( !empty( $tabledata->tables ) && isset( $tabledata->tables ) ) ? unserialize( $tabledata->tables ) : get_table_list();
    $dbtable = ( !empty( $tabledata->id ) && isset( $tabledata->id ) ) ? $tabledata->id : '';
} else {
    $tableList = get_table_list();
    $dbtable = '';
}

?>

<!-- ****************************************** -->
<!-- ****************************************** -->

<div class="wrap inside" style="overflow: hidden;">
    <h1 class="wp-heading-inline"><?php _e( 'Restaurant ', 'tdrestaurant' );?></h1>

    <div class="tdcontainer tdsidearea">
            <div class="tdcontent boxstyle tdlefside">
            <div class="appointment_table" id="ajax_table_submit">
                <form action="" method="post">

        <table class="tableupdatebutton">
            <input type="hidden" name="appointmentdate" value="<?php echo $td_date; ?>">
            <input type="hidden" name="table_location" value="inside">
            <?php wp_nonce_field( 'happy_taslim_table' );?>
            <input type="hidden" name="action" value="submit_table_submit">

            <tr>
                <td>
                    <?php submit_button( __( 'MAAK RESERVATIE', 'tdrestaurant' ), 'primary button-hero tdbuttonr', 'submit_appointment', false );?>
                </td>
                </td>
            </tr>
        </table>

                    <div class="table-area" style="height:620px">
                <?php
$get_appointments = get_the_appointments();
// $serial_number = 0;
$previous_marge_table = '';
foreach ( $tableList as $table ) {


    $imgLink = plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/' . $table['image'];

    // $serial_number++;
    $get_appointment = get_the_appointment( array( 'tableid' => $table['tableid'], 'start_date' => $td_date ) );

    if ( isset( $get_appointment->tableid ) && in_array( $get_appointment->tableid, $td_isnotfree ) ) {
        $is_fullday = ';background:#F44336;';
    } else {
        $is_fullday = ';background: #4CAF50;';
    }

    /* disable table */
    if (in_array( $table['tableid'] , disable_table() ) ) {
        $is_fullday = ';background:#F44336;';
    }

    ?>
                        <?php
$table_serial_number = ( isset( $get_appointment->margetable ) && $get_appointment->margetable == 'yes' ) ? 'yes' : '';

    // if ($table_serial_number == 'yes' && $previous_marge_table != 'minus') {
    //    $taslim_table_number = $serial_number--;
    //    $previous_marge_table = 'minus';
    // }else {
    //     $taslim_table_number = $serial_number;
    // }
    ?>
                        <div class="table-chair <?php echo ( !empty( $table['tablefor'] ) ) ? $table['tablefor'] : ''; ?>" style="<?php echo ( !empty( $table['style'] ) ) ? $table['style'] : 'display: none;'; ?> <?php echo $is_fullday; ?> border-radius:10px;">


                            <label for="rtoggle<?php echo $table['tableid']; ?>">
                                <span class="table_serial"><?php echo $table['tableid']; ?></span>
                                <img src="<?php echo $imgLink; ?>">
                            </label>
                            <input type="checkbox" name="tablenumber[]" value="<?php echo $table['tableid']; ?>" id="rtoggle<?php echo $table['tableid']; ?>" class="visually-hidden addpeople" peopleClass="tdpeople<?php echo $table['tableid']; ?>">

                            <input type="checkbox" name="chairnumber[]" value="<?php echo $table['chair']; ?>" class="peoplenumber tdpeople<?php echo $table['tableid']; ?>" style="display: none;">
<?php echo reservation_info_in_table( $table['tableid'], $td_date); ?>

                        </div>

                        <?php }?>
                    </div>

                </form>
            </div>
        </div>

        <div class="tdsidbar tdrightside">
            <h3><?php echo wp_date( 'd/m/Y', $td_date ); ?></h3>

            <div class="time_slot">
                <ul>
                    <?php
$time_slot = '+30mins';
$start_time = strtotime( '12:00', $td_date );
$end_time = strtotime( '22:00', $td_date );
$alltimeslot = array();
$todaytotlalPeople = 0;

for ( $t = $start_time; $t <= $end_time; $t = strtotime( $time_slot, $t ) ) {

    if ( wp_date( "H:i" ) >= date( "H:i", $t ) && wp_date( "H:i" ) <= date( "H:i", strtotime( '+29mins', $t ) ) ) {
        $active_time = 'active_now';
    } else {
        $active_time = '';
    }

    $args = array(
        'reservation_start' => $t,
        'reservation_end'   => strtotime( '+29mins', $t ),
        'customer_name'     => '',
        'customer_phone'    => '',
    );

    $tdorder = get_all_today( $args );
    if ( count( $tdorder ) != 0 ) {
        $totlalPeople = 0;
        foreach ( $tdorder as $value ) {
            $totlalPeople += $value->quantity;
            $todaytotlalPeople += $value->quantity;
        }
    } else {
        $totlalPeople = 0;
    }
    ?>
                        <li class="<?php echo $active_time; ?>"><?php echo date( "H:i", $t ); ?> - <span> <strong><?php echo $totlalPeople; ?></strong></span> </li>
                       <?php }
?>
                </ul>
            </div>
                <?php echo 'Totaal: '.$todaytotlalPeople; ?>
        </div>

    </div>

</div>

<!-- ****************************************** -->

<div class="wrap outside">
    <h1 class="wp-heading-inline"><?php _e( 'Terras ', 'tdrestaurant' );?></h1>

    <div class="tdcontainer tdsidearea">
            <div class="tdcontent boxstyle tdlefside">
            <div class="appointment_table" id="ajax_table_submit">
                <form action="" method="post">

        <table class="tableupdatebutton">
            <input type="hidden" name="appointmentdate" value="<?php echo $td_date; ?>">
            <input type="hidden" name="table_location" value="outside">
            <?php wp_nonce_field( 'happy_taslim_table' );?>
            <input type="hidden" name="action" value="submit_table_submit">

            <tr>
                <td>
                    <?php submit_button( __( 'MAAK RESERVATIE', 'tdrestaurant' ), 'primary button-hero tdbuttonr', 'submit_appointment', false );?>
                </td>
                </td>
            </tr>
        </table>

                    <div class="table-area">
                <?php
$get_appointments = get_the_appointments();
// $serial_number = 0;
$previous_marge_table = '';
foreach ( $tableList as $table ) {

    $imgLink = plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/' . $table['image'];
    
    // $serial_number++;
    // $serial_number++;
    $get_appointment = get_the_appointment( array( 'tableid' => $table['tableid'], 'start_date' => $td_date ) );

    if ( isset( $get_appointment->tableid ) && in_array( $get_appointment->tableid, $td_isnotfree ) ) {
        $is_fullday = ';background:#F44336;';
    } else {
        $is_fullday = ';background: #4CAF50;';
    }

    /* disable table */
    if (in_array( $table['tableid'] , disable_table() ) ) {
        $is_fullday = ';background:#F44336;';
    }


    
    $is_allergie = ( isset( $tc_customerinf->allergie ) && $tc_customerinf->allergie == 'yes' ) ? '<img src="' . plugins_url( 'admin/images/', HAPPYTASLIM_FILE ) . 'gluten.png" style="width: 15px;height: 15px;margin-left: 3px;margin-top: 0px;">' : '';

    ?>
                        <?php
$table_serial_number = ( isset( $get_appointment->margetable ) && $get_appointment->margetable == 'yes' ) ? 'yes' : '';

    // if ($table_serial_number == 'yes' && $previous_marge_table != 'minus') {
    //    $taslim_table_number = $serial_number--;
    //    $previous_marge_table = 'minus';
    // }else {
    //     $taslim_table_number = $serial_number;
    // }
    ?>
                        <div class="table-chair <?php echo ( !empty( $table['tablefor'] ) ) ? $table['tablefor'] : ''; ?>" style="<?php echo ( !empty( $table['style'] ) ) ? $table['style'] : 'display: none;'; ?> <?php echo $is_fullday; ?> border-radius:10px;">


                            <label for="ttoggle<?php echo $table['tableid']; ?>">
                                <span class="table_serial"><?php echo $table['tableid']; ?></span>
                                <img src="<?php echo $imgLink; ?>">
                            </label>
                            <input type="checkbox" name="tablenumber[]" value="<?php echo $table['tableid']; ?>" id="ttoggle<?php echo $table['tableid']; ?>" class="visually-hidden addpeople" peopleClass="tdpeople<?php echo $table['tableid']; ?>">

                            <input type="checkbox" name="chairnumber[]" value="<?php echo $table['chair']; ?>" class="peoplenumber tdpeople<?php echo $table['tableid']; ?>" style="display: none;">

<?php echo reservation_info_in_table( $table['tableid'], $td_date); ?>
                        </div>

                        <?php }?>
                        <img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/trapje.png'?>" class="table_area_design">
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<div id="taslim_ajax_dispaly"></div>