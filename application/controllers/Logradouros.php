<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logradouros extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'cepi',            'label'   => 'CEP I', 		'rules'   => 'trim'),
                                array( 'field'   => 'logradouro',            'label'   => 'Logradouro', 'rules'   => 'trim|required'),
                                array( 'field'   => 'bairro',            'label'   => 'Bairro',         'rules'   => 'trim|required'),
                                array( 'field'   => 'inicio',            'label'   => 'Numero INicio', 		'rules'   => 'trim'),
                                array( 'field'   => 'final',            'label'   => 'Numero final', 		'rules'   => 'trim'),
                                array( 'field'   => 'km',            'label'   => 'km', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_cidade',            'label'   => 'Id da cidade', 		'rules'   => 'trim|required'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model('logradouros_model');
	}
	
	public function index()
	{
            $this->listar();
	}
	
	public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->logradouros_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->logradouros_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/logradouros.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Logradouros', 'logradouros', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->logradouros_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $id = $this->logradouros_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Logradouros Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/logradouros.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Logradouros', 'logradouros', 0)
                                ->set_breadscrumbs('Adicionar', 'logradouros/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_logradouros',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->logradouros_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            $edit = $this->logradouros_model->editar($data,'logradouros.id = '.$codigo);
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
                            $function = strtolower(__FUNCTION__);
                            $class = strtolower(__CLASS__);
                            $data = $this->_inicia_select($codigo);
                            $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                            $data['action_novo'] = base_url().$class.'/adicionar/';
                            $data['tipo'] = 'Logradouros Editar';//$data = $this->_init_selects();
                            $data['item'] = $dados;
                            $data['mostra_id'] = TRUE;
                            $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                            $this->layout
                                    ->set_function( $function )
                                    ->set_include('js/logradouros.js', TRUE)
                                    ->set_include('css/estilo.css', TRUE)
                                    ->set_breadscrumbs('Painel', 'painel',0)
                                    ->set_breadscrumbs('Logradouros', 'logradouros', 0)
                                    ->set_breadscrumbs('Editar', 'logradouros/editar/'.$codigo, 1)
                                    ->set_usuario($this->set_usuario())
                                    ->set_menu($this->get_menu($class, $function))
                                    ->view('add_logradouros',$data);
			}
		}
		else 
		{
			redirect('logradouros/listar');
		}
	}
	
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno = array();
            $this->load->model('cidades_model');
            $retorno['cidades'] = $this->cidades_model->get_select();
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->logradouros_model->excluir('logradouros.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'cep', 'titulo' => 'CEP', 	'link' => str_replace(array('[col]','[ordem]'), array('cep',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cep') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cep' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cep' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'logradouro','titulo' => 'Logradouro', 	'link' => str_replace(array('[col]','[ordem]'), array('logradouro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'logradouro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'logradouro' ) ? 'ui-state-highlight'.( ($extras['col'] == 'logradouro' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'cidade','titulo' => 'Cidade', 	'link' => str_replace(array('[col]','[ordem]'), array('cidade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cidade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cidade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cidade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'encurtador.id',                             'valor' => '' ) ),
                                        array( 'name' => 'cep',  'titulo' => 'CEP: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'logradouros.cep',     'valor' => '' ) ),
                                        array( 'name' => 'logradouro','titulo' => 'Logradouro: ','tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'logradouros.logradouro',   'valor' => '' ) ),
                                        array( 'name' => 'cidade','titulo' => 'Cidade: ','tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'logradouros.cidade',   'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
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
            $this->load->model('cidades_model');
		$data = $this->input->post(NULL, TRUE);
		$data['cep'] = str_replace('-', '', $data['cepi']);
                $cidade = $this->cidades_model->get_item($data['id_cidade']);
                $data['cidade'] = $cidade->nome;
		return $data;
	}
}


