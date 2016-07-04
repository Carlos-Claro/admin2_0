<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version 1.0
 * @access public
 * @package canais
 */
class Estatisticas extends MY_Controller 
{
        /**
         * Constroi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct(FALSE);
	}
        
        /**
         * seta a classe listar
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->listar();
	}
        
        /**
         * 
         * @version 1.0
         * @access public
         * @todo interligar controllers a consulta
         * @todo criar tabelas de ligação para imoveis has empresas em pow3
         */
	public function listar( $controller = FALSE, $id = FALSE, $tipo_retorno = 'return' )
	{
            if ( ! ( isset($controller) && isset($id) ) )
            {
                die('Acesso não autorizado, consulte o administrador, Erro 0253 - Falta elemento de critério.');
            }
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$tipo_retorno.'/';
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tabela'] = $id;
            $valores['tabela'] = $controller;
            $model_controller = $controller.'_model';
            $this->load->model(array($model_controller,'logs_dia_model'));
            $item = $this->{$model_controller}->get_item($id);
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens_total_views_clicks = $this->logs_dia_model->get_total_views_clicks($controller, $filtro->get_filtro());
            $itens_total = $this->logs_dia_model->get_total_group_data($controller, $filtro->get_filtro());
            $itens_dia = $this->logs_dia_model->get_itens_group_data($controller, $filtro->get_filtro(), $off_set);
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($itens_total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$tipo_retorno.'/'.$filtro->get_url();
            $extras['col'] = 'referencia';
            $extras['ordem'] = 'DESC'; 
            $extras['item'] = $item; 
            $extras['controller'] = $controller; 
            $extras['itens_totais'] = $itens_total;
            $extras['totais'] = $itens_total_views_clicks; 
            $data['listagem'] = $this->_inicia_listagem( $itens_dia, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/estatisticas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/jquery.flot.js', TRUE)
                        ->set_include('css/datepicker/datepicker.css', TRUE)
                        ->set_include('js/datepicker/bootstrap-datepicker.js', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('estatisticas', 'estatisticas', 1)
                        ->set_usuario()
                        //->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data, 'layout/sem_menu');	
	}
	
        /**
         * exportar uma lista canais para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->canais_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}

        /**
         * Cria uma lista de canais no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
	private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE, $lista = FALSE )
	{
            $this->load->library('listagem_etiqueta');
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                    
                    $data['cabecalho'] = array(
                                                    array( 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'data',       'titulo' => 'Data',             'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? ' '.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'dayweek',    'titulo' => 'Dia da Semana',    'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => 'set_dayweek' ),
                                                        (object)array( 'chave' => 'views',      'titulo' => 'Views',            'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => 'set_number_format'),
                                                        (object)array( 'chave' => 'clicks',     'titulo' => 'Clicks',           'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => 'set_number_format'),
                                                    )),
                                                    array( 'classe' => 'espaco-proximo', 'itens' => array(
                                                    )),
                                                );
			
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Ver', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-equalizer"></span>'),
                                                    );
			$data['chave'] = 'id';
			$data['qtde_por_linha'] = 1;
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        //var_dump($extras['item']);
                        
                        $data['titulo'] = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $data['titulo'] .= '<strong>Estatisticas por '.$extras['controller'].' </strong>- '.( isset($extras['item']->empresa_nome_fantasia) ? $extras['item']->empresa_nome_fantasia : ( isset($extras['item']->nome) ? $extras['item']->nome : $extras['item']->titulo ) );
                        $data['titulo'] .= '</div>';
                        $data['titulo'] .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $data['titulo'] .= '<span class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Totais em '.$extras['itens_totais'].' em dias</strong></span>';
                        $data['titulo'] .= '<span class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><strong>Views: </strong>'.number_format($extras['totais']['itens'][0]->views, 0, ',', '.').'</span>';
                        $data['titulo'] .= '<span class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><strong>Clicks: </strong>'.number_format($extras['totais']['itens'][0]->clicks, 0, ',', '.').'</span>';
                        $data['titulo'] .= '</div>';
                        
			
                        $this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		}
		return $retorno;
	}
	
	
        /**
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array(), $filtros = FALSE )
	{
            if ( $filtros )
            {
                foreach( $filtros as $array )
                {
                    if ( isset($array->id) )
                    {
                        $config['itens'][] = array( 'name' => $array->id,              'titulo' => $array->descricao,             'tipo' => $array->tipo, 'valor' => $array->valor, 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => $array->id, 	'valor' => '' ) );
                    }
                }
            }
            else
            {
                /*
                if ( isset($valores['data_inicio']) )
                {
                    $valores['data_inicio'] = converte_data_mysql($valores['data_inicio']);
                }
                if ( isset($valores['data_fim']) )
                {
                    $valores['data_fim'] = converte_data_mysql($valores['data_fim']);
                }
                 * 
                 */
                $config['itens'] = array(
                                        array( 'name' => 'data_inicio',         'titulo' => 'Data inicio: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default datepicker data_db',  'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.data >= ',     'valor' => '' ) ),
                                        array( 'name' => 'data_fim',            'titulo' => 'Data fim: ',                'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default datepicker data_db',               'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.data <= ',     'valor' => '' ) ),
                                        array( 'name' => 'tabela',              'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'locais.tabela',     'valor' => '' ) ),
                                        array( 'name' => 'id_tabela',           'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.id_tabela',     'valor' => '' ) ),
                                        );	
                
            }
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
	
