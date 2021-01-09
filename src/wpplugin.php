<?php
/**
 * Created by PhpStorm.
 * User: j.ghasemi
 * Date: 2/5/2019
 * Time: 4:31 PM
 */

namespace jamal\wpmstructure;


//require_once __DIR__ . '/../vendor/autoload.php';

use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

abstract class wpplugin
{
    private static $domain,$LangsFilesPath,$mode;
    
    public function __construct($domain,$file,$mode,$LangsFilesPath=''){

        defined( 'ABSPATH' ) || exit;
        self::$domain = $domain;
        self::$mode = $mode;
        self::$LangsFilesPath = $LangsFilesPath;
        self::$LangsFilesPath = $LangsFilesPath==''?dirname( plugin_basename( $file ) ) . '/langs/':$LangsFilesPath;
        
        $this->loadPluginTextdomain($domain,self::$LangsFilesPath);
        
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
        $loader = new \Twig\Loader\FilesystemLoader();

        foreach ($dirs as $dir){
            $loader->addPath($dirPath.$dir, $dir);
        }

        $twig = new \Twig\Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        
        //$twig->addExtension(new twigExtension());
        return $twig;
    }

    /**
     * Get the value of domain
     */ 
    public static function getDomain()
    {
        return self::$domain;
    }

    /**
     * Get the value of mode
     */ 
    public static function getMode()
    {
        return self::$mode;
    }
}