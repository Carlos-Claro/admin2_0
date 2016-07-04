<?php
class Image_tipo_model extends MY_Model
{
    
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'image_tipo');
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
                                array('nome' => 'image_tipo'),
                                );
    	
    	$data['filtro'] = 'image_tipo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'tipo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'image_tipo.id as id, image_tipo.tipo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
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
                            image_tipo.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            image_tipo.id as id,
                            image_tipo.tipo as tipo,
                            image_tipo.sub_tipo as sub_tipo,
                            image_tipo.descricao as descricao,
                            image_tipo.pasta as pasta,';
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
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