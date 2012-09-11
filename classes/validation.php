<?php

namespace ViewForm;

class Validation extends \Fuel\Core\Validation {

    public function _validation_date($val, $prefix) {
        $year  = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day   = $this->validated($prefix . '_day');
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        
        return mktime(0, 0, 0,  $month , $day, $year);
    }

    public function _validation_date_start($val, $prefix, $startDate) {
        $year  = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day   = $this->validated($prefix . '_day');
        $date  = mktime(0, 0, 0, $month, $day, $year);
        if ($date < $startDate) {
            return false;
        }
        return true;
    }

    public function _validation_date_end($val, $prefix, $endDate) {
        $year  = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day   = $this->validated($prefix . '_day');
        $date  = mktime(0, 0, 0, $month, $day, $year);
        if ($endDate < $date) {
            return false;
        }
        return true;
    }

    
    
    public static function _validation_allow_empty_in_array($val, $compare) {
        if (Validation::_empty($val)) {
            return true;
        }
        
        if (!in_array($val, $compare)) {
                return false;
        }
    
        return true;
    }
    

    /**
     * Validate not required array input
     *
     * @param   array
     * @return  true|array
     * 
     * @author     Kenji Suzuki https://github.com/kenjis
     * @copyright  2011-2012 Kenji Suzuki
     * @license    MIT License http://www.opensource.org/licenses/mit-license.php
     */
    public static function _validation_not_required_array($val) {
        if (is_array($val)) {
            return true;
        } else {
            return array();
        }
    }
    public function _validation_default_select_filter($val) {
        //$config = \Config::load('form');
        $key = $this->fieldset()->form()->get_config('non_select_key', null);
        if($key !== null &&  $key == $val) {
        //if(isset($config['non_select_key']) && $config['non_select_key'] != null && $val == $config['non_select_text']){
            return null;
        }
        return true;
    }


}