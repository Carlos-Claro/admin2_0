<?php
class Estados_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'estados');
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
                                array('nome' => 'estados'),
                                );
    							
    	$data['filtro'] = 'estados.uf = "'.$id.'"';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_item_por_nome( $nome = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
                                );
    							
    	$data['filtro'] = 'estados.nome like "'.$nome.'"';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0]->uf : NULL;
    }
	
	
    public function get_select( $filtro = array(), $coluna = 'uf', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'estados.uf as id, estados.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
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
                            estados.uf as uf,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'uf';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'uf', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            estados.uf as uf,
                            estados.nome as nome
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
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