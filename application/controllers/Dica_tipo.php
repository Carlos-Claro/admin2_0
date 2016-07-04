<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de tipo de dicas
 * @version 1.0
 * @access public
 * @package dica_tipo
 */
class Dica_Tipo extends MY_Controller 
{       
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'tipo',           'label'   => 'Tipo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'id_dica_setor',           'label'   => 'Setor', 		'rules'   => 'required'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('dica_tipo_model','dica_setor_model'));
	}
	
        /**
         * seta a classe listar
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->listar();
	}
	
        /**
         * 
         * cria a listagem de dica tipos carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o dica_tipo_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->dica_tipo_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->dica_tipo_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/dica_tipo.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Dica Tipo', 'dica_tipo', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista dica_tipo para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->dica_tipo_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com sua validações
         * @version 1.0
         * @access public
         * @return void  - redireciona ou monta o formulario
         */
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			$id = $this->dica_tipo_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Dica Tipo Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/dica_tipo.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Dica Tipo', 'dica_tipo', 0)
                                ->set_breadscrumbs('Adicionar', 'dica_tipo/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_dica_tipo',$data);
		}   
		 
	}
        
        /**
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->dica_tipo_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                $id = $this->dica_tipo_model->editar($data, 'dica_tipo.id = '.$codigo);
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Dica Tipo Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/dica.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Dica Tipo', 'dica_tipo', 0)
                                        ->set_breadscrumbs($dados->titulo, 'dica_tipo/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_dica_tipo',$data);
			}
		}
		else 
		{
			redirect('dica/listar');
		}
	}
	
        /**
         * 
         * @param bool $id
         * @return array
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['setor'] = $this->dica_setor_model->get_select();
            return $retorno;
        }
        
        /**
         * Deleta um dica_tipo e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->dica_tipo_model->excluir('dica_tipo.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
        /**
         * Cria uma lista de canais no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
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
                                                    (object)array( 'chave' => 'tipo','titulo' => 'Tipo', 	'link' => str_replace(array('[col]','[ordem]'), array('tipo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'tipo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'tipo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'tipo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
	
        /**
         * Cria um filtro por id, titulo e tipo
         * cria botões de adicionar, exportar e deletar selecionados
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'dica_tipo.id', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'dica_tipo.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'tipo',           'titulo' => 'Tipo: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'dica_tipo.tipo', 		'valor' => '' ) ),
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
	
        /**
         * Chama post e tira acentos para gerar o link
         * @version 1.0
         * @access public
         */
	public function gera_link_automatico()
        {
            $data = $this->_post();
            //$link = str_replace(' ','+',$data['titulo']);
            $link = tira_acento($data['titulo']);
            echo $link;
        }
        
        /**
         * Verifica se link valido
         * @version 1.0
         * @access public
         */
        public function verifica_link( )
        {
            $dados = $this->_post();
            $filtro = 'dica_tipo.link like "'.$dados['link'].'" ';
            if ( isset($data['id']) && $data['id'] != 'undefined' )
            {
                $filtro .= 'AND dica.id != '.$id;
            }
            $data = $this->dica_tipo_model->get_itens($filtro);
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
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private 
         */
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}


