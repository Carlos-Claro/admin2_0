<?php
class Tarefas_projetos_Model extends MY_Model {
    private $coluna = '';
    private $tabela = NULL;
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_projetos');
    }


    private function _set_colunas( $completo = FALSE )
    {
        $coluna = 
                'tarefas_projetos.id as id'
                . ', tarefas_projetos.id_tarefas_portfolio as id_tarefas_portfolio'
                . ', tarefas_projetos.id_setor_responsavel as id_setor_responsavel'
                . ', tarefas_projetos.id_responsavel as id_responsavel'
                . ', tarefas_portfolio.titulo as tarefas_portfolio'
                . ', tarefas_projetos.titulo as titulo'
                . ', tarefas_projetos.status_projeto as status_projeto'
                . ', tarefas_projetos.descricao as descricao'
                . ', tarefas_projetos.premissas as premissas'
                . ', tarefas_projetos.restricoes as restricoes'
                . ', tarefas_projetos.riscos_iniciais as riscos_iniciais'
                . ', tarefas_projetos.requisitos as requisitos'
                . ', tarefas_projetos.exclusao_escopo as exclusao_escopo'
                . ', tarefas_portfolio.titulo as portfolio'
                . ', usuarios.nome as responsavel'
                . ', tarefas_portfolio.titulo as portfolio'
                . ', tarefas_portfolio.id as id_tarefas_portfolio';
        return $coluna;
    }
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $tabela = array();
        $tabela[] = array('nome' => 'tarefas_projetos');
        $tabela[] = array('nome' => 'tarefas_portfolio','where' => 'tarefas_projetos.id_tarefas_portfolio = tarefas_portfolio.id', 'tipo' => 'INNER');
        $tabela[] = array('nome' => 'usuarios','where' => 'tarefas_projetos.id_responsavel = usuarios.id', 'tipo' => 'INNER');
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
    
    
    public function adicionar_has( $data = array() )
    {
    	$database = $this->database;
        $database['table'] = 'tarefas_projetos_has_usuarios';
        return $this->adicionar_($database, $data);
    }
    public function excluir_has($filtro)
    {
    	$database = $this->database;
        $database['table'] = 'tarefas_projetos_has_usuarios';
        return $this->excluir_($database, $filtro);
    }
    
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas_projetos.id = '.$id;
        $data['group'] = 'tarefas_projetos.id';
    	$tarefa = $this->get_itens_($data);
        $retorno = isset($tarefa['itens'][0]) ? $tarefa['itens'][0] : NULL;
    	return $retorno;
    }
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas_projetos.id as id, tarefas_projetos.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_projetos'),
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
                            count(tarefas_projetos.id) as qtde,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'tarefas_projetos.id';
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
        $data['group'] = 'tarefas_projetos.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    public function get_itens_has( $filtro = array(), $coluna = 'usuarios.nome', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'tarefas_projetos_has_usuarios.papel as papel'
                . ', usuarios.id as id_usuario';
    	$data['tabela'][] = array('nome' => 'tarefas_projetos_has_usuarios');
        $data['tabela'][] = array('nome' => 'tarefas_projetos','where' => 'tarefas_projetos_has_usuarios.id_tarefas_projetos = tarefas_projetos.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'usuarios','where' => 'tarefas_projetos_has_usuarios.id_usuario = usuarios.id', 'tipo' => 'INNER');
        
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'tarefas_projetos.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $this->_inicia_complementos($retorno);
    }
    //adicionar todos os model e seus metdodos , aceite, comunicacao , marcos e etc.
    private function _inicia_por_item( $item )
    {
        $retorno = $item;
        $filtro = 'tarefas_projetos.id '.$item->id_tarefas_projetos;
        $retorno->has_usuarios = $this->tarefas_projetos_has_usuarios->get_item($id);
        $retorno->aceite = $this->tarefas_projetos_aceite_model->get_itens( $filtro );
        $retorno->usuarios = $this->get_itens_has( $filtro );
        $retorno->comunicacao =  $this->tarefas_projetos_comunicacao_model->get_itens( $filtro );
        $retorno->marcos  =  $this->tarefas_projetos_marcos_model->get_itens( $filtro );
        $retorno->qualidade =  $this->tarefas_projetos_qualidade_model->get_itens( $filtro );
        $retorno->riscos =  $this->tarefas_projetos_riscos_model->get_itens( $filtro );
        return $retorno;
    }
    private function _inicia_complementos( $itens = NULL )
    {
       // $this->load->model( array('tarefas_projetos_aceite_model','tarefas_projetos_comunicacao_model','tarefas_projetos_qualidade_model','tarefas_projetos_riscos_model') );
        if ( isset($itens) )
        {
           if ( isset($itens) )
            {
                foreach( $itens as $item )
                {
                    $retorno = $this->_inicia_por_item($item); 
                }
            }
            else
            {
                $retorno = $this->_inicia_por_item($itens);
            }
        }
        else
        {
            $retorno = NULL;
        }
        return $retorno;
    }
}