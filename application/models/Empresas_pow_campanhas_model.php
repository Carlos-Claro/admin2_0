<?php
class Empresas_Pow_Campanhas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_pow_campanhas');
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
                                array('nome' => 'empresas_pow_campanhas'),
                                );
    							
    	$data['filtro'] = 'empresas_pow_campanhas.id_pow_campanhas = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas.empresa_nome_fantasia as id, pow_campanhas.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_pow_campanhas'),
                                array('nome' => 'pow_campanhas', 'where' => 'pow_campanhas.id = empresas_pow_campanhas.id_pow_campanhas','tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_pow_campanhas.id_empresas','tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_pow_campanhas'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_pow_campanhas.id_empresas', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros', 'where' => 'logradouros.id = empresas.id_logradouro', 'tipo' => 'INNER'),
                                array('nome' => 'pow_campanhas', 'where' => 'pow_campanhas.id = empresas_pow_campanhas.id_pow_campanhas', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'retorno_inicio, empresa_nome_fantasia', $ordem = 'ASC', $off_set = NULL  )
    {
        //DATE_FORMAT(MAX(empresas_interacao.data_inclusao), "%d-%m-%Y %H:%i:%s") as ultima_interacao,
        //CONCAT("DE:", DATE_FORMAT(empresas_ocorrencia.data_retorno_inicio, "%d-%m-%Y %H:%i"), " ATÉ: ", DATE_FORMAT(empresas_ocorrencia.data_retorno_fim, "%d-%m-%Y %H:%i") ) as data_retorno,
        $data['coluna'] = '
                            empresas.id as id,
                            empresas.empresa_nome_fantasia as empresa,
                            logradouros.cidade as cidade,
                            empresas.empresa_telefone as telefone,
                            usuarios.nome as usuario_nome,
                            COUNT(empresas_interacao.id_empresas_ocorrencia) as qtde_interacao,
                            DATE_FORMAT(empresas_ocorrencia.data_retorno_inicio, "%d-%m-%Y %H:%i") as retorno_inicio,
                            DATE_FORMAT(empresas_ocorrencia.data_retorno_fim, "%d-%m-%Y %H:%i") as retorno_fim,
                            ';
        
    	$data['tabela'] = array(
                                array('nome' => 'empresas_pow_campanhas'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = empresas_pow_campanhas.id_empresas', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros', 'where' => 'logradouros.id = empresas.id_logradouro', 'tipo' => 'INNER'),
                                array('nome' => 'pow_campanhas', 'where' => 'pow_campanhas.id = empresas_pow_campanhas.id_pow_campanhas', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia.id = empresas_pow_campanhas.id_ocorrencias', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER'),
                                );
        
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['group'] = 'empresas.id';
        //$data['col'] = 'data_retorno';
    	//$data['ordem'] = 'ASC';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}