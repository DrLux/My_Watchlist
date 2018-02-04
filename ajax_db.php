<?php 
session_start();
if (!isset($_SESSION["id_utente"])){
  $_SESSION["flash"] = "Accedi per poter visitare il sito";
  session_write_close();
  header("Location: index.php");
  die();
}

include 'common.php';
header('Content-Type: application/json');

	/*Catturo le eccezioni*/
	try {
		$db = get_db(); //funzione che istanzia l' oggetto db che comunica col vero db
		$stmt = null;

		if (isset($_GET["request"])){ //elimina gli acceddi diretti dell' utente alla pagina
			$stmt = makeQuery($db);
		} else {
			header("HTTP/1.1 400 Invalid Request");
    		die("ERROR 400: Invalid request - This service accepts only ajax requests.");
		}	
		
		if ($stmt != null){ //se la query è andata a buon fine
	 		$result = $stmt->fetchAll();
	 		echo json_encode($result,JSON_FORCE_OBJECT);
	 	}
 		
	} catch (PDOException $ex) { //se qualcosa va storto catturo l'errore
		$db = null; 	
		printf("Error details: ".$ex->getMessage());
	}



function makeQuery($db){

	if ($_GET["request"] == "get_episode")
		return get_episode($db);
	
	if ($_GET["request"] == "get_watchlist")
		return watchlist($db);

	if ($_GET["request"] == "cleanDB")
		return cleanDB($db);

	if ($_GET["request"] == "remove_from_watchlist")
		return remove_from_watchlist($db);

	if ($_GET["request"] == "series_in_watchlist")
		return series_in_watchlist($db);

	if ($_GET["request"] == "manage_watchlist")
		return manage_watchlist($_GET["id_serie"], $_GET["lingua"], $_GET["banner"], date('Y-m-d', $_GET["lastupdate"]), $_GET["status"], $_GET["nome_serie"], $_GET["stagioni"], $db);

	if ($_GET["request"] == "updateDB")
		return updateDB($db);
}



