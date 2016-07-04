<?php
class Dica_Tipo_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'dica_tipo');
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
                            dica_tipo.id as id,
                            dica_tipo.titulo as titulo, 
                            dica_tipo.link as link,
                            dica_tipo.tipo as tipo, 
                            dica_tipo.id_dica_setor as id_dica_setor, 
                            dica_setor.id as id_setor,
                            dica_setor.titulo as titulo_setor, 
                            dica_setor.link as link_setor,
                            dica_setor.id_canais as id_canais 
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_tipo'),
                                array('nome' => 'dica_setor', 'where' => 'dica_setor.id = dica_tipo.id_dica_setor', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'dica_tipo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            dica_tipo.id as id, 
                            dica_tipo.titulo as descricao 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_tipo'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_tipo( $filtro = array(), $coluna = 'dica_tipo.titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            dica_tipo.id as id, 
                            CONCAT(dica_tipo.titulo," - ",dica_setor.titulo) as descricao 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_tipo'),
                                array('nome' => 'dica_setor', 'where' => 'dica_setor.id = dica_tipo.id_dica_setor', 'tipo' => 'INNER')
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
                            dica_tipo.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_tipo'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'dica_tipo.id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            dica_tipo.id as id,
                            dica_tipo.titulo as titulo, 
                            dica_tipo.link as link,
                            dica_tipo.tipo as tipo, 
                            dica_tipo.id_dica_setor as id_dica_setor, 
                            dica_setor.id as id_setor,
                            dica_setor.titulo as titulo_setor, 
                            dica_setor.link as link_setor,
                            dica_setor.id_canais as id_canais 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_tipo'),
                                array('nome' => 'dica_setor', 'where' => 'dica_setor.id = dica_tipo.id_dica_setor', 'tipo' => 'INNER')
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