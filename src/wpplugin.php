<?php
/**
 * Created by PhpStorm.
 * User: j.ghasemi
 * Date: 2/5/2019
 * Time: 4:31 PM
 */

namespace jamal\wpmstructure;



use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

abstract class wpplugin
{
    private $domain,$LangsFilesPath;
    public function __construct($domain,$LangsFilesPath='',$file){

        defined( 'ABSPATH' ) || exit;
        $this->domain = $domain;
        $this->LangsFilesPath = $domain;

        if($LangsFilesPath==''){
            $LangsFilesPath = dirname( plugin_basename( __FILE__ ) ) . '/langs/';
        }
        $this->loadPluginTextdomain($domain,$LangsFilesPath);


        add_action('wp_enqueue_scripts', array($this,'registerScriptsAndStyles'),1);
        add_action( 'admin_enqueue_scripts', array($this,'registerAdminScriptsAndStyles'), 10000 );
        add_action( 'login_enqueue_scripts', [$this,'loginStylesheet'] );

        register_activation_hook($file ,$this->activation());
        register_deactivation_hook($file ,$this->deactivation());
    }
    public function loadPluginTextdomain($domain,$path ){
        load_plugin_textdomain( $domain, false, $path );
    }

    public function getPluginPath($dirName){
        $dir = trailingslashit(  plugin_dir_path( __FILE__ )."../"  . $dirName ) ;
        $url = trailingslashit(  plugin_dir_url ( __FILE__ ) . "../". $dirName ) ;
        return [
            'dir'=>$dir,
            'url'=>$url
        ];
    }
    abstract function loginStylesheet();
    abstract function registerScriptsAndStyles();
    abstract function registerAdminScriptsAndStyles();

    abstract function activation();
    abstract function deactivation();

    public function initTwig($dirs,$dirPath){
        $loader = new Twig_Loader_Filesystem();

        foreach ($dirs as $dir){
            $loader->addPath($dirPath.$dir, $dir);
        }

        $twig = new Twig_Environment($loader, array('debug' => true));
        $twig->addExtension(new Twig_Extension_Debug());
        //$twig->addExtension(new twigExtension());

        return $twig;
    }
}