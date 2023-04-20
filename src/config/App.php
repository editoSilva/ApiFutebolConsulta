<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8');
ini_set('max_execution_time', 9600);
date_default_timezone_set('America/Sao_Paulo');





define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);


require_once(ROOT_PATH . '/src/config/conn/Conn.class.php');
require_once(ROOT_PATH . '/src/config/conn/Create.class.php');
require_once(ROOT_PATH . '/src/config/conn/Delete.class.php');
require_once(ROOT_PATH . '/src/config/conn/Read.class.php');
require_once(ROOT_PATH . '/src/config/conn/Query.class.php');
require_once(ROOT_PATH . '/src/config/conn/Update.class.php');



if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $socket = "https://";
} else {
    $socket = "http://";
}


$_servidor = $_SERVER['SERVER_NAME'];


define('SERVER_URL', $socket . $_servidor);

//config conexao
$host = 'localhost';
$banco = 'dbname';
$usuario = 'root';
$senha = 'pwd';



// CONFIGRAÇÕES DA CONEXÃO ####################
define('HOST', $host);
define('USER', $usuario);
define('PASS', $senha);
define('DBSA', $banco);



// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('WS_ACCEPT', 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');

//WSErro :: Exibe erros lançados :: Front
function WSErro($ErrMsg, $ErrNo, $ErrDie = null)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";

    if ($ErrDie) :
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR) :
        die;
    endif;
}

set_error_handler('PHPErro');