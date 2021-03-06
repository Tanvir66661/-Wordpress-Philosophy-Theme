<?php
$td_date = isset( $_GET['td_date'] ) ? sanitize_text_field( $_GET['td_date'] ) : strtotime( wp_date( 'd-m-Y' ) );
$time_slot = isset( $_GET['time_slot'] ) ? sanitize_text_field( $_GET['time_slot'] ) : '';
$table_plan = isset( $_GET['out_side'] ) ? sanitize_text_field( $_GET['out_side'] ) : 'inside';
$tds_date = isset( $_REQUEST['tds_date'] ) ? strtotime( sanitize_text_field( $_REQUEST['tds_date'] ) ) : strtotime( wp_date( 'd-m-Y' ) );
$customer_name = isset( $_REQUEST['customer_name'] ) ? sanitize_text_field( $_REQUEST['customer_name'] ) : '';
$customer_phone = isset( $_REQUEST['customer_phone'] ) ? sanitize_text_field( $_REQUEST['customer_phone'] ) : '';
//echo date('d-m-Y', $td_date);

if ( !empty( $td_date ) ) {
    $tabledata = happy_get_table( $td_date );
    $tableList = ( !empty( $tabledata->tables ) && isset( $tabledata->tables ) ) ? unserialize( $tabledata->tables ) : get_table_list();

    $dbtable = ( !empty( $tabledata->id ) && isset( $tabledata->id ) ) ? $tabledata->id : '';
} else {
    $tableList = get_table_list();
    $dbtable = '';
}
?>

<div class="wrap <?php echo $table_plan; ?>">
    <h1><?php _e( 'Nieuwe reservatie toevoegen', 'tdrestaurant' );?></h1>

    <?php if ( isset( $_GET['appoinment-deleted'] ) && $_GET['appoinment-deleted'] == 'true' ) {?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'De reservatie werd succesvol verwijderd!', 'tdrestaurant' );?></p>
        </div>
    <?php }?>

    <form method="post">
        <input type="hidden" name="page" value="tdrestaurant">
        <div class="tablenav top">
            <div class="alignleft">
                <p class="searchname">
                    <input type="hidden" name="tds_date" value="<?php echo wp_date( 'd-m-Y', $td_date ); ?>" autocomplete="off">
                    <input type="text" value="<?php echo ( isset( $_REQUEST['customer_name'] ) ) ? $_REQUEST['customer_name'] : ''; ?>" name="customer_name" placeholder="Naam">
                    <input type="submit" name="name-submit" value="Zoek" class="button">
                </p>
            </div>

            <br class="clear">
        </div>
    </form>
<?php

if ( isset( $_REQUEST['customer_phone'] ) || isset( $_REQUEST['customer_name'] ) || isset( $_REQUEST['customer_phone'] ) ): ?>

<?php
$args = array(
    'reservation_start' => strtotime( wp_date( 'Y-m-d', $tds_date ) . '00:00:00' ),
    'reservation_end'   => strtotime( wp_date( 'Y-m-d', $tds_date ) . '23:59:59' ),
    'customer_name'     => $customer_name,
    'customer_phone'    => $customer_phone,
);
$tdorder = get_all_today( $args );
?>
<?php if ( !empty( $tdorder ) ): ?>

<table class="wp-list-table widefat fixed striped contacts">
    <thead>
    <tr>
        <th scope="col" id="name" class="manage-column column-name column-primary">Klant</th>
        <th scope="col" id="phone" class="manage-column column-phone">Telefoonnummer</th>
        <th scope="col" id="table_number" class="manage-column column-table_number">Tafelnummer</th>
        <th scope="col" id="person" class="manage-column column-person">Aantal personen</th>
        <th scope="col" id="time" class="manage-column column-time" style="width: 170px;">Reservatie info</th>
        <th scope="col" id="returning_customer" class="manage-column column-returning_customer">Vaste klant</th><th scope="col" id="created_at" class="manage-column column-created_at">Gereserveerd op</th>
        <th style="width: 160px;"></th>
    </tr>
    </thead>

    <tbody id="the-list" data-wp-lists="list:contact">
        <?php foreach ( $tdorder as $orderdata ) { 
            
            $td_updateButton = sprintf( '<a href="%s" class="button button-primary" title="%s">%s</a>', admin_url( 'admin.php?page=tdappointments&action=edit&out_side=' . $orderdata->table_location . '&id=' . $orderdata->customer_id ), $orderdata->customer_id, __( 'Bewerk', 'tdrestaurant' ), __( 'Bewerk', 'tdrestaurant' ) );

            $td_deleteButton = sprintf( '<a href="%s" class="submitdelete button button-primary" style="margin-left:10px;" onclick="return confirm(\'Bent u zeker dat u deze reservering wenst te verwijderen?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdappointments&appdelete_action=capdelete_action&appdelete_action=yes&id=' . $orderdata->customer_id ), 'td-appointment-delete' ), $orderdata->customer_id, __( 'Verwijder', 'tdrestaurant' ), __( 'Verwijder', 'tdrestaurant' ) );            
            
        ?>
            <tr>
                <td class="name column-name has-row-actions column-primary" data-colname="Klant">
                    <strong><?php echo $orderdata->last_name ?></strong>

                    <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>

                </td>
                <td class="phone column-phone" data-colname="Telefoonnummer"><?php echo $orderdata->phone ?></td>
                <td class="table_number column-table_number" data-colname="Tafelnummer">
