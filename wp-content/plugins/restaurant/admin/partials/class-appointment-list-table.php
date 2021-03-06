<?php

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Customer-list -------------------------------------------------------------------------------------------------------------
 */

class Customer_List extends \WP_List_Table {

    function __construct() {
        parent::__construct( [
            'singular' => 'contact',
            'plural'   => 'contacts',
            'ajax'     => false,
        ] );
    }

    function no_items() {
        _e( 'No address found', 'tdrestaurant' );
    }

    public function get_columns() {
        return [
            // 'cb'         => '<input type="checkbox" />',
            'name'       => __( 'Naam', 'tdrestaurant' ),
            'phone'      => __( 'Telefoonnummer', 'tdrestaurant' ),
            'created_at' => __( 'Datum', 'tdrestaurant' ),
        ];
    }

    function get_sortable_columns() {
        $sortable_columns = [
            // 'name'       => [ 'name', true ],
            // 'created_at' => [ 'created_at', true ],
        ];

        return $sortable_columns;
    }

    // function get_bulk_actions() {
    //     $actions = array(
    //         'trash'  => __( 'Move to Trash', 'tdrestaurant' ),
    //     );

    //     return $actions;
    // }

    protected function column_default( $item, $column_name ) {

        switch ( $column_name ) {

        case 'name':
        //return $item->last_name;

        case 'phone':
            return $item->phone;

        case 'created_at':
            return wp_date( 'd/m/Y', strtotime( $item->created_date ) );

        default:
            //return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    public function column_name( $item ) {
        $actions = [];

        $actions['edit'] = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=tdcustomerlist&action=edit&id=' . $item->id ), $item->id, __( 'Bewerk', 'tdrestaurant' ), __( 'Bewerk', 'tdrestaurant' ) );

        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Bent u zeker dat u deze reservering wenst te verwijderen?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdcustomerlist&cdelete_action=customer_account_delete&id=' . $item->id ), 'td-customer-delete' ), $item->id, __( 'Verwijder', 'tdrestaurant' ), __( 'Verwijder', 'tdrestaurant' ) );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=tdcustomerlist&action=edit&id=' . $item->id ), $item->last_name, $this->row_actions( $actions )
        );
    }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="customer_id[]" value="%d" />', $item->id
        );
    }

    function extra_tablenav( $which ) {?>

       <?php if ( $which == "top" ): ?>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_name'] ) ) ? $_REQUEST['customer_name'] : ''; ?>" name="customer_name" placeholder="Naam">
                <input type="submit" name="name-submit" value="Zoek" class="button" >
            </p>
        </div>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_phone'] ) ) ? $_REQUEST['customer_phone'] : ''; ?>" name="customer_phone" placeholder="Telefoonnummer">
                <input type="submit" name="phone-submit" value="Zoek" class="button" >
            </p>
        </div>

        <?php endif;
    }

    public function prepare_items() {
        $column = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$column, $hidden, $sortable];

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        if ( isset( $_REQUEST['customer_name'] ) ) {
            $args['customer_name'] = $_REQUEST['customer_name'];
        }

        if ( isset( $_REQUEST['customer_phone'] ) ) {
            $args['customer_phone'] = $_REQUEST['customer_phone'];
        }

        $this->items = happytaslim_get_all_customer( $args );

        $this->set_pagination_args( [
            'total_items' => happytaslim_get_customer_count(),
            'per_page'    => $per_page,
        ] );
    }
}

/**
 * Customer-list -------------------------------------------------------------------------------------------------------------
 */

class Appointments_List extends \WP_List_Table {

    function __construct() {
        parent::__construct( [
            'singular' => 'contact',
            'plural'   => 'contacts',
            'ajax'     => false,
        ] );
    }

    function get_views() {
        $status_links = array(
            "all"       => __( "<a href='#'>All</a>", 'my-plugin-slug' ),
            "published" => __( "<a href='#'>Published</a>", 'my-plugin-slug' ),
            "trashed"   => __( "<a href='#'>Trashed</a>", 'my-plugin-slug' ),
        );
        //return $status_links;
    }

    function no_items() {
        _e( 'No address found', 'tdrestaurant' );
    }

    public function get_columns() {
        return [
            //'cb'         => '<input type="checkbox" />',
            'name'               => __( 'Klant', 'tdrestaurant' ),
            'phone'              => __( 'Telefoonnummer', 'tdrestaurant' ),
            'table_number'       => __( 'Tafelnummer', 'tdrestaurant' ),
            'person'             => __( 'Aantal personen', 'tdrestaurant' ),
            'time'               => __( 'Reservatie info', 'tdrestaurant' ),
            'returning_customer' => __( 'Vaste klant', 'tdrestaurant' ),
            'created_at'         => __( 'Gereserveerd op', 'tdrestaurant' ),
        ];
    }

