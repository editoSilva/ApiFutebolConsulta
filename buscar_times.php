<?php
require($_SERVER['DOCUMENT_ROOT'] . "/src/config/App.php");

$qty = 0;
$times = [];
$liga = '';


//Listar  Campeoantos cadastrados
//SELECT
$read = new Read;
$read->FullRead("select referal_league_id, name FROM leagues");
if ($read->getRowCount() < 1) {
    // sem resultados
}

$ligas = $read->getResult();


if(isset($_POST["id"])) {
    

    $id = preg_replace('/[^[:alnum:]_]/', '', $_POST["id"]);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v2/teams/league/$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
        'x-rapidapi-key: 4e081b6d5dmsh213846c08567d5fp1151c8jsn53cc4b1d7139',
        'X-RapidAPI-Host: api-football-v1.p.rapidapi.com'
        ),
    ));

    $response = curl_exec($curl);


    $response = json_decode($response);

    
    $qty = $response->api->results;
    
    curl_close($curl);

    $Cadastra = new Create;
    foreach($response->api->teams as $key => $time) {

        $times[$key]['country'] = $time->country;
        $times[$key]['logo'] = $time->logo;
        $times[$key]['referal_team_id'] = $time->team_id;
        $times[$key]['name'] = $time->name;




        $dados = [
            'country' => $time->country,
            'logo' => $time->logo,
            'league' => $id,
            'referal_team_id' => $time->team_id,
            'name' => $time->name,
            'createdAt' => date_create()->format('Y-m-d H:i:s')
        ];

        //Verifica se o dado não já está inserido
        //SELECT
        $read = new Read;
        $read->FullRead("select referal_team_id FROM teams WHERE  referal_team_id =".$id);
        
        if ($read->getRowCount() < 1) {
            // sem resultados
            //Cadastra
            
            $Cadastra->ExeCreate('teams', $dados);

            //se a inserção foi bem sucedida
            if ($Cadastra->getResult()) {

                $novoIdCriado = $Cadastra->getResult();

            }
        }else{

            //Atualiza
            $rows = $read->getResult();

            foreach ($rows as $row) {
            
            //UPDATE
            $campoPK = $row['referal_team_id']; // id a ser atualizado
            $dados = [
                'country' => $time->country,
                'logo' => $time->logo,
                'league' => $id,
                'referal_team_id' => $time->league_id,
                'name' => $time->name,
                'createdAt' => date_create()->format('Y-m-d H:i:s')
            ];


            $Update = new Update;
            $Update->ExeUpdate('teams', $dados, "WHERE referal_team_id= :referal_team_id", 'referal_team_id=' . $campoPK);
            if ($Update->getResult()) {
            //atualziado com sucesso.
            // executar outras acoes e/ou logs
            }

 
            }
            
        } 


        

    }

}


?>


<html lang="pt_BR">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Consulta de dados futebol</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <style>
    .int-league {

        padding: 8px;
        margin-bottom: 12px;
    }

    .logo img {
        width: 20px;
        height: 20px;
        margin-right: 6px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="text-center card-body">
            <h5 class="card-title">Listade Times </h5>
            <p class="card-text">Quantidade Times: <b><?php echo  $qty; ?></b></p>
        </div>


        <form method="POST">
            <div class="card card-header">
                <div class="form-row align-items-center">
                    <div class="col-sm-3 my-1">

                        <select class="form-control" id="exampleFormControlSelect1" name="id">

                            <?php
                            foreach ($ligas as $row) {
                                echo "<option value='".$row['referal_league_id']."'>ID: ".$row['referal_league_id']." -  ".$row['name']."</option>";

                            }
                    ?>

                        </select>

                    </div>

                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </div>
            </div>
        </form>
        <div class='card int-league logo'>

            <?php


        if(count($times) > 0 ) {
                foreach($times as $time) {
                    
                    echo "<p>" .$time['referal_team_id']."  <img src='".$time['logo']."' />" .$time['name']."</p>";
            
                }
                
            } else {

                
                echo "<h3>Nenhu dado ecotrado!</h3>";
            }


?>
        </div>

    </div>