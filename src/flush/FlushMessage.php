<?php

namespace Dream\Flush;

use Dream\Session\Session;

/**
 *
 */
class FlushMessage extends \ArrayObject
{
    public function __construct()
    {
        $flush['alert'] = [];
        $flush['notice'] = [];
        parent::__construct($flush);
    }

    public function set_notice($value)
    {
        $this['notice'][] = $value;
        Session::set('notice',$this['notice']);
    }

    public function set_alert($value)
    {
        $this['alert'][] = $value;
        Session::set('alert',$this['alert']);
    }

    public function notice()
    {
        $flush = Session::get('notice');
        Session::erase('notice');
        return $flush;
    }

    public function alert()
    {
        $flush = Session::get('alert');
        Session::erase('alert');
        return $flush;
    }

    public function alert_any()
    {
        return (Session::check('alert') && !empty(Session::get('alert')));
    }

    public function notice_any()
    {
        return (Session::check('notice') && !empty(Session::get('notice')));
    }
}
