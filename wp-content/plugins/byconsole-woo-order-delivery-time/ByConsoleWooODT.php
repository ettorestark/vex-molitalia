<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly /** 

/*

* Plugin Name: WooODT Lite

* Plugin URI: https://plugins.byconsole.com/product/byconsole-wooodt-extended

* Description: Let your buyers to choose if order to deliver or pickup along with their chosen date and time (Need to have Woocommerce installed first). 

* Version: 1.1.8

* Author: ByConsole 

* Author URI: https://www.plugins.byconsole.com/ 

* Text Domain: byconsole-woo-order-delivery-time

* Domain Path: /languages

* License: GPL2 

*/ 

// load plugin's text domaim

function byconsolewooodt_free_plugin_activation() {

	global $wpdb;

	if(!get_option('byconsolewooodt_free_plugin_activation_date')){		

		$currentActivatedDate = date("m/d/Y");

		update_option('byconsolewooodt_free_plugin_activation_date',$currentActivatedDate);

	}

	

}

register_activation_hook( __FILE__, 'byconsolewooodt_free_plugin_activation' );





function byconsolewooodt_free_plugin_activation_admin_notice_error() {

	

	$free_plugins_activated_date = get_option('byconsolewooodt_free_plugin_activation_date');

	$free_plugins_activated_after_date = date('m/d/Y', strtotime($free_plugins_activated_date. ' + 16 days'));

	$currentDate = date("m/d/Y");

	

	if($free_plugins_activated_after_date <= $currentDate){	

		$message = 'It has been more then 15 days you are using our plugins. Will you mind put a 5 star review for us. Please <a href="https://wordpress.org/support/plugin/byconsole-woo-order-delivery-time/reviews/?rate=5#new-post" target="_new">click here</a>';

    echo '<div class="notice notice-warning is-dismissible" style="padding: 10px;">'.$message.'</div>';	

	}

	

}



add_action( 'admin_notices', 'byconsolewooodt_free_plugin_activation_admin_notice_error' );





function byconsolewooodt_load_text_domain(){







$byc_lang_path=dirname( plugin_basename(__FILE__) ) . '/languages/';







if(load_plugin_textdomain( 'byconsole-woo-order-delivery-time', false, $byc_lang_path ));







}



add_action('plugins_loaded','byconsolewooodt_load_text_domain');





include('inc/admin.php');







global $woocommerce;







// we need cookie so lets have a safe and confirm way







add_action('init', 'byconsolewooodtSetCookie', 1);







function byconsolewooodtSetCookie() {







// set default values if empty to avoid undefined index issue using cookies







if(empty($_COOKIE['byconsolewooodt_delivery_widget_cookie'])){







$byconsolewooodt_delivery_widget=array(







'byconsolewooodt_widget_date_field'=>'',







'byconsolewooodt_widget_time_field'=>'',







'byconsolewooodt_widget_type_field'=>'levering'







); 







$json_byconsolewooodt_delivery_widget=json_encode($byconsolewooodt_delivery_widget);







setcookie('byconsolewooodt_delivery_widget_cookie',$json_byconsolewooodt_delivery_widget,time() + 60 * 60 * 24 *1,'/');







if(empty($_COOKIE['byconsolewooodt_delivery_widget_cookie']))







{







	$_COOKIE['byconsolewooodt_delivery_widget_cookie']=json_encode($byconsolewooodt_delivery_widget);







}















}







} 







// front-end widget 







