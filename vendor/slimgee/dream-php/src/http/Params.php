<?php
namespace Dream\Http;
/**
 *
 */
class Params extends \ArrayObject
{
    function __construct($params)
    {
        $params = sanitize($params);
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $class = __CLASS__;
                $params[$key] = new $class($value);
            }
        }
        parent::__construct($params);
    }

    public function permit($permited = [])
    {
        $ret = [];

        foreach ($permited as $value) {
            if (!array_key_exists($value,$this)) {
                continue;
            }
            $ret[$value] = $this[$value];
        }
        return $ret;
    }
}
