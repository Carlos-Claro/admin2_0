<?php
class Imoveis_estilos_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_estilos');
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
    	$data['coluna'] = 'imoveis_estilos.id as id,
                           imoveis_estilos.nome as nome,
                           imoveis_estilos.descricao as descricao,
                           ';
        $data['tabela'] = array(
                                array('nome' => 'imoveis_estilos'),
                                );
    							
    	$data['filtro'] = 'imoveis_estilos.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_estilos.id as id, imoveis_estilos.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_estilos'),
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
                            imoveis_estilos.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_estilos'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'imoveis_estilos.id as id,
                           imoveis_estilos.nome as nome,
                           imoveis_estilos.descricao as descricao,
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_estilos'),
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