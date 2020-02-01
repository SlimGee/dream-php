<?php

namespace Dream\Kernel;

/**
 * Application configuration abstraction
 */
class Config
{

    public function __construct(array $config)
    {
        $this->marshall($this,$config);
    }

    /**
     * Marshall the associative array into properties
     * @param Dream\Kernel\Config $class The class to inject properties
     * @param array $data The data to marshall
     */
    public function marshall($class, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $class->$key = new \stdClass();
                foreach ($value as $nkey => $nvalue){
                    if (is_array($nvalue)){
                        $this->assign($class->$key,$nvalue);
                        continue;
                    }
                    $class->$key->$nkey = $nvalue;
                }
                continue;
            }
            $class->$key = $value;
        }
    }
}
