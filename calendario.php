<?php 
session_start();
if (!isset($_SESSION["id_utente"])){
	$_SESSION["flash"] = "Accedi per poter visitare il sito";
 	session_write_close();
	header("Location: index.php");
	die();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>MyWatchlist</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="img/favicon.png" type="image/png" rel="shortcut icon" />
		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js"></script>

		
		<link href="css/head.css" type="text/css" rel="stylesheet" />
		<link href="css/scheda_serie.css" type="text/css" rel="stylesheet" />
		<link href="css/scheda_episodio.css" type="text/css" rel="stylesheet" />
		<link href="css/search.css" type="text/css" rel="stylesheet" />
		<link href="css/calendario.css" type="text/css" rel="stylesheet" />
		<link href="css/index.css" type="text/css" rel="stylesheet" />
		<script src="js/calendar.js" type="text/javascript"></script>
		<script src="js/search.js" type="text/javascript"></script>
		<script src="js/infoSerie.js" type="text/javascript"></script>
		<script src="js/infoEpisodio.js" type="text/javascript"></script>
		
	</head>

	<body>
		<div id="wrapper">
			<div id= "head">
				<div class="head_container"> <!-- gli elementi sul menu principale -->
					<div class="logo"></div>
					<div class="center_elements">
    					<div id="calendario"></div>              
						<nav>
							<div id="search_serie">
	            			<input type="submit" class="button" id="button_search" value="Cerca" />              
	            			<input type="text" name="search" id="input_search" placeholder="Cerca Serie Tv">
	            			</div>
						</nav>
					</div>
						<form name="logout" action="check.php" method="POST">
							<div class="user_profile">
								<div id="user_name"><?= $_SESSION["nome_utente"];?></div>
								<div id="user_icon"></div>
								<input name="submit" class="button" type="submit" value="Logout" />
							</div>	
						</form>
				</div>
			</div>	
			<div class="container">
			<div id="flash"></div> <!-- il messaggio di errore a scomparsa -->
				<div class="panel_calendar" id="panel_calendar"> <!-- Pannello calendario -->
					<div class="month"> 
					  	<ul>
						    <div class="prev" id="prev">&#10096;</div>
						    <div class="next" id="next">&#10097;</div>
						    <div class="back" id="back">&#8634;</div>
						    <li id="nome_mese"></li>
						    <li id="anno"></li>
						 </ul>
					</div>

					<ul class="weekdays">
					  <li>Lunedi</li>
					  <li>Martedi</li>
					  <li>Mercoledi</li>
					  <li>Giovedi</li>
					  <li>Venerdi</li>
					  <li>Sabato</li>
					  <li>Domenica</li>
					</ul>

					
					<ul class="days" id="days"></ul>

				</div>

				<div id="panel_episode" class="panel_episode"> <!-- Informazioni sugli episodi -->
					<div class="episode">
						<div id="locandina_episodio" class="locandina_episodio"></div>

						<div class="dati_episodio">
							<h2 id="titolo_serie_ep"></h2>

								<dt >TRAMA:</dt> 
										<dd id="trama_episodio"></dd>  
								<dt>STAGIONI:</dt> 
									<dd id="stagione_ep"></dd>
								<dt>Numero Episodio:</dt> 
									<dd id="num_ep"></dd>
								<dt>MESSA IN ONDA:</dt> 
									<dd id="data_ep"></dd>
						</div>
					</div>
					<input id="close_episode" class="button" type="button" value="Chiudi" >
				</div>

				<div id="panel_search" class="panel_search"> <!-- risultati ricerca -->
					<div id="result">
						<h2>Trovati <span id="counter"></span> risultati<code id="titolo_ricerca"></code></h2>
					</div>
					<div class="box_banner" id="box_banner">	
						<div class="stopfloat"></div>
					</div>

					<div id="pnlDetail" class="PanelDetail">
						<div class="serie" id="serie">
							<div id="titolo_serie"><h2></h2></div>
							<div class="locandina_serie" id="locandina_serie"></div>
							<div class="dati_serie">
								<dt>TRAMA:</dt>
									<dl id="trama_serie"></dl>
								<dt>STAGIONI:</dt>
									<dl id="stagioni_serie"></dl>
								<dt>STATUS:</dt>
									<dl id="status_serie"></dl>
								<dt>GENERE:</dt>
									<dl id="genere_serie"></dl>
								<dt>DURATA EPISODI:</dt>
									<dl id="durata_episodi_serie"></dl>
								<dt>CREATA IL:</dt>
									<dl id="creazione_serie"></dl>
								<dt>NETWORK:</dt>
									<dl id="network_serie"></dl>
								<div id="area_button" class="area_button"> Inserisci Nella Watchlist:
									<div id="check_ita" class="check_ita"></div>
									<input id="add_ita" class="button" type="button" value="Segui in Ita" >
									<div id="check_eng" class="check_eng"></div>
									<input id="add_eng" class="button" type="button" value="Segui in Sub-Ita" >
								</div>
							</div>
						</div>
						<input id="close_detail" class="button" type="button" value="Chiudi" >
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

