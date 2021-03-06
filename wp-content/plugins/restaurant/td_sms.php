<?php
function taslim_get_tomorrow_reservation() {

    $datetime = new DateTime('tomorrow');
    $startDate = wp_date( $datetime->format('Y-m-d') ) . ' 00:00:00';
    $endDate = wp_date( $datetime->format('Y-m-d') ) . ' 23:59:59'; 

    global $wpdb;
   // $get_reservation = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments WHERE  start_date BETWEEN '{$startDate}' AND '{$endDate}' " );

  	$get_reservation = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}happy_appointments INNER JOIN {$wpdb->prefix}happy_customer ON " . $wpdb->prefix . "happy_appointments.customer_id =" . $wpdb->prefix . "happy_customer.id WHERE end_date BETWEEN '{$startDate}' AND '{$endDate}' GROUP BY customer_id  " );

    if ( count($get_reservation) != 0 ) {
    	foreach ($get_reservation as $item) {
    		$sms_body = 'Beste klant, zoals afgesproken heten wij U van harte welkom op '.date( "d/m/Y", strtotime( $item->start_date ) ).' van '.date( "H:i", strtotime( $item->start_date ) ).' tot '.date( "H:i", strtotime( $item->end_date ) ).' bij Thais Restaurant Khamphet. Bij verlet graag een seintje op 0477210708 Mvg,Thais Restaurant Khamphet';
			//$email_subject = "sms received".time();
			//$sms_number = '32488078552';
			$sms_number = $item->phone;
			//$body_sms = 'this is from plugin';
			$sms_status = $item->sendsms;
			if (!empty($sms_number) && $sms_status != 1) :
			$smsurl = 'https://api.spryngsms.com/api/send.php?USERNAME=info@thais-restaurant.eu&SECRET=8bc5a4750205e1eb9bf833e6f0587eb34c5670246373c0cae9&REFERENCE=remainder&DESTINATION='.$sms_number.'&SENDER=teamdigital&BODY='.$sms_body.'&SERVICE=dev test&ROUTE=2167&ALLOWLONG=1';
			$response = wp_remote_get( $smsurl );
			$table_name = $wpdb->prefix.'happy_appointments';
			$wpdb->update( $table_name, array( 'sendsms' => $response['body'] ), array( 'customer_id' => $item->customer_id ) );
			endif;
			//wp_mail( 'chotovaia@gmail.com', $email_subject, $sms_body );
    	}
    } // end loop
}


add_action( 'init', 'taslim_send_sms_function', 10, 1 );
function taslim_send_sms_function() {
	if ( isset( $_GET['sendsms'] ) ) {
		taslim_get_tomorrow_reservation();
	}
}


//--------------------------------------------
