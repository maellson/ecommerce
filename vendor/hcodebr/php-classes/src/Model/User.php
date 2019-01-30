<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Mailer;


class User extends Model{
    
    const SESSION = "User";
    const SECRET = "HcodePhp7_Secret";

    public static function login($login, $password)
    {

		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		if(count ($results)===0){
			throw new \Exception('Usuário inexistente ou senha inválida!');
		}

		$data = $results[0];
		if(password_verify($password, $data["despassword"])===true)//verifica se o password vindo como parametro é igual so do banco "despassword"
		{
			$user = new User();
			$user->setData($data);
                        
                        $_SESSION[User::SESSION]= $user->getValues();
                        
                        return $user;


		} else {

			throw new \Exception('Usuário inexistente ou senha inválida!');
		}

	}

        
    public static function verifyLogin($inadmin = true) {
        if(
                
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"]>0
            ||
            (bool)$_SESSION[User::SESSION]["inadmin"]!== $inadmin
           ){
               header("Location: /admin/login");
               exit;
            
        }
        
    }



    public static function logout() 
    {
        $_SESSION[User::SESSION]=NULL;
        
    }


    public static function listAll(){

    	$sql = new Sql();

    	 return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING (idperson) ORDER BY b.desperson ");


    }
    
    public function save(){
//        pdesperson VARCHAR(64),
//        pdeslogin VARCHAR(64),
//        pdespassword VARCHAR(256),
//        pdesemail VARCHAR(128),
//        pnrphone BIGINT,
//        pinadmin TINYINT
//
        $sql = new Sql();
        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail,:nrphone, :inadmin)",
            array(
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
            
        ));
        
        $this->setData($results[0]);
    }
    
    public function get($iduser){
        
        $sql = new Sql();
       $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",
                array(
                    ":iduser"=> $iduser
                ));
       
       $this->setData($results[0]);
    }
    
    public function update(){
        
        $sql = new Sql();
        $results = $sql->select("CALL sp_users_update_save(:iduser, :desperson, :deslogin, :despassword, :desemail,:nrphone, :inadmin)",
            array(
            ":iduser"=>$this->getiduser(),    
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
            
        ));
        
        $this->setData($results[0]);
        
        
    }
    
    public function delete(){
        
        $sql = new Sql();
        
        $sql->query("CALL sp_users_delete(:iduser)",
                array(
                    ":iduser"=>$this->getiduser()
                    
                ));
        
    }
    
    public static function getForgot($email){
        
        $sql = new Sql();
        $results = $sql->select(
                "SELECT * FROM tb_persons a INNER JOIN tb_users b USING (idperson) WHERE a.desemail = :email;", array (
                    ":email"=>$email
                )
                
                );
        if(count($results)===0){
            
            throw new \Exception("Não foi possivel recuperar a senha!");
            
        }
        
        else {
            
            $data = $results[0];
              $results_sp = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser,:desip)", 
                               
                      array(
                          ":iduser"=>$data["iduser"],
                          "desip"=>$_SERVER["REMOTE_ADDR"]
                          
                      ));
              if(count($results_sp)===0){
                  throw new \Exception ("Não foi possível recuperar a senha!");
              }
              
              else{
                  $dataRecovery = $results_sp[0];
                 $code = base64_encode
                         (
                            mcrypt_encrypt
                                        (
                                            MCRYPT_RIJNDAEL_128,
                                            User::SECRET,
                                            $dataRecovery["idrecovery"],
                                            MCRYPT_MODE_ECB
                                        )
                         );
                 
                 $link = "http://ecommerce/admin/forgot/reset?code=$code";
                 
                 $mailer = new Mailer($data["desemail"], $data["desperson"],
                         "redefnicao de senha da loja",
                         "forgot", 
                         array("name"=>$data["desperson"],"link"=>$link));
                 
                 $mailer->send();//envio do email
                 
                 return $data ;
                  
              } 
        }
        
    }


}

 ?>