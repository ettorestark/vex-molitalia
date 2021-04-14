<?php


require_once(plugin_dir_path(WCST_PLUGIN_FILE) . '/triggers/wcst-trigger.php');

class WCST_Triggers
{

    public static $deprecation_notice_fired = false;

    /* @var $_triggers[] */
    private static $_triggers = array();

    public static function register($trigger)
    {
        if (!is_subclass_of($trigger, 'WCST_Base_Trigger')) {
            throw new Exception('Must be a subclass of GF_Field');
        }
        if (empty($trigger->slug)) {
            throw new Exception('The type must be set');
        }
        if (isset(self::$_triggers[$trigger->slug])) {
            throw new Exception('Field type already registered: ' . $trigger->slug);
        }
        self::$_triggers[$trigger->slug] = $trigger;
    }

    public static function exists($trigger_slug)
    {
        return isset(self::$_triggers[$trigger_slug]);
    }

    /**
     * Alias for get_instance()
     *
     * @param $field_type
     *
     * @return WCST_Base_Trigger
     */
    public static function get($trigger_slug)
    {
        return self::get_instance($trigger_slug);
    }

    /**
     * @param $field_type
     *
     * @return WCST_Base_Trigger
     */
    public static function get_instance($trigger_slug)
    {
        return isset(self::$_triggers[$trigger_slug]) ? self::$_triggers[$trigger_slug] : false;
    }

    /**
     * @return WCST_Base_Trigger[]
     */
    public static function get_all()
    {
        
        $triggers  = self::$_triggers;
        $new_triggers = array();

        foreach($triggers as $key => $trigger) {
            $trigger_index_array[$key] = $trigger->default_priority;
        }
        $trigger_index_array = apply_filters('wcst_triggers_index' , $trigger_index_array);

        asort($trigger_index_array);

        foreach($trigger_index_array as $trigger => $val) {
            $new_triggers[$trigger] = $triggers[$trigger];
        }
        return $new_triggers;
    }


    public static function get_triggers()
    {

        $parents = array(
            'wcst_best_sellers_settings' => array(
                'name' => 'Best Sellers',
                'slug' => 'best_sellers',
                'triggers' => array()
            ),
            'wcst_low_stock_settings' => array(
                'name' => 'Low Stock',
                'slug' => 'low_stock',
                'triggers' => array()
            ),
            'wcst_smarter_reviews_settings' => array(
                'name' => 'Smarter Reviews',
                'slug' => 'smarter_reviews',
                'triggers' => array()
            ),
            'wcst_guarantee_settings' => array(
                'name' => 'Guarantee',
                'slug' => 'guarantee',
                'triggers' => array()
            ),
            'wcst_sales_activities_settings' => array(
                'name' => 'Sales Activities',
                'slug' => 'sales_activities',
                'triggers' => array()
            ),
            'wcst_badges_settings' => array(
                'name' => 'Badges',
                'slug' => 'badges',
                'triggers' => array()
            ),
            'wcst_savings_settings' => array(
                'name' => 'Savings',
                'slug' => 'savings',
                'triggers' => array()
            ),
            'wcst_deal_expiry_settings' => array(
                'name' => 'Deal Expiry',
                'slug' => 'deal_expiry',
                'triggers' => array()
            ),
        );
        foreach (self::$_triggers as $key => $trigger) {


            $parents[$trigger->parent_slug]['triggers'][] = array(
                'name' => $trigger->get_title(),
                'slug' => $trigger->slug,
            );
        }

        return $parents;
    }





}

// load all the trigger files automatically
foreach (glob(plugin_dir_path(WCST_PLUGIN_FILE) . '/triggers/wcst-*.php') as $gf_field_filename) {
    if ($gf_field_filename == "wcst-trigger") {
        continue;
    }

    require_once($gf_field_filename);
}