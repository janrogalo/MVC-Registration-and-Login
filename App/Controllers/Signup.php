<?php

namespace App\Controllers;
use \Core\View;
use \App\Models\User;


class Signup extends \Core\Controller
{
// Show the index page @return void
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

// Sign up a new user @return void
    public function createAction()
    {
        $user = new User($_POST);
        if ($user -> save()){
            $user->sendActivationEmail();
           $this -> redirect('/signup/success');

        } else {
            View::renderTemplate('Signup/new.html', ['user'=> $user]);
        }
    }

    public function successAction(){
        View::renderTemplate('Signup/success.html');
    }


    public function activateAction(){
       User::activate($this->route_params['token']);
       $this->redirect('/signup/activated');
    }

    public function activatedAction(){
        View::renderTemplate('Signup/activated.html');
    }


}
