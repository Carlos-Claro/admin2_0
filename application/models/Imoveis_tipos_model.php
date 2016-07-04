<?php
class Imoveis_tipos_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_tipos');
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
    	$data['coluna'] = 'imoveis_tipos.id as id,
                           imoveis_tipos.nome as nome,
                           imoveis_tipos.busca as busca,
                           imoveis_tipos.seo as seo, 
                           imoveis_tipos.tipo_area as tipo_area, 
                           imoveis_tipos.link as link,
                           imoveis_tipos.plural as plural, 
                           ';
        $data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    							
    	$data['filtro'] = 'imoveis_tipos.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_filtro( $filtro = NULL )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_tipos.id as id, imoveis_tipos.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
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
                            imoveis_tipos.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'imoveis_tipos.id as id,
                           imoveis_tipos.nome as nome,
                           imoveis_tipos.busca as busca,
                           imoveis_tipos.seo as seo, 
                           imoveis_tipos.tipo_area as tipo_area, 
                           imoveis_tipos.link as link,
                           imoveis_tipos.plural as plural,
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
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
