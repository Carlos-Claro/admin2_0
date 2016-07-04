<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canais_Conteudo extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),
                                array( 'field'   => 'title',           'label'   => 'Title', 	'rules'   => 'trim'),
                                array( 'field'   => 'description',           'label'   => 'Description', 	'rules'   => 'max_length[200]|trim'),
                                array( 'field'   => 'ordem',           'label'   => 'Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'id_canais_setor',           'label'   => 'Setor Pai', 	'rules'   => 'required|trim'),
                                array( 'field'   => 'data_publicacao',           'label'   => 'Data de Publicação', 	'rules'   => 'trim'),
                                array( 'field'   => 'data_acao_inicio',           'label'   => 'Data Inicio', 	'rules'   => 'trim'),
                                array( 'field'   => 'data_acao_fim',           'label'   => 'Data Fim', 	'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('canais_conteudo_model', 'canais_setor_model', 'canais_model'));
	}
	
	public function index()
	{
            $this->listar();
	}
        
        public function listar($id = '', $coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_canais_setor'] = $id;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $sub = NULL;
            $master = NULL;
            if(isset($id) && $id)
            {
                $sub = $this->canais_setor_model->get_item($id);
                $master = $this->canais_model->get_item($sub->id_canais);
            }
            $itens = $this->canais_conteudo_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->canais_conteudo_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($sub) && $sub) ? $sub : NULL ;
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/canais_conteudo.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Canais', 'canais/listar',0)
                        ->set_breadscrumbs($master->titulo, 'canais_setor/listar/'.$master->id,0)
                        ->set_breadscrumbs($sub->titulo, 'canais_setor/listar/'.$sub->id,1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->canais_conteudo_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar($id_canal = '')
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			$id = $this->canais_conteudo_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/'.$id_canal.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $canal = $this->canais_setor_model->get_item($id_canal);
                        $master = $this->canais_model->get_item($canal->id_canais);
                        $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                        $data['canal'] = $id_canal;
			$data['action'] = base_url().$class.'/'.$function.'/'.$id_canal;
			$data['tipo'] = 'Canais Conteudo Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/canais_conteudo.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Canais', 'canais', 0)
                                ->set_breadscrumbs($master->titulo, 'canais_setor/listar/'.$canal->id_canais, 0)
                                ->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_canais_conteudo',$data);
		}   
		 
	}
	
	public function editar($codigo = NULL, $id_canal = '', $ok = FALSE)
	{
		$dados = $this->canais_conteudo_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $id = $this->canais_conteudo_model->editar($data, array('canais_conteudo.id' => $codigo));
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/'.$id_canal.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $canal = $this->canais_setor_model->get_item($id_canal);
                                $master = $this->canais_model->get_item($canal->id_canais);
                                $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo.'/'.$id_canal;
                                $data['action_novo'] = base_url().$class.'/adicionar/'.$id_canal;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo.'/'.$id_canal;
				$data['tipo'] = 'Canais Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/canais_conteudo.js', TRUE)
                                        ->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Canais', 'canais', 0)
                                        ->set_breadscrumbs($master->titulo, 'canais_setor/listar/'.$canal->id_canais, 0)
                                        ->set_breadscrumbs($canal->titulo, 'canais_conteudo/listar/'.$id_canal, 0)
                                        ->set_breadscrumbs($dados->titulo, 'canais_conteudo/listar/'.$id_canal, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_canais_conteudo',$data);
			}
		}
		else 
		{
			redirect('canais_conteudo/listar/'.$id_canal);
		}
	}
	
        private function _inicia_select( $id = FALSE) 
        {
            $retorno['pai'] = $this->canais_setor_model->get_select_pai();
            $retorno['selecionado'] = (isset($id) && $id) ? $this->canais_conteudo_model->get_selected($id) : '';
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->canais_conteudo_model->excluir('canais_conteudo.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
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
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'link',  'titulo' => 'Link',    'link' => str_replace(array('[col]','[ordem]'), array('link',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        $data['extras']['opcao'] = 'Canal ';
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_conteudo.id', 	'valor' => '' ) ),
                                        array( 'name' => 'id_canais_setor',              'titulo' => 'Setor de canais: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_conteudo.id_canais_setor', 	'valor' => '' ) ),
                                        array( 'name' => 'descricao',           'titulo' => 'Descricao: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais_conteudo.descricao', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar/'.$valores['id_canais_setor'].'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
        
        public function gera_link_automatico()
        {
            $data = $this->_post();
            //$link = str_replace(' ','+',$data['titulo']);
            //$link = str_replace(',','+',$data['titulo']);
            $link = tira_acento($data['titulo']);
            echo $link;
        }
        
        public function verifica_link( )
        {
            $dados = $this->_post();
            $filtro = 'canais_conteudo.link like "'.$dados['link'].'" ';
            if ( isset($data['id']) && $data['id'] != 'undefined' )
            {
                $filtro .= 'AND canais_conteudo.id != '.$id;
            }
            $data = $this->canais_conteudo_model->get_itens($filtro);
            if ( $data['qtde'] > 0 )
            {
                $retorno = $dados['link'].substr( md5( time() ), 0, 5);
            }
            else
            {
                $retorno = 0;
            }
            echo $retorno;
        }
	
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['descricao'] = $this->input->post('descricao');
		if ( ! isset( $data['ativo'] ) )
		{
			$data['ativo'] = 0;
		}
                $data['exibe_destaque'] = ( (isset($data['exibe_destaque']) && $data['exibe_destaque']) ? 1 : 0);
                $data['data_publicacao'] = converte_data_mysql($data['data_publicacao']); 
                $data['data_acao_inicio'] = isset($data['data_acao_inicio']) && ! empty($data['data_acao_inicio']) ? converte_data_mysql($data['data_acao_inicio']) : NULL; 
                $data['data_acao_fim'] = isset($data['data_acao_fim']) && ! empty($data['data_acao_fim']) ? converte_data_mysql($data['data_acao_fim']) : NULL; 
		return $data;
	}
}


