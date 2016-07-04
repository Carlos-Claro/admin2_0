<?php
class Tarefas_projetos_iteracao_Model extends MY_Model {
    private $coluna = '';
    private $tabela = NULL;
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_projetos_iteracao');
    }
    
    private function _set_colunas( $completo = FALSE )
    {
        $coluna = 
                  'tarefas_projetos_iteracao.id as id'
                . ', tarefas_projetos_iteracao.id_tarefas_projetos as id_tarefas_projeto'
                . ', tarefas_projetos_iteracao.message as message'
                . ', tarefas_projetos_iteracao.id_usuario as id_usuario'
                . ', tarefas_projetos_iteracao.data as data'
                . ', COUNT(pai.id) as qtde_respostas'
                ;
        
        return $coluna;
    }
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $tabela = array();
        $tabela[] = array('nome' => 'tarefas_projetos_iteracao');
        $tabela[] = array('nome' => 'tarefas_projetos_iteracao pai', 'where' => 'pai.id_pai = tarefas_projetos_iteracao.id', 'tipo' => 'LEFT');
        return $tabela;
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
    	$data['filtro'] = 'tarefas_projetos_iteracao.id = '.$id;
    	$tarefa = $this->get_itens_($data);
        $retorno = isset($tarefa['itens'][0]) ? $tarefa['itens'][0] : NULL;
    	return $retorno;
    }
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_projetos_iteracao.id as id, tarefas_projetos_iteracao.message as descricao, tarefas_projetos_iteracao.data as data';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_projetos_iteracao'),
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
                            count(tarefas_projetos_iteracao.id) as qtde,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'tarefas_projetos_iteracao.id';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
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
        $data['group'] = 'tarefas_projetos_iteracao.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['off_set'] = 0;
    	$data['qtde_itens'] = 9990;
    	$retorno = $this->get_itens_($data);
        /*
        $retorno = array('itens' => NULL,'qtde' => 0);
        if ( isset($d['itens']) && $d['qtde'] > 0 )
        {
            foreach( $d['itens'] as $k => $v )
            {
                $retorno['itens'][$k] = $v;
                $retorno['itens'][$k]->itens = $this->get_itens('tarefas_projetos_iteracao.id_pai = '.$v->id);
                $retorno['qtde']++;
            }
        }
         * 
         */
        
    	return $retorno;
    }
}