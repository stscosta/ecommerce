<?php 

namespace Hcode\Model;

use \Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Mailer;

class User extends Model 
{
	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const SECRET_IV = "HcodePhp7_Secret_IV";
	//const SECRET = "hcodephp7_secret";
    
	protected $fields = [
		"iduser", 
		"idperson", 
		"deslogin", 
		"despassword", 
		"inadmin", 
		"dtergister",
		"desperson", 
		"desemail", 
		"nrphone"
	];
    
	public static function login($login, $password):User
	{
		$db = new Sql();
		$results = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));
		if (count($results) === 0) {
			throw new \Exception("Não foi possível fazer login.");
		}
		$data = $results[0];
		if (password_verify($password, $data["despassword"]) === true) 
		{
			$user = new User();
			$user->setData($data);
			$_SESSION[User::SESSION] = $user->getValues();
			return $user;
		} else {
			throw new \Exception("Não foi possível fazer login.");
		}

	}

	public static function verifyLogin($inadmin = true)
	{
		if (
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		) {
			header("Location: /admin/login");
			exit;
		}
	}
    
    public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}

	// 	Classe para listar os usuários do BD
	public static function listAll()
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
	}
	// Salvar os dados do BD 
	public function save()
	{
		$sql = new Sql();
		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
				":desperson"=>$this->getdesperson(),
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()
		));
		$this->setData($results[0]);
	}
	// Uptade no BD
	public function get($iduser)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
			":iduser"=>$iduser
		));
		$this->setData($results[0]);
	} 
    
	public function update()
	{
		$sql = new Sql();
		$results = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
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
	public function delete()
	{
		$sql = new Sql();
		$sql->query("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()	
		));
	}

    //Classes para esqueci a senha

	public static function getForgot($email)
	{
		$sql = new Sql();
		$results = $sql-> select("
			SELECT * 
			FROM tb_persons a
			INNER JOIN tb_users b USING(idperson)
			WHERE desemail = :email;
			", array(
				":email"=>$email
			));

		if(count($results) === 0)
		{                       //NÃO EXISTE NO BD O email     
			throw new \Exception("Não foi possivel recuperar a senha");
			
		} 
		else 
	 	{                    // Agora verificamos a senha:
	 		$data = $results[0];
	 		
	 		$resultsRecovery = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser,:desip)", array(
	 			":iduser"=>$data["iduser"],
	 			":desip"=>$_SERVER["REMOTE_ADDR"]
	 		));
	 		
	 		if(count($resultsRecovery) === 0)
	 		{
	 			throw new \Exception("Não foi possível recuperar");
	 	 	}
	 	 	else
	 	 	{
	 	 		$dataRecovery = $resultsRecovery[0];
	 	 		//$code = $dataRecovery["idrecovery"];
	 	 		$code = openssl_encrypt($dataRecovery['idrecovery'], 
	 	 			'AES-128-CBC', 
	 	 			pack("a16", User::SECRET), 
	 	 			0, 
	 	 			pack("a16", User::SECRET_IV));

				$code = base64_encode($code);

				/*	
	 	 		define('SECRET_IV', pack('a16', 'senha'));
				define('SECRET', pack('a16', 'senha'));
	 	 		$code = openssl_encrypt(
				json_encode($dataRecovery["idrecovery"]),
				"AES-128-CBC", 
				'SECRET',
				0,
				SECRET_IV
				);
	 	 		*/
	 	 		/*if ($inadmin === true) {
						$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";
					} else {
						$link = "http://www.hcodecommerce.com.br/forgot/reset?code=$code";
				}*/				

	 	 		$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";
	 	 		
	 	 		$mailer = new Mailer($data["desemail"], $data["desperson"],"Redefinir Senha Hcode", "forgot", array(
	 	 			"name"=>$data["desperson"],
	 	 			"link"=>$link	
	 	 		));
	 	 		$mailer->send();
	 	 		return $link;
	 	 		//var_dump($link);
	 	 	}
	 	}
	}

	public static function validForgotDecrypt($code)
	{
		$code = base64_decode($code);
		$idrecovery = openssl_decrypt(
			$code, 
			'AES-128-CBC', 
			pack("a16", User::SECRET), 
			0, 
			pack("a16", User::SECRET_IV)
		);

		$sql = new Sql();
		$results = $sql->select("
			SELECT *
			FROM tb_userspasswordsrecoveries a
			INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE
				a.idrecovery = :idrecovery
				AND
				a.dtrecovery IS NULL
				AND
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
		", array(
			":idrecovery"=>$idrecovery
		));

		if (count($results) === 0)
		{
			throw new \Exception("Não foi possível recuperar a senha.");
		}
		else
		{
			return $results[0];
		}
	}

	public static function setFogotUsed($idrecovery)
	{
		$sql = new Sql();
		$sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
			":idrecovery"=>$idrecovery
		));
	}

	public function setPassword($password)
	{
		$sql = new Sql();
		$sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
			":password"=>$password,
			":iduser"=>$this->getiduser()
		));
	}

	


}
?>
