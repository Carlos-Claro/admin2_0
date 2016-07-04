<?php
class Publicidade_areas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'publicidade_areas');
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
                            *
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas'),
                                );
    							
    	$data['filtro'] = 'publicidade_areas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'area', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'publicidade_areas.id as id, CONCAT(publicidade_areas.area, " - ", publicidade_areas.posicao) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_posicao( $filtro = array(), $coluna = 'area', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'publicidade_areas.posicao as id, publicidade_areas.posicao as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'publicidade_areas.posicao';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            publicidade_areas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            publicidade_areas.id as id,
                            publicidade_areas.area as area,
                            publicidade_areas.quantia as quantia,
                            publicidade_areas.posicao as posicao,
                            COUNT(publicidade_campanhas.id) as qtde_total,
                            COUNT(ativas.id) as qtde_ativas,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas'),
                                array('nome' => 'publicidade_campanhas', 'where' => 'publicidade_areas.id = publicidade_campanhas.id_servico', 'tipo' => 'LEFT'),
                                array('nome' => 'publicidade_campanhas ativas', 'where' => 'publicidade_areas.id = publicidade_campanhas.id_servico AND ativas.inicio > '.time().' AND ativas.termino < '.time(), 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'publicidade_areas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}