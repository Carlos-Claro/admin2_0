<?php
class Tarefas_tempo_Model extends MY_Model {
	
    private $coluna = '';
    private $tabela = NULL;
    
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_tempo');
    }

    private function _set_colunas( $completo = FALSE )
    {
        $this->coluna = 
                'tarefas_tempo.id as id'
                . ', tarefas_tempo.id_tarefas as id_tarefas'
                . ', tarefas_tempo.id_tarefas_atividades as id_tarefas_atividades'
                . ', tarefas_tempo.data_inicio as data_inicio'
                . ', tarefas_tempo.data_fim as data_fim'
                . ', usuarios.nome as usuario';
        
        return $this->coluna;
    }
    
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $this->tabela = array();
        $this->tabela[] = array('nome' => 'tarefas_tempo');
        $this->tabela[] = array('nome' => 'usuarios', 'where' => 'tarefas_tempo.id_usuario = usuarios.id', 'tipo' => 'LEFT');
        if ( $completo )
        {
            //$this->tabela[] = array('nome' => 'tarefas_has_usuarios', 'where' => 'tarefas.id = tarefas_has_usuarios.id_tarefas', 'tipo' => 'LEFT');
            //$this->tabela[] = array('nome' => 'tarefas_atividades', 'where' => 'tarefas.id = tarefas_atividades.id_tarefas', 'tipo' => 'LEFT');
        }
        return $this->tabela;
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
    	$data['filtro'] = 'tarefas_tempo.id = '.$id;
        $data['group'] = 'tarefas_tempo.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_tempo.id as id, tarefas_tempo.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_tempo'),
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
                            tarefas_tempo.id as id,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'tarefas_tempo.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
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