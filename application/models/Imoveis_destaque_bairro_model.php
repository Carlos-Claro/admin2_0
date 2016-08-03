<?php
class Imoveis_destaque_bairro_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_destaque_bairro');
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
                            imoveis_destaque_bairro.id as id,
                            imoveis_destaque_bairro.id_empresa as id_empresa,
                            DATE_FORMAT(imoveis_destaque_bairro.data_inicio,"%d/%m/%Y %H:%i") as data_inicio,
                            DATE_FORMAT(imoveis_destaque_bairro.data_fim,"%d/%m/%Y %H:%i") as data_fim,
                            imoveis_destaque_bairro.id_tipo as id_tipo,
                            imoveis_destaque_bairro.id_cidade as id_cidade,
                            imoveis_destaque_bairro.id_bairro as id_bairro,
                            imoveis_destaque_bairro.negocio as negocio,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                array('nome' => 'empresas', 'where' => 'imoveis_destaque_bairro.id_empresa = empresas.id'),
                                );
    							
    	$data['filtro'] = 'imoveis_destaque_bairro.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_filtro( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis_destaque_bairro.id as id,
                            imoveis_destaque_bairro.id_empresa as id_empresa,
                            DATE_FORMAT(imoveis_destaque_bairro.data_inicio,"%d/%m/%Y %H:%i") as data_inicio,
                            DATE_FORMAT(imoveis_destaque_bairro.data_fim,"%d/%m/%Y %H:%i") as data_fim,
                            imoveis_destaque_bairro.id_tipo as id_tipo,
                            imoveis_destaque_bairro.id_cidade as id_cidade,
                            imoveis_destaque_bairro.id_bairro as id_bairro,
                            imoveis_destaque_bairro.negocio as negocio,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                array('nome' => 'empresas', 'where' => 'imoveis_destaque_bairro.id_empresa = empresas.id'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
        
    	return (isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE);
    }
	
    public function get_select( $filtro = array(), $coluna = 'imoveis_destaque_bairro.data_fim', $ordem = 'DESC' )
    {
    	$data['coluna'] = 'imoveis_destaque_bairro.id as id, CONCAT(imoveis_tipos.nome," - ",bairros.nome, " - até ", DATE_FORMAT(imoveis_destaque_bairro.data_fim,"%d/%m/%Y "), " - ", cidades.nome ) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_destaque_bairro.id_tipo = imoveis_tipos.id'),
                                array('nome' => 'cidades', 'where' => 'imoveis_destaque_bairro.id_cidade = cidades.id'),
                                array('nome' => 'bairros','where' => 'imoveis_destaque_bairro.id_bairro = bairros.id')
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
                            count(imoveis_destaque_bairro.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : FALSE;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'data_fim', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_destaque_bairro.id as id,
                            imoveis_destaque_bairro.id_tipo as id_tipo,
                            imoveis_tipos.nome as tipo,
                            imoveis_destaque_bairro.id_empresa as id_empresa,
                            empresas.empresa_nome_fantasia as empresa,
                            imoveis_destaque_bairro.id_cidade as id_cidade,
                            imoveis_destaque_bairro.id_bairro as id_bairro,
                            cidades.nome as cidade,
                            bairros.nome as bairro,
                            DATE_FORMAT(imoveis_destaque_bairro.data_inicio,"%d/%m/%Y %H:%i") as data_inicio,
                            DATE_FORMAT(imoveis_destaque_bairro.data_fim,"%d/%m/%Y %H:%i ") as data_fim,
                            imoveis_destaque_bairro.negocio as negocio,
                            IF ( imoveis_destaque_bairro.negocio = 1, "Venda", "Locação") as negocio_
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_destaque_bairro.id_tipo = imoveis_tipos.id'),
                                array('nome' => 'cidades', 'where' => 'imoveis_destaque_bairro.id_cidade = cidades.id'),
                                array('nome' => 'empresas', 'where' => 'imoveis_destaque_bairro.id_empresa = empresas.id'),
                                array('nome' => 'bairros', 'where' => 'imoveis_destaque_bairro.id_bairro = bairros.id'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'imoveis_destaque_bairro.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
}