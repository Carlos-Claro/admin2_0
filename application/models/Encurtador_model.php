<?php
class Encurtador_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('encurtador'));
        $this->database = array('db' => 'encurtador', 'table' => 'encurtador');
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
                                array('nome' => 'encurtador'),
                                );
    							
    	$data['filtro'] = 'encurtador.id = '.$id;
        $data['db'] = 'encurtador';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'encurtador.id as id, encurtador.link_encaminhado as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'encurtador'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['db'] = 'encurtador';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            encurtador.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'encurtador'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
        $data['db'] = 'encurtador';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            encurtador.id as id,
                            encurtador.link_encurtado as link_encurtado,
                            encurtador.link_encaminhado as link_encaminhado,
                            count(encurtador_log.id) as acesso
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'encurtador'),
                                array('nome' => 'encurtador_log', 'where' => 'encurtador.id = encurtador_log.id_encurtador', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'encurtador.id';
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['db'] = 'encurtador';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}