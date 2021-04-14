<?php



define('VISANET_PE_LICENSE_SECRET_KEY', '587423b988e403.69821411');

define('VISANET_PE_LICENSE_SERVER_URL', 'http://socialmediaspiders.com');

define('VISANET_PE_ITEM_REFERENCE', 'Facturacion Electronica Sunat Peru - WooCommerce Gateway');

add_action( 'init', 'validate_visanetpe' );

function validate_visanetpe()
{

	if( isset( $_POST['woocommerce_facturacion_code'] ) )
	{
		$license = $_POST['woocommerce_facturacion_code'];

		update_option( 'woocommerce_facturacion_license', $license );

		unset( $_POST['woocommerce_facturacion_code'] );

		if( empty( $license ) )
		{
			set_facturacion_disabled();

			return;
		}

		activate_facturacion_license( $license );

		return;
	}

	$license = get_option( 'woocommerce_facturacion_license', '' );

	if( empty( $license ) )
	{
		set_facturacion_disabled();

		return;
	}

	$activated = get_option( 'woocommerce_facturacion_activated', '0' );

	$last_date = get_option( 'woocommerce_facturacion_last_date', '' );

	$current_date = date( 'd/m/Y' );

	if( empty( $last_date ) && $activated )
	{
		return;
	}

	if( $last_date != $current_date )
	{
		verify_facturacion_license( $license );
	}
}

function set_facturacion_disabled()
{
	update_option( 'woocommerce_facturacion_activated', '0' );
}

function activate_facturacion_license( $license )
{
	global $GLOBALS;

	$api_params = array(
        'slm_action' => 'slm_activate',
        'secret_key' => VISANET_PE_LICENSE_SECRET_KEY,
        'license_key' => $license,
        'registered_domain' => $_SERVER['SERVER_NAME'],
        'item_reference' => urlencode( VISANET_PE_ITEM_REFERENCE ),
    );

    $query = esc_url_raw( add_query_arg( $api_params, VISANET_PE_LICENSE_SERVER_URL ) );
    $response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

    if ( is_wp_error( $response ) ){

        $GLOBALS['facturacion_message'] = "<div class=\"notice notice-error\"><p><b>". VISANET_PE_ITEM_REFERENCE .":</b> Unexpected Error! The query returned with an error.</p></div>";

        set_facturacion_disabled();

        return;

    }

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->result == 'success' )
	{
		activate_facturacion_plugin( $license_data->message );

		return;
	}

	$GLOBALS['facturacion_message'] = "<div class=\"notice notice-error\"><p><b>". VISANET_PE_ITEM_REFERENCE .":</b> $license_data->message</p></div>";

	set_facturacion_disabled();

}

function verify_facturacion_license( $license )
{
	$api_params = array(
		'slm_action' => 'slm_check',
		'secret_key' => VISANET_PE_LICENSE_SECRET_KEY,
		'license_key' => $license,
	);

	$response = wp_remote_get( add_query_arg( $api_params, VISANET_PE_LICENSE_SERVER_URL ), array( 'timeout' => 20, 'sslverify' => false ) );

	if ( is_wp_error( $response ) ){

        update_option( 'woocommerce_facturacion_activated', '0' );

        set_facturacion_disabled();

         $GLOBALS['facturacion_message'] = "<div class=\"notice notice-error\"><p><b>". VISANET_PE_ITEM_REFERENCE .":</b> Unexpected Error! The query returned with an error.</p></div>";

        return;

    }

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );


    if( $license_data->result == 'success' )
	{
		activate_facturacion_plugin( $license_data->message );

		return;
	}

	update_option( 'woocommerce_facturacion_activated', '0' );

	$GLOBALS['facturacion_message'] = "<div class=\"notice notice-error\"><p><b>". VISANET_PE_ITEM_REFERENCE .":</b> $license_data->message</p></div>";

	set_facturacion_disabled();

}


function activate_facturacion_plugin( $message )
{
	update_option( 'woocommerce_facturacion_activated', '1' );

	update_option( 'woocommerce_facturacion_last_date', date( 'd/m/Y' ) );

	$GLOBALS['facturacion_message'] = "<div class=\"notice notice-success\"><p><b>". VISANET_PE_ITEM_REFERENCE .":</b> $message</p></div>";
}