<?php
require($_SERVER['DOCUMENT_ROOT'] . "/src/config/App.php");

//SELECT
$read = new Read;
$read->FullRead("QUERY");
if ($read->getRowCount() < 1) {
    // sem resultados
}

$rows = $read->getResult();

foreach ($rows as $row) {

    //lista os resultados
    echo $row['campo_1'].'<br>';
    echo $row['campo_2'];
}

//INSERT

//adicione os nomes dos campo e seus respectivos valores
//!!!!! tratar dados de entrada
$dados = [
    'campo_1' => 'valor',
    'campo_2' => 'valor',

];



$Cadastra = new Create;

$Cadastra->ExeCreate('tabela', $dados);

//se a inserção foi bem sucedida
if ($Cadastra->getResult()) {

    $novoIdCriado = $Cadastra->getResult();

    //continua o código
}

//UPDATE

//adicione os nomes dos campo e seus respectivos valores
//!!!!! tratar dados de entrada

$campoPK = 1; // id a ser atualizado
$dados = [
    'campo_1' => 'valor',
    'campo_2' => 'valor',

];


$Update = new Update;
$Update->ExeUpdate('tabela', $dados, "WHERE campoPK = :campoPK", 'campoPK=' . $campoPK);
if ($Update->getResult()) {
    //atualziado com sucesso.
    // executar outras acoes e/ou logs
}

//DELETE
//!!!!! tratar dados de entrada
$deleta = new Delete;
$deleta->ExeDelete('tabela', "WHERE campoPK = :campoPK", 'campoPK=' . $campoPK);
if ($deleta->getResult()) {
    //deletado com sucesso.
    // executar outras acoes e/ou logs
}