class byconsolewooodt_widget extends WP_Widget {







function __construct() {







parent::__construct(







// Base ID of our widget







'byconsolewooodt_widget', 







// Widget name will appear in UI







__('Order delivery time widget', 'byconsole-woo-order-delivery-time'), 







// Widget description







array( 'description' => __( 'Widget for users to choose time and date of delivery', 'byconsole-woo-order-delivery-time' ), ) 







);







}







// Creating widget front-end







// This is where the action happens







public function widget( $args, $instance ) {







echo $args['before_widget'];







if ( ! empty( $instance['byconsolewooodt_widget_title'] ) ) {







echo $args['before_title'] . apply_filters( 'widget_title', esc_html($instance['byconsolewooodt_widget_title']) ) . $args['after_title'];







}







//echo __( esc_attr( 'Enter your delivery date and time' ), 'byconsole-woo-order-delivery-time' );























echo $args['after_widget'];







$stripped_out_byconsolewooodt_delivery_widget_cookie=stripslashes($_COOKIE['byconsolewooodt_delivery_widget_cookie']);







$byconsolewooodt_delivery_widget_cookie_array=json_decode($stripped_out_byconsolewooodt_delivery_widget_cookie,true);















//var_dump( $byconsolewooodt_delivery_widget_cookie_array);















$byconsolewooodt_delivery_date = ! empty( $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_date_field'] ) ? $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_date_field'] : false;







$byconsolewooodt_delivery_time = ! empty( $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_time_field'] ) ? $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_time_field'] : false;















$byconsolewooodt_delivery_type = ! empty( $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field'] ) ? $byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field'] : false;















//$byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']















$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');







$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');















//for translations







$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');







$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');







$byconsolewooodt_date_field_text=get_option('byconsolewooodt_date_field_text');







$byconsolewooodt_time_field_text=get_option('byconsolewooodt_time_field_text');







// get cookie as array







$stripped_out_byconsolewooodt_delivery_widget_cookie=stripslashes($_COOKIE['byconsolewooodt_delivery_widget_cookie']);







$byconsolewooodt_delivery_widget_cookie_array=json_decode($stripped_out_byconsolewooodt_delivery_widget_cookie,true);







if(!empty($byconsolewooodt_takeaway_lable)) 







{ } 







else 







{ 







$byconsolewooodt_takeaway_lable = __('Take away','byconsole-woo-order-delivery-time');







}















if(!empty($byconsolewooodt_delivery_lable)) 







{ } 







else 







{ 







$byconsolewooodt_delivery_lable = __('Delivery','byconsole-woo-order-delivery-time');







}















if(!empty($byconsolewooodt_date_field_text)) 







{ } 







else 







{ 







$byconsolewooodt_date_field_text = __('Select date','byconsole-woo-order-delivery-time');







}















if(!empty($byconsolewooodt_time_field_text)) 







{ } 







else 







{ 







$byconsolewooodt_time_field_text = __('Select time','byconsole-woo-order-delivery-time');







}







?>















<form action="" method="post" class="byconsolewooodt_delivery_type_for_widget">







<div class="byconsolewooodt_delivery_type_for_widget">







<input type="radio" name="byconsolewooodt_widget_type_field" value="take_away" <?php if($byconsolewooodt_delivery_type=='take_away'){echo ' checked="checked"';}?>/>







<label for="byconsolewooodt_delivery_type_take_away" class="radio "><?php echo esc_html($byconsolewooodt_takeaway_lable);?></label>















<input type="radio" name="byconsolewooodt_widget_type_field" value="levering"<?php if($byconsolewooodt_delivery_type=='levering'){echo ' checked="checked"';}?> />







<label for="byconsolewooodt_delivery_type_levering" class="radio "><?php echo esc_html($byconsolewooodt_delivery_lable);?></label>







</div>







<br />







<input type="text" name="byconsolewooodt_widget_date_field" class="byconsolewooodt_widget_date_field"  placeholder="<?php echo esc_html($byconsolewooodt_date_field_text);?>" readonly="readonly" value="<?php echo esc_attr($byconsolewooodt_delivery_date);?>" />















<input type="text" name="byconsolewooodt_delivery_date_alternate" class="byconsolewooodt_widget_date_field_alternate" id="byconsolewooodt_delivery_date_alternate_widget" readonly="readonly" value="<?php echo esc_html($byconsolewooodt_delivery_date_alternate);?>" style="display:none;" />















<input type="text" name="byconsolewooodt_widget_time_field" class="byconsolewooodt_widget_time_field" placeholder="<?php echo esc_html($byconsolewooodt_time_field_text);?>" value="<?php echo esc_html($byconsolewooodt_delivery_time);?>" />







<br />







<p class="byc_service_time_closed"></p>







<?php 







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){?>







<p class="min-shipping-time"><img src="<?php echo plugins_url('images/min-shipping-time.png', __FILE__)?>" alt="Minimum delivery time" /> &nbsp; <?php echo esc_html(get_option('byconsolewooodt_delivery_times'));?></p>







<?php }?>







<input type="submit" name="byconsolewooodt_widget_submit" value="Save" />















</form>







<?php







echo $args['after_widget'];







//pre-order settings







?>







<script>







jQuery(document).ready(function(){







delivery_opening_time="<?php echo esc_html(get_option('byconsolewooodt_delivery_hours_from')); ?>";







pickup_opening_time="<?php echo esc_html(get_option('byconsolewooodt_opening_hours_from')); ?>";







delivery_ending_time="<?php echo esc_html(get_option('byconsolewooodt_delivery_hours_to')); ?>";







pickup_ending_time="<?php echo esc_html(get_option('byconsolewooodt_opening_hours_to')); ?>";







<?php







if(get_option('byconsolewooodt_preorder_days')==''){// if no pre-order date is not set in settings page







?>







jQuery(".byconsolewooodt_widget_date_field").datepicker({







minDate: 0,







showAnim: "slideDown", 







altField: ".byconsolewooodt_widget_date_field_alternate",







altFormat: "dd/m/yy",







onSelect: function(){jQuery(".byconsolewooodt_widget_time_field").timepicker("remove"); jQuery(".byconsolewooodt_widget_time_field").val(''); ByconsolewooodtDeliveryWidgetTimePopulate(".byconsolewooodt_widget_date_field",".byconsolewooodt_widget_time_field");} // reset timepicker on date selection to get new time value depending date selected here AND THEN call call time population function







});







<?php







}else{//if no pre-order date is set in settings page do the date selection restriction







?>







jQuery( ".byconsolewooodt_widget_date_field" ).datepicker({ 







minDate: 0, 







maxDate: "<?php echo esc_html(get_option('byconsolewooodt_preorder_days'));?>+D", 







showOtherMonths: true,







selectOtherMonths: true,







showAnim: "slideDown",







altField: ".byconsolewooodt_widget_date_field_alternate",







altFormat: "dd/m/yy",







onSelect: function(){jQuery(".byconsolewooodt_widget_time_field").timepicker("remove"); jQuery(".byconsolewooodt_widget_time_field").val(''); ByconsolewooodtDeliveryWidgetTimePopulate(".byconsolewooodt_widget_date_field",".byconsolewooodt_widget_time_field");} // reset timepicker on date selection to get new time value depending date selected here AND THEN call call time population function







});







<?php }	







//synchornize both the delivery type radio button in widget and in checkout field in simple way







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){







?>







jQuery("#byconsolewooodt_delivery_type_levering").prop("checked", true);







<?php	}







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away'){







?>







jQuery("#byconsolewooodt_delivery_type_take_away").prop("checked", true);







<?php	} ?>







jQuery("input#byconsolewooodt_delivery_date").val("<?php echo esc_html($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_date_field']);?>");







jQuery("input#byconsolewooodt_delivery_time").val("<?php echo esc_html($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_time_field']);?>");







})







</script>







<?php







}







// Widget Backend 







public function form( $instance ) {







if ( isset( $instance[ 'byconsolewooodt_widget_title' ] ) ) {







$title = $instance[ 'byconsolewooodt_widget_title' ];







}







else {







$title = __( 'New title', 'byconsole-woo-order-delivery-time' );







}







// Widget admin form







?>







<p>







<label for="<?php echo esc_html($this->get_field_id( 'byconsolewooodt_widget_title' )); ?>"><?php __( 'Title:','byconsole-woo-order-delivery-time' ); ?></label> 







<input class="widefat" id="<?php echo esc_html($this->get_field_id( 'byconsolewooodt_widget_title' )); ?>" name="<?php echo $this->get_field_name( 'byconsolewooodt_widget_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />







</p>







<?php 







}







// Updating widget replacing old instances with new







public function update( $new_instance, $old_instance ) {







$instance = array();







$instance['byconsolewooodt_widget_title'] = ( ! empty( $new_instance['byconsolewooodt_widget_title'] ) ) ? strip_tags( $new_instance['byconsolewooodt_widget_title'] ) : '';







return $instance;







}







/*****************************************************/







} // Class byconsolewooodt_widget ends here







// Register and load the widget







