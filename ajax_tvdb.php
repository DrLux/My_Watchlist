<?php
session_start();
if (!isset($_SESSION["id_utente"])){
  $_SESSION["flash"] = "Accedi per poter visitare il sito";
  session_write_close();
  header("Location: index.php");
  die();
}

include 'common.php';
//questa pagina fa chiamate al database esterno 


$lingua_risultati = "";
$url = '';

if(isset($_GET["request"])){

  if ($_GET["request"] == "episode"){
    if ($_GET["translate"] == "it")
      $lingua_risultati = "it-IT".",";
    $id = $_GET["id"];
    $abs_ep = $_GET["abs"];
    $url  = 'https://api.thetvdb.com/series/'.$id.'/episodes/query?absoluteNumber='.$abs_ep;
  }

  if ($_GET["request"] == "search"){
    if ($_GET["translate"] == "it")
      $lingua_risultati = "it-IT".",";
    $title = $_GET["title"];
    $url  = 'https://api.thetvdb.com/search/series?name='.$title;
  }

  if ($_GET["request"] == "info_serie"){
    if ($_GET["translate"] == "it")
      $lingua_risultati = "it-IT".",";
    $id = urlencode($_GET["id"]);
    $url  = 'https://api.thetvdb.com/series/'.$id;
  }

  if ($_GET["request"] == "num_stagioni"){
    $id = urlencode($_GET["id"]);
    $url  = 'https://api.thetvdb.com/series/'.$id.'/episodes/summary';
  }


    $opt = get_opt($lingua_risultati);
    $content = do_estern_call($opt,$url);
    echo $content;
} else {
  header("HTTP/1.1 400 Invalid Request");
  die("ERROR 400: Invalid request - This service accepts only ajax requests.");
}
?>