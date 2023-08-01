<?php
namespace jamal\wpmstructure\rest;
interface RegisterRestRouteInterface{
    public function __construct(string $nameSpace,string $route,string $methods);

    public function callback($request);

    public function registerRestRoute();
    public function applyRestRoute();

    public function addMiddleWare(APIMiddleWareInterface $middleware);
}