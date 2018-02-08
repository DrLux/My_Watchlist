<?php 

function get_db(){
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "tvsd";
  $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

  //Verifico la possibilità di connessione al DB
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
  }

  //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  return $db;
}

//prepara l' header per la chiamata eserna al sito, nella lingua passata come parametro
function get_opt($lingua_risultati){
  header("Content-type: application/json");
  
  $opts = array(
          'http'=>array(
          'method'=>"GET",
          'header'=> "accept-language:".$lingua_risultati."en;q=0.8\r\n".
                 "content-type: application/json\r\n" .
                "accept-encoding:gzip,deflate, sdchrn\r\n" .
                 "Content-Type: application/json\r\n" .
                 "scheme:https\r\n".
                 "dnt:1\r\n".
                 "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\r\n".
                 "Content-type: application/x-www-form-urlencoded\r\n".
                 "authorization:Bearer ".$_SESSION["token"]."\r\n" .
                 "Cookie: foo=bar\r\n"
        )
      );
   return $opts;
}

function do_estern_call($opt,$url){
  $stream = stream_context_create($opt);
  $content = file_get_contents($url, true, $stream);

  foreach($http_response_header as $c => $h) {
        if(stristr($h, 'content-encoding') and stristr($h, 'gzip'))        {
            //decomprime i dati ricevuti
            $content = gzinflate( substr($content,10,-8) );
        }
    }
  return $content;
}

//il token va richiesto con una chiamata diretta in curl.
function get_new_token(){
  //$data = MY Credentials  
  $data_string = json_encode($data); 


  $ch = curl_init('https://api.thetvdb.com/login'); 
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',  
                                              'Content-Length: '.strlen($data_string)) );
  $response = curl_exec($ch);
  curl_close($ch);
  $response = json_decode($response);
  return $response->token;
}

?>