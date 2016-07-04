<?php
class Reserva_log_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'reserva_log');
    }
	
    public function adicionar( $data = array() )
    {
        return $this->adicionar_($this->database, $data);
    }
    
    public function editar($data = array(),$filtro = array())
    {
        return $this->editar_($this->database, $data, $filtro);
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '
                            reserva_log.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'reserva_log'),
                                );
    							
    	$data['filtro'] = 'reserva_log.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $concat = TRUE )
    {
    	$data['coluna'] = 'reserva_log.id as id, ';
            $data['coluna'] .= 'reserva_log.id_item as descricao,';
    	$data['tabela'] = array(
                                array('nome' => 'reserva_log'),
                                array('nome' => 'tipo',       'where' => 'reserva_log.id_item = tipo.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            reserva_log.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'reserva_log'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            reserva_log.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'reserva_log'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    
}