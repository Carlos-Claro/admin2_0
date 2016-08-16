<?php
class Imoveis_dest_listagem_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_dest_listagem');
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
                            imoveis_dest_listagem.id as id,
                            imoveis_dest_listagem.id_empresa as id_empresa,
                            FROM_UNIXTIME(imoveis_dest_listagem.data_ini,"%d/%m/%Y %H:%i") as data_ini,
                            FROM_UNIXTIME(imoveis_dest_listagem.data_fim,"%d/%m/%Y %H:%i") as data_fim,
                            imoveis_dest_listagem.id_tipo as id_tipo,
                            imoveis_dest_listagem.id_cidade as id_cidade,
                            imoveis_dest_listagem.negocio as negocio,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_dest_listagem'),
                                array('nome' => 'empresas', 'where' => 'imoveis_dest_listagem.id_empresa = empresas.id'),
                                );
    							
    	$data['filtro'] = 'imoveis_dest_listagem.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'imoveis_dest_listagem.data_fim', $ordem = 'DESC' )
    {
    	$data['coluna'] = 'imoveis_dest_listagem.id as id, CONCAT(imoveis_tipos.nome, " - até ", FROM_UNIXTIME(imoveis_dest_listagem.data_fim,"%d/%m/%Y "), " - ", cidades.nome ) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_dest_listagem'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_dest_listagem.id_tipo = imoveis_tipos.id'),
                                array('nome' => 'cidades', 'where' => 'imoveis_dest_listagem.id_cidade = cidades.id'),
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
                            count(imoveis_dest_listagem.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_dest_listagem'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = '';
    	$retorno = $this->get_itens_($data);
        
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'imoveis_dest_listagem.data_fim', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_dest_listagem.id as id,
                            imoveis_dest_listagem.id_tipo as id_tipo,
                            imoveis_tipos.nome as tipo,
                            imoveis_dest_listagem.id_empresa as id_empresa,
                            empresas.empresa_nome_fantasia as empresa,
                            imoveis_dest_listagem.id_cidade as id_cidade,
                            cidades.nome as cidade,
                            FROM_UNIXTIME(imoveis_dest_listagem.data_ini,"%d/%m/%Y %H:%i") as data_ini,
                            FROM_UNIXTIME(imoveis_dest_listagem.data_fim,"%d/%m/%Y %H:%i ") as data_fim,
                            imoveis_dest_listagem.negocio as negocio,
                            IF ( imoveis_dest_listagem.negocio = 1, "Venda", "Locação") as negocio_
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_dest_listagem'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_dest_listagem.id_tipo = imoveis_tipos.id'),
                                array('nome' => 'cidades', 'where' => 'imoveis_dest_listagem.id_cidade = cidades.id'),
                                array('nome' => 'empresas', 'where' => 'imoveis_dest_listagem.id_empresa = empresas.id'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'imoveis_dest_listagem.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}