<?php
class Interacao_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_interacao');
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
                                array('nome' => 'empresas_interacao'),
                                );
    							
    	$data['filtro'] = 'empresas_interacao.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas_interacao.id as id, empresas_interacao.obs as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_interacao'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_interacao.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_interacao'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens($filtro = NULL, $coluna = 'empresas_interacao.data_inclusao', $ordem = 'DESC', $off_set = NULL)
    {
    	$data['coluna'] = '	
                            empresas_interacao.id as id,
                            empresas_interacao.id_empresas_ocorrencia,
                            empresas_interacao.id_empresas_status_ocorrencia,
                            DATE_FORMAT(empresas_interacao.data_inclusao, "%d-%m-%Y") as data_inclusao,
                            DATE_FORMAT(empresas_interacao.data_inclusao, "%H:%i:%s") as hora_inclusao,
                            CONCAT("DE:", DATE_FORMAT(empresas_interacao.data_retorno_inicio, "%d-%m-%Y %H:%i"), " ATÉ: ", DATE_FORMAT(empresas_interacao.data_retorno_fim, "%d-%m-%Y %H:%i") ) as data_retorno,
                            empresas_interacao.obs,
                            empresas_contato.nome as nome_contato,
                            empresas_status_ocorrencia.titulo as status,
                            usuarios.nome as nome_usuario                
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_interacao'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id = empresas_interacao.id_contato', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_interacao.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia.id = empresas_interacao.id_empresas_ocorrencia', 'tipo' => 'INNER'),                    
                                array('nome' => 'usuarios', 'where' => 'empresas_interacao.id_usuario = usuarios.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens']) ? $retorno['itens'] : NULL;
    }
   
    public function get_emails_campanha( $filtro = array(), $coluna = 'empresas_ocorrencia.id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            email_automatico.id as id,
                            email_automatico.titulo as descricao,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_pow_campanhas', 'where' => 'empresas_pow_campanhas.id_ocorrencias = empresas_ocorrencia.id', 'tipo' => 'INNER'),
                                array('nome' => 'pow_campanhas', 'where' => 'pow_campanhas.id = empresas_pow_campanhas.id_pow_campanhas', 'tipo' => 'INNER'),
                                array('nome' => 'campanhas_has_emails', 'where' => 'campanhas_has_emails.id_pow_campanhas = pow_campanhas.id', 'tipo' => 'INNER'),
                                array('nome' => 'email_automatico', 'where' => 'email_automatico.id = campanhas_has_emails.id_email_automatico', 'tipo' => 'INNER'),                
                            );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
}