<?php
class tarefas_projetos_has_usuarios_model extends MY_Model {
    private $coluna = '';
    private $tabela = NULL;
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_projetos_has_usuarios');
    }

    private function _set_colunas( $completo = FALSE )
    {
        $coluna = 
                'tarefas_projetos_has_usuarios.id_tarefas_projetos as id_tarefas_projetos'
                .',tarefas_projetos_has_usuarios.id as id'
                .',tarefas_projetos_has_usuarios.id_usuario as id_usuario'
                . ', tarefas_projetos_has_usuarios.papel as papel'
                .', usuarios.email as email';
        
        return $coluna;
    }
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $tabela = array();
        $tabela[] = array('nome' => 'tarefas_projetos_has_usuarios');
        $tabela[] = array('nome' => 'tarefas_projetos','where' => 'tarefas_projetos_has_usuarios.id_tarefas_projetos = tarefas_projetos.id', 'tipo' => 'INNER');
        $tabela[] = array('nome' => 'usuarios',         'where' =>'tarefas_projetos_has_usuarios.id_usuario = usuarios.id', 'tipo' => 'INNER');
        //$tabela[] = array('nome' => 'usuarios',         'where' => 'tarefas_projetos_comunicacao.id_responsavel = usuarios.id', 'tipo' => 'INNER');
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
    	$data['filtro'] = 'tarefas_projetos_has_usuarios.id_usuario = '.$id;
    	$tarefa = $this->get_itens_($data);
        $retorno = isset($tarefa['itens'][0]) ? $tarefa['itens'][0] : NULL;
    	return $retorno;
    }
    public function get_select( $filtro = array(), $coluna = 'papel', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_projetos_has_usuarios.id_usuario, tarefas_projetos_has_usuarios.papel';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_projetos_has_usuarios'),
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
                            count(tarefas_projetos_has_usuarios.id_usuario) as qtde,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'tarefas_projetos_has_usuaios.id_usuario';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    public function get_itens( $filtro = array(), $coluna = 'id_usuario', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'tarefas_projetos_has_usuarios.id_usuario';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
}