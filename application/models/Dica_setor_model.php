<?php
class Dica_Setor_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'dica_setor');
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
                            dica_setor.id as id,
                            dica_setor.titulo as titulo, 
                            dica_setor.link as link,
                            dica_setor.id_canais as id_canais, 
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_setor'),
                                array('nome' => 'dica_tipo', 'where' => 'dica_tipo.id_dica_setor = dica_setor.id', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'dica_setor.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            dica_setor.id as id, 
                            dica_setor.titulo as descricao 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_setor'),
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
                            dica_setor.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_setor'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            dica_setor.id as id,
                            dica_setor.titulo as titulo, 
                            dica_setor.link as link,
                            dica_setor.id_canais as id_canais, 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dica_setor'),
                                array('nome' => 'dica_tipo', 'where' => 'dica_tipo.id_dica_setor = dica_setor.id', 'tipo' => 'INNER'),
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