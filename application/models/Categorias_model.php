<?php
class Categorias_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'categorias');
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
                            categorias.id as id,
                            categorias.nome as titulo,
                            categorias.views as views,
                            categorias.endereco as endereco,
                            categorias.meta_titulo as meta_titulo,
                            categorias.meta_descricao as meta_descricao,
                            categorias.meta_keys as meta_keys,
                            categorias.link as link,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
                                );
    							
    	$data['filtro'] = 'categorias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'categorias.id as id, categorias.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
                                //array('nome' => 'subcategorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'categorias.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = noticias.id_categoria', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$filtro;
        $retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    
    public function get_max_id( )
    {
    	$data['coluna'] = 'MAX(categorias.id) as id';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
                                );
    							
    	//$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            categorias.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            categorias.id as id,
                            categorias.nome as titulo,
                            categorias.views as views,
                            categorias.endereco as endereco,
                            categorias.meta_titulo as meta_titulo,
                            categorias.meta_descricao as meta_descricao,
                            categorias.meta_keys as meta_keys,
                            categorias.link as link,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
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
    
    /*
    public function get_itens_json( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            categorias.id as id,
                            categorias.nome as descricao,
                            COUNT(subcategorias.id_categoria) as qtde
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'categorias'),
                                array('nome' => 'subcategorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['group'] = 'categorias.id';
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }*/
    
}