<?php

namespace App\Http\Controllers;

/**
 *
 */
class Home extends ApplicationController
{
    public function index()
    {
        $a = (new \Db\Migration\CreateUser());
        echo $a->change();
    }
}
