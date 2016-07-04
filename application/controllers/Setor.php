<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de setor
 * @version 1.0
 * @access public
 * @package setor
 */
class Setor extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );

        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model('setores_model');
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
         * cria a listagem de setores carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o setores_model que vai 
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
            $valores['id_pai'] = '00';
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->setores_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->setores_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/setor.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Setores', 'setor', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista setores para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->setores_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com suas validações
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
			$id = $this->setores_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Setores Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/setor.js', TRUE)
                                ->set_include('js/ckeditor/ckeditor.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Setores', 'setor', 0)
                                ->set_breadscrumbs('Adicionar', 'setor/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_setor',$data);
		}   
		 
	}
	
        /**
         * 
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->setores_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $data['ativo'] = ( (isset($data['ativo']) && $data['ativo'] ) ? 1 : 0);
                                $data['painel'] = ( (isset($data['painel']) && $data['painel'] ) ? 1 : 0);
				$id = $this->setores_model->editar($data, array('setores.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                //$pai = $this->setores_model->get_item($dados->id_pai);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Setores Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/setor.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Setores', 'setor', 0)
                                        ->set_breadscrumbs($dados->titulo, 'setor/listar/', 1)
                                        //->set_breadscrumbs('Editar', 'setor/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_setor',$data);
			}
		}
		else 
		{
			redirect('setor/listar');
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
            $retorno['pai'] = $this->setores_model->get_select();
            return $retorno;
        }
        
        /**
         * Deleta um setor e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->setores_model->excluir('setores.id in ('.implode(',',$selecionados).')');
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
         * Cria uma lista de setores no estilo listagem normal,
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
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ), 'classe_destino' => 'setor/listar_nivel_2/[id]' ),
                                                    (object)array( 'chave' => 'classe','titulo' => 'Classe', 	'link' => str_replace(array('[col]','[ordem]'), array('classe',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'classe') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'classe' ) ? 'ui-state-highlight'.( ($extras['col'] == 'classe' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'ativo',  'titulo' => 'Ativo',    'link' => str_replace(array('[col]','[ordem]'), array('ativo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ativo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'ativo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'ativo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'pai',  'titulo' => 'Setor Pai',    'link' => str_replace(array('[col]','[ordem]'), array('pai',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'pai') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'pai' ) ? 'ui-state-highlight'.( ($extras['col'] == 'pai' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por id, titulo, classe e id_pai
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'setores.id', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'setores.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'classe',           'titulo' => 'Classe: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'setores.classe', 		'valor' => '' ) ),
                                        array( 'name' => 'id_pai',           'titulo' => 'Id Pai: ',         'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'setores.id_pai', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
        // Nivel 2
        /**
         * cria a listagem de setores carregando inicia filtros nivel 2, itens, total itens,
         * inicia listagem nivel 2, difinir a URL da página, chama o setores_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
        public function listar_nivel_2($id =  '', $coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_pai'] = $id;
            $filtro = $this->_inicia_filtros_nivel_2( $url, $valores );
            $pai = (isset($id) && $id) ? $this->setores_model->get_item($id) :  NULL;
            $itens = $this->setores_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->setores_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ;
            $data['listagem'] = $this->_inicia_listagem_nivel_2( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/setor.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Setores', 'setor', 0)
                        ->set_breadscrumbs( $pai->titulo, 'canais_setor', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com suas validações
         * @param type $id_pai
         * @version 1.0
         * @access public
         * @return void  - redireciona ou monta o formulario
         */
        public function adicionar_nivel_2( $id_pai = '' )
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			$id = $this->setores_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar_nivel_2/'.$id.'/'.$id_pai.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $pai = $this->setores_model->get_item($id_pai);
			$data['action'] = base_url().$class.'/'.$function.'/'.$id_pai;
			$data['tipo'] = 'Setores Adicionar';	
                        $data['id_pai'] = $id_pai;	
			$this->layout
				->set_function( $function )
				->set_include('js/setor.js', TRUE)
                                ->set_include('js/ckeditor/ckeditor.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Setores', 'setor', 0)
                                ->set_breadscrumbs($pai->titulo, 'setor/listar_nivel_2/'.$id_pai, 0)
                                ->set_breadscrumbs('Adicionar', 'setor/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_setor',$data);
		}   
		 
	}
        
        /**
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param type $id_pai
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
        public function editar_nivel_2($codigo = NULL, $id_pai= '', $ok = FALSE)
	{
		$dados = $this->setores_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $data['ativo'] = ( (isset($data['ativo']) && $data['ativo'] ) ? 1 : 0);
                                $data['painel'] = ( (isset($data['painel']) && $data['painel'] ) ? 1 : 0);
				$id = $this->setores_model->editar($data, array('setores.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar_nivel_2/'.$codigo.'/'.$data['id_pai'].'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $pai = $this->setores_model->get_item($id_pai);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo.'/'.$id_pai;
				$data['action_novo'] = base_url().$class.'/adicionar_nivel_2/'.$id_pai;
                                $data['tipo'] = 'Setores Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/setor.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Setores', 'setor', 0)
                                        ->set_breadscrumbs($pai->titulo, 'setor/listar_nivel_2/'.$id_pai, 0)
                                        ->set_breadscrumbs($dados->titulo, 'setor/listar_nivel_2/', 1)
                                        //->set_breadscrumbs('Editar', 'setor/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_setor',$data);
			}
		}
		else 
		{
			redirect('setor/listar');
		}
	}
        
        /**
         * Cria uma lista de setores nivel 2 no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
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
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'classe','titulo' => 'Classe', 	'link' => str_replace(array('[col]','[ordem]'), array('classe',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'classe') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'classe' ) ? 'ui-state-highlight'.( ($extras['col'] == 'classe' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'ativo',  'titulo' => 'Ativo',    'link' => str_replace(array('[col]','[ordem]'), array('ativo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ativo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'ativo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'ativo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'pai',  'titulo' => 'Setor Pai',    'link' => str_replace(array('[col]','[ordem]'), array('pai',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'pai') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'pai' ) ? 'ui-state-highlight'.( ($extras['col'] == 'pai' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
	
        /**
         * Cria um filtro por id, titulo, classe e id_pai
         * cria botões de adicionar, exportar e deletar selecionados
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros_nivel_2($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'setores.id', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'setores.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'classe',           'titulo' => 'Classe: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'setores.classe', 		'valor' => '' ) ),
                                        array( 'name' => 'id_pai',           'titulo' => 'Id Pai: ',         'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'setores.id_pai', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar_nivel_2/'.$valores['id_pai'].'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
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
		if ( ! isset( $data['ativo'] ) )
		{
			$data['ativo'] = 0;
		}
		return $data;
	}
}
