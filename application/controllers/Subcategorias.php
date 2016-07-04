<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de subcategorias
 * @version 1.0
 * @access public
 * @package subcategorias
 */
class Subcategorias extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'data',           'label'   => 'Data', 		'rules'   => 'trim'),
                                array( 'field'   => 'nome',           'label'   => 'Nome', 	'rules'   => 'required'),
                                array( 'field'   => 'listar',           'label'   => 'Listar', 	'rules'   => 'trim'),
                                array( 'field'   => 'views',           'label'   => 'Views', 		'rules'   => 'trim'),
                                array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_categoria',            'label'   => 'Categorias', 		'rules'   => 'required'),
                                );

        /**
         * Constroi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('subcategorias_model','categorias_model'));
	}
	
        /**
         * Seta a classe listar
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->listar();
	}
	
        /**
         * Cria a listagem de subcategorias carregando inicia filtros, itens, total itens,
         * inica listagem, definir a URL da pagina, chama o subcategorias_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais 
         * @param string $id
         * @param type $coluna - coluna de ordenação do banco de dados
         * @param type $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param type $off_set - pagina que esta visualizando
         * @version 1.0
         * @access public
         */
	public function listar($id = '', $coluna = 'nome', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['categoria'] = $id;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $pai = (isset($id) && $id) ? $this->categorias_model->get_item($id) :  NULL;
            $itens = $this->subcategorias_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->subcategorias_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['cabecalho'] = (isset($pai) && $pai) ? $pai : NULL ;
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/subcategorias.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Categorias', 'categorias', 0)
                        ->set_breadscrumbs((isset($pai) && $pai) ? $pai->titulo : '', 'categorias', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * Exportar uma lista subcategorias para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->subcategorias_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e adiciona os campos de valida no banco de dados com suas validações
         * @param string $id_categoria - se referencia pelo id de categoria
         * @return void - redireciona ou monta o formulario
         * @version 1.0
         * @access public
         */
	public function adicionar( $id_categoria = '' )
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        
                        if(isset($data['data'])  && !empty($data['data']) )
                        {
                            $exp_a = explode('-', $data['data']);
                            $data['data'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                        }
                        
			$id = $this->subcategorias_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/'.$id_categoria.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $categoria = $this->categorias_model->get_item($id_categoria);
			$data['action'] = base_url().$class.'/'.$function.'/'.$id_categoria;
			$data['tipo'] = 'Subcategorias Adicionar';	
                        $data['id_categoria'] = $id_categoria;	
			$this->layout
				->set_function( $function )
				->set_include('js/subcategorias.js', TRUE)
                                ->set_include('js/ckeditor/ckeditor.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Categorias', 'categorias', 0)
                                ->set_breadscrumbs($categoria->titulo, 'subcategorias/listar/'.$id_categoria, 0)
                                ->set_breadscrumbs('Adicionar', 'subcategorias/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_subcategorias',$data);
		}   
		 
	}
        
        /**
         * Monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo - com o registro a ser editado
         * @param string $id_categoria - se referencia pelo id de categoria
         * @param bool $ok - verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $id_categoria = NULL, $ok = FALSE)
	{
		$dados = $this->subcategorias_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            
                            if(isset($data['data'])  && !empty($data['data']) )
                            {
                                $exp_a = explode('-', $data['data']);
                                $data['data'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                            }
                            
                            $id = $this->subcategorias_model->editar($data, array('subcategorias.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/'.$data['id_categoria'].'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $categoria = $this->categorias_model->get_item($id_categoria);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo.'/'.$id_categoria;
				$data['action_novo'] = base_url().$class.'/adicionar/'.$id_categoria;
                                $data['tipo'] = 'Subcategorias Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/subcategorias.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Categorias', 'categorias', 0)
                                        ->set_breadscrumbs($categoria->titulo, 'subcategorias/listar/'.$id_categoria, 0)
                                        ->set_breadscrumbs($dados->titulo, 'subcategorias/listar/'.$id_categoria, 1)
                                        //->set_breadscrumbs('Editar', 'subcategorias/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_subcategorias',$data);
			}
		}
		else 
		{
			redirect('subcategorias/listar/'.$id_categoria);
		}
	}
        
        /**
         * Monta o link e o mostra
         * @version 1.0
         * @access public
         */
        public function gera_link() 
        {
            $classe = strtolower(__CLASS__);
            $link = $this->monta_link($classe);
            echo $link;
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
            $retorno['pai'] =  $this->categorias_model->get_select();
            $retorno['selecionado'] = (isset($id) && $id) ? $this->subcategorias_model->get_selected($id) : '';
            return $retorno;
        }
        
        /**
         * Deleta uma subcategoria e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->subcategorias_model->excluir('subcategorias.id in ('.implode(',',$selecionados).')');
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
         * Cria uma lista de subcategorias no estilo listagem normal,
         * chama os campos necessarios para criar o cabeçalho e definir
         * id como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar
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
                                                    //(object)array( 'chave' => 'categoria','titulo' => 'Categoria', 	'link' => str_replace(array('[col]','[ordem]'), array('categoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'categoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'categoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'categoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),                        
                                                    (object)array( 'chave' => 'nome', 'titulo' => 'Subcategoria', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'views','titulo' => 'Views', 	'link' => str_replace(array('[col]','[ordem]'), array('views',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'views') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'views' ) ? 'ui-state-highlight'.( ($extras['col'] == 'views' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
        
        /**
         * Cria um filtro por id e categoria,
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'subcategorias.id', 	'valor' => '' ) ),
                                        //array( 'name' => 'categoria',           'titulo' => 'Categoria: ',         'tipo' => 'select', 'valor' => $this->categorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'categorias.id', 		'valor' => '' ) ),
                                        //array( 'name' => 'subcategoria',           'titulo' => 'Subcategoria: ',         'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'subcategorias.id', 		'valor' => '' ) ),
                                        array( 'name' => 'categoria',           'titulo' => 'Categoria: ',         'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'categorias.id', 		'valor' => '' ) ),
                                        //array( 'name' => 'subcategoria',           'titulo' => 'Subcategoria: ',         'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'subcategorias.id', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar/'.$valores['categoria'].'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data - com todos os campos setados do formulario
         * @version 1.0
         * @access private
         */
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
        
        public function get_select( $id_categoria = FALSE, $tipo_retorno = 'json' )
        {
            if ( $id_categoria )
            {
                $filtro = 'id_categoria = '.$id_categoria;
                $valores = $this->subcategorias_model->get_select($filtro,FALSE);
                if ( isset($valores) )
                {
                    $retorno['status'] = TRUE;
                    $config['valor'] = $valores; 
                    $config['nome'] = 'id_subcategoria'; 
                    $config['extra'] = 'class="form-control"'; 
                    $retorno['valores'] = form_select($config, 0 ); 
                }
                else
                {
                    $retorno['status'] = FALSE;
                }
            }
            else
            {
                $retorno['status'] = FALSE;
            }
            if ( $tipo_retorno =='json' )
            {
                echo json_encode($retorno);
            }
            else
            {
                return $retorno;
            }
        }
}


