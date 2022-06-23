<?php

namespace Core;

use \App\Auth;
use \App\Flash;


abstract class Controller
{


    protected $route_params = [];


    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }


    protected function before()
    {
    }

    protected function after()
    {
    }

    public function redirect($url){
        header ('Location: http://'.$_SERVER['HTTP_HOST'].$url, true, 303);
        exit;
    }


    public function requireLogin(){
        if (! Auth::getUser()){
            Flash::addMessage('Please login to access that page', Flash::INFO);
            Auth::rememberRequestedPage();
            $this -> redirect('/login');
        }
    }
}
