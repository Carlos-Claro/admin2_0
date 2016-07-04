<?php
class Subcategorias_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'subcategorias');
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
                            subcategorias.id as id,
                            subcategorias.id_categoria as id_categoria,
                            subcategorias.data as data,
                            subcategorias.nome as titulo,
                            subcategorias.views as views,
                            subcategorias.listar as listar,
                            subcategorias.link as link,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                );
    							
    	$data['filtro'] = 'subcategorias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(), $concat = TRUE )
    {
    	$data['coluna'] = 'subcategorias.id as id, ';
        if ( $concat )
        {
            $data['coluna'] .= 'CONCAT(subcategorias.nome," / ",categorias.nome) as descricao,';
        }
        else
        {
            $data['coluna'] .= 'subcategorias.nome as descricao,';
        }
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                array('nome' => 'categorias',       'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'categorias.nome, subcategorias.nome';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'categorias.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = subcategorias.id_categoria', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'subcategorias.id = '.$filtro;
        $retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL ;
    }
    
    public function get_max_id( )
    {
    	$data['coluna'] = 'MAX(subcategorias.id) as id';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                );
    							
    	//$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            subcategorias.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                array('nome' => 'categorias',       'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            subcategorias.id as id,
                            subcategorias.id_categoria as id_categoria,
                            subcategorias.nome as nome,
                            subcategorias.views as views,
                            categorias.nome as categoria,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                array('nome' => 'categorias',       'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
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
                            subcategorias.id as id,
                            subcategorias.nome as descricao,
                            (SELECT COUNT(subcategorias.id) FROM (`subcategorias`) ) as qtde
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'subcategorias'),
                                array('nome' => 'categorias',       'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }*/
    
}