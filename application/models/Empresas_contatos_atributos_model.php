<?php
class Empresas_Contatos_Atributos_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_contatos_atributos');
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
                                array('nome' => 'empresas_contatos_atributos'),
                                );
    							
    	$data['filtro'] = 'empresas_contatos_atributos.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = NULL )
    {
    	$data['coluna'] = '
                            empresas_contatos_atributos.id as id, 
                            empresas_contatos_atributos.campo as descricao,';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contatos_atributos')
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_contatos_atributos.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contatos_atributos'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id = empresas_contatos_atributos.id_contato', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'ASC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            empresas_contatos_atributos.id as id,
                            empresas_contatos_atributos.id_contato as id_contato,
                            empresas_contatos_atributos.campo as campo,
                            empresas_contatos_atributos.valor as valor,
                            empresas_contato.nome as nome,
                            empresas_contato.email as email,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contatos_atributos'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id = empresas_contatos_atributos.id_contato', 'tipo' => 'INNER'),
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