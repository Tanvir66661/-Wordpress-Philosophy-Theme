<?php 
 
 //Get appointment List

function happytaslim_get_all_table ( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'DESC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'happy_tables-all';
    $items     = wp_cache_get( $cache_key, 'happy_tables' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'happy_tables ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, 'happy_tables' );
    }

    return $items;
}


function happytaslim_get_table_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'happy_tables' );
}

function happytaslim_edit_table( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'happy_tables WHERE id = %d', $id ) );
}



if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}


/**
 * table-list -------------------------------------------------------------------------------------------------------------
 */

class Table_List extends \WP_List_Table {

    function __construct() {
        parent::__construct( [
            'singular' => 'contact',
            'plural'   => 'contacts',
            'ajax'     => false
        ] );
    }


    function no_items() {
        _e( 'No address found', 'tdrestaurant' );
    }


    public function get_columns() {
        return [
           // 'cb'         => '<input type="checkbox" />',
            'id'       => __( 'Date', 'tdrestaurant' ),
           // 'date'       => __( 'Date', 'tdrestaurant' ),
            'created_at' => __( 'Created Table', 'tdrestaurant' ),
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

            // case 'date':
            //     return wp_date( 'd-m-Y', strtotime( $item->date ) );

            case 'created_at':
                return wp_date( 'd-m-Y', strtotime( $item->created_date ) );

            default:
                //return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }


    public function column_id( $item ) {
        $actions = [];

        $actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=tdtable&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'tdrestaurant' ), __( 'Edit', 'tdrestaurant' ) );

       // $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=tdcustomerlist&cdelete_action=customer_account_delete&id=' . $item->id ), 'td-customer-delete' ), $item->id, __( 'Delete', 'tdrestaurant' ), __( 'Delete', 'tdrestaurant' ) );


        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=tdtable&action=edit&id=' . $item->id ), wp_date( 'd-m-Y', strtotime( $item->date ) ), $this->row_actions( $actions )
        );
    }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="customer_id[]" value="%d" />', $item->id
        );
    }


    public function prepare_items() {
        $column   = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [ $column, $hidden, $sortable ];

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        $this->items = happytaslim_get_all_table( $args );

        $this->set_pagination_args( [
            'total_items' => happytaslim_get_table_count(),
            'per_page'    => $per_page
        ] );
    }
}
