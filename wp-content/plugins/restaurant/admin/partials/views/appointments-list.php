<style>
.row-actions {
    left: 0px;
}
</style>
<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e( 'Overzicht reservaties', 'tdrestaurant' );?></h1>

    <?php if ( isset( $_GET['appoinment-deleted'] ) && $_GET['appoinment-deleted'] == 'true' ) {?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'De reservatie werd succesvol verwijderd!', 'tdrestaurant' );?></p>
        </div>
    <?php }?>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
<?php
$list_table = new Appointments_List();
$list_table->prepare_items();
//$list_table->search_box( 'search', 'search_id' );
$list_table->views();
$list_table->display();
?>
    </form>
</div>