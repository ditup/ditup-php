<?php
namespace Mrkvon\Ditup\Controller;

use Mrkvon\Ditup\Core\Controller as Controller;

class User extends Controller
{
    public static function route($url){
        $self = new self;
        $self->index(isset($url[0]) ? $url[0] : '', array_slice($url, 1));
    }

    public function index($name = '', $action=[])
    {
        $user = $this->model('User');
        $user->name = $this->username;
        $member = $this->model('User');
        $member->name = $name;

        $user_profile = $this->model('UserProfile');
        $user_profile->setUsername($name);
        
        if($name===''){
            /****redirect to general people page*****/
            header('Location:/people');
            exit();
        }
        elseif($member->exists($name)){
            //get profile data from database
            $action[0] = isset($action[0]) ? $action[0] : '';
            switch($action[0]){
                case 'edit':
                    $this->edit($user->name, $member->name, $user_profile, $this->loggedin);
                    break;
                case 'info':
                    echo 'implement info!! (controllers/User.php)';
                    break;
                case 'change-password':
                    if($member->name === $user->name && $this->loggedin){
                        if(isset($_POST, $_POST['submit'])){
                            $errors = [];
                            if($user->changePassword([
                                'username' => $member->name,
                                'old-password' => $_POST['old-password'],
                                'new-password' => $_POST['new-password'],
                                'new-password2' => $_POST['new-password2']
                            ], $errors)){
                                $this->view('general/message', [
                                    'loggedin' => $this->loggedin,
                                    'user-me' => $user->name,
                                    'message' => 'Password of user '.$member->name.' was successfuly changed.'
                                ]);
                            }
                            else{
                                $this->view('people/change-password', [
                                    'loggedin' => $this->loggedin,
                                    'user-me' => $user->name,
                                    'member' => $member->name,
                                    'errors' => $errors
                                ]);
                            }
                            /*validate and process data... submit to database or show form with errors...*/   
                        }
                        else{
                            $this->view('people/change-password', [
                                'loggedin' => $this->loggedin,
                                'user-me' => $user->name,
                                'member' => $member->name
                            ]);
                        }
                    }
                    else{
                        $this->view('general/error',
                            [
                                'loggedin' => $this->loggedin,
                                'user-me' => $user->name,
                                'message' => 'You don\'t have rights to change password of user '.$member->name.'.'
                            ]
                        );
                    }
                    break;
                case 'message':
                    // /user/[username]/message/[message-id]
                    break;
                case 'messages':
                    // /user/[username]/messages

                    break;
                case 'dits':
                    $this->dits($member->name, $user->name);
                    break;
                case 'settings':
                    $this->settings($user->name, $member->name, $user_profile, $this->loggedin);
                    break;
                case '':
		    $static_user_profile = $this->staticModel('UserProfile');
		    $tags = $static_user_profile::getTags($member->name);
                    $profile_data=$user_profile->getProfile($member->name);
		    $profile_data['tags'] = $tags;
                    if(is_array($profile_data)){
                        if($member->name === $user->name && $this->loggedin){
                            $this->view('people/profile', ['loggedin' => $this->loggedin, 'user-me' => $user->name, 'member' => $member->name, 'profile' => $profile_data]);
                        }
                        else{
                            $this->view('people/profile', ['loggedin' => $this->loggedin, 'user-me' => $user->name, 'member' => $member->name, 'profile' => $profile_data]);       
                        }
                    }
                    elseif($profile_data===false){
                        $this->view('general/error',
                            [
                                'loggedin' => $this->loggedin,
                                'user-me' => $user->name,
                                'message' => 'user '.$member->name.' doesn\'t exist.'
                            ]
                        );
                    }
                    break;
                default:
                    exit('404 Page Not Found');
            }
        }
        else{
            $this->view('general/error', ['loggedin' => $this->loggedin, 'user-me' => $user->name, 'member' => $member->name, 'message' => 'User ' . $member->name . ' was not found or is hidden from you.']);
        }
    }

