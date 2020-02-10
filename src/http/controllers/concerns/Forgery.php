<?php
namespace Dream\Http\Controllers\Concerns;
use Dream\Session\Session;
/**
 *
 */
trait Forgery
{
    function protect_from_foregery()
    {
        if ($_POST) {
            if (!Session::check('authenticity_token')) {
                $this->will_render = false;
                header('HTTP/1.1 400 Bad Request');
                die();
            }
            if (!isset($_POST['authenticity_token'])) {
                $this->will_render = false;
                header('HTTP/1.1 400 Bad Request');
                die();
            }
            if ($_POST['authenticity_token'] !== Session::get('authenticity_token')) {
                $this->will_render = false;
                header('HTTP/1.1 400 Bad Request');
                die();
            }
        }
    }

    public function validate_token()
    {
        if (!isset($this->params['token'])) {
            $this->will_render = false;
            header('HTTP/1.1 400 Bad Request');
            die();
        }
        if ($this->params['token'] !== Session::get('authenticity_token')) {
            $this->will_render = false;
            header('HTTP/1.1 500 Internal Server Error');
            die();
        }
    }
}