function byconsolewooodt_load_widget() {







register_widget( 'byconsolewooodt_widget' );







}







add_action( 'widgets_init', 'byconsolewooodt_load_widget' );//save frontend widget data in cookie, so we need to do it before any output, hence hook it on init







function byconsolewooodt_savefrontend_widget_data(){







// save thwe widget data in in cookie	







if(isset($_POST['byconsolewooodt_widget_submit'])){







$byconsolewooodt_delivery_widget_post_array = array(







'byconsolewooodt_widget_date_field'   => sanitize_text_field($_POST['byconsolewooodt_widget_date_field']),







'byconsolewooodt_widget_time_field'   => sanitize_text_field($_POST['byconsolewooodt_widget_time_field']),







'byconsolewooodt_widget_type_field'   => sanitize_text_field($_POST['byconsolewooodt_widget_type_field']),







);







//set cookie







$json_byconsolewooodt_delivery_widget_post_array=json_encode($byconsolewooodt_delivery_widget_post_array);







setcookie('byconsolewooodt_delivery_widget_cookie',$json_byconsolewooodt_delivery_widget_post_array , time() + 60 * 60 * 24 * 1, '/');







$_COOKIE['byconsolewooodt_delivery_widget_cookie'] = $json_byconsolewooodt_delivery_widget_post_array;// for immediate access in widget







}







}// end of byconsolewooodt_savefrontend_widget_data







add_action('init','byconsolewooodt_savefrontend_widget_data');// Add the field to the checkout







//add_action( 'woocommerce_after_order_notes', 'byconsolewooodt_checkout_field' );







add_action( 'woocommerce_checkout_before_customer_details', 'byconsolewooodt_checkout_field' );







