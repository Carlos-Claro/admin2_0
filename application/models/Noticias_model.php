<?php
class Noticias_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'noticias');
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
    	$data['coluna'] = 'noticias.*'
                        . ', FROM_UNIXTIME(noticias.data,"%Y") as ano'
                        . ', FROM_UNIXTIME(noticias.data,"%m") as mes';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$id;
    	$item = $this->get_itens_($data);
        if ( isset($item['itens'][0]) )
        {
            $retorno = $item['itens'][0];
            $images = $this->images_model->get_arquivo_por_tipo(12,$retorno->id);
            if ( isset($images['itens']) && $images['qtde'] > 0 )
            {
                $retorno->images = $images['itens'];
            }
            $this->load->model('logs_dia_model');
            $filto_logs = 'id_local = 83 and id_tabela = '.$id;
            $retorno->logs = $this->logs_dia_model->get_itens_group_data('noticias', $filto_logs);
            $retorno->logs_totais = $this->logs_dia_model->get_total_views_clicks('noticias', $filto_logs);
        }
        else
        {
            $retorno = NULL;
        }
        
        
    	return $retorno;
    }
    
    public function get_item_pow( $id = '' )
    {
    	$data['coluna'] = 'noticias.*'
                        . ', FROM_UNIXTIME(noticias.data,"%Y") as ano'
                        . ', FROM_UNIXTIME(noticias.data,"%m") as mes';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$id;
    	$item = $this->get_itens_($data);
        if ( isset($item['itens'][0]) )
        {
            $retorno = $item['itens'][0];
            $images = $this->images_model->get_arquivo_por_tipo(33,$retorno->id);
            if ( isset($images['itens']) && $images['qtde'] > 0 )
            {
                $retorno->images = $images['itens'];
            }
        }
        else
        {
            $retorno = NULL;
        }
        
        
    	return $retorno;
    }
    
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'noticias.id as id, noticias.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
     public function get_max_id( )
    {
    	$data['coluna'] = 'MAX(noticias.id) as id';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    							
    	//$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(noticias.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = noticias.id_categoria', 'tipo' => 'LEFT'),
                                array('nome' => 'editorias', 'where' => 'editorias.id = noticias.id_editoria', 'tipo' => 'LEFT'),
                                array('nome' => 'noticias_tipo_area', 'where' => 'noticias.tipo_area = noticias_tipo_area.id', 'tipo' => 'LEFT'),
                                array('nome' => 'canais', 'where' => 'noticias.id_canais = canais.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'data_unix', $ordem = 'DESC', $off_set = NULL, $limit = N_ITENS  )
    {
    	$data['coluna'] = '	
                            noticias.id as id,
                            noticias.titulo as titulo,
                            FROM_UNIXTIME(noticias.data,"%d/%m/%Y %H:%i") as data,
                            noticias.data as data_unix,
                            categorias.nome as categoria,
                            editorias.nome as editoria,
                            noticias_tipo_area.nome as tipo_area,
                            canais.titulo as canais,
                            canais_noticias.nome as canais_noticias,
                            IF ( noticias.vitrine = 1, "V", "X") as vitrine, 
                            IF ( noticias.vitrine_canal = 1, "V", "X") as vitrine_canal, 
                            SUM(noticias_visualizacao.views) as qtde_visualizacao,
                            IF ( usuarios.nome IS NULL, "", CONCAT(usuarios.nome, " - ",noticias_has_usuarios.data, " - ",noticias_has_usuarios.acao) ) as log, 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = noticias.id_categoria', 'tipo' => 'LEFT'),
                                array('nome' => 'editorias', 'where' => 'editorias.id = noticias.id_editoria', 'tipo' => 'LEFT'),
                                array('nome' => 'noticias_tipo_area', 'where' => 'noticias.tipo_area = noticias_tipo_area.id', 'tipo' => 'LEFT'),
                                array('nome' => 'noticias_has_usuarios', 'where' => 'noticias.id = noticias_has_usuarios.id_noticia', 'tipo' => 'LEFT'),
                                array('nome' => 'usuarios', 'where' => 'noticias_has_usuarios.id_usuario = usuarios.id', 'tipo' => 'LEFT'),
                                array('nome' => 'canais', 'where' => 'noticias.id_canais = canais.id', 'tipo' => 'LEFT'),
                                array('nome' => 'canais_noticias', 'where' => 'noticias.id_canal = canais_noticias.id', 'tipo' => 'LEFT'),
                                array('nome' => 'noticias_visualizacao', 'where' => 'noticias.id = noticias_visualizacao.id_noticia', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
                $data['qtde_itens'] = $limit;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'noticias.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function adicionar_has( $data = array() )
    {
    	$database = $this->database;
        $database['table'] = 'noticias_has_usuarios';
        return $this->adicionar_($database, $data);
    }
    public function excluir_has($filtro)
    {
    	$database = $this->database;
        $database['table'] = 'noticias_has_usuarios';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_itens_has( $filtro = array(), $coluna = 'noticias_has_usuarios.id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = ''
                . 'noticias_has_usuarios.acao as acao'
                . ', usuarios.nome as nome'
                . ', noticias_has_usuarios.data as data'
                . ', usuarios.id as id_usuario'
                ;
    	$data['tabela'][] = array('nome' => 'noticias_has_usuarios');
        $data['tabela'][] = array('nome' => 'usuarios','where' => 'noticias_has_usuarios.id_usuario = usuarios.id', 'tipo' => 'INNER');
        
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
        $data['group'] = 'noticias_has_usuarios.id';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
}