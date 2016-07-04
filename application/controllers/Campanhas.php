<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campanhas extends MY_Controller 
{
        
        private $valida_add = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'data_inicio',            'label'   => 'Data de Inicio', 		'rules'   => 'trim'),
                                array( 'field'   => 'data_fim',            'label'   => 'Data de Fim', 		'rules'   => 'trim'),
                                array( 'field'   => 'meta',            'label'   => 'Meta', 		'rules'   => 'trim'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                array( 'field'   => 'equipes[]',           'label'   => 'Equipe', 		'rules'   => 'required'),
                                array( 'field'   => 'empresas_campanha',           'label'   => 'Empresas Selecionadas', 		'rules'   => 'required'),
                                );
        
        private $valida_edt = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'data_inicio',            'label'   => 'Data de Inicio', 		'rules'   => 'trim'),
                                array( 'field'   => 'data_fim',            'label'   => 'Data de Fim', 		'rules'   => 'trim'),
                                array( 'field'   => 'meta',            'label'   => 'Meta', 		'rules'   => 'trim'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                );
    
        
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('pow_campanhas_model','empresas_pow_campanhas_model','empresas_model','ocorrencias_model','interacao_model','empresas_status_ocorrencia_model','email_automatico_model','pow_cargos_model','usuario_model'));
            $this->load->library(array('ocorrencias'));
	}
        
	public function index()
	{
            redirect('painel');
	}
        
        public function listar($tipo = 'todas', $coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$tipo.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores, $tipo );
            /*
            $filter = $filtro->get_filtro();
            switch($tipo)
            {
                case 'abertas':
                    $filter[] = array('tipo' => 'where', 'campo' => 'pow_campanhas.data_fim >', 'valor' => date('Y-m-d H:i:s'));
                    break;
                case 'abertas_usuario':
                    $filter[] = array('tipo' => 'where', 'campo' => 'pow_campanhas.data_fim >', 'valor' => date('Y-m-d H:i:s'));
                    $filter[] = array('tipo' => 'where', 'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => $this->sessao['id']);
                    $filter[] = array('tipo' => 'where', 'campo' => 'usuarios.id', 'valor' => $this->sessao['id']);
                    //$filter[] = array('tipo' => 'where', 'campo' => 'empresas_ocorrencia_has_usuario.id_usuario', 'valor' => $this->sessao['id']);
                    break;
                case 'fechadas':
                    $filter[] = array('tipo' => 'where', 'campo' => 'pow_campanhas.data_fim <', 'valor' => date('Y-m-d H:i:s'));
                    break;
                case 'fechadas_usuario':
                    $filter[] = array('tipo' => 'where', 'campo' => 'pow_campanhas.data_fim <', 'valor' => date('Y-m-d H:i:s'));
                    $filter[] = array('tipo' => 'where', 'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => $this->sessao['id']);
                    $filter[] = array('tipo' => 'where', 'campo' => 'usuarios.id', 'valor' => $this->sessao['id']);
                    //$filter[] = array('tipo' => 'where', 'campo' => 'empresas_ocorrencia_has_usuario.id_usuario', 'valor' => $this->sessao['id']);
                    break;
                default:
                    break;
            }
            */
            $itens = $this->pow_campanhas_model->get_itens($filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->pow_campanhas_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$tipo.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem($itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/campanhas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Campanhas', 'campanhas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->pow_campanhas_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
        
        public function adicionar()
        {
                $this->form_validation->set_rules($this->valida_add); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $dados_extra['empresas_campanha'] = $data['empresas_campanha'];
                        $dados_extra['email_automatico'] = ((isset($data['email_automatico']) && $data['email_automatico']) ? $data['email_automatico'] : NULL ) ;
                        $dados_extra['equipes'] = $data['equipes'];
                        $dados_extra['texto'] = $data['titulo'];
                        //$dados_extra['retorno'] = $data['data_fim'];
                        
                        
                        unset($data['email_automatico']);
                        unset($data['equipes']);
                        unset($data['nome_empresa']);
                        unset($data['empresas_campanha']);
                        unset($data['categorias']);
                        unset($data['subcategorias']);
                        unset($data['estados']);
                        unset($data['cidades']);
                        unset($data['bairros']);
                        unset($data['status']);
                        unset($data['logradouro']);
                        
                        if(isset($data['data_inicio']) && !empty($data['data_inicio']) && $data['data_inicio'] != '0000-00-00 00:00:00')
                        {
                            $exp_a = explode('/', $data['data_inicio']); 
                            $data['data_inicio'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0].' 00:00:00';
                        }
                        else
                        {
                            $data['data_inicio'] = NULL;
                        }
                        
                        if(isset($data['data_fim']) && !empty($data['data_fim']) && $data['data_fim'] != '0000-00-00 00:00:00')
                        {
                            $exp_a = explode('/', $data['data_fim']); 
                            $data['data_fim'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0].'  00:00:00';
                        }
                        else
                        {
                            $data['data_fim'] = NULL;
                        }
                        
			$id = $this->pow_campanhas_model->adicionar($data);
                        
                        $dados_extra['id_campanha'] = $id;
                        $dados_extra['retorno_inicio'] = $data['data_inicio'];
                        $dados_extra['retorno_fim'] = $data['data_fim'];
                        
                        $this->adicionar_dados_campanha($dados_extra);
                        
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
                        $data['function'] = $function;
			$data['tipo'] = 'Campanhas Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/campanhas.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Campanhas', 'campanhas/listar', 0)
                                ->set_breadscrumbs('Adicionar', 'campanhas/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_campanhas',$data);
		}   
        }
        
        public function adicionar_dados_campanha($data = NULL)
        {
            if(isset($data) && $data)
            {
                $data_oc['id_empresas_status_ocorrencia'] = 4;
                $data_oc['id_assunto'] = 13;
                $data_oc['id_setor'] = 4;
                $data_oc['id_usuario_ativo'] = $this->sessao['id'];
                $data_oc['id_contato'] = NULL;
                $data_oc['texto'] = 'Ocorrência '.$data['texto'];
                $data_oc['data_retorno_inicio'] = $data['retorno_inicio'];
                $data_oc['data_retorno_fim'] = $data['retorno_fim'];
                //$data_oc['data'] = date('Y-m-d H:i:s');

                
                $data_in['data_retorno_inicio'] = $data['retorno_inicio'];
                $data_in['data_retorno_fim'] = $data['retorno_fim'];
                
                /*
                if(isset($data['data_retorno']) && !empty($data['data_retorno']))
                {
                    $exp_a = explode('/', $data['data_retorno']); 
                    $data_in['data_retorno_inicio'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0].' 00:00:00';
                }*/
                /*
                if(isset($data['data_fim']) && !empty($data['data_fim']))
                {
                    $exp_c = explode('/', $data['data_fim']);
                    $exp_d = explode(' ', $exp_c[2]);
                    $data_in['data_retorno_fim'] = $exp_d[0].'-'.$exp_c[1].'-'.$exp_c[0].' '.$exp_d[1];
                }*/
                
                $data_in['id_empresas_status_ocorrencia'] = 4;
                $data_in['id_usuario'] = $this->sessao['id'];
                $data_in['id_contato'] = NULL;
                $data_in['obs'] = 'Interação '.$data['texto'];
                //$data_in['data_inclusao'] = date('Y-m-d H:i:s');
                $filtro[] = array('tipo' => 'where_in', 'campo' => 'usuarios_has_cargos.id_pow_cargos', 'valor' => implode(',',$data['equipes'][0]) );
                $usuarios = $this->usuario_model->get_select_has_cargo($filtro);
                
                $data_ca['id_pow_campanhas'] = $data['id_campanha'];
                
                //$empresas = explode(',',$data['empresas_campanha']);
                $empresas = $data['empresas_campanha'];
                foreach($empresas as $empresa)
                {
                    if(!empty($empresa))
                    {
                        $data_oc['id_empresa'] = $empresa;
                        $ocorrencia = $this->ocorrencias_model->adicionar($data_oc);
                        if($ocorrencia)
                        {
                            $data_in['id_empresas_ocorrencia'] = $ocorrencia;
                            $this->interacao_model->adicionar($data_in);

                            $data_ca['id_ocorrencias'] = $ocorrencia;
                            $data_ca['id_empresas'] = $empresa;
                            $this->empresas_pow_campanhas_model->adicionar($data_ca); 

                            $data_us['id_empresas_ocorrencia'] = $ocorrencia;
                            foreach($usuarios as $usuario)
                            {
                                $data_us['id_usuario'] = $usuario->id;
                                $this->ocorrencias_model->adicionar_has($data_us);
                            }
                        }
                    }
                }
                
                if(isset($data['email_automatico']) && $data['email_automatico'])
                {
                    $data_em['id_pow_campanhas'] = $data['id_campanha'];
                    foreach($data['email_automatico'][0] as $email)
                    {
                        $data_em['id_email_automatico'] =  $email;
                        $this->pow_campanhas_model->adicionar_has_emails($data_em);
                    }
                }
            }
        }
        
        public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->pow_campanhas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida_edt);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                
                                if(isset($data['data_inicio']) && !empty($data['data_inicio']) && $data['data_inicio'] != '0000-00-00 00:00:00')
                                {
                                    $exp_a = explode('/', $data['data_inicio']); 
                                    $exp_b = explode(' ', $exp_a[2]); 
                                    $data['data_inicio'] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1];
                                }
                                else
                                {
                                    $data['data_inicio'] = NULL;
                                }

                                if(isset($data['data_fim']) && !empty($data['data_fim']) && $data['data_fim'] != '0000-00-00 00:00:00')
                                {
                                    $exp_a = explode('/', $data['data_fim']); 
                                    $exp_b = explode(' ', $exp_a[2]); 
                                    $data['data_fim'] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1];
                                }
                                else
                                {
                                    $data['data_fim'] = NULL;
                                }
                                
                                $id = $this->pow_campanhas_model->editar($data, 'empresas_pow_campanhas.id = '.$codigo);
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
                        }
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data = $this->inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
				$data['tipo'] = 'Campanhas Editar';
				$data['function'] = $function;
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/campanhas.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Campanhas', 'campanhas/listar', 0)
                                        ->set_breadscrumbs($dados->titulo, 'campanhas', 1)
                                        //->set_breadscrumbs('Editar', 'canais/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_campanhas',$data);
			}
		}
		else 
		{
			redirect('campanhas/listar');
		}
	}
        
        public function inicia_select($id = NULL)
        {
            $retorno['email_automatico'] = $this->email_automatico_model->get_select();
            $retorno['equipes'] = $this->pow_cargos_model->get_select();
            return $retorno;
        }
        
        private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                       $data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',             'titulo' => 'ID da Campanha',   'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo',      'titulo' => 'Titulo',         'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'campanhas/listar_empresas_campanha/[id]'),                        
                                                    (object)array( 'chave' => 'data_inicio',         'titulo' => 'Data Início',      'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_fim',        'titulo' => 'Data Término',     'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'meta',        'titulo' => 'Meta',     'link' => str_replace(array('[col]','[ordem]'), array('meta',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'meta') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'meta' ) ? 'ui-state-highlight'.( ($extras['col'] == 'meta' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'qtde_interacao',        'titulo' => 'Interações',     'link' => str_replace(array('[col]','[ordem]'), array('qtde_interacao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_interacao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_interacao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_interacao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'descricao',      'titulo' => 'Descrição',         'link' => str_replace(array('[col]','[ordem]'), array('descricao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'descricao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'descricao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'descricao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),                        
                                                    );
                       
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
                        $data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
        private function _inicia_filtros($url = '', $valores = array(), $tipo = '' )
	{
                $config['itens'] = array(
                                            array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'pow_campanhas.id', 	'valor' => '' ) ),
                                            array( 'name' => 'titulo',              'titulo' => 'Titulo: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'pow_campanhas.titulo', 	'valor' => '' ) ),
                                            array( 'name' => 'meta',              'titulo' => 'Meta: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'pow_campanhas.meta', 	'valor' => '' ) ),
                                            array( 'name' => 'data_inicio',          'titulo' => 'Data Inicio: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'pow_campanhas.data_inicio', 	'valor' => '' ) ),
                                            //array( 'name' => 'status',              'titulo' => 'Status: ',             'tipo' => 'select', 'valor' => $this->empresas_status_ocorrencia_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_status_ocorrencia.id', 	'valor' => '' ) ),
                                            //array( 'name' => 'link',           'titulo' => 'Link: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'culinaria_categorias.link', 		'valor' => '' ) ),
                                );
                $valores['data_tempo']      = date('Y-m-d H:i:s');
                $valores['usuario_ativo']   = $this->sessao['id'];
                $valores['usuarios']        = $this->sessao['id'];
                
                switch($tipo)
                {
                    case 'abertas':
                        $config['itens'][] = array( 'name' => 'data_tempo', 'where' => array( 'tipo' => 'where',  'campo' => 'pow_campanhas.data_fim >', 'valor' => '' ) );
                        break;
                    case 'abertas_usuario':
                        $config['itens'][] = array( 'name' => 'data_tempo', 'where' =>      array( 'tipo' => 'where',  'campo' => 'pow_campanhas.data_fim >', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'usuario_ativo', 'where' =>   array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'usuarios', 'where' =>        array( 'tipo' => 'where',  'campo' => 'usuarios.id', 'valor' => '' ) );
                        break;
                    case 'fechadas':
                        $config['itens'][] = array( 'name' => 'data_tempo', 'where' =>      array( 'tipo' => 'where',  'campo' => 'pow_campanhas.data_fim <', 'valor' => '' ) );
                        break;
                    case 'fechadas_usuario':
                        $config['itens'][] = array( 'name' => 'data_tempo', 'where' =>      array( 'tipo' => 'where',  'campo' => 'pow_campanhas.data_fim <', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'usuario_ativo', 'where' =>   array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'usuarios', 'where' =>        array( 'tipo' => 'where',  'campo' => 'usuarios.id', 'valor' => '' ) );
                        break;
                            
                }
                
 		$config['colunas'] = 3;
 		$config['url']     = $url;
                $config['extras']  = '';
                $config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
	}
        
        /** Empresas Campanha  **/
        public function listar_empresas_campanha($id = '', $coluna = 'retorno_inicio', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id'] = $id;
            $filtro = $this->_inicia_filtros_empresas( $url, $valores );
            $filter = $filtro->get_filtro();
            $filter[] = array('tipo' => 'where', 'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '4');
            $pai =  $this->pow_campanhas_model->get_item($id);
            $itens = $this->empresas_pow_campanhas_model->get_itens( $filter, 'empresas_ocorrencia.data_retorno_inicio', $ordem, $off_set );
            $total = $this->empresas_pow_campanhas_model->get_total_itens( $filter );
            //$itens = $this->empresas_pow_campanhas_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            //$total = $this->empresas_pow_campanhas_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ;
            $data['listagem'] = $this->_inicia_listagem_empresas( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/campanhas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Campanhas', 'campanhas/listar/',0)
                        ->set_breadscrumbs($pai->titulo, 'campanhas/listar/',1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        private function _inicia_listagem_empresas( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                       $data['cabecalho'] = array(
                                                    //(object)array( 'chave' => 'id',             'titulo' => 'ID da Empresa',   'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'empresa',      'titulo' => 'Empresa',         'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'empresas/editar/[id]'),                        
                                                    (object)array( 'chave' => 'cidade',        'titulo' => 'Cidade',     'link' => str_replace(array('[col]','[ordem]'), array('cidade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cidade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cidade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cidade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'telefone',         'titulo' => 'Telefone',      'link' => str_replace(array('[col]','[ordem]'), array('telefone',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'telefone') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'telefone' ) ? 'ui-state-highlight'.( ($extras['col'] == 'telefone' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'usuario_nome',        'titulo' => 'Responsável',     'link' => str_replace(array('[col]','[ordem]'), array('usuario_nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuario_nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuario_nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'usuario_nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'ultima_interacao',        'titulo' => 'Ultima Interação',     'link' => str_replace(array('[col]','[ordem]'), array('ultima_interacao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ultima_interacao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'ultima_interacao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'ultima_interacao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'data_retorno',        'titulo' => 'Data de Retorno',     'link' => str_replace(array('[col]','[ordem]'), array('data_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'retorno_inicio',        'titulo' => 'Data de Retorno',     'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'qtde_interacao',        'titulo' => 'Quantidade Interações',     'link' => str_replace(array('[col]','[ordem]'), array('qtde_interacao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_interacao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_interacao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_interacao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'descricao',      'titulo' => 'Descrição',         'link' => str_replace(array('[col]','[ordem]'), array('descricao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'descricao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'descricao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'descricao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),                        
                                                    );
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
                        $data['extras'] = $extras;
                        $data['extras']['opcao'] = 'Campanha ';
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
        private function _inicia_filtros_empresas($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                            array( 'name' => 'id',              'titulo' => 'ID da Campanha: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'pow_campanhas.id', 	'valor' => '' ) ),
                                            array( 'name' => 'id_empresa',              'titulo' => 'ID da Empresa: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id', 	'valor' => '' ) ),
                                            array( 'name' => 'empresa',              'titulo' => 'Nome da Empresa: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                            array( 'name' => 'cidade',              'titulo' => 'Cidade: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'logradouros.cidade', 	'valor' => '' ) ),
                                            array( 'name' => 'usuarios',          'titulo' => 'Responsável: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'usuarios.nome', 	'valor' => '' ) ),
                                            //array( 'name' => 'status',              'titulo' => 'Status: ',             'tipo' => 'select', 'valor' => $this->empresas_status_ocorrencia_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_status_ocorrencia.id', 	'valor' => '' ) ),
                                            //array( 'name' => 'link',           'titulo' => 'Link: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'culinaria_categorias.link', 		'valor' => '' ) ),
                                );
 		$config['colunas'] = 3;
 		$config['url']     = $url;
                $config['extras']  = '';
                $config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
	}
        
        public function get_categorias()
        {
            $this->load->model('categorias_model');
            echo json_encode($this->categorias_model->get_select());
        }
        
        public function get_subcategorias($id = NULL)
        {
            $this->load->model('subcategorias_model');
            $filtro = (isset($id) && $id) ? 'id_categoria = '.$id : '';
            echo json_encode($this->subcategorias_model->get_select($filtro));
        }
        
        public function get_estados()
        {
            $this->load->model('estados_model');
            echo json_encode($this->estados_model->get_select());
        }
        
        public function get_cidades($id = NULL)
        {
            $this->load->model('cidades_model');
            $filtro = (isset($id) && $id) ? 'uf = "'.$id.'" ' : '';
            echo json_encode($this->cidades_model->get_select($filtro));
        }
        
        public function get_bairros($id = NULL)
        {
            $this->load->model('logradouros_model');
            $filtro = (isset($id) && $id) ? 'id_cidade = '.$id : '';
            echo json_encode($this->logradouros_model->get_select_bairros($filtro));
        }
        
        public function get_status($id = NULL)
        {
            $this->load->model('status_atualizada_model');
            echo json_encode($this->status_atualizada_model->get_select());
        }
        
        public function get_empresas()
        {
            $retorno = NULL;
            $data = $this->_post();
            $filtro = '';
            $a = 0;
            foreach($data['itens'] as $item)
            {
                if(!empty($item['valor']))
                {
                    if($a == 0)
                    {
                        $filtro .= $item['campo'].( (isset($item['tipo']) && $item['tipo'] == 'text' ) ? ' like "%'.$item['valor'].'%" ' : ' = '.$item['valor']);
                    }
                    else
                    {
                        $filtro .= ' AND '.$item['campo'].( ( isset($item['tipo']) && $item['tipo'] == 'text' ) ? ' like "%'.$item['valor'].'%" ' : ' = '.$item['valor'] );
                    }
                    $a++;
                }
            }
            
            if(isset($filtro) && !empty($filtro))
            {
                $retorno = $this->empresas_model->get_itens_campanhas($filtro);
            }
            else
            {
                $retorno = $this->empresas_model->get_itens_campanhas();
            }
            
            echo json_encode($retorno);
             
        }
        
        private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}
