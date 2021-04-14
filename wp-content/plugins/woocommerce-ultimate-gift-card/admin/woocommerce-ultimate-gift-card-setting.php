<?php 
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$generaltab = "";
$producttab = "";
$tab = "";
$emailtab = "";
$offlinetab = "";
$exporttab = "";
$additionaltab = "";
$discounttab = "";
$thankyouordertab = "";
$exportcoupontabactive = false;
$generaltabactive = false;
$producttabactive = false;
$emailtabactive = false;
$offlinepaymenttabactive = false;
$additionalsettingtabactive = false;
$discounttabactive = false;
$thankyouordertabactive = false;
if(isset($_GET['tab']) && !empty($_GET['tab']))
{
	$tab = $_GET['tab'];
	if($tab == 'general-setting')
	{
		$generaltab = "nav-tab-active";
		$generaltabactive = true;
	}	
	if($tab == 'product-setting')
	{
		$producttab = "nav-tab-active";
		$producttabactive = true;
	}
	if($tab == 'email-setting')
	{
		$emailtab = "nav-tab-active";
		$emailtabactive = true;
	}
	
	if($tab == 'offline-giftcard')
	{
		$offlinetab = "nav-tab-active";
		$offlinepaymenttabactive = true;
	}
	if($tab == 'export-coupon')
	{
		$exporttab = "nav-tab-active";
		$exportcoupontabactive = true;
	}
	if($tab == 'other-additional-setting')
	{
		$additionaltab = "nav-tab-active";
		$additionalsettingtabactive = true;
	}
	if($tab == 'discount-tab')
	{
		$discounttab = "nav-tab-active";
		$discounttabactive = true;
	}
	if($tab == 'thankyou-tab')
	{
		$thankyouordertab = "nav-tab-active";
		$thankyouordertabactive = true;
	}
	
	do_action('mwb_wgm_setting_tab_active');
}	
if(empty($tab))
{
	$generaltab = "nav-tab-active";
	$generaltabactive = true;
}

?>

<div class="wrap woocommerce" id="mwb_wgm_setting_wrapper">
	<div style="display: none;" class="loading-style-bg" id="mwb_wgm_loader">
		<img src="<?php echo MWB_WGM_URL;?>/assets/images/loading.gif">
	</div>
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<h1 class="mwb_wgm_setting_title"><?php _e('Giftcard Settings', 'woocommerce-ultimate-gift-card')?></h1>
		<br/>
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a class="nav-tab <?php echo $generaltab;?>" href="?page=mwb-wgc-setting&tab=general-setting"><?php _e('General', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $producttab;?>" href="?page=mwb-wgc-setting&tab=product-setting"><?php _e('Products', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $emailtab;?>" href="?page=mwb-wgc-setting&tab=email-setting"><?php _e('Email Template', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $offlinetab;?>" href="?page=mwb-wgc-setting&tab=offline-giftcard"><?php _e('Offline Giftcard', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $exporttab;?>" href="?page=mwb-wgc-setting&tab=export-coupon"><?php _e('Import/Export', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $additionaltab;?>" href="?page=mwb-wgc-setting&tab=other-additional-setting"><?php _e('Other Setting', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $discounttab;?>" href="?page=mwb-wgc-setting&tab=discount-tab"><?php _e('Discount', 'woocommerce-ultimate-gift-card');?></a>
			<a class="nav-tab <?php echo $thankyouordertab;?>" href="?page=mwb-wgc-setting&tab=thankyou-tab"><?php _e('Thank You Order', 'woocommerce-ultimate-gift-card');?></a>		
			<?php 
			do_action('mwb_wgm_setting_tab');
			?>	
		</nav>
		<?php 
		if($generaltabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/general-setting.php';
		}	
		if($producttabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/product-setting.php';
		}
		if($emailtabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/email-setting.php';
		}
		if($offlinepaymenttabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/offline-gift.php';
		}
		if($exportcoupontabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/export-coupon.php';
		}
		if($additionalsettingtabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/other-additional-setting.php';
		}
		if($discounttabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/discount-setting.php';
		}
		if($thankyouordertabactive == true)
		{	
			include_once MWB_WGM_DIRPATH.'/admin/template/thankyou-order-setting.php';
		}
		do_action('mwb_wgm_setting_tab_html');
		?>
		
	</form>
</div>
