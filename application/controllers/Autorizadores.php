<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * págins de autorizadores, precisa implementar total
 * @version 1.0
 * @access public
 * @package autorizadores
 */
class Autorizadores extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario 
         * @var array
         */
	private $valida = array(
                                //array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                //array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                //array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                //array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );
        
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e librarys padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('autorizadores_model'));
	}
        
        
        public function salva_autorizador()
        {
            $dados = $this->_post();
            $dados['cpf'] = str_replace(array('.','-','/'),'',$dados['cpf']);
            $tem = $this->autorizadores_model->get_select($dados);
            if ( isset($tem) )
            {
                $id = $this->autorizadores_model->adicionar($dados);
            }
            else
            {
                $id = $tem[0]->id;
            }
            echo json_encode($id);
        }
        
        public function get_autorizador_por_cpf (  )
        {
            $dados = $this->_post();
            $valor = str_replace(array('.','-','/'),'',$dados['cpf']);
            $filtro = 'autorizadores.cpf like "'.$valor.'"  ';
            $retorno = $this->autorizadores_model->get_itens($filtro);
            $return = (count($retorno['itens']) > 0 ) ? $retorno['itens'] : FALSE;
            
            echo json_encode($return);
        }
        
        
	public function index()
	{
            redirect('painel');
	}
	
	/**
         * corrigir, inativo
         * 
         */
	public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->autorizadores_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->autorizadores_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/autorizadores.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Autorizadores', 'autorizadores', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->autorizadores_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida_cad); 
		if  ( $this->form_validation->run() )
		{
                        $data = $this->_post();
                        $id = $this->autorizadores_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Autorizadores Adicionar';
			$this->layout
				->set_function( $function )
				->set_include('js/autorizadores.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Autorizadores', 'autorizadores/listar', 1)
				->set_usuario($this->set_usuario())
				->set_menu($this->get_menu($classe, $function))
				->view('add_autorizadores',$data);
		}   
		 
	}
	
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->autorizadores_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
				$id = $this->autorizadores_model->editar($data, array('autorizadores.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
				$data['tipo'] = 'Autorizadores Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                                $this->layout
					->set_function( $function )
					->set_include('js/autorizadores.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('autorizadores', 'autorizadores/listar', 1)
					->set_usuario($this->set_usuario())
					->view('add_autorizadores',$data);
			}
		}
		else 
		{
			redirect('autorizadores/listar');
		}
	}
	
        
        private function _inicia_select( ) 
        {
            $retorno = array();
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->autorizadores_model->excluir('autorizadores.id in ('.implode(',',$selecionados).')');
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
                                                    (object)array( 'chave' => 'nome',     'titulo' => 'Nome',       'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'cpf',     'titulo' => 'CPF',       'link' => str_replace(array('[col]','[ordem]'), array('cpf',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cpf') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cpf' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cpf' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nascimento',     'titulo' => 'Nascimento',       'link' => str_replace(array('[col]','[ordem]'), array('nascimento',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nascimento') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nascimento' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nascimento' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'aprovado',     'titulo' => 'aprovado',       'link' => str_replace(array('[col]','[ordem]'), array('aprovado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'aprovado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'aprovado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'aprovado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'autorizadores.id', 	'valor' => '' ) ),
                                        array( 'name' => 'cpf',   'titulo' => 'cpf: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'autorizadores.cpf', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',   'titulo' => 'nome: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'autorizadores.nome', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;              
 		//$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
	private function _post()
	{
            $data = $this->input->post(NULL, TRUE);

            return $data;
	}
}
