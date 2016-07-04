<?php
class Pow_Campanhas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'pow_campanhas');
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
    
    public function adicionar_has_emails( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'campanhas_has_emails';
        return $this->adicionar_($database, $data);
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'pow_campanhas'),
                                );
    							
    	$data['filtro'] = 'pow_campanhas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'pow_campanhas.id as id, pow_campanhas.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'pow_campanhas'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            pow_campanhas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'pow_campanhas'),
                                array('nome' => 'empresas_pow_campanhas', 'where' => 'empresas_pow_campanhas.id_pow_campanhas = pow_campanhas.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id', 'tipo' => 'INNER'),                                
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),                                
                                //array('nome' => 'empresas_ocorrencia_has_usuario', 'where' => 'empresas_ocorrencia_has_usuario.id_usuario = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),                    
                                //array('nome' => 'empresas_ocorrencia ocorrencia', 'where' => 'ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'LEFT'),
                                //array('nome' => 'empresas', 'where' => 'empresas.id = empresas_ocorrencia.id_empresa', 'tipo' => 'LEFT'),
                                //array('nome' => 'usuarios', 'where' => 'usuarios.id = ocorrencia.id_usuario_ativo', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = ' DISTINCT	
                            pow_campanhas.id as id, 
                            pow_campanhas.titulo as titulo,
                            pow_campanhas.descricao as descricao,
                            DATE_FORMAT(pow_campanhas.data_inicio, "%d-%m-%Y %H:%i:%s") as data_inicio,
                            DATE_FORMAT(pow_campanhas.data_fim, "%d-%m-%Y %H:%i:%s") as data_fim,
                            pow_campanhas.meta as meta,
                            COUNT(empresas_interacao.id_empresas_ocorrencia) as qtde_interacao 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'pow_campanhas'),
                                array('nome' => 'empresas_pow_campanhas', 'where' => 'empresas_pow_campanhas.id_pow_campanhas = pow_campanhas.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),
                                //array('nome' => 'empresas_ocorrencia_has_usuario', 'where' => 'empresas_ocorrencia_has_usuario.id_usuario = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),
                                //array('nome' => 'empresas', 'where' => 'empresas.id = empresas_ocorrencia.id_empresa', 'tipo' => 'INNER'),
                                //array('nome' => 'empresas_ocorrencia ocorrencia', 'where' => 'ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'LEFT'),
                                //array('nome' => 'usuarios', 'where' => 'usuarios.id = ocorrencia.id_usuario_ativo', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        //$data['group'] = 'empresas_interacao.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens']) && $retorno['itens'][0]->qtde_interacao > 0) ? $retorno : NULL;
    }
    
}