function byconsolewooodt_checkout_field( $checkout ) {

global $woocommerce;// get cookie as array

$byconsolewooodt_time_field_validation = get_option('byconsolewooodt_time_field_validation');

/********************************/

 $bycwooodt_has_virtual_products = false;

  // Default virtual products number

  $bycwooodt_virtual_products = 0;

  // Get all products in cart

  $bycwooodt_products = $woocommerce->cart->get_cart();

  // Loop through cart products

  foreach( $bycwooodt_products as $bycwooodt_product ) {
	  // Get product ID and '_virtual' post meta
	  $bycwooodt_product_id = $bycwooodt_product['product_id'];

	  $bycwooodt_is_virtual = get_post_meta( $bycwooodt_product_id, '_virtual', true );

	  // Update $has_virtual_product if product is virtual

	  if( $bycwooodt_is_virtual == 'yes' )

  		$bycwooodt_virtual_products += 1;
  }

  if( count($bycwooodt_products) == $bycwooodt_virtual_products )
  {
	  $bycwooodt_both_product_count_val = 'same';
  }
  else
  {
	  $bycwooodt_both_product_count_val = 'not_same';
  }

   //echo $bycwooodt_both_product_count_val;

  //$has_virtual_products = true;

/********************************/
if($bycwooodt_both_product_count_val == 'not_same')
{

$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');

$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');

$byconsolewooodt_date_field_text=get_option('byconsolewooodt_date_field_text');

$byconsolewooodt_time_field_text=get_option('byconsolewooodt_time_field_text');
// get cookie as array
$stripped_out_byconsolewooodt_delivery_widget_cookie=stripslashes($_COOKIE['byconsolewooodt_delivery_widget_cookie']);

$byconsolewooodt_delivery_widget_cookie_array=json_decode($stripped_out_byconsolewooodt_delivery_widget_cookie,true);

if(!empty($byconsolewooodt_takeaway_lable)) 
{ 
//$byconsolewooodt_takeaway_lable =  get_option('byconsolewooodt_takeaway_lable'); 
} 
else 
{ 
$byconsolewooodt_takeaway_lable = __('Take away','byconsole-woo-order-delivery-time');
}

if(!empty($byconsolewooodt_delivery_lable)) 
{ 
//$byconsolewooodt_delivery_lable =  get_option('byconsolewooodt_delivery_lable'); 
} 
else 
{ 

$byconsolewooodt_delivery_lable = __('Delivery','byconsole-woo-order-delivery-time');
}

if(!empty($byconsolewooodt_date_field_text)) 
{ 
//$byconsolewooodt_date_field_text =  get_option('byconsolewooodt_date_field_text'); 
} 
else 
{ 
$byconsolewooodt_date_field_text = __('Select date','byconsole-woo-order-delivery-time');
}

if(!empty($byconsolewooodt_time_field_text)) 
{ 
//$byconsolewooodt_time_field_text =  get_option('byconsolewooodt_time_field_text'); 
} 
else 
{ 
$byconsolewooodt_time_field_text = __('Select time','byconsole-woo-order-delivery-time');
}

$get_option_byconsolewooodt_chekout_page_order_type_label=get_option('byconsolewooodt_chekout_page_order_type_label');
if(!empty($get_option_byconsolewooodt_chekout_page_order_type_label)){
}else{
$get_option_byconsolewooodt_chekout_page_order_type_label = __('Select order type','byconsole-woo-order-delivery-time');	
}

$get_option_byconsolewooodt_chekout_page_date_label=get_option('byconsolewooodt_chekout_page_date_label');
if(!empty($get_option_byconsolewooodt_chekout_page_date_label)){
//$get_option_byconsolewooodt_chekout_page_date_lebel=get_option('byconsolewooodt_chekout_page_date_label');	
}else{
$get_option_byconsolewooodt_chekout_page_date_label = __('Select date','byconsole-woo-order-delivery-time');		
}

$get_option_byconsolewooodt_chekout_page_time_label=get_option('byconsolewooodt_chekout_page_time_label');

if(!empty($get_option_byconsolewooodt_chekout_page_time_label)){
}else{
$get_option_byconsolewooodt_chekout_page_time_label=__('Select time','byconsole-woo-order-delivery-time');	
}

echo '<div id="byconsolewooodt_checkout_field"><h2>'.esc_html(get_option('byconsolewooodt_chekout_page_section_heading')) . '</h2>';

woocommerce_form_field( 'byconsolewooodt_delivery_type', array(
'type'          => 'radio',

'required'		=>	'true',

'class'         => array('byconsolewooodt_delivery_type', 'ABC'),

'label'         => $get_option_byconsolewooodt_chekout_page_order_type_label,

'label_class'	=> 'byconsolewooodt_ordertype_label',

'placeholder'   => __('Select delivery type','byconsole-woo-order-delivery-time'),

'default'		=> esc_html($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']),

'checked'		=> 'checked',

'options'		=> array(

'take_away' => esc_html($byconsolewooodt_takeaway_lable), 

'levering' => esc_html($byconsolewooodt_delivery_lable), 
)
));

//$get_option_byconsolewooodt_chekout_page_date_lebel='CVBN';
woocommerce_form_field( 'byconsolewooodt_delivery_date', array(
'type'          => 'text',

'required'		=>	'true',

'class'         => array('byconsolewooodt_delivery_date'),

'label'         => $get_option_byconsolewooodt_chekout_page_date_label,

'placeholder'   => __(esc_html($byconsolewooodt_date_field_text),'byconsole-woo-order-delivery-time'),

'default'		=> esc_html($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_date_field'])
));

woocommerce_form_field( 'byconsolewooodt_delivery_date_alternate', array(

'type'          => 'text',

'class'         => array('byconsolewooodt_delivery_date_alternate'),

'label'         => '',

'placeholder'   => '',

'default'		=> '',
));		

if($byconsolewooodt_time_field_validation == 'yes'){
	$required = true;
}else{
	$required = false;
}

woocommerce_form_field( 'byconsolewooodt_delivery_time', array(
'type'          => 'text',

'required'		=>	$required,

'class'         => array('byconsolewooodt_delivery_time'),

'label'         => $get_option_byconsolewooodt_chekout_page_time_label,

'placeholder'   => __(esc_html($byconsolewooodt_time_field_text),'byconsole-woo-order-delivery-time'),

'default'		=> esc_html($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_time_field'])
));

echo '<p class="byc_service_time_closed"></p></div>';
	}
}

// Validate the custom field.
add_action('woocommerce_checkout_process', 'byconsolewooodt_checkout_field_process');

function byconsolewooodt_checkout_field_process() {
// Check if set, if its not set add an error.
global $woocommerce;// get cookie as array
/********************************/
 $bycwooodt_has_virtual_products = false;
  // Default virtual products number
  $bycwooodt_virtual_products = 0;
  // Get all products in cart
  $bycwooodt_products = $woocommerce->cart->get_cart();
  // Loop through cart products
  foreach( $bycwooodt_products as $bycwooodt_product ) {
	  // Get product ID and '_virtual' post meta
	  $bycwooodt_product_id = $bycwooodt_product['product_id'];

	  $bycwooodt_is_virtual = get_post_meta( $bycwooodt_product_id, '_virtual', true );
	  // Update $has_virtual_product if product is virtual
	  if( $bycwooodt_is_virtual == 'yes' )
  		$bycwooodt_virtual_products += 1;
  }

  if( count($bycwooodt_products) == $bycwooodt_virtual_products )
  {
	  $bycwooodt_both_product_count_val = 'same';
  }
  else
  {
	  $bycwooodt_both_product_count_val = 'not_same';

  }

  //echo $bycwooodt_both_product_count_val;
  //$has_virtual_products = true;
/********************************/

if($bycwooodt_both_product_count_val == 'not_same')
{
if ( ! $_POST['byconsolewooodt_delivery_date'] )

wc_add_notice( __( '<b>Pickup / Delivery date</b> is a required field.','byconsole-woo-order-delivery-time' ), 'error' );

$byconsolewooodt_time_field_validation = get_option('byconsolewooodt_time_field_validation');

if($byconsolewooodt_time_field_validation == 'yes'){

if ( ! $_POST['byconsolewooodt_delivery_time'] )

wc_add_notice( __( '<b>Pickup / Delivery time</b> is a required field.','byconsole-woo-order-delivery-time' ), 'error' );

}
 }
}

//Save the order meta with field value
add_action( 'woocommerce_checkout_update_order_meta', 'byconsolewooodt_checkout_field_update_order_meta' );

function byconsolewooodt_checkout_field_update_order_meta( $order_id ) {

global $woocommerce;// get cookie as array
/********************************/
 $bycwooodt_has_virtual_products = false;
  // Default virtual products number
  $bycwooodt_virtual_products = 0;
  // Get all products in cart
  $bycwooodt_products = $woocommerce->cart->get_cart();

  // Loop through cart products
  foreach( $bycwooodt_products as $bycwooodt_product ) {
	  // Get product ID and '_virtual' post meta
	  $bycwooodt_product_id = $bycwooodt_product['product_id'];

	  $bycwooodt_is_virtual = get_post_meta( $bycwooodt_product_id, '_virtual', true );
  // Update $has_virtual_product if product is virtual
	  if( $bycwooodt_is_virtual == 'yes' )
  		$bycwooodt_virtual_products += 1;
  }







  







  if( count($bycwooodt_products) == $bycwooodt_virtual_products )







  {







	  $bycwooodt_both_product_count_val = 'same';







  }







  else







  {







	  $bycwooodt_both_product_count_val = 'not_same';







  }







  







  //echo $bycwooodt_both_product_count_val;







  //$has_virtual_products = true;







  







/********************************/















if($bycwooodt_both_product_count_val == 'not_same')







{























if ( ! empty( $_POST['byconsolewooodt_delivery_date'] ) ) {







update_post_meta( $order_id, 'byconsolewooodt_delivery_date', sanitize_text_field( $_POST['byconsolewooodt_delivery_date'] ) );







}







if ( ! empty( $_POST['byconsolewooodt_delivery_time'] ) ) {







update_post_meta( $order_id, 'byconsolewooodt_delivery_time', sanitize_text_field( $_POST['byconsolewooodt_delivery_time'] ) );







}







if ( ! empty( $_POST['byconsolewooodt_delivery_type'] ) ) {







update_post_meta( $order_id, 'byconsolewooodt_delivery_type', sanitize_text_field($_POST['byconsolewooodt_delivery_type'] ));







}







}















}







//Display field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'byconsolewooodt_checkout_field_display_admin_order_meta', 10, 1 );

function byconsolewooodt_checkout_field_display_admin_order_meta($order){

$order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');

$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='take_away'){

if(!empty($byconsolewooodt_takeaway_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_takeaway_lable; 
} 
else 
{ 
$order_delivery_type = __('Take away','byconsole-woo-order-delivery-time');
}
}

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='levering'){
if(!empty($byconsolewooodt_delivery_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_delivery_lable; 
} 
else 
{ 
$order_delivery_type = __('Delivery','byconsole-woo-order-delivery-time');
}
}

//date
if( !empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) )
{
echo '<p><strong>'.$order_delivery_type.' '.__('date','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) . '</p>';
}

//time
if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) )
{
echo '<p><strong>'.$order_delivery_type.' '.__('time','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) . '</p>';
}

//type
if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )) )
{
echo '<p><strong>'.__('Order type','byconsole-woo-order-delivery-time').':</strong> ' . esc_html($order_delivery_type) . '</p>';
}
}

