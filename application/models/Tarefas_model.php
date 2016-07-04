<?php
class Tarefas_Model extends MY_Model {
	
    private $coluna = '';
    private $tabela = NULL;
    
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas');
    }

    private function _set_colunas( $completo = FALSE )
    {
        $this->coluna = 
                'tarefas.id as id'
                . ', tarefas.titulo as titulo'
                . ', tarefas.id_tarefas_projeto as id_tarefas_projeto'
                . ', tarefas_projetos.titulo as tarefas_projeto_titulo'
                . ', tarefas_portfolio.titulo as tarefas_porfolio_titulo'
                . ', tarefas_portfolio.id as id_tarefas_porfolio'
                . ', tarefas.descricao as descricao'
                . ', tarefas.data_inicio as data_inicio'
                . ', tarefas.data_fim as data_fim'
                . ', IF (NOW() > tarefas.data_fim, "danger", "warning") as classe'
                . ', tarefas.previsao_horas as previsao_horas'
                . ', tarefas.id_usuario as id_usuario'
                . ', usuarios.nome as usuario'
                . ', GROUP_CONCAT(distinct usuarios_has.nome SEPARATOR ", ") as usuarios'
                . ', GROUP_CONCAT(distinct empresas.empresa_nome_fantasia SEPARATOR ", ") as empresas'
                . ', COUNT(tarefas_atividades.id) as qtde_atividades'
                . ', tarefas_status.titulo as tarefa_status'
                . ', tarefas_status.id as id_tarefa_status';
        
        return $this->coluna;
    }
    
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $this->tabela = array();
        $this->tabela[] = array('nome' => 'tarefas');
        $this->tabela[] = array('nome' => 'tarefas_projetos',       'where' => 'tarefas.id_tarefas_projeto = tarefas_projetos.id', 'tipo' => 'INNER');
        $this->tabela[] = array('nome' => 'tarefas_portfolio',      'where' => 'tarefas_portfolio.id = tarefas_projetos.id_tarefas_portfolio', 'tipo' => 'INNER');
        $this->tabela[] = array('nome' => 'tarefas_status',         'where' => 'tarefas.id_tarefas_status = tarefas_status.id', 'tipo' => 'INNER');
        $this->tabela[] = array('nome' => 'usuarios',               'where' => 'tarefas.id_usuario = usuarios.id', 'tipo' => 'LEFT');
        $this->tabela[] = array('nome' => 'tarefas_has_usuarios',   'where' => 'tarefas.id = tarefas_has_usuarios.id_tarefas', 'tipo' => 'LEFT');
        $this->tabela[] = array('nome' => 'usuarios usuarios_has',  'where' => 'tarefas_has_usuarios.id_usuarios = usuarios_has.id', 'tipo' => 'LEFT');
        $this->tabela[] = array('nome' => 'tarefas_atividades',     'where' => 'tarefas.id = tarefas_atividades.id_tarefas', 'tipo' => 'LEFT');
        $this->tabela[] = array('nome' => 'tarefas_has_empresa',    'where' => 'tarefas.id = tarefas_has_empresa.id_tarefas', 'tipo' => 'LEFT');
        $this->tabela[] = array('nome' => 'empresas',               'where' => 'empresas.id = tarefas_has_empresa.id_empresas', 'tipo' => 'LEFT');
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
        $this->load->model('tarefas_atividades_model');
    	$data['coluna'] = $this->_set_colunas();
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = 'tarefas.id = '.$id;
        $data['group'] = 'tarefas.id';
    	$tarefa = $this->get_itens_($data);
        
        $retorno['item'] = $tarefa['itens'][0];
        $retorno['usuarios'] = $this->get_usuarios_selecionados($id);
        $retorno['empresas'] = $this->get_empresas_selecionados($id);
        $retorno['atividades'] = $this->tarefas_atividades_model->get_itens_por_tarefa($id);
        
    	return $retorno;
    }
	
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'tarefas.id as id, tarefas.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas'),
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
                            tarefas.id as id,
                            ';
    	$data['tabela'] = $this->_set_tabelas();
    	$data['filtro'] = $filtro;
        $data['group'] = 'tarefas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_total_itens_relatorios ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(tarefas.id) as id
                            ';
    	$data['tabela'][] = array('nome' => 'tarefas_tempo');
        $data['tabela'][] = array('nome' => 'tarefas',         'where' => 'tarefas_tempo.id_tarefas = tarefas.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'tarefas_atividades',         'where' => 'tarefas_tempo.id_tarefas_atividades = tarefas_atividades.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'usuarios',         'where' => 'tarefas_tempo.id_usuario = usuarios.id', 'tipo' => 'INNER');
    	if(is_array($filtro))
        {
            $data['filtro'] = $filtro;
            $data['filtro'][] = 'tarefas_tempo.data_fim is null';
        }
        else
        {
            $data['filtro'] = $filtro.'tarefas_tempo.data_fim is null';
        }
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : 0;
    }
    
    public function get_total_itens_relatorios_tarefas ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(tarefas.id) as id
                            ';
    	$data['tabela'][] = array('nome' => 'tarefas_tempo');
        $data['tabela'][] = array('nome' => 'tarefas',         'where' => 'tarefas_tempo.id_tarefas = tarefas.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'tarefas_atividades',         'where' => 'tarefas_tempo.id_tarefas_atividades = tarefas_atividades.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'usuarios',         'where' => 'tarefas_tempo.id_usuario = usuarios.id', 'tipo' => 'INNER');
    	if(is_array($filtro))
        {
            $data['filtro'] = $filtro;
            $data['filtro'][] = 'tarefas_tempo.data_inicio is not null';
        }
        else
        {
            $data['filtro'] = $filtro.'tarefas_tempo.data_inicio is not null';
        }
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : 0;
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
        $data['group'] = 'tarefas.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_relatorios( $filtro = array(), $coluna = 'tarefas.id', $ordem = 'DESC', $off_set = NULL )
    {
        /*SELECT 
	(UNIX_TIMESTAMP(tarefas.data_fim)-UNIX_TIMESTAMP(tarefas.data_inicio)) as tempo_total
	tarefas.id as id, 
	GROUP_CONCAT(distinct usuarios_has.nome SEPARATOR ", ") as nome,
	group_concat(distinct tarefas_tempo.data_inicio SEPARATOR ", ") as tempo,
	group_concat(distinct tarefas_tempo.data_fim SEPARATOR ", ") as tempo_fim*/		
    	/*$data['coluna'] = 'tarefas.id as id, 
	GROUP_CONCAT(distinct usuarios_has.nome SEPARATOR ", ") as nome,
	group_concat(distinct tarefas_tempo.data_inicio SEPARATOR ", ") as tempo,
	group_concat(distinct tarefas_tempo.data_fim SEPARATOR ", ") as tempo_fim';*/
        
        $data['coluna']= 'tarefas.id as id,
                          tarefas_atividades.id as id_atividade,
                          usuarios.id as id_usuario,
                          usuarios.nome as nome_usuario,
                          tarefas.titulo as titulo_tarefa,
                          tarefas_atividades.descricao as titulo_atividade,
                          DATE_FORMAT(tarefas_tempo.data_inicio, "%d/%m/%Y %T") as data_inicio,
                          CONCAT(tarefas.titulo, "/" , tarefas_atividades.descricao) as tarefa_atividade';
        
        
    	$data['tabela'][] = array('nome' => 'tarefas_tempo');
        $data['tabela'][] = array('nome' => 'tarefas',         'where' => 'tarefas_tempo.id_tarefas = tarefas.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'tarefas_atividades',         'where' => 'tarefas_tempo.id_tarefas_atividades = tarefas_atividades.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'usuarios',         'where' => 'tarefas_tempo.id_usuario = usuarios.id', 'tipo' => 'INNER');      
        if(is_array($filtro))
        {
            $data['filtro'] = $filtro;
            $data['filtro'][] = 'tarefas_tempo.data_fim is null';
        }
        else
        {
            $data['filtro'] = $filtro.'tarefas_tempo.data_fim is null';
        }
        
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'tarefas.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_relatorios_tarefas( $filtro = array(), $coluna = 'tarefas.id', $ordem = 'DESC', $off_set = NULL )
    {
        $data['coluna']= 'tarefas.id as id,
                          tarefas_atividades.id as id_atividade,
                          usuarios.id as id_usuario,
                          usuarios.nome as nome_usuario,
                          tarefas.titulo as titulo_tarefa,
                          tarefas_atividades.descricao as titulo_atividade,
                          DATE_FORMAT(tarefas_tempo.data_inicio, "%d/%m/%Y %H:%i") as data_inicio,
                          DATE_FORMAT(tarefas_tempo.data_fim, "%d/%m/%Y %H:%i") as data_fim,
                          CONCAT(tarefas.titulo, "/" , tarefas_atividades.descricao) as tarefa_atividade';
        
        
    	$data['tabela'][] = array('nome' => 'tarefas_tempo');
        $data['tabela'][] = array('nome' => 'tarefas',         'where' => 'tarefas_tempo.id_tarefas = tarefas.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'tarefas_atividades',         'where' => 'tarefas_tempo.id_tarefas_atividades = tarefas_atividades.id', 'tipo' => 'INNER');
        $data['tabela'][] = array('nome' => 'usuarios',         'where' => 'tarefas_tempo.id_usuario = usuarios.id', 'tipo' => 'INNER');      
        if(is_array($filtro))
        {
            $data['filtro'] = $filtro;
            $data['filtro'][] = 'tarefas_tempo.data_inicio is not null';
        }
        else
        {
            $data['filtro'] = $filtro.'tarefas_tempo.data_inicio is not null';
        }
        
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'tarefas_tempo.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function adicionar_has_usuario( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'tarefas_has_usuarios';
        return $this->adicionar_($database, $data);
    }
    
    public function excluir_has_usuario($filtro)
    {
        $database = $this->database;
        $database['table'] = 'tarefas_has_usuarios';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_usuarios_selecionados( $id = NULL )
    {
    	$data['coluna'] = 'tarefas_has_usuarios.id_usuarios as id, usuarios.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_has_usuarios'),
                                array('nome' => 'usuarios', 'where' => 'tarefas_has_usuarios.id_usuarios = usuarios.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'tarefas_has_usuarios.id_tarefas = '.$id;
    	$dados = $this->get_itens_($data);
        if ( isset($dados['itens']) )
        {
            foreach ( $dados['itens'] as $itens )
            {
                $retorno[$itens->id] = $itens->descricao;
            }
        }
        else
        {
            $retorno = NULL;
        }
    	return $retorno;
    }
    
    public function get_select_usuarios_selecionados( $id = NULL )
    {
    	$data['coluna'] = 'tarefas_has_usuarios.id_usuarios as id, usuarios.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_has_usuarios'),
                                array('nome' => 'usuarios', 'where' => 'tarefas_has_usuarios.id_usuarios = usuarios.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'tarefas_has_usuarios.id_tarefas = '.$id;
    	$dados = $this->get_itens_($data);
        $retorno = isset($dados['itens']) ? $dados['itens'] : array();
    	return $retorno;
    }
    
    public function adicionar_has_empresa( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'tarefas_has_empresa';
        return $this->adicionar_($database, $data);
    }
    
    public function excluir_has_empresa($filtro)
    {
        $database = $this->database;
        $database['table'] = 'tarefas_has_empresa';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_empresas_selecionados( $id = NULL )
    {
    	$data['coluna'] = 'tarefas_has_empresa.id_empresas as id, empresas.empresa_nome_fantasia as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_has_empresa'),
                                array('nome' => 'empresas', 'where' => 'tarefas_has_empresa.id_empresas = empresas.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'tarefas_has_empresa.id_tarefas = '.$id;
    	$data['group'] = 'empresas.id';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens']) ? $retorno['itens'] : NULL;
    }
    
    public function adicionar_visualizacao( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'tarefas_visualizacao';
        return $this->adicionar_($database, $data);
    }
    
    public function excluir_visualizacao($filtro)
    {
        $database = $this->database;
        $database['table'] = 'tarefas_visualizacao';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_visualizacao_usuario( $id = NULL )
    {
    	$data['coluna'] = 'tarefas.id as id, COUNT(tarefas_visualizacao.data_view) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'tarefas_visualizacao'),
                                array('nome' => 'tarefas',              'where' => 'tarefas_visualizacao.id_tarefas = tarefas.id', 'tipo' => 'LEFT'),
                                array('nome' => 'tarefas_has_usuarios', 'where' => 'tarefas_visualizacao.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios',             'where' => 'tarefas_visualizacao.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                
                                );
    							
    	$data['filtro'] = 'tarefas.id_usuario = '.$id;
    	$data['group'] = 'tarefas.id';
        
    	$retorno = $this->get_itens_($data,1);
    	return isset($retorno['itens']) ? $retorno['itens'] : NULL;
        
    }
    
    public function get_maior_menor_data( $id_tarefas_projeto )
    {
    	$data['coluna'] = 'MIN(tarefas.data_inicio) as data_inicio,'
                        . 'MAX(tarefas.data_fim) as data_fim,'
                        . 'tarefas.id_tarefas_projeto';
        $data['tabela'] = array(
                                array('nome' => 'tarefas'),
                                //array('nome' => 'usuarios',             'where' => 'tarefas_visualizacao.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                );
        $data['filtro'] = 'tarefas.id_tarefas_projeto = '.$id_tarefas_projeto;
        $data['group'] = 'tarefas.id_tarefas_projeto';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
        
    }
    
    public function get_maior_menor_data_por_usuario( $id_usuario )
    {
    	$data['coluna'] = 'MIN(tarefas.data_inicio) as data_inicio,'
                        . 'MAX(tarefas.data_fim) as data_fim';
        $data['tabela'] = array(
                                array('nome' => 'tarefas'),
                                array('nome' => 'tarefas_has_usuarios', 'where' => 'tarefas_has_usuarios.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios',             'where' => 'tarefas_has_usuarios.id_usuarios = tarefas.id', 'tipo' => 'INNER'),
                                );
        $data['filtro'][] = 'usuarios.id = '.$id_usuario;
        $data['filtro'][] = 'tarefas.id_tarefas_status = 1';
        $data['group'] = 'usuarios.id';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
        
    }
    
    public function get_tempo_trabalhado( $id_tarefa )
    {
    	$data['coluna'] = ''
                        . 'tarefas_tempo.data_fim as data_fim,'
                        . 'tarefas_tempo.data_inicio as data_inicio,'
                        . 'tarefas_tempo.id';
        $data['tabela'] = array(
                                array('nome' => 'tarefas_tempo'),
                                //array('nome' => 'usuarios',             'where' => 'tarefas_visualizacao.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                );
        $data['filtro'] = 'tarefas_tempo.id_tarefas = '.$id_tarefa;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens']) ? $retorno['itens'] : NULL;
        
    }
    
    
    /**
     * Retorna para a agenda as tarefas abertas e pendêntes
     * @param type $usuario - Usuario da consulta
     * @param type $tipo_data - ["passado", "presente", "futuro"]
     */
    public function get_tarefas_abertas($id_usuario, $tipo_data = 'presente', $ordem = 'ASC')
    {
    	$data['coluna'] = '
                            DATE_FORMAT(tarefas.data_inicio, "%d/%m/%Y") as data_inicio,
                            DATE_FORMAT(tarefas.data_fim, "%d/%m/%Y") as data_fim,
                            tarefas.id as id,
                            tarefas_projetos.id as id_projeto,
                            CONCAT(tarefas.titulo, ", Projeto: ", tarefas_projetos.titulo, ", ", tarefas_portfolio.titulo ) as titulo,
                            GROUP_CONCAT(usuarios.nome SEPARATOR ",") as usuarios,
                            tarefas_status.titulo as status,
                            COUNT(tarefas_atividades.id) as qtde_atividades
                            ';
        $data['tabela'] = array(
                                array('nome' => 'tarefas'),
                                array('nome' => 'tarefas_has_usuarios', 'where' => 'tarefas_has_usuarios.id_tarefas = tarefas.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios',             'where' => 'tarefas_has_usuarios.id_usuarios = usuarios.id', 'tipo' => 'INNER'),
                                array('nome' => 'tarefas_projetos ',             'where' => 'tarefas.id_tarefas_projeto = tarefas_projetos.id', 'tipo' => 'INNER'),
                                array('nome' => 'tarefas_portfolio',             'where' => 'tarefas_portfolio.id = tarefas_projetos.id_tarefas_portfolio', 'tipo' => 'INNER'),
                                array('nome' => 'tarefas_status',             'where' => 'tarefas.id_tarefas_status = tarefas_status.id', 'tipo' => 'INNER'),
                                array('nome' => 'tarefas_atividades',             'where' => 'tarefas.id = tarefas_atividades.id_tarefas', 'tipo' => 'LEFT'),
                                );
        $data['filtro'][] = 'tarefas_has_usuarios.id_usuarios = '.$id_usuario;
        $data['filtro'][] = 'tarefas_status.id = 1';
        switch ($tipo_data)
        {
            case 'passado':
                $data['filtro'][] = 'tarefas.data_inicio < NOW()';
                $data['filtro'][] = 'tarefas.data_fim < NOW()';
                break;
            case 'presente':
                $data['filtro'][] = 'tarefas.data_inicio < NOW()';
                $data['filtro'][] = 'tarefas.data_fim > NOW()';
                break;
            case 'futuro':
                $data['filtro'][] = 'tarefas.data_inicio > NOW()';
                $data['filtro'][] = 'tarefas.data_fim > NOW()';
                break;
            
        }
        $data['ordem'] = $ordem;
        $data['col'] = 'tarefas.data_inicio';
        $data['group'] = 'tarefas.id';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno) ? $retorno : NULL;
    }
    
}