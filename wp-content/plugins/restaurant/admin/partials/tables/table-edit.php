<?php
$td_date = isset( $_GET['td_date'] ) ? sanitize_text_field( $_GET['td_date'] ) : strtotime(wp_date('d-m-Y'));
$tableId = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
$table_plan = isset( $_GET['out_side'] ) ? sanitize_text_field( $_GET['out_side'] ) : 'inside';
$if_bsuccess = (isset( $_GET['cupdate'] ) && isset( $_GET['cupdate'] ) == 'success') ? 'Reservation' : 'Annuleer';

if (!empty($tableId)) {
    $tabledata = happytaslim_edit_table($tableId);
    $tableList =  (!empty($tabledata->tables)) ? unserialize($tabledata->tables) : get_table_list();
    $td_date =  ( !empty($tabledata->date ) && isset( $tabledata->date ) ) ? strtotime($tabledata->date) : '';
    $is_table_checked = ( !empty($tabledata->date ) && isset( $tabledata->date ) && $tabledata->default == 'yes' ) ? 'checked="checked"' : ''; 
}else {
    $tableList = get_table_list();
    $is_table_checked = '';
}  



?>

<div class="wrap <?php echo $table_plan; ?>">
    <h1 class="wp-heading-inline"><?php _e( 'Bijwerken tafelplan', 'tdrestaurant' ); ?></h1>

    <?php if ( isset( $_GET['cupdate'] ) && $_GET['cupdate'] == 'success' ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Tafelplan werd succesvol bijgewerkt!', 'tdrestaurant' ); ?></p>
        </div>
    <?php } ?>

    <div class="tdcontainer">
            <div class="tdcontent boxstyle">

            <div class="appointmentdate">
                <form action="" method="post" autocomplete="off">

                <table class="form-table" style="width: 910px;">
                    <tbody>
                        <tr>
                            <td><input type="text" name="form_date" id="selectdate" class="datefield notranslate" placeholder="<?php echo esc_attr( '', 'tdrestaurant' ); ?>" value="<?php echo wp_date('d-m-Y', $td_date); ?>" required="required" /></td>
                            <td>
                            <input type="hidden" name="id" value="<?php echo $tableId;?>">
                            <?php wp_nonce_field( 'happy_taslim_submit_edit_table_date' ); ?>
                            <?php submit_button( __( 'RESTAURANT', 'tdrestaurant' ), 'button button-primary', 'submit_edittable_date' ); ?>
                            </td>
                            <td>
                            <?php submit_button( __( 'TERRAS', 'tdrestaurant' ), 'button button-primary terras', 'submit_edittable_date', false ); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </form>
            </div>  

            <div class="appointment_table">

        <div class="table-iteam">
                <ul>
                    <li class="tablelist" tableNumber="2" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-vierkant-2.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-vierkant-2.png'?>"></li>

                    <li class="tablelist" tableNumber="2" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-vierkant-2v.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-vierkant-2v.png'?>"></li>

                    <li class="tablelist" tableNumber="4" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-vierkant-4.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-vierkant-4.png'?>"></li>

                    <li class="tablelist" tableNumber="4" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-vierkant-4v.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-vierkant-4v.png'?>"></li>

                    <li class="tablelist" tableNumber="4" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-rond-4.png" ><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-rond-4.png'?>"></li>

                    <li class="tablelist" tableNumber="5" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-rond-5.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-rond-5.png'?>"></li>

                    <li class="tablelist" tableNumber="6" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-rond-6.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-rond-6.png'?>"></li>

                    <li class="tablelist" tableNumber="7" tablefor="<?php echo $table_plan; ?>" tableimg="tafel-rond-7.png"><img src="<?php echo plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/tafel-rond-7.png'?>"></li>
                </ul>
        </div>

                <form action="" method="post" id="tdedittablef">
                    <div id="table_section" class="table-area">    
                    <?php                     
					$serial_number = 0;
                    foreach ($tableList as $table) {  $serial_number++; 
                        $imgLink = plugin_dir_url( HAPPYTASLIM_FILE ).'admin/images/'.$table['image'];
                    ?>
                    
                        <div class="table-chair <?php echo (!empty($table['tablefor'])) ? $table['tablefor'] : ''; ?> edit_table_position" style="<?php echo (!empty($table['style'])) ? $table['style'] : 'display: none;'; ?>">
							
                            <input type="hidden" name="tablefor[]" value="<?php echo $table['tablefor']; ?>">
							<input type="hidden" name="image[]" value="<?php echo $table['image']; ?>">
							<input type="hidden" name="style[]" class="table_style" value="<?php echo $table['style']; ?>">

                                <span class="table_remove">X</span>
                                <span class="table_serial"><input type="text" name="tableid[]" value="<?php echo $table['tableid']; ?>"><input type="text" name="chair[]" value="<?php echo $table['chair']; ?>" style="margin-top: 6px;"></span>
                                <img src="<?php echo $imgLink;?>">
                            
                        </div>
                    <?php } ?>
                    </div>

                    <input type="hidden" name="edit_tableId" value="<?php echo $tableId; ?>">
                    <input type="hidden" name="tableDate" value="<?php echo $td_date; ?>">
                    <?php wp_nonce_field( 'taslim_table_updates' ); ?>
                    <input type="hidden" name="action" value="submit_update_tables">
                    <table class="tableupdatebutton">
                        <?php /*
                    <?php if( empty(get_option( 'tddefault_table_id' )) || get_option( 'tddefault_table_id' ) == $tableId ): ?>
                        <tr>
                            <td><label for="defaultTable" style="margin-bottom: 20px;display: block;"><input type="checkbox" name="defaultTable" <?php echo $is_table_checked; ?>  value="yes" id="defaultTable">Make this table as a default plan</label></td>
                        </tr>
                    <?php endif; ?>*/ ?>
                        <tr>
                            <td>
                                <div class="button-group tdbutong">
                                    <a href="<?php echo admin_url( 'admin.php?page=tdrestaurant&id='.$tableId.'&td_date='.$td_date); ?>" class="button button-primary button-hero">Annuleer</a>
                                    <a href="<?php echo admin_url( 'admin.php?page=tdrestaurant&action=tableedit&id='.$tableId.'&td_date='.$td_date); ?>" class="button button-primary button-hero">Herstel</a>
                                    <a href="<?php echo admin_url( 'admin.php?page=tdrestaurant&action=tableedit&id='.$tableId.'&resettableid='.$tableId.'&td_date='.$td_date); ?>" onclick="return confirm('Bent u zeker dat u deze reservering wenst te verwijderen?');" class="button button-primary button-hero">Tafelplan (standaard)</a>
                                </div>            
                            </td>
                            <td>
                                <?php submit_button( __( 'Opslaan', 'tdrestaurant' ), 'primary button-hero', 'update_tables', false ); ?>
                            </td>
                        </tr>
                    </table>
                    
                </form>
            </div>
        </div>
    </div>

</div>