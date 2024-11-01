<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    public function loadPluginator()
    {
        $pluginator_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/pluginator.php';
        // error_log("pluginator_path: "  . $pluginator_path);
        require_once $pluginator_path;
    }
}