// Display order meta in order details section
if(get_option('byconsolewooodt_widget_field_position')=='top'){ //hook here if it is set to show on top in admin settings page

//add_action( 'woocommerce_view_order', 'byconsolewooodt_checkout_field_display_user_order_meta', 10, 1 );
add_action( 'woocommerce_order_details_after_order_table_items', 'byconsolewooodt_checkout_field_display_user_order_meta', 10, 1 );
}
if(get_option('byconsolewooodt_widget_field_position')=='bottom'){  //hook here if it is set to show on bottom in admin settings page
add_action( 'woocommerce_order_details_after_order_table', 'byconsolewooodt_checkout_field_display_user_order_meta', 10, 1 );
}

function byconsolewooodt_checkout_field_display_user_order_meta($order){
$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');
$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');

/*if(get_post_meta( $order->id, 'byconsolewooodt_delivery_type', true )=='take_away'){	$order_delivery_type='Take Away';}
if(get_post_meta( $order->id, 'byconsolewooodt_delivery_type', true )=='levering'){		$order_delivery_type='Delivery';}*/

$order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

if(get_post_meta( $order_id , 'byconsolewooodt_delivery_type', true )=='take_away'){

if(!empty($byconsolewooodt_takeaway_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_takeaway_lable; 
} 
else 
{ 
$order_delivery_type = __('Take away','byconsole-woo-order-delivery-time');
}
}

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='levering'){
if(!empty($byconsolewooodt_delivery_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_delivery_lable; 
} 
else 
{ 
$order_delivery_type = __('Delivery','byconsole-woo-order-delivery-time');
}
}

if( !empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) )
{
echo '<p><strong>'.$order_delivery_type.' '.__('date','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) . '</p>';
}

if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) )
{
echo '<p><strong>'.$order_delivery_type.' '.__('time','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) . '</p>';
}

if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )) )
{
echo '<p><strong>'.__('Order type','byconsole-woo-order-delivery-time').':</strong> ' . esc_html($order_delivery_type) . '</p>';
}

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='levering'){
$prepare_shipping_text= str_replace('[byc_delivery_date]','<b>'.esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )).'</b>',esc_html(get_option('byconsolewooodt_orders_delivered')));

if(get_option('byconsolewooodt_time_field_validation') == 'yes'){
echo '<p>'.str_replace('[byc_delivery_time]','<b>'.esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )).'</b>',$prepare_shipping_text).'</p>';
	}else{
echo '<p>'.str_replace('[byc_delivery_time]','<b>ASAP</b>',$prepare_shipping_text).'</p>';
	}
}

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='take_away'){
$prepare_shipping_text= str_replace('[byc_pickup_date]','<b>'.esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )).'</b>',esc_html(get_option('byconsolewooodt_orders_pick_up')));

if(get_option('byconsolewooodt_time_field_validation') == 'yes'){
echo '<p>'.str_replace('[byc_pickup_time]','<b>'.esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )).'</b>',$prepare_shipping_text).'</p>';	
}else{
echo '<p>'.str_replace('[byc_pickup_time]','<b>ASAP</b>',$prepare_shipping_text).'</p>';		
	}
}
}

//include the custom order meta to woocommerce mail
add_action( "woocommerce_email_after_order_table", "byconsolewooodt_woocommerce_email_after_order_table", 10, 1);

function byconsolewooodt_woocommerce_email_after_order_table( $order ) {
$byconsolewooodt_takeaway_lable=get_option('byconsolewooodt_takeaway_lable');
$byconsolewooodt_delivery_lable=get_option('byconsolewooodt_delivery_lable');

/*	if(get_post_meta( $order->id, 'byconsolewooodt_delivery_type', true )=='take_away'){ $order_delivery_type='Take Away';}
if(get_post_meta( $order->id, 'byconsolewooodt_delivery_type', true )=='levering'){	$order_delivery_type='Delivery';}*/

$order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='take_away'){
if(!empty($byconsolewooodt_takeaway_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_takeaway_lable; 
} 
else 
{ 
$order_delivery_type = __('Take away','byconsole-woo-order-delivery-time');
}
}

if(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )=='levering'){
if(!empty($byconsolewooodt_delivery_lable)) 
{ 
$order_delivery_type =  $byconsolewooodt_delivery_lable; 
} 
else 
{ 
$order_delivery_type = __('Delivery','byconsole-woo-order-delivery-time');
}
}

if( !empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) )
{
echo '<p></p><p><strong>'.$order_delivery_type.' '.__('date','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) . '</p>';
}

if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) )
{
echo '<p><strong>'.$order_delivery_type.' '.__('time','byconsole-woo-order-delivery-time').':</strong> ' . esc_html(get_post_meta( $order_id, 'byconsolewooodt_delivery_time', true )) . '</p>';
}

if(!empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_type', true )) && !empty(get_post_meta( $order_id, 'byconsolewooodt_delivery_date', true )) )
{
echo '<p><strong>'.__('Order type','byconsole-woo-order-delivery-time').':</strong> ' . esc_html($order_delivery_type) . '</p>';
}
}

// add our styles and js
function byconsolewooodt_add_scripts() {

wp_enqueue_script('jquery-ui-datepicker');

wp_register_script('byconsolewooodt_script_2', plugins_url('js/jquery.timepicker.min.js', __FILE__), array('jquery'),'1.12', true);

wp_register_script('byconsolewooodt_script_3', plugins_url('js/byconsolewooodt.js', __FILE__), array('jquery'),'1.12', true);

wp_enqueue_script('byconsolewooodt_script_2');

wp_enqueue_script('byconsolewooodt_script_3');
}

