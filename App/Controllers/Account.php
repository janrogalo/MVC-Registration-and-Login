<?php

namespace App\Controllers;
use \App\Models\User;

class Account extends \Core\Controller
{

 public function validateEmailAction(){
$is_valid =! User::emailExists($_GET['email'], $_GET['ignore_id'] ?? null);
header('Content-Type: application/json');
echo json_encode($is_valid);
 }


}