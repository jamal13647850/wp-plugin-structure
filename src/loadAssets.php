<?php

declare(strict_types=1);

namespace jamal\wpmstructure;

defined('ABSPATH') || exit();

enum Media:string
{
    case all='all';
    case size280='only screen and (min-width: 280px) and (max-width: 480px)';
    case size481='only screen and (min-width: 481px) and (max-width: 768px)';
    case size769='only screen and (min-width: 769px) and (max-width: 1024px)';
    case size1025='only screen and (min-width: 1025px) and (max-width: 1200px)';
    case size1201='only screen and (min-width: 1201px)  and (max-width: 1823px)';
    case size1824='only screen and (min-width: 1824px)';
    case handheld='handheld';
    case print='print';
  
}

class loadAssets
{
    private array $cssList;
    private array $jsList;

    private array $conditionalCssList;
    private array $conditionalJsList;
    private string $mode;
    private string $cssPath;
    private string $jsPath;
    private string $domain;


    public function __construct(string $mode, string $cssPath, string $jsPath, string $domain)
    {
        $this->mode = $mode;
        $this->cssPath = $cssPath;
        $this->jsPath = $jsPath;
        $this->domain = $domain;

        $this->jsList=[];
        $this->cssList=[];

        $this->conditionalCssList=[];
        $this->conditionalJsList=[];

    }

