<?php
class Noticias_tipo_area_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'noticias_tipo_area.id as id, noticias_tipo_area.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'noticias_tipo_area'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
}