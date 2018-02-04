function start_search() {
	if ($('input_search').value){
		$('panel_calendar').style.display = "none";
		var title = htmlEscape(encodeURIComponent($('input_search').value)); //sanifico l' input contro xss
		$('titolo_ricerca').setAttribute("data-title", title);
		clean_box_banner();
		HideDetail();
		search_tvs(title);
	}
}

function search_tvs(title){
	new Ajax.Request(
		"ajax_tvdb.php",
		{	
			parameters: {request: "search", title: title, translate: "it"},
			method: "GET",
			onSuccess: show_banner
		}
	);
}

function show_banner(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		if (ajax.responseJSON){
			var parser = ajax.responseJSON.data;
			$('counter').innerHTML = parser.length;
			parser.forEach(function (item, index) {
			    obj_tvs_toHtml("it", item, index)
			});
		} else {
			try_search_eng();
		}
	} else {
		console.log("Problemi con i server tvdb");
	}
}

//se la prima ricerca fallisce ne tenta una seconda in inglese
function try_search_eng(){
	new Ajax.Request(
		"ajax_tvdb.php",
		{	
			parameters: {request: "search", title: $('titolo_ricerca').getAttribute("data-title"), translate: "en"},
			method: "GET",
			onSuccess: second_search
		}
	);
}

function second_search(ajax){
	if (ajax.readyState == 4 && ajax.status == 200) {
		if (ajax.responseJSON){
			var parser = ajax.responseJSON.data;
			$('counter').innerHTML = parser.length;
			parser.forEach(function (item, index) {
			    obj_tvs_toHtml("en", item, index)
			});
		} else {
			$('counter').innerHTML = 0;
		}
	} else {
		console.log("Problemi con i server tvdb");
	}
}

function obj_tvs_toHtml(language, serie, index){
	create_banner(language, serie.banner, serie.seriesName, serie.network, serie.firstAired, serie.status, serie.id );
}


function create_banner( language, src_img, title, pub, anno, status, id){
	var data = new Date(anno);
	var options = { year: 'numeric', month: 'long', day: 'numeric' };

		if (src_img)//se esiste uso il loro banner, altrimenti carico quello di default
			src_img = "http://thetvdb.com/banners/"+src_img;
		else
			src_img = "img/default_banner.jpg"

		var redirect = document.createElement("a");
		var new_banner = document.createElement("div");
		var new_img_banner = document.createElement("img");
		var new_title_banner = document.createElement("dt");
		var new_pub_banner = document.createElement("p");
		var new_trasm_banner = document.createElement("p");
		var new_status_banner = document.createElement("p");

		new_banner.setAttribute('class', 'banner');

		new_img_banner.setAttribute('class', 'img_banner');
		new_img_banner.setAttribute('title', 'img_banner');
		new_img_banner.setAttribute('alt', 'img_banner');
		new_img_banner.setAttribute('src', src_img);

		new_title_banner.innerHTML = title;

		new_pub_banner.innerHTML = "Publisher: "+pub;
		new_trasm_banner.innerHTML = "Anno di uscita: "+data.toLocaleString('it-IT',options);
		new_status_banner.innerHTML = "status: "+status;

		if (language == "it"){
			new_banner.observe('click', showDetail);
		}
		else{ //se la serie si trova solo a seguito di una ricerca in inglese, sicuramente non sarà disponibile in italia
			new_banner.observe('click', function(event){
				try_eng(id); //lancio solo la ricerca in lingua inglese
			});

			var bandiera = document.createElement("img"); //segnalo subito visivamente con una bandierina
			bandiera.setAttribute('class', 'lang_banner');
			bandiera.setAttribute('src', 'img/icon_eng.png');
			new_banner.appendChild(bandiera);
		}

		new_banner.appendChild(new_img_banner);
		new_banner.appendChild(new_title_banner);
		new_banner.appendChild(new_pub_banner);
		new_banner.appendChild(new_trasm_banner);
		new_banner.appendChild(new_status_banner);
		new_banner.setAttribute("data-id", id);

		$('box_banner').insert(new_banner);
}

//encodeURIComponent fa passare - _ . ! ~ * ' ( )
function htmlEscape(str) {
    return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            //.replace(/'/g, '&#39;') necessario ai fini della ricerca. Sanificheranno loro poi lato db
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
}

//rimuove tutti i banner creati
function clean_box_banner(){
	while ($('box_banner').firstChild) {
    	$('box_banner').removeChild($('box_banner').firstChild);
	}
}

function hide_banner(){
	$('box_banner').style.display = "none";
	$('result').style.display = "none";
}