        /**
         * @version 1.0
         * @access public
         */
	public function listar_dia( $controller = FALSE, $id = FALSE, $dia = FALSE )
	{
            if ( ! ( isset($controller) && isset($id) && isset($id) ) )
            {
                die('Acesso não autorizado, consulte o administrador, Erro 0253 - Falta elemento de critério.');
            }
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tabela'] = $id;
            $valores['data'] = $dia;
            $valores['tabela'] = $controller;
            $model_controller = $controller.'_model';
            $this->load->model(array($model_controller,'logs_dia_model'));
            $item = $this->{$model_controller}->get_item($id);
            $filtro = $this->_inicia_filtros_item( '', $valores );
            $itens_dia = $this->logs_dia_model->get_itens_group_locais($controller,$filtro->get_filtro());
            $get_url = $filtro->get_url();
            $url = ''.( (empty($get_url) ) ? '?' : $get_url );
            //$data['paginacao'] = $this->init_paginacao($itens['total'], $url);
            //$data['filtro'] = $filtro->get_html();
            /*
            $this->load->library('gnuplot_base');
            $data_grafico = (object)array(
                                'item' => $item, 
                                'itens' => $itens_dia,
                                'tipo' => 'histogram',
                                'images' => '',
                                );
            $grafico = $this->gnuplot_base->get_views_clicks_por_data($data_grafico);
            var_dump($grafico);
            */
            
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$filtro->get_url();
            $extras['col'] = 'referencia';
            $extras['ordem'] = 'DESC'; 
            $data['listagem'] = $this->_inicia_listagem_item( $itens_dia, $extras );
            $lista = $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/estatisticas.js', TRUE)
                        
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('estatisticas', 'estatisticas', 1)
                        ->set_usuario()
                        ->view('listar',$data, 'layout/sem_menu', TRUE);	
            echo $lista;
	}
        
