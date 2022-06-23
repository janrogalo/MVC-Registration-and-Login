<?php

namespace App\Controllers;

abstract class Authenticated extends \Core\Controller
{
    protected function before(){
        $this -> requireLogin();
    }
}