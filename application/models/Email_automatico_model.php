<?php
class Email_Automatico_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'email_automatico');
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
                                array('nome' => 'email_automatico'),
                                );
    							
    	$data['filtro'] = 'email_automatico.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_anexo( $id = '' )
    {
    	$data['coluna'] = ' 
                            IF(image_pai.id_pai <> 0, CONCAT(image_tipo.pasta,image_arquivo.arquivo), NULL ) as anexo
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'email_automatico'),
                                array('nome' => 'image_pai', 'where' => 'image_pai.id_pai = email_automatico.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo', 'where' => 'image_arquivo.id = image_pai.id_image_arquivo', 'tipo' => 'INNER'),
                                array('nome' => 'image_tipo', 'where' => 'image_tipo.id = image_pai.id_image_tipo AND image_pai.id_image_tipo = 23', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'email_automatico.id = '.$id;
    	$data['group'] = 'email_automatico.id';
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL);
    }
    
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'email_automatico.id as id, email_automatico.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'email_automatico'),
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
                            email_automatico.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'email_automatico'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            email_automatico.id as id,
                            email_automatico.titulo as titulo,
                            email_automatico.corpo as corpo,
                            email_automatico.assinatura as assinatura, 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'email_automatico'),
                                //array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
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