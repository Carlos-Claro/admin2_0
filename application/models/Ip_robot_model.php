<?php
class Ip_robot_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'ip_robot');
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
                                array('nome' => 'ip_robot'),
                                );
    							
    	$data['filtro'] = 'ip_robot.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_ip( $ip = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'ip_robot'),
                                );
    							
    	$data['filtro'] = 'ip_robot.ip = "'.$ip.'"';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'ip_robot.id as id, ip_robot.ip as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'ip_robot'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'ip_robot.ip';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            ip_robot.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'ip_robot'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            ip_robot.id as id,
                            ip_robot.ip as ip,
                            ip_robot.faq as faq,
                            ip_robot.descricao as descricao,
                            ip_robot.data_inclusao
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'ip_robot'),
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