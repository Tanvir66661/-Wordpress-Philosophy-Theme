<?php

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class Form_Handler {

    /**
     * Hook 'em all
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_form' ) );
        add_action( 'admin_init', array( $this, 'update_table_form' ) );
        add_action( 'admin_init', array( $this, 'handle_form_appointment' ) );
        add_action( 'admin_init', array( $this, 'table_delete' ) );
        add_action( 'admin_init', array( $this, 'tdcustomer_delete' ) );
        add_action( 'admin_init', array( $this, 'tdappointment_delete' ) );
        add_action( 'admin_init', array( $this, 'handle_form_edittable' ) );
    }

    /**
     * Handle the appointment new and edit form
     *
     * @return void
     */
    public function handle_form() {
        if ( !isset( $_POST['edit_customer'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'happy_taslim_edit_customer' ) ) {
            die( __( 'Are you cheating?', 'tdrestaurant' ) );
        }

        if ( !current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'tdrestaurant' ) );
        }

        $errors = array();
        $field_id = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;
        $page_url = admin_url( 'admin.php?page=tdcustomerlist&action=edit&id=' . $field_id );

        $customer_name = isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '';
        $customer_phone = isset( $_POST['customer_phone'] ) ? sanitize_text_field( $_POST['customer_phone'] ) : '';

        // some basic validation
        if ( !$customer_name ) {
            $errors[] = __( 'Error: customer name is required', 'tdrestaurant' );
        }

        if ( !$customer_phone ) {
            $errors[] = __( 'Error: phone number is required', 'tdrestaurant' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'last_name' => $customer_name,
            'phone'     => $customer_phone,
        );

        if ( $field_id ) {

            $fields['id'] = $field_id;
            $insert_id = happy_customer_update( $fields );

        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'cupdate' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'cupdate' => 'success' ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }

    public function update_table_form() {
        if ( !isset( $_POST['update_tables'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'taslim_table_updates' ) ) {
            die( __( 'Are you cheating?', 'tdrestaurant' ) );
        }

        if ( !current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'tdrestaurant' ) );
        }

        $edit_tableId = isset( $_POST['edit_tableId'] ) ? intval( $_POST['edit_tableId'] ) : 0;

        $is_defaulttable = isset( $_POST['defaultTable'] ) ? sanitize_text_field( $_POST['defaultTable'] ) : 'no';

        if ( isset( $_POST['tableid'] ) ) {

            $tableid = $_POST['tableid'];
            $tablefor = $_POST['tablefor'];
            $chair = $_POST['chair'];
            $image = $_POST['image'];
            $style = $_POST['style'];
            $max_loop = max( array_keys( $_POST['tableid'] ) );

            $arraysametable = array_diff_key( $tableid , array_unique( $tableid ) );

            if ( !empty($arraysametable) ) {
                wp_die( 'Table number '.implode(',', $arraysametable).' already exit' );
            }

            $get_tables = array();
            $serial_number = 1;
            for ( $i = 0; $i <= $max_loop; $i++ ) {

                $serial_number++;

                if ( !isset( $tableid[$i] ) ) {
                    continue;
                }

                $td_custom_table = array('5A','6A');
               // $tableid[$i] == $serial_number ;
               // //in_array( $tableid[$i] , $td_custom_table)
                if ( in_array( $tableid[$i] , $td_custom_table) ) {
                    $happy_tableid = $tableid[$i];
                    $serial_number--;
                } elseif (!empty($tableid[$i]) && $tableid[$i] == $serial_number) {
                    $happy_tableid = $tableid[$i];
                } else {
                    $happy_tableid = $serial_number;
                }

                $get_tables[] = array(
                    'tableid'  => $tableid[$i],
                    'tablefor' => $tablefor[$i],
                    'chair'    => $chair[$i],
                    'image'    => $image[$i],
                    'style'    => $style[$i],
                );
            }
        }

        $args = array(
            'tables' => serialize( $get_tables ),
            'date'   => wp_date( "Y-m-d", sanitize_text_field( $_POST['tableDate'] ) ),
            'default'   => $is_defaulttable,
        );

        // New or edit?
        if ( !$edit_tableId ) {

            $insert_id = add_new_tables( $args );

            if ( $is_defaulttable == 'yes' && empty(get_option( 'tddefault_table_id' )) ) {
                add_option( 'tddefault_table_id', $insert_id, '', 'yes' );
            }

        } else {

            $args['id'] = $edit_tableId;
            $insert_id = add_new_tables( $args );

            if ( $is_defaulttable == 'yes' ) {
                add_option( 'tddefault_table_id', $insert_id, '', 'yes' );
            }elseif ($is_defaulttable == 'no' && get_option( 'tddefault_table_id' ) == $insert_id ) {
                delete_option( 'tddefault_table_id' );
            } 

        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'cupdate' => 'error' ), admin_url( 'admin.php?page=tdrestaurant&action=tableedit&id=' . $insert_id ) );
        } else {
            $redirect_to = add_query_arg( array( 'cupdate' => 'success' ), admin_url( 'admin.php?page=tdrestaurant&id=' . $insert_id.'&td_date='.$_POST['tableDate']) );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }

    public function handle_form_appointment() {
        if ( !isset( $_POST['submit_appointment_date'] ) ) {
            return;
        }

        if ( isset( $_POST['submit_appointment_date'] ) && $_POST['submit_appointment_date'] == 'TERRAS' ) {
            $out_side = 'outside';
        } else {
            $out_side = 'inside';
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'happy_taslim_submit_appointment_date' ) ) {
            die( __( 'Are you cheating?', 'tdrestaurant' ) );
        }

        if ( !current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'tdrestaurant' ) );
        }

        $errors = array();
        $page_url = admin_url( 'admin.php?page=tdrestaurant' );
        $form_date = isset( $_POST['form_date'] ) ? sanitize_text_field( $_POST['form_date'] ) : '';
        $time_slot = isset( $_POST['time_slot'] ) ? sanitize_text_field( $_POST['time_slot'] ) : '';
        $converttime = strtotime( $form_date );
        $redirect_to = add_query_arg( array( 'td_date' => $converttime, 'out_side' => $out_side ), $page_url );

        wp_safe_redirect( $redirect_to );
        exit;

    }

    public function handle_form_edittable() {
        if ( !isset( $_POST['submit_edittable_date'] ) ) {
            return;
        }

        if ( isset( $_POST['submit_edittable_date'] ) && $_POST['submit_edittable_date'] == 'TERRAS' ) {
            $out_side = 'outside';
        } else {
            $out_side = 'inside';
        }

        $tableid = ( isset( $_POST['id'] ) ) ? sanitize_text_field( $_POST['id'] ) : '';

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'happy_taslim_submit_edit_table_date' ) ) {
            die( __( 'Are you cheating?', 'tdrestaurant' ) );
        }

        if ( !current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'tdrestaurant' ) );
        }

        $errors = array();
        $page_url = admin_url( 'admin.php?page=tdrestaurant&action=tableedit' );
        $form_date = isset( $_POST['form_date'] ) ? sanitize_text_field( $_POST['form_date'] ) : '';
        $converttime = strtotime( $form_date );

        $redirect_to = add_query_arg( array( 'td_date' => $converttime, 'id' => $tableid, 'out_side' => $out_side ), $page_url );

        wp_safe_redirect( $redirect_to );
        exit;

    }

    public function tdcustomer_delete() {

        if ( !isset( $_REQUEST['cdelete_action'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'td-customer-delete' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        if ( taslim_dbappointment_delete( $id ) ) {
            $redirected_to = admin_url( 'admin.php?page=tdcustomerlist&customer-deleted=true' );
        } else {
            $redirected_to = admin_url( 'admin.php?page=tdcustomerlist&customer-deleted=false' );
        }

        wp_redirect( $redirected_to );
        exit;
    }

    public function tdappointment_delete() {

        if ( !isset( $_REQUEST['appdelete_action'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'td-appointment-delete' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        $appdelete_action = isset( $_REQUEST['appdelete_action'] ) ? $_REQUEST['appdelete_action'] : '';

        if ( taslim_dbappointment_delete( $id ) ) {

            if($appdelete_action == 'yes'){
                $redirected_to = admin_url( 'admin.php?page=tdrestaurant&appoinment-deleted=true' );
            }else {
                $redirected_to = admin_url( 'admin.php?page=tdappointments&appoinment-deleted=true' );
            }

        } else {

            if($appdelete_action == 'yes'){
                $redirected_to = admin_url( 'admin.php?page=tdrestaurant&appoinment-deleted=false' );
            }else {
                $redirected_to = admin_url( 'admin.php?page=tdappointments&appoinment-deleted=false' );
            }

        }

        wp_redirect( $redirected_to );
        exit;
    }

    public function table_delete() {

        if ( !isset( $_REQUEST['resettableid'] ) ) {
            return;
        }

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id = isset( $_REQUEST['resettableid'] ) ? intval( $_REQUEST['resettableid'] ) : 0;
        $td_date = isset( $_REQUEST['td_date'] ) ? intval( $_REQUEST['td_date'] ) : 0;

        if ( db_delete_table( $id ) ) {
            $redirected_to = admin_url( 'admin.php?page=tdrestaurant&action=tableedit&td_date=' . $td_date );
        } else {
            $redirected_to = admin_url( 'admin.php?page=tdrestaurant&action=tableedit&td_date=' . $td_date );
        }

        wp_redirect( $redirected_to );
        exit;
    }

}

new Form_Handler();