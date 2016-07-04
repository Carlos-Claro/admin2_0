<?php
class Planos_sites_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'planos_sites');
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
                                array('nome' => 'planos_sites'),
                                );
    							
    	$data['filtro'] = 'planos_sites.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'planos_sites.id as id, '
                . 'CONCAT(
			( 
				IF (planos_sites.status = 0 , "Inativo - ", "Ativo - ") 
			), 
			
			( 
				IF ( 
					planos_sites.tipo = 100, 
					" Imoveis ", 
					IF( 
						planos_sites.tipo = 200 , 
						" Institucional ", 
						" E-commerce " 
						)
					)
		),planos_sites.nome)  as descricao
                 ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_sites'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'descricao';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            planos_sites.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_sites'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            planos_sites.id as id,
                            planos_sites.nome as titulo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_sites'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}