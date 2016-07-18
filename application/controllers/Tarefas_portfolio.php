<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de portfolios
 * @version 1.0
 * @access public
 * @package tarefas
 */
class Tarefas_portfolio extends MY_Controller 
{
        /**
         * cria um array de 2 posições para validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo',                          'rules'   => 'required'),
                                array( 'field'   => 'descricao',        'label'   => 'Descricao',                       'rules'   => 'trim'),
                                array( 'field'   => 'demanda_semanal',  'label'   => 'Demanda Semanal',                 'rules'   => 'trim'),
                                array( 'field'   => 'data_inicio',      'label'   => 'Data Inicio',                     'rules'   => 'trim'),
                                array( 'field'   => 'data_fim',         'label'   => 'Data Fim',                        'rules'   => 'trim'),
                                array( 'field'   => 'id_responsavel',   'label'   => 'O Responsável é obrigatório',     'rules'   => 'trim|required'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e helpers padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('tarefas_portfolio_model'));
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
         * criar a listagem de tarefas caregando o inicia filtros, itens, total itens, inicia listagem,
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param int $off_set - pagina que esta vizualizando
         * @version 1.0
         * @access public
         */
        public function listar( $coluna = 'tarefas_portfolio.titulo', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_portfolio_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_portfolio_model->get_total_itens( $filter );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$off_set.'/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/tarefas_portfolio.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Portfolio', 'tarefas_portfolio', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        /**
         *
         * @param int $id
         * @param bollean $ok
         * @version 1.0
         * @access public
         */
        public function editar ( $id = NULL, $ok = NULL )
        {
            $this->form_validation->set_rules($this->valida);
            if ($this->form_validation->run())
            {
                $dados = $this->_post();
                if ( isset($id) )
                {
                    $affect = $this->tarefas_portfolio_model->editar($dados, 'tarefas_portfolio.id = '.$id);
                    if ( $affect && $affect > 0 )
                    {
                        redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id.'/1','refresh');
                    }
                    else
                    {
                        redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id.'/2','refresh');
                    }
                }
                else
                {
                    $id_portfolio = $this->tarefas_portfolio_model->adicionar($dados);
                    if( isset($id_portfolio) && $id_portfolio )
                    {
                        redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_portfolio.'/1','refresh');
                    }
                    else
                    {
                        redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_portfolio.'/2','refresh');
                    }
                }
            }
            else
            {
                $data = $this->_inicia_select();
                $classe = __CLASS__;
                $function = __FUNCTION__;
                $layout = $this->layout;
                $layout->set_breadscrumbs('Painel', 'painel',0)->set_breadscrumbs('Portfolio', 'tarefas_portfolio', 0);
                if ( isset($ok) )
                {
                    if ( $ok == 1 )
                    {
                        $data['erro']['class'] = 'alert alert-success';
                        $data['erro']['texto'] = 'Seu Projeto foi salvo com sucesso.';
                    }
                    else
                    {
                        $data['erro']['class'] = 'alert alert-danger';
                        $data['erro']['texto'] = 'A ação teve problemas para ser executada, ou salvo sem modificação.';
                    }
                }
                $data['item'] = (isset($id)) ? $this->tarefas_portfolio_model->get_item($id) : FALSE;
                if ( isset($id) )
                {
                    $layout->set_breadscrumbs('Editar', 'editar/'.$id,1);
                }
                else
                {
                    $layout->set_breadscrumbs('Adicionar', 'editar/',1);
                }
                
                $data['action'] = base_url().strtolower($classe).'/'.strtolower($function).'/'.( isset($id) ? $id : '' );
                $layout
                            ->set_classe( $classe )
                            ->set_function( $function ) 
                            ->set_include('js/listar.js', TRUE)
                            ->set_include('js/tarefas_portfolio.js', TRUE)
                            ->set_include('js/tarefas_projetos.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->set_usuario()
                            ->set_menu($this->get_menu($classe, $function))
                            ->view('add_tarefas_portfolio',$data);	
            }
            
        }
        
        /**
         * Inicia todos os selecionaveis do view,
         * sendo eles: usuarios_selecionados, empresas_selecionados, select_tipo_image,
         * select do usuarios_model, select do tarefas_status_model, id_usuario
         * @param string $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( )
        {
            $this->load->model('usuarios_model');
            $retorno['usuarios'] = $this->usuarios_model->get_select('usuarios.ativo = 1 AND usuarios.id_empresa = 6288');
            return $retorno;
        }
        /**
         * cria a lista de tarefas no estilo de etiquetas,
         * chama os campos necessarios para criar a cabeçalho e define id campos como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem_etiqueta
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
                    /**
                     * alterações no cabecalho listagem
                     * - cabecalho[]->classe = criação com objetivo de alinhar os elemento dentro da etiqueta
                     * - cabecalho[]->link = alteração da funcionalidade para gerenciar o botão que vai ordenar, sendo: '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' 
                     */
                    
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',             'titulo' => 'ID',               'classe' => 'col-lg-2 col-sm-2 col-md-2 col-xs-2 pull-left',    'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'titulo',         'titulo' => 'Titulo',           'classe' => 'col-lg-10 col-sm-10 col-md-10 col-xs-10',          'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? ''.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'responsavel',    'titulo' => 'Gerente Portfólio','classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6 pull-left',    'link' => str_replace(array('[col]','[ordem]'), array('responsavel',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'responsavel') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'responsavel' ) ? ''.( ($extras['col'] == 'responsavel' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'qtde_projetos',    'titulo' => 'Qtde Projetos',  'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6 pull-left',    'link' => str_replace(array('[col]','[ordem]'), array('responsavel',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'responsavel') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'responsavel' ) ? ''.( ($extras['col'] == 'responsavel' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    );
			
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Ver_andamento',  'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right ver_andamento', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
                                                    (object) array('titulo' => 'Ver_aprovados',  'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right ver_aprovados', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
                                                    (object) array('titulo' => 'Ver_todos',  'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right ver_todos', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
                                                    (object) array('titulo' => 'Editar',        'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
                        /**
                         * qtde_por_linha = caso exista vai definir quantos elemento mostrar por linha na listagem, para objetos muito extensos, usar 1
                         * titulo = isset-> vai inserir o titulo do elemento.
                         * chave = utilizado para definir data-item da linha
                         * * load em listagem_etiqueta
                         */
			$data['qtde_por_linha'] = 1;
			$data['chave'] = 'id';
                        
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$data['titulo'] = 'Portfolios';
                        $this->load->library('listagem_etiqueta');
			$this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		}
		return $retorno;
	}
        /**
         * cria um filtro por titulo, descrição e status para a listagem de etiquetas de tarefas
         * cria os botões de exportar e editar
         * @param string $url
         * @param array $valores
         * @return array $retorno - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas_portfolio.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'descricao',       'titulo' => 'Descricao: ',       'tipo' => 'text', 'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas_portfolio.descricao', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
	}
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @param bool $tarefa
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post( )
	{
		$dados = $this->input->post(NULL, TRUE);
                if ( isset($dados['data_inicio']) )
                {
                    $dados['data_inicio'] = converte_data_mysql($dados['data_inicio']);
                }
                else
                {
                    $dados['data_inicio'] = NULL;
                    
                }
                if ( isset($dados['data_fim']) )
                {
                    $dados['data_fim'] = converte_data_mysql($dados['data_fim']);
                }
                else
                {
                    $dados['data_fim'] = NULL;
                }
		return $dados;
	}

    public function interacoes( $coluna = 'tarefas_portfolio.titulo', $ordem = 'ASC', $off_set = 0)
    {
        // praticamente uma copia do listar, com alguns filtros adicionais para listar somente os portfolios necessários.
        // talvez desse pra criar uma variavel adicionar em listar, e reaproveitar melhor a função ao inves de fazer
        // essa.

        $id_usuario = $this->sessao['id'];
        $this->load->model('tarefas_projetos_iteracao_has_usuarios_model');

        // retorna o id dos portfolios que possuem interação aberta
        $ids = $this->tarefas_projetos_iteracao_has_usuarios_model->get_portfolios($id_usuario);

        // caso não tenha nenhuma interação sem ler, redireciona para o listar
        // isso acontece quando o usuario clica pra ver as interações não lidas, depois de marcar tudo como lido
        // da um refresh
        if (!isset($ids) || $ids['qtde'] == 0)
            redirect(strtolower(__CLASS__));

        // como o retorno de portfolio é um vetor de objetos, retiro somente o ID (pra fazer um filtro)
        // mudar o model pra retornar da maneira 'correta'?
        foreach ($ids['itens'] as $id)
            $ids_portfolio[] = $id->id_portfolio;

        // efetua a listagem
        $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem.'/'.$off_set;
        $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
        $filtro = $this->_inicia_filtros( $url, $valores );
        $filter =  $filtro->get_filtro();

        // injeta os ids dos portfolios a serem filtrados diretamente no filtro!
        // o correto seria fazer um novo método no tarefas_portfolio_model?
        $filter[] = array('tipo' => 'where_in', 'campo' => 'tarefas_portfolio.id', 'valor' => $ids_portfolio);

        $itens = $this->tarefas_portfolio_model->get_itens( $filter, $coluna, $ordem, $off_set );
        $total = $this->tarefas_portfolio_model->get_total_itens( $filter );
        $get_url = $filtro->get_url();
        $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
        $data['paginacao'] = $this->init_paginacao($total, $url);
        $data['filtro'] = $filtro->get_html();
        $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$off_set.'/'.$filtro->get_url();
        $extras['col'] = $coluna;
        $extras['ordem'] = $ordem;
        $extras['total_itens'] = $total;
        $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
        
        // zera o contador
        // o correto seria 
        $this->tarefas_projetos_iteracao_has_usuarios_model->zera_contador($id_usuario);
        
        $this->layout
            ->set_classe( $classe )
            ->set_function( $function )
            ->set_include('js/listar.js', TRUE)
            ->set_include('js/tarefas_portfolio.js', TRUE)
            ->set_include('css/estilo.css', TRUE)
            ->set_breadscrumbs('Painel', 'painel',0)
            ->set_breadscrumbs('Portfolio', 'tarefas_portfolio', 1)
            ->set_usuario()
            ->set_menu($this->get_menu($classe, $function))
            ->view('listar',$data);
    }
}
