function search_episode(){
	var lang = this.getAttribute("data-lang");
	var id = this.getAttribute("data-id_serie");
	var stagione = this.getAttribute("data-stagione");
	var puntata = this.getAttribute("data-num_puntata");
	get_episode(id,stagione,puntata,lang);
}


function get_episode(id,stagione,puntata,lang){
	new Ajax.Request(
		"ajax_db.php",
		{	
			parameters: {request: "get_episode", id_serie: id, lingua: lang,  puntata: puntata, stagione: stagione},
			method: "GET",
			onSuccess: manage_ep
		}
	);
}

function manage_ep(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		var parser = ajax.responseJSON.episodio;
		show_ep(); //mostra il pannello con i dati dell' episodio
		print_ep(parser.nome_serie, parser.trama, parser.stagione, parser.num_puntata, parser.data, parser.punt_id_serie, parser.id_img_puntata);
		if (ajax.responseJSON.errore != ""){ //In caso di errore mostra un banner di segnalazione
			$('flash').innerHTML = "Traduzione italiana dell' anteprima non ancora disponibile";
			$('flash').style.display = "block";
			$('flash').fade({ duration: 5.0});
		}
	} else {
		console.log("Problemi di accesso al db");
	}
}

//Mostra i dati dell' episodio a schermo
function print_ep(nome_serie, trama, stagione, num_puntata, data, punt_id_serie, id_img_puntata){
	var img = "http://thetvdb.com/banners/episodes/"+punt_id_serie+"/"+id_img_puntata+".jpg";
	var data = new Date(data);
	var options = { year: 'numeric', month: 'long', day: 'numeric' };

	$('data_ep').innerHTML = data.toLocaleString('it-IT',options);
	$('num_ep').innerHTML = num_puntata;
	$('stagione_ep').innerHTML = stagione;
	$('trama_episodio').innerHTML = trama;
	$('titolo_serie_ep').innerHTML = nome_serie;
	$('locandina_episodio').setStyle({backgroundImage: 'url(' + img + ')'});
}

function show_ep(){
	$('panel_episode').style.display = "block";
	$('close_episode').observe("click", hide_episode);
}

function hide_episode(){
	$('panel_episode').style.display = "none";
}