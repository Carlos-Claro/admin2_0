<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Encurtador extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'link_encurtado',            'label'   => 'Link encurtado', 		'rules'   => 'trim|required'),
                                array( 'field'   => 'link_encaminhado',           'label'   => 'Link encaminhado',              'rules'   => 'trim|required'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model('encurtador_model');
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
            if(isset($valores['link_encaminhado']) && $valores['link_encaminhado'])
            {
                $valores['link_encaminhado'] = urlencode($valores['link_encaminhado']);
            }
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->encurtador_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->encurtador_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/encurtador.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Encurtador', 'encurtador', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->encurtador_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        $id = $this->encurtador_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Encurtador Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/encurtador.js', TRUE)
				->set_include('js/jquery.md5.min.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Encurtador', 'encurtador', 0)
                                ->set_breadscrumbs('Adicionar', 'encurtador/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_encurtador',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->encurtador_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            $edit = $this->encurtador_model->editar($data,'encurtador.id = '.$codigo);
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
                            $function = strtolower(__FUNCTION__);
                            $class = strtolower(__CLASS__);
                            $data = $this->_inicia_select($codigo);
                            $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                            $data['action_novo'] = base_url().$class.'/adicionar/';
                            $data['tipo'] = 'Encurtador Editar';//$data = $this->_init_selects();
                            $data['item'] = $dados;
                            $data['mostra_id'] = TRUE;
                            $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                            $this->layout
                                    ->set_function( $function )
                                    ->set_include('js/encurtador.js', TRUE)
                                    ->set_include('js/jquery.md5.min.js', TRUE)
                                    ->set_include('css/estilo.css', TRUE)
                                    ->set_breadscrumbs('Painel', 'painel',0)
                                    ->set_breadscrumbs('Encurtador', 'encurtador', 0)
                                    ->set_breadscrumbs('Editar', 'encurtador/editar/'.$codigo, 1)
                                    ->set_usuario($this->set_usuario())
                                    ->set_menu($this->get_menu($class, $function))
                                    ->view('add_encurtador',$data);
			}
		}
		else 
		{
			redirect('encurtador/listar');
		}
	}
	
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno = array();
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->encurtador_model->excluir('encurtador.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'link_encurtado', 'titulo' => 'Link Encurtado', 	'link' => str_replace(array('[col]','[ordem]'), array('link_encurtado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link_encurtado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link_encurtado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link_encurtado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'link_encaminhado','titulo' => 'Link encaminhado', 	'link' => str_replace(array('[col]','[ordem]'), array('link_encaminhado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link_encaminhado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link_encaminhado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link_encaminhado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'acesso','titulo' => 'Qtde acessos', 	'link' => str_replace(array('[col]','[ordem]'), array('acesso',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'acesso') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'acesso' ) ? 'ui-state-highlight'.( ($extras['col'] == 'acesso' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
                                        array( 'name' => 'link_encurtado',  'titulo' => 'Link Encurtado: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'encurtador.link_encurtado',     'valor' => '' ) ),
                                        array( 'name' => 'link_encaminhado','titulo' => 'Link Encaminhado: ','tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'encurtador.link_encaminhado',   'valor' => '' ) ),
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
	
        public function verifica_link( $link, $id = NULL )
        {
            $filtro = 'encurtador.link_encurtado like "'.$link.'" ';
            if ( isset($id) && $id != 'undefined' )
            {
                $filtro .= 'AND encurtador.id != '.$id;
            }
            $data = $this->encurtador_model->get_itens($filtro);
            if ( $data['qtde'] > 0 )
            {
                $retorno = 0;
            }
            else
            {
                $retorno = 1;
            }
            echo $retorno;
        }
	
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		if ( !isset($data['id_usuario']) )
                {
                    $data['id_usuario'] = $this->get_id_usuario();
                }
                if(isset($data['link_encaminhado']))
                {
                    $data['link_encaminhado'] = urlencode($this->input->post('link_encaminhado'));
                }
		return $data;
	}
}


