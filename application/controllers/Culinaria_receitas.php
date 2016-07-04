<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culinaria_Receitas extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'ingredientes',           'label'   => 'Ingredientes', 		'rules'   => 'trim'),
                                array( 'field'   => 'modo_preparo',            'label'   => 'Modo de Preparo', 		'rules'   => 'trim'),
                                array( 'field'   => 'nome',           'label'   => 'Nome', 	'rules'   => 'trim'),
                                array( 'field'   => 'email',           'label'   => 'Email', 	'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'telefone',           'label'   => 'Telefone', 	'rules'   => 'trim'),
                                array( 'field'   => 'liberado',           'label'   => 'Liberado', 	'rules'   => 'trim'),
                                array( 'field'   => 'aceito',           'label'   => 'Aceito', 	'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('culinaria_receitas_model','culinaria_categorias_model'));
	}
	
	public function index()
	{
            $this->listar();
	}
        	
	public function listar($coluna = 'titulo', $ordem = 'ASC' )
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            //$valores['categoria'] = $id;
            $filtro = $this->_inicia_filtros( $url, $valores );
            //$pai = (isset($id) && $id) ? $this->culinaria_categorias_model->get_item($id) :  NULL;
            $itens = $this->culinaria_receitas_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->culinaria_receitas_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            //$extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ;
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/culinaria_receitas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 0)
                        ->set_breadscrumbs((isset($pai) && $pai) ? $pai->titulo : '', 'categorias', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->culinaria_receitas_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar( $id_categoria = '' )
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $data['data'] = date('Y-m-d');
                        
                        if(isset($data['data_cadastro'])  && !empty($data['data_cadastro']) )
                        {
                            $exp_a = explode('-', $data['data_cadastro']);
                            $data['data_cadastro'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                        }
                        
                        $id = $this->culinaria_receitas_model->adicionar($data);
                        redirect(strtolower(__CLASS__).'/editar/'.$id.'/'.$id_categoria.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $categoria = $this->culinaria_categorias_model->get_item($id_categoria);
                        $data['ckeditor_ingredientes'] = $this->inicia_ckeditor('ingredientes');
                        $data['ckeditor_modo_preparo'] = $this->inicia_ckeditor('modo_preparo');
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Receitas Adicionar';	
                        $data['id_categoria'] = $id_categoria;	
			$this->layout
				->set_function( $function )
				->set_include('js/culinaria_receitas.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 0)
                                ->set_breadscrumbs($categoria->titulo, 'culinaria_receitas/listar/'.$id_categoria, 0)
                                ->set_breadscrumbs('Adicionar', 'culinaria_receitas/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_culinaria_receitas',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->culinaria_receitas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            
                            if(isset($data['data_cadastro'])  && !empty($data['data_cadastro']) )
                            {
                                $exp_a = explode('-', $data['data_cadastro']);
                                $data['data_cadastro'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                            }
                            
                            $id = $this->culinaria_receitas_model->editar($data, array('culinaria_receitas.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['ckeditor_ingredientes'] = $this->inicia_ckeditor('ingredientes');
                                $data['ckeditor_modo_preparo'] = $this->inicia_ckeditor('modo_preparo');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo.'/';
				$data['action_novo'] = base_url().$class.'/adicionar/';
                                $data['tipo'] = 'Receitas Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/culinaria_receitas.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 0)
                                        ->set_breadscrumbs($dados['item']->titulo, 'culinaria_receitas/listar/', 1)
                                        //->set_breadscrumbs('Editar', 'culinaria_receitas/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_culinaria_receitas',$data);
			}
		}
		else 
		{
			redirect('culinaria_receitas/listar/'.$id_categoria);
		}
	}
        
        public function moderar_image( $id )
        {
            $this->load->model('images_model');
            $data = array('image_pai.moderada' => 1);
            $filtro = array('image_pai.id' => $id);
            $ed = $this->images_model->editar_pai($data,$filtro);
            echo json_encode($ed);
        }
        
        public function gera_link() 
        {
            $classe = strtolower(__CLASS__);
            $link = $this->monta_link($classe);
            echo $link;
        }
	
        private function _inicia_select( $id = FALSE ) 
        {
            if(isset($id) && $id)
            {
                $retorno['selecionado'] = $this->culinaria_receitas_model->get_selected($id);
            }
            $retorno['pai'] = $this->culinaria_categorias_model->get_select();
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->culinaria_receitas_model->excluir('culinaria_receitas.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'id',                 'titulo' => 'ID',               'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'categoria',          'titulo' => 'Categoria',        'link' => str_replace(array('[col]','[ordem]'), array('categoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'categoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'categoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'categoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo',             'titulo' => 'Titulo',           'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'liberado',           'titulo' => 'Liberado',         'link' => str_replace(array('[col]','[ordem]'), array('liberado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'liberado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'liberado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'liberado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'qtde_fotos',         'titulo' => 'Qtde Fotos',       'link' => str_replace(array('[col]','[ordem]'), array('qtde_fotos',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_fotos') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_fotos' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_fotos' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'fotos_moderar',      'titulo' => 'Tem Foto moderar?','link' => str_replace(array('[col]','[ordem]'), array('fotos_moderar',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'fotos_moderar') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'fotos_moderar' ) ? 'ui-state-highlight'.( ($extras['col'] == 'fotos_moderar' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        $data['extras']['opcao'] = 'Categoria ';
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text',   'valor' => '',                                              'classe' => 'ui-state-default',                 'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_receitas.id',             'valor' => '' ) ),
                                        array( 'name' => 'categoria',       'titulo' => 'Categoria: ',      'tipo' => 'hidden', 'valor' => '',                                              'classe' => 'form-control ui-state-default',    'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_receitas.id_categoria',	'valor' => '' ) ),
                                        array( 'name' => 'nome',            'titulo' => 'Nome: ',           'tipo' => 'text',   'valor' => '',                                              'classe' => 'form-control ui-state-default',    'where' => array( 'tipo' => 'like', 	'campo' => 'culinaria_receitas.titulo', 	'valor' => '' ) ),
                                        //array( 'name' => 'liberado',        'titulo' => 'Moderado: ',       'tipo' => 'select', 'valor' => $this->_set_liberado(),                          'classe' => 'form-control ui-state-default',    'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_receitas.liberado',       'valor' => '' ) ),
                                        array( 'name' => 'id_categoria',    'titulo' => 'Categoria: ',      'tipo' => 'select', 'valor' => $this->culinaria_categorias_model->get_select(), 'classe' => 'form-control ui-state-default',    'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_receitas.id_categoria',   'valor' => '' ) ),
                                        //array( 'name' => 'nome',          'titulo' => 'Nome: ',           'tipo' => 'select', 'valor' => $this->culinaria_receitas_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_receitas.id', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar/" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        private function _set_liberado()
        {
            $retorno = array(
                            (object)array('id' => '0', 'descricao' => 'Aguardando moderação' ),
                            (object)array('id' => '1', 'descricao' => 'Liberado' ),
                
                            );
            return $retorno;
        }
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['ingredientes'] = $this->input->post('ingredientes');
                $data['modo_preparo'] = $this->input->post('modo_preparo');
                $data['liberado'] = ( (isset($data['liberado']) ) ? 1 : 0);
                $data['aceito'] = ( (isset($data['aceito']) ) ? 1 : 0);
		return $data;
	}
}