        /**
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros_item($url = '', $valores = array(), $filtros = FALSE )
	{
            if ( $filtros )
            {
                foreach( $filtros as $array )
                {
                    if ( isset($array->id) )
                    {
                        $config['itens'][] = array( 'name' => $array->id,              'titulo' => $array->descricao,             'tipo' => $array->tipo, 'valor' => $array->valor, 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => $array->id, 	'valor' => '' ) );
                    }
                }
            }
            else
            {
                $config['itens'] = array(
                                        array( 'name' => 'data',         'titulo' => 'Data: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default data',  'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.data',     'valor' => '' ) ),
                                        array( 'name' => 'tabela',              'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'locais.tabela',     'valor' => '' ) ),
                                        array( 'name' => 'id_tabela',           'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.id_tabela',     'valor' => '' ) ),
                                        );	
                
            }
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
	private function _inicia_listagem_item( $itens, $extras = NULL, $exportar = FALSE, $lista = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                    
                    
                        if ( ! $lista )
                            {
                                $lista = array(
                                                //(object)array('id' => 'nome',   'descricao' => 'nome',          'acao' => FALSE ),
                                                (object)array('id' => 'descricao','descricao' => 'descricao', 'acao' => FALSE ),
                                                (object)array('id' => 'views',  'descricao' => 'views',         'acao' => FALSE ),
                                                (object)array('id' => 'clicks', 'descricao' => 'clicks',        'acao' => FALSE ),
                                                );
                            }

                        foreach ( $lista as $cabecalho )
                            {
                                $data['cabecalho'][] = (object)array( 
                                                                        'chave'     => $cabecalho->id, 
                                                                        'acao'      => $cabecalho->acao,    
                                                                        'titulo'    => $cabecalho->descricao,       
                                                                        'link'      => str_replace(array('[col]','[ordem]'), array($cabecalho->id,( ($extras['ordem'] == 'ASC' && $extras['col'] == $cabecalho->id) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $cabecalho->id ) ? 'ui-state-highlight'.( ($extras['col'] == $cabecalho->id && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) 
                                                                    );
                            }
                    
			//$data['chave'] = 'id';
			$data['qtde_por_linha'] = 1;
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
        
        /**
         * 
         * @version 1.0
         * @access public
         * @todo interligar controllers a consulta
         * @todo criar tabelas de ligação para imoveis has empresas em pow3
         */
	public function consolidado( $controller = FALSE, $id = FALSE, $tipo_retorno = 'return' )
	{
            if ( ! ( isset($controller) && isset($id) ) )
            {
                die('Acesso não autorizado, consulte o administrador, Erro 0253 - Falta elemento de critério.');
            }
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$tipo_retorno.'/';
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tabela'] = $id;
            $valores['tabela'] = $controller;
            $model_controller = $controller.'_model';
            $this->load->model(array($model_controller,'logs_dia_model'));
            $item = $this->{$model_controller}->get_item($id);
            $filtro = $this->_inicia_filtros_consolidado( $url, $valores );
            $itens_total_views_clicks = $this->logs_dia_model->get_total_views_clicks($controller, $filtro->get_filtro());
            $itens_total = $this->logs_dia_model->get_total_group_locais($controller, $filtro->get_filtro());
            $itens_dia = $this->logs_dia_model->get_itens_group_locais( $controller, $filtro->get_filtro(), $off_set);
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            //$data['paginacao'] = $this->init_paginacao($itens_total, $url);
            //$data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$tipo_retorno.'/'.$filtro->get_url();
            $extras['col'] = 'referencia';
            $extras['ordem'] = 'DESC'; 
            $extras['item'] = $item; 
            $extras['controller'] = $controller; 
            $extras['itens_totais'] = $itens_total;
            $extras['totais'] = $itens_total_views_clicks; 
            $data['listagem'] = $this->_inicia_listagem_consolidado( $itens_dia, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/estatisticas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('css/datepicker/datepicker.css', TRUE)
                        ->set_include('js/datepicker/bootstrap-datepicker.js', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('estatisticas', 'estatisticas', 1)
                        ->set_usuario()
                        //->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data, 'layout/sem_menu');	
	}
	
        /**
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros_consolidado($url = '', $valores = array(), $filtros = FALSE )
	{
            if ( $filtros )
            {
                foreach( $filtros as $array )
                {
                    if ( isset($array->id) )
                    {
                        $config['itens'][] = array( 'name' => $array->id,              'titulo' => $array->descricao,             'tipo' => $array->tipo, 'valor' => $array->valor, 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => $array->id, 	'valor' => '' ) );
                    }
                }
            }
            else
            {
                $config['itens'] = array(
                                        //array( 'name' => 'id_locais',           'titulo' => 'Data: ',           'tipo' => 'select', 'valor' => '', 'classe' => 'form-control ui-state-default data',  'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.data',     'valor' => '' ) ),
                                        array( 'name' => 'tabela',              'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'locais.tabela',     'valor' => '' ) ),
                                        array( 'name' => 'id_tabela',           'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.id_tabela',     'valor' => '' ) ),
                                        );	
                
            }
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
	private function _inicia_listagem_consolidado( $itens, $extras = NULL, $exportar = FALSE, $lista = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                   
                    $data['cabecalho'] = array(
                                                    array( 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'descricao',    'titulo' => 'Local',    'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => FALSE ),
                                                        (object)array( 'chave' => 'views',      'titulo' => 'Views',            'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => 'set_number_format'),
                                                        (object)array( 'chave' => 'clicks',     'titulo' => 'Clicks',           'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  'acao' => 'set_number_format'),
                                                    )),
                                                    array( 'classe' => 'espaco-proximo', 'itens' => array(
                                                    )),
                                                );
			
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Expandir', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-equalizer"></span>'),
                                                    );
			$data['chave'] = 'id';
			$data['qtde_por_linha'] = 1;
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        //var_dump($extras['item']);
                        
                        $data['titulo'] = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $data['titulo'] .= '<strong>Estatisticas por '.$extras['controller'].' </strong>- '.( isset($extras['item']->empresa_nome_fantasia) ? $extras['item']->empresa_nome_fantasia : ( isset($extras['item']->nome) ? $extras['item']->nome : $extras['item']->titulo ) );
                        $data['titulo'] .= '</div>';
                        $data['titulo'] .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $data['titulo'] .= '<span class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong>Totais em '.$extras['itens_totais'].' em locais</strong></span>';
                        $data['titulo'] .= '<span class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><strong>Views: </strong>'.number_format($extras['totais']['itens'][0]->views, 0, ',', '.').'</span>';
                        $data['titulo'] .= '<span class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><strong>Clicks: </strong>'.number_format($extras['totais']['itens'][0]->clicks, 0, ',', '.').'</span>';
                        $data['titulo'] .= '</div>';
                        
                        $this->load->library('listagem_etiqueta');
                        $this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		}
		return $retorno;
	}
	
        
	
        /**
         * @version 1.0
         * @access public
         */
	public function listar_dia_por_locais( $controller = FALSE, $id = FALSE, $local = FALSE )
	{
            if ( ! ( isset($controller) && isset($id) && isset($id) ) )
            {
                die('Acesso não autorizado, consulte o administrador, Erro 0253 - Falta elemento de critério.');
            }
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tabela'] = $id;
            $valores['id_local'] = $local;
            $valores['tabela'] = $controller;
            $model_controller = $controller.'_model';
            $this->load->model(array($model_controller,'logs_dia_model'));
            $item = $this->{$model_controller}->get_item($id);
            $filtro = $this->_inicia_filtros_locais( '', $valores );
            $itens_dia = $this->logs_dia_model->get_itens_group_data($controller,$filtro->get_filtro());
            $get_url = $filtro->get_url();
            $url = ''.( (empty($get_url) ) ? '?' : $get_url );
            //$data['paginacao'] = $this->init_paginacao($itens['total'], $url);
            //$data['filtro'] = $filtro->get_html();
            
            
            
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$controller.'/'.$id.'/'.$filtro->get_url();
            $extras['col'] = 'referencia';
            $extras['ordem'] = 'DESC'; 
            $data['listagem'] = $this->_inicia_listagem_locais( $itens_dia, $extras );
            $lista = $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/estatisticas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('estatisticas', 'estatisticas', 1)
                        ->set_usuario()
                        ->view('listar',$data, 'layout/sem_menu', TRUE);	
            echo $lista;
	}
        
        /**
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros_locais($url = '', $valores = array(), $filtros = FALSE )
	{
            if ( $filtros )
            {
                foreach( $filtros as $array )
                {
                    if ( isset($array->id) )
                    {
                        $config['itens'][] = array( 'name' => $array->id,              'titulo' => $array->descricao,             'tipo' => $array->tipo, 'valor' => $array->valor, 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => $array->id, 	'valor' => '' ) );
                    }
                }
            }
            else
            {
                $config['itens'] = array(
                                        array( 'name' => 'id_local',         'titulo' => ': ',           'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default data',  'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.id_local',     'valor' => '' ) ),
                                        array( 'name' => 'tabela',              'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'locais.tabela',     'valor' => '' ) ),
                                        array( 'name' => 'id_tabela',           'titulo' => ': ',                'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default ',               'where' => array( 'tipo' => 'where', 	'campo' => 'logs_dia.id_tabela',     'valor' => '' ) ),
                                        );	
                
            }
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
	private function _inicia_listagem_locais( $itens, $extras = NULL, $exportar = FALSE, $lista = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                    
                    
                        if ( ! $lista )
                            {
                                $lista = array(
                                                //(object)array('id' => 'nome',   'descricao' => 'nome',          'acao' => FALSE ),
                                                (object)array('id' => 'data','descricao' => 'Data', 'acao' => FALSE ),
                                                (object)array('id' => 'dayweek','descricao' => 'Dia', 'acao' => 'set_dayweek' ),
                                                (object)array('id' => 'views',  'descricao' => 'views',         'acao' => 'set_number_format'),
                                                (object)array('id' => 'clicks', 'descricao' => 'clicks',        'acao' => 'set_number_format' ),
                                                );
                            }

                        foreach ( $lista as $cabecalho )
                            {
                                $data['cabecalho'][] = (object)array( 
                                                                        'chave'     => $cabecalho->id, 
                                                                        'acao'      => $cabecalho->acao,    
                                                                        'titulo'    => $cabecalho->descricao,       
                                                                        'link'      => str_replace(array('[col]','[ordem]'), array($cabecalho->id,( ($extras['ordem'] == 'ASC' && $extras['col'] == $cabecalho->id) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $cabecalho->id ) ? 'ui-state-highlight'.( ($extras['col'] == $cabecalho->id && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) 
                                                                    );
                            }
                    
			//$data['chave'] = 'id';
			$data['qtde_por_linha'] = 1;
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private 
         */
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['descricao'] = $this->input->post('descricao');
		if ( ! isset( $data['ativo'] ) )
		{
			$data['ativo'] = 0;
		}
                
		return $data;
	}
        
}


