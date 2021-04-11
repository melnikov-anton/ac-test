<?php

class Router {

  private $routes;

  public function __construct($routes) {
    $this->routes = $routes;
  }

  public function route() {
    $reqUri = $_SERVER['REQUEST_URI'];
    $reqPath = explode('?', $reqUri)[0];
    $reqMethod = $_SERVER['REQUEST_METHOD'];
    if (array_key_exists($reqPath, $this->routes)) {
      $currentRoute = $this->routes[$reqPath];
      if (array_key_exists($reqMethod, $currentRoute)) {
        $controller = $currentRoute[$reqMethod]['controller'];
        $action = $currentRoute[$reqMethod]['action'];
        if(method_exists($controller, $action)) {
          $instance = new $controller();
          call_user_func_array([$instance, $action], []);
        } else {
          $error_response = new ErrorResponse();
          $error_response(500);
        }
      } else {
        $error_response = new ErrorResponse();
        $error_response(405);
      }
    } else {
      $error_response = new ErrorResponse();
      $error_response(404);
    }
  }
}
