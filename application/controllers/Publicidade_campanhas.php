<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de categorias
 * @version 1.0
 * @access public
 * @package categorias
 */
class Publicidade_campanhas extends MY_Controller 
{       
        /**
         * Cria um array para validar   a pagina com os campos necessários do formulário
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'area',         'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'id_guia',      'label'   => 'Guia', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_setor',     'label'   => 'Setor', 		'rules'   => 'trim'),
                                array( 'field'   => 'quantia',      'label'   => 'Views', 		'rules'   => 'trim'),
                                array( 'field'   => 'posicao',      'label'   => 'Posição', 		'rules'   => 'trim'),
                                array( 'field'   => 'largura',      'label'   => 'Largura',             'rules'   => 'trim'),
                                array( 'field'   => 'altura',       'label'   => 'Altura',              'rules'   => 'trim'),
                                array( 'field'   => 'peso',         'label'   => 'Peso',                'rules'   => 'trim'),
                                array( 'field'   => 'id_area',      'label'   => 'Area',                'rules'   => 'trim'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model( array('publicidade_areas_model', 'publicidade_campanhas_model', 'empresas_model') );
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
         * cria a listagem de categorias carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o categorias_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($coluna = 'termino', $ordem = 'ASC', $off_set = 0, $resposta = 'inc')
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->publicidade_campanhas_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->publicidade_campanhas_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/publicidade_campanhas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Publicidade Campanhas - Listar', 'publicidade_campanhas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista categorias para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->publicidade_campanhas_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com sua validações
         * @version 1.0
         * @access public
         * @return void  - redireciona ou monta o formulario
         */
	public function adicionar( $id_empresa )
	{
            $function = strtolower(__FUNCTION__);
            $class = strtolower(__CLASS__);
            $data = $this->_inicia_select();
            $data['empresa'] = $this->empresas_model->get_item($id_empresa);
            $data['action'] = base_url().$class.'/'.$function;
            $data['tipo'] = 'Publicidade Áreas Adicionar';	
            $this->layout
                    ->set_function( $function )
                    ->set_include('js/publicidade_campanhas.js', TRUE)
                    ->set_include('css/estilo.css', TRUE)  
                    ->set_breadscrumbs('Painel', 'painel',0)
                    ->set_breadscrumbs('Publicidade Campanhas', 'publicidade_campanhas', 0)
                    ->set_breadscrumbs('Adicionar', 'publicidade_campanhas/adicionar', 1)
                    ->set_usuario($this->set_usuario())
                    ->set_menu($this->get_menu($class, $function))
                    ->view('add_publicidade_campanhas',$data);
	}
        
        /**
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar_fora( $codigo = NULL )
	{
            $dados = $this->publicidade_campanhas_model->get_item($codigo);
            if ($dados)
            {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data = $this->_inicia_select();
                $data['item'] = $dados;
                $this->layout
                            ->set_function( $function )
                            ->set_include('js/publicidade_campanhas.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->set_usuario($this->set_usuario())
                            ->view('add_publicidade_campanhas',$data, 'layout/sem_head',TRUE);
            }
            else 
            {
                $retorno = 'Nenhum item selecionado, contate o administrador.';
            }
            echo $retorno;
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
		$dados = $this->publicidade_campanhas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            $id = $this->publicidade_campanhas_model->editar($data, array('publicidade_campanhas.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data = $this->_inicia_select();
				$data['tipo'] = 'Publicidade Campanhas Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
                                        ->set_include('js/publicidade_campanhas.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Publicidade Campanhas', 'publicidade_campanhas', 0)
                                        ->set_breadscrumbs('editar', '', 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_publicidade_campanhas',$data);
			}
		}
		else 
		{
			redirect('publicidade_areas/listar');
		}
	}
        
        /**
         * @param string $codigo com o registro a ser editado
         * @version 1.0
         * @access public
         */
	public function estatisticas($codigo = NULL )
	{
            die('Em construção');
	}
        