add_action( 'wp_enqueue_scripts', 'byconsolewooodt_add_scripts' ); 

//add styles
function byconsolewooodt_add_styles() {

wp_enqueue_style('byconsolewooodt_stylesheet', plugins_url('css/style.css', __FILE__));

wp_enqueue_style('byconsolewooodt_stylesheet_2', plugins_url('css/jquery-ui.min.css', __FILE__));

wp_enqueue_style('byconsolewooodt_stylesheet_3', plugins_url('css/jquery-ui.theme.min.css', __FILE__));

wp_enqueue_style('byconsolewooodt_stylesheet_4', plugins_url('css/jquery-ui.structure.min.css', __FILE__));

wp_enqueue_style('byconsolewooodt_stylesheet_5', plugins_url('css/jquery.timepicker.css', __FILE__));
}







add_action( 'wp_enqueue_scripts', 'byconsolewooodt_add_styles' ); 







// refreshing the cart on page load







/** Break html5 cart caching */







add_action('wp_enqueue_scripts', 'cartcache_enqueue_scripts', 100);







function cartcache_enqueue_scripts()







{







wp_deregister_script('wc-cart-fragments');







wp_enqueue_script( 'wc-cart-fragments', plugins_url( 'js/cart-fragments.js', __FILE__ ), array( 'jquery', 'jquery-cookie' ), '1.12', true );







}







// show only store pickup when take_away is selected	







//







add_filter('woocommerce_package_rates', 'byconsolewooodt_shipping_according_widget_input', 10, 2);







//add_filter('woocommerce_package_rates', 'byconsolewooodt_shipping_according_widget_input', 100);







function byconsolewooodt_shipping_according_widget_input($rates, $package)







{







// get cookie as array







$stripped_out_byconsolewooodt_delivery_widget_cookie=stripslashes($_COOKIE['byconsolewooodt_delivery_widget_cookie']);







$byconsolewooodt_delivery_widget_cookie_array=json_decode($stripped_out_byconsolewooodt_delivery_widget_cookie,true);







global $woocommerce;







$version = "2.6";







if (version_compare($woocommerce->version, $version, ">=")) {







$new_rates = array();







/*echo '<hr />';







print_r($rates);*/







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away'){







foreach($rates as $key => $rate) {







if ('local_pickup' === $rate->method_id || 'legacy_local_pickup' === $rate->method_id) {







$new_rates[$key] = $rates[$key];







}







}







/*print_r($new_rates);







print_r($rates);*/







}elseif($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){







foreach($rates as $key => $rate) {







/*print_r($rate);







echo '<hr />';*/







if ('local_pickup' != $rate->method_id && 'legacy_local_pickup' != $rate->method_id ) {







$new_rates[$key] = $rates[$key];







//unset($rates['local_pickup']);







}







}







}else{







//







}







return empty($new_rates) ? $rates : $new_rates;







/*echo '<hr />';







print_r($new_rates);*/







}







else {







if ($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away') {







$predefined_shipping          = $rates['local_pickup'];







$rates                  = array();







$rates['local_pickup'] = $predefined_shipping;







}







if ($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering') {







$predefined_shipping          = $rates['flat_rate'];







$rates                  = array();







$rates['flat_rate'] = $predefined_shipping;







}







return $rates;







}







}







// check if checkout page







// do the JS to populate date and time field paqrameter















function byconsolewooodt_wp_head() {







?>







	<style>







		#byconsolewooodt_delivery_date_alternate_field{display:none;}







	</style>







<?php







}







add_action('wp_head', 'byconsolewooodt_wp_head', 1);















