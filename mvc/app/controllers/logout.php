<?php
namespace Mrkvon\Ditup\Controller;

use Mrkvon\Ditup\Core\Controller as Controller;

class Logout extends Controller
{   
    public static function route($url){
        $self = new self;
        $self->index();
    }

    public function index()
    {
        if($this->loggedin){
            session_destroy();
            //deleting login cookies
            $cookie_login = $this->staticModel('CookieLogin');
            $cookie_login::destroyLoginCookie();

            echo 'successfuly logged out';
            header("Location:/"); //improve!!
        }
        else {
            echo 'no need to log out. you are not logged in.';
            header("Location:/"); //improve!!
        }
    }

    private function bot(){
        return false;
    }

    public function verify($username, $verify_code){
        $user=$this->model('User');
        $user->verify(['username' => $username, 'verify_code' => $verify_code]);
    }
    
}
