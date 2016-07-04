<?php
class Planos_pi_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'planos_pi');
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
                                array('nome' => 'planos_pi'),
                                );
    							
    	$data['filtro'] = 'planos_pi.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'planos_pi.id as id, CONCAT( '
                . '( 
				IF (planos_pi.status = 0 , "Inativo - ", "Ativo - ") 
			), 
			
			( 
				IF ( 
					planos_pi.portal = 100, 
					" Imoveis ", 
					IF( 
						planos_pi.portal = 200 , 
						" Institucional ", 
						" E-commerce " 
						)
					)
		),'
                . ''
                . ''
                . '"Qtde: ", planos_pi.imoveis, ", Fotos: ", planos_pi.fotos, " ", planos_pi.observacao, " - ", planos_pi.valor) as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_pi'),
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
                            count(planos_pi.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_pi'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            planos_pi.id as id,
                            planos_pi.fotos as fotos,
                            planos_pi.imoveis as imoveis,
                            planos_pi.valor as valor,
                            planos_pi.portal as portal,
                            planos_pi.observacao as observacao,
                            planos_pi.status as status,
                            planos_pi.id_tabela as id_tabela,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'planos_pi'),
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