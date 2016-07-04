<?php
class Empresas_Contato_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresa_contato');
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
                                array('nome' => 'empresas_contato'),
                                );
    							
    	$data['filtro'] = 'empresas_contato.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_contato_por_id( $filtro = array())
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contato'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = NULL )
    {
    	$data['coluna'] = '
                            empresas_contato.id as id, 
                            IF(empresas_contato.funcao <> "", CONCAT(empresas_contato.nome, " (", empresas_contato.funcao, ") "), empresas_contato.nome) as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contato')
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_contato.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contato'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_contato.id_empresa', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            empresas_contato.id as id,
                            empresas_contato.nome as nome,
                            empresas_contato.funcao as funcao,
                            empresas_contato.id_empresa as id_empresa,
                            empresas_contato.telefone as telefone,
                            empresas_contato.email as email,
                            empresas_contato.obs as obs,
                            empresas_contato.principal as principal,
                            empresas_contato.status as status,
                            empresas.empresa_nome_fantasia as empresa,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contato'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_contato.id_empresa', 'tipo' => 'INNER'),
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
    
    
    /*
     * @deprecated 
     * Função utilizada apenas para atualizar a base de dados da empresa de contatos
     * identificando quem é o autorizador  e contato
     */
    public function get_autorizador( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            empresas_contato.id as id,
                            empresas_contato.nome as nome,
                            empresas_contato.funcao as funcao,
                            empresas_contato.id_empresa as id_empresa,
                            empresas_contato.email as email,
                            empresas_contato.principal as principal,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_contato'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_contato.id_empresa', 'tipo' => 'INNER'),
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