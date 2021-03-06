<?php

class taslimAjax {

    function __construct() {
        add_action( 'wp_ajax_submit_table_submit', [$this, 'submit_table_submit_enquiry'] );
        add_action( 'wp_ajax_nopriv_submit_table_submit', [$this, 'submit_table_submit_enquiry'] );
        add_action( 'wp_ajax_edit_reservation', [$this, 'edit_reservation_callback'] );
        add_action( 'wp_ajax_nopriv_edit_reservation', [$this, 'edit_reservation_callback'] );
        add_action( 'wp_ajax_taslim_appointment_submit', [$this, 'appointment_submit'] );
        add_action( 'wp_ajax_nopriv_taslim_appointment_submit', [$this, 'appointment_submit'] );
        add_action( 'wp_ajax_get_selected_end_time', [$this, 'appointment_get_selected_end_time'] );
        add_action( 'wp_ajax_nopriv_get_selected_end_time', [$this, 'appointment_get_selected_end_time'] );
        add_action( 'wp_ajax_update_reservation_status', [$this, 'update_reservation_status'] );
        add_action( 'wp_ajax_nopriv_update_reservation_status', [$this, 'update_reservation_status'] );
        add_action( 'wp_ajax_staff_update_reservation', [$this, 'staff_update_reservation'] );
        add_action( 'wp_ajax_nopriv_staff_update_reservation', [$this, 'staff_update_reservation'] );
        add_action( 'wp_ajax_staff_reservation_update', [$this, 'staff_reservation_update'] );
        add_action( 'wp_ajax_nopriv_staff_reservation_update', [$this, 'staff_reservation_update'] );
    }

    public function taslim_appoinment_form( $appointmentdate, $table_list, $customer_id, $chairnumber, $chairnumberlist, $table_location ) {

        $customerid = !empty( $customer_id ) ? $customer_id : '';
        //$chairnumber = !empty($chairnumber) ? array_sum($_REQUEST['chairnumber']) : '';

        if ( $customerid != '' ) {
            $customerinfo = happytaslim_get_customer( $customerid );
            $get_customer_appointment = get_appointments_table( $customerid );
        }

        $taslimReservation = array();
        if ( isset( $get_customer_appointment ) ) {
            foreach ( $get_customer_appointment as $value ) {
                $taslimReservation['quantity'] = $value->quantity;
                $taslimReservation['start_date'] = $value->start_date;
                $taslimReservation['end_date'] = $value->end_date;
                $taslimReservation['fullday'] = $value->fullday;
                $taslimReservation['birthday'] = $value->birthday;
                $taslimReservation['allergie'] = $value->allergie;
                $taslimReservation['sunny'] = $value->sunny;
                $taslimReservation['margetable'] = $value->margetable;
                $taslimReservation['status'] = $value->status;
                break;
            }
        }

        $customerName = ( isset( $customerinfo->last_name ) ) ? $customerinfo->last_name : '';
        $customerPhone = ( isset( $customerinfo->phone ) ) ? $customerinfo->phone : '';

        $tablenumber = explode( ',', $table_list );
        $multiple_number = ( count( $tablenumber ) > 1 ) ? 'yes' : 'no';

        //print_r($tablenumber);
        $is_fullday = array();
        if ( is_array( $tablenumber ) ) {
            foreach ( $tablenumber as $table ) {
                $get_appointment = get_the_appointment( array( 'tableid' => $table, 'start_date' => $appointmentdate ) );
            }
        }
        ob_start();
        ?>
        <div class="happytaslim_be_popup_form_wrap enable">
            <div class="happytaslim_be_popup_form_position_fixer">
                <div class="happytaslim_be_popup_form_bg">
                    <div class="bookmify_be_popup_form">
                        <?php if ( $customerid == '' ): ?>
                        <div class="happytaslim_be_popup_form_header">
                            <h3>Reeds gereserveerd</h3>
                            <span class="closer"></span>
                        </div>
                        <?php endif;?>
        <?php if ( $customerid == '' ):

            echo "<div class='td_popup_content' style='margin-bottom: 10px;''><table class=customerinfo>";
            echo "
		        <thead><tr>
		            <th>Naam</th>
		            <th>Telefoonnummer</th>
		            <th>Tijd</th>
		            <th>Tafelnummer(s)</th>
		        </tr></thead>";

            foreach ( $tablenumber as $table ) {

                $get_reservation_lits = get_appointments( array( 'tableid' => $table, 'start_date' => $appointmentdate ) );

                $reservation_count = 0;
                if ( !empty( $get_reservation_lits ) ):
                    foreach ( $get_reservation_lits as $get_reservation_lit ) {

                        $reservationName = isset( $get_reservation_lit->customer_id ) && !empty( $get_reservation_lit->customer_id ) ? get_the_customer( $get_reservation_lit->customer_id, 'last_name' ) : '';

                        $reservationPhone = isset( $get_reservation_lit->customer_id ) && !empty( $get_reservation_lit->customer_id ) ? get_the_customer( $get_reservation_lit->customer_id, 'phone' ) : '';

                        $reservations_s = isset( $get_reservation_lit->start_date ) && !empty( $get_reservation_lit->start_date ) ? date( 'H:i', strtotime( $get_reservation_lit->start_date ) ) : '';

                        $reservation_e = isset( $get_reservation_lit->end_date ) && !empty( $get_reservation_lit->end_date ) ? date( 'H:i', strtotime( $get_reservation_lit->end_date ) ) : '';

                        $reservation_stats = isset( $get_reservation_lit->status ) && !empty( $get_reservation_lit->status ) ? $get_reservation_lit->status : '';

                        $reservation_t = isset( $get_reservation_lit->tableid ) && !empty( $get_reservation_lit->tableid ) ? $get_reservation_lit->tableid : '';

                        $td_updateButton = sprintf( '<a href="%s" class="button button-small button-primary" title="%s">%s</a>', admin_url( 'admin.php?page=tdappointments&action=edit&out_side=' . $get_reservation_lit->table_location . '&id=' . $get_reservation_lit->customer_id ), $get_reservation_lit->customer_id, __( 'Bewerk', 'tdrestaurant' ), __( 'Bewerk', 'tdrestaurant' ) );

                        $td_deleteButton = sprintf( '<a href="%s" class="submitdelete button button-small button-primary" onclick="return confirm(\'Bent u zeker dat u deze reservering wenst te verwijderen?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdappointments&appdelete_action=capdelete_action&appdelete_action=yes&id=' . $get_reservation_lit->customer_id ), 'td-appointment-delete' ), $get_reservation_lit->customer_id, __( 'Verwijder', 'tdrestaurant' ), __( 'Verwijder', 'tdrestaurant' ) );

                        // $reservation_e_id = isset($get_reservation_lit->customer_id) && !empty($get_reservation_lit->customer_id) ? admin_url( 'admin.php?page=tdappointments&action=edit&id='.$get_reservation_lit->customer_id ) : '';

                        $reservation_count++;
                        echo "
				            <tr>
				            <td>{$reservationName}</td>
				            <td>{$reservationPhone}</td>
				            <td>{$reservations_s} to {$reservation_e}</td>
				            <td style='text-align: center;'>{$reservation_t}</td>
				            </tr>";

                    } //child loop

