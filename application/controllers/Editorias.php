<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de editorias
 * @version 1.0
 * @access public
 * @package canais
 */
class Editorias extends MY_Controller 
{       
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario
         * @var array 
         */
	private $valida = array(
                                array( 'field'   => 'nome',           'label'   => 'Nome', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'link',           'label'   => 'Link', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_canais',            'label'   => 'Canais', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'cor',            'label'   => 'Cor', 		'rules'   => 'trim'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void* 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('editorias_model','canais_model'));
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
         * cria a listagem de editorias carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o editorias_model que vai 
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
            $itens = $this->editorias_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->editorias_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/editorias.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Editorias', 'editorias', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista editorias para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->editorias_model->get_itens( $filtro->get_filtro() );
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
                        $data['cor'] = str_replace('#', '', $data['cor']);
			$id = $this->editorias_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Editorias Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/editorias.js', TRUE)
                                ->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
				->set_include('js/colorpicker/docs.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
				->set_include('css/colorpicker/docs', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Editorias', 'editorias', 0)
                                ->set_breadscrumbs('Adicionar', 'editorias/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_editorias',$data);
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
		$dados = $this->editorias_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                                $data = $this->_post();
                                $data['cor'] = str_replace('#', '', $data['cor']);
                                $id = $this->editorias_model->editar($data, 'editorias.id = '.$codigo);
                                redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data = $this->inicia_select();
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Editorias Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/editorias.js', TRUE)
                                        ->set_include('js/colorpicker/bootstrap-colorpicker.min.js', TRUE)
                                        ->set_include('js/colorpicker/docs.js', TRUE)
                                        ->set_include('css/estilo.css', TRUE)  
                                        ->set_include('css/colorpicker/bootstrap-colorpicker.min.css', TRUE)  
                                        ->set_include('css/colorpicker/docs', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Editorias', 'editorias', 0)
                                        ->set_breadscrumbs($dados->nome, 'editorias/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_editorias',$data);
			}
		}
		else 
		{
			redirect('editorias/listar');
		}
	}
	
        /**
         * Deleta uma editoria e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->editorias_model->excluir('editorias.id in ('.implode(',',$selecionados).')');
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
         * 
         * @return array
         * @version 1.0
         * @access private
         */
        private  function inicia_select()
        {
            $retorno['canais'] = $this->canais_model->get_select();
            return $retorno;
        }

        /**
         * Cria uma lista de editorias no estilo listagem normal,
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
                                                    (object)array( 'chave' => 'nome', 'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'link',  'titulo' => 'Link',    'link' => str_replace(array('[col]','[ordem]'), array('link',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'canal','titulo' => 'Canal', 	'link' => str_replace(array('[col]','[ordem]'), array('canal',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'canal') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'canal' ) ? 'ui-state-highlight'.( ($extras['col'] == 'canal' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por id, titulo, link e id_canais
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'editorias.id', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',          'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'editorias.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'link',           'titulo' => 'Link: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'editorias.link', 		'valor' => '' ) ),
                                        array( 'name' => 'id_canais',           'titulo' => 'Canal: ',         'tipo' => 'select', 'valor' => $this->canais_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'editorias.id_canais', 		'valor' => '' ) ),
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
            $filtro = 'editorias.link like "'.$dados['link'].'" ';
            if ( isset($data['id']) && $data['id'] != 'undefined' )
            {
                $filtro .= 'AND editorias.id != '.$id;
            }
            $data = $this->editorias_model->get_itens($filtro);
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


