<?php

namespace ViewForm;

class Fieldset extends \Fuel\Core\Fieldset {

    /**
     *
     * @param type $name
     * @param array $config
     * @return Fieldset 
     */
    public static function forge($name = 'default', array $config = array()){
        return parent::forge($name,$config);
    }

    
    /**
     * Factory for Fieldset_Field objects
     *
     * @param   string
     * @param   string
     * @param   array
     * @param   array
     * @return  Fieldset_Field
     */
    public function add($name, $label = '', array $attributes = array(), array $rules = array()) {
        if ($name instanceof Fieldset_Field) {
            if ($name->name == '' or $this->field($name->name) !== false) {
                throw new \RuntimeException('Fieldname empty or already exists in this Fieldset: "' . $name->name . '".');
            }

            $name->set_fieldset($this);
            $this->fields[$name->name] = $name;
            return $name;
        } elseif ($name instanceof Fieldset) {
            if (empty($name->name) or $this->field($name->name) !== false) {
                throw new \RuntimeException('Fieldset name empty or already exists in this Fieldset: "' . $name->name . '".');
            }

            $name->set_parent($this);
            $this->fields[$name->name] = $name;
            return $name;
        }

        if (empty($name) || (is_array($name) and empty($name['name']))) {
            throw new \InvalidArgumentException('Cannot create field without name.');
        }

        // Allow passing the whole config in an array, will overwrite other values if that's the case
        if (is_array($name)) {
            $attributes = $name;
            $label      = isset($name['label']) ? $name['label'] : '';
            $rules      = isset($name['rules']) ? $name['rules'] : array();
            $name = $name['name'];
        }

        // Check if it exists already, if so: return and give notice
        if (($field = $this->field($name))) {
            \Error::notice('Field with this name exists already in this fieldset: "' . $name . '".');
            return $field;
        }

        $field = new \ViewForm\Fieldset_Field($name, $label, $attributes, $rules, $this);
        $this->fields[$name] = $field;


        return $field;
    }

    /**
     * remove Fieldset_Field
     * @param type $name
     * @return \ViewForm\Fieldset 
     */
    public function remove($name) {
        unset($this->fields[$name]);
        return $this;
    }

    public function add_radio($name, $label = '', $options = array(), array $attributes = array(), array $rules = array()) {
        $attr = array_merge(array('options' => $options, 'type'    => 'radio'), $attributes);
        return $this->add($name, $label, $attr, $rules)
                        ->add_rule('in_array', $options);
    }

    public function add_checkbox($name, $label = '', $options = array(), array $attributes = array(), array $rules = array()) {
        $attr = array_merge(array('options' => $options, 'type'    => 'checkbox'), $attributes);
        return $this->add($name, $label, $attr, $rules)
                        ->add_rule('allow_emplty_in_array', array_keys($options))
                        ->add_rule('not_required_array');
    }

    public function add_select($name, $label = '', $options = array(), array $attributes = array(), array $rules = array()) {
        $attr = array_merge(array('options' => $options, 'type'    => 'select'), $attributes);
        return $this->add($name, $label, $attr, $rules)
                        ->add_rule('in_array', array_keys($options));
    }

    public function add_text($name, $label = '', array $attributes = array(), array $rules = array()) {
        return $this->add($name, $label, $attributes, $rules);
    }

    public function add_password($name, $label = '', array $attributes = array(), array $rules = array()) {
        $attr = array_merge(array('type' => 'password'), $attributes);
        return $this->add($name, $label, $attr, $rules);
    }

    public function add_textarea($name, $label = '', array $attributes = array(), array $rules = array()) {
        $attr = array_merge(array('type' => 'textarea'), $attributes);
        return $this->add($name, $label, $attr, $rules);
    }

    public function add_date_select($name, $label = '', $startDate = null, $endDate = null, array $attributes = array(), array $rules = array()) {


        $years = array();
        for($i = 2012; $i <= 2016; $i++){
            $years[$i] = $i;
        }
        $this->add_select($name . '_year', $label . '(year)', $years);
        $months    = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $i;
        }
        $this->add_select($name . '_month', $label . '(month)', $months);

        $days = array();
        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }
        $this->add_select($name . '_day', $label . '(day)', $days);
        $this->add_text($name, $label, $attributes, $rules)->add_rule('date', $name);
        return $this;
        
    }

    
    
    public function build_for_hidden() {
        $fields_output = '';
        foreach ($this->field() as $f) {
            $fields_output .= \Fuel\Core\Form::hidden($f->name, $f->value) . PHP_EOL;
        }
        return $fields_output;
    }

}