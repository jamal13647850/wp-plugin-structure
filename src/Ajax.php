<?php

namespace jamal\wpmstructure;
abstract class Ajax {
    public function __construct($method)
    {
        add_action('wp_ajax_'.$method,[$this,'loggedInUsers']);
        add_action( 'wp_ajax_nopriv_'.$method, [$this,'loggedOutUsers'] );
    }
    abstract function loggedInUsers();
    abstract function loggedOutUsers();

}