    private function edit($username_me, $username_member, $user_profile_class, $loggedin){
        if($username_me === $username_member && $loggedin){
            //print_r($_POST);
            //echo (isset($_POST)?'post true':'post false');
            //echo (isset($_FILES)?'file true':'file false');
            print_r($_FILES);
            if(!empty($_POST) && isset($_POST['submit'])&&$_POST['submit']=='update profile'){
                /*here data should be entered to database.
                what data?
                
                */
                $data = $user_profile_class->getProfile($username_member);
                print_r($data);
                foreach($_POST as $field=>$value){
                    $data[$field]=$value;
                }

                $data = $_POST;
                //print_r($_POST);
                $errors=array();
                if($user_profile_class->validate($data, $errors)){
                    $data['username'] = $username_member;
                    $data['v_age'] = (isset($data['v_age'])&&$data['v_age']=='on') ? true : false;
                    $data['v_about'] = (isset($data['v_about'])&&$data['v_about']=='on') ? true : false;
                    $data['v_gender'] = (isset($data['v_gender'])&&$data['v_gender']=='on') ? true : false;
                    $data['v_website'] = (isset($data['v_website'])&&$data['v_website']=='on') ? true : false;
                    $data['v_bewelcome'] = (isset($data['v_bewelcome'])&&$data['v_bewelcome']=='on') ? true : false;
                    //print_r($data);
                    $user_profile_class->setProfile($data);
                    
                    header('Location:/user/'.$this->username);
                    exit();
                }
                else{
                    $this->view('people/profile-edit', [
                        'loggedin' => $this->loggedin,
                        'user' => $username_me,
                        'member' => $username_member,
                        'profile' => $data,
                        'errors' => $errors
                    ]);
                }
            }
            elseif(!empty($_FILES)){
                if(isset($_FILES['profile-picture'])){
                    $file=$_FILES['profile-picture'];
                }
                else exit('you have to enter file to upload (go to <a href=".">form</a>)');

                echo '<br />';

                if($file['error']===0){
                    //echo '<br />$file[type]='.$file['type'].'<br />';
                    if($file['type']==='image/png' || $file['type']==='image/jpeg'){
                        //print_r(getcwd());
                        $filetype='';
                        if($file['type']==='image/png') $filetype='png';
                        elseif($file['type']==='image/jpeg') $filetype='jpg';
                        $filename=$username_me.'.'.$filetype;
                        if(move_uploaded_file($file['tmp_name'], 'img/profile/'.$filename)){
                            if(file_exists('img/profile/'.$username_me.'.jpg')&&$filename!==$username_me.'.jpg') unlink('img/profile/'.$username_me.'.jpg');
                            if(file_exists('img/profile/'.$username_me.'.png')&&$filename!==$username_me.'.png') unlink('img/profile/'.$username_me.'.png');
                            header('Location:/user/'.$username_me);
                            exit();
                        }
                        else exit('failed to upload file.');
                    }
                    else exit('only png, jpg allowed');
                }
                else exit('error uploading file');

                exit();
            }
            else{
                $this->view('people/profile-edit', ['loggedin' => $loggedin, 'user' => $username_me, 'member' => $username_member, 'profile' => $user_profile_class->getProfile($username_member)]);
            }
        }
        else {
            $this->view('general/error', ['loggedin' => $this->loggedin, 'user-me' => $username_me, 'member' => $username_member, 'message' => 'Sorry, you don\'t have rights to edit profile of user '.$username_member.'.']);
        }
    }

    private function settings($username_me, $username_member, $user_profile, $loggedin){
        if($username_me === $username_member && $loggedin===true){
            $usr = $this->model('User');
            $settings = $usr->readSettings($username_member);
            if(!empty($_POST) && isset($_POST['save'])&&$_POST['save']=='save settings'){
                /*here data should be entered to database.
                what data?
                
                */
                $submitted_settings=$_POST;
                print_r($_POST);
                $errors=array();
                if(!$usr->saveSettings($username_member, $submitted_settings, $errors)){
                    $this->view('people/edit-settings', [
                        'loggedin' => $this->loggedin,
                        'user-me' => $username_me,
                        'member' => $username_member,
                        'settings' => $settings,
                        'errors' => $errors
                    ]);
                    exit();
                }
                else{
                    $this->view('general/message', [
                        'loggedin' => $this->loggedin,
                        'user-me' => $username_me,
                        'message' => 'settings of '.$username_member.' were successfuly saved',
                    ]);
                    exit();
                }
            }
            else{
                $this->view('people/edit-settings', [
                    'loggedin' => $loggedin,
                    'user-me' => $username_me,
                    'member' => $username_member,
                    'settings' => $settings,
                    'errors' => []
                ]);
            }
        }
        else {
            $this->view('general/error', ['loggedin' => $this->loggedin, 'user-me' => $username_me, 'message' => 'Sorry, you don\'t have rights to edit settings of user '.$username_member.'.']);
        }
    }

    private function dits($username_member, $username_me){
        $static_user_profile = $this->staticModel('UserProfile');
        $projects=$static_user_profile::getProjects($username_member);
        $this->view('people/profile/projects', ['loggedin' => $this->loggedin, 'user-me' => $username_me, 'member' => $username_member, 'projects' => $projects]);       
        exit('implement projects!');
    }
}
