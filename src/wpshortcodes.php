<?php
/**
 * Created by PhpStorm.
 * User: j.ghasemi
 * Date: 2/6/2019
 * Time: 11:22 AM
 */

namespace jamal\wpmstructure;


abstract class wpshortcodes
{
    protected $tag;
    public function __construct($tag){
        $this->tag=$tag;
        $this->Register();
    }

    public function Register(){
        add_action( 'init', array($this,'AllShortCodes'));
    }
    //add_action( 'init', array($this,'AllShortCodes'));
     function AllShortCodes(){
         add_shortcode($this->tag,array($this,'mainFunction'));
     }
    //add_shortcode('safircode',array($this,'safirCodeFunc'));
    abstract function mainFunction($atts);
    /*function mainFunction($atts) {
        $a = shortcode_atts(array(
            'form' => ''
        ), $atts);
        $form = $a['form'];

        switch ($form) {
            case 'placement':
                $placement=new placement();
                $placement->startPlacement();
                break;
                break;
        }
    }*/

}