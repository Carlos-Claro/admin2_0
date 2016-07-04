<?php
class Ocorrencias_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_ocorrencia');
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
        $database['table'] = 'empresas_ocorrencia_has_usuario';
        return $this->adicionar_($database, $data);
    	//$this->db->insert('empresas_ocorrencia_has_usuario', $data); 
        //return $this->db->insert_id();
    }
    
    public function get_item( $id = NULL, $coluna = 'id', $ordem = 'DESC', $off_set = NULL)
    {
        $data['coluna'] = '	
                            empresas_ocorrencia.id as id,
                            empresas_ocorrencia.id_empresa,
                            empresas_ocorrencia.id_empresa_status_ocorrencia,
                            empresas_ocorrencia.id_setor,
                            empresas_ocorrencia.id_contato,
                            empresas_ocorrencia.prioridade,
                            empresas_ocorrencia.data,
                            empresas_ocorrencia.texto,
                            empresas_interacao.data_retorno_inicio,
                            empresas_interacao.data_retorno_fim,
                            empresas.empresa_razao_social,
                            empresas_status_ocorrencia.titulo as status,
                            empresas_ocorrencia_assunto.titulo as assunto,
                            usuarios.nome as usuario_ativo,
                            empresas_contato.nome as nome_contato
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresa_status_ocorrencia = empresas_status_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id','tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'empresas_ocorrencia.id_usuario_ativo = usuarios.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id_empresa = empresas_ocorrencia.id_empresa AND empresas_contato.id = empresas_ocorrencia.id_contato','tipo' => 'LEFT')
                                );			
    	$data['filtro'] = $id;
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);

        if($retorno['qtde'] > 0)
        {
            $this->load->model('interacao_model');
            
            foreach ($retorno['itens'] as $a)
            {
                $ret[$a->id]['item'] = $a; 
                $ret[$a->id]['interacoes'] = $this->interacao_model->get_itens('empresas_interacao.id_empresas_ocorrencia = '.$a->id);
            }
        }
        else
        {
            $ret = NULL;
        }
        return $ret;
    }
	
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas_ocorrencia.id as id, empresas_ocorrencia.assunto as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_ocorrencia.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id','tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'empresas_ocorrencia.id_usuario_ativo = usuarios.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id_empresa = empresas_ocorrencia.id_empresa AND empresas_contato.id = empresas_ocorrencia.id_contato','tipo' => 'LEFT')
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'empresas_status_ocorrencia.id', $ordem = 'DESC', $off_set = NULL )
    {
        //DATE_FORMAT(empresas_ocorrencia.data, "%H:%i:%s") as hora_inicial,
        //DATE_FORMAT(empresas_ocorrencia.data, "%d-%m-%Y") as data_inicial,
        //DATEDIFF( MAX(empresas_interacao.data_inclusao), empresas_ocorrencia.data ) as dias,
        //TIMEDIFF( MAX(empresas_interacao.data_inclusao), empresas_ocorrencia.data )as horas,
        //MAX(empresas_interacao.data_inclusao) as dias,
    	$data['coluna'] = '	
                            empresas_ocorrencia.id as id,
                            empresas_ocorrencia.id_empresas_status_ocorrencia,
                            empresas_ocorrencia.id_setor,
                            empresas_ocorrencia.data,
                            empresas_ocorrencia.id_usuario_ativo as id_usuario_ativo,
                            empresas_ocorrencia.texto,
                            empresas_ocorrencia.prioridade,
                            CONCAT("DE:", DATE_FORMAT(empresas_ocorrencia.data_retorno_inicio, "%d-%m-%Y %H:%i"), " ATÉ: ", DATE_FORMAT(empresas_ocorrencia.data_retorno_fim, "%d-%m-%Y %H:%i") ) as data_retorno,
                            DATEDIFF( MAX(empresas_interacao.data_inclusao), empresas_ocorrencia.data ) as dias,
                            TIMEDIFF( MAX(empresas_interacao.data_inclusao), empresas_ocorrencia.data ) as horas,
                            empresas_status_ocorrencia.titulo as status,
                            empresas_ocorrencia_assunto.titulo as assunto,
                            usuarios.nome as usuario_ativo,
                            empresas_contato.nome as nome_contato
                            ';
    	$data['tabela'] = array(
                                    array('nome' => 'empresas_ocorrencia'),
                                    array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id','tipo' => 'INNER'),
                                    array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                    array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id','tipo' => 'INNER'),
                                    array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id','tipo' => 'INNER'),
                                    array('nome' => 'usuarios', 'where' => 'empresas_ocorrencia.id_usuario_ativo = usuarios.id','tipo' => 'INNER'),
                                    array('nome' => 'empresas_contato', 'where' => 'empresas_contato.id_empresa = empresas_ocorrencia.id_empresa AND empresas_contato.id = empresas_ocorrencia.id_contato','tipo' => 'LEFT')
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
    	}
    	$data['group'] = 'empresas_ocorrencia.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);

        if($retorno['qtde'] > 0)
        {
            $this->load->model('interacao_model');
            
            foreach ($retorno['itens'] as $a)
            {
                $ret[$a->id]['item'] = $a; 
                $ret[$a->id]['interacoes'] = $this->interacao_model->get_itens('empresas_interacao.id_empresas_ocorrencia = '.$a->id);
            }
        }
        else
        {
            $ret = NULL;
        }
        
       return $ret;
    }
    
    public function get_itens_ocorrencias( $filtro = array(), $coluna = 'empresas_ocorrencia.id_empresas_status_ocorrencia', $ordem = 'DESC', $off_set = NULL )
    {
        //CONCAT("Data: ", DATE_FORMAT(empresas_interacao.data_retorno, "%d-%m-%Y"), " Periodo: ", empresas_interacao.periodo) as data_retorno,
    	//CONCAT("DE:", DATE_FORMAT(empresas_ocorrencia.data_retorno_inicio, "%d-%m-%Y %H:%i"), " ATÉ: ", DATE_FORMAT(empresas_ocorrencia.data_retorno_fim, "%d-%m-%Y %H:%i") ) as data_retorno,
        //empresas.id as id,
        $data['coluna'] = '	
                            empresas.empresa_nome_fantasia as empresa,
                            empresas_ocorrencia.id_usuario_ativo as usuario_ativo,
                            empresas_ocorrencia.prioridade as prioridade,
                            CONCAT(empresas.id,"#",empresas_ocorrencia.id) as id,
                            DATE_FORMAT(empresas_ocorrencia.data, "%d-%m-%Y") as data_inicio,
                            empresas_ocorrencia_assunto.titulo as assunto,
                            DATE_FORMAT(empresas_ocorrencia.data_retorno_inicio, "%d-%m-%Y %H:%i") as retorno_inicio,
                            DATE_FORMAT(empresas_ocorrencia.data_retorno_fim, "%d-%m-%Y %H:%i") as retorno_fim,
                            DATE_FORMAT(MAX(empresas_interacao.data_inclusao), "%d-%m-%Y") as data_fim,
                            empresas_status_ocorrencia.titulo as status,
                            usuarios.nome as usuarios_nome,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id ','tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id ', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id ', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo ', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'empresas_ocorrencia.id';
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
        return ( (isset($retorno['qtde']) && $retorno['qtde'] > 0) ? $retorno : NULL );
    }
    
    public function get_total_itens_ocorrencias ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_ocorrencia.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id ', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id ', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo ', 'tipo' => 'INNER')
                                //array('nome' => 'empresas_ocorrencia'),
                                //array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo', 'tipo' => 'INNER')
                            );
    	$data['filtro'] = $filtro;
        $data['group'] = 'empresas_ocorrencia.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens_email_automatico($filtro = array())
    {
        $data['coluna'] = '	
                            CONCAT(empresas.id,"#",empresas_ocorrencia.id) as id,
                            empresas_ocorrencia.id as id_ocorrencia,
                            empresas_ocorrencia.data as data,
                            empresas_ocorrencia.prioridade as prioridade,
                            MAX(empresas_interacao.data_inclusao) as data_fim,
                            empresas_contato.nome as contato_nome,
                            empresas_contato.email as contato_email,
                            empresas_ocorrencia_assunto.titulo as assunto,
                            usuarios.nome as usuario_nome,
                            usuarios.email as usuario_email,
                            empresas_ocorrencia_assunto.titulo as assunto,
                            (SELECT 
                                GROUP_CONCAT( 
                                    DISTINCT CONCAT(usuario_interno.nome,"-",usuario_interno.email) 
                                    ORDER BY empresas_interacao.data_inclusao DESC SEPARATOR "," 
                                )
                            ) as usuario_interno,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas', 'where' => 'empresas_ocorrencia.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_contato', 'where' => 'empresas_ocorrencia.id_contato = empresas_contato.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas_ocorrencia_assunto', 'where' => 'empresas_ocorrencia.id_assunto = empresas_ocorrencia_assunto.id ', 'tipo' => 'INNER'),
                                array('nome' => 'empresas_status_ocorrencia', 'where' => 'empresas_ocorrencia.id_empresas_status_ocorrencia = empresas_status_ocorrencia.id ', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia.id_usuario_ativo ', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios usuario_interno', 'where' => 'usuario_interno.id = empresas_interacao.id_usuario ', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'empresas_ocorrencia.id';
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
    	}
    	//$data['col'] = $coluna;
    	//$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
        
        return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    
    
    public function get_itens_usuarios_campanhas( $filtro = array(), $coluna = 'empresas_ocorrencia.id_empresas_status_ocorrencia', $ordem = 'DESC', $off_set = NULL )
    {
        
    	$data['coluna'] = '
                            usuarios.id as id_usuario,
                            usuarios.nome as nome_usuario,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_has_usuario', 'where' => 'empresas_ocorrencia_has_usuario.id_empresas_ocorrencia = empresas_ocorrencia.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia_has_usuario.id_usuario ', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'empresas_ocorrencia.id';
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
        
        return $retorno;
    }
    
    public function get_total_itens_usuarios_campanhas ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas_ocorrencia.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_ocorrencia'),
                                array('nome' => 'empresas_interacao', 'where' => 'empresas_interacao.id_empresas_ocorrencia = empresas_ocorrencia.id','tipo' => 'INNER'),
                                array('nome' => 'empresas_ocorrencia_has_usuario', 'where' => 'empresas_ocorrencia_has_usuario.id_empresas_ocorrencia = empresas_ocorrencia.id', 'tipo' => 'INNER'),
                                array('nome' => 'usuarios', 'where' => 'usuarios.id = empresas_ocorrencia_has_usuario.id_usuario ', 'tipo' => 'INNER')
                            );
    	$data['filtro'] = $filtro;
        $data['group'] = 'empresas_ocorrencia.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    /**
     * Retorna para a agenda as tarefas abertas e pendêntes
     * @param type $usuario - Usuario da consulta
     * @param type $tipo_data - ["passado", "presente", "futuro"]
     */
    public function get_ocorrencias_abertas($id_usuario, $tipo_data = 'presente', $ordem = 'ASC')
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
                                array('nome' => 'empresas_ocorrencias'),
                                array('nome' => 'empresas_ocorrencias_has_usuarios', 'where' => 'empresas_ocorrencias_has_usuarios.id_empresas_ocorrencia = empresas_ocorrencias.id', 'tipo' => 'INNER'),
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