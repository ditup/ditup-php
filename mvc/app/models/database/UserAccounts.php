<?php

namespace Mrkvon\Ditup\Model\Database;

use PDO;    //to be removed when DbAccess will be finished
use PDOException;   //to be removed when DbAccess will be finished
use Exception;

require_once dirname(__FILE__).'/db-login.php';
require_once dirname(__FILE__).'/DbAccess.php';

class UserAccounts extends DbAccess
{

    public static function insertIntoDatabase(Array $values){
        if(isset($values, $values['username'], $values['email'], $values['password'], $values['salt'], $values['iterations'])){
            $pdo = new PDO('mysql:host='.Login\HOSTNAME.';dbname='. Login\DATABASE .';charset=utf8', Login\USERNAME, Login\PASSWORD);
    
            //****************without these lines it will not catch error and not transaction well. not rollback.********
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
            $pdo->beginTransaction();
            // 
            try
            {
                // Prepare the statements
                $statement=$pdo->prepare('INSERT INTO user_accounts (username, email, password, salt, iterations) VALUES (:un, :em, :pwd, :salt, :it)');
                $statement->bindValue(':un',strval($values['username']), PDO::PARAM_STR);
                $statement->bindValue(':em',strval($values['email']), PDO::PARAM_STR);
                $statement->bindValue(':pwd',strval($values['password']), PDO::PARAM_STR);
                $statement->bindValue(':salt',strval($values['salt']), PDO::PARAM_STR);
                $statement->bindValue(':it',strval($values['iterations']), PDO::PARAM_STR);
                $statement->execute();
                
                //$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                //$data = $rows;
      //
                $pdo->commit();
                echo 'entered data to database';
            }
            catch(PDOException $e)
            {
                $pdo->rollBack();
                throw new Exception('database problem: ' . $e);
              // Report errors
            }
            unset($pdo);
            // echo $data;
            //return $data;
        }
        else{
            if(!isset($values)) throw new Exception ('Users::insertIntoDatabase Error: array of values must be provided!');
            elseif(!isset($values['username'])) throw new Exception ('Users::insertIntoDatabase Error: username must be provided!');
            elseif(!isset($values['email'])) throw new Exception ('Users::insertIntoDatabase Error: email must be provided!');
            elseif(!isset($values['password'])) throw new Exception ('Users::insertIntoDatabase Error: hashed password must be provided!');
            elseif(!isset($values['salt'])) throw new Exception ('Users::insertIntoDatabase Error: salt must be provided!');
            elseif(!isset($values['iterations'])) throw new Exception ('Users::insertIntoDatabase Error: number of iterations must be provided!');
            else throw new Exception ('general exception');
        }
    }
    
    public function updateVerifyCode($values){
        if(isset($values, $values['username'], $values['email'], $values['verify_code'], $values['delete_code'])){
            
            $this->dbConnect();
            $this->dbExecute('UPDATE user_accounts SET verify_code=:vc, delete_code=:dc, code_created=CURRENT_TIMESTAMP WHERE username=:un AND email=:em', [':un' => $values['username'], ':em' => $values['email'], ':vc' => $values['verify_code'], ':dc' => $values['delete_code']]);
            $this->dbDisconnect();
        }
        else{
            if(!isset($values)) throw new Exception ('Users::updateVerifyCode Error: array of values must be provided!');
            elseif(!isset($values['username'])) throw new Exception ('Users::updateVerifyCode Error: username must be provided!');
            elseif(!isset($values['email'])) throw new Exception ('Users::updateVerifyCode Error: email must be provided!');
            elseif(!isset($values['verify_code'])) throw new Exception ('Users::updateVerifyCode Error: verification code must be provided!');
            elseif(!isset($values['delete_code'])) throw new Exception ('Users::updateVerifyCode Error: deletion code must be provided!');
            else throw new Exception ('general exception, debugging will be needed');
        }
    }

    public static function selectAccountByUsername($username){        
        require_once dirname(__FILE__).'/db-login.php';
        $pdo = new PDO('mysql:host='.Login\HOSTNAME.';dbname='. Login\DATABASE .';charset=utf8', Login\USERNAME, Login\PASSWORD);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
        $pdo->beginTransaction();
        // 
        try
        {
            // Prepare the statements
            $statement=$pdo->prepare('SELECT * FROM user_accounts WHERE username=:un');
            $statement->bindValue(':un',strval($username), PDO::PARAM_STR);
            $statement->execute();
            
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $data=$rows;
  // 
            $pdo->commit();
        }
        catch(PDOException $e)
        {
          $pdo->rollBack();
      
          //$outcome=htmlentities(print_r($e,true));
          // Report errors
        }
        unset($pdo);

        return $data;
    }    
    
    public function updateVerified($values){
        if(isset($values, $values['username'], $values['verify_code'])){
            $this->dbConnect();
            $this->dbExecute('UPDATE user_accounts SET verified=TRUE WHERE username=:un AND verify_code=:vc AND TIME_TO_SEC(TIMEDIFF(NOW(),code_created))<21600' , [':un'=>$values['username'], ':vc' => $values['verify_code']]);
            $this->dbDisconnect();
        }
        else{
            if(!isset($values)) throw new Exception ('Users::updateVerifyCode Error: array of values must be provided!');
            elseif(!isset($values['username'])) throw new Exception ('Users::updateVerifyCode Error: username must be provided!');
            elseif(!isset($values['verify_code'])) throw new Exception ('Users::updateVerifyCode Error: verification code must be provided!');
            else throw new Exception ('general exception, debugging will be needed');
        }
    }
    
    public static function selectAccountByEmail($email){        
        require_once dirname(__FILE__).'/db-login.php';
        $pdo = new PDO('mysql:host='.Login\HOSTNAME.';dbname='. Login\DATABASE .';charset=utf8', Login\USERNAME, Login\PASSWORD);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
        $pdo->beginTransaction();
        // 
        try
        {
            // Prepare the statements
            $statement=$pdo->prepare('SELECT * FROM user_accounts WHERE email=:email');
            $statement->bindValue(':email',strval($email), PDO::PARAM_STR);
            $statement->execute();
            
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $data=$rows;
  // 
            $pdo->commit();
        }
        catch(PDOException $e)
        {
          $pdo->rollBack();
      
          //$outcome=htmlentities(print_r($e,true));
          // Report errors
        }
        unset($pdo);

        return $data;
    }    
}
