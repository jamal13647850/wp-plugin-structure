<?php

namespace jamal\wpmstructure;
abstract class Ajax {
    public function __construct()
    {
        $extendedClassName=explode('\\', static::class);
        $extendedClassName=end($extendedClassName);
        add_action('wp_ajax_'.$extendedClassName,[$this,'loggedInUsers']);
        add_action( 'wp_ajax_nopriv_'.$extendedClassName, [$this,'loggedOutUsers'] );
        
       
    }
    abstract function loggedInUsers();
    abstract function loggedOutUsers();

}