function byconsolewooodt_footer_script(){







// get cookie as array







if(!empty($_COOKIE['byconsolewooodt_delivery_widget_cookie'])){







$stripped_out_byconsolewooodt_delivery_widget_cookie=stripslashes($_COOKIE['byconsolewooodt_delivery_widget_cookie']);







$byconsolewooodt_delivery_widget_cookie_array=json_decode($stripped_out_byconsolewooodt_delivery_widget_cookie,true);







}







?>







<script>







function ByConsoleWooODTStartTimeByInterval(cur_hour,cur_minute){







if(cur_minute > 0 && cur_minute < 15){







var start_minute=15;







}else if(cur_minute >= 15 && cur_minute < 30){







var start_minute=30;







}else if(cur_minute >= 30 && cur_minute < 45){







var start_minute=45;







}else if(cur_minute >= 45 && cur_minute < 59){







var start_minute=59;







}else{}







if(start_minute==59){







var next_hour=parseInt(cur_hour)+1;







var start_time_updated=next_hour+":"+"00";







}else{







var start_time_updated=cur_hour+":"+start_minute;







}







return start_time_updated;







} // end of ByConsoleWooODTtimeInterval







function ByconsolewooodtDeliveryWidgetTimePopulate(date_field_identifier,time_field_identifier){ 







// lock the time selection based on admin settings for delivery time







//echo 'var curtime_to_compare=new Date().toLocaleTimeString();';







service_status="open";







var current_date= new Date();







var curtime= new Date().toLocaleTimeString("en-US", { hour12: false, hour: "numeric", minute: "numeric"});







// get local minute







//var cur_minute= new Date().toLocaleTimeString("en-US", { hour12: false, minute: "numeric"});







var cur_minute=current_date.getMinutes();







// get local hour







//var cur_hour= new Date().toLocaleTimeString("en-US", { hour12: false, hour: "numeric"});											 







var cur_hour=current_date.getHours();















var curtime=cur_hour+':'+cur_minute;















ByConsoleWooODTStartTimeByInterval(cur_hour,cur_minute); // check this function in wp-footer







//populate time field based on date selection (call this function onSelect event of datepicker)







/*var selected_date=jQuery(".byconsolewooodt_widget_date_field").datepicker( "getDate" );*/







selected_date=jQuery(date_field_identifier).datepicker('option', 'dateFormat', 'dd M yy').val();















//var byc_delivery_date_alternate = jQuery("#byconsolewooodt_delivery_date_alternate").val().split("/");







var byc_delivery_date_alternate = jQuery(date_field_identifier+"_alternate").val().split("/");







if(byc_delivery_date_alternate[1]==1){







byc_delivery_date_alternate_month='Jan';







}else if(byc_delivery_date_alternate[1]==2){







byc_delivery_date_alternate_month='Feb';







}else if(byc_delivery_date_alternate[1]==3){







byc_delivery_date_alternate_month='Mar';







}else if(byc_delivery_date_alternate[1]==4){







byc_delivery_date_alternate_month='Apr';







}else if(byc_delivery_date_alternate[1]==5){







byc_delivery_date_alternate_month='May';







}else if(byc_delivery_date_alternate[1]==6){







byc_delivery_date_alternate_month='Jun';







}else if(byc_delivery_date_alternate[1]==7){







byc_delivery_date_alternate_month='Jul';







}else if(byc_delivery_date_alternate[1]==8){







byc_delivery_date_alternate_month='Aug';







}else if(byc_delivery_date_alternate[1]==9){







byc_delivery_date_alternate_month='Sep';







}else if(byc_delivery_date_alternate[1]==10){







byc_delivery_date_alternate_month='Oct';







}else if(byc_delivery_date_alternate[1]==11){







byc_delivery_date_alternate_month='Nov';







}else if(byc_delivery_date_alternate[1]==12){







byc_delivery_date_alternate_month='Dec';







}else{







byc_delivery_date_alternate_month='';







}







selected_date = byc_delivery_date_alternate[0] + " " + byc_delivery_date_alternate_month + " " + byc_delivery_date_alternate[2];







todays_date=new Date();







todays_date_month=(todays_date.getMonth()+1);







todays_date_date=todays_date.getDate();







todays_date_year=todays_date.getFullYear();







if( todays_date_month < 10){







todays_date_month='0' + todays_date_month;







}







if(todays_date_date < 10){







todays_date_date='0' + todays_date_date;







}







if(todays_date_month==1){







todays_date_month='Jan';







}else if(todays_date_month==2){







todays_date_month='Feb';







}else if(todays_date_month==3){







todays_date_month='Mar';







}else if(todays_date_month==4){







todays_date_month='Apr';







}else if(todays_date_month==5){







todays_date_month='May';







}else if(todays_date_month==6){







todays_date_month='Jun';







}else if(todays_date_month==7){







todays_date_month='Jul';







}else if(todays_date_month==8){







todays_date_month='Aug';







}else if(todays_date_month==9){







todays_date_month='Sep';







}else if(todays_date_month==10){







todays_date_month='Oct';







}else if(todays_date_month==11){







todays_date_month='Nov';







}else if(todays_date_month==12){







todays_date_month='Dec';







}else{







todays_date_month='';







}







todays_formated_date = todays_date_date + " " + todays_date_month + " " + todays_date_year;







if( Date.parse(selected_date) != Date.parse(todays_formated_date) ){







<?php if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away'){?>







start_time_updated_as_per_selected_date = pickup_opening_time;







<?php }







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){?>







start_time_updated_as_per_selected_date = delivery_opening_time;







<?php }?>







//alert('Different date, so starting time is store openning time '+delivery_opening_time + pickup_opening_time);







}else if( Date.parse(selected_date) == Date.parse(todays_formated_date) ){







//if current time is grater than openning time then show current time







<?php if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away'){?>







//alert(curtime +"||"+ pickup_opening_time);







if(Date.parse('22 Sep 2017 '+curtime) <= Date.parse('22 Sep 2017 '+pickup_opening_time)){







start_time_updated_as_per_selected_date = pickup_opening_time;







}







if(Date.parse('22 Sep 2017 '+curtime) > Date.parse('22 Sep 2017 '+pickup_opening_time)){







start_time_updated_as_per_selected_date = ByConsoleWooODTStartTimeByInterval(cur_hour,cur_minute); // check this function in wp_footer







if(Date.parse('11 Jan 2018 '+start_time_updated_as_per_selected_date) >= Date.parse('11 Jan 2018 <?php echo esc_html(get_option('byconsolewooodt_opening_hours_to'));?>')){







	service_status="closed";







	}







}







<?php }







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){?>







if(Date.parse('22 Sep 2017 '+curtime) <= Date.parse('22 Sep 2017 '+delivery_opening_time)){







start_time_updated_as_per_selected_date = delivery_opening_time;







}







if(Date.parse('22 Sep 2017 '+curtime) > Date.parse('22 Sep 2017 '+delivery_opening_time)){







start_time_updated_as_per_selected_date = ByConsoleWooODTStartTimeByInterval(cur_hour,cur_minute); // check this function in wp_footer







//alert('start_time_updated_as_per_selected_date : '+start_time_updated_as_per_selected_date);







if(Date.parse('11 Jan 2018 '+start_time_updated_as_per_selected_date) >= Date.parse('11 Jan 2018 <?php echo esc_html(get_option('byconsolewooodt_delivery_hours_to'));?>')){







	service_status="closed";







	}















}







<?php }?>







//alert('equal date, so starting time is current time '+start_time_updated_as_per_selected_date)







}else{







alert('You have bug in this version of plugin, please update the plugin');







}







<?php







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='levering'){







?>







/*







echo 'if(curtime > "'.get_option('byconsolewooodt_delivery_hours_from').'"){';







echo 'var start_time=curtime;';







echo '}else{';







echo 'var start_time="'.get_option('byconsolewooodt_delivery_hours_from').'";}';







//echo 'alert(start_time);';







*/







//alert("service_status : "+service_status);







//alert(start_time_updated_as_per_selected_date);







//alert('<?php //echo get_option('byconsolewooodt_delivery_hours_to');?>');















if(service_status=='closed'){







	jQuery('p.byc_service_time_closed').html('<?php echo __("We are closed now, please select another day","byconsole-woo-order-delivery-time");?>');







	//alert('time_field_identifier : '+time_field_identifier);







	jQuery(time_field_identifier).css("dispaly:none");







	jQuery(time_field_identifier).addClass("byc_closed_now");







	}else{







jQuery(time_field_identifier).css("dispaly:block");







jQuery(time_field_identifier).removeClass("byc_closed_now");







jQuery('p.byc_service_time_closed').html('');







jQuery(time_field_identifier).timepicker({







//if it is not today's date selected in dateicker then do not do the past time resriction 







//if(jQuery(".byconsolewooodt_widget_date_field").datepicker( "getDate" )!= new Date();















"minTime": start_time_updated_as_per_selected_date,







"maxTime": "<?php echo get_option('byconsolewooodt_delivery_hours_to');?>",







"disableTextInput": "true",







"disableTouchKeyboard": "true",







"scrollDefault": "now",







"step": "15",







"selectOnBlur": "true",







"timeFormat": "<?php echo get_option('byconsolewooodt_hours_format');?>"







});		







		}















<?php







}







