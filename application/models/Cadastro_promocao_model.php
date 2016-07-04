<?php
class Cadastro_promocao_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cadastro_promocao');
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
    	$data['coluna'] = 'cadastro_promocao.*, '
                        . 'image_arquivo.arquivo as arquivo, '
                        . 'image_arquivo.id as id_arquivo, '
                        . 'image_pai.id as id_pai_arquivo, '
                        . 'image_tipo.pasta as pasta';
        $data['tabela'] = array(
                                array('nome' => 'cadastro_promocao'),
                                array('nome' => 'image_pai',   'where' => 'image_pai.id_pai = cadastro_promocao.id AND image_pai.id_image_tipo = 32',  'tipo' => 'LEFT' ),
                                array('nome' => 'image_arquivo',   'where' => 'image_pai.id_image_arquivo = image_arquivo.id',  'tipo' => 'LEFT' ),
                                array('nome' => 'image_tipo',   'where' => 'image_pai.id_image_tipo = image_tipo.id ',  'tipo' => 'LEFT' ),
                                );
    							
    	$data['filtro'] = 'cadastro_promocao.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'cadastro_promocao.id as id, cadastro_promocao.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_promocao'),
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
                            cadastro_promocao.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_promocao'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cadastro_promocao.id as id,
                            cadastro_promocao.titulo as titulo,
                            cadastro_promocao.data as data'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_promocao'),
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