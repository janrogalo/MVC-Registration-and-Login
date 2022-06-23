<?php

namespace App;
use App\Models\RememberedLogin;
use App\Models\User;
use App\Token;

class Auth
{

    public static function login($user, $remember_me)
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        if ($remember_me) {
            if ($user->rememberLogin()) {
                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
            }
        }
    }


    public static function logout()
    {

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        static::forgetLogin();
    }

    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }

    public static function getUser()
    {
        if (isset($_SESSION['user_id'])) {
            return User::findById($_SESSION['user_id']);
        } else {

            return static::loginFromRememberCookie();
        }
    }

    protected static function loginFromRememberCookie(){
        $cookie = $_COOKIE['remember_me'] ?? false;
        if ($cookie) {
            $remembered_login = RememberedLogin::findyByToken($cookie);
            if ($remembered_login && !$remembered_login->hasExpired()){
                $user = $remembered_login -> getUser();
                static::login($user, false);
                return $user;
            }
        }
    }

protected static function forgetLogin(){
        $cookie = $_COOKIE['remember_me'] ?? false;
        if($cookie){
            $remembered_login = RememberedLogin::findyByToken($cookie);
            if ($remembered_login){
                $remembered_login->delete();
            }
            setcookie('remember_me', '', time() - 3600);
        }
}

}