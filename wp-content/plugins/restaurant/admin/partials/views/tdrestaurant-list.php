<div class="wrap">
    <h2><?php _e( 'Appointments', 'tdrestaurant' ); ?> <a href="<?php echo admin_url( 'admin.php?page=tdrestaurant&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'tdrestaurant' ); ?></a></h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table = new tdrestaurant_list_table();
        $list_table->prepare_items();
        $list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>