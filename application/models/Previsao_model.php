<?php
class Previsao_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tempo_previsao');
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
    
    public function get_item( $filtro = '')
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'tempo_previsao'),
                                );
    							
    	$data['filtro'] = $filtro;//'tempo_previsao.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_data($filtro = array())
    {
    	$data['coluna'] = '
                            tempo_previsao.id as id, 
                            tempo_previsao.dia as dia, 
                            tempo_previsao.tempo as tempo, 
                            tempo_previsao.maxima as maxima, 
                            tempo_previsao.minima as minima,
                            tempo_previsao.iuv as iuv, 
                            tempo_condicao.descricao as descricao, 
                            tempo_condicao.nome_img as img
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'tempo_previsao'),
                                array('nome' => 'tempo_condicao', 'where' => 'tempo_previsao.tempo = tempo_condicao.sigla' , 'tipo' => 'INNER'),
                                );
    	//$dia = isset($date)? $date : date('Y-m-d');				
    	$data['filtro'] = $filtro;//"tempo_previsao.dia = '".$dia."'";
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL);
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'tempo_previsao.id as id, tempo_previsao.dia as dia ';
    	$data['tabela'] = array(
                                array('nome' => 'tempo_previsao'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            tempo_previsao.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'tempo_previsao'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
 
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            tempo_previsao.id as id,
                            tempo_previsao.id_cidade as id_cidade,
                            tempo_previsao.id_condicao as id_condicao,
                            tempo_previsao.dia as dia,
                            tempo_previsao.maxima as maxima,
                            tempo_previsao.minima as uf,
                            tempo_previsao.iuv as iuv,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'tempo_previsao'),
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