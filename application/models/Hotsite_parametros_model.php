<?php
class Hotsite_parametros_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'hotsite_parametros');
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
    	$data['coluna'] = ' hotsite_parametros.*, 

                            ';
    	$data['tabela'] = array(
                                array('nome' => 'hotsite_parametros'),
                                );
    							
    	$data['filtro'] = 'hotsite_parametros.id_empresa = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array() )
    {
//    	$data['coluna'] = 'hotsite_parametros.id as id, hotsite_parametros.empresa_nome_fantasia as descricao ';
//    	$data['tabela'] = array(
//                                array('nome' => 'empresas'),
//                                );
//    							
//    	$data['filtro'] = $filtro;
//    	$retorno = $this->get_itens_($data);
//    	return $retorno['itens'];
    }
    
    public function get_select_paginas( $filtro = array() )
    {
    	$data['coluna'] = 'hotsite_paginas.id as id, hotsite_paginas.nome_pagina as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'hotsite_paginas'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            hotsite_parametros.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'hotsite_parametros'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
}