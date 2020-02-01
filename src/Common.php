<?php
use Dream\Registry;
use Dream\Http\Sessions\Session;
use Lead\Components\Variable;
use Lead\{
    Lexer,
    Parser,
    Stream
};

$singular = [
  "(matr)ices$" => "\\1ix",
  "(vert|ind)ices$" => "\\1ex",
  "^(ox)en" => "\\1",
  "(alias)es$" => "\\1",
  "([octop|vir])i$" => "\\1us",
  "(cris|ax|test)es$" => "\\1is",
  "(shoe)s$" => "\\1",
  "(o)es$" => "\\1",
  "(bus|campus)es$" => "\\1",
  "([m|l])ice$" => "\\1ouse",
  "(x|ch|ss|sh)es$" => "\\1",
  "(m)ovies$" => "\\1\\2ovie",
  "(s)eries$" => "\\1\\2eries",
  "([^aeiouy]|qu)ies$" => "\\1y",
  "([lr])ves$" => "\\1f",
  "(tive)s$" => "\\1",
  "(hive)s$" => "\\1",
  "([^f])ves$" => "\\1fe",
  "(^analy)ses$" => "\\1sis",
  "((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$" => "\\1\\2sis",
  "([ti])a$" => "\\1um",
  "(p)eople$" => "\\1\\2erson",
  "(m)en$" => "\\1an",
  "(s)tatuses$" => "\\1\\2tatus",
  "(c)hildren$" => "\\1\\2hild",
  "(n)ews$" => "\\1\\2ews",
  "([^u])s$" => "\\1"
];

$plural = [
  "^(ox)$" => "\\1\\2en",
  "([m|l])ouse$" => "\\1ice",
  "(matr|vert|ind)ix|ex$" => "\\1ices",
  "(x|ch|ss|sh)$" => "\\1es",
  "([^aeiouy]|qu)y$" => "\\1ies",
  "(hive)$" => "\\1s",
  "(?:([^f])fe|([lr])f)$" => "\\1\\2ves",
  "sis$" => "ses",
  "([ti])um$" => "\\1a",
  "(p)erson$" => "\\1eople",
  "(m)an$" => "\\1en",
  "(c)hild$" => "\\1hildren",
  "(buffal|tomat)o$" => "\\1\\2oes",
  "(bu|campu)s$" => "\\1\\2ses",
  "(alias|status|virus)" => "\\1es",
  "(octop)us$" => "\\1i",
  "(ax|cris|test)is$" => "\\1es",
  "s$" => "s",
  "$" => "s"
];

function normalize($pattern)
{
  return '#' . trim($pattern, '#') . '#';
}

function pluralize($string)
{
  global $plural;
  $result = $string;
  foreach ($plural as $rule => $replacement)
  {
    $rule = normalize($rule);
    if (preg_match($rule, $string))
    {
      $result = preg_replace($rule, $replacement, $string);
      break;
    }
  }
  return $result;
}

function singular($string)
{
  global $singular;
  $result = $string;
  foreach ($singular as $rule => $replacement)
  {
    $rule = normalize($rule);
    if (preg_match($rule, $string))
    {
      $result = preg_replace($rule, $replacement, $string);
      break;
    }
  }
  return $result;
}

function redirect_to($location)
{
    app()->registry()->get('controller')->willRrender = false;
    header('Location: ' . $location);
    exit();
}

function notice($value)
{
    app()->registry()->get('flush')->set_notice($value);
}

function alert($value)
{
    app()->registry()->get('flush')->set_alert($value);
}

function _dlog($log)
{
    $l = file_get_contents('log.txt');
    file_put_contents('log.txt',$l . $log . "\n");
}

function _flush($value='')
{
    if (strlen($value) < 1) {
        return app()->registry()->get('flush');
    }
    return app()->registry()->get('flush')[$value];
}

function dnd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

if (!function_exists('split')) {
    function split($string,$pattern,$limit=null)
    {
        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        return preg_split(normalize($pattern), $string, $limit, $flags);
    }
}

if (!function_exists('match')) {
    function match($string, $pattern)
    {
        preg_match_all(normalize($pattern), $string, $matches, PREG_PATTERN_ORDER);
        if (!empty($matches[1])){
            return $matches[1];
        }
        if (!empty($matches[0])){
            return $matches[0];
        }
        return null;
    }
}

if (!function_exists('a_clean')) {
    function a_clean($array)
    {
        return array_filter($array, function($item) {
            return !empty($item);
        });
    }
}

if (!function_exists('a_trim')) {
    function a_trim($array)
    {
        return array_map(function($item) {
            return trim($item);
        }, $array);
    }
}

