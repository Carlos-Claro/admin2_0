<?php
class Logradouros_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'logradouros');
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
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'logradouros'),
                                );
    							
    	$data['filtro'] = 'logradouros.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'logradouros.id as id, logradouros.logradouro as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'logradouros'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'logradouros.cep';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_bairros( $filtro = array() )
    {
    	$data['coluna'] = 'logradouros.bairro as id, logradouros.bairro as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'logradouros'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['group'] = 'logradouros.bairro';
        $data['col'] = 'logradouros.cep';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            logradouros.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logradouros'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            logradouros.id as id,
                            logradouros.logradouro as logradouro,
                            logradouros.cep as cep,
                            logradouros.cepi as cepi,
                            logradouros.bairro as bairro,
                            logradouros.inicio as n_inicio,
                            logradouros.final as n_final,
                            logradouros.cidade as cidade,
                            logradouros.id_cidade as id_cidade,
                            cidades.uf as estado
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logradouros'),
                                array('nome' => 'cidades',       'where' => 'logradouros.id_cidade = cidades.id', 'tipo' => 'INNER'),
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