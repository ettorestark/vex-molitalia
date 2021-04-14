<?php

/**
 * Class WCST_Compatibilities
 * Loads all the compatibilities files we have in compatibility folder
 */
class WCST_Compatibilities {

	public static function load_all_compatibilities() {
		if ( isset( $_GET['wcst_disable'] ) && $_GET['wcst_disable'] == 'yes' && is_user_logged_in() && current_user_can( 'administrator' ) ) {
			return;
		}
		// load all the WCST_Compatibilities files automatically
		foreach ( glob( plugin_dir_path( WCST_PLUGIN_FILE ) . 'compatibility/*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );
			if ( isset( $file_data['basename'] ) && 'index.php' == $file_data['basename'] ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}
}

/** Hooked over 999 so that all the plugins got initiated by that time */
add_action( 'plugins_loaded', array( 'WCST_Compatibilities', 'load_all_compatibilities' ), 999 );
