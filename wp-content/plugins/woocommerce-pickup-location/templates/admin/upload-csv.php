<div class="wrap">

<?php if(isset($wp_pkpo_msg) && ($wp_pkpo_msg!='')) { ?>
<div class="updated notice">
    <p><?php echo $wp_pkpo_msg;?></p>
</div>
<?php } ?>

    <h1 id="add-csv"><?php _e('Upload CSV', 'woocommerce-pickup-location'); ?></h1>
    <div id="ajax-response"></div>

    <p><?php _e('Upload pickup location csv file', 'woocommerce-pickup-location'); ?></p>
    <form method="post" name="" id="" class="" novalidate="novalidate" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="user_login"><?php _e('Pickup Location CSV file', 'woocommerce-pickup-location'); ?></label></th>
                    <td><input name="wp_pkpo_csv_file" id="wp_pkpo_csv_file" value="" aria-required="true"  type="file"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input name="createuser" id="createusersub" class="button button-primary" value="<?php _e('Upload', 'woocommerce-pickup-location'); ?>" type="submit"></p>
    </form>
    <hr/>
    <h1 id="add-csv"><?php _e('Export CSV File', 'woocommerce-pickup-location'); ?></h1>
    <p><?php if(isset($link))echo $link; ?></p>
    <form method="post" name="" id="" class="" novalidate="novalidate" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"></th>
                    <td><p class="submit"><input name="wp_pkpo_export" id="wp_pkpo_export" class="button button-primary" value="<?php _e('Export', 'woocommerce-pickup-location'); ?>" type="submit"></p></td>
                </tr>
            </tbody>
        </table>
    </form>
    
</div>