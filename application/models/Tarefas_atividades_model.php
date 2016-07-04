

<?php
class Tarefas_atividades_Model extends MY_Model {
	
    private $coluna = '';
    private $tabela = NULL;
    
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_atividades');
    }

    private function _set_colunas( $completo = FALSE )
    {
        $this->coluna = 
                'tarefas_atividades.id as id'
                . ', tarefas_atividades.id_tarefas as id_tarefas'
                . ', tarefas_atividades.descricao as descricao'
                . ', tarefas_atividades.previsao_tempo as previsao_tempo'
                . ', tarefas_atividades.id_usuario as id_usuario'
                . ', tarefas_atividades.data_fim as data_fim'
                . ', tarefas_status.titulo as tarefa_status'
                . ', tarefas.id_tarefas_projeto as id_tarefas_projeto'
                . ', tarefas_status.id as id_tarefa_status';
        
        return $this->coluna;
    }
    
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $this->tabela = array();
        $this->tabela[] = array('nome' => 'tarefas_atividades');
        $this->tabela[] = array('nome' => 'tarefas_status', 'where' => 'tarefas_atividades.id_tarefas_status = tarefas_status.id', 'tipo' => 'INNER');
        $this->tabela[] = array('nome' => 'tarefas', 'where' => 'tarefas_atividades.id_tarefas = tarefas.id', 'tipo' => 'INNER');
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
        $this->load->model('tarefas_interacoes_model');
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_atividades.id = '.$id;
        $data['group'] = 'tarefas_atividades.id';
    	$retorno = $this->get_itens_($data);
        if ( isset($retorno['itens'][0]) )
        {
            $item = $retorno['itens'][0];
            $item->interacoes = $this->tarefas_interacoes_model->get_select('tarefas_interacoes.id_tarefas_atividades = '.$id);
            $item->usuarios = $this->get_select_has('tarefas_atividades_has_usuarios.id_tarefas_atividades = '.$id);
        }
        else
        {
            $item = NULL;
        }
    	return $item;
    }
	
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_atividades.id as id, tarefas_atividades.descricao as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_atividades'),
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
                            categorias.id as id,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC' )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_por_tarefa( $id_tarefas )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_atividades.id_tarefas = '.$id_tarefas;
    	$data['col'] = 'tarefas_status.id, tarefas_atividades.id ';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	if ( isset($retorno['itens']) && $retorno['qtde'] > 0 )
        {
            $this->load->model('tarefas_interacoes_model');
            foreach( $retorno['itens'] as $item )
            {
                $ret['itens'][$item->id] = $item;
                $ret['itens'][$item->id]->interacoes = $this->tarefas_interacoes_model->get_select('tarefas_interacoes.id_tarefas_atividades = '.$item->id);
                $ret['itens'][$item->id]->usuarios = $this->get_select_has('tarefas_atividades_has_usuarios.id_tarefas_atividades = '.$item->id);
                
            }
            $ret['qtde'] = $retorno['qtde'];
        }
        else
        {
            $ret = NULL;
        }
    	return $ret;
    }
    
    /**
     * has
     */
    public function adicionar_has( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'tarefas_atividades_has_usuarios';
        return $this->adicionar_($database, $data);
    }
    
    public function editar_has($data = array(),$filtro = array())
    {
        $database = $this->database;
        $database['table'] = 'tarefas_atividades_has_usuarios';
        return $this->editar_($database, $data, $filtro);
    }

    public function excluir_has($filtro)
    {
        $database = $this->database;
        $database['table'] = 'tarefas_atividades_has_usuarios';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_item_has_por_tarefas_atividades( $id = '' )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_atividades.id = '.$id;
        $data['group'] = 'tarefas_atividades.id';
    	$retorno = $this->get_itens_($data);
        if ( isset($retorno['itens'][0]) )
        {
            $item = $retorno['itens'][0];
            $item->interacoes = $this->tarefas_interacoes_model->get_select('tarefas_interacoes.id_tarefas_atividades = '.$id);
        }
        else
        {
            $item = NULL;
        }
    	return $item;
    }
	
    public function get_select_has( $filtro = array() )
    {
    	$data['coluna'] = 'usuarios.id as id, usuarios.nome as descricao, usuarios.email as email';

    	$data['coluna'] = 'usuarios.id as id, usuarios.nome as descricao, , usuarios.email as email';

    	$data['tabela'] = array(
                                array('nome' => 'tarefas_atividades_has_usuarios'),
                                array('nome' => 'usuarios', 'where' => 'tarefas_atividades_has_usuarios.id_usuarios = usuarios.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
}