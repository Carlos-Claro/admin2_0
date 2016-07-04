<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de canais
 * @version 1.0
 * @access public
 * @package canais
 */
class Canais extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessários do formulário
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'trim'),
                                array( 'field'   => 'library',           'label'   => 'Library', 	'rules'   => 'trim'),
                                array( 'field'   => 'classe',            'label'   => 'Classe',         'rules'   => 'trim'),
                                array( 'field'   => 'title',           'label'   => 'Title', 	'rules'   => 'trim'),
                                array( 'field'   => 'description',           'label'   => 'Description', 	'rules'   => 'trim'),
                                array( 'field'   => 'posicao_menu',           'label'   => 'Posicao Menu', 	'rules'   => 'trim'),
                                array( 'field'   => 'dropdown',           'label'   => 'Dropdown', 	'rules'   => 'trim'),
                                array( 'field'   => 'ordem',           'label'   => 'Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'id_subcategoria',           'label'   => 'Id Subcategoria', 	'rules'   => ''),
                                );
        
        /**
         * Constroi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('canais_model','subcategorias_model','images_model','canais_setor_model','canais_conteudo_model'));
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
         * cria a listagem de canais carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o canais_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($coluna = 'titulo', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['menu_ativo'] = 1;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->canais_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->canais_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/canais.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Canais', 'canais', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista canais para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->canais_model->get_itens( $filtro->get_filtro() );
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
                        $dados_res = ((isset($data['id_subcategoria']) && $data['id_subcategoria']) ? $data['id_subcategoria'] : NULL);
                        unset($data['id_subcategoria']);
			$id = $this->canais_model->adicionar($data);
                        $filtro_deleta = 'id_canais = '.$id; 
                        $this->canais_model->excluir_has($filtro_deleta);
                        if(isset($dados_res) && $dados_res)
                        {
                            $res = array();
                            foreach ($dados_res as $id_res)
                            {
                                $res['id_canais'] = $id ;
                                $res['id_subcategorias'] = $id_res ;
                                $this->canais_model->adicionar_has($res);
                            }
                        }
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Canais Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/canais.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Canais', 'canais', 0)
                                ->set_breadscrumbs('Adicionar', 'canais/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_canais',$data);
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
		$dados = $this->canais_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                $dados_res = ((isset($data['id_subcategoria']) && $data['id_subcategoria']) ? $data['id_subcategoria'] : NULL);
                                unset($data['id_subcategoria']);
                                $id = $this->canais_model->editar($data, 'canais.id = '.$codigo);
                                $filtro_deleta = 'id_canais = '.$codigo; 
                                $this->canais_model->excluir_has($filtro_deleta);
                                if(isset($dados_res) && $dados_res)
                                {
                                    $res = array();
                                    foreach ($dados_res as $id_res)
                                    {
                                        $res['id_canais'] = $codigo ;
                                        $res['id_subcategorias'] = $id_res ;
                                        $this->canais_model->adicionar_has($res);
                                    }
                                }
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['ckeditor_descricao'] = $this->inicia_ckeditor('descricao');
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Canais Editar';
				$data['item'] = $dados;
                                $this->canais_model->get_item_selected($codigo);
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/canais.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Canais', 'canais', 0)
                                        ->set_breadscrumbs($dados->titulo, 'canais', 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_canais',$data);
			}
		}
		else 
		{
			redirect('canais/listar');
		}
	}
	
        /**
         * Inicia todos os selecionaveis do view,
         * sendo eles: item_selected
         * @param bool $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['subcategorias'] = $this->subcategorias_model->get_select();
            if(isset($id) && $id)
            {
                $retorno['canais_subcategorias'] = $this->canais_model->get_item_selected($id);
            }
            return $retorno;
        }
        
        /**
         * Deleta um canal e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
                $quantidade_has = $this->canais_model->excluir_has('canais_subcategorias.id_canais in ('.implode(',',$selecionados).')');
		$quantidade = $this->canais_model->excluir('canais.id in ('.implode(',',$selecionados).')');
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
                                                    //(object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) , 'classe_destino' => 'canais_setor/listar/id/DESC?b[id_pai]=00&b[id_canais]='),
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) , 'classe_destino' => 'canais_setor/listar/[id]'),
                                                    (object)array( 'chave' => 'descricao','titulo' => 'Descricao', 	'link' => str_replace(array('[col]','[ordem]'), array('descricao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'descricao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'descricao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'descricao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'link',  'titulo' => 'Link',    'link' => str_replace(array('[col]','[ordem]'), array('link',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
			
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Atributos', 'class' => 'btn btn-success', 'icone' => '<span class="glyphicon glyphicon-cog"></span>', 'link' => 'canais_atributo/editar/[id]'),
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
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais.id', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',           'tipo' => 'select', 'valor' => $this->canais_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais.id', 	'valor' => '' ) ),
                                        array( 'name' => 'descricao',           'titulo' => 'Descricao: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais.descricao', 		'valor' => '' ) ),
                                        array( 'name' => 'menu_ativo',           'titulo' => 'Ativo: ',         'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'canais.menu_ativo', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
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
            //$link = str_replace(',','+',$data['titulo']);
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
            $filtro = 'canais.link like "'.$dados['link'].'" ';
            if ( isset($data['id']) && $data['id'] != 'undefined' )
            {
                $filtro .= 'AND canais.id != '.$id;
            }
            $data = $this->canais_model->get_itens($filtro);
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
                $data['descricao'] = $this->input->post('descricao');
		if ( ! isset( $data['ativo'] ) )
		{
			$data['ativo'] = 0;
		}
                
		return $data;
	}
}


