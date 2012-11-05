<?php

namespace ViewForm;

class Fieldset_Field extends \Fuel\Core\Fieldset_Field {

    /**
     * テキストボックス、チェックボックスなどinput系のタグを出力する
     * @param array $attr
     * @return string 
     */
    public function field_text($attr = array()) {
        $form = $this->fieldset()->form();
        // Add IDs when auto-id is on
        if ($form->get_config('auto_id', false) === true and $this->get_attribute('id') == '') {
            $auto_id = str_replace(array('[', ']'), array('-', ''), $form->get_config('auto_id_prefix', '') . $this->name);
            $this->set_attribute('id', $auto_id);
        }

        $baseAttributes = array_merge($this->attributes, $attr);
      
        switch ($this->type) {
            case 'hidden':
                $build_field = $form->hidden($this->name, $this->value, $baseAttributes);
                break;
            case 'radio': case 'checkbox':
                if ($this->options) {
                    $build_field = '';
                    $i = 0;
                    foreach ($this->options as $value => $label) {
                        $attributes         = $baseAttributes;
                        $attributes['name'] = $this->name;
                        $this->type == 'checkbox' and $attributes['name'] .= '[' . $i . ']';

                        $attributes['value'] = $value;
                        $attributes['label'] = $label;

                        if (is_array($this->value) ? in_array($value, $this->value) : $value == $this->value) {
                            $attributes['checked'] = 'checked';
                        }

                        if (!empty($attributes['id'])) {
                            $attributes['id'] .= '_' . $i;
                        } else {
                            $attributes['id'] = null;
                        }

                        $build_field .= $this->type == 'radio' ? $form->radio($attributes) : $form->checkbox($attributes);
                        $build_field .= $form->label($label , $this->name . '_' . $i) . '&nbsp;';
                        $i++;
                    }
                } else {
                    $build_field = $this->type == 'radio' ? $form->radio($this->name, $this->value, $baseAttributes) : $form->checkbox($this->name, $this->value, $baseAttributes);
                }
                break;
            case 'select':
                $attributes  = $baseAttributes;
                $name        = $this->name;
                unset($attributes['type']);
                array_key_exists('multiple', $attributes) and $name .= '[]';
                $options = $this->options;
                if($form->get_config('non_select_text', null) != null){
                    $key = $form->get_config('non_select_key', 0);
                    if(!isset($this->options[$key])){
                        $nonSelect = array($key => $form->get_config('non_select_text', null) );
                        $options = array_merge($nonSelect , $options);            
                    }
                }
                
                $build_field = $form->select($name, $this->value, $options, $attributes);
                break;
            case 'textarea':
                $attributes  = $baseAttributes;
                unset($attributes['type']);
                $build_field = $form->textarea($this->name, $this->value, $attributes);
                break;
            case 'button':
                $build_field = $form->button($this->name, $this->value, $baseAttributes);
                break;
            case false:
                $build_field = '';
                break;
            default:
                $build_field = $form->input($this->name, $this->value, $baseAttributes);
                break;
        }

        return $build_field;
    }

    /**
     * 入力用のラベルタグを出力する
     * @param array $attr
     * @return string 
     */
    public function label_text($attr = array()) {
        $form          = $this->fieldset()->form();
        $required_mark = $this->get_attribute('required', null) ? $form->get_config('required_mark', null) : null;
        $label         = $this->label ? $form->label($this->label . $required_mark, $this->name, $attr) : '';
        return $label;
    }

    /**
     * エラーメッセージを出力する
     * @return string 
     */
    public function error_text() {
        $form           = $this->fieldset()->form();
        $error_template = $form->get_config('error_template', "");
        $error_msg      = ($form->get_config('inline_errors') && $this->error()) ? str_replace('{error_msg}', (string) $this->error(), $error_template) : '';
        return $error_msg;
    }

    /**
     * エラーメッセージを出力する
     * @return string 
     */
    public function has_error() {
        return (bool) $this->error();
    }

    public function value_text() {
        $value = $this->value;
        switch ($this->type) {
            case 'hidden':
                break;
            case 'radio':
            case 'checkbox':
            case 'select':
                $value = $this->options[$this->value];
                break;
        }

        return $value;
    }

    /**
     * shortcut of add_rule('required');
     * @return Fieldset_Field
     */
    public function required() {
        return $this->add_rule('required');
    }
    
    /**
     * shortcut of add_rule('max_length', $num);
     * @return Fieldset_Field
     */
    public function max_length($num) {
        return $this->add_rule('max_length',$num);
    }
    
    /**
     * shortcut of add_rule('min_length', $num);
     * @return Fieldset_Field
     */
    public function min_length($num) {
        return $this->add_rule('min_length',$num);
    }
    
    
    /**
     * shortcut of add_rule('valid_email');
     * @return Fieldset_Field
     */
    public function valid_email() {
        return $this->add_rule('valid_email');
    }
    
    
    
    

}
