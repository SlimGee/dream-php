<?php
use Lead\Parser;
use Lead\Lexer;
use Lead\Stream;
use Lead\Components\Variable;

include 'view_helper_helpers.php';

$style_tag = new Variable('style_tag');
$style_tag->setValue(function ($style){
    loadCss($style);
    echo "<link href = '/app/assets/stylesheets/$style.css' rel = 'stylesheet' data-turbolinks-track = 'reload'>";
});

$js_include_tag = new Variable('js_include_tag');
$js_include_tag->setValue(function ($js){
    loadJs($js);
    echo "<script src = '/app/assets/javascripts/$js.js' data-turbolinks-track = 'reload'></script>";
});

$partial = new Variable('partial');
$partial->setValue(function ($partial){
    $lexer = new Lexer(
        new Stream(load_file($partial))
    );
    $parser = new Parser($lexer);
    foreach ($parser->parse() as $expression) {
        $expression->evaluate();
    }
});

$yield = new Variable('yield');
$yield->setValue(function (){
    $view = load_file(app()->registry()->get('action_view'));
    $parser = new Parser(
        new Lexer(
            new Stream($view)
            )
    );
    foreach ($parser->parse() as $expression) {
        $expression->evaluate();
    }
});

$form_for = new Variable('form_for');
$form_for->setValue(function ($model,$action,$block_var,$attributes = NULL){
    $var = new Variable($block_var);
    $var->setValue(new Class{
        public function setModel($model)
        {
            $this->model = $model;
        }
        public function text_field($name,$attributes=NULL)
        {
            $fallback = get_fallback_vals($name) ? "value = '" . get_fallback_vals($name) . "'" : NULL;

            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='text' {$fallback} name='{$this->model}[{$name}]' {$attr_str}>";
        }

        public function hidden_field($name, $value, $attributes=NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='hidden' value='{$value}' name='{$this->model}[{$name}]' {$attr_str}>";
        }

        public function password_field($name,$attributes = NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='password' name='{$this->model}[{$name}]' {$attr_str}>";
        }

        public function email_field($name,$attributes = NULL)
        {
            $fallback = get_fallback_vals($name) ? "value = '" . get_fallback_vals($name) . "'" : NULL;
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
                echo "<input type='email' {$fallback}' name='{$this->model}[{$name}]' {$attr_str}>";
            }
        }

        public function text_area($name,$attributes = NULL)
        {
            $fallback = get_fallback_vals($name) ? "value = '" . get_fallback_vals($name) . "'" : $attributes->at('value')->evaluate();

            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<textarea name='{$this->model}[{$name}]' {$attr_str}>{$fallback}</textarea>";
        }

        public function label($name,$attributes = NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<label {$attr_str}>{$name}</label>";
        }

        public function submit($name,$attributes = NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='submit' value='{$name}' {$attr_str}>";
        }

        public function button($name,$attributes = NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<button {$attr_str}>{$name}</button>";
        }

        public function collection_select($name,$collection,$value,$display,$attributes = NULL)
        {
            $fallback = get_fallback_vals($name);
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            $inner_str = '';
            foreach ($collection as $item) {
                $var = ($fallback == $item->$value) ? "selected" : NULL;
                $inner_str .= "<option value = '{$item->$value}' {$var}>{$item->$display}</option>";
            }
            echo "<select name='{$this->model}[{$name}]' {$attr_str}>{$inner_str}</select>";
        }

        public function check_box($name,$attributes = NULL)
        {
            $fallback = get_fallback_vals($name);
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='checkbox' name='{$this->model}[{$name}]' {$attr_str} value = '{$fallback}'>";
        }

        public function radio_button($name,$attributes = NULL)
        {
            $fallback = get_fallback_vals($name);
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='radio' name='{$this->model}[{$name}]' {$attr_str} value = '{$fallback}'>";
        }

        public function file($name,$attributes = NULL)
        {
            $attr_str = '';
            if (!is_null($attributes)) {
                foreach($attributes->getValue() as $key => $value){
                    $attr_str .= $key . "='" . $value->evaluate() . "' ";
                }
            }
            echo "<input type='file' name='{$this->model}[{$name}]' {$attr_str}>";
        }
    });
    $var->getValue()->setModel($model);
    $attr_str = '';
    if (!is_null($attributes)) {
        foreach($attributes->getValue() as $key => $value){
            $attr_str .= $key . "='" . $value->evaluate() . "' ";
        }
    }
    $token = sha1(app()->registry()->get('token'));
    Dream\Session\Session::set('authenticity_token',$token);
    $form = "<form action = '{$action}' {$attr_str}>";
    $form .= "<input type='hidden' name='authenticity_token' value='{$token}'>";
    echo $form;
});

$end_form = new Variable('end_form');
$end_form->setValue(function (){
    echo "</form>";
});

$current_user = new Variable('current_user');
$current_user->setValue(function (){
    return current_user();
});

$current_user = new Variable('is_logged_in');
$current_user->setValue(function (){
    return is_logged_in();
});

$fallbacks = new Variable('fallbacks');
$fallbacks->setValue(function ($key){
    return get_fallback_vals($key);
});

$link_to = new Variable('link_to');
$link_to->setValue(function ($inner,$path,$attributes = NULL){
    $attr_str = '';
    if (!is_null($attributes)) {
        foreach($attributes->getValue() as $key => $value){
            $attr_str .= $key . "='" . $value->evaluate() . "' ";
        }
    }
    echo "<a href = '{$path}' {$attr_str}>{$inner}</a>";
});
