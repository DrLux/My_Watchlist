<?php
session_start();
//Blocca l' accesso agli utenti che non anno effettuato il login
if (!isset($_SESSION["id_utente"])){
  $_SESSION["flash"] = "Accedi per poter visitare il sito";
  session_write_close();
  header("Location: index.php");
  die();
}

include 'common.php';
header('Content-Type: application/json');

    //genero la data del giorno corrente
    $now = new \DateTime('now');
    $today = $now->format('d');
    $month = intval($now->format('m'));
    $year = $now->format('Y');
    $name_month = array(
        1 => "Gennaio",
        2 => "Febbraio",
        3 => "Marzo",
        4 => "Aprile",
        5 => "Maggio",
        6 => "Giugno",
        7 => "Luglio",
        8 => "Agosto",
        9 => "Settembre",
        10 => "Ottobre",
        11 => "Novembre",
        12 => "Dicembre",
        "Gennaio" => 1,
        "Febbraio" => 2,
        "Marzo" => 3,
        "Aprile" => 4,
        "Maggio" => 5,
        "Giugno" => 6,
        "Luglio" => 7,
        "Agosto" => 8,
        "Settembre" => 9,
        "Ottobre" => 10,
        "Novembre" => 11,
        "Dicembre" => 12,
    );

    if (isset($_POST["calendar_function"])){
        if ($_POST["calendar_function"] == "next_month"){
            $now->setDate($_POST["year"], $name_month[$_POST["month"]]+1, $today);//genera la data rispetto al mese successivo a quello attuale
            $month = intval($now->format('m'));
            $year = $now->format('Y');
        }

        if ($_POST["calendar_function"] == "prev_month"){
            $now->setDate($_POST["year"], $name_month[$_POST["month"]]-1, $today);
            $month = intval($now->format('m'));
            $year = $now->format('Y');
        }
    }

    $check_day = new \DateTime('now'); //in caso in cui ci sia l' oggi di un altro mese/anno
    if ( intval($check_day->format('m')) != $month || $check_day->format('Y') != $year){
        $today = 0; //Se il mese o l' anno è diverso da quello attuale, non è oggi.
    }
  
        

    $day_in_month=cal_days_in_month(CAL_GREGORIAN,$month,$year); 

    //l' 1 indica il giorno del mese di cui vogliamo sapere il nome del giorno, lo 0 ne indica il formato della riposta, in questo caso numero da 1 a 7 invece che nome
    $start_day_month = jddayofweek(gregoriantojd($month,1,$year),0);


    $db = get_db(); //scarichi tutti gli eventi del mese del db
    $query = $db->prepare("SELECT day(data) as 'event_day', `punt_id_serie`, `stagione`, `num_puntata`, `punt_lingua`, `banner_img` 
                            from puntata inner join serie on punt_id_serie = id_serie and punt_lingua = serie_lingua join watchlist on wl_id_serie = id_serie and wl_lingua = serie_lingua join utente on wl_id_utente = id_utente 
                            where id_utente = :id_utente and month(data) = :month and year(data) = :year
                            order by day(data);");

    $query->bindParam(":id_utente", $_SESSION["id_utente"]);
    $query->bindParam(":month", $month);
    $query->bindParam(":year", $year);
    $query->execute();
    $num_episodi = $query->rowCount();
    $query = $query->fetchAll();


    $response = array( //risposta da inviare in ajax
                    'today' => $today,
                    'day_in_month'=> $day_in_month,
                    'name_month' => $name_month[$month],
                    'year' => $year,
                    'start_day' =>  $start_day_month,
                    'episodi' => $query,
                    'num_episodi' => $num_episodi,
                 );

    echo json_encode($response,JSON_FORCE_OBJECT);
?>