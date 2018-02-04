<?php 
session_start();
if (isset($_SESSION["id_utente"]))
	header("Location: calendario.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>login-MyWatchlist</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/index.css" type="text/css" rel="stylesheet" />
		<link href="img/favicon.png" type="image/png" rel="shortcut icon" />
		<link href="css/head.css" type="text/css" rel="stylesheet" />
		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js" ></script>
		<script src="js/index.js" type="text/javascript"></script>
	</head>

	<body>
		<div id="wrapper">
			<div id= "head">
				<div class="head_container">
					<div class="logo"></div>	
				</div>
			</div>	
		<div class="container">
			<div class="help_container">
			<div id="flash"></div>
				<div class="show_demo"></div>
				<div class="user_form">
					<div class="login">
						<div class="formholder">
							<div class="randompad">
					           <fieldset>
					           		<form name="loginForm" action="check.php" method="POST" onsubmit="return login_check_js()">
							            <input id="login_user" name="login_user" type="email" placeholder="Inserisci E-Mail" required />
							            <input id="login_psw" name="login_psw" type="password" placeholder="Inserisci password" required/>
							            <input id="login_button" name="submit" class="button" type="submit" value="Accedi" /> 
							        </form>
					           </fieldset>
					        </div>
					    </div>
					</div>
					<div class="register">
						<div class="formholder">
							<div class="randompad">
					           <fieldset>
						             <div class="imgcontainer">
			    						<img src="img/avatar.png" alt="Avatar" class="avatar">
			 						 </div>
					           		<form name="regForm" onsubmit="return reg_check_js()" action="check.php" method="POST">
			 						    <!-- <input id= "reg_data" type="date" name="bday"> -->
							            <input id="reg_email" name="reg_mail" type="email" placeholder="Inserisci E-mail" required/>
							            <input id="reg_user" name="reg_user" type="text" placeholder="Inserisci Nome Utente" required/>
							            <input id="reg_psw" name="password" type="password" placeholder="Inserisci Password" required/>
							            <input id="reg_button" name="submit" class="button" type="submit" value="Registrati" />
							        </form>
					           </fieldset>
					        </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div id="footer_separator">				
				<div id="w3c">
					<a href="http://validator.w3.org/check/referer">
						<img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/4/w3c-html.png" alt="Valid HTML" />
					</a>
					<a href="http://jigsaw.w3.org/css-validator/check/referer">
						<img src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS" />
					</a>
				</div>
				<div id="tvdb">Thanks to: </div>
			</div>
		</div>
	</body>	
</html>

