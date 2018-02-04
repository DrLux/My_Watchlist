window.onload = function() { //primissimo script
	init_calendar(); //richiede i dati del calendario in forma standard
	$("next").observe("click", next_month);
	$("prev").observe("click", prev_month);
	$("back").observe("click", init_calendar);
	$('button_search').observe("click", start_search);
	$('input_search').addEventListener('keypress', function (e) { //serve per avviare la ricerca alla pressione di enter
    	var key = e.which || e.keyCode;
    	if (key === 13) 
    		start_search();
    });
	$('calendario').observe("click", hide_all);
	$("tvdb").observe("click", redirect_tvdb);
}

function prev_month(){
	new Ajax.Request(
		"ajax_calendar.php",
		{	
			parameters: {calendar_function: "prev_month", month: $('nome_mese').innerHTML, year: $('anno').innerHTML},
			method: "POST",
			onSuccess: show_calendar
		}
	);
}

function next_month(){
	new Ajax.Request(
		"ajax_calendar.php",
		{	
			parameters: {calendar_function: "next_month", month: $('nome_mese').innerHTML, year: $('anno').innerHTML},
			method: "POST",
			onSuccess: show_calendar
		}
	);
}

function init_calendar(){
	new Ajax.Request(
		"ajax_calendar.php",
		{	
			parameters: {calendar_function: "", month: "", year: ""},
			method: "POST",
			onSuccess: show_calendar
		}
	);
}


function show_calendar(ajax){
	var parser = ajax.responseJSON;
	var today = parser.today;
	var day_in_month = parser.day_in_month;
	var name_month = parser.name_month;
	var year = parser.year;
	var start_day = parser.start_day;
	var episodi = parser.episodi;
	var num_ep = parser.num_episodi;
	clean_calendar();

	//var giorno;
	var bannerino;

	$('nome_mese').innerHTML = name_month;
	$('anno').innerHTML = year;
	var j = 0; //indice dell' array degli episodi

	for (var x = start_day; x >1 ; x-- )
		$('days').insert("<li class='hide'></li>"); //serve per far iniziare il mese nel giusto giorno della settimana


	for (var i = 1; i <= day_in_month; i++ ){
		var giorno = document.createElement("li");
		if (i == today){
			giorno.setAttribute('class', 'today'); //segnala quale giorno corrisponde a quello odierno
		}
		giorno.innerHTML = i;


		while ( j < num_ep && i == episodi[j].event_day){ //crea i bannerini, non uso il create element per evitare memory leak (non disalloca completamente i dati)
			bannerino = '<div id="b'+j+'" class="bannerino" ';
			bannerino += 'data-id_serie= "'+episodi[j].punt_id_serie+'"';
			bannerino += 'data-stagione="'+episodi[j].stagione+'"';
			bannerino += 'data-num_puntata="'+episodi[j].num_puntata+'"';
			bannerino += 'data-lang="'+episodi[j].punt_lingua+'"';
			bannerino += ' ></div>'
			giorno.innerHTML += bannerino; 
			j++;
		}
						
		$('days').insert(giorno);
	}

	for (var x = 0; x <num_ep; x++){ //in un ciclo a parte aggiungo poi le informazioni sui bannerini. Si poteva evitare con il create element
		document.getElementById("b"+x).observe("click", search_episode);
		$('b'+x).setStyle({backgroundImage: 'url(http://thetvdb.com/banners/'+episodi[x].banner_img+')'});
		$('b'+x).innerHTML += '<img src="img/icon_'+episodi[x].punt_lingua+'.png">';
	}
	
}

function clean_calendar(){ //rimuove tutti gli elementi del calendario
	while( $('days').firstChild ){
 		$('days').removeChild( $('days').firstChild );
	}
}

function hide_all() { //nasconde tutti i div lasciando visibile solo il calendario
	$('pnlDetail').style.display = "none";
	$('panel_calendar').style.display = "block";
	clean_box_banner();
	hide_banner();
	$('box_banner').style.height = "auto";
	init_calendar();
	hide_episode();
}

function redirect_tvdb(){ //redirect su tvdb quando si clicca sul banner
	window.location.href = "http://thetvdb.com/";
}