        /**
         * Deleta uma categoria e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->publicidade_campanhas_model->excluir('publicidade_campanhas.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
        private function _inicia_select( $id_guia = FALSE )
        {
            $retorno = array();
            $retorno['tipo_'][] = (object)array('id' => 'periodo', 'descricao' => 'Periodo');
            $retorno['tipo_'][] = (object)array('id' => 'limite', 'descricao' => 'Limite');
            $retorno['publicidade_areas'] = $this->publicidade_areas_model->get_select();
            return $retorno;
        }
        
        /**
         * Cria uma lista de categorias no estilo listagem normal,
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
                    $itens_cabecalho = array(
                                            (object)array('id' => 'id', 'descricao' => 'ID'),
                                            (object)array('id' => 'area', 'descricao' => 'Area'),
                                            (object)array('id' => 'empresa', 'descricao' => 'Empresa'),
                                            (object)array('id' => 'periodo', 'descricao' => 'Periodo'),
                                            );
                    
                    foreach ( $itens_cabecalho as $cabecalho )
                    {
			$data['cabecalho'][] = (object)array( 'chave' => $cabecalho->id,     'titulo' => $cabecalho->descricao,       'link' => str_replace(array('[col]','[ordem]'), array($cabecalho->id,( ($extras['ordem'] == 'ASC' && $extras['col'] == $cabecalho->id) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $cabecalho->id ) ? 'ui-state-highlight'.( ($extras['col'] == $cabecalho->id && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                    }
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    (object) array('titulo' => 'Estatisticas', 'class' => 'btn btn-default', 'icone' => '<span class="glyphicon glyphicon-equalizer"></span>'),
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
         * Cria um filtro por id, titulo, secricao e menu_ativo
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
                                        array( 'name' => 'id',          'titulo' => 'ID: ',         'tipo' => 'text',   'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'publicidade_areas.id', 	'valor' => '' ) ),
                                        array( 'name' => 'area',        'titulo' => 'Titulo: ',     'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'publicidade_areas.area', 	'valor' => '' ) ),
                                        array( 'name' => 'posicao',     'titulo' => 'Posição: ',    'tipo' => 'select',   'valor' => $this->publicidade_areas_model->get_select_posicao(), 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'categorias.link', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <button type="button" class="btn btn-warning">Para adicionar novas campanhas utilize o cadastro da empresa</button>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}

        public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post(FALSE);
            $data['id_empresa'] = $dados['id_empresa'];
            if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
            {
            
                if ( is_array($dados['campo']) )
                {
                    $filtro = 'publicidade_campanhas.id = '.$dados['id'];
                    for( $a = 0; $a < count($dados['campo']); $a++ )
                    {
                        $data[$dados['campo'][$a]] = $dados['valor'][$a];
                        
                    }
                }
                else
                {
                    $filtro = 'publicidade_campanhas.id = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                }
                $afetados = $this->publicidade_campanhas_model->editar($data, $filtro);
                if ( $afetados > 0 )
                {
                    $retorno['status'] = TRUE;
                    $retorno['id'] = $dados['id'];
                    $retorno['muda_url'] = FALSE;
                }
                else
                {
                    $retorno['status'] = TRUE;
                    $retorno['id'] = $dados['id'];
                    $retorno['muda_url'] = FALSE;
                }
            }
            else
            {
                $data[$dados['campo']] = $dados['valor'];
                $retorno['id'] = $this->publicidade_campanhas_model->adicionar($data);
                if ( isset($retorno['id']) && $retorno['id'] )
                {
                    $retorno['status'] = TRUE;
                    $retorno['muda_url'] = base_url().'publicidade_campanhas/editar/'.$retorno['id'].'/';
                }
                else
                {
                    $retorno['status'] = FALSE;
                    $retorno['mensagem'] = 'Não foi possivel adicionar';
                    $retorno['muda_url'] = FALSE;
                }
            }
            echo json_encode($retorno);
        }
        
        private function deleta_image( $campo, $id )
        {
            $item = $this->publicidade_campanhas_model->get_item($id);
            if ( isset($item) )
            {
                $image = $item->$campo;
                unlink( str_replace( '/admin2_0', '', getcwd() ).'/publicidade/'.$image);
            }
        }
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post( $edita = FALSE )
	{
		$data = $this->input->post(NULL, TRUE);
                if ( ! $edita )
                {
                    if ( isset($data['campo']) && $data['campo'] == 'inicio' ) 
                    {
                        $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                    }
                    if ( isset($data['campo']) && $data['campo'] == 'termino' ) 
                    {
                        $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                    }
                    if ( isset($data['campo']) && ( $data['campo'] == 'banner' || $data['campo'] == 'banner_alternativo' ) ) 
                    {
                        if ( empty($data['valor']) )
                        {
                            $this->deleta_image($data['campo'], $data['id']);
                        }
                    }
                }
		return $data;
	}
}


