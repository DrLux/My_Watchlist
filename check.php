<?php
session_start();

include 'common.php';

if (isset($_POST["request"]) && $_POST["request"] == "check_flash"){
	if (isset($_SESSION["flash"]))
	 		echo $_SESSION["flash"];
}

//Questa funzione si occupa del controllo del login degli utenti
if (isset($_POST["submit"])){

	if ($_POST["submit"] == "Accedi"){
		accedi($_POST["login_user"], $_POST["login_psw"], "Le credenziali inserite sono errate");
	}


	if ($_POST["submit"] == "Registrati"){
		$db = get_db();	
		$check_user = check_user($_POST["reg_user"]);	

		if ($check_user){
			error_to_index("Utente gia presente");
		} else {	
			$check_email = check_email($_POST["reg_mail"]);
			if ($check_email){
				error_to_index("Mail gia presente");
			} else {
				$insert_user = insert_user($_POST["reg_user"] , $_POST["reg_mail"], $_POST["password"]);

				if ($insert_user)
					accedi($_POST["reg_mail"], $_POST["password"], "Errore durante l' inserimento dell' utente");
			}
		}
	}

	if ($_POST["submit"] == "Logout"){
		session_destroy();
		session_regenerate_id(TRUE); 
		header("Location: index.php");
		die();
	}

}

	function accedi($mail, $pass, $msg_error){
		$ret = check_login($mail, $pass);
		if ($ret){
			entra_nel_sito($ret["username"],$ret["id_utente"]); 
		} else{
			error_to_index($msg_error);
	 	}
	}


	function check_login($mail, $password){
		$db = get_db();
		$query = $db->prepare("SELECT * 																						FROM `utente` 																					WHERE email like :mail and password like :password;");

		$password = md5($password);
		$query->bindParam(":mail", $mail);
		$query->bindParam(":password", $password);
		$query->execute();
		$query = $query->fetch();

		return $query;
	}

	function check_user($user){
		$db = get_db();

		$check_user = $db->prepare("SELECT * FROM `utente` WHERE username like :user; ");

		$check_user->bindParam(":user", $user);
		$check_user->execute();
		$check_user = $check_user->fetch();
		return $check_user;
	}

	function check_email($email){
		$db = get_db();

		$check_email = $db->prepare("SELECT * FROM `utente` WHERE email like :email; ");

		$check_email->bindParam(":email", $email);
		$check_email->execute();
		$check_email = $check_email->fetch();
		return $check_email;
	}

	function insert_user($user, $mail, $password){
		$db = get_db();

		$query = $db->prepare("INSERT INTO `utente` (`id_utente`, `email`, `username`, `password`) VALUES (NULL, :email, :user, :password);");

		$password = md5($password);

		$query->bindParam(":email", $mail);
		$query->bindParam(":user", $user);
		$query->bindParam(":password", $password);
		$query->execute();
		return $query;
	}

	function entra_nel_sito($username, $id_utente){
		$_SESSION["id_utente"] = $id_utente;
		$_SESSION["nome_utente"] = $username;
		$_SESSION["flash"] = "";
		$_SESSION["token"] = get_new_token();
		header("Location: calendario.php");
	}

	function error_to_index($error){
		$_SESSION["flash"] = $error;
		session_write_close();
		header("Location: index.php");
 		die;
	}
?>