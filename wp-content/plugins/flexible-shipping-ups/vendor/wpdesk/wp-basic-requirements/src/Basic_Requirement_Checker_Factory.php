<?php

if ( ! class_exists('Basic_Requirement_Checker')) {
    require_once 'Basic_Requirement_Checker.php';
}

class WPDesk_Basic_Requirement_Checker_Factory
{
    /**
     * @param $plugin_file
     * @param $plugin_name
     * @param $text_domain
     * @param $php_version
     * @param $wp_version
     *
     * @return WPDesk_Requirement_Checker
     */
    public function create_requirement_checker($plugin_file, $plugin_name, $text_domain)
    {
        return new WPDesk_Basic_Requirement_Checker($plugin_file, $plugin_name, $text_domain, null, null);
    }
}
