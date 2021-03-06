<?php
$reservation = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
$get_appointmen_date = get_appointment_date( $reservation );

$table_plan = isset( $_GET['out_side'] ) ? sanitize_text_field( $_GET['out_side'] ) : 'inside';

$get_app_date = '';
foreach ( $get_appointmen_date as $value ) {
    $get_app_date = date( 'd-m-Y', strtotime( $value->start_date ) );
    break;
}

$if_edit = ( !empty( $get_app_date ) ? strtotime( $get_app_date ) : strtotime( wp_date( 'd-m-Y' ) ) );

$td_date = isset( $_GET['td_date'] ) ? sanitize_text_field( $_GET['td_date'] ) : $if_edit;

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
    <h1><?php _e( 'Reservatie', 'tdrestaurant' );?></h1>
    <div class="mobilescrol">
    <div class="tdcontainer tdsidearea">
            <div class="tdcontent boxstyle tdlefside">

            <div class="appointment_table" id="ajax_update_appointment">
                <form action="" method="post">
                    <div class="table-area">
                    <?php

// $serial_number = 0;
$previous_marge_table = '';
foreach ( $tableList as $table ) {
    // $serial_number++;

    $imgLink = plugin_dir_url( HAPPYTASLIM_FILE ) . 'admin/images/' . $table['image'];

    $get_appointment = get_the_appointment( array( 'tableid' => $table['tableid'], 'start_date' => $td_date ) );

    $get_customer_table = get_edit_appointment( $table['tableid'], $reservation );

    $is_booked = ( isset( $get_appointment->status ) && $get_appointment->status == 'approved' ) ? ';background:#FF9800;' : ';background: #4CAF50;';

    $isitfullday = td_is_itfullday( $table['tableid'], $td_date );

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

    $is_checked = ( isset( $get_customer_table->customer_id ) && $get_customer_table->customer_id == $reservation ) ? "checked='checked'" : '';
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
                            <input type="checkbox" name="tablenumber[]" value="<?php echo $table['tableid']; ?>" <?php echo $is_checked; ?> id="toggle<?php echo $table['tableid']; ?>" class="visually-hidden addpeople" peopleClass="tdpeople<?php echo $table['tableid']; ?>">

                            <input type="checkbox" name="chairnumber[]" value="<?php echo $table['chair']; ?>" <?php echo $is_checked; ?> class="peoplenumber tdpeople<?php echo $table['tableid']; ?>" style="display: none;">

                            <?php echo reservation_info_in_table( $table['tableid'], $td_date); ?>

                        </div>

                        <?php }?>
                    </div>

                    <input type="hidden" name="customer_id" value="<?php echo $reservation; ?>">
                    <input type="hidden" name="appointmentdate" value="<?php echo $td_date; ?>">
                    <input type="hidden" name="table_location" value="<?php echo $table_plan; ?>">
                    <?php wp_nonce_field( 'happy_update_appointment' );?>
                    <input type="hidden" name="action" value="edit_reservation">
                    <br>
                    <?php submit_button( __( 'Bijwerken', 'tdrestaurant' ), 'primary button-hero', 'update_appointment', false );?>
                    <a href="<?php echo admin_url( 'admin.php?page=tdappointments' ); ?>" class="button button-primary button-hero" style="margin-left: 20px;">Annuleren</a>
                </form>
            </div>
        </div>
    </div>
    </div>
<?php
/*
<div class="tdsidbar boxstyle">

<div class="customer-info">
<h3 style=" margin: 0px 0px 15px 0px;">Detail Reservatie</h3>
<?php
if (!empty( $reservation )) {
$tcrinfo = get_appointment_date($reservation);
$ctableList= array();
$start_date= '';
$end_date= '';
foreach ($tcrinfo as $value) {
$ctableList[] = $value->tableid;
$start_date = $value->start_date;
$end_date = $value->end_date;
}
echo '<p> <strong>Naam : </strong>'.get_the_customer( $reservation, 'last_name').'<p>';
echo '<p> <strong>Tel. : </strong>'.get_the_customer( $reservation, 'phone').'<p>';
echo '<p> <strong>Tafelnummer(s) : </strong>'.implode(', ', $ctableList).'<p>';
echo '<p> <strong>Tijd : </strong>'.wp_date('H:i', strtotime($start_date)).' to '.wp_date('H:i', strtotime($end_date)).'<p>';
echo '<p> <strong>Datum : </strong>'.wp_date('d/m/Y', strtotime($start_date)).'<p>';
}
?>
</div>
</div>
 */
?>

</div>

<div id="taslim_ajax_dispaly"></div>
