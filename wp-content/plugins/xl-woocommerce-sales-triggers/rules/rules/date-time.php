<?php

class WCST_Rule_Day extends WCST_Rule_Base
{

    public function __construct()
    {
        parent::__construct('day');
    }

    public function get_possibile_rule_operators()
    {

        $operators = array(
            '==' => __("is", WCST_SLUG),
            '!=' => __("is not", WCST_SLUG),
        );

        return $operators;
    }

    public function get_possibile_rule_values()
    {
        $options = array(
            '0' => __('Sunday', WCST_SLUG),
            '1' => __('Monday', WCST_SLUG),
            '2' => __('Tuesday', WCST_SLUG),
            '3' => __('Wednesday', WCST_SLUG),
            '4' => __('Thursday', WCST_SLUG),
            '5' => __('Friday', WCST_SLUG),
            '6' => __('Saturday', WCST_SLUG),

        );

        return $options;
    }

    public function get_condition_input_type()
    {
        return 'Select';
    }

    public function is_match($rule_data, $productID)
    {
        global $post;
        $result = false;
        $timestamp = current_time('timestamp');

        $dateTime = new DateTime();
      //  $dateTime->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
	    $dateTime->setTimestamp( current_time( 'timestamp' ));

        $day_today = $dateTime->format('w');

        if (isset($rule_data['condition']) && isset($rule_data['operator'])) {

            if ($rule_data['operator'] == '==') {
                $result = $rule_data['condition'] == $day_today ? true : false;
            }

            if ($rule_data['operator'] == '!=') {
                $result = $rule_data['condition'] == $day_today ? false : true;
            }
        }

        return $this->return_is_match($result, $rule_data);
    }

}

class WCST_Rule_Date extends WCST_Rule_Base
{

    public function __construct()
    {
        parent::__construct('date');
    }

    public function get_possibile_rule_operators()
    {
        $operators = array(
            '==' => __("is equal to", WCST_SLUG),
            '!=' => __("is not equal to", WCST_SLUG),
            '>' => __("is greater than", WCST_SLUG),
            '<' => __("is less than", WCST_SLUG),
            '>=' => __("is greater or equal to", WCST_SLUG),
            '=<' => __("is less or equal to", WCST_SLUG)
        );
        return $operators;
    }

    public function get_condition_input_type()
    {
        return 'Date';
    }

    public function is_match($rule_data, $productID)
    {
        global $post;

        $result = false;


        if (isset($rule_data['condition']) && isset($rule_data['operator'])) {


            $dateTime = new DateTime();
          //  $dateTime->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
	        $dateTime->setTimestamp( current_time( 'timestamp' ));

            switch ($rule_data['operator']) {
                case '==' :

                    $result = ($rule_data['condition']) == $dateTime->format('Y-m-d');

                    break;
                case '!=' :

                    $result = ($rule_data['condition']) != $dateTime->format('Y-m-d');

                    break;

                case '>' :

                    $result = $dateTime->getTimestamp() > strtotime($rule_data['condition']);

                    break;

                case '<' :

                    $result = $dateTime->getTimestamp() < strtotime($rule_data['condition']);

                    break;

                case '=<' :

                    $result = $dateTime->getTimestamp() <= strtotime($rule_data['condition']);
                    break;
                case '>=' :

                    $result = $dateTime->getTimestamp() >= strtotime($rule_data['condition']);

                    break;

                default:
                    $result = false;
                    break;
            }
        }

        return $this->return_is_match($result, $rule_data);
    }

}


class WCST_Rule_Time extends WCST_Rule_Base
{

    public function __construct()
    {
        parent::__construct('time');
    }

    public function get_possibile_rule_operators()
    {
        $operators = array(
            '==' => __("is equal to", WCST_SLUG),
            '!=' => __("is not equal to", WCST_SLUG),
            '>' => __("is greater than", WCST_SLUG),
            '<' => __("is less than", WCST_SLUG),
            '>=' => __("is greater or equal to", WCST_SLUG),
            '=<' => __("is less or equal to", WCST_SLUG)
        );
        return $operators;
    }

    public function get_condition_input_type()
    {
        return 'Time';
    }

    public function is_match($rule_data, $productID)
    {
        global $post;

        $result = false;


        if (isset($rule_data['condition']) && isset($rule_data['operator']) && $rule_data['condition']) {


            $parsetime = explode(" : ", $rule_data['condition']);
            if (count($parsetime) !== 2) {
                return $this->return_is_match($result, $rule_data);
            }

            $dateTime = new DateTime();
           // $dateTime->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
	        $dateTime->setTimestamp( current_time( 'timestamp' ));
            $timestamp_current = $dateTime->getTimestamp();
            $dateTime->setTime($parsetime[0], $parsetime[1]);
            $timestamp = $dateTime->getTimestamp();
            switch ($rule_data['operator']) {
                case '==' :
                    $result = $timestamp_current == $timestamp;
                    break;
                case '!=' :
                    $result = $timestamp_current != $timestamp;
                    break;
                case '>' :
                    $result = $timestamp_current > $timestamp;
                    break;
                case '<' :
                    $result = $timestamp_current < $timestamp;
                    break;
                case '=<' :
                    $result = $timestamp_current <= $timestamp;
                    break;
                case '>=' :
                    $result = $timestamp_current >= $timestamp;
                    break;
                default:
                    $result = false;
                    break;
            }
        }

        return $this->return_is_match($result, $rule_data);

    }

}
