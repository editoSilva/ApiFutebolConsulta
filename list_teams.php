<?php

require($_SERVER['DOCUMENT_ROOT'] . "/src/config/App.php");

if(isset($_GET["league"])) {
    
    $league = preg_replace('/[^[:alnum:]_]/', '', $_GET["league"]);
        
    $read = new Read;
    
    $read->FullRead("select referal_team_id, name, league, country, logo FROM teams WHERE league='$league'");
    
    if ($read->getRowCount() > 0) {

        $rows = $read->getResult();

            $data = [

                ['success' => 1,'results' => count($rows),'teams' => $rows]
            ];

       echo  json_encode($data);


    } else {
            $data = [

                ['success' => 0,'results' => 0,'teams' => []]
            ];

       echo  json_encode($data);
    }


} else {

    $data =  ['error' => 'acesso não permitido!'];
    echo  json_encode($data);

}