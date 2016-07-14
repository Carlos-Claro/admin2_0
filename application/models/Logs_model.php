<?php
class Logs_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        $array_db = array('guiasjp','logs');
        parent::__construct($array_db);
        
    }
	
    public function adicionar( $data = array() )
    {
        $database = array('db' => 'guiasjp','table' => 'logs');
        return $this->adicionar_($database, $data);
    }
    
    public function adicionar_dia( $data = array() )
    {
        $database = array('db' => 'logs','table' => 'logs_dia');
        return $this->adicionar_multi_($database, $data);
    }

    public function excluir_insert($filtro)
    {
        $database = array('db' => 'guiasjp','table' => 'logs_insert');
        return $this->excluir_($database, $filtro);
    }
    
    
    public function get_itens_insert_dia( $dia )
    {
    	$data['coluna'] = '	
                            logs_insert.id_tabela as id_tabela,
                            logs_insert.id_local as id_local,
                            DATE_FORMAT(logs_insert.data, ,"%Y-%m-%d") as data,
                            SUM(logs_insert.views) as views,
                            SUM(logs_insert.clicks) as clicks,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logs_insert'),
                                );
    	$data['filtro'] = 'logs_insert.data < NOW()';;
        $data['off_set'] = 0;
        $data['qtde_itens'] = 10000000;
    	$data['group'] = 'logs_insert.id_tabela, logs_insert.id_local';
    	$data['col'] = 'logs_insert.id_tabela';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data,1);
    	var_dump($retorno);die();
    	return $retorno;
    }
    
}