function get_episode($db){
	$lingua = $_GET["lingua"];
	$error = "";
	$query = $db->prepare("SELECT `punt_id_serie`, `stagione`, `num_puntata`, `punt_lingua`, `data`, `trama`, `id_img_puntata`, nome_serie 
						from puntata inner join serie on punt_id_serie = id_serie and punt_lingua = serie_lingua 
						where punt_id_serie = :id_serie and punt_lingua = :lingua and num_puntata = :num_puntata and stagione = :stagione; ");

	$query->bindParam(":id_serie", $_GET["id_serie"]);
	$query->bindParam(":lingua",$lingua);
	$query->bindParam(":num_puntata", $_GET["puntata"]);
	$query->bindParam(":stagione", $_GET["stagione"]);
	$query->execute();
	$result = $query->fetch();

	if ($result["trama"] == null){//se non sono presenti ancora presenti trame in italiano le cerca in inglese
		$lingua = "eng";	
		$query->bindParam(":lingua", $lingua);
		$query->execute();
		$temp_res = $query->fetch();
		$result["trama"] = $temp_res["trama"];
		$error = "Non disponibile in lingua italiana";
	}

	$result = array('episodio'=>$result,'errore'=>$error);
	echo json_encode($result,JSON_FORCE_OBJECT);
	return null;
}

function get_watchlist($db){
	$query = $db->prepare("SELECT * FROM `watchlist` WHERE wl_id_utente = :id_utente; ");

	$query->bindParam(":id_serie", $_SESSION["id_utente"]);
	$query->execute();
	return $query;
}


function cleanDB($db){
	$query = $db->prepare("DELETE FROM `serie` WHERE status='ended';");
	$query->execute();
	return $query;
}

function remove_from_watchlist($db, $wl_id_serie, $wl_lingua){
	$query = $db->prepare("DELETE FROM `watchlist` 
							WHERE wl_id_utente = :id_utente AND wl_id_serie = :id_serie AND wl_lingua = :serie_lingua;");

	$query->bindParam(":id_utente", $_SESSION["id_utente"]);
	$query->bindParam(":id_serie", $wl_id_serie);
	$query->bindParam(":serie_lingua", $wl_lingua);
	$query->execute();
	return $query;
}

function series_in_watchlist($db){
	$query = $db->prepare("SELECT * FROM `watchlist` WHERE wl_id_utente = :id_utente and wl_id_serie = :id_serie and wl_lingua = :lingua;");

	$query->bindParam(":id_utente", $_SESSION["id_utente"]);
	$query->bindParam(":id_serie", $_GET["id_serie"]);
	$query->bindParam(":lingua", $_GET["lingua"]);
	$query->execute();
 		
	return $query;
}


function manage_watchlist($id_serie, $serie_lingua, $banner_img,$ultima_mod,$status,$nome_serie, $stagioni_serie, $db){
	$query = check_serie($id_serie, $serie_lingua, $db);
	$result = $query->fetch();
	$return = null;

	if ($result == null){
		//se la serie non è già presente la creo
		$return = insert_serie($id_serie, $serie_lingua, $banner_img, $ultima_mod, $status, $nome_serie, $stagioni_serie, $db);
		insert_episode($id_serie, $stagioni_serie, $serie_lingua, $db);		
		add_to_watchlist($id_serie, $serie_lingua, $db);
		$return = array('lingua'=>$serie_lingua,'presente'=>1,'style'=>'block');
	} else {
		$gia_presente = series_in_watchlist($db);
		if ($gia_presente && $gia_presente->fetch()){
			remove_from_watchlist($db, $id_serie, $serie_lingua);
			$return = array('lingua'=>$serie_lingua,'presente'=>0,'style'=>'none');			
		} else {
			add_to_watchlist($id_serie, $serie_lingua, $db);
			$return = array('lingua'=>$serie_lingua,'presente'=>1,'style'=>'block');
		}
	} 

	if ($return)
 		echo json_encode($return,JSON_FORCE_OBJECT);

	return null;
}

function insert_serie($id_serie, $serie_lingua, $banner_img, $ultima_mod, $status, $nome_serie, $stagioni_serie, $db){
	$insert_serie = $db->prepare("INSERT INTO `serie` (`id_serie`, `serie_lingua`, `banner_img`, `ultima_mod`, `status`, `nome_serie`, `stagioni`) VALUES (:id_serie, :serie_lingua, :banner_img, :ultima_mod, :status, :nome_serie, :stagioni);");

	$insert_serie->bindParam(":id_serie", $id_serie);
	$insert_serie->bindParam(":serie_lingua", $serie_lingua);
	$insert_serie->bindParam(":banner_img", $banner_img);
	$insert_serie->bindParam(":ultima_mod", $ultima_mod);
	$insert_serie->bindParam(":status", $status);
	$insert_serie->bindParam(":nome_serie", $nome_serie);
	$insert_serie->bindParam(":stagioni", $stagioni_serie);
	$insert_serie->execute(); 
	return $insert_serie;
}

function insert_episode($id_serie, $stagioni_serie, $serie_lingua, $db){

	$lingua_risultati =""; 
	$url = "https://api.thetvdb.com/series/".$id_serie."/episodes/query?airedSeason=".$stagioni_serie;

	if ($serie_lingua == "ita")
		$lingua_risultati = "it-IT".",";
	
	$opt = get_opt($lingua_risultati);
		$content = json_decode(do_estern_call($opt,$url), true); //scarico tutti gli episodi da tvdb
		$content = $content["data"];

		//inserisco tutti gli episodi nel db
	foreach ($content as $key => $episode) {
		$add_episode = $db->prepare("INSERT INTO `puntata` (`punt_id_serie`, `stagione`, `num_puntata`, `punt_lingua`, `data`, `trama`, `id_img_puntata`) VALUES (:punt_id_serie, :stagione, :num_puntata, :punt_lingua, :data, :trama, :id_img_puntata);");

		$add_episode->bindValue(":punt_id_serie", $id_serie);
		$add_episode->bindValue(":punt_lingua", $serie_lingua);
		$add_episode->bindParam(":stagione", $episode["airedSeason"]);
		$add_episode->bindParam(":num_puntata", $episode["airedEpisodeNumber"]);
		$add_episode->bindParam(":data", $episode["firstAired"]);
		$add_episode->bindParam(":trama", $episode['overview']);
		$add_episode->bindParam(":id_img_puntata", $episode['id']);
		$add_episode->execute();
	}
	return $add_episode;
}

function check_serie($id_serie, $serie_lingua, $db){
	$query = $db->prepare("SELECT * FROM serie WHERE id_serie = :id_serie and serie_lingua = :serie_lingua;");

	$query->bindParam(":id_serie", $id_serie);
	$query->bindParam(":serie_lingua", $serie_lingua);
	$query->execute();
	return $query;
}

function add_to_watchlist($id_serie, $serie_lingua, $db){
	//aggiungo la serie nella watchlist
	$second_query = $db->prepare("INSERT INTO `watchlist` (`wl_id_utente`, `wl_id_serie`, `wl_lingua`, `data_aggiunta`) VALUES (:wl_id_utente, :wl_id_serie, :wl_lingua, :data_aggiunta);");

	$data_aggiunta = date("Y-m-d");

	$second_query->bindParam(":wl_id_utente", $_SESSION["id_utente"]);
	$second_query->bindParam(":data_aggiunta", $data_aggiunta);
	$second_query->bindParam(":wl_id_serie", $id_serie);
	$second_query->bindParam(":wl_lingua", $serie_lingua);
	$second_query->execute();
	return $second_query;
}
?>
	