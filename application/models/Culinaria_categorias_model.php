<?php
class Culinaria_Categorias_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'culinaria_categorias');
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
                            culinaria_categorias.id as id,
                            culinaria_categorias.nome as titulo,
                            culinaria_categorias.icone as icone,
                            culinaria_categorias.ordem as ordem,
                            culinaria_categorias.liberado as liberado,
                            culinaria_categorias.link as link,
                            culinaria_categorias.id_canais as id_canais,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_categorias'),
                                );
    							
    	$data['filtro'] = 'culinaria_categorias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'culinaria_categorias.id as id, culinaria_categorias.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_categorias '),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
     public function get_max_id( )
    {
    	$data['coluna'] = 'MAX(culinaria_categorias.id) as id';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_categorias'),
                                );
    							
    	//$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            culinaria_categorias.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_categorias'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            culinaria_categorias.id as id,
                            culinaria_categorias.nome as nome,
                            culinaria_categorias.icone as icone,
                            culinaria_categorias.ordem as ordem,
                            culinaria_categorias.liberado as liberado,
                            culinaria_categorias.link as link,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_categorias'),
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