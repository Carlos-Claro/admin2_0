<?php
class Facebook_Groups_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'facebook_grupos');
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
                                array('nome' => 'facebook_groups'),
                                );
    							
    	$data['filtro'] = 'facebook_groups.id_group = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'facebook_groups.id_group as id, facebook_groups.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                //array('nome' => 'facebook_groups', 'where' => 'facebook_groups.id_cidade = 0 ', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = $filtro;
        //$data['col'] = 'facebook_groups.nome ';
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            facebook_groups.id_group as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id_group';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            facebook_groups.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno : FALSE );
    }
    
    public function get_nome_por_id( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            facebook_groups.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE );
    }
    
    /*
    public function get_id_por_categoria( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            facebook_groups.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno['itens'] : FALSE );
    }*/
    
    public function get_grupos_por_cidade( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            facebook_groups.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = facebook_groups.id_cidade', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno['itens'] : FALSE );
    }
    
    public function get_grupos_por_categoria( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            facebook_groups.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                array('nome' => 'facebook_categorias', 'where' => 'facebook_categorias.id = facebook_groups.id_categoria', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno['itens'] : FALSE );
    }
    
    /*
    public function get_itens_por_id($filtro = NULL)
    {
    	$data['coluna'] = 'facebook_groups.id_group as id ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_groups'),
                                );
    	$data['filtro'] = 'facebook_groups.id_group = '.$filtro;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? TRUE : FALSE );
    }*/
    
}