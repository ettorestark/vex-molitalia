<?php

/**
 * this add logs in txt file inside WP uploads folder
 *
 * @param string $string
 * @param type $filename
 * @param type $mode
 *
 * @return boolean
 */
if ( ! function_exists( 'wcst_logging' ) ) {
	function wcst_logging( $string, $filename = "default.txt", $mode = 'a' ) {
		if ( empty( $string ) ) {
			return false;
		}
		if ( ( WCST_Common::$is_force_debug === true ) || ( WP_DEBUG === true && ! is_admin() ) ) {
			$date_obj = new DateTime();
			$date_obj->setTimezone( new DateTimeZone( WCST_Common::get_site_timezone_string() ) );
			$date_obj->setTimestamp( current_time( 'timestamp' ) );
			$curTime = $date_obj->format( "M d, Y H.i.s" );

			$upload_dir = wp_upload_dir();
			$base_path  = $upload_dir['basedir'] . '/xlplugins/sales-trigger';
			if ( ! file_exists( $base_path ) ) {
				mkdir( $base_path, 0777, true );
			}
			$filename  = str_replace( '.txt', '-' . date( 'Y-m' ) . '.txt', $filename );
			$file_path = $base_path . '/' . $filename;
			$file      = fopen( $file_path, $mode );
			$curTime   = $curTime . ': ';
			$string    = "\r\n" . $curTime . $string;
			fwrite( $file, $string );
			fclose( $file );
		}
	}
}

if ( ! function_exists( 'xlplugins_force_log' ) ) {
	function xlplugins_force_log( $string, $filename = "force.txt", $mode = 'a' ) {

		if ( empty( $string ) ) {
			return false;
		}

		$current_date_obj = new DateTime( 'now', new DateTimeZone( 'UTC' ) );

		$upload_dir = wp_upload_dir();
		$base_path  = $upload_dir['basedir'] . '/xlplugins';

		if ( ! file_exists( $base_path ) ) {
			mkdir( $base_path, 0777, true );
		}
		$filename  = str_replace( '.txt', '-' . date( "Y-m" ) . '.txt', $filename );
		$file_path = $base_path . '/' . $filename;
		$file      = fopen( $file_path, $mode );
		$curTime   = $current_date_obj->format( "M d, Y H.i.s" ) . ': ';
		$string    = "\r\n" . $curTime . $string;
		fwrite( $file, $string );
		fclose( $file );

		return true;
	}
}
