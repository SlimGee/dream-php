<?php
namespace Dream\Database\ActiveRecord\Concerns;

/**
 *
 */
trait Relationship
{
  function has_many($attributes)
  {
    $attributes = strtolower($attributes);
    $class = "App\\Models\\" . ucfirst(singular($attributes));
    $clause = singular($this->table) . '_id';
    $proxy = new \Dream\Database\ActiveRecord\Proxy($class::where($clause . " = {$this->id}",true));
    $proxy::$clause = $clause;
    $proxy::$identity = $this->id;
    $proxy::$class = $class;
    return $proxy;
  }

  public function belongs_to($attribute)
  {
    $attribute = strtolower($attribute);
    $class = "App\\Models\\" . ucfirst(singular($attribute));
    $clause = singular($attribute) . '_id';
    return $class::find($this->$clause);
  }

  public function __get($attr)
  {
    if (!method_exists($this,$attr))
    {
      return null;
    }
    return $this->$attr();
  }
}
