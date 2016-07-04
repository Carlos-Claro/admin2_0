<?php
class Editorias_Model extends MY_Model {
	
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'editorias');
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
                                array('nome' => 'editorias'),
                                );
    							
    	$data['filtro'] = 'editorias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'editorias.id as id, editorias.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'editorias'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'editorias.id as id ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'editorias', 'where' => 'editorias.id = noticias.id_editoria', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            editorias.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'editorias'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            editorias.id as id,
                            editorias.nome as nome,
                            editorias.link as link,
                            editorias.cor as cor,
                            canais.titulo as canal,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'editorias'),
                                array('nome' => 'canais', 'where' => 'canais.id = editorias.id_canais', 'tipo' => 'INNER')
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