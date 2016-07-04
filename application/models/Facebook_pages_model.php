<?php
class Facebook_Pages_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'facebook_pages');
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
                                array('nome' => 'facebook_pages'),
                                );
    							
    	$data['filtro'] = 'facebook_pages.id_page = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array(), $coluna = 'nome', $ordem = 'ASC')
    {
    	$data['coluna'] = 'facebook_pages.id_page as id, facebook_pages.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        //$data['col'] = 'facebook_pages.nome ';
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            facebook_pages.id_page as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_pages.id_page as id,
                            facebook_pages.nome as nome,
                            facebook_pages.link as link,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
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
    
     public function get_nome_por_id( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_pages.id_page as id,
                            facebook_pages.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
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
                            facebook_pages.id_page as id,
                            facebook_pages.nome as nome,
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
    }
     * */
    
    public function get_pages_por_cidade( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_pages.id_page as id,
                            facebook_pages.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = facebook_pages.id_cidade', 'tipo' => 'INNER')
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
    
    public function get_pages_por_categoria( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_pages.id_page as id,
                            facebook_pages.nome as nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                array('nome' => 'facebook_categorias', 'where' => 'facebook_categorias.id = facebook_pages.id_categoria', 'tipo' => 'INNER')
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
    
    public function get_itens_por_id($filtro = NULL)
    {
    	$data['coluna'] = 'facebook_pages.id_page as id, facebook_pages.link as link ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                );
    	$data['filtro'] = 'facebook_pages.id_page = '.$filtro;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? TRUE : FALSE );
    }
    
    /*
    public function get_itens_por_link($link = NULL)
    {
    	$data['coluna'] = 'facebook_pages.link as link, cidades.portal as portal ';
    	$data['tabela'] = array(
                                array('nome' => 'facebook_pages'),
                                array('nome' => 'cidades', 'where' => 'cidades.portal like '.$link.'', 'tipo' => 'INNER')
                                );
    	//$data['filtro'] = 'facebook_pages.id_page = '.$filtro;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? TRUE : FALSE );
    }*/
   
}