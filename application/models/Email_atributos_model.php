<?php
class Email_atributos_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('email_mkt'));		
        $this->database = array('db' => 'email_mkt', 'table' => 'email_atributos');
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
                                array('nome' => $this->database['table']),
                                );
    							
    	$data['filtro'] = 'email_atributos.id = '.$id;
        $data['db'] = $this->database['db'];
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            email_atributos.id as id, 
                            email_atributos.chave as descricao ,
                            email_atributos.valor as valor 
                            ';
    	$data['tabela'] = array(
                                array('nome' => $this->database['table']),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['db'] = $this->database['db'];
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            email_atributos.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => $this->database['table']),
                                //array('nome' => 'email_has_regra', 'where' => 'email.id = email_has_regra.id_email', 'tipo' => 'LEFT'),
                                //array('nome' => 'email_regras', 'where' => 'email_regras.id = email_has_regra.id_email_regras', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'email_atributos.id';
        $data['db'] = $this->database['db'];
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = ''
                . 'email_atributos.id as id'
                . ', email_atributos.chave as chave'
                . ', email_atributos.valor as valor'
                . ', email_has_atributos.complemento as complemento'
                ;
    	$data['tabela'] = array(
                                array('nome' => $this->database['table']),
                                array('nome' => 'email_has_atributos', 'where' => 'email_atributos.id = email_has_atributos.id_email_atributo', 'tipo' => 'LEFT'),
                                //array('nome' => 'email_regras', 'where' => 'email_regras.id = email_has_regra.id_email_regras', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'email_atributos.id';
        $data['db'] = $this->database['db'];
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
}