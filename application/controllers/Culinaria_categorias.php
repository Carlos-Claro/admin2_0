<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culinaria_Categorias extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'nome',           'label'   => 'Nome', 		'rules'   => 'required'),
                                array( 'field'   => 'ordem',           'label'   => 'Ordem', 		'rules'   => 'trim'),
                                array( 'field'   => 'liberado',            'label'   => 'Liberado', 		'rules'   => 'trim'),
                                array( 'field'   => 'link',           'label'   => 'Link', 	'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model('culinaria_categorias_model');
	}
	
	public function index()
	{
            $this->listar();
	}
        	
	public function listar($coluna = 'nome', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->culinaria_categorias_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->culinaria_categorias_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/culinaria_categorias.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->culinaria_categorias_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $id = $this->culinaria_categorias_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Culinaria Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/culinaria_categorias.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 0)
                                ->set_breadscrumbs('Adicionar', 'culinaria_categorias/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_culinaria_categorias',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->culinaria_categorias_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            $id = $this->culinaria_categorias_model->editar($data, array('culinaria_categorias.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Culinaria ';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/culinaria_categorias.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Culinaria', 'culinaria_categorias', 0)
                                        ->set_breadscrumbs($dados->titulo, 'culinaria_categorias', 1)
                                        //->set_breadscrumbs('Editar', 'culinaria_categorias/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_culinaria_categorias',$data);
			}
		}
		else 
		{
			redirect('culinaria_categorias/listar');
		}
	}
        
        public function gera_link() 
        {
            $classe = strtolower(__CLASS__);
            $link = $this->monta_link($classe);
            echo $link;
        }
	
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->culinaria_categorias_model->excluir('culinaria_categorias.id in ('.implode(',',$selecionados).')');
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
                                                    //(object)array( 'chave' => 'nome', 'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'culinaria_receitas/listar/id/DESC?b[categoria]=' ),
                                                    (object)array( 'chave' => 'nome', 'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'culinaria_receitas/listar/[id]' ),
                                                    (object)array( 'chave' => 'link',  'titulo' => 'Link',    'link' => str_replace(array('[col]','[ordem]'), array('link',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_categorias.id', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',          'titulo' => 'Nome: ',           'tipo' => 'select', 'valor' => $this->culinaria_categorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'culinaria_categorias.id', 	'valor' => '' ) ),
                                        array( 'name' => 'link',           'titulo' => 'Link: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'culinaria_categorias.link', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['liberado'] = (isset($data['liberado']) ? 1 : 0 );
		return $data;
	}
}


