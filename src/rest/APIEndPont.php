<?php
namespace jamal\wpmstructure\rest;
abstract class APIEndPont implements RegisterRestRouteInterface{

    private array $middleWares;
    private string $methods;
    private string $route;
    private string $nameSpace;

    public function __construct(string $nameSpace,string $route,string $methods){
        $this->nameSpace=$nameSpace;
        $this->route=$route;
        $this->methods=$methods;

        

    }

    public function applyRestRoute(): void
    {
        add_action('rest_api_init', [$this,'registerRestRoute']);
    }


    public function callback($request){
        if(!empty($this->middleWares)){
            foreach($this->middleWares as $middleWare){
                $result= $middleWare->handle($request);
                if($result instanceof \WP_REST_Request) {
                    continue;
                } else {
                    return $result;
                }
            }
        }
        
        return $this->endPointHandler($request);
        
    }

    public function addMiddleWare(APIMiddleWareInterface $middleware):APIEndPont
    {
        $this->middleWares[]=$middleware;
        return $this;
    }

    abstract public function endPointHandler($request):\WP_REST_Response;
    public function registerRestRoute(): void
    {
        register_rest_route($this->nameSpace, $this->route, array(
            'methods'  => $this->methods,
            'callback' => [$this, 'callback']
        ));
    }

}