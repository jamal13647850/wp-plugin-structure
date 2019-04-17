<?php
/**
 * Created by PhpStorm.
 * User: j.ghasemi
 * Date: 4/17/2019
 * Time: 10:23 AM
 */

namespace jamal\wpmstructure;


abstract class wphooks
{
    public function __construct(){
        $this->registerHooks();
    }

    abstract function registerHooks();
}