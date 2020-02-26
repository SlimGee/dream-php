<?php
namespace Dream;
/**
 *
 */
class Validator
{
    private static $errors;

    public static function make(array $source,array $validations)
    {
        $errors = [];
        foreach($validations as $item => $rules){
            if (!isset($source[$item])) {
                continue;
            }
            $display = $rules['display'];
            foreach($rules as $rule=>$rule_value){

                $value = trim($source[$item]);

                switch ($rule) {
                    case 'required':
                        if (empty($value))
                            $errors[] = "{$display} is required";
                        break;

                    case 'min':
                        if(strlen($value) < $rule_value)
                            $errors[] = "{$display} must be at least {$rule_value} charaters.";
                        break;

                    case 'max':
                        if(strlen($value) > $rule_value)
                            $errors[] = "{$display} must be at most {$rule_value} characters.";
                        break;

                    case 'matches':
                        if($value !== $source[$rule_value]){
                            $matchDisplay = $validations[$rule_value]['display'];
                            $errors[] = "{$display} do not match {$matchDisplay}";
                        }
                        break;

                    case 'is_numeric':
                        if(!is_numeric($value))
                            $errors[] = "{$display} should be numeric.";
                        break;

                    case 'valid_email':
                        if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                            $errors[] = "{$display} must be valid.";
                        break;
                    case 'uniq':
                        if (app()->registry()->get('db')->find_first($rule_value,"{$item} = ?",[$value])) {
                            $errors[] = "{$display} already exists.";
                        }
                        break;
                    case 'selection':
                        if($value == $rule_value)
                            $errors[] = "please select a {$display}";
                        break;
                }
            }
        }
        self::$errors = $errors;
        return (sizeof($errors) < 1) ? true : false;
    }

    public static function get_errors()
    {
        return self::$errors;
    }
}
