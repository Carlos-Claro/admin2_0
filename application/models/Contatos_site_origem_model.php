<?php
class Contatos_Site_Origem_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'contatos_site_origem');
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
                                array('nome' => 'contatos_site_origem'),
                                //array('nome' => 'contatos_site', 'where' => 'contatos_site_origem.origem = contatos_site.origem', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'contatos_site_origem.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'descricao', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'contatos_site_origem.origem as id,
                           CONCAT(contatos_site_origem.local, " (" , contatos_site_origem.origem, ")") as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site_origem'),
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
                            contatos_site_origem.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site_origem'),
                                //array('nome' => 'contatos_site', 'where' => 'contatos_site_origem.origem = contatos_site.origem', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            contatos_site_origem.id as id,
                            contatos_site_origem.origem as origem,
                            contatos_site_origem.local as local,
                            contatos_site_origem.imoveis as imoveis,
                            contatos_site_origem.guiasjp as guiasjp,
                            contatos_site.origem as orige,
                            
                            CONCAT(contatos_site_origem.local, " (" , contatos_site_origem.origem, ")") as id_local_origem';
                            
;
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site_origem'),
                                //array('nome' => 'empresas', 'where' => 'contatos_site.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                //array('nome' => 'contatos_site', 'where' => 'contatos_site_origem.origem = contatos_site.origem', 'tipo' => 'INNER'),
                                //array('nome' => 'subcategorias', 'where' => 'empresas.id_subcategoria = subcategorias.id', 'tipo' => 'INNER'),
                                //array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        var_dump($data);
    	$retorno = $this->get_itens_($data,1);
    	
    	return $retorno;
    }
    
}