if (!function_exists('fallback_vals')) {
    function fallback_vals(array $prev)
    {
        $data = $prev;
        unset($data['password']);
        unset($data['password_confirmation']);
        Session::set('fallback_vals',$data);
    }
}

if (!function_exists('sanitize')) {
    function sanitize(array $input)
    {
        return array_map(function($item) {
            if (is_array($item)) {
                return sanitize($item);
            }
            return htmlentities($item, ENT_NOQUOTES, 'utf-8');
        }, $input);
    }
}

if (!function_exists('get_fallback_vals')) {
    function get_fallback_vals($name)
    {
        if (!Session::check('fallback_vals')) {
            return;
        }
        if (!isset(Session::get('fallback_vals')[$name])) {
            return;
        }
        return Session::get('fallback_vals')[$name];
    }
}

if (!function_exists('flush_fallback_vals')) {
    function flush_fallback_vals()
    {
        return Session::erase('fallback_vals');
    }
}

if (!function_exists('load_file')) {
    function load_file($file){
        return file_get_contents(ROOT . DS . 'app' . DS .'views' . DS . $file . '.php');
    }
}

if (!function_exists('get_class_meta')) {
    function get_class_meta($class,$method)
    {
        $reflector = new \ReflectionMethod($class,$method);
        $parts = match($reflector->getDocComment(),'@[a-zA-Z]+\s*[a-zA-Z0-9, _]*');

        if (!($parts)) {
            return;
        }
        $meta = [];
        foreach ($parts as $part) {
            $partb = a_clean(a_trim(split($part,'[\s]')));
            for ($i=1; $i < sizeof($partb); $i++) {
                $meta[$partb[0]][] = $partb[$i];
            }
        }
        return $meta;
    }
}

if (!function_exists('back_link')) {
    function back_link()
    {
        return app()->registry()->get('back_link');
    }
}

if (!function_exists('redirect_back')) {
    function redirect_back()
    {
        return redirect_to(back_link());
    }
}


foreach (\Dream\Route\Router::$get as $key => $value) {

    $name = (isset($value->name)) ? $value->name : $name = $value->controller . '_' .$value->action;
    eval("
    use Lead\\Components\\Variable;
    if(!function_exists('{$name}_path')){
        function {$name}_path()
        {
            if ('{$key}' == 'root') {
                return '/';
            }
            if (preg_match_all('/\:\w+/','{$key}',\$matches)) {
                \$args = func_get_args();

                \$args = array_map(function(\$item) {
                    return (is_object(\$item)) ? \$item->id : \$item;
                },\$args);
                if (sizeof(\$args)<1) {
                    throw new InvalidArgumentException('Argument for route {$name} cannot be null', 1);
                }
                \$i = 0;
                \$a = '{$key}';
                foreach (\$matches[0] as \$m) {
                    \$a = preg_replace('#' . \$m . '#',\$args[0],\$a);
                }
                return \$a;
            }
            return '{$key}';
            }
        }
        \$var = new Variable('{$name}' . '_path');
        \$var->setValue(function (){
                return call_user_func_array('{$name}' . '_path',func_get_args());
        });
    ");
}


foreach (\Dream\Route\Router::$post as $key => $value) {
    $name = (isset($value->name)) ? $value->name : $name = $value->controller . '_' .$value->action;
    eval("
    use Lead\\Components\\Variable;
    if(!function_exists('{$name}_path')){
        function {$name}_path()
        {
            if ('{$key}' == 'root') {
                return '/';
            }
            if (preg_match_all('/\:\w+/','{$key}',\$matches)) {
                \$args = func_get_args();
                \$args = array_map(function(\$item) {
                    return (is_object(\$item)) ? \$item->id : \$item;
                },\$args);
                if (sizeof(\$args)<1) {
                    throw new InvalidArgumentException('Argument for route {$name} cannot be null', 1);
                }
                \$i = 0;
                \$a = '{$key}';
                foreach (\$matches[0] as \$m) {
                    \$a = preg_replace('#' . \$m . '#',\$args[0],\$a);
                }
                return \$a;
            }
            return '{$key}';
        }}
        \$var = new Variable('{$name}' . '_path');
        \$var->setValue(function (){
                return call_user_func_array('{$name}' . '_path',func_get_args());
        });
    ");
}



function render_view($name)
{
    app()->registry()->get('controller')->will_render = false;
    app()->registry()->get('controller')->set_view_vars();
    $parser = new Parser(
        new Lexer(
            new Stream(load_file($name))
            )
        );
    foreach ($parser->parse() as $expression) {
        $expression->evaluate();
    }
}

function app()
{
    return Dream\Kernel\App::instance();
}
