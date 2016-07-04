<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_Automatico extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'corpo',           'label'   => 'Corpo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'assinatura',            'label'   => 'Assinatura', 		'rules'   => 'required|trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model('email_automatico_model');
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
            $itens = $this->email_automatico_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->email_automatico_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/email_automatico.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Email Automático', 'email_automatico', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->email_automatico_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			$id = $this->email_automatico_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data['ckeditor_corpo'] = $this->inicia_ckeditor('corpo');
                        $data['ckeditor_assinatura'] = $this->inicia_ckeditor('assinatura');
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Email Automático Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/email_automatico_model.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Email Automático', 'email_automatico', 0)
                                ->set_breadscrumbs('Adicionar', 'email_automatico/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_email_automatico',$data);
		}   
		 
	}
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->email_automatico_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                $this->email_automatico_model->editar($data, 'email_automatico.id = '.$codigo);
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data['ckeditor_corpo'] = $this->inicia_ckeditor('corpo');
                                $data['ckeditor_assinatura'] = $this->inicia_ckeditor('assinatura');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Email Automático Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/email_automatico.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Email Automático', 'email_automatico', 0)
                                        ->set_breadscrumbs($dados->titulo, 'email_automatico/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_email_automatico',$data);
			}
		}
		else 
		{
			redirect('email_automatico/listar');
		}
	}
	
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->email_automatico_model->excluir('email_automatico.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
        
        public function enviar_email_teste()
        {
            $data = $this->_post();
            if(isset($data) && $data)
            {
                $item = $this->email_automatico_model->get_item($data['id']);
                $email['assunto'] = $item->titulo;
                $email['mensagem'] = $item->corpo.$item->assinatura;
                $email['email'] = 'programacao01@pow.com.br';
                $email['to'] = 'programacao01@pow.com.br';
                
                $anexo = $this->email_automatico_model->get_item_anexo($data['id']);
                if(isset($anexo) && $anexo)
                {
                    $anexo = str_replace('admin2_0', '', getcwd().$anexo->anexo);
                    $email['anexo'] = str_replace('[id]', $data['id'], $anexo);
                }
                echo $this->envio($email);
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
                                                    (object)array( 'chave' => 'corpo','titulo' => 'Corpo', 	'link' => str_replace(array('[col]','[ordem]'), array('corpo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'corpo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'corpo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'corpo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    (object) array('titulo' => 'Testar', 'class' => 'btn btn-success', 'icone' => '<span class="glyphicon glyphicon-envelope"></span>'),
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'email_automatico.id', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'email_automatico.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'corpo',           'titulo' => 'Corpo: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'email_automatico.corpo', 		'valor' => '' ) ),
                                        array( 'name' => 'assinatura',           'titulo' => 'Assinatura: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'email_automatico.assinatura', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['corpo'] = $this->input->post('corpo');
                $data['assinatura'] = $this->input->post('assinatura');
		return $data;
	}
}