    public function LoadStyle()
    {

        // $this->cssList = [
        //     'admin-core' => [
        //         'handle' => '',
        //         'src' => '',
        //         'deps' => [],
        //         'ver' => false,
        //         'media' => 'all'
        //     ],
        $this->cssList = $this->addType($this->createAssetsNames($this->cssList), 'css');
        foreach ($this->cssList as $key => $css) {
            wp_register_style(
                $this->cssList[$key]['handle'],
                $this->cssPath . $this->cssList[$key]['src'],
                $this->cssList[$key]['deps'],
                $this->cssList[$key]['ver'],
                $this->cssList[$key]['media']
            );
            wp_enqueue_style($this->cssList[$key]['handle']);
        }

        $this->LoadStyleOnSpecificPage();

        return $this;
    }
    public function LoadScripts()
    {
        // $this->jsList =  [
        //     'admin-core' => [
        //         'handle' => '',
        //         'src' => '',
        //         'deps' => [],
        //         'ver' => false,
        //         'in_footer' => true
        //     ],
        //   ]



        $this->jsList = $this->addType($this->createAssetsNames($this->jsList), 'js');

        foreach ($this->jsList as $key => $js) {
            wp_enqueue_script(
                $this->jsList[$key]['handle'],
                $this->jsPath . $this->jsList[$key]['src'],
                $this->jsList[$key]['deps'],
                $this->jsList[$key]['ver'],
                $this->jsList[$key]['in_footer']
            );
            wp_localize_script(
                $this->jsList[$key]['handle'],
                $this->domain,
                apply_filters("jampluginl10n", ['ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))])
            );
        }

        $this->LoadScriptsOnSpecificPage();

        return $this;
    }
    
    private function createAssetsNames(array $assets): array
    {


        foreach ($assets as $key => $asset) {
            switch ($this->mode) {
                case "development":

                    if (is_rtl()) {
                        $assets[$key]['handle'] = $key . '-rtl' . '-' . $this->domain;
                        $assets[$key]['src'] = $key . '-rtl';
                    } else {
                        $assets[$key]['handle'] = $key . '-ltr' . '-' . $this->domain;
                        $assets[$key]['src'] = $key . '-ltr';
                    }

                    break;
                case "production":
                    if (is_rtl()) {
                        $assets[$key]['handle'] = $key . '-rtl-min' . '-' . $this->domain;
                        $assets[$key]['src'] = $key . '-rtl.min';
                    } else {
                        $assets[$key]['handle'] = $key . '-ltr-min' . '-' . $this->domain;
                        $assets[$key]['src'] = $key . '-ltr.min';
                    }

                    break;
            }
        }
        return $assets;
    }


    private function addType(array $assetList, string $type): array
    {
        foreach ($assetList as $key => $value) {
            $assetList[$key]['handle'] = $assetList[$key]['handle'] . '-' . $type;
            $assetList[$key]['src'] = $assetList[$key]['src'] . '.' . $type;
        }
        return $assetList;
    }
    private function addPath(array $assetList, string $path)
    {
        foreach ($assetList as $key => $value) {
            $assetList[$key]['src'] = $path . $assetList[$key]['src'];
        }
        return $assetList;
    }



    private function LoadScriptsOnSpecificPage():\jamal\wpmstructure\loadAssets{

        
        $this->conditionalJsList = $this->addType($this->createAssetsNames($this->conditionalJsList), 'js');

        foreach ( $this->conditionalJsList as $key => $js) {
            if (is_page($this->conditionalJsList[$key]['loadOn'])) { 
                wp_enqueue_script(
                    $this->conditionalJsList[$key]['handle'],
                    $this->jsPath .  $this->conditionalJsList[$key]['src'],
                    $this->conditionalJsList[$key]['deps'],
                    $this->conditionalJsList[$key]['ver'],
                    $this->conditionalJsList[$key]['in_footer']
                );
                wp_localize_script(
                    $this->conditionalJsList[$key]['handle'],
                    $this->domain,
                    apply_filters($this->domain."cjslocal", ['ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))])
                );
            }
            
        }

        return $this;
    }

    private function LoadStyleOnSpecificPage()
    {

        

        $this->conditionalCssList = $this->addType($this->createAssetsNames($this->conditionalCssList), 'css');
        foreach ($this->conditionalCssList as $key => $css) {
            if (is_page($this->conditionalCssList[$key]['loadOn'])) { 
                wp_register_style(
                    $this->conditionalCssList[$key]['handle'],
                    $this->cssPath . $this->conditionalCssList[$key]['src'],
                    $this->conditionalCssList[$key]['deps'],
                    $this->conditionalCssList[$key]['ver'],
                    $this->conditionalCssList[$key]['media']
                );
                wp_enqueue_style($this->conditionalCssList[$key]['handle']);
            }
            
        }

        return $this;
    }

    /**
    * @param int|string|int[]|string[] $page Optional. Page ID, title, slug, or array of such
    *                                        to check against. Default empty.
    * @param array $deps
    */
    public function addJSForSpecificPage(string $name,$loadOn = '',array $deps=[],bool $ver=false,bool $in_footer=true):void{
        $this->conditionalJsList=array_merge($this->conditionalJsList,[$name => [
            'handle' => '',
            'src' => '',
            'deps' => $deps,
            'ver' => $ver,
            'in_footer' => $in_footer,
            'loadOn'=>$loadOn 
        ]]);
    }

    public function addCSSForSpecificPage(string $name,$loadOn = '',Media $media=Media::all,array $deps=[],bool $ver=false):void{
        $this->conditionalCssList=array_merge($this->conditionalCssList,[$name => [
            'handle' => '',
            'src' => '',
            'deps' => $deps,
            'ver' => $ver,
            'media' =>  $media->value,
            'loadOn'=>$loadOn 
        ]]);
    }



    public function addJS(string $name,array $deps=[],bool $ver=false,bool $in_footer=true):void{
        $this->jsList=array_merge($this->jsList,[$name => [
            'handle' => '',
            'src' => '',
            'deps' => $deps,
            'ver' => $ver,
            'in_footer' => $in_footer
        ]]);
    }

    public function addCSS(string $name,Media $media=Media::all,array $deps=[],bool $ver=false):void{
        $this->cssList=array_merge($this->cssList,[$name => [
            'handle' => '',
            'src' => '',
            'deps' => $deps,
            'ver' => $ver,
            'media' =>  $media->value
        ]]);
    }
}
