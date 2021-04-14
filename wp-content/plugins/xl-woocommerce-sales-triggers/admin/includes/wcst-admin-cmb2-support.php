<?php

class WCST_Admin_CMB2_Support {

    /**
     * Callback function for groups
     * @param $field_args CMB2 Field agrs
     * @param $field
     */
    public static function cmb2_wcst_before_call($field_args, $field) {
        $attributes = '';
        if (($field_args['id'] == "_wcst_data_wcst_guarantee_guarantee")) {
            $class_single = '';
            foreach ($field_args['attributes'] as $attr => $val) {
                if ($attr == 'class') {
                    $class_single .= ' ' . $val;
                }
                // if data attribute, use single quote wraps, else double
                $quotes = false !== stripos($attr, 'data-') ? "'" : '"';
                $attributes .= sprintf(' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes);
            }
            echo '<div class="wcst_custom_wrapper_group' . $class_single . '" ' . $attributes . '><div class="wcst_guarantee_head">Guarantees</div>';
        }
    }

    /**
     * Callback function for groups
     * @param $field_args CMB2 Field agrs
     * @param $field
     */
    public static function cmb2_wcst_after_call($field_args, $field) {
        $attributes = '';
        if (($field_args['id'] == "_wcst_data_wcst_guarantee_guarantee")) {
            echo '</div>';
        }
    }

    /**
     * Callback function for groups
     * @param $field_args CMB2 Field agrs
     * @param $field
     */
    public static function cmb2_wcst_before_call_wysiwyg($field_args, $field) {
        $attributes = '';
        if (($field_args['id'] == "_wcst_data_wcst_image_text_html")) {
            $class_single = '';
            foreach ($field_args['attributes'] as $attr => $val) {
                if ($attr == 'class') {
                    $class_single .= ' ' . $val;
                }
                // if data attribute, use single quote wraps, else double
                $quotes = false !== stripos($attr, 'data-') ? "'" : '"';
                $attributes .= sprintf(' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes);
            }
            echo '<div class="wcst_custom_wrapper_wysiwyg' . $class_single . '" ' . $attributes . '>';
        }
    }

    /**
     * Callback function for groups
     * @param $field_args CMB2 Field agrs
     * @param $field
     */
    public static function cmb2_wcst_after_call_wysiwyg($field_args, $field) {
        $attributes = '';
        if (($field_args['id'] == "_wcst_data_wcst_image_text_html")) {
            echo '</div>';
        }
    }

    /**
     * Hooked over `xl_cmb2_add_conditional_script_page` so that we can load conditional logic scripts
     * @param $options Pages
     * @return mixed
     */
    public static function wcst_push_support_form_cmb_conditionals($pages) {

        return $pages;
    }

    /**
     * Output a message if the current page has the id of "2" (the about page)
     * @param  object $field_args Current field args
     * @param  object $field Current field object
     */
    public static function cmb_after_row_cb($field_args, $field) {
        echo '</div></div>';
    }

    /**
     * Output a message if the current page has the id of "2" (the about page)
     * @param  object $field_args Current field args
     * @param  object $field Current field object
     */
    public static function cmb_before_row_cb($field_args, $field) {
        $default = array('wcst_accordion_title' => __("Untitled", WCST_SLUG), 'wcst_is_accordion_opened' => false);

        $field_args = wp_parse_args($field_args, $default);

        $is_active = ($field_args['wcst_is_accordion_opened']) ? "active" : "";
        $is_display_none = (!$field_args['wcst_is_accordion_opened']) ? "style='display:none'" : "";
        echo '<div class="cmb2_wcst_wrapper_ac"><div class="cmb2_wcst_acc_head ' . $is_active . ' "><a href="javascript:void(0);">' . $field_args["wcst_accordion_title"] . '</a> <div class="toggleArrow"></div></div><div class="cmb2_wcst_wrapper_ac_data" ' . $is_display_none . '>';
    }

    public static function wcst_after_row_icon_preview($field_args, $field) {
        echo '<div class="wcst_icon_preview"><i class="wcst_custom_icon"></i></div>';
    }

    public static function cmb_before_row_cb_for_order_date($field_args, $field) {
        echo '<div class="order_date_outer_wrapper"> ';
    }

    public static function cmb_after_row_cb_for_order_date($field_args, $field) {
        echo '</div>';
    }

    public static function cmb2_label_callback_for_date_fields($field_args, $field) {

        return '<div class="order_label" >' . __("Orders", WCST_SLUG) . '</div>' . $field->label();
    }


    public static function render_trigger_nav() {

        $get_triggers = apply_filters('wcst_admin_trigger_nav', WCST_Triggers::get_triggers());

        $html = '<ul class="subsubsub subsubsub_wcst">';

        $html_inside = array();
        $html_inside[] = sprintf('<li><a href="%s" class="%s">%s</a></li>', admin_url('admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '&section=all'), self::active_class('all'), __("All", WCST_SLUG));
        foreach ($get_triggers as $triggers) {
            if (is_array($triggers['triggers']) && count($triggers['triggers']) > 0) {
                $html_inside[] = sprintf('<li><a href="%s" class="%s">%s</a></li>', admin_url('admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '&section=' . $triggers['slug']), self::active_class($triggers['slug']), $triggers['name']);
            }
        }

        if (count($html_inside) > 0) {
            $html .= implode("", $html_inside);
        }

        $html .= '</ul>';

        echo $html;
    }

    public static function active_class($trigger_slug) {

        if (self::get_current_trigger() == $trigger_slug) {
            return "current";
        }
        return "";
    }

    public static function get_current_trigger() {
        if (isset($_GET['page']) && $_GET['page'] == "wc-settings" && isset($_GET['section'])) {
            return $_GET['section'];
        }
        return "all";
    }

    public static function cmb_opt_groups($args, $defaults, $field_object, $field_types_object) {

        // Only do this for the field we want (vs all select fields)
        if ('_wcst_data_choose_trigger' != $field_types_object->_id()) {
            return $args;
        }

        $option_array = WCST_Common::get_triggers_select();

        $saved_value = $field_object->escaped_value();
        $value = $saved_value ? $saved_value : $field_object->args('default');

        $options_string = '';

        $args = array(
            'label' => __('Select an Option', WCST_SLUG),
            'value' => '',
            'checked' => !$value
        );

        if ($field_object->args["show_option_none"]) {
            $options_string .= $field_types_object->select_option($args);
        }

        foreach ($option_array as $group_label => $group) {

            $options_string .= '<optgroup label="' . $group_label . '">';

            foreach ($group as $key => $label) {

                $args = array(
                    'label' => $label,
                    'value' => $key,
                    'checked' => $value == $key
                );
                $options_string .= $field_types_object->select_option($args);
            }
            $options_string .= '</optgroup>';
        }

        // Ok, replace the options value
        $defaults['options'] = $options_string;

        return $defaults;
    }

}
