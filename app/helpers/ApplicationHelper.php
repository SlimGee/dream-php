<?php
namespace App\Helpers;
use Dream\Views\Helpers\Helper;
/**
 * Application view helper
 */
class ApplicationHelper extends Helper
{
    public function title()
    {
        if (isset($this->controller->message)) {
            return "";
        }
        return "The Dream Framework";
    }
}
