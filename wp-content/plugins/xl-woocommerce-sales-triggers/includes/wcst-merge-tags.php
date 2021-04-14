<?php

class WCST_Merge_Tags {

	/**
	 * Maybe try and parse content to found the wcst merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content = '', $slug ) {

		$get_all = self::get_all_tags();

		//iterating over all the merge tags
		if ( $get_all && is_array( $get_all ) && count( $get_all ) > 0 ) {
			foreach ( $get_all as $tag ) {
				$matches = array();

				$re  = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					if ( ! isset( $matches[0] ) ) {
						return;
					}

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match = $exact_match;

						//checking support for the trigger
						//if merge tag doesn't support the current trigger, discard and continue
						if ( ! in_array( $slug, self::merge_tag_support() ) ) {
							$content = str_replace( $old_match, '', $content );
							continue;
						}

						//replace the current tag with the square brackets [shortcode compatible]
						$exact_match = str_replace( '{{' . $tag, '[wcst_' . $tag, $exact_match );
						$exact_match = str_replace( '}}', ']', $exact_match );

						$content = str_replace( $old_match, $exact_match, $content );

					}
				}
			}
		}

		return $content;
	}

	public static function get_all_tags() {
		$tags = array( 'current_time', 'current_date', 'current_day', 'cutoff_time_left', 'today', 'product_category', 'product_category_link' );

		return $tags;
	}

	public static function merge_tag_support() {
		return array( 'guarantee', 'static_badge' );
	}

	public static function init() {

		add_shortcode( 'wcst_current_time', array( __CLASS__, 'process_time' ) );
		add_shortcode( 'wcst_current_date', array( __CLASS__, 'process_date' ) );
		add_shortcode( 'wcst_today', array( __CLASS__, 'process_today' ) );
		add_shortcode( 'wcst_current_day', array( __CLASS__, 'process_day' ) );
		add_shortcode( 'wcst_cutoff_time_left', array( __CLASS__, 'time_left_countdown' ) );
		add_shortcode( 'wcst_product_category', array( __CLASS__, 'wcst_product_category' ) );
        add_shortcode( 'wcst_product_category_link', array( __CLASS__, 'wcst_product_category_link' ) );
	}

	public static function process_date( $shortcode_attrs ) {
		$default_f = WCST_Common::wcst_get_date_format();
		$atts      = shortcode_atts( array(
			'format'     => $default_f, //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'adjustment' => '',
			'cutoff'     => '',
		), $shortcode_attrs );

		$date_obj = new DateTime();
		// $date_obj->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
		$date_obj->setTimestamp( current_time( 'timestamp' ) );
		/** cutoff functionlity starts */
		if ( $atts['cutoff'] !== '' ) {
			$date_obj_cutoff = new DateTime();
			$date_obj_cutoff->setTimestamp( current_time( 'timestamp' ) );
			$parsed_date = date_parse( $atts['cutoff'] );

			$date_defaults = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);

			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}

			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );
			/**
			 * Start assignment
			 */
			//   $date_obj_cutoff->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));

			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );

			if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {
				$date_obj->modify( '+1 days' );
			}
		}

		/** Cut-Off functionality Ends */
		if ( $atts['adjustment'] !== '' ) {
			$date_obj->modify( $atts['adjustment'] );
		}

		return date_i18n( $atts['format'], $date_obj->getTimestamp() );
	}

	public static function process_day( $shortcode_attrs ) {
		$default_f = WCST_Common::wcst_get_date_format();
		$atts      = shortcode_atts( array(
			'adjustment' => '',
			'cutoff'     => '',
		), $shortcode_attrs );

		$date_obj = new DateTime();
		//   $date_obj->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
		$date_obj->setTimestamp( current_time( 'timestamp' ) );
		/** cutoff functionlity starts */
		if ( $atts['cutoff'] !== '' ) {
			$date_obj_cutoff = new DateTime();
			$date_obj_cutoff->setTimestamp( current_time( 'timestamp' ) );
			$parsed_date = date_parse( $atts['cutoff'] );

			$date_defaults = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);

			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}

			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );

			/**
			 * Start assignment
			 */
			//   $date_obj_cutoff->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));

			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );

			if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {
				$date_obj->modify( '+1 days' );
			}
		}

		/** Cut-Off functionality Ends */
		if ( $atts['adjustment'] !== '' ) {
			$date_obj->modify( $atts['adjustment'] );
		}

		return date_i18n( 'l', $date_obj->getTimestamp() );
	}

	public static function process_today( $shortcode_attrs ) {

		$atts = shortcode_atts( array(
			'cutoff' => '',
		), $shortcode_attrs );

		$date_obj = new DateTime();
		//   $date_obj->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
		$date_obj->setTimestamp( current_time( 'timestamp' ) );
		/** cutoff functionlity starts */
		if ( $atts['cutoff'] !== '' ) {
			$date_obj_cutoff = new DateTime();
			$date_obj_cutoff->setTimestamp( current_time( 'timestamp' ) );
			$parsed_date = date_parse( $atts['cutoff'] );

			$date_defaults = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);

			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}

			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );

			/**
			 * Start assignment
			 */
			//   $date_obj_cutoff->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
			$date_obj_cutoff->setTimestamp( current_time( 'timestamp' ) );

			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );

			if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {
				return __( 'tomorrow', WCST_SLUG );
			}
		}

		return __( 'today', WCST_SLUG );
	}

	public static function process_time( $shortcode_attrs ) {
		$default_f = WCST_Common::wcst_get_time_format();

		$atts = shortcode_atts( array(
			'format'     => $default_f, //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'adjustment' => '',
		), $shortcode_attrs );

		$date_obj = new DateTime();
		//  $date_obj->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
		$date_obj->setTimestamp( current_time( 'timestamp' ) );
		if ( $atts['adjustment'] !== '' ) {

			$date_obj->modify( $atts['adjustment'] );
		}

		return date_i18n( $atts['format'], $date_obj->getTimestamp() );
	}

	public static function time_left_countdown( $shortcode_attrs ) {

		$atts = shortcode_atts( array(
			'cutoff'     => '', //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'adjustment' => '',
			'format'     => '',
			'timer'      => 'off',
		), $shortcode_attrs );

		if ( $atts['cutoff'] == '' || $atts['format'] == '' ) {
			return '';
		}

		$date_obj = new DateTime();

		//setting up current time's object
		//this will set a timestamp which is always shows current site time
		$current_Date_object = clone $date_obj;
		$current_Date_object->setTimestamp( current_time( 'timestamp' ) );
		$utc0_time = $current_Date_object->getTimestamp();

		$parsed_date = date_parse( $atts['cutoff'] );

		$date_defaults = array(
			'year'   => $date_obj->format( 'Y' ),
			'month'  => $date_obj->format( 'm' ),
			'day'    => $date_obj->format( 'd' ),
			'hour'   => $date_obj->format( 'H' ),
			'minute' => $date_obj->format( 'i' ),
			'second' => '00',
		);

		$date_cuttoff = true;
		if ( $parsed_date['day'] === false ) {
			$date_cuttoff = false;
		}

		foreach ( $parsed_date as $attrs => &$date_elements ) {
			if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
				$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
			}
		}

		$parsed_date = wp_parse_args( $parsed_date, $date_defaults );

		/**
		 * Start assignment
		 */

		$date_obj->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
		$date_obj->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );

		$interval = $current_Date_object->diff( $date_obj );

		$x = $interval->format( '%R' );

		$is_left = $x;

		if ( $is_left == '-' ) {
			if ( $date_cuttoff ) {
				return '';
			}
			$date_obj->modify( '+1 day' );
			$interval = $current_Date_object->diff( $date_obj );
		}
		$class = 'wcst_time_left_mt';

		if ( $atts['timer'] == 'on' ) {
			$class .= ' wcst_manual_timer';
		}
		/**
		 * handling php and js total days left format thing
		 */
		if ( $atts['timer'] == 'off' ) {
			$atts['format'] = str_replace( '%d', '%a', $atts['format'] );
			$atts['format'] = str_replace( '%D', '%a', $atts['format'] );
		}

		//trying to find relative time stamp
		//so the timestamp which we got after setting up the dateset and remove the offset of the current WordPress settings
		$date_starttime = new DateTime();
		$date_starttime->setTimezone( new DateTimeZone( WCST_Common::get_site_timezone_string() ) );
		$date_starttime->setTimestamp( $date_obj->getTimestamp() );
		$realTimeStamp = ( $date_obj->getTimestamp() - $date_starttime->getOffset() );

		return sprintf( '<span class="%s"> <span data-format = "%s" data-diff="%s" data-date="%s" data-display="days">%s</span></span>', $class, WCST_Common::get_time_directives_for_js( $atts['format'] ), ( $date_obj->getTimestamp() - $utc0_time ), $realTimeStamp, $interval->format( $atts['format'] ) );
	}

	public static function wcst_product_category( $shortcode_attrs ){
	    global $product;

        $atts = shortcode_atts( array(
            'id'     => '',
        ), $shortcode_attrs );

        if( '' == $atts['id'] ){
            $product_id = $product->get_id();

            $product_categories = get_the_terms( $product_id, 'product_cat' );

            if( is_array($product_categories) && count($product_categories) > 0 ){
                $productInfo = XL_WCST_Product::get_instance( $product_id );
                $term_id = apply_filters( 'wcst_static_badge_cat_id', $product_categories[0]->term_id, $productInfo );
                return get_term_by( 'id', $term_id, 'product_cat' )->name;
            }
        }
        else{
            $term = get_term_by( 'id', $atts['id'], 'product_cat' );
            return $term->name;
        }
        return '';

    }

    public static function wcst_product_category_link( $shortcode_attrs ){
        global $product;

        $atts = shortcode_atts( array(
            'id'     => '',
        ), $shortcode_attrs );

        if( '' == $atts['id'] ){

            $product_id = $product -> get_id();
            $product_categories = get_the_terms( $product_id, 'product_cat' );

            if( is_array( $product_categories ) && count( $product_categories ) > 0 ){
                $productInfo = XL_WCST_Product::get_instance( $product_id );
                $term_id = apply_filters( 'wcst_static_badge_cat_id', $product_categories[0]->term_id, $productInfo );
                $link = '<a href=" ' .get_term_link( $term_id, 'product_cat' ).' ">'.get_term_by( 'id', $term_id, 'product_cat' )->name.'</a>';
                return $link;
            }
        }
        else{
            $term = get_term_by( 'id', $atts['id'], 'product_cat' );
            $link = '<a href=" ' .get_term_link( $term->term_id, 'product_cat' ).' ">'.$term->name.'</a>';
            return $link;
        }
        return '';
    }

}

WCST_Merge_Tags::init();
