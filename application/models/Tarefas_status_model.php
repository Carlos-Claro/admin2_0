<?php
class Tarefas_status_Model extends MY_Model {
	
    private $coluna = '';
    private $tabela = NULL;
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_status');
    }
    

    private function _set_colunas( $completo = FALSE )
    {
        $this->coluna = 
                'tarefas_status.titulo as titu'
                . ', tarefas_status.id as id';
        
        return $this;
    }
    
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $this->tabela = array();
        $this->tabela[] = array('nome' => 'tarefas_status', 'where' => 'tarefas.id_tarefas_status = tarefas_status.id', 'tipo' => 'INNER');
        return $this;
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
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_status.id = '.$id;
        $data['group'] = 'tarefas_status.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_status.id as id, tarefas_status.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_status'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            tarefas_status.id as id,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = $this->_set_colunas(TRUE);
    	$data['tabela'] = $this->_set_tabelas(TRUE);
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