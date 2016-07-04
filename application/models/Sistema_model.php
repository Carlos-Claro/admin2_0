<?php
class Sistema_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'sistema');
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
    
    public function get_max_id()
    {
        $query = $this->db->select_max('id');
        $retorno = $query->get('sistema')->result();
        return isset($retorno[0]->id)?($retorno[0]->id + 1) : 0;
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'sistema'),
                                );
    							
    	$data['filtro'] = 'sistema.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            sistema.id as id, 
                            sistema.nome as descricao 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'sistema'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_tag( $filtro = array(),$coluna = 'descricao', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'DISTINCT sistema.tag as id,
                           sistema.tag as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'sistema'),
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
                            count(sistema.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'sistema'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            sistema.id as id,
                            sistema.nome as nome,
                            sistema.tag as tag,
                            sistema.responsavel as responsavel,
                            sistema.email as email,
                            sistema.telefone as telefone,
                            sistema.documentacao as documentacao'; 
    	$data['tabela'] = array(
                                array('nome' => 'sistema'),
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
