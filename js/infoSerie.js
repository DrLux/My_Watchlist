//uniche due var globali di tutto il progetto
var banner;
var lastupdate;

function showDetail(){
	id = this.getAttribute("data-id");
	$('box_banner').style.height = "800px";
   	new Ajax.Request(
		"ajax_tvdb.php",
		{	
			parameters: {request: "info_serie", id: id, translate: "it"},
			method: "GET",
			onSuccess: UpdateDetail
		}
	);
}


function search_num_season(id){
	new Ajax.Request(
		"ajax_tvdb.php",
		{	
			parameters: {request: "num_stagioni", id: id},
			method: "GET",
			onSuccess: print_season
		}
	);
}

function UpdateDetail(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		if (ajax.responseJSON.errors){//se non si trovano i dettagli in italiano li cerca in inglese
			try_eng(ajax.responseJSON.data.id,show_eng);	
		} else {
			var parser = ajax.responseJSON.data;

			banner = parser.banner;
			lastupdate = parser.lastUpdated; 
			
			manage_parser(parser); //mostro le informazioni trovate sulla serie
			create_button_watchlist(parser.id, "ita", parser.status); //permetto l' aggiunta della serie alla watchlist
			try_eng(ajax.responseJSON.data.id,manage_eng); //comunque poi li cerca in inglese ma solo per vedere se sono eventualmente disponibili episodi per seguire in sub ita		
		}
	} else {
		console.log("Problemi con i server tvdb");
	}
}

//se la serie non è presente in italiano la scarica in inglese
function try_eng(id,callback = show_eng){
	$('box_banner').style.height = "800px";
   	new Ajax.Request(
		"ajax_tvdb.php",
		{	
			parameters: {request: "info_serie", id: id, translate: ""},
			method: "GET",
			onSuccess: callback
		}
	);
}

function manage_eng(ajax){ //si limita solo a creare il pulsante per l' aggiunta in inglese
	if (ajax.readyState == 4 && ajax.status == 200) {
		if (!ajax.responseJSON.errors){
			var parser = ajax.responseJSON.data;
			create_button_watchlist(parser.id, "eng", parser.status);	
		}
	} else {
		console.log("Problemi con i server tvdb");
	}
}

function show_eng(ajax){ //compila tutti i campi in lingua inglese
	if (ajax.readyState == 4 && ajax.status == 200) {
		if (!ajax.responseJSON.errors){
			var parser = ajax.responseJSON.data;

			banner = parser.banner;
			lastupdate = parser.lastUpdated; 

			manage_parser(parser); 	
			$('flash').innerHTML = "Non disponibile in Italiano";
			$('flash').style.display = "block";
			$('flash').fade({ duration: 6.0});
			create_button_watchlist(parser.id,"eng", parser.status);
		} 
	} else {
		console.log("Problemi con i server tvdb");
	}
}

function manage_parser(parser) {
	info_tvs(parser.id, parser.seriesName, parser.overview, parser.status, parser.runtime, parser.firstAired, parser.network);
	search_num_season(parser.id);
	parser.genre.forEach(print_genre);
	var str = $('genere_serie').innerHTML;
	hide_banner();
	$('pnlDetail').style.display = "block";
	$("close_detail").observe("click", HideDetail);
	$('genere_serie').innerHTML = str.slice(0,-1); //rimuove l' ultimo trattino - 	
}

function info_tvs(idserie, titolo, trama, status, durata, data_inizio,network){
	image_path = "http://thetvdb.com/banners/posters/"+idserie+"-1.jpg"
	var data = new Date(data_inizio);
	var options = { year: 'numeric', month: 'long', day: 'numeric' };

		$('locandina_serie').setStyle({backgroundImage: 'url(' + image_path + ')'});
		$('titolo_serie').innerHTML = titolo;
		$('trama_serie').innerHTML = trama;
		$('stagioni_serie').innerHTML = "";
		$('genere_serie').innerHTML = "";
		$('durata_episodi_serie').innerHTML = durata;
		$('creazione_serie').innerHTML = data.toLocaleString('it-IT',options);
		$('network_serie').innerHTML = network;
		$('status_serie').innerHTML = status;	
}

//Parla e printa in modo ordinato la lista di genere a cui la serie appartiene
function print_genre(generi){
	$('genere_serie').innerHTML += " " + generi + " -";
}

//la stagione va cercata in una chiamata a parte
function print_season(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		var parser = ajax.responseJSON.data;
		$('stagioni_serie').innerHTML = parser.airedSeasons.length - 1;
	} else {
		console.log("Problemi con i server tvdb");
	}
}


function HideDetail(){
	$('pnlDetail').style.display = "none";
	$('result').style.display = "block";
	$('box_banner').style.display = "block";
	$('box_banner').style.height = "auto";
	$('add_ita').style.display = "none";
	$('add_eng').style.display = "none";
	$('area_button').style.display = "none";
	$('check_ita').style.display = "none";
	$('check_eng').style.display = "none";

}

function create_button_watchlist(id, language, status){
	if (status != "Ended"){
		$('area_button').style.display = "block";
		$('add_'+language).style.display = "block";
		$('add_'+language).setAttribute("data-lng", language);
		$('add_'+language).setAttribute("data-id", id);
		$('add_'+language).observe('click', add_to_watchlist);
		
		new Ajax.Request(
		"ajax_db.php",
		{	
			parameters: {request: "series_in_watchlist", id_serie: id, lingua: language},
			method: "GET",
			onSuccess: update_button_watchlist
		});
	} 
}

//serve per sapere quali pulsanti si possono generare. (solo ita o anche eng)
function update_button_watchlist(ajax){ 
	if (ajax.readyState == 4 && ajax.status == 200) {
		var parser = ajax.responseJSON;
		parser = parser["0"];
		if (parser)
			$('check_'+parser.wl_lingua).style.display = "block";
	} else {
		console.log("update_button_watchlist"+ajax);
	}
}

function add_to_watchlist(){
	var id = this.getAttribute("data-id");
	var lingua = this.getAttribute("data-lng");
	var nome_serie = $('titolo_serie').innerHTML;
	var stagioni = $('stagioni_serie').innerHTML;
	var status = $('status_serie').innerHTML;

	new Ajax.Request(
		"ajax_db.php",
		{	
			parameters: {request: "manage_watchlist", id_serie: id, lingua: lingua, stagioni: stagioni, banner: banner, lastupdate: lastupdate, nome_serie: nome_serie, status: status},
			method: "GET",
			onSuccess: feedback_added_watchlist
		});
}

function feedback_added_watchlist(ajax){ //se l' inserimento va a buon file aggiunge il check, altrimenti segnala problemi nell' inserimento in watchlist
	if (ajax.readyState == 4 && ajax.status == 200) {
		var parser = ajax.responseJSON;
		if (parser)
			$('check_'+parser.lingua).style.display = parser.style;
		else {
			$('flash').innerHTML = "Errore durante l' inserimento in Watchlist";
			$('flash').style.display = "block";
			$('flash').fade({ duration: 3.0});
		}
	} else {
		console.log("Errore durante l' aggiunta nella Watchlist");
	}
}