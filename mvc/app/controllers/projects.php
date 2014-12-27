<?php
namespace Mrkvon\Ditup\Controller;

use Mrkvon\Ditup\Core\Controller as Controller;
use \Exception;

/**class Projects*
 * /projects/"action"
 * as opposed to /project/"project-name"
 * /projects/new, /projects/create
**/

class Projects extends Controller
{   
    public static function route($url){
        $self = new self;
        if(isset($url[0])){
            $self->$url[0]();
        }
        else{
            $self->index();
        }
    }
    public function index()
    {
        $this->view('projects/index', ['loggedin' => $this->loggedin, 'user-me' => $this->username]);
    }

    public function create()
    {
    /***if user is logged in, go to create page.
        if user is not logged in, go to /log in first
    *****/
        if($this->loggedin){
            if(isset($_POST, $_POST['projectname'], $_POST['url'], $_POST['subtitle'], $_POST['description'], $_POST['create'])){
                $project_data=[
                    'creator' => $this->username,
                    'projectname' => $_POST['projectname'],
                    'url' => $_POST['url'],
                    'subtitle' => $_POST['subtitle'],
                    'description' => $_POST['description'],
                    'create' => $_POST['create']
                ];

                try{
                    $errors = [];
                    $create_project = $this->model('CreateProject');
                    if(!$create_project->create($project_data, $errors)){
                        $this->view('projects/create', [
                            'loggedin' => $this->loggedin,
                            'user-me' => $this->username,
                            'values' => $project_data,
                            'errors' => $errors
                        ]); 
                    }
                    else{
                        header('Location:/project/'.$project_data['url'].'/edit');
                    }
                }
                catch(Exception $e){
                    $this->view('general/error', [
                        'loggedin' => $this->loggedin,
                        'user-me' => $this->username,
                        'message' => print_r($e,true)
                        ]
                    );
                }

            }
            else{
                $this->view('projects/create', ['loggedin' => $this->loggedin, 'user-me' => $this->username]);
            }
        }
        else{
            $this->view('general/error', ['loggedin' => $this->loggedin, 'user-me' => $this->username, 'message' => 'To create new project, you have to <a href="/login">log in</a> first. If you don\'t have account, you can <a href="/signup">sign up</a>.']);
        }
    }
}