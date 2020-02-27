<?php
namespace Lead;
/**
 * Input stream
 */
class Stream implements \Iterator
{
    private $stream;

    private $key;

    public function __construct(string $stream)
    {
        $this->stream = $stream;
        $this->key = 0;
    }

    public function current()
    {
        return substr($this->stream,$this->key,1);
    }

    public function next()
    {
        $this->key = $this->key + 1;
    }

    public function valid()
    {
        return ($this->key < strlen($this->stream)) ? true : false ;
    }

    public function key()
    {
        return $this->key;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function prev()
    {
        if ($this->key > 0)
        {
            $this->key = $this->key - 1;
        }
    }
}
