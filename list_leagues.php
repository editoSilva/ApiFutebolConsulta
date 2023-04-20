<?php
require($_SERVER['DOCUMENT_ROOT'] . "/src/config/App.php");

if(isset($_GET["timezone"]) && isset($_GET["pais"]) && isset($_GET["ano"])) {
    
    $timeZone   =  preg_replace('/[^[:alpha:]_]/', '', $_GET["timezone"]);
    $pais       =  preg_replace('/[^[:alpha:]_]/', '', $_GET["pais"]);
    $ano        =  preg_replace('/[^[:alnum:]_]/', '', $_GET["ano"]);
    
    $read = new Read;
    
    $read->FullRead("select referal_league_id, name, country, flag, logo FROM leagues WHERE country ='$pais' AND  pais='$pais'  AND ano='$ano'");
    
    if ($read->getRowCount() > 0) {

        $rows = $read->getResult();

            $data = [

                ['success' => 1,'results' => count($rows),'leages' => $rows]
            ];

       echo  json_encode($data);


    } else {
            $data = [

                ['success' => 0,'results' => 0,'leages' => []]
            ];

       echo  json_encode($data);
    }

}else {

    $data =  ['error' => 'acesso não permitido!'];
    echo  json_encode($data);

}