<?php
class Imoveis_historico_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_historico');
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
                                array('nome' => 'imoveis_historico'),
                                );
    							
    	$data['filtro'] = 'imoveis_historico.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_filtro( $filtro = NULL )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_historico'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_id_chave_por_id_empresa ( $id_empresa )
    {
    	$data['coluna'] = 'imoveis_historico.id as id, imoveis_historico.referencia as referencia';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_historico'),
                                );
    							
    	$data['filtro'] = 'imoveis_historico.id_empresa = '.$id_empresa;
    	$valor = $this->get_itens_($data);
        if ( isset($valor['itens']) )
        {
            foreach( $valor['itens'] as $item )
            {
                $retorno[$item->id] = $item->referencia;
            }
        }
        else
        {
            $retorno = NULL;
        }
    	return isset($retorno) ? $retorno : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_historico.id as id, imoveis_historico.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_historico'),
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
                            imoveis_historico.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_historico'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_historico.id as id,
                            imoveis_historico.nome as nome, 
                            imoveis_historico.negocio as venda, 
                            imoveis_historico.id_tipo as id_tipo,
                            imoveis_historico.id_cidade as id_cidade,
                            imoveis_historico.id_bairro as id_bairro,
                            imoveis_historico.id_empresa as id_empresa,
                            imoveis_historico.referencia as referencia, 
                            imoveis_historico.data_cadastro as data_cadastro, 
                            imoveis_historico.data_deleta as data_deleta, 
                            imoveis_historico.image as image,
                            imoveis_historico.views as views,
                            imoveis_historico.clicks as clicks,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
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