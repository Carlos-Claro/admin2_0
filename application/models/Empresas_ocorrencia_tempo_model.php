<?php
class Empresas_Ocorrencia_Tempo_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_ocorrencia_tempo');
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
                                array('nome' => 'empresas_ocorrencia_tempo'),
                                );
    							
    	$data['filtro'] = 'empresas_ocorrencia_tempo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas_ocorrencia_tempo.id as id, empresas_ocorrencia_tempo.tempo_inicio as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia_tempo'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_ocorrencia_tempo.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia_tempo'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            empresas_ocorrencia_tempo.id as id,
                            empresas_ocorrencia_tempo.id_ocorrencia as id_ocorrencia,
                            empresas_ocorrencia_tempo.id_usuario as id_usuario,
                            empresas_ocorrencia_tempo.tempo_inicio as tempo_inicio,
                            empresas_ocorrencia_tempo.tempo_fim as tempo_fim,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia_tempo'),
                                array('nome' => 'empresas_ocorrencia', 'where' => 'empresas_ocorrencia_tempo.id_ocorrencia = empresas_ocorrencia.id'),
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