<?php
class Autorizadores_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'autorizadores');
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
                                array('nome' => 'autorizadores'),
                                );
    							
    	$data['filtro'] = 'autorizadores.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $concat = TRUE )
    {
    	$data['coluna'] = 'autorizadores.id as id, autorizadores.nome as descricao,';
        
    	$data['tabela'] = array(
                                array('nome' => 'autorizadores'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'autorizadores.nome';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens']) && $retorno['qtde'] > 0 ) ? $retorno['itens'] : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            autorizadores.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'autorizadores'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            autorizadores.id as id,
                            autorizadores.nome as nome,
                            autorizadores.cpf as cpf,
                            autorizadores.nascimento as nascimento,
                            autorizadores.aprovado as aprovado,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'autorizadores'),
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