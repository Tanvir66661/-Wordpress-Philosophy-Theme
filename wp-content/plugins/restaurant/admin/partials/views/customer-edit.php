<div class="wrap">
    <h1><?php _e( 'Update Customer info', 'tdrestaurant' );?></h1>

    <?php if ( isset( $_GET['cupdate'] ) && $_GET['cupdate'] == 'success' ) {?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'De account werd succesvol verwijderd!', 'tdrestaurant' );?></p>
        </div>
    <?php }?>

    <?php $item = happytaslim_get_customer( $id );?>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-customer-id">
                    <th scope="row">
                        <label for="customer_name"><?php _e( 'Name', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="customer_name" id="customer_name" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="<?php echo esc_attr( $item->last_name ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-customer-id">
                    <th scope="row">
                        <label for="customer_phone"><?php _e( 'Phone', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="customer_phone" id="customer_phone" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="<?php echo esc_attr( $item->phone ); ?>" required="required" />
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">

        <?php wp_nonce_field( 'happy_taslim_edit_customer' );?>
        <?php submit_button( __( 'Update', 'tdrestaurant' ), 'primary', 'edit_customer' );?>

    </form>
</div>