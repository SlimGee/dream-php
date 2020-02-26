<?php
namespace Dream\Views;
use Dream\Views\Helpers\Assets;
use Lead\Parser;
use Lead\Lexer;
use Lead\Stream;
use Lead\Components\Variable;

include 'view_helpers.php';
/**
 *
 */
class View
{
     public function __construct($layout)
     {
         $parser = new Parser(
             new Lexer(
                 new Stream(load_file('layouts/' . $layout))
                 )
             );
         foreach ($parser->parse() as $expression) {
             $expression->evaluate();
         }
     }

     public static function register_methods($helper)
     {
         $reflector = new \ReflectionClass(get_class($helper));
         foreach ($reflector->getMethods() as $method) {
             if ($method->name == "__construct") {
                 continue;
             }
             $var = new Variable($method->name);
             $var->setValue(function () use ($helper, $method){
                 return call_user_func_array([$helper, $method->name], func_get_args());
             });
         }
     }
}
