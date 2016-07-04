<?php
class Tarefas_projetos_status_Model extends MY_Model {
	
    private $coluna = '';
    private $tabela = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    private function _set_colunas( $completo = FALSE )
    {
        $this->coluna = 
                'tarefas_projetos_status.titulo as titulo'
                . ', tarefas_projetos_status.id as id';
        
        return $this;
    }
    
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $this->tabela = array();
        $this->tabela[] = array('nome' => 'tarefas_projetos_status');
        return $this;
    }
    
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_projetos_status.id = '.$id;
        $data['group'] = 'tarefas_projetos_status.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_projetos_status.id as id, tarefas_projetos_status.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_projetos_status'),
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
                            tarefas_projetos_status.id as id,
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