<?php
    $alltable = get_appointments_table( $orderdata->customer_id );
    $margeTable = array();
    foreach ( $alltable as $stable ) {
        $margeTable[] = $stable->tableid;
    }
    if ( count( $margeTable ) > 1 ) {
        echo $orderdata->tableid . ' - [' . implode( '+', $margeTable ) . ']';
    } else {
        echo $orderdata->tableid;
    }
?>
                </td>
                <td class="person column-person" data-colname="Aantal personen"><?php echo $orderdata->quantity; ?></td>
                <td class="time column-time" data-colname="Reservatie info">
                    <?php echo date( 'd/m/Y', strtotime( $orderdata->start_date ) ) . ' - ' . date( 'H:i', strtotime( $orderdata->start_date ) ) . ' to ' . date( 'H:i', strtotime( $orderdata->end_date ) ); ?>
                </td>
                <td class="returning_customer column-returning_customer" data-colname="Vaste klant"><?php echo get_returning_customer( $orderdata->phone ); ?></td>
                <td class="created_at column-created_at" data-colname="Gereserveerd op"><?php echo date( 'd/m/Y', strtotime( $orderdata->created_date ) ); ?></td>
                
                <td><?php echo $td_updateButton; ?> <?php echo $td_deleteButton; ?></td>
            </tr>
        <?php }?>
    </tbody>
</table>
<?php else: ?>
    Geen reservering gevonden!
<?php endif;?>
<?php endif;?>


    <div class="tdcontainer">
        <div class="appointmentdate">
            <form action="" method="post" autocomplete="off">

            <table class="form-table">
                <tbody>
                    <tr>
                        <td><input type="text" name="form_date" id="selectdate" class="datefield notranslate" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="<?php echo wp_date( 'd-m-Y', $td_date ); ?>" required="required" /></td>
                        <?php wp_nonce_field( 'happy_taslim_submit_appointment_date' );?>
                        <td>
                        <?php submit_button( __( 'RESTAURANT', 'tdrestaurant' ), 'button button-primary', 'submit_appointment_date', false );?>
                        </td>
                        <td>
                        <?php submit_button( __( 'TERRAS', 'tdrestaurant' ), 'button button-primary terras', 'submit_appointment_date', false );?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
    <div class="mobilescrol">
    <div class="tdcontainer tdsidearea">
            <div class="tdcontent boxstyle tdlefside">
            <div class="appointment_table" id="ajax_table_submit">
                <form action="" method="post">

                    <table class="tableupdatebutton">
                        <tr>
                            <td>
                                <a href="<?php echo admin_url( 'admin.php?page=tdrestaurant&action=tableedit&id=' . $dbtable . '&td_date=' . $td_date . '&out_side=' . $table_plan ); ?>" class="button button-primary button-hero tdbuttonr" style="background: #009688;border-color: #009688;">Wijzig plan</a>
                            </td>
                            <td>
                                <?php submit_button( __( 'Doorgaan', 'tdrestaurant' ), 'primary button-hero tdbuttonr', 'submit_appointment', false );?>
                            </td>
                            </td>
                        </tr>
                    </table>
<?php $table_height  = ($table_plan == 'inside') ? 'style="height:620px;overflow: hidden;"' : '' ;?>                    
                    <div class="table-area" <?php echo $table_height;?> >
                <?php
$get_appointments = get_the_appointments();
// $serial_number = 0;
$previous_marge_table = '';
foreach ( $tableList as $table ) {


    $imgLink = plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/' . $table['image'];

    // $serial_number++;
    $get_appointment = get_the_appointment( array( 'tableid' => $table['tableid'], 'start_date' => $td_date ) );

    $isitfullday = td_is_itfullday( $table['tableid'], $td_date );

    $is_booked = ( isset( $get_appointment->status ) && $get_appointment->status == 'approved' ) ? ';background:#FF9800;' : ';background: #4CAF50;';

    $is_it_two = is_it_two_reservation( $table['tableid'], $td_date );

    if ( empty( $is_it_two ) ) {
        $is_fullday = ( isset( $isitfullday->fullday ) && $isitfullday->fullday == 'yes' && isset( $get_appointment->status ) && $get_appointment->status == 'approved' ) ? ';background: #F44336;' : $is_booked;
    } else {
        $is_fullday = ';background:#F44336;';
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

                            <label for="toggle<?php echo $table['tableid']; ?>">
                                <span class="table_serial"><?php echo $table['tableid']; ?></span>

                                <img src="<?php echo $imgLink; ?>">
                            </label>

                            <input type="checkbox" name="tablenumber[]" value="<?php echo $table['tableid']; ?>" id="toggle<?php echo $table['tableid']; ?>" class="visually-hidden addpeople" peopleClass="tdpeople<?php echo $table['tableid']; ?>">

                            <input type="checkbox" name="chairnumber[]" value="<?php echo $table['chair']; ?>" class="peoplenumber tdpeople<?php echo $table['tableid']; ?>" style="display: none;">

                            <?php echo reservation_info_in_table( $table['tableid'], $td_date); ?>

                        </div>

                        <?php }?>
                        <img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/trapje.png'?>" class="table_area_design">
                    </div>

                    <input type="hidden" name="appointmentdate" value="<?php echo $td_date; ?>">
                    <input type="hidden" name="table_location" value="<?php echo $table_plan; ?>">
                    <?php wp_nonce_field( 'happy_taslim_table' );?>
                    <input type="hidden" name="action" value="submit_table_submit">
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

</div>

<div id="taslim_ajax_dispaly"></div>