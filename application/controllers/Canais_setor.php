<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canais_Setor extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'id_canais',           'label'   => 'Canal Ligação', 		'rules'   => 'required'),
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'trim'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                array( 'field'   => 'title',           'label'   => 'Title', 	'rules'   => 'trim'),
                                array( 'field'   => 'description',           'label'   => 'Description', 	'rules'   => 'trim'),
                                array( 'field'   => 'ordem',           'label'   => 'Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'ativo',           'label'   => 'Ativo', 	'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('canais_setor_model', 'canais_model', 'canais_noticias_model'));
	}
	
	public function index()
	{
            $this->listar();
	}
        
        public function listar($id = '', $coluna = 'titulo', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_canais'] = $id;
            $valores['id_pai'] = '00';
            $filtro = $this->_inicia_filtros( $url, $valores );
            $pai = (isset($id) && $id) ? $this->canais_model->get_item($id) :  NULL;
            $itens = $this->canais_setor_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->canais_setor_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['id_pai'] = $id;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ;
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/canais_setor.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Canais', 'canais', 0)
                        ->set_breadscrumbs((isset($pai) && $pai) ? $pai->titulo : '', 'canais_setor', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->canais_setor_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
        
	public function adicionar($id_canal = '')
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $data['hexa_cor_titulo'] = str_replace('#', '', $data['hexa_cor_titulo']);
                        $data['id_pai'] = ($data['id_pai']) ? $data['id_pai'] : '0' ;
                        $data['id_canais_noticias'] = (isset($data['id_canais_noticias']) && $data['id_canais_noticias']) ? $data['id_canais_noticias'] : NULL ;
                        $id = $this->canais_setor_model->adicionar($data);
                        redirect(strtolower(__CLASS__).'/editar/'.$id.'/'.$id_canal.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $canal = $this->canais_model->get_item($id_canal);
                        $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                        $data['canal'] = $id_canal;
			$data['action'] = base_url().$class.'/'.$function.'/'.$id_canal;
			$data['tipo'] = 'Canais Setor Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/canais_setor.js', TRUE)
				->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
				->set_include('js/colorpicker/docs.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
				->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
				->set_include('css/colorpicker/docs', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Canais', 'canais', 0)
                                ->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 0)
                                ->set_breadscrumbs('Adicionar', 'canais_setor/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_canais_setor',$data);
		}   
		 
	}
	
        public function editar($codigo = NULL, $id_canal = '', $ok = FALSE)
	{
		$dados = $this->canais_setor_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $data['hexa_cor_titulo'] = str_replace('#', '', $data['hexa_cor_titulo']);
                                $data['id_canais_noticias'] = (isset($data['id_canais_noticias']) && $data['id_canais_noticias']) ? $data['id_canais_noticias'] : NULL ;
                                $id = $this->canais_setor_model->editar($data, array('canais_setor.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/'.$data['id_canais'].'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $canal = $this->canais_model->get_item($id_canal);
                                $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo.'/'.$id_canal;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo.'/'.$id_canal;
				$data['action_novo'] = base_url().$class.'/adicionar/'.$id_canal;
                                $data['tipo'] = 'Canais Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/canais_setor.js', TRUE)
                                        ->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
                                        ->set_include('js/colorpicker/docs.js', TRUE)
                                        ->set_include('css/estilo.css', TRUE)  
                                        ->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
                                        ->set_include('css/colorpicker/docs', TRUE)  
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Canais', 'canais', 0)
                                        ->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 0)
                                        ->set_breadscrumbs($dados->titulo, 'canais_setor/listar/'.$id_canal, 1)
                                        //->set_breadscrumbs('Editar', 'canais_setor/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_canais_setor',$data);
			}
		}
		else 
		{
                        redirect('canais_setor/listar/'.$id_canal);
		}
	}
	
        private function _inicia_select( $id = FALSE, $id_canal =  FALSE, $id_pai = FALSE ) 
        {
            $retorno['canais'] = $this->canais_model->get_select();
            $retorno['canais_noticias'] = $this->canais_noticias_model->get_select();
            $retorno['pai'] = $this->canais_setor_model->get_select();
            $retorno['selecionado'] = (isset($id) && $id) ? $this->canais_setor_model->get_selected($id) : NULL;
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->canais_setor_model->excluir('canais_setor.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' )),
                                                    );
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    (object) array('titulo' => 'Setor', 'class' => 'btn btn-primary', 'link'=> 'canais_setor/adicionar_nivel_2/'.(isset($itens['itens'][0]->id_canais) ? $itens['itens'][0]->id_canais : '').'/[id]', 'icone' => '<span class="glyphicon glyphicon-plus"></span>'),
                                                    (object) array('titulo' => 'Conteudo', 'class' => 'btn btn-primary', 'link'=> 'canais_conteudo/adicionar/[id]', 'icone' => '<span class="glyphicon glyphicon-plus"></span>'),
                                                    (object) array('titulo' => 'Listar Setor', 'class' => 'btn btn-primary', 'link'=> 'canais_setor/listar_nivel_2/'.(isset($itens['itens'][0]->id_canais) ? $itens['itens'][0]->id_canais : '').'/[id]', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
                                                    (object) array('titulo' => 'Listar Conteudo', 'class' => 'btn btn-primary', 'link'=> 'canais_conteudo/listar/[id]', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
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
        
        private function _inicia_filtros($url = '', $valores = array())
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id', 	'valor' => '' ) ),
                                        array( 'name' => 'id_canais',              'titulo' => 'Canais: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id_canais', 	'valor' => '' ) ),
                                        array( 'name' => 'id_pai',              'titulo' => 'Setor Pai: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id_pai', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais_setor.titulo', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar/'.$valores['id_canais'].'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        // Nivel 2
        public function listar_nivel_2($id_canal = '', $id_pai = '', $coluna = 'titulo', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id_canal.'/'.$id_pai.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_canais'] = $id_canal;
            $valores['id_pai'] = $id_pai;
            $filtro = $this->_inicia_filtros_nivel_2( $url, $valores );
            $pai = (isset($id_pai) && $id_pai) ? $this->canais_setor_model->get_item($id_pai) : NULL;
            $canal = (isset($id_canal) && $id_canal) ? $this->canais_model->get_item($id_canal) : NULL;
            $itens = $this->canais_setor_model->get_itens($filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->canais_setor_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id_canal.'/'.$id_pai.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ; 
            $data['listagem'] = $this->_inicia_listagem_nivel_2( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/canais_setor_nivel_2.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Canais', 'canais/listar', 0)
                        ->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 0)
                        ->set_breadscrumbs($pai->titulo, 'canais_setor', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        
        public function adicionar_nivel_2($id_canal = '', $id_pai = '')
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $data['descricao'] = addslashes($data['descricao']);
			$data['hexa_cor_titulo'] = str_replace('#', '', $data['hexa_cor_titulo']);
                        $data['id_pai'] = ($data['id_pai']) ? $data['id_pai'] : '0' ;
                        $id = $this->canais_setor_model->adicionar($data);
                        redirect(strtolower(__CLASS__).'/editar_nivel_2/'.$id.'/'.$id_canal.'/'.$id_pai.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $canal = $this->canais_model->get_item($id_canal);
                        $pai = $this->canais_setor_model->get_item($id_pai);
                        $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                        $data['canal'] = $id_canal;
                        $data['id_pai'] = $id_pai;
			$data['action'] = base_url().$class.'/'.$function.'/'.$id_canal.'/'.$id_pai;
			$data['tipo'] = 'Canais Setor Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/canais_setor_nivel_2.js', TRUE)
                                ->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
				->set_include('js/colorpicker/docs.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
				->set_include('css/colorpicker/docs', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Canais', 'canais', 0)
                                ->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 0)
                                ->set_breadscrumbs($pai->titulo, 'canais_setor/listar_nivel_2/'.$id_canal.'/'.$id_pai, 0)
                                ->set_breadscrumbs('Adicionar', 'canais_setor/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_canais_setor',$data);
		}   
		 
	}
        
        public function editar_nivel_2($codigo = NULL, $id_canal = '', $id_pai = '', $ok = FALSE)
	{
		$dados = $this->canais_setor_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $data['descricao'] = addslashes($data['descricao']);
                                $data['hexa_cor_titulo'] = str_replace('#', '', $data['hexa_cor_titulo']);
                                $id = $this->canais_setor_model->editar($data, array('canais_setor.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar_nivel_2/'.$codigo.'/'.$data['id_canais'].'/'.$data['id_pai'].'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $canal = $this->canais_model->get_item($id_canal);
                                $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo.'/'.$id_canal.'/'.$id_pai;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo.'/'.$id_canal.'/'.$id_pai;
				$data['action_novo'] = base_url().$class.'/adicionar_nivel_2/'.$id_canal.'/'.$id_pai;
                                $data['tipo'] = 'Canais Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/canais_setor_nivel_2.js', TRUE)
                                        ->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
                                        ->set_include('js/colorpicker/docs.js', TRUE)
                                        ->set_include('css/estilo.css', TRUE)  
                                        ->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
                                        ->set_include('css/colorpicker/docs', TRUE)  
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Canais', 'canais', 0)
                                        //->set_breadscrumbs($canal->titulo, 'canais_setor/listar/'.$id_canal, 0)
                                        ->set_breadscrumbs($dados->titulo, 'canais_setor/listar_nivel_2/'.$id_canal.'/'.$id_pai, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_canais_setor',$data);
			}
		}
		else 
		{
                        redirect('canais_setor/listar_nivel_2/'.$id_canal.'/'.$id_pai);
		}
	}
       
        private function _inicia_listagem_nivel_2( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'canais_conteudo/listar/[id]'),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        $data['extras']['opcao'] = 'Setor ';
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
        private function _inicia_filtros_nivel_2($url = '', $valores = array())
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id', 	'valor' => '' ) ),
                                        array( 'name' => 'id_canais',              'titulo' => 'Canais: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id_canais', 	'valor' => '' ) ),
                                        array( 'name' => 'id_pai',              'titulo' => 'Setor Pai: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais_setor.id_pai', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais_setor.titulo', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar_nivel_2/'.$valores['id_canais'].'/'.$valores['id_pai'].'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
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
            $filtro = 'canais_setor.link like "'.$dados['link'].'" ';
            if ( isset($data['id']) && $data['id'] != 'undefined' )
            {
                $filtro .= 'AND canais_setor.id != '.$id;
            }
            $data = $this->canais_setor_model->get_itens($filtro);
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
		return $data;
	}
}


