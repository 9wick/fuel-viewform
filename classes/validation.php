<?php

namespace ViewForm;

class Validation  extends \Fuel\Core\Validation{
    
    public function _validation_date($val, $prefix){
        $year = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day = $this->validated($prefix . '_day');
        if(!checkdate($month, $day, $year)){
            return false;
        }
        return $year . "-" . $month . "-" . $day;
    }
    
    
    
    public function _validation_date_start($val, $prefix, $startDate){
        $year = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day = $this->validated($prefix . '_day');
        $date = mktime(0, 0, 0, $month, $day, $year);
        if($date < $startDate){
            return false;
        }
    }
    
    public function _validation_date_end($val, $prefix, $endDate){
        $year = $this->validated($prefix . '_year');
        $month = $this->validated($prefix . '_month');
        $day = $this->validated($prefix . '_day');
        $date = mktime(0, 0, 0, $month, $day, $year);
        if($endDate < $date){
            return false;
        }
    }
    

}