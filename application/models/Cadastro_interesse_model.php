<?php
class Cadastro_interesse_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cadastro_interesse');
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
                                array('nome' => 'cadastro_interesse'),
                                );
    							
    	$data['filtro'] = 'cadastro_interesse.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'cadastro_interesse.id as id, cadastro_interesse.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_interesse'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            cadastro_interesse.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_interesse'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cadastro_interesse.id as id,
                            cadastro_interesse.titulo as titulo,
                            cadastro_interesse.tipo as tipo'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_interesse'),
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