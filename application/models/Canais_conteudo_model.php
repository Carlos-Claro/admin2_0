<?php
class Canais_Conteudo_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canais_conteudo');
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
                                array('nome' => 'canais_conteudo'),
                                );
    							
    	$data['filtro'] = 'canais_conteudo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais_conteudo.id as id, canais_conteudo.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'canais_conteudo'),
                               // array('nome' => 'canais_setor', 'where' => 'canais_setor.id = canais_conteudo.id_canais_setor', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] =  $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'canais_conteudo.id_canais_setor as id';
    	$data['tabela'] = array(
                                array('nome' => 'canais_conteudo'),
                                array('nome' => 'canais_setor', 'where' => 'canais_setor.id = canais_conteudo.id_canais_setor', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'canais_conteudo.id = '.$filtro;
        $retorno = $this->get_itens_($data);
    	return isset($retorno['itens']) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            canais_conteudo.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_conteudo'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais_conteudo.id as id,
                            canais_conteudo.id_canais_setor as id_canais_setor,
                            canais_setor.titulo as titulo_canais_setor,
                            canais_conteudo.titulo as titulo,
                            canais_conteudo.descricao as descricao,
                            canais_conteudo.link as link, 
                            canais_conteudo.title as title,
                            canais_conteudo.description as description, 
                            canais_conteudo.ordem as ordem,
                            canais_conteudo.ativo as ativo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_conteudo'),
                                array('nome' => 'canais_setor', 'where' => 'canais_setor.id = canais_conteudo.id_canais_setor', 'tipo' => 'INNER'),
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