// lock the time selection based on admin settings for pickup time







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']=='take_away'){







?>







//alert("service_status : "+service_status);







if(service_status=='closed'){







	jQuery('p.byc_service_time_closed').html('<?php echo __("We are closed now, please select another day","byconsole-woo-order-delivery-time");?>');







	jQuery(time_field_identifier).css("dispaly:none");







	//alert('time_field_identifier : '+time_field_identifier);







	jQuery(time_field_identifier).css("dispaly:none");







	jQuery(time_field_identifier).addClass("byc_closed_now");







	}else{







jQuery(time_field_identifier).css("dispaly:block");







jQuery(time_field_identifier).removeClass("byc_closed_now");







jQuery('p.byc_service_time_closed').html('');







jQuery(time_field_identifier).timepicker({







"minTime": start_time_updated_as_per_selected_date,







"maxTime": "<?php echo get_option('byconsolewooodt_opening_hours_to');?>",







"disableTextInput": "true",







"disableTouchKeyboard": "true",







"scrollDefault": "now",







"step": "15",







"selectOnBlur": "true",







"timeFormat": "<?php echo get_option('byconsolewooodt_hours_format');?>"







});







	}







<?php







}







// if no delivery type is not selected then show all times







if($byconsolewooodt_delivery_widget_cookie_array['byconsolewooodt_widget_type_field']==''){







?>







jQuery(time_field_identifier).timepicker({







"disableTextInput": "true",







"disableTouchKeyboard": "true",







"scrollDefault": "now",







"step": "15",







"selectOnBlur": "true",







"timeFormat": "<?php echo get_option('byconsolewooodt_hours_format');?>"







});







<?php







}	







?>







} // End of function ByconsolewooodtDeliveryWidgetTimePopulate







</script>







<?php







if(is_checkout()){// execute on woocommerce check out page only







//date and time fields population by plugin settings page







?>







<script>







jQuery(document).ready(function(){







<?php







if(get_option('byconsolewooodt_preorder_days')==''){// if no pre-order date is not set in settings page







?>







jQuery("#byconsolewooodt_delivery_date").datepicker({







minDate: 0,







showAnim: "slideDown",







altField: "#byconsolewooodt_delivery_date_alternate",







altFormat: "dd/m/yy",







onSelect: function(){jQuery("#byconsolewooodt_delivery_time").timepicker("remove"); jQuery("#byconsolewooodt_delivery_time").val(''); ByconsolewooodtDeliveryWidgetTimePopulate("#byconsolewooodt_delivery_date","#byconsolewooodt_delivery_time");} // reset timepicker on date selection to get new time value depending date selected here AND THEN call call time population function







});







<?php







}else{//if no pre-order date is set in settings page do the date selection restriction







?>







jQuery( "#byconsolewooodt_delivery_date" ).datepicker({ 







minDate: 0,







maxDate: "<?php echo get_option('byconsolewooodt_preorder_days');?>D",







showOtherMonths: true,







selectOtherMonths: true,







showAnim: "slideDown",







altField: "#byconsolewooodt_delivery_date_alternate",







altFormat: "dd/m/yy",







onSelect: function(){jQuery("#byconsolewooodt_delivery_time").timepicker("remove"); jQuery("#byconsolewooodt_delivery_time").val(''); ByconsolewooodtDeliveryWidgetTimePopulate("#byconsolewooodt_delivery_date","#byconsolewooodt_delivery_time");} // reset timepicker on date selection to get new time value depending date selected here AND THEN call call time population function







});







<?php	}	?>







})







jQuery(document).ready(function(){







//jQuery("#byconsolewooodt_delivery_date_alternate").css("display","none");







jQuery(".byconsolewooodt_widget_date_field").val("");







jQuery(".byconsolewooodt_widget_time_field").val("");







});







</script>







<?php







// refresh the page once delivery type is changed and if the checkout page dont have the widget present (if it has widget present it will be refresh by widget itself)







//check if it is checkout page







//check if widget is present on checkout page







//if widget is not present create it and make it hide







echo '<div style="display:none;">';







the_widget( 'byconsolewooodt_widget' );







echo '</div>';







?>















<script>







//alertboxes to translate







jQuery(document).ready(function() {







jQuery('#byconsolewooodt_delivery_time').on('click',function(){







			if(! jQuery('#byconsolewooodt_delivery_time').hasClass('ui-timepicker-input')){







				//alert("checkout");







				alert("<?php echo __("Please select date again","byconsole-woo-order-delivery-time");?>");







				}







			});















jQuery('#byconsolewooodt_delivery_time').attr("readonly");















jQuery("#byconsolewooodt_delivery_date").prop("readonly",true);























})







</script>















<?php







}// is_checkout







else







{







?>







<script>







//alertboxes to translate







jQuery(document).ready(function() {







		jQuery('.byconsolewooodt_widget_time_field').on('click',function(){







			if(! jQuery('.byconsolewooodt_widget_time_field').hasClass('ui-timepicker-input')){







				//alert("widget");







				alert("<?php echo __("Please select date again","byconsole-woo-order-delivery-time");?>");







				}







			});







			







			







});







</script>







<?php















	







	} // !is_checkout







	







	















} //byconsolewooodt_footer_script







add_action('wp_footer','byconsolewooodt_footer_script');







//add_action('wp_footer','woocommerce_package_rates',999);















add_action('wp_footer','recalculate_shipping');







function recalculate_shipping(){







foreach (WC()->cart->get_cart() as $key => $value) {







    WC()->cart->set_quantity($key, $value['quantity']+1);







    WC()->cart->set_quantity($key, $value['quantity']);







    break;





}





}



?>