<?php

class WCST_Triggers_Data {

	public $ID = null;

	/**
	 * Contains all triggers data
	 * @var array
	 */
	protected $wcst_trigger_data = array();
	protected $wcst_product_metadata = array();
	public $wcst_overridden_triggers = array();


	/**
	 * WCST_Triggers_Data constructor.
	 * Construct to call hooks and setting up properties
	 *
	 * @param $post_id
	 */
	public function __construct( $post_id ) {

		$this->ID = $post_id;
	}

	/**
	 * Hooked over `wp`
	 * Checks if single product page
	 * Checks and prepare triggers data to be called in core file
	 */
	public function wcst_maybe_process_data() {
		global $post, $wpdb;

		$post_id            = $this->ID;
		$overriden_triggers = array();

		if ( ! empty( $this->wcst_trigger_data ) ) {
			return $this->wcst_trigger_data;
		}

		$xl_transient_obj = XL_Transient::get_instance();
		$xl_cache_obj     = XL_Cache::get_instance();

		$field_option_data = WCST_Common::get_default_settings();

		$meta_query = apply_filters( 'wcst_product_meta_query', $wpdb->prepare( "SELECT meta_key,meta_value  FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $post_id, "%_wcst_data%" ) );

		$cache_key = 'wcst_product_meta_' . $this->ID;
		/**
		 * Setting xl cache and transient for product meta
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
		if ( false !== $cache_data ) {
			$get_product_wcst_meta = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );

			if ( false !== $transient_data ) {
				$get_product_wcst_meta = $transient_data;
			} else {
				$get_product_wcst_meta = $wpdb->get_results( $meta_query, ARRAY_A );
				$xl_transient_obj->set_transient( $cache_key, $get_product_wcst_meta, 7200, 'sales-trigger' );
			}
			$xl_cache_obj->set_cache( $cache_key, $get_product_wcst_meta, 'sales-trigger' );
		}

		if ( $get_product_wcst_meta ) {
			$this->wcst_product_metadata = wp_parse_args( $this->get_parsed_query_results( $get_product_wcst_meta ), $field_option_data );
			foreach ( WCST_Triggers::get_triggers() as $key => $triggers ) {
				if ( is_array( $triggers ) ) {
					foreach ( $triggers['triggers'] as $trigger ) {
						if ( ! isset( $this->wcst_product_metadata[ '_wcst_data_wcst_' . $trigger['slug'] . '_mode' ] ) ) {
							continue;
						}
						if ( $this->wcst_product_metadata[ '_wcst_data_wcst_' . $trigger['slug'] . '_mode' ] == 0 ) {
							continue;
						}
						array_push( $overriden_triggers, $trigger['slug'] );
						$this->wcst_trigger_data[][ $trigger['slug'] ][] = $this->parse_key_value( $this->wcst_product_metadata, $trigger['slug'], 'product' );
					}
				}
			}
		}

		WCST_Common::insert_log( "Overridden Triggers are--" );
		WCST_Common::insert_log( print_r( $overriden_triggers, true ) );
		$this->wcst_overridden_triggers = $overriden_triggers;
		$get_all_triggers               = WCST_Triggers::get_all();
		$triggers_left                  = array();

		foreach ( $get_all_triggers as $triggers ) {
			if ( ! in_array( $triggers->slug, $overriden_triggers ) ) {
				array_push( $triggers_left, $triggers->slug );
			}
		}

		if ( empty( $triggers_left ) ) {
			return $this->wcst_trigger_data;
		}

		$args = array(
			'post_type'        => WCST_Common::get_trigger_post_type_slug(),
			'post_status'      => 'publish',
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'nopaging'         => true,
			'meta_query'       => array(
				array(
					'key'     => '_wcst_data_choose_trigger',
					'value'   => $triggers_left,
					'compare' => 'IN',
				),
			),
			'suppress_filters' => false, //wpml compatibility
		);


		$args['meta_query'][]           = array(
			'key'     => '_wcst_data_showon_product',
			'value'   => 'yes',
			'compare' => '=',
		);
		$args['meta_query']['relation'] = 'AND';

		$cache_key = 'wcst_trigger_query_';

		// handling for WPML
		if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== "" ) {
			$cache_key .= ICL_LANGUAGE_CODE . "_";
		}

		$cache_key .= md5( json_encode( $args ) );

		do_action( 'wcst_before_query', $post_id );

		/**
		 * Setting xl cache and transient for Sales Trigger query
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
		if ( false !== $cache_data ) {
			$triggers_data = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );

			if ( false !== $transient_data ) {
				$triggers_data = $transient_data;
			} else {
				$contents = new WP_Query( $args );
				$xl_transient_obj->set_transient( $cache_key, $contents->posts, 7200, 'sales-trigger' );
				$triggers_data = $contents->posts;
			}
			$xl_cache_obj->set_cache( $cache_key, $triggers_data, 'sales-trigger' );
		}

		do_action( 'wcst_after_query', $post_id );

		if ( is_array( $triggers_data ) && count( $triggers_data ) > 0 ) {
			WCST_Common::insert_log( 'Query have Triggers.' );
			WCST_Common::insert_log( print_r( $triggers_data, true ) );
			foreach ( $triggers_data as $post_id ) {
				$slug = get_post_meta( $post_id, '_wcst_data_choose_trigger', true );

				${'slug_' . $slug} = (int) apply_filters( 'wcst_trigger_' . $slug . '_data_length', 0 );
				WCST_Common::insert_log( 'data length for ' . $slug . ' -- ' . ${'slug_' . $slug} );

				if ( ${'slug_' . $slug} > 0 && isset( $this->wcst_trigger_data[ $slug ] ) && ${'slug_' . $slug} == count( $this->wcst_trigger_data[ $slug ] ) ) {
					continue;
				}
				if ( WCST_Common::match_groups( $post_id, $this->ID ) ) {
					WCST_Common::insert_log( 'Get Passes By Rules: - ' . $post_id );
					$this->wcst_trigger_data[][ $slug ][ $post_id ] = $this->get_single_instance_data( $post_id, $slug, $overriden_triggers );
				}
			}
		}

		return $this->wcst_trigger_data;
	}

	protected function get_parsed_query_results( $results ) {

		$parsed_results = array();
		foreach ( $results as $result ) {
			$parsed_results[ $result['meta_key'] ] = $result['meta_value'];
		}

		return $parsed_results;
	}

	/**
	 * Parse and prepare data for single trigger
	 *
	 * @param $data Array Options data
	 * @param $trigger String Trigger slug
	 * @param string $mode options|product
	 *
	 * @return array
	 */
	public function parse_key_value( $data, $trigger, $mode = 'options' ) {
		$trigger_data = array();


		$prepare_key = "_wcst_data_wcst_" . $trigger . "_";


		foreach ( $data as $key => $field_val ) {


			if ( strpos( $key, $prepare_key ) === false ) {
				continue;
			}

			$key                  = str_replace( $prepare_key, "", $key );
			$trigger_data[ $key ] = apply_filters( 'wcst_filter_values', maybe_unserialize( $field_val ), $key, $trigger );
		}

		$trigger_data['mode'] = $mode;


		return apply_filters( 'wcst_filter_trigger_data', $trigger_data, $trigger );
	}

	public function get_single_instance_data( $content_id, $slug, $overriden_triggers = array() ) {

		global $wpdb;
		if ( in_array( $slug, $overriden_triggers ) ) {
			return false;
		}

		$xl_transient_obj = XL_Transient::get_instance();
		$xl_cache_obj     = XL_Cache::get_instance();

		$meta_query = apply_filters( 'wcst_product_meta_query', $wpdb->prepare( "SELECT meta_key,meta_value  FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $content_id, "%_wcst_data_wcst_" . $slug . "%" ) );

		$cache_key = 'wcst_trigger_meta_' . $content_id;

		/**
		 * Setting xl cache and transient for product meta
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
		if ( false !== $cache_data ) {
			$get_product_wcst_meta = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );

			if ( false !== $transient_data ) {
				$get_product_wcst_meta = $transient_data;
			} else {
				$get_product_wcst_meta = $wpdb->get_results( $meta_query, ARRAY_A );
				$xl_transient_obj->set_transient( $cache_key, $get_product_wcst_meta, 7200, 'sales-trigger' );
			}
			$xl_cache_obj->set_cache( $cache_key, $get_product_wcst_meta, 'sales-trigger' );
		}

		$get_position = get_post_meta( $content_id, '_wcst_data_position', true );

		if ( ! $get_product_wcst_meta ) {
			return false;
		}

		$get_parsed_data             = $this->parse_key_value( wp_parse_args( $this->get_parsed_query_results( $get_product_wcst_meta ), $this->parse_default_args_by_trigger( $this->get_parsed_query_results( $get_product_wcst_meta ), $slug ) ), $slug, 'options' );
		$get_parsed_data['position'] = $get_position;

		return $get_parsed_data;
	}

	public function parse_default_args_by_trigger( $data, $trigger ) {
		$field_option_data = WCST_Common::get_default_settings();


		foreach ( $field_option_data as $slug => $value ) {

			if ( strpos( $slug, '_wcst_data_wcst_' . $trigger . '' ) !== false ) {
				$data[ $slug ] = $value;
			}
		}

		return $data;
	}

	public function get_trigger_data( $trigger_slug, $productID, $context = "product", $triggers = '', $is_override_meta = false, $skip_rules = "no" ) {
		global $wpdb;

		if ( isset( $this->wcst_trigger_data[ $trigger_slug ] ) ) {
			return $this->wcst_trigger_data[ $trigger_slug ];
		}

		if ( $is_override_meta === true && $context == "product" && get_post_meta( $productID, '_wcst_data_wcst_' . $trigger_slug . '_mode', true ) == 1 ) {

			$meta_query            = apply_filters( 'wcst_product_meta_query', $wpdb->prepare( "SELECT meta_key,meta_value  FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", (int) $productID, "%_wcst_data_wcst_" . $trigger_slug . "%" ) );
			$get_product_wcst_meta = $wpdb->get_results( $meta_query, ARRAY_A );

			if ( ! $get_product_wcst_meta ) {
				return;
			}

			$this->wcst_product_metadata = wp_parse_args( $this->get_parsed_query_results( $get_product_wcst_meta ) );

			$this->wcst_trigger_data[ $trigger_slug ][0] = $this->parse_key_value( $this->wcst_product_metadata, $trigger_slug, 'product' );

			return $this->wcst_trigger_data;
		} else {
			$xl_cache_obj = XL_Cache::get_instance();

			$args             = array(
				'post_type'        => WCST_Common::get_trigger_post_type_slug(),
				'post_status'      => 'publish',
				'nopaging'         => true,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'fields'           => 'ids',
				'meta_query'       => array(
					array(
						'key'     => '_wcst_data_choose_trigger',
						'value'   => $trigger_slug,
						'compare' => '=',
					),
				),
				'suppress_filters' => false
			);
			$is_by_pass_rules = false;
			if ( $triggers !== "" ) {
				$args['post__in'] = explode( ",", $triggers );

			}
			if ( $skip_rules == "yes" ) {
				$is_by_pass_rules = true;
			}

			$cache_key = "wcst_wp_query_";

			// handling for WPML
			if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== "" ) {
				$cache_key .= ICL_LANGUAGE_CODE . "_";
			}

			$cache_key .= md5( json_encode( $args ) );

			$results = $xl_cache_obj->get_cache( $cache_key, 'wcst_trigger_data' );

			if ( ! $results ) {
				$q = new WP_Query( $args );
				$xl_cache_obj->set_cache( $cache_key, $q, 'wcst_trigger_data' );
			} else {
				$q = $results;
			}

			if ( $q->found_posts > 0 ) {

				foreach ( $q->posts as $post_id ) {

					if ( $is_by_pass_rules === true || WCST_Common::match_groups( $post_id, $this->ID ) ) {
						$slug = get_post_meta( $post_id, "_wcst_data_choose_trigger", true );

						$this->wcst_trigger_data[ $slug ][ $post_id ] = $this->get_single_instance_data( $post_id, $slug, array() );
					}
				}
			}
		}

		return $this->wcst_trigger_data;
	}

	public function get_from_product( $content, $slug ) {
		global $wpdb;
		$meta_query = apply_filters( 'wcst_product_meta_query_' . $slug, $wpdb->prepare( "SELECT meta_key,meta_value  FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $this->ID, "%_wcst_data_wcst_" . $slug . "%" ) );

		$xl_transient_obj = XL_Transient::get_instance();
		$xl_cache_obj     = XL_Cache::get_instance();

		$cache_key = 'wcst_product_meta_' . $this->ID;

		/**
		 * Setting xl cache and transient for product meta
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
		if ( false !== $cache_data ) {
			$get_product_wcst_meta = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );

			if ( false !== $transient_data ) {
				$get_product_wcst_meta = $transient_data;
			} else {
				$get_product_wcst_meta = $wpdb->get_results( $meta_query, ARRAY_A );
				$xl_transient_obj->set_transient( $cache_key, $get_product_wcst_meta, 7200, 'sales-trigger' );
			}
			$xl_cache_obj->set_cache( $cache_key, $get_product_wcst_meta, 'sales-trigger' );
		}

		if ( ! $get_product_wcst_meta ) {
			return;
		}

		$get_trigger = WCST_Triggers::get( $slug );

		$wcst_product_metadata = wp_parse_args( $this->get_parsed_query_results( $get_product_wcst_meta ), $get_trigger );

		return $this->parse_key_value( $wcst_product_metadata, $slug, 'product' );
	}

	/**
	 * Calling non public property will return data from property `wcst_trigger_data`
	 *
	 * @param $name name if property to be called
	 *
	 * @return bool|mixed Data on success, false otherwise
	 */
	public function __get( $name ) {

		return isset( $this->wcst_trigger_data[ $name ] ) ? $this->wcst_trigger_data[ $name ] : false;
	}

}