                endif;

                //  echo "
                // <tr>
                // <td>{$reservation_count} reservatie</td>
                // <td></td>
                // <td></td>
                // <td></td>
                // </tr>";

            } // main loop
            echo "</table></div>";
            ?>
		<?php endif;

        if ( $customerid == '' ) {

            $ftable_list = explode( ",", $table_list );

            if ( is_array( $ftable_list ) ) {
                foreach ( $ftable_list as $table ) {
                    $is_fullday = td_is_itfullday( $table, $appointmentdate );

                    $is_two_re = is_it_two_reservation_booking( $table, $appointmentdate );

                    if ( !empty( $is_two_re ) && $is_two_re == 'yes' ) {
                        $isfullday_booked = 'yes';
                        break;
                    }

                    
                    if ( isset( $is_fullday->fullday ) && $is_fullday->fullday == 'yes' ) {
                        $isfullday_booked = 'yes';
                        break;
                    }

                }
            }
        }

        if ( $isfullday_booked != 'yes' ):

        ?>

    <div class="happytaslim_be_popup_form_header">
        <h3><?php echo ( $customerid == '' ) ? 'Nieuwe reservatie' : 'Bewerk reservatie'; ?> <?php echo ' - ' . $table_list; ?></h3>
    </div>
                    <form id="ajaxappointment" action="" method="post" autocomplete="off">
                        <div class="happytaslim_be_popup_form_content">
                            <span class="taslim-loading"><img src="<?php echo esc_url( includes_url() . 'js/thickbox/loadingAnimation.gif' ); ?>" /></span>
                        <table class="appointmentfield">
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <label for="customername">Naam :</label>
                                        <input type="text" name="customername" id="customername" value="<?php echo $customerName; ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <label for="customerphone">Telefoonnummer :</label>
                                        <input type="text" name="customerphone" id="customerphone" value="<?php echo $customerPhone; ?>" class="datefield" >
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <label for="tpeople">Aantal personen :</label>
                                        <select name="tpeople" id="tpeople">
                                            <option value="">----</option>
                                            <?php
