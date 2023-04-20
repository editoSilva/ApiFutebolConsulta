<?php

/**
 * <b>Update.class:</b>
 * Classe responsável por atualizações genéticas no banco de dados!
 */


class Query extends Conn {

    
    private $Result;

    /** @var PDOStatement */
    private $Query;
	
	
	
	public function QueryExe($Query) {
		
      
       
        $this->Execute();
    }
	
	 //Obtém o PDO e Prepara a query
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Query = $this->Conn->query($this->Query);
    }

  

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->Query->execute($this->Query);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao Deletar:</b> {$e->getMessage()}", $e->getCode());
        }
    }
	
}