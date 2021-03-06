<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e( 'Tables', 'tdrestaurant' ); ?></h1>

    <?php if ( isset( $_GET['appoinment-deleted'] ) && $_GET['appoinment-deleted'] == 'true' ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Appoinment has been deleted successfully!', 'tdrestaurant' ); ?></p>
        </div>
    <?php } ?>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table = new Table_List();
        $list_table->prepare_items();
        //$list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>