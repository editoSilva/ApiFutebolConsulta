<?php
require($_SERVER['DOCUMENT_ROOT'] . "/src/config/App.php");

$leagues = []; 
$qty  = 0;
$timeZone= '';
$pais =  '';
$ano = '';

if(isset($_POST["timezone"]) && isset($_POST["pais"]) && isset($_POST["ano"])) {
    
    $timeZone = addslashes($_POST["timezone"]);
    $pais =  preg_replace('/[^[:alpha:]_]/', '',$_POST["pais"]);
    $ano = preg_replace('/[^[:alnum:]_]/', '',$_POST["ano"]);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-footballv1.p.rapidapi.com/v2/leagues/type/league/$pais/$ano?timezone=$timeZone",
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

    foreach($response->api->leagues as $key => $league) {

        $leagues[$key]['country'] = $league->country;
        $leagues[$key]['logo'] = $league->logo;
        $leagues[$key]['flag'] = $league->flag;
        $leagues[$key]['referal_league_id'] = $league->league_id;
        $leagues[$key]['name'] = $league->name;


        $dados = [
            'country' => $league->country,
            'logo' => $league->logo,
            'flag' => $league->flag,
            'referal_league_id' => $league->league_id,
            'name' => $league->name,
            'createdAt' => date_create()->format('Y-m-d H:i:s'),
            'ano'   => $ano,
            'timezone' => $timeZone,
            'pais'     => $pais, 
        ];

        //Verifica se o dado não já está inserido
        //SELECT
        $read = new Read;
        $read->FullRead("select referal_league_id, ano, timezone FROM leagues WHERE  referal_league_id ='$league->league_id' and ano='$ano'");
        
        if ($read->getRowCount() < 1) {
            // sem resultados
            //Cadastra
            
            $Cadastra->ExeCreate('leagues', $dados);

            //se a inserção foi bem sucedida
            if ($Cadastra->getResult()) {

                $novoIdCriado = $Cadastra->getResult();

            }
        }else{

            //Atualiza
            $rows = $read->getResult();

            foreach ($rows as $row) {
            
            //UPDATE
            $campoPK = $row['referal_league_id']; // id a ser atualizado
            $dados = [
                'country' => $league->country,
                'logo' => $league->logo,
                'flag' => $league->flag,
                'referal_league_id' => $league->league_id,
                'name' => $league->name,
                'updatedAt' => date_create()->format('Y-m-d H:i:s')
            ];


            $Update = new Update;
            $Update->ExeUpdate('leagues', $dados, "WHERE referal_league_id= :referal_league_id", 'referal_league_id=' . $campoPK);
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
            <h5 class="card-title">Buscar Campeonatos</h5>
            <p class="card-text">Quantidade: <b><?php echo  $qty; ?></b></p>
            <p class="card-text">Você pesquisou por:</p>
            <p class="card-text">Time Zone: <b><?php echo  $timeZone; ?></b>País: <b><?php echo  $pais; ?></b> Ano:
                <b><?php echo  $ano; ?></b>
            </p>

        </div>


        <form method="POST">
            <div class="card card-header">
                <div class="form-row align-items-center">
                    <div class="col-sm-3 my-1">
                        <label class="sr-only" for="inlineFormInputName">Time Zone</label>
                        <input type="text" class="form-control" id="inlineFormInputName" name="timezone" required
                            placeholder="Time Zone">
                    </div>
                    <div class="col-sm-3 my-1">
                        <label class="sr-only" for="inlineFormInputName">País</label>
                        <input type="text" class="form-control" id="inlineFormInputPais" name="pais" placeholder="País"
                            required>
                    </div>
                    <div class="col-sm-3 my-1">
                        <label class="sr-only" for="inlineFormInputName">Ano</label>
                        <input type="text" class="form-control" id="inlineFormInputAno" name="ano" placeholder="Ano"
                            required>
                    </div>
                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
        </form>
        <div class='card int-league logo'>

            <?php





        if(count($leagues) > 0 ) {
                foreach($leagues as $league) {
                    
                    echo "<p>" .$league['referal_league_id']." <img src='".$league['flag']."' /> <img src='".$league['logo']."' />" .$league['name']."</p>";
            
                }
                
            } else {

                
                echo "<h3>Nenhu dado ecotrado!</h3>";
            }


?>
        </div>

    </div>




    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>

</body>

</html>