for ( $i = 0; $i < 20; $i++ ) {
            $is_checked = ( $i == ( isset( $taslimReservation['quantity'] ) ? $taslimReservation['quantity'] : 0 ) ) ? 'selected="selected"' : '';
            echo '<option value="' . $i . '" ' . $is_checked . '>' . $i . '</option>';
        }
        ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                    <table width="100%">
                                        <tr>

                                    <td>
                                        <label for="onedaybook">
                                        <input type="checkbox" name="onedaybook" <?php echo ( isset( $taslimReservation['fullday'] ) && $taslimReservation['fullday'] == 'yes' ) ? 'checked="checked"' : ''; ?> value="yes" id="onedaybook">
                                        Volgeboekt</label>
                                    </td>
                                    <td>
                                        <label for="birthday">
                                        <input type="checkbox" name="birthday" <?php echo ( isset( $taslimReservation['birthday'] ) && $taslimReservation['birthday'] == 'yes' ) ? 'checked="checked"' : ''; ?> value="yes" id="birthday">
                                        Verjaardag</label>
                                        <?php if ( $multiple_number == 'yes' ): ?>
                                        <input type="hidden" name="margetable" value="yes" id="margetable">
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <label for="allergieid">
                                        <input type="checkbox" name="allergie" <?php echo ( isset( $taslimReservation['allergie'] ) && $taslimReservation['allergie'] == 'yes' ) ? 'checked="checked"' : ''; ?> value="yes" id="allergieid">
                                        Allergie</label>
                                    </td>
                                    <td>
                                    <label for="sunnyid">
                                        <input type="checkbox" name="sunny" <?php echo ( isset( $taslimReservation['sunny'] ) && $taslimReservation['sunny'] == 'yes' ) ? 'checked="checked"' : ''; ?> value="yes" id="sunnyid">Zonnig</label>                                        
                                    </td>                                            
                                        </tr>
                                    </table>

                                </td>
                                </tr>

                                <tr class="timeslotarea">
                                    <td>
                                    <label for="time_slot" class="mg-top15">Aankomsttijd :</label>
                                    <select name="time_slot" id="time_slot">
                                        <option value="">----</option>
                                        <?php
//$remove_slots = array('15:15','15:30','15:45','16:00','16:15','16:30','16:45',);
        $remove_slots = array();
        $allslots = happy_time_slots();
        $slots = array_diff( $allslots, $remove_slots );
        foreach ( $slots as $slot ) {
            $is_checked = ( $slot == ( isset( $taslimReservation['start_date'] ) ? date( 'H:i', strtotime( $taslimReservation['start_date'] ) ) : 0 ) ) ? 'selected="selected"' : '';
            echo '<option value="' . $slot . '" ' . $is_checked . '>' . $slot . '</option>';
        }
        ?>
                                    </select>
                                    </td>

                                    <td>
                                    <label for="end_time_slot" class="mg-top15">Vertrektijd :</label>
                                    <select name="end_time_slot" id="end_time_slot">
                                        <option value="">----</option>
                                        <?php
//$remove_slots = array('15:15','15:30','15:45','16:00','16:15','16:30','16:45',);
        $remove_slots = array();
        $allslots = happy_time_slots_end();
        $slots = array_diff( $allslots, $remove_slots );
        $tdtcoutnt = 0;
        foreach ( $slots as $slot ) {
            $tdtcoutnt++;
            $is_checked = ( $slot == ( isset( $taslimReservation['end_date'] ) ? date( 'H:i', strtotime( $taslimReservation['end_date'] ) ) : 0 ) ) ? 'selected="selected"' : '';

            // if ( isset( $taslimReservation['end_date'] ) && $slot != date( 'H:i', strtotime( $taslimReservation['end_date'] ) ) ) {
            //     echo '<option value="' . date( 'H:i', strtotime( $taslimReservation['end_date'] ) ) . '" selected="selected">' . date( 'H:i', strtotime( $taslimReservation['end_date'] ) ) . '</option>';
            // }
            echo '<option value="' . $slot . '" ' . $is_checked . '>' . $slot . '</option>';
        }
        ?>
                                    </select>
                                    </td>
                                </tr>
                                <tr class="timeslotarea">
                                </tr>
                            </tbody>
                        </table>

                        </div>
                        <div class="happytaslim_be_popup_form_button">
                        <?php wp_nonce_field( 'happy_taslim_table' );?>
                        <input type="hidden" name="appointmentstats" value="approved">
                        <input type="hidden" name="customer_id" value="<?php echo $customerid; ?>">
                        <input type="hidden" name="chairnumberlist" value="<?php echo $chairnumberlist; ?>">
                        <input type="hidden" name="chairnumber" value="<?php echo $chairnumber; ?>">
                        <input type="hidden" name="table_location" value="<?php echo $table_location; ?>">
                        <input type="hidden" name="appointment_table" value="<?php echo $table_list; ?>">
                        <input type="hidden" name="appointment_date" value="<?php echo $appointmentdate; ?>">
                        <input type="hidden" name="action" value="taslim_appointment_submit">
                        <?php submit_button( __( 'Opslaan', 'tdrestaurant' ), 'save', 'submit_appointment_final', false );?>

                            <a class="cancel" href="#">Annuleer</a>
                        </div>

                        </form>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <?php
return ob_get_clean();
    }

    public function taslim_appoinment_message( $taslimMessage ) {

        $get_appointment = get_appointments( array( 'customer_id' => $taslimMessage->customer_id, 'start_date' => $taslimMessage->start_date, 'end_date' => $taslimMessage->end_date ) );

        $tableList = array();
        $pquantity = 0;

        foreach ( $get_appointment as $value ) {
            $tableList[] = $value->tableid;
            $pquantity += $value->quantity;
        }

        $reservation_e_id = isset( $taslimMessage->customer_id ) && !empty( $taslimMessage->customer_id ) ? admin_url( 'admin.php?page=tdappointments&action=edit&id=' . $taslimMessage->customer_id ) : '';

        ob_start();
        ?>

        <div class="happytaslim_be_popup_form_wrap enable">
            <div class="happytaslim_be_popup_form_position_fixer">
                <div class="happytaslim_be_popup_form_bg">
                    <div class="bookmify_be_popup_form">
                        <div class="happytaslim_be_popup_form_header">
                            <h3>Overzicht reservaties</h3>
                            <span class="closer"></span>
                        </div>
                        <div class="happytaslim_be_popup_form_content">
                            <table class=customerinfo>
                                <thead><tr>
                                    <th>Naam</th>
                                    <th>Telefoonnummer</th>
                                    <th>Tijd</th>
                                    <th>Tafelnummer(s)</th>
                                </tr></thead>

                                <tr>
                                <td><?php echo get_the_customer( $taslimMessage->customer_id, 'last_name' ); ?> </td>
                                <td><?php echo get_the_customer( $taslimMessage->customer_id, 'phone' ); ?> </td>
                                <td><?php echo wp_date( 'H:i', strtotime( $taslimMessage->start_date ) ); ?> to <?php echo wp_date( 'H:i', strtotime( $taslimMessage->end_date ) ); ?></td>
                                <td><?php echo implode( ', ', $tableList ); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="happytaslim_be_popup_form_button">
                            <a class="cancel" href="#">Annuleer</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
return ob_get_clean();
    }

    public function submit_table_submit_enquiry() {

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'happy_taslim_table' ) ) {
            wp_send_json_error( [
                'message' => 'Nonce verification failed!',
            ] );
        }

        // if ( !current_user_can( 'manage_options' ) ) {
        //     wp_send_json_error( [
        //         'message' => 'sorry sttaf can not edit',
        //     ] );
        // }

        $chairnumberlist = isset( $_REQUEST['chairnumber'] ) ? implode( ",", $_REQUEST['chairnumber'] ) : '';
        $chairnumber = isset( $_REQUEST['chairnumber'] ) ? array_sum( $_REQUEST['chairnumber'] ) : '';
        $table_location = isset( $_REQUEST['table_location'] ) ? sanitize_text_field( $_REQUEST['table_location'] ) : '';
        $tablenumber = isset( $_REQUEST['tablenumber'] ) ? $_REQUEST['tablenumber'] : '';
        $appointmentdate = isset( $_REQUEST['appointmentdate'] ) ? sanitize_text_field( $_REQUEST['appointmentdate'] ) : '';

       // $tdsubmittable = explode( ',', $tablenumber );


        $is_disablet = array_intersect( $tablenumber , disable_table());
        if (!empty($is_disablet )) {
            $error = __( 'Deze tafel werd manueel door u geblokkeerd.  Om verder te kunnen gaan, dient u eerst uw blokkering op te heffen.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        if ( empty( $tablenumber ) ) {
            wp_send_json_error( [
                'message' => __( 'Opgelet! U dient minimaal 1 fafel te selecteren.', 'tdrestaurant' ),
            ] );
        } else {
            $table_list = implode( ",", $tablenumber );
        }

        if ( is_array( $tablenumber ) ) {
            foreach ( $tablenumber as $table ) {

                $is_fullday = td_is_itfullday( $table, $appointmentdate );

                // if ( isset($is_fullday->fullday) && $is_fullday->fullday == 'yes' && $is_fullday->status == 'approved' ) {
                //     wp_send_json_success([
                //         'message' => $this->taslim_appoinment_message($is_fullday)
                //     ]);
                // }

            }
        }

        // if ( is_array( $tablenumber ) ) {
        //     foreach ( $tablenumber as $table) {
        //         $is_fullday = get_the_appointment(array('tableid' => $table, 'start_date' => $appointmentdate)) ;
        //         if ( isset($is_fullday->status) && $is_fullday->status == 'approved' ) {

        //            // $this->taslim_appoinment_message($is_fullday);

        //         }
        //     }
        // }

        wp_send_json_success( [
            'message' => $this->taslim_appoinment_form( $appointmentdate, $table_list, $customer_id = '', $chairnumber, $chairnumberlist, $table_location ),
        ] );
    }

    public function appointment_submit() {

        $errors = array();

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'happy_taslim_table' ) ) {
            wp_send_json_error( [
                'message' => 'Nonce verification failed!',
            ] );
        }

        if ( !current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'tdrestaurant' ) );
        }

        $getcustomer_id = isset( $_REQUEST['customer_id'] ) ? sanitize_text_field( $_REQUEST['customer_id'] ) : '';
        $chairnumber = isset( $_REQUEST['chairnumber'] ) ? sanitize_text_field( $_REQUEST['chairnumber'] ) : '';
        $chairnumberlist = isset( $_REQUEST['chairnumberlist'] ) ? sanitize_text_field( $_REQUEST['chairnumberlist'] ) : '';

        $table_location = isset( $_REQUEST['table_location'] ) ? sanitize_text_field( $_REQUEST['table_location'] ) : '';
        $customername = isset( $_REQUEST['customername'] ) ? sanitize_text_field( $_REQUEST['customername'] ) : '';
        $customerphone = isset( $_REQUEST['customerphone'] ) ? sanitize_text_field( $_REQUEST['customerphone'] ) : '';
        $quantity = isset( $_REQUEST['tpeople'] ) ? sanitize_text_field( $_REQUEST['tpeople'] ) : '';
        $fullday = isset( $_REQUEST['onedaybook'] ) ? sanitize_text_field( $_REQUEST['onedaybook'] ) : 'no';
        $birthday = isset( $_REQUEST['birthday'] ) ? sanitize_text_field( $_REQUEST['birthday'] ) : 'no';
        $allergie = isset( $_REQUEST['allergie'] ) ? sanitize_text_field( $_REQUEST['allergie'] ) : 'no';
        $sunny = isset( $_REQUEST['sunny'] ) ? sanitize_text_field( $_REQUEST['sunny'] ) : 'no';
        $margetable = isset( $_REQUEST['margetable'] ) ? sanitize_text_field( $_REQUEST['margetable'] ) : 'no';
        $appointment_table = isset( $_REQUEST['appointment_table'] ) ? sanitize_text_field( $_REQUEST['appointment_table'] ) : '';

        $status = isset( $_REQUEST['appointmentstats'] ) ? sanitize_text_field( $_REQUEST['appointmentstats'] ) : 'approved';

        $time_slot = isset( $_REQUEST['time_slot'] ) ? sanitize_text_field( $_REQUEST['time_slot'] ) : '';
        $end_time_slot = isset( $_REQUEST['end_time_slot'] ) ? sanitize_text_field( $_REQUEST['end_time_slot'] ) : '';
        $dateApp = isset( $_REQUEST['appointment_date'] ) ? sanitize_text_field( $_REQUEST['appointment_date'] ) : '';

        $confirm_save = isset( $_REQUEST['confirm_save'] ) ? sanitize_text_field( $_REQUEST['confirm_save'] ) : 'no';
        // data Rearage
        $timeAppStart = date( "H:i:s", strtotime( $time_slot ) );
        $timeAppEnd = date( "H:i:s", strtotime( $end_time_slot ) );
        $dateApp = date( "Y-m-d", $dateApp );
        $startDate = $dateApp . " " . $timeAppStart;
        $endDate = $dateApp . " " . $timeAppEnd;
        $startDate = date( "Y-m-d H:i:s", strtotime( $startDate ) );
        $endDate = date( "Y-m-d H:i:s", strtotime( $endDate ) );

        if ( empty( $customername ) ) {
            $error = __( 'Opgelet! U dient een naam in te geven.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }


        if ( empty( $customerphone ) ) {
            $error = __( 'Opgelet! U dient een telefoonnummer in te geven.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        if ( !is_numeric( $customerphone ) ) {
            $error = __( 'U kan geen spaties of andere tekens gebruiken in dit veld, enkel cijfers.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        if ( empty( $quantity ) ) {
            $error = __( 'Opgelet! U dient het aantal personen in te geven.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        //    $margetable
        $getAllselectedTable = explode( ',', $appointment_table );

        $tdoptions = get_option( 'tddisabletable' );
        $tdoptions_selectedt = isset( $tdoptions['table_plan_type'] ) ? esc_attr( $tdoptions['table_plan_type']) : '';
        if ( $tdoptions_selectedt == 'td_corona') {
            $tdc_custom_table = array( '12', '13' );
        } else {
            $tdc_custom_table = array( '19', '20' );

        }


        $tdctable_result = array_intersect( $getAllselectedTable, $tdc_custom_table );

        // &$quantity
        $skip_people_count_validation = 'no';
        if ( count( $tdctable_result ) >= 2 ) {
            if ( $quantity > 10 ) {
                $skip_people_count_validation = 'no';
            } else {
                $skip_people_count_validation = 'yes';
            }
        }

        if ( empty( $quantity <= $chairnumber ) && $skip_people_count_validation == 'no' ) {
            $error = __( 'Opgelet! Het is onmogelijk om meer dan ' . $chairnumber . ' personen toe te voegen.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        if ( empty( $time_slot ) ) {
            $error = __( 'Opgelet! U dient de aankomsttijd in te geven.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        if ( empty( $end_time_slot ) ) {
            $error = __( 'Opgelet! U dient de vertrektijd in te geven.', 'tdrestaurant' );
            wp_send_json_error( [
                'message' => $error,
            ] );
        }

        $tableList = '';
        if ( $errors ) {

            foreach ( $errors as $error ) {
                $tableList .= $error . '';
            }

            wp_send_json_error( [
                'message' => $tableList,
            ] );

        }

        // if ($fullday == 'yes' && empty($getcustomer_id)) {

        //     if (is_it_fullday_appointment( $appointment_table, $dateApp) == 'yes') {
        //         wp_send_json_error( [
        //             'message' => 'Opgelet! Deze tafel is reeds volgeboekt voor de gekozen datum.'
        //         ] );
        //      }
        // }

        if ( is_it_booked_same_time( $appointment_table, $startDate, $endDate ) == 'yes' && empty( $getcustomer_id ) && $confirm_save != 'yes' ) {
            wp_send_json_error( [
                'confirm' => 'yes',
                'message' => 'Opgelet! Deze tafel is reeds geboekt voor de gekozen tijd en datum.',
            ] );
        }

        $customerfields = array(
            'id'        => $getcustomer_id,
            'last_name' => $customername,
            'phone'     => $customerphone,
        );

        $customer_id = add_new_customer( $customerfields );

        if ( empty( $customer_id ) ) {
            $customer_id = $getcustomer_id;
        }

        $tablenumber = explode( ',', $appointment_table );
        $chairNumber = explode( ',', $chairnumberlist );

        if ( !empty( $getcustomer_id ) ) {
            global $wpdb;
            $get_customer_reservation_ids = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE customer_id = {$customer_id}" );

            $get_r_tables = array();
            foreach ( $get_customer_reservation_ids as $get_customer_reservation_id ) {
                $get_r_tables[] = $get_customer_reservation_id->tableid;
            }

            $diiferet_tables = array_diff( $get_r_tables, $tablenumber );
            if ( !empty( $diiferet_tables ) ) {
                foreach ( $diiferet_tables as $diiferet_table ) {
                    $wpdb->delete( $wpdb->prefix . 'happy_appointments', array( 'tableid' => $diiferet_table, 'customer_id' => $customer_id ) );
                }
            }
        }

        $alltablenumber = array_combine( $tablenumber, $chairNumber );
        if ( is_array( $alltablenumber ) ) {
            global $wpdb;

            foreach ( $alltablenumber as $table => $cpeople ) {

                $get_appointment_id = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE customer_id = {$customer_id} AND tableid = '{$table}'" );

                $app_id = ( isset( $get_appointment_id->id ) && !empty( $get_appointment_id->id ) ) ? sanitize_text_field( $get_appointment_id->id ) : null;

                $fields = array(
                    'id'             => $app_id,
                    'customer_id'    => $customer_id,
                    'quantity'       => $quantity,
                    'status'         => $status,
                    'fullday'        => $fullday,
                    'birthday'       => $birthday,
                    'allergie'       => $allergie,
                    'sunny'         => $sunny,
                    'margetable'     => $margetable,
                    'tableid'        => $table,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'chair'          => $cpeople,
                    'table_location' => $table_location,
                    'info'           => '',
                );

                $insert_id = add_new_appoinment( $fields );
            }
        }

        wp_send_json_success( [
            'message' => '<h3>De reservatie werd succesvol aangemaakt!</h3>',
        ] );
    }

    public function appointment_get_selected_end_time() {
        $time_slot = isset( $_REQUEST['time_slot'] ) ? strtotime( sanitize_text_field( $_REQUEST['time_slot'] ) ) : '';
        $time_slot = date( "H:i", strtotime( '+149mins', $time_slot ) );

        // if ($time_slot >= '22:00') {
        //     wp_send_json_error( [
        //         'message' => 'Sorry you can not select this time'
        //     ] );
        // }

        // $remove_slots = array('15:15','15:30','15:45','16:00','16:15','16:30','16:45',);
        $remove_slots = array();
        $allslots = happy_time_slots_end();
        $slots = array_diff( $allslots, $remove_slots );

        foreach ( $remove_slots as $rslot ) {
            if ( $time_slot == $rslot ) {
                wp_send_json_error( [
                    'message' => 'Sorry you can not select this time',
                ] );
            }
        }

        ob_start();?>

            <option selected="selected" value="">----</option>
            <?php
        $tdtcoutnt = 0;
        foreach ( $slots as $slot ) {
            $tdtcoutnt++;
            $is_checked = ( $slot == $time_slot ) ? 'selected="selected"' : '';
            if ( $slot != $time_slot && $tdtcoutnt == 1 ) {
                echo '<option value="' . $time_slot . '" selected="selected">' . $time_slot . '</option>';
            }
            echo '<option value="' . $slot . '" ' . $is_checked . '>' . $slot . '</option>';
        }
        ?>

            <?php
$endtimeslot = ob_get_clean();
        wp_send_json_success( [
            'message' => $endtimeslot,
        ] );
    }

// Update Reservation

    public function edit_reservation_callback() {

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'happy_update_appointment' ) ) {
            wp_send_json_error( [
                'message' => 'Nonce verification failed!',
            ] );
        }

        $customer_id = isset( $_REQUEST['customer_id'] ) ? $_REQUEST['customer_id'] : '';
        $chairnumberlist = isset( $_REQUEST['chairnumber'] ) ? $_REQUEST['chairnumber'] : '';
        $chairnumberlist = isset( $_REQUEST['chairnumber'] ) ? implode( ",", $_REQUEST['chairnumber'] ) : '';
        $chairnumber = isset( $_REQUEST['chairnumber'] ) ? array_sum( $_REQUEST['chairnumber'] ) : '';
        $table_location = isset( $_REQUEST['table_location'] ) ? sanitize_text_field( $_REQUEST['table_location'] ) : '';
        $tablenumber = isset( $_REQUEST['tablenumber'] ) ? $_REQUEST['tablenumber'] : '';
        $appointmentdate = isset( $_REQUEST['appointmentdate'] ) ? sanitize_text_field( $_REQUEST['appointmentdate'] ) : '';

        if ( empty( $tablenumber ) ) {
            wp_send_json_error( [
                'message' => __( 'Opgelet! U dient eerst een tafel te selecteren.', 'tdrestaurant' ),
            ] );
        } else {
            $table_list = implode( ",", $tablenumber );
        }

        wp_send_json_success( [
            'message' => $this->taslim_appoinment_form( $appointmentdate, $table_list, $customer_id, $chairnumber, $chairnumberlist, $table_location ),
        ] );
    }

    public function update_reservation_status() {

        $customer_id = isset( $_REQUEST['customer'] ) ? sanitize_text_field( $_REQUEST['customer'] ) : '';
        $status = isset( $_REQUEST['status'] ) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        global $wpdb;
        $get_reservations = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE customer_id = {$customer_id}" );

        foreach ( $get_reservations as $value ) {

            if ( $status == 'itisfree' ) {
                $wpdb->update( $wpdb->prefix . 'happy_appointments', array( 'customer_in' => 'yes', 'customer_out' => 'no' ), array( 'id' => $value->id ) );
            } elseif ( $status == 'itisnotfree' ) {
                $wpdb->update( $wpdb->prefix . 'happy_appointments', array( 'customer_in' => 'no', 'customer_out' => 'yes' ), array( 'id' => $value->id ) );
            } else {
                $wpdb->update( $wpdb->prefix . 'happy_appointments', array( 'customer_in' => null, 'customer_out' => null ), array( 'id' => $value->id ) );
            }

        }

        wp_send_json_success( [
            'message' => 'De reservatie werd bijgewerkt!',
        ] );
    }


    public function staff_update_reservation() {

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'staff_edit_reservation' ) ) {
            wp_send_json_error( [
                'message' => 'Nonce verification failed!',
            ] );
        }
        $customer_id = isset( $_REQUEST['customer_id'] ) ? $_REQUEST['customer_id'] : '';
        $tableLocation = isset( $_REQUEST['tableLocation'] ) ? $_REQUEST['tableLocation'] : '';
        $tableList = isset( $_REQUEST['tableList'] ) ? $_REQUEST['tableList'] : '';
        $rpeople = isset( $_REQUEST['rpeople'] ) ? $_REQUEST['rpeople'] : '';


        ob_start();
        ?>

        <div class="happytaslim_be_popup_form_wrap enable">
            <div class="happytaslim_be_popup_form_position_fixer">
                <div class="happytaslim_be_popup_form_bg">
                    <div class="bookmify_be_popup_form">
                        <div class="happytaslim_be_popup_form_header">
                            <h3>Overzicht reservaties</h3>
                            <span class="closer"></span>
                        </div>
                        <form id="staff_reservation_update" action="" method="post" autocomplete="off">
                            <div class="happytaslim_be_popup_form_content">

                            <table class="appointmentfield">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <label for="tpeople">Aantal personen :</label>
                                            <select name="tpeople" id="tpeople">
                                                <option value="">----</option>
                                        <?php
                                        for ( $i = 0; $i < 20; $i++ ) {
                                        $is_checked = ( $i == ( isset( $rpeople ) ? $rpeople : 0 ) ) ? 'selected="selected"' : '';
                                        echo '<option value="' . $i . '" ' . $is_checked . '>' . $i . '</option>';
                                        }
                                        ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <label for="tdappointment_table">Tables :</label>
                                        <input type="text" name="appointment_table" id="tdappointment_table" value="<?php echo $tableList; ?>" class="datefield">
                                        </td>
                                    </tr>                                
                                    <tr>
                                        <td>
                                            <label for="table_location" class="mg-top15">Location :</label>
                                            <?php $table_positon_list = array('inside' => 'Restaurant', 'outside' => 'Terras'); ?> 
                                            <select name="table_location" id="table_location">
                                            <?php
                                            foreach ($table_positon_list as $tdtabposition => $disvalue) {
                                            $is_checked = ( isset($tableLocation) && $tableLocation == $tdtabposition) ? 'selected="selected"' : '' ;
                                            echo '<option value="'.$tdtabposition.'" '.$is_checked.'>'.$disvalue.'</option>';
                                            }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                <tbody>
                            </table>

                        </div>
                        
                        <div class="happytaslim_be_popup_form_button">
                            <?php wp_nonce_field( 'staff_edit_table' );?>                      
                            <input type="hidden" name="old_tables" value="<?php echo $tableList; ?>">
                            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                            <input type="hidden" name="action" value="staff_reservation_update">
                            <?php submit_button( __( 'Opslaan', 'tdrestaurant' ), 'save', 'submit_staff_final', false );?>
                            <a class="cancel" href="#">Annuleer</a>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $td_content = ob_get_clean();

        wp_send_json_success([
            'message' => $td_content
        ]);
    }


    public function staff_reservation_update() {

        $errors = array();

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'staff_edit_table' ) ) {
            wp_send_json_error( [
                'message' => 'Nonce verification failed!',
            ] );
        }

        $customer_id = isset( $_REQUEST['customer_id'] ) ? $_REQUEST['customer_id'] : '';
        $tpeople = isset( $_REQUEST['tpeople'] ) ? $_REQUEST['tpeople'] : '';
        $table_location = isset( $_REQUEST['table_location'] ) ? $_REQUEST['table_location'] : '';
        $appointment_table = isset( $_REQUEST['appointment_table'] ) ? $_REQUEST['appointment_table'] : '';
        $old_tables = isset( $_REQUEST['old_tables'] ) ? $_REQUEST['old_tables'] : '';
        $rpeople = isset( $_REQUEST['rpeople'] ) ? $_REQUEST['rpeople'] : '';
        global $wpdb;
        $table_name = $wpdb->prefix . 'happy_appointments';

        $appointment_table_array = explode( ',', $appointment_table );
        $old_tables_array = explode( ',', $old_tables );

        $margetable = ( count( $appointment_table_array ) > 1 ) ? 'yes' : 'no';

        // echo "<pre>";
        // print_r($appointment_table_array);
        // echo "</pre>";

        $oldreservationData = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_appointments WHERE customer_id = %d', $customer_id ) );

        $ndfields = array(
            'customer_id'    => $oldreservationData->customer_id,
            'quantity'       => $tpeople,
            'status'         => 'approved',
            'fullday'        => $oldreservationData->fullday,
            'birthday'       => $oldreservationData->birthday,
            'allergie'       => $oldreservationData->allergie,
            'sunny'       => $oldreservationData->sunny,
            'margetable'     => $margetable,
            'start_date'     => $oldreservationData->start_date,
            'end_date'       => $oldreservationData->end_date,
            'chair'          => $oldreservationData->chair,
            'table_location' => $table_location,
            'info'           => '',
        );


        if ( empty( $appointment_table ) ) {
            wp_send_json_error( [
                'message' => 'Opgelet! U dient minimum 1 tafelnummer op te geven.',
            ] );
        }

        if ( !empty( $old_tables_array ) ) {
            foreach ( $old_tables_array as $diiferet_table ) {
                $wpdb->delete( $wpdb->prefix . 'happy_appointments', array( 'tableid' => $diiferet_table, 'customer_id' => $customer_id ) );
            }
        }

        foreach ($appointment_table_array as  $table) {
            $ndfields['tableid'] = $table;
            if ( $wpdb->insert( $table_name, $ndfields ) ) {
                //return $wpdb->insert_id;
            }
        }


        wp_send_json_success( [
            'message' => '<h3>De tafel werd succesvol verplaatst!</h3>',
        ] );


    }










}

new taslimAjax();