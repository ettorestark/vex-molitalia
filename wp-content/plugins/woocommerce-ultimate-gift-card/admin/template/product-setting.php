<?php 
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(isset($_POST['mwb_wgm_product_setting_save']))
{
	unset($_POST['mwb_wgm_product_setting_save']);
	if(isset($_POST['mwb_wgm_product_setting_exclude_product']))
	{
		$giftcard_exclude_products = $_POST['mwb_wgm_product_setting_exclude_product'];
		if(isset($giftcard_exclude_products) && !empty($giftcard_exclude_products))
		{
			$giftcard_exclude_product_string = "";
			foreach($giftcard_exclude_products as $giftcard_exclude_product)
			{
				$giftcard_exclude_product_string .= $giftcard_exclude_product.',';
			}
			$giftcard_exclude_product_string = rtrim($giftcard_exclude_product_string, ",");
			update_option("mwb_wgm_product_setting_exclude_product_format", $giftcard_exclude_product_string);
		}
	}
	else
	{
		$_POST['mwb_wgm_product_setting_exclude_product'] = "";
	}	

	if(isset($_POST['mwb_wgm_product_setting_exclude_category']))
	{
		
	}
	else
	{
		$_POST['mwb_wgm_product_setting_exclude_category'] = "";
	}
	
	
	if(!isset($_POST['mwb_wgm_general_setting_giftcard_ex_sale']))
	{
		$_POST['mwb_wgm_general_setting_giftcard_ex_sale'] = 'no';
	}
	
	do_action('mwb_wgm_product_setting_save');
	
	$postdata = $_POST;
	//print_r($postdata);die;
	foreach($postdata as $key=>$data)
	{
		update_option($key, $data);
	}
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e('Settings saved','woocommerce-ultimate-gift-card'); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php _e('Dismiss this notice','woocommerce-ultimate-gift-card'); ?></span>
		</button>
	</div><?php
}	
$giftcard_exclude_product = get_option("mwb_wgm_product_setting_exclude_product", array());
$giftcard_exclude_category = get_option("mwb_wgm_product_setting_exclude_category", array());
//print_r($giftcard_exclude_category);die;
$giftcard_ex_sale = get_option("mwb_wgm_general_setting_giftcard_ex_sale", false);
?>
<div class="mwb_table">
<table class="form-table mwb_wgm_general_setting">
	<tbody>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_ex_sale"><?php _e('Exclude Sale Items', 'woocommerce-ultimate-gift-card')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Check this box if the Giftcard Coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'woocommerce-ultimate-gift-card');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_general_setting_giftcard_ex_sale">
					<input type="checkbox" <?php echo ($giftcard_ex_sale == 'yes')?"checked='checked'":""?> name="mwb_wgm_general_setting_giftcard_ex_sale" id="mwb_wgm_general_setting_giftcard_ex_sale" class="input-text" value="yes"> <?php _e('Enable to exclude Sale Items','woocommerce-ultimate-gift-card');?>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_product_setting_exclude_product"><?php _e('Exclude Products', 'woocommerce-ultimate-gift-card')?></label>
			</th>
			<td class="forminp forminp-text">
				
				<label for="mwb_wgm_product_setting_exclude_product">
				<?php 
				$attribute_description = __('Products which must not be in the cart to use Giftcard coupon or, for "Product Discounts", which products are not discounted.', 'woocommerce-ultimate-gift-card');
				echo wc_help_tip( $attribute_description );
				?>
					<select id="mwb_wgm_product_setting_exclude_product" multiple="multiple" name="mwb_wgm_product_setting_exclude_product[]">
					<?php 
					$args = array( 'post_type' => array('product'), 'posts_per_page' => -1 );
					$loop = new WP_Query( $args );
					if($loop->have_posts()):
						while ( $loop->have_posts() ) : $loop->the_post();
						$productid = get_the_ID();
						$productitle = get_the_title();
						$productselect = "";
					
						if(is_array($giftcard_exclude_product) && in_array($productid, $giftcard_exclude_product))
						{
							$productselect = "selected='selected'";
						}	
					
						?>
						<option value="<?php echo $productid;?>" <?php echo $productselect;?>><?php echo $productitle;?></option>
						<?php 
						endwhile;
					endif
					?>
						
					</select>	
				</label>						
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_product_setting_exclude_category"><?php _e('Exclude Product Category', 'woocommerce-ultimate-gift-card')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Product must not be in this category for the Giftcard coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.', 'woocommerce-ultimate-gift-card');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_product_setting_exclude_category">
					<select id="mwb_wgm_product_setting_exclude_category" multiple="multiple" name="mwb_wgm_product_setting_exclude_category[]">
					<?php 
					$args = array('taxonomy'=>'product_cat');
					$categories = get_terms($args);
					if(isset($categories) && !empty($categories))
					{
						foreach($categories as $category)
						{
							$catid = $category->term_id;
							$catname = $category->name;
							$catselect = "";
						
							if(is_array($giftcard_exclude_category) && in_array($catid, $giftcard_exclude_category))
							{
								$catselect = "selected='selected'";
							}
						
							?>
							<option value="<?php echo $catid;?>" <?php echo $catselect;?>><?php echo $catname;?></option>
							<?php 
						}	
					}	
					?>
					</select>	
				</label>						
			</td>
		</tr>
		<?php 
		do_action('mwb_wgm_product_setting');
		?>
	</tbody>
</table>
</div>
<p class="submit">
	<input type="submit" value="<?php _e('Save changes','woocommerce-ultimate-gift-card');?>" class="button-primary woocommerce-save-button" name="mwb_wgm_product_setting_save">
</p>

<div class="clear"></div>
	