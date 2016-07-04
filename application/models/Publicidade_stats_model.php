<?php
class Publicidade_stats_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'publicidade_stats');
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
    	$data['coluna'] = '
                            publicidade_stats.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
    							
    	$data['filtro'] = 'publicidade_stats.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'descricao', $ordem = 'DESC' )
    {
    	$data['coluna'] = 'DISTINCT CONCAT( publicidade_stats.ano, "-", publicidade_stats.mes) as id, CONCAT( publicidade_stats.ano, "-", publicidade_stats.mes) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'descricao';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(publicidade_stats.id) as qtde,
                            CONCAT( publicidade_stats.ano, "-", publicidade_stats.mes) as referencia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'referencia';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'publicidade_stats.ano DESC, publicidade_stats.mes DESC', $ordem = '', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            publicidade_stats.id as id,
                            publicidade_stats.id_campanha as id_campanha,
                            publicidade_stats.ano as ano,
                            publicidade_stats.mes as mes,
                            CONCAT( publicidade_stats.ano, "-", publicidade_stats.mes) as referencia,
                            SUM(publicidade_stats.views) as views,
                            SUM(publicidade_stats.clicks) as clicks,
                            publicidade_stats.robot as robot,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'referencia';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}