    function get_sortable_columns() {
        $sortable_columns = [
            // 'created_at' => [ 'created_at', true ],
        ];

        return $sortable_columns;
    }

    // function get_bulk_actions() {
    //     $actions = array(
    //         'trash'  => __( 'Move to Trash', 'tdrestaurant' ),
    //     );

    //     return $actions;
    // }

    protected function column_default( $item, $column_name ) {

        switch ( $column_name ) {

        case 'phone':

            return $item->phone;

        case 'table_number':

            $alltable = get_appointments_table( $item->customer_id );

            $margeTable = array();
            foreach ( $alltable as $stable ) {
                $margeTable[] = $stable->tableid;
            }

            if ( count( $margeTable ) > 1 ) {
                return $item->tableid . ' - [' . implode( '+', $margeTable ) . ']';
            } else {
                return $item->tableid;
            }

        case 'person':
            $alltable = get_appointments_table( $item->customer_id );

            $totalchair = 0;
            foreach ( $alltable as $stable ) {
                $totalchair += $stable->chair;
            }

            return $item->quantity . '/' . $totalchair;

        case 'time':

            return date( 'd/m/Y', strtotime( $item->start_date ) ) . ' - ' . date( 'H:i', strtotime( $item->start_date ) ) . ' to ' . date( 'H:i', strtotime( $item->end_date ) );

        case 'returning_customer':
            return get_returning_customer( $item->phone );

        case 'created_at':
            return date( 'd/m/Y', strtotime( $item->created_date ) );

        default:
            //return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    public function column_name( $item ) {
        $actions = [];
        $actions['edit'] = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=tdappointments&action=edit&out_side=' . $item->table_location . '&id=' . $item->customer_id ), $item->customer_id, __( 'Bewerk', 'tdrestaurant' ), __( 'Bewerk', 'tdrestaurant' ) );

        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Bent u zeker dat u deze reservering wenst te verwijderen?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdappointments&appdelete_action=capdelete_action&id=' . $item->customer_id ), 'td-appointment-delete' ), $item->customer_id, __( 'Verwijder', 'tdrestaurant' ), __( 'Verwijder', 'tdrestaurant' ) );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=tdappointments&action=edit&id=' . $item->customer_id ), $item->last_name, $this->row_actions( $actions )
        );
    }

    // $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdcustomerlist&action=tdcustomerlist&id=' . $item->id ), 'tdcustomer-delete-address' ), $item->id, __( 'Delete', 'tdrestaurant' ), __( 'Delete', 'tdrestaurant' ) );

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="customer_id[]" value="%d" />', $item->id
        );
    }

    function extra_tablenav( $which ) {?>

       <?php if ( $which == "top" ): ?>


        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" name="reservation_date" id="selectdate" class="datefield notranslate" placeholder="Datum" value="<?php echo ( isset( $_REQUEST['reservation_date'] ) ) ? $_REQUEST['reservation_date'] : ''; ?>" autocomplete="off">
                <input type="submit" name="date-submit" value="Zoek" class="button" >
            </p>
        </div>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_name'] ) ) ? $_REQUEST['customer_name'] : ''; ?>" name="customer_name" placeholder="Naam">
                <input type="submit" name="name-submit" value="Zoek" class="button" >
            </p>
        </div>

        <div class="alignleft actions">
            <p class="search-box">
                <input type="text" value="<?php echo ( isset( $_REQUEST['customer_phone'] ) ) ? $_REQUEST['customer_phone'] : ''; ?>" name="customer_phone" placeholder="Telefoonnummer">
                <input type="submit" name="phone-submit" value="Zoek" class="button" >
            </p>
        </div>

        <?php endif;
    }

    public function prepare_items() {
        $column = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$column, $hidden, $sortable];

        $per_page = 50;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        if ( isset( $_REQUEST['customer_name'] ) ) {
            $args['customer_name'] = $_REQUEST['customer_name'];
        }

        if ( isset( $_REQUEST['customer_phone'] ) ) {
            $args['customer_phone'] = $_REQUEST['customer_phone'];
        }

        if ( isset( $_REQUEST['reservation_date'] ) ) {
            $args['reservation_date'] = $_REQUEST['reservation_date'];
        }

        $this->items = happytaslim_get_all_appointment( $args );

        $this->set_pagination_args( [
            'total_items' => happytaslim_get_appointment_count(),
            'per_page'    => $per_page,
        ] );
    }
}