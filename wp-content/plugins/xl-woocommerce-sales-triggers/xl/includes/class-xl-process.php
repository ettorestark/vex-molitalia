<?php

/**
 * Basic process class that detect request and pass to respective class
 *
 * @author XLPlugins
 * @package XLCore
 */
class XL_process {
	/**
	 * Initiate hooks
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'parse_request_and_process' ), 14 );
		add_action( 'admin_notices', array( 'XL_admin_notifications', 'render' ) );
		add_action( 'wp_loaded', array( 'XL_admin_notifications', 'hide_notices' ) );
	}

	public function parse_request_and_process() {
		//Initiating the license instance to handle submissions  (submission can redirect page two that can cause "header already sent" issue to be arised)
		// Initiating this to over come that issue
		if ( isset( $_GET['page'] ) && $_GET['page'] == XL_dashboard::get_expected_slug() && isset( $_GET['tab'] ) && $_GET['tab'] == 'licenses' ) {
			XL_licenses::get_instance();
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] == XL_dashboard::get_expected_slug() && isset( $_GET['tab'] ) && $_GET['tab'] == 'licenses' ) {
			if ( isset( $_GET['ts'] ) && isset( $_GET['response'] ) && ( time() - $_GET['ts'] ) < 5 && $_GET['response'] == 1 ) {
				XL_admin_notifications::add_notification( array(
					'plugin_license_notif' => array(
						'type'           => 'success',
						'is_dismissable' => true,
						'content'        => sprintf( __( '<p> Plugin successfully deactivated. </p>', 'xlplugins' ) )
					)
				) );
			}
		}


		//Handling Optin
		if ( isset( $_GET['xl-optin-choice'] ) && isset( $_GET['_xl_optin_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_xl_optin_nonce'], 'xl_optin_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'xlplugins' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheating huh?', 'xlplugins' ) );
			}

			$optin_choice = sanitize_text_field( $_GET['xl-optin-choice'] );
			if ( $optin_choice == "yes" ) {
				XL_optIn_Manager::Allow_optin();
				if ( isset( $_GET['ref'] ) ) {
					XL_optIn_Manager::update_optIn_referer( filter_input( INPUT_GET, 'ref' ) );
				}

			} else {
				XL_optIn_Manager::block_optin();
			}


			do_action( 'xl_after_optin_choice', $optin_choice );
		}


		//Initiating the license instance to handle submissions  (submission can redirect page two that can cause "header already sent" issue to be arised)
		// Initiating this to over come that issue
		if ( isset( $_GET['page'] ) && $_GET['page'] == XL_dashboard::get_expected_slug() && isset( $_GET['tab'] ) && $_GET['tab'] == 'support' && isset( $_POST['xl_submit_support'] ) ) {
			$instance_support = XL_Support::get_instance();


			if ( filter_input( INPUT_POST, 'choose_addon' ) == "" || filter_input( INPUT_POST, 'comments' ) == "" ) {
				$instance_support->validation = false;
				XL_admin_notifications::add_notification( array(
					'support_request_failure' => array(
						'type'           => 'error',
						'is_dismissable' => true,
						'content'        => __( '<p> Unable to submit your request.All fields are required. Please ensure that all the fields are filled out.</p>', 'xlplugins' ),

					)
				) );
			} else {
				$instance_support->xl_maybe_push_support_request( $_POST );
			}
		}
	}


}

new XL_process();
