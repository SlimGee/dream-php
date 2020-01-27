<?php
namespace Dream\Database\ActiveRecord;

/**
 * Rowset object
 * This is the object that can be returned when the all() method is called on
 * models
 */
class RowSet extends Model implements \IteratorAggregate
{

  /**
   * the Iterator that we are using
   * @var Iterator
   */
  public $iterator;

  private $count;

  /**
   * class constructor
   * initializes the models property
   * @param array of type Model
   */
  public function __construct(array $models)
  {
    $this->iterator = new \ArrayIterator($models);
    $this->count = count($this->iterator);
  }

  /**
   *
   */
  public function getIterator()
  {
    return $this->iterator;
  }

  /**
   * limit number of rows to return
   * comes in handy for pagination
   * @param int the limit
   * @return Iterator rows iterator
   */
  public function limit($value)
  {
    $this->iterator = new \LimitIterator($this->iterator,0,$value);
    return $this;
  }

  public function first_of()
  {
      return $this->iterator->offsetGet(0);
  }

  /**
   * offset of rows to return
   * comes in handy for pagination
   * @param int the offset
   * @return Iterator rows iterator
   */
  public function offset($value)
  {
    $this->iterator = new \LimitIterator($this->iterator,$value);
    return $this;
  }

  public function count()
  {
    return $this->count;
  }

  public function any()
  {
    return ($this->count() > 0);
  }
  /**
   * order the result by a specific attribute
   * @param string how
   * @return object $this;
   */
  public function order_by($by,$how = ''){
    $this->by = $by;
    switch (strtolower($how))
    {
      case 'desc':
        $this->iterator->uasort(function($a,$b)
        {
          $by = $this->by;
          if ($a->$by == $b->$by)
          {
            return 0;
          }
          if ($a->$by < $b->$by)
          {
            return 1;
          }
          return -1;
        });
        break;

      default:
        $this->iterator->uasort(function($a,$b)
        {
          $by = $this->by;
          if ($a->$by == $b->$by)
          {
            return 0;
          }
          if ($a->$by < $b->$by)
          {
            return -1;
          }
          return 1;
        });
        break;
    }
    return $this;
  }
}
