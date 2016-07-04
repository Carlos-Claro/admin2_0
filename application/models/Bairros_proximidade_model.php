<?php
class Bairros_proximidade_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'bairros_proximidade');
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
                                array('nome' => 'bairros_proximidade'),
                                );
    							
    	$data['filtro'] = 'bairros_proximidade.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'bairros_proximidade.id as id, bairros_proximidade.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
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
                            bairros_proximidade.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            bairros_proximidade.id as id,
                            bairros_proximidade.titulo as titulo,
                            CONCAT("proximo_",bairros_proximidade.link) as link, 
                            bairros_proximidade.link_bairros as link_bairros,
                            bairros_proximidade.ativo as ativo, 
                            cidades.nome as cidade
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                array('nome' => 'cidades', 'where' => 'bairros_proximidade.id_cidade = cidades.id', 'tipo' => 'INNER'),
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
