<?php
class Contatos_Site_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'contatos_site');
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
    	$data['coluna'] = 'contatos_site.id as ids,
                           FROM_UNIXTIME(contatos_site.data,"%d/%m/%Y %H:%i") as data,
                           contatos_site.id_empresa as id_empresa,
                           contatos_site.nome as nome, 
                           contatos_site.email as email, 
                           contatos_site.assunto as assunto,
                           contatos_site.mensagem as mensagem, 
                           contatos_site.fone as fone, 
                           contatos_site.origem as origem,
                           contatos_site.cidade as cidade,
                           contatos_site.id_item as id_item,
                           contatos_site.id_cidade as id_cidade,
                           contatos_site.sms_enviado as sms_enviado,
                           contatos_site.estado as estado,
                           contatos_site.portal as portal,
                           contatos_site.id_tipo_item as id_tipo_item,
                           contatos_site.tipo_negocio_item as tipo_negocio_item,
                           CONCAT(contatos_site_origem.local, " (" , contatos_site_origem.origem, ")") as id_local_origem,
                           ';
        $data['tabela'] = array(
                                array('nome' => 'contatos_site'),
                                array('nome' => 'contatos_site_origem', 'where' => 'contatos_site.origem = contatos_site_origem.origem', 'tipo' => 'LEFT'),
                               //array('nome' => 'empresas', 'where' => 'contatos_site.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'contatos_site.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'contatos_site.id as id, contatos_site.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site'),
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
                            contatos_site.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site'),
                                array('nome' => 'contatos_site_origem', 'where' => 'contatos_site.origem = contatos_site_origem.origem', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas', 'where' => 'contatos_site.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            contatos_site.id as id,
                            FROM_UNIXTIME(contatos_site.data,"%d/%m/%Y %H:%i") as data,
                            contatos_site.id_empresa as id_empresa,
                            contatos_site.nome as nome, 
                            contatos_site.email as email, 
                            contatos_site.assunto as assunto,
                            contatos_site.mensagem as mensagem, 
                            contatos_site.fone as fone, 
                            contatos_site.origem as origem,
                            contatos_site.cidade as cidade,
                            contatos_site.id_item as id_item,
                            contatos_site.id_cidade as id_cidade,
                            contatos_site.sms_enviado as sms_enviado,
                            contatos_site.estado as estado,
                            contatos_site.portal as portal,
                            contatos_site.id_tipo_item as id_tipo_item,
                            contatos_site.tipo_negocio_item as tipo_negocio_item,
                            CONCAT(contatos_site_origem.local, " (" , contatos_site_origem.origem, ")") as id_local_origem,
                            empresas.empresa_nome_fantasia as empresas';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site'),
                                array('nome' => 'contatos_site_origem', 'where' => 'contatos_site.origem = contatos_site_origem.origem', 'tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'contatos_site.id_empresa = empresas.id', 'tipo' => 'LEFT'),
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
    
    public function get_itens_disparo( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = 1000, $group = 'contatos_site.email' )
    {
    	$data['coluna'] = '	
                            GROUP_CONCAT(contatos_site.id SEPARATOR ",") as id,
                            cadastros.id as id_cadastro,
                            FROM_UNIXTIME(contatos_site.data,"%d/%m/%Y %H:%i") as data,
                            contatos_site.nome as nome, 
                            contatos_site.email as email, 
                            contatos_site.cidade as cidade,
                            contatos_site_origem.tabela as tabela,
                            COUNT(contatos_site.email) as qtde_contatos,
                            GROUP_CONCAT(DISTINCT contatos_site.id_item SEPARATOR ",") as id_itens,
                            GROUP_CONCAT(DISTINCT contatos_site.id_tipo_item SEPARATOR ",") as id_tipo_item,
                            GROUP_CONCAT(DISTINCT contatos_site.id_cidade SEPARATOR ",") as cidades,
                            GROUP_CONCAT(DISTINCT imoveis_tipos.link SEPARATOR ",") as tipos_item,
                            GROUP_CONCAT(DISTINCT contatos_site.tipo_negocio_item SEPARATOR ",") as tipo_negocio_item,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site'),
                                array('nome' => 'contatos_site_origem', 'where' => 'contatos_site.origem = contatos_site_origem.origem', 'tipo' => 'INNER'),
                                array('nome' => 'cadastros', 'where' => 'contatos_site.email = cadastros.email', 'tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'contatos_site.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos', 'where' => 'contatos_site.id_tipo_item = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                //array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    		$data['qtde_itens'] = $qtde_itens;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = $group;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}