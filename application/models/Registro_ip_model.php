<?php
class Registro_ip_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'registro_ip');
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
    
    public function truncate()
    {
        $this->db->truncate('registro_ip');
        return true;
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
                                );
    							
    	$data['filtro'] = 'registro_ip.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_item_robot( $filtro )
    {
    	$data['coluna'] = '
                            registro_ip.id as id,
                            registro_ip.ip as ip, 
                            registro_ip.user_agent as user_agent, 
                            count(registro_ip.ip) as soma';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
                                array('nome' => 'ip_robot', 'where' => 'ip_robot.ip = registro_ip.ip', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['group'] = 'registro_ip.ip';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'registro_ip.id as id, registro_ip.ip as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'registro_ip.ip';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            registro_ip.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            registro_ip.id as id,
                            registro_ip.data as data,
                            registro_ip.ip as ip,
                            registro_ip.user_agent as user_agent,
                            registro_ip.obs as obs,
                            registro_ip.robot as robot,
                            registro_ip.url as url
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
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