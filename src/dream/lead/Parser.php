<?php
namespace Lead;
use Lead\Components\{
    Assignment,
    Block,
    Call,
    Condition,
    Decimal,
    Each,
    Html,
    Integer,
    LEcho,
    LList,
    LPrint,
    LString,
    Operator,
    PropertyFetch,
    Variable
};
use Lead\Variables;
/**
 *
 */
class Parser
{
  private $tokens;

  private $stop_at = [];

  public function __construct(Lexer $lexer)
  {
    $this->stop_at[] = 'terminator';
    $this->tokens = $lexer->lex();
  }

  public function parse()
  {
      while ($this->tokens->valid())
      {
          $a = $this->next_expr(null);

          if ($a != NULL) {
              yield $a;
          }
          $this->tokens->next();
      }
  }

  public function next_expr($prev = NULL)
  {
      $current = $this->tokens->current();
      $type = $current[0];
      $value = $current[1];
      if (in_array($type,$this->stop_at) || $type == 'end')
      {
          return $prev;
      }
      elseif($type == 'symbol')
      {
          if (is_a($prev,'lead\components\variable'))
          {
              $params = $this->param_list();
              return $this->next_expr(new Call($prev,$params));
          }
          $this->tokens->next();
          $var = Variables::get($value) ?? new Variable($value);
          return $this->next_expr($var);
      }
      elseif ($type == 'number')
      {
          if (is_a($prev,'lead\components\variable'))
          {
              $params = $this->param_list();
              return $this->next_expr(new Call($prev,$params));
          }
          $this->tokens->next();
          if (strpos($value,'.'))
          {
              return $this->next_expr(new Decimal($value));
          }
          return $this->next_expr(new Integer($value));
      }
      elseif ($type == 'string')
      {
          if (is_a($prev,'lead\components\variable'))
          {
              $params = $this->param_list();
              if ($this->tokens->current()[0] == "html" ) {
                  return $this->next_expr(new Call($prev,$params));

              }
              return $this->next_expr(new Call($prev,$params));
          }
          $this->tokens->next();
          return $this->next_expr(new LString($value));
      }
      elseif ($type == 'operation')
      {
          $this->tokens->next();
          $nxt = $this->one_expr();
          $this->tokens->next();
          return $this->next_expr(new Operator($prev,$nxt,$value));
      }
      elseif ($type == 'html')
      {
          $this->tokens->next();
          return $this->next_expr(new Html($value));
      }
      elseif ($type == 'echo')
      {
          $this->tokens->next();
          $handler = new LEcho($this->next_expr());
          return $this->next_expr($handler);
      }
      elseif ($type == '=')
      {
          $this->tokens->next();
          return $this->next_expr(new Assignment($prev,$this->next_expr()));
      }
      elseif ($type == 'print')
      {
          $this->tokens->next();
          return $this->next_expr(new LPrint($this->next_expr()));
      }
      elseif ($type == 'if') {
         $a = $this->handle_if();
         $this->tokens->next();
         return $this->next_expr($a);
      }
      elseif ($type == '.')
      {
          if (!is_a($prev,'lead\components\variable') AND !is_a($prev,'lead\components\PropertyFetch'))
          {
              throw new \UnexpectedValueException('Dot Operator (.) Expecting a symbol before "."');
          }
          $this->tokens->next();
          if ($this->tokens->current()[0] !== 'symbol')
          {
              throw new \UnexpectedValueException('Expecting a property name after "."', 1);
          }
          $name = $this->tokens->current()[1];
          $handler = new PropertyFetch($prev,$name);
          $this->tokens->next();
          if ($this->tokens->current()[0] == 'symbol' ||
                $this->tokens->current()[0] == 'number' ||
                $this->tokens->current()[0] == 'string') {
              $handler->setParams($this->param_list());
          }
          return $this->next_expr($handler);
      }
      elseif ($type == 'each')
      {
          $this->tokens->next();
          $collection = $this->next_expr();
          $this->tokens->next();
          if ($this->tokens->current()[0] !== 'symbol')
          {
              throw new \UnexpectedValueException(
                  'Expecting an alias after each ' . $collection->name() . ' as ...', 1
              );
          }
          $alias = $this->tokens->current()[1];
          $this->tokens->next();
          $this->tokens->next();
          $block = $this->peek(['end']);
          $this->tokens->next();
          //dnd($this->tokens->current());
          return $this->next_expr(new Each($collection,$alias,$block));
      }
      elseif ($type == "as" || $type == "," || $type == ']')
      {
          return $prev;
      }
      elseif ( $type == ':')
      {
          $this->tokens->next();
          $val = $this->next_expr($prev);
          return [$prev,$val];
      }
      elseif ($type == "[")
      {
          $this->tokens->next();
          $array = $this->assemble(']');
          //var_dump($array);
          return $this->next_expr(new LList($array));
      }
  }

  public function assemble($stop_at)
  {
      $keyval = false;
      $ret = [];
      while ($this->tokens->valid() && $this->tokens->current()[0] !== "]") {
          if ($this->tokens->current()[0] == "terminator")
          {
              $this->tokens->next();
              continue;
          }
          $key = $this->next_expr();

          if (is_object($key))
          {
              $this->tokens->next();
          }
          if (is_array($key))
          {
              $ret[$key[0]->evaluate()] = $key[1];
          }
          else
          {
              $ret[] = $key;
          }
          if ($this->tokens->current()[0] == "]")
          {
              $this->tokens->next();
              break;
          }
          $this->tokens->next();
      }
      return $ret;
  }

  public function param_list($delim = '')
  {
      $params = [];
      while ($this->tokens->valid() && $this->tokens->current()[0] !== 'terminator' )
      {
          if ($this->tokens->current()[0] == ",")
          {
              $this->tokens->next();
              continue;
          }
          elseif ( $this->tokens->current()[0] == "html") {
              break;
          }
          $params[] = $this->one_expr();
          if ($this->tokens->current()[0] !== "terminator") {
              $this->tokens->next();
          }
      }
      return $params;
  }

  public function handle_if()
  {
      $this->tokens->next();
      $if = $this->next_expr();
      $this->tokens->next();
      $then = $this->peek(['else','elif','end']);
      $else = NULL;
      $elsif = NULL;
      if ($this->tokens->current()[0] == 'else') {
          $this->tokens->next();
          $this->tokens->next();
          $else = $this->peek(['end']);
      }
      elseif ($this->tokens->current()[0] == 'elif') {
          $elsif = $this->handle_if();
      }
      return new Condition($if,$then,$else,$elsif);
  }

  public function one_expr()
  {
      $current = $this->tokens->current();
      $type = $current[0];
      $value = $current[1];
      if($type == 'symbol')
      {
          $var = Variables::get($value) ?? new Variable($value);
          return $var;
      }
      elseif ($type == 'number')
      {
          return new Integer($value);
      }
      elseif ($type == 'string')
      {
          return new LString($value);
      }
      elseif ($type == "[")
      {
          $this->tokens->next();
          $array = $this->assemble(']');
          return $this->next_expr(new LList($array));
      }

  }

  public function peek($stop_at)
  {
      $block = new Block();
      while ($this->tokens->valid() && !in_array($this->tokens->current()[0],$stop_at))
      {
          $block->addExpression($this->next_expr());
          $this->tokens->next();
      }
      return $block;
  }
}
