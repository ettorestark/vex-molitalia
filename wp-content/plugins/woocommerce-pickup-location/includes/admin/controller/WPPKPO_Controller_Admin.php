<?php
@session_start();

class WPPKPO_Controller_Admin {

    private $wppkpo_view;
    private $required = '2.2.0';
    public $upload_directory = "wp_pkpo_files"; 
    public $delimiter = ","; // Delimiter to be used in handling csv files, default is ','
    public $enclosure = '"'; // Enclosure to be used in handling csv files, default is '"' 

    public function __construct() {
        $this->wppkpo_view = new WPPKPO_View_Admin();
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
        add_action('admin_menu', array($this, 'wp_pkpo_menu'));
        add_action('admin_enqueue_scripts', array($this, 'wp_pkpo_admin_enqueue_scripts'));
        add_action('init', array($this, 'wp_pkpo_pickup_location'));
        add_action('add_meta_boxes', array($this, 'wp_pkpo_register_meta_boxes'));
        add_action('save_post', array($this, 'wp_pkpo_save_meta_box'));
        add_action('admin_footer', array($this, 'wp_pkpo_enqueue_footer'));
        add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'wp_pkpo_admin_order_details'), 10, 1);
        add_action('wp_ajax_check_valid_date', array($this, 'wp_pkpo_check_valid_date'));
        add_action('wp_ajax_nopriv_check_valid_date', array($this, 'wp_pkpo_check_valid_date'));
        add_action('wp_ajax_check_valid_time', array($this, 'wp_pkpo_check_valid_time'));
        add_action('wp_ajax_nopriv_check_valid_time', array($this, 'wp_pkpo_check_valid_time'));
        add_filter('manage_pickup_location_posts_columns', array($this, 'wp_pkpo_location_table_columns_head'), 10, 1);
        add_action('manage_pickup_location_posts_custom_column', array($this, 'wp_pkpo_location_table_columns_content'), 10, 2);
        add_filter('plugin_action_links_' . WPPKPO_PLUGIN_BASENAME, array($this, 'add_action_links'));
        add_action( 'manage_shop_order_posts_custom_column'  , array($this, 'wp_pkpo_order_posts_column'), 10, 2);
        add_action('wp_ajax_nopriv_neareststores', array($this, 'wp_pkpo_neareststore'));
        add_action('wp_ajax_neareststores', array($this, 'wp_pkpo_neareststore'));
    }

    public function wp_pkpo_neareststore() {
        global $wpdb;
        $marker_table_name = $wpdb->prefix . 'pkpo_markers';
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'pickup_location',
            'post_status' => 'publish',
        );
        
        $pickups = get_posts($args);
        if (count($pickups)) {
            foreach ($pickups as $pickup) {
                $markers = array();
                $pickup_id = $pickup->ID;
                $data = $wpdb->get_results("select * from $marker_table_name where post_id=$pickup_id", ARRAY_A);
                if (count($data) <= 0){
                    $markers["post_id"] = $pickup_id;
                    $markers["name"] = $pickup->post_title;
                    $markers["address"] = $pickup->post_content;
                    $markers["lat"] = get_post_meta($pickup_id, "wp_pkpo_lat", true);
                    $markers["lng"] = get_post_meta($pickup_id, "wp_pkpo_long", true);
                    $wpdb->insert($marker_table_name, $markers);
                }
            }
        }

        $center_lat = $_GET["lat"];
        $center_lng = $_GET["lng"];
        $radius = $_GET["radius"];
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        if (isset($_GET["radius"])) {
            $sql = "SELECT id, name, address, lat, lng,post_id, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( lat ) ) ) ) AS distance FROM $marker_table_name HAVING distance < '$radius' ORDER BY distance LIMIT 0 , 20";
        } else {
            $wp_pkpo_settings = get_option('wp_pkpo_options');
            $defaultlagLng = $wp_pkpo_settings["wp_pkpo_default_position"];
            $latLng = explode(",", $defaultlagLng);
            $center_lat = $latLng[0];
            $center_lng = $latLng[1];
            $sql = "SELECT id, name, address, lat, lng,post_id, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( lat ) ) ) ) AS distance FROM $marker_table_name  ORDER BY distance ";
        }
        $results = $wpdb->get_results($sql, ARRAY_A);
        header("Content-type: text/xml");
        foreach ($results as $row) {
            $post_id = $row['post_id'];
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("id", $row['id']);
            $newnode->setAttribute("name", $row['name']);
            $newnode->setAttribute("address", $row['address']);
            $newnode->setAttribute("lat", $row['lat']);
            $newnode->setAttribute("lng", $row['lng']);
            $newnode->setAttribute("distance", $row['distance']);
            $newnode->setAttribute("post_id", $row['post_id']);
            $newnode->setAttribute("wp_pkpo_opening_hours", get_post_meta($post_id, "wp_pkpo_opening_hours", true));
            $newnode->setAttribute("wp_pkpo_closing_hours", get_post_meta($post_id, "wp_pkpo_closing_hours", true));
            $newnode->setAttribute("wp_pkpo_min_time", get_post_meta($post_id, "wp_pkpo_min_time", true));
            $newnode->setAttribute("wp_pkpo_max_time", get_post_meta($post_id, "wp_pkpo_max_time", true));
            $term_list = wp_get_post_terms($post_id, 'pickup_days', array("fields" => "all"));
            $pickup_week_days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
            $pickup_terms_week_days = array();
            $pickup_location_days = '';
            
            if (isset($term_list[0])) {
                foreach ($term_list as $terms) {
                    $pickup_terms_week_days[] = $terms->slug;
                }
            }

            if (isset($pickup_week_days[0])) {
                foreach ($pickup_week_days as $pickup_week_day) {
                    $pickup_week_day = strtolower($pickup_week_day);

                    if (!in_array(trim($pickup_week_day), $pickup_terms_week_days)) {
                        if ($pickup_week_day === 'sunday')
                            $pickup_location_days .= '0,';
                        if ($pickup_week_day === 'monday')
                            $pickup_location_days .= '1,';
                        if ($pickup_week_day === 'tuesday')
                            $pickup_location_days .= '2,';
                        if ($pickup_week_day === 'wednesday')
                            $pickup_location_days .= '3,';
                        if ($pickup_week_day === 'thursday')
                            $pickup_location_days .= '4,';
                        if ($pickup_week_day === 'friday')
                            $pickup_location_days .= '5,';
                        if ($pickup_week_day === 'saturday')
                            $pickup_location_days .= '6,';
                    }
                }
            }

            $left_trim_pickupdays = ltrim($pickup_location_days, ",");
            $right_trim_pickupdays = rtrim($left_trim_pickupdays, ",");
            $newnode->setAttribute("wp_pkpo_working_days", $right_trim_pickupdays);
        }
        echo $dom->saveXML();
        die();
    }

    public function add_action_links($links) {
        $mylinks = array(
            '<a href="' . admin_url('edit.php?post_type=pickup_location&page=wp_pkpo_settings') . '">Settings</a>',
        );
        return array_merge($links, $mylinks);
    }

    public function wp_pkpo_check_valid_time() {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $wp_pickup_wrong_time_msg = $wp_pkpo_settings['wp_pickup_wrong_time'];
        $open_time = strtotime($_POST['open_time']);
        $close_time = strtotime($_POST['close_time']);
        $time_selected = $_POST['time_selected'];
        $date_selected = $_POST['date_selected'];
        $min_time = $_POST['min_time'];
        $max_time = $_POST['max_time'];
        $output = array("result" => "true");
        $output = $this->wp_pkpo_check_min_max_time($date_selected, $time_selected, $min_time, $max_time);
        $time_selected = strtotime($_POST['time_selected']);
        if (($time_selected >= $open_time) && ($time_selected <= $close_time))
            $output = array("result" => "true");
        else
             $output = array("result" => "false","issue" => "shop_closed", "error_message" => $wp_pickup_wrong_time_msg);

        echo json_encode($output);
        die();
    }

    public function wp_pkpo_check_valid_date() {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $wp_pickup_wrong_date_msg = $wp_pkpo_settings['wp_pickup_wrong_date'];
        $off_days = explode(",", $_POST['off_days']);
        $date_selected = $_POST['date_selected'];
        $time_selected = $_POST['time_selected'];
        $min_time = $_POST['min_time'];
        $max_time = $_POST['max_time'];
        $timestamp = strtotime($date_selected);
        $day = date("w", $timestamp);
        $output = array("result" => "true");
        $output = $this->wp_pkpo_check_min_max_time($date_selected, $time_selected, $min_time, $max_time);
        if (in_array($day, $off_days))
            $output = array("result" => "false","issue" => "off_days", "error_message" => $wp_pickup_wrong_date_msg);

        echo json_encode($output);
        die();
    }

    private function wp_pkpo_check_min_max_time($date_selected, $time_selected, $min_time, $max_time) {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $wp_pickup_max_time_msg = $wp_pkpo_settings['wp_pickup_max_time_msg'];
        $wp_pickup_min_time_msg = $wp_pkpo_settings['wp_pickup_min_time_msg'];
        $today_timestamp = time();
        $output = array("result" => "true");
        $min_time = (int)trim($min_time);
        $max_time = (int)trim($max_time);
        if (!empty($date_selected) && !empty($time_selected)) {
            $selected_date_time = strtotime($date_selected . " " . $time_selected);
            $hour_diff = round(($selected_date_time - $today_timestamp) / 3600, 0);
            if (!empty($min_time)  && (($min_time) > ($hour_diff))) {
                $output = array("result" => "false", "issue" => "min_time", "error_message" => sprintf($wp_pickup_min_time_msg,$min_time));
                echo json_encode($output);
                die();
            }
            if (!empty($max_time) && (($max_time) < ($hour_diff))) {
                $output = array("result" => "false", "issue" => "max_time", "error_message" => sprintf($wp_pickup_max_time_msg,$max_time));
                echo json_encode($output);
                die();
            }
        }
        return $output;
    }

    public function wp_pkpo_location_table_columns_head($defaults) {
        $wp_pkpo_columns = array();
        $address = 'wp_pkpo_address';
        $hours = 'working_hours';
        $title = 'date';

        foreach ($defaults as $key => $value) {
            if ($key === $title) {
                $wp_pkpo_columns[$address] = __('Address', 'woocommerce-pickup-location');
                $wp_pkpo_columns[$hours] = __('Working Hours', 'woocommerce-pickup-location');
            }

            $wp_pkpo_columns[$key] = $value;
            if ($key === 'title')
                $wp_pkpo_columns['title'] = __('Pickup Location', 'woocommerce-pickup-location');
        }

        return $wp_pkpo_columns;
    }

    public function wp_pkpo_location_table_columns_content($column_name, $post_ID) {

        if ($column_name === 'wp_pkpo_address') {
            $wp_pkpo_location_details = get_post($post_ID);
            echo __($wp_pkpo_location_details->post_content, 'woocommerce-pickup-location');
        }
        if ($column_name === 'working_hours') {
            $wp_pkpo_opening_hours = get_post_meta($post_ID, 'wp_pkpo_opening_hours', true);
            $wp_pkpo_closing_hours = get_post_meta($post_ID, 'wp_pkpo_closing_hours', true);
            echo __($wp_pkpo_opening_hours . ' To ' . $wp_pkpo_closing_hours, 'woocommerce-pickup-location');
        }
    }

    function wp_pkpo_admin_order_details($order) {
        $this->wppkpo_view->wp_pkpo_admin_order_details($order);
    }

    public function wp_pkpo_enqueue_footer() {
        wp_enqueue_style('wp_pkpo_jquery_ui_css', WPPKPO_PLUGIN_URL . '/assets/css/jquery.ui.css');
        wp_enqueue_script('jquery', WPPKPO_PLUGIN_URL . '/assets/js/jquery.js');
        wp_enqueue_script('jquery_ui', WPPKPO_PLUGIN_URL . '/assets/js/jquery.ui.js');
        wp_enqueue_style('wp_pkpo_timepicker_css', WPPKPO_PLUGIN_URL . '/assets/css/wickedpicker.min.css');
        wp_enqueue_script('wp_pkpo_widgetpicker', WPPKPO_PLUGIN_URL . '/assets/js/widgetpicker.js', array("jquery"));
    }

    public function wp_pkpo_pickup_location_csv() {
        $wp_pkpo_msg = '';
        $link = '';
        if (isset($_FILES['wp_pkpo_csv_file']['name'])) {
            if (($handle = fopen($_FILES['wp_pkpo_csv_file']['tmp_name'], "r")) !== FALSE) {
                fgetcsv($handle);
                while (($wp_pkpo_data = fgetcsv($handle, 2000, ",")) !== FALSE) {

                    $wp_pkpo_check_post_title = get_page_by_title($wp_pkpo_data[0], ARRAY_A, 'pickup_location');
                    if (!isset($wp_pkpo_check_post_title['ID'])) {
                        $wp_pkpo_location_post = array(
                            'post_title' => wp_strip_all_tags($wp_pkpo_data[0]),
                            'post_content' => $wp_pkpo_data[1],
                            'post_status' => 'publish',
                            'post_author' => get_current_user_id(),
                            'post_type' => 'pickup_location'
                        );

                        $wp_pkpo_post_id = wp_insert_post($wp_pkpo_location_post);

                        if ($wp_pkpo_post_id != '' && isset($wp_pkpo_post_id)) {
                            if (isset($wp_pkpo_data[2]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_contact_email', $wp_pkpo_data[2]);
                            if (isset($wp_pkpo_data[3]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_opening_hours', $wp_pkpo_data[3]);
                            if (isset($wp_pkpo_data[4]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_closing_hours', $wp_pkpo_data[4]);
                            if (isset($wp_pkpo_data[5]))
                                $this->wp_pkpo_set_taxonomy($wp_pkpo_post_id, 'pickup_days', explode(",", $wp_pkpo_data[5]));
                            if (isset($wp_pkpo_data[6]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_contact_details', $wp_pkpo_data[6]);
                            if (isset($wp_pkpo_data[7]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_lat', $wp_pkpo_data[7]);
                            if (isset($wp_pkpo_data[8]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_long', $wp_pkpo_data[8]);
                            if (isset($wp_pkpo_data[9]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_min_time', $wp_pkpo_data[9]);
                            if (isset($wp_pkpo_data[10]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pkpo_max_time', $wp_pkpo_data[10]);
                            if (isset($wp_pkpo_data[11]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pickup_city_display', $wp_pkpo_data[10]);
                            if (isset($wp_pkpo_data[12]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pickup_state_display', $wp_pkpo_data[10]);
                            if (isset($wp_pkpo_data[13]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pickup_country_display', $wp_pkpo_data[10]);
                            if (isset($wp_pkpo_data[14]))
                                update_post_meta($wp_pkpo_post_id, 'wp_pickup_zip_display', $wp_pkpo_data[10]);
                        }
                    }
                }
                fclose($handle);
            }
            $wp_pkpo_msg = __('csv file uploaded successfully', 'woocommerce-pickup-location');
        }
        else if (isset($_POST["wp_pkpo_export"])) {
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'pickup_location',
                'post_status' => 'publish',
                'suppress_filters' => true
            );
            $pickup_locations = get_posts($args);
            $loop = 0;
            $export_data[$loop] = array("pickup_title","pickup_address","pickup_email","pickup_open_time","pickup_close_time","pickup_days","pickup_details","location_lattitude","location_langitude","min_time","max_time","city","state","country","zipcode");
            $loop++;
            if ($pickup_locations) {
                foreach ($pickup_locations as $pickup) {
                    $post_id = $pickup->ID;
                    $export_data[$loop][] = $pickup->post_title;
                    $export_data[$loop][] = $pickup->post_content;
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_contact_email', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_opening_hours', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_closing_hours', true);
                    $term_list = wp_get_post_terms($post_id, 'pickup_days', array("fields" => "names"));
                    $term_list = array_values($term_list);
                    $export_data[$loop][] = implode(",", $term_list);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_contact_details', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_lat', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_long', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_min_time', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pkpo_max_time', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pickup_city_display', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pickup_state_display', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pickup_country_display', true);
                    $export_data[$loop][] = get_post_meta($post_id, 'wp_pickup_zip_display', true);
                    $loop++;
                }
               $url =  $this->arrayToCSV($export_data);
               $link =  "<a href='$url'>".__('Download File', 'woocommerce-pickup-location')."</a>";
            }
        }
        echo $this->wppkpo_view->wp_pkpo_pickup_location_csv($wp_pkpo_msg, $link);
    }

    public function wp_pkpo_set_taxonomy($postId, $taxonomy, array $fields) {
        $termIds = array();
        $loopCount = 0;

        foreach ($fields as $field) {
            $field = trim($field);
            if (empty($field))
                continue;
            $content = explode(":", $field); //slug character needs to added here from option
            if (count($content) === 1) { //no slug
                $termname = get_term_by('name', $field, $taxonomy);
                if ($termname) {
                    $slug = $termname->slug;
                    $parentId = $termname->parent;
                } else {
                    $slug = $field;
                    $parentId = 0;
                }
            } else if (count($content) > 1) {
                $slug = $content[0];
                $field = $content[1];
                $parentId = 0;
            }

            $term = get_term_by('slug', $slug, $taxonomy);

            if ($term)
                $term = wp_update_term($term->term_id, $taxonomy, array(
                    'slug' => $slug,
                    'parent' => $parentId
                ));
            else
                $term = wp_insert_term($field, $taxonomy, array(
                    'slug' => $slug,
                    'parent' => $parentId
                ));


            $termIds[] = (int) $term['term_id'];
            $termLastId = $term['term_id'];
            $loopCount++;
        }

        wp_set_object_terms($postId, $termIds, $taxonomy, FALSE);
        wp_cache_set('last_changed', time() - 1800, 'terms');
        wp_cache_delete('all_ids', $taxonomy);
        wp_cache_delete('get', $taxonomy);
        delete_option("{$taxonomy}_children");
        _get_term_hierarchy($taxonomy);
    }

    public function wp_pkpo_save_meta_box($post_id) {
        if (isset($_POST['wp_pkpo_lat']))
            update_post_meta($post_id, 'wp_pkpo_lat', $_POST['wp_pkpo_lat']);
        if (isset($_POST['wp_pkpo_lat']))
            update_post_meta($post_id, 'wp_pkpo_long', $_POST['wp_pkpo_long']);
        if (isset($_POST['wp_pkpo_opening_hours']))
            update_post_meta($post_id, 'wp_pkpo_opening_hours', $_POST['wp_pkpo_opening_hours']);
        if (isset($_POST['wp_pkpo_closing_hours']))
            update_post_meta($post_id, 'wp_pkpo_closing_hours', $_POST['wp_pkpo_closing_hours']);
        if (isset($_POST['wp_pkpo_contact_details']))
            update_post_meta($post_id, 'wp_pkpo_contact_details', $_POST['wp_pkpo_contact_details']);
        if (isset($_POST['wp_pkpo_contact_email']))
            update_post_meta($post_id, 'wp_pkpo_contact_email', $_POST['wp_pkpo_contact_email']);
        if (isset($_POST['wp_pickup_city_display']))
            update_post_meta($post_id, 'wp_pickup_city_display', $_POST['wp_pickup_city_display']);
        if (isset($_POST['wp_pickup_state_display']))
            update_post_meta($post_id, 'wp_pickup_state_display', $_POST['wp_pickup_state_display']);
        if (isset($_POST['wp_pickup_country_display']))
            update_post_meta($post_id, 'wp_pickup_country_display', $_POST['wp_pickup_country_display']);
        if (isset($_POST['wp_pickup_zip_display']))
            update_post_meta($post_id, 'wp_pickup_zip_display', $_POST['wp_pickup_zip_display']);
        if (isset($_POST['wp_pkpo_min_time']))
            update_post_meta($post_id, 'wp_pkpo_min_time', $_POST['wp_pkpo_min_time']);
        if (isset($_POST['wp_pkpo_max_time']))
            update_post_meta($post_id, 'wp_pkpo_max_time', $_POST['wp_pkpo_max_time']);
        if (isset($_POST['wp_pkpo_product_locations']))
            update_post_meta($post_id, 'wp_pkpo_product_locations', $_POST['wp_pkpo_product_locations']);
        else 
            update_post_meta($post_id, 'wp_pkpo_product_locations', array());
    }

    public function wp_pkpo_register_meta_boxes() {
        add_meta_box('wp_pkpo_pickup_location', __('Pickup Location', 'woocommerce-pickup-location'), array($this, 'wp_pkpo_pickup_location_callback'), 'pickup_location');
        add_meta_box('wp_pkpo_pickup_location_working_hour', __('Pickup Location Working Hours', 'woocommerce-pickup-location'), array($this, 'wp_pkpo_pickup_location_working_hours_callback'), 'pickup_location', 'side');
        add_meta_box('wp_pkpo_pickup_location_contact', __('Pickup Location Contacts', 'woocommerce-pickup-location'), array($this, 'wp_pkpo_pickup_location_contacts_callback'), 'pickup_location', 'side');
        add_meta_box('wp_pkpo_pickup_time_limit', __('Time Limitation', 'woocommerce-pickup-location'), array($this, 'wp_pkpo_pickup_time_limit_callback'), 'pickup_location', 'side');
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        if (isset($wp_pkpo_settings["wp_pickup_location_based_product"]) && $wp_pkpo_settings["wp_pickup_location_based_product"] === "enable") {
            add_meta_box('wp_pkpo_product_location', __('Locations', 'woocommerce-pickup-location'), array($this, 'wp_pkpo_product_location_callback'), 'product', 'side', 'low');
        }
    }

    public function wp_pkpo_pickup_location_contacts_callback() {
        global $post;
        $wp_pkpo_contact_details = '';
        $wp_pkpo_contact_email = '';
        if (isset($post->ID)) {
            $wp_pkpo_contact_details = get_post_meta($post->ID, 'wp_pkpo_contact_details', true);
            $wp_pkpo_contact_email = get_post_meta($post->ID, 'wp_pkpo_contact_email', true);
            $wp_pickup_city = get_post_meta($post->ID, 'wp_pickup_city_display', true);
            $wp_pickup_state = get_post_meta($post->ID, 'wp_pickup_state_display', true);
            $wp_pickup_country = get_post_meta($post->ID, 'wp_pickup_country_display', true);
            $wp_pickup_zip = get_post_meta($post->ID, 'wp_pickup_zip_display', true);
        }
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $this->wppkpo_view->wp_pkpo_pickup_location_contacts_callback($wp_pkpo_contact_details, $wp_pkpo_contact_email, $wp_pickup_city, $wp_pickup_state, $wp_pickup_country, $wp_pickup_zip, $wp_pkpo_settings);
    }

    public function wp_pkpo_pickup_location_working_hours_callback() {
        global $post;
        $wp_pkpo_opening_hours = '';
        $wp_pkpo_closing_hours = '';

        if (isset($post->ID)) {
            $wp_pkpo_opening_hours = get_post_meta($post->ID, 'wp_pkpo_opening_hours', true);
            $wp_pkpo_closing_hours = get_post_meta($post->ID, 'wp_pkpo_closing_hours', true);
        }

        $this->wppkpo_view->wp_pkpo_pickup_location_working_hours_callback($wp_pkpo_opening_hours, $wp_pkpo_closing_hours);
    }

    public function wp_pkpo_pickup_location_callback() {
        global $post;
        $wp_pkpo_lat = '';
        $wp_pkpo_long = '';
        $settings = get_option('wp_pkpo_options');

        if (isset($post->ID)) {
            $wp_pkpo_lat = get_post_meta($post->ID, 'wp_pkpo_lat', true);
            $wp_pkpo_long = get_post_meta($post->ID, 'wp_pkpo_long', true);
        }

        $this->wppkpo_view->wp_pkpo_pickup_location_callback($wp_pkpo_lat, $wp_pkpo_long, $settings);
    }
    
    public function wp_pkpo_pickup_time_limit_callback() {
        global $post;
        $wp_pkpo_min_time = '';
        $wp_pkpo_max_time = '';

        if (isset($post->ID)) {
            $wp_pkpo_min_time = get_post_meta($post->ID, 'wp_pkpo_min_time', true);
            $wp_pkpo_max_time = get_post_meta($post->ID, 'wp_pkpo_max_time', true);
        }

        $this->wppkpo_view->wp_pkpo_pickup_time_limit_callback($wp_pkpo_min_time, $wp_pkpo_max_time);
    }
    
    public function wp_pkpo_product_location_callback() {
        global $post;
        $wp_pkpo_locations = get_posts(array(
            'post_type' => 'pickup_location',
            'numberposts' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ));

        if (isset($post->ID)) {
            $wp_pkpo_selected_locations = get_post_meta($post->ID, 'wp_pkpo_product_locations', true);
        }

        $this->wppkpo_view->wp_pkpo_product_locations_callback($wp_pkpo_locations, $wp_pkpo_selected_locations);
    }

    public function wp_pkpo_admin_enqueue_scripts() {
        global $wpaie_settings_screen, $wp_pkpo_upload_csv_screen;
        $screen = get_current_screen();
        
        if ( $screen->id != "pickup_location" && $screen->id != $wpaie_settings_screen && $screen->id != $wp_pkpo_upload_csv_screen )
            return;
        
        wp_enqueue_script('jquery');
        wp_enqueue_style('wp_pkpo_pickup_css', WPPKPO_PLUGIN_URL . '/assets/css/woocommerce_pickup_admin.css');
        wp_register_script('wp_pkpo_pickup_js', WPPKPO_PLUGIN_URL . '/assets/js/woocommerce_pickup_admin.js', array("jquery"));
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $timeformat = array(
                'wp_pkpo_timepicker_formate' => $wp_pkpo_settings['wp_pickup_default_time_formate']
        );
        wp_localize_script( 'wp_pkpo_pickup_js', 'wp_pkpo_pickup_js_var', $timeformat );
        wp_enqueue_script('wp_pkpo_pickup_js');
        wp_enqueue_script('wp_pkpo_pickup_map_js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDxbNaqDprpp9EDiMsb-6zUELbpQjGjrm4&libraries=places');
    }

    public function wp_pkpo_pickup_location() {
        $labels = array(
            'name' => _x('Pickup Location', 'post type general name', 'woocommerce-pickup-location'),
            'singular_name' => _x('Pickup Location', 'post type singular name', 'woocommerce-pickup-location'),
            'add_new' => _x('Add New', 'Pickup Location', 'woocommerce-pickup-location'),
            'add_new_item' => __('Add New Pickup Location', 'woocommerce-pickup-location'),
            'edit_item' => __('Edit Pickup Location', 'woocommerce-pickup-location'),
            'new_item' => __('New Pickup Location', 'woocommerce-pickup-location'),
            'all_items' => __('All Pickup Locations', 'woocommerce-pickup-location'),
            'view_item' => __('View Pickup Location', 'woocommerce-pickup-location'),
            'search_items' => __('Search Pickup Locations', 'woocommerce-pickup-location'),
            'not_found' => __('No Pickup Location found', 'woocommerce-pickup-location'),
            'not_found_in_trash' => __('No Pickup Location found in the Trash', 'woocommerce-pickup-location'),
            'parent_item_colon' => '',
            'menu_name' => 'Pickup Location'
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Pickup Location',
            'public' => true,
            'publicly_queryable' => true,
            'menu_position' => 4,
            'supports' => array('title', 'editor', 'thumbnail'),
            'has_archive' => true,
            'capability_type'    => 'post',
            'menu_icon' => 'dashicons-location-alt'
        );
        register_post_type('pickup_location', $args);
    }

    public function plugins_loaded() {
        global $woocommerce;

        if (isset($woocommerce->version) && version_compare($woocommerce->version, $this->required) < 0) {
            add_action('admin_notices', array($this, 'admin_notice'));
            return false;
        }
    }

    public function admin_notice() {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce pickup location requires at least WooCommerce %s in order to function. Please upgrade WooCommerce.', 'woocommerce-pickup-location'), $this->required) . '</p></div>';
    }

    public function wp_pkpo_menu() {
        global $wpaie_import, $wpaie_export, $wpaie_settings_screen, $wp_pkpo_upload_csv_screen;
        $wp_pkpo_upload_csv_screen = add_submenu_page('edit.php?post_type=pickup_location', __('Pickup Location CSV', 'woocommerce-pickup-location'), __('Pickup Location CSV', 'woocommerce-pickup-location'), 'manage_options', 'wp_pkpo_pickup_location_csv', array($this, 'wp_pkpo_pickup_location_csv'));
        $wpaie_settings_screen = add_submenu_page('edit.php?post_type=pickup_location', __('Settings', 'woocommerce-pickup-location'), __('Settings', 'woocommerce-pickup-location'), 'manage_options', 'wp_pkpo_settings', array($this, 'wp_pkpo_settings'));
    }

    public function wp_pkpo_settings() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $wp_pkpo_msg = '';

        if (isset($_POST['submit'])) {

            $wp_pkpo_options = array();
            $wp_pkpo_pickup_points = array();

            if (isset($_POST['wp_pkpo_default_position']))
                $wp_pkpo_options['wp_pkpo_default_position'] = $_POST['wp_pkpo_default_position'];

            if (isset($_POST['wp_pkpo_pickup_selection_type']))
                $wp_pkpo_options['wp_pkpo_pickup_selection_type'] = $_POST['wp_pkpo_pickup_selection_type'];
            
            if (isset($_POST['wp_pickup_auto_select_pickup']))
                $wp_pkpo_options['wp_pickup_auto_select_pickup'] = $_POST['wp_pickup_auto_select_pickup'];
            
            if (isset($_POST['wp_pkpo_pickup_date_formate']))
                $wp_pkpo_options['wp_pkpo_pickup_date_formate'] = $_POST['wp_pkpo_pickup_date_formate'];
            
             if (isset($_POST['wp_pickup_map_position']))
                $wp_pkpo_options['wp_pickup_map_position'] = $_POST['wp_pickup_map_position'];

            if (isset($_POST['wp_pkpo_pickup_admin_map_zoom']))
                $wp_pkpo_options['wp_pkpo_pickup_admin_map_zoom'] = $_POST['wp_pkpo_pickup_admin_map_zoom'];

            if (isset($_POST['wp_pkpo_pickup_checkout_map_zoom']))
                $wp_pkpo_options['wp_pkpo_pickup_checkout_map_zoom'] = $_POST['wp_pkpo_pickup_checkout_map_zoom'];

            if (isset($_POST['wp_pkpo_shortcode_map_zoom']))
                $wp_pkpo_options['wp_pkpo_shortcode_map_zoom'] = $_POST['wp_pkpo_shortcode_map_zoom'];

            if (isset($_POST['wp_pkpo_map_disable']))
                $wp_pkpo_options['wp_pkpo_map_disable'] = $_POST['wp_pkpo_map_disable'];

            if (isset($_POST['wp_pkpo_billing_hide_details']))
                $wp_pkpo_options['wp_pkpo_billing_hide_details'] = $_POST['wp_pkpo_billing_hide_details'];

           if (isset($_POST['wp_pkpo_shipping_hide_details']))
                $wp_pkpo_options['wp_pkpo_shipping_hide_details'] = $_POST['wp_pkpo_shipping_hide_details'];

           if (isset($_POST['wp_pkpo_hide_checkout_time']))
                $wp_pkpo_options['wp_pkpo_hide_checkout_time'] = $_POST['wp_pkpo_hide_checkout_time'];
            
            if (isset($_POST['wp_pkpo_hide_checkout_date']))
                $wp_pkpo_options['wp_pkpo_hide_checkout_date'] = $_POST['wp_pkpo_hide_checkout_date'];
            
            if (isset($_POST['wp_pickup_order_list_show_pickup']))
                $wp_pkpo_options['wp_pickup_order_list_show_pickup'] = $_POST['wp_pickup_order_list_show_pickup'];
            
            if (isset($_POST['wp_pkpo_send_info_in_email']))
                $wp_pkpo_options['wp_pkpo_send_info_in_email'] = $_POST['wp_pkpo_send_info_in_email'];

            if (isset($_POST['wp_pickup_default_timepicker_lang']))
                $wp_pkpo_options['wp_pickup_default_timepicker_lang'] = $_POST['wp_pickup_default_timepicker_lang'];

            if (isset($_POST['wp_pickup_default_datepicker_lang']))
                $wp_pkpo_options['wp_pickup_default_datepicker_lang'] = $_POST['wp_pickup_default_datepicker_lang'];

            if (isset($_POST['wp_pickup_user_roles']))
                $wp_pkpo_options['wp_pickup_user_roles'] = $_POST['wp_pickup_user_roles'];

            if (isset($_POST['wp_pickup_wrong_date']))
                $wp_pkpo_options['wp_pickup_wrong_date'] = $_POST['wp_pickup_wrong_date'];

            if (isset($_POST['wp_pickup_wrong_time']))
                $wp_pkpo_options['wp_pickup_wrong_time'] = $_POST['wp_pickup_wrong_time'];
            
            if (isset($_POST['wp_pickup_min_time_msg']))
                $wp_pkpo_options['wp_pickup_min_time_msg'] = $_POST['wp_pickup_min_time_msg'];
            
            if (isset($_POST['wp_pickup_max_time_msg']))
                $wp_pkpo_options['wp_pickup_max_time_msg'] = $_POST['wp_pickup_max_time_msg'];

            if (isset($_POST['wp_pickup_default_time_formate']))
                $wp_pkpo_options['wp_pickup_default_time_formate'] = $_POST['wp_pickup_default_time_formate'];

            if (isset($_POST['wp_pickup_default_date_text']))
                $wp_pkpo_options['wp_pickup_default_date_text'] = $_POST['wp_pickup_default_date_text'];
            
            if (isset($_POST['wp_pickup_default_time_text']))
                $wp_pkpo_options['wp_pickup_default_time_text'] = $_POST['wp_pickup_default_time_text'];

            if (isset($_POST['wp_pickup_address_display']))
                $wp_pkpo_options['wp_pickup_address_display'] = $_POST['wp_pickup_address_display'];
            
            if (isset($_POST['wp_pickup_email_display']))
                $wp_pkpo_options['wp_pickup_email_display'] = $_POST['wp_pickup_email_display'];
            
            if (isset($_POST['wp_pickup_city_display']))
                $wp_pkpo_options['wp_pickup_city_display'] = $_POST['wp_pickup_city_display'];
            
            if (isset($_POST['wp_pickup_state_display']))
                $wp_pkpo_options['wp_pickup_state_display'] = $_POST['wp_pickup_state_display'];
            
            if (isset($_POST['wp_pickup_country_display']))
                $wp_pkpo_options['wp_pickup_country_display'] = $_POST['wp_pickup_country_display'];
            
            if (isset($_POST['wp_pickup_zip_display']))
                $wp_pkpo_options['wp_pickup_zip_display'] = $_POST['wp_pickup_zip_display'];
            
            if (isset($_POST['wp_pickup_load_pickup_later']))
                $wp_pkpo_options['wp_pickup_load_pickup_later'] = $_POST['wp_pickup_load_pickup_later'];
            
            if (isset($_POST['wp_pickup_default_shipping_pickup']))
                $wp_pkpo_options['wp_pickup_default_shipping_pickup'] = $_POST['wp_pickup_default_shipping_pickup'];
            
            if (isset($_POST['wp_pickup_radius_search']))
                $wp_pkpo_options['wp_pickup_radius_search'] = $_POST['wp_pickup_radius_search'];
            
            if (isset($_POST['wp_pickup_hide_pickup_shipping_based_on_order']))
                $wp_pkpo_options['wp_pickup_hide_pickup_shipping_based_on_order'] = $_POST['wp_pickup_hide_pickup_shipping_based_on_order'];
            
            if (isset($_POST['wp_pickup_map_api_key']))
                $wp_pkpo_options['wp_pickup_map_api_key'] = $_POST['wp_pickup_map_api_key'];
            
            if (isset($_POST['wp_pickup_map_search']))
                $wp_pkpo_options['wp_pickup_map_search'] = $_POST['wp_pickup_map_search'];
            
            if (isset($_POST['wp_pickup_location_based_product']))
                $wp_pkpo_options['wp_pickup_location_based_product'] = $_POST['wp_pickup_location_based_product'];
            
            if (isset($_POST['wp_pickup_location_select2']))
                $wp_pkpo_options['wp_pickup_location_select2'] = $_POST['wp_pickup_location_select2'];
            
            if (isset($_FILES['wp_pkpo_marker_image']['name']) && $_FILES['wp_pkpo_marker_image']['name'] !== '') {
                if (!function_exists('wp_handle_upload')) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }

                $uploadedfile = $_FILES['wp_pkpo_marker_image'];

                $upload_overrides = array('test_form' => false);

                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    $wp_pkpo_options['wp_pkpo_marker_image'] = $movefile['url'];
                }
            } else {
                $wp_pkpo_options['wp_pkpo_marker_image'] = $_POST['wp_pkpo_old_image_url'];
            }

            update_option('wp_pkpo_options', $wp_pkpo_options);
            $wp_pkpo_msg = __('Setting Saved Successfully !', 'woocommerce-pickup-location');
        }

        $wp_pkpo_settings = get_option('wp_pkpo_options');
        $this->wppkpo_view->wp_pkpo_settings($wp_pkpo_settings, $wp_pkpo_msg);
    }
    
    public function wp_pkpo_order_posts_column($column) {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        if (isset($wp_pkpo_settings["wp_pickup_order_list_show_pickup"]) && $wp_pkpo_settings["wp_pickup_order_list_show_pickup"] === "enable") {
            global $post, $woocommerce, $the_order;
            $order_id = $the_order->id;
            switch ($column) {
                case 'shipping_address' :
                    $pickup_name = get_post_meta($order_id, 'pickup_name', true);
                    echo __("Pickup location : ", "") . $pickup_name;
                    break;
            }
            return $column;
        }
    }
    
    public function arrayToCSV($csvArray, $fileName = "file.csv") {

        $list = $csvArray;
        $fileSavePath = $this->getWPUploadDir();
        $fileName = time() . $fileName;
        $fp = fopen($fileSavePath . "/" . $fileName, 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields, $this->delimiter, $this->enclosure);
        }
        $upload_dir = wp_upload_dir();
        $export_file_name = $upload_dir['baseurl'] . "/" . $this->upload_directory . "/" . $fileName;
        fclose($fp);
        return $export_file_name;
    }
    
    private function getWPUploadDir() {
        $upload_dir = wp_upload_dir();
        $fileSavePath = $upload_dir['basedir'] . "/" . $this->upload_directory;

        if (!is_dir($fileSavePath))
            wp_mkdir_p($fileSavePath);

        return $fileSavePath;
    }
    
}