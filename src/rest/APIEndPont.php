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
                return $middleWare->handle($request);
            }
        }
        
        return $this->endPointHandler($request);
        
    }

    public function addMiddleWare(APIMiddleWareInterface $middleware): void
    {
        $this->middleWares[]=$middleware;
    }

    abstract public function endPointHandler($request):\WP_REST_Response;
    public function registerRestRoute(): void
    {
        register_rest_route($this->nameSpace, $this->route, array(
            'methods'  => $this->methods,
            'callback' => [$this, 'callback'],
            // 'permission_callback' => function() {
            //     // Check if the request comes from the 'dashboard.example.com' domain
            //     $referrer_host = parse_url(wp_get_raw_referer(), PHP_URL_HOST);
            //     if ($referrer_host !== 'dashboard.example.com') {
            //         return new \WP_Error('invalid_referer', 'Invalid referrer domain.', array('status' => 403));
            //     }
    
            //     // Check if the requester's IP is 98.67.56.87
            //     $requester_ip = $_SERVER['REMOTE_ADDR'];
            //     if ($requester_ip !== '98.67.56.87') {
            //         return new \WP_Error('invalid_ip', 'Access denied for your IP.', array('status' => 403));
            //     }
    
            //     return true;
            // },
        ));
    }

}