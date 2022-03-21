<?php

namespace jamal\wpmstructure;
abstract class Ajax {
    public function __construct()
    {
        add_action('wp_ajax_'.static::class,[$this,'loggedInUsers']);
        add_action( 'wp_ajax_nopriv_'.static::class, [$this,'loggedOutUsers'] );
    }
    abstract function loggedInUsers();
    abstract function loggedOutUsers();

}