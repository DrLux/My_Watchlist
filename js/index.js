window.onload = function() {
	$("tvdb").observe("click", redirect_tvdb);
	check_flash();
}

//controllo che viene fatto prima in inviare l' input utente al gestore di login
function login_check_js(){
	htmlEscape($("login_user").value);
	htmlEscape($("login_psw").value);
	return true;
}


function reg_check_js(){
	htmlEscape($("reg_user").value);
	htmlEscape($("reg_psw").value);
	htmlEscape($("reg_email").value);
	return true;
}

//Controlla se ci sono errori da mostrare all' utente
function check_flash(){
	new Ajax.Request(
		"check.php",
		{	
			parameters: {request: "check_flash"},
			method: "POST",
			onSuccess: update_flash
		}
	);
}

//aggionra il banner rosso che segnala la presenza di errori
function update_flash(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		var parser = ajax.responseText;
		if (parser != ""){
			$('flash').innerHTML = parser;
			$('flash').style.display = "block";
			$('flash').fade({ duration: 5.0});
		}
	}
}

//Controlli di sicurezza lato JS sull' input utente per il login
function htmlEscape(str) {
    return String(str)
            .replace(/&/g, '')
            .replace(/"/g, '')
            .replace(/'/g, '') 
            .replace(/</g, '')
            .replace(/>/g, '')
            .replace(/;/g, '');
}

function redirect_tvdb(){
	window.location.href = "http://thetvdb.com/";
}
