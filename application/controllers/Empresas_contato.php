<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresas_Contato extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'funcao',           'label'   => 'Função', 		'rules'   => 'trim'),
                                array( 'field'   => 'nome',            'label'   => 'Nome', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'telefone',            'label'   => 'Telefone', 		'rules'   => 'trim'),
                                array( 'field'   => 'email',           'label'   => 'Email', 		'rules'   => 'trim'),
                                array( 'field'   => 'obs',           'label'   => 'Observação', 		'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('empresas_contato_model','empresas_model'));
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
            $itens = $this->empresas_contato_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->empresas_contato_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/empresas_contato.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Empresas Contato', 'empresas_contato', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->empresas_contato_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        unset($data['busca_empresa']);
			$id = $this->empresas_contato_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Empresas Contato Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/empresas_contato.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Empresas Contato', 'empresas_contato', 0)
                                ->set_breadscrumbs('Adicionar', 'empresas_contato/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_empresas_contato',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->empresas_contato_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                unset($data['busca_empresa']);
                                if(!isset($data['principal']))
                                {
                                    $data['principal'] = 0;
                                }
                                if(!isset($data['status']))
                                {
                                    $data['status'] = 0;
                                }
                                
                                $id = $this->empresas_contato_model->editar($data, 'empresas_contato.id = '.$codigo);
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Empresas Contato Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/empresas_contato.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Empresas Contato', 'empresas_contato', 0)
                                        ->set_breadscrumbs($dados->nome, 'empresas_contato/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_empresas_contato',$data);
			}
		}
		else 
		{
			redirect('empresas_contato/listar');
		}
	}
	
        private function _inicia_select( $id = FALSE ) 
        {
            if(isset($id) && $id)
            {
                $contato = $this->empresas_contato_model->get_item($id);
                $retorno['empresa'] = $this->empresas_model->get_item($contato->id_empresa);
            }
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->empresas_contato_model->excluir('empresas_contato.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'empresa', 'titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome',  'titulo' => 'Nome',    'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'funcao','titulo' => 'Função', 	'link' => str_replace(array('[col]','[ordem]'), array('funcao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'funcao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'funcao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'funcao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_contato.id', 	'valor' => '' ) ),
                                        array( 'name' => 'empresa',          'titulo' => 'Empresa: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',          'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_contato.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'funcao',           'titulo' => 'Função: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_contato.funcao', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        public function get_empresa($valor = '')
        {
            $retorno = $this->empresas_model->get_select('(empresa_nome_fantasia like "%'.$valor.'%" OR empresa_razao_social like "%'.$valor.'%") AND empresas.servicos_pagina_inicio < "'.time(date('Y-m-d H:i:s')).'" AND empresas.servicos_pagina_termino > "'.time(date('Y-m-d H:i:s')).'" AND empresas.servicos_pagina = 1');
            echo json_encode($retorno);
        }
        
        
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}


