<div class="wrap">
    <h1><?php _e( 'Add new appointment', 'tdrestaurant' );?></h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-customer-id">
                    <th scope="row">
                        <label for="customer_id"><?php _e( 'customer id', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="number" name="customer_id" id="customer_id" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-quantity">
                    <th scope="row">
                        <label for="quantity"><?php _e( 'quantity', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="number" name="quantity" id="quantity" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-payment">
                    <th scope="row">
                        <label for="payment"><?php _e( 'payment', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="payment" id="payment" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-due-payment">
                    <th scope="row">
                        <label for="due_payment"><?php _e( 'due_payment', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="due_payment" id="due_payment" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-status">
                    <th scope="row">
                        <label for="status"><?php _e( 'status', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <select name="status" id="status">
                            <option value="approved"><?php echo __( 'approved', 'tdrestaurant' ); ?></option>
                            <option value="pending"><?php echo __( 'pending', 'tdrestaurant' ); ?></option>
                            <option value="canceled"><?php echo __( 'canceled', 'tdrestaurant' ); ?></option>
                            <option value="rejected"><?php echo __( 'rejected', 'tdrestaurant' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="row-start-date">
                    <th scope="row">
                        <label for="start_date"><?php _e( 'start_date', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="start_date" id="start_date" class="regular-text custom_date" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-end-date">
                    <th scope="row">
                        <label for="end_date"><?php _e( 'end_date', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <input type="text" name="end_date" id="end_date" class="regular-text" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-info">
                    <th scope="row">
                        <label for="info"><?php _e( 'info', 'tdrestaurant' );?></label>
                    </th>
                    <td>
                        <textarea name="info" id="info"placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" rows="5" cols="30"></textarea>
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field( 'happy_taslim' );?>
        <?php submit_button( __( 'Add new appointment', 'tdrestaurant' ), 'primary', 'submit_appointment' );?>

    </form>
</div>