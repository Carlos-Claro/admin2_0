<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de ocorencias
 * @version 1.0
 * @access public
 * @package ocorrencias
 */
class Ocorrencias extends MY_Controller 
{
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('ocorrencias_model','usuario_model','empresas_ocorrencia_assunto_model'));
	}
	
        /**
         * seta a classe abertos
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->abertos();
	}
        
        public function agenda( $tipo = 'completa' )
        {
            $id_usuario = $this->sessao['id'];
            $passado = $this->ocorrencias_model->get_tarefas_abertas($id_usuario,'passado', 'DESC');
            $data['abas'][] = array(
                                    'active' => FALSE,
                                    'classe' => 'passado',
                                    'titulo' => 'Tarefas Passadas',
                                    'conteudo' => $this->_inicia_listagem_agenda($passado)
                                    );
            $presente = $this->tarefas_model->get_tarefas_abertas($id_usuario,'presente', 'ASC');
            $data['abas'][] = array(
                                    'active' => TRUE,
                                    'classe' => 'presente',
                                    'titulo' => 'Para Hoje',
                                    'conteudo' => $this->_inicia_listagem_agenda($presente)
                                    );
            
            $futuro = $this->tarefas_model->get_tarefas_abertas($id_usuario,'futuro', 'ASC');
            $data['abas'][] = array(
                                    'active' => FALSE,
                                    'classe' => 'futuro',
                                    'titulo' => 'Proximos dias',
                                    'conteudo' => $this->_inicia_listagem_agenda($futuro)
                                    );
            
            
            
            $this->layout
                        ->set_classe( __CLASS__ )
                        ->set_function( __FUNCTION__ ) 
                        ->set_include('js/abas.js', TRUE)
                        ->set_include('js/tarefas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Agenda', 'tarefas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu(__CLASS__, __FUNCTION__))
                        ->view('abas',$data);	
            //var_dump($presente);
        }
        
        public function set_qtde_hoje()
        {
            $id_usuario = $this->sessao['id'];
            $presente = $this->tarefas_model->get_tarefas_abertas($id_usuario,'presente');
            echo $presente['qtde'];
        }
        
        private function _inicia_listagem_agenda( $itens, $extras = NULL, $ordenacao = TRUE )
	{
                    /**
                     * alterações no cabecalho listagem
                     * - cabecalho[]->classe = criação com objetivo de alinhar os elemento dentro da etiqueta
                     * - cabecalho[]->link = alteração da funcionalidade para gerenciar o botão que vai ordenar, sendo: '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' 
                     */
			$data['cabecalho'] = array(
                                                    array( 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'titulo',         'titulo' => 'Tarefa / Projeto / Portfolio',     'classe' => 'col-lg-12 col-sm-12 col-md-12 col-xs-12',   ),
                                                        (object)array( 'chave' => 'data_inicio',    'titulo' => 'Data Inicio',  'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? ' '.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'data_fim',       'titulo' => 'Data Fim',     'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? ' '.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'qtde_atividades','titulo' => 'Qtde Atividades',     'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('qtde_atividades',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_atividades') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_atividades' ) ? ' '.( ($extras['col'] == 'qtde_atividades' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                    )),
                                                    
                            );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Verificar', 'class' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6 btn btn-info pull-left', 'icone' => '<span class="glyphicon glyphicon-ok"></span>', 'link' => 'tarefas/editar/0/[id]', 'extra' => ''),
                                                    (object) array('titulo' => 'Finalizar', 'class' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6 btn btn-danger pull-right fechar-tarefa', 'icone' => '<span class="glyphicon glyphicon-remove"></span>', 'extra' => 'data-id="[id]"'),
                                                    );
                        /**
                         * qtde_por_linha = caso exista vai definir quantos elemento mostrar por linha na listagem, para objetos muito extensos, usar 1
                         * titulo = isset-> vai inserir o titulo do elemento.
                         * chave = utilizado para definir data-item da linha
                         * * load em listagem_etiqueta
                         */
			$data['qtde_por_linha'] = 1;
			$data['chave'] = 'id';
                        $data['ordenacao'] = $ordenacao;
                        
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$data['titulo'] = 'Tarefas';
                        $this->load->library('listagem_etiqueta');
			$this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		return $retorno;
	}
        
        /**
         * Redireciona para lista de empresas
         * @version 1.0
         * @access public
         */
        public function nova_ocorrencia()
        {
            redirect('empresas/listar');
        }
	
        /**
         * cria a listagem de ocorrencias carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o ocorrencias_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param String $tipo - qual o estado da ocorrencia
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($tipo = 'abertas', $coluna = 'empresas_ocorrencia.data_retorno_inicio', $ordem = 'DESC', $off_set = 0)
	{
            //$coluna = 'empresas_ocorrencia.id_empresas_status_ocorrencia,empresas_ocorrencia.data_retorno_inicio '
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$tipo.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores, $tipo );
            //$valores['id_usuario_ativo'] = $this->session->userdata('id');
            //$filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->ocorrencias_model->get_itens_ocorrencias( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->ocorrencias_model->get_total_itens_ocorrencias( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$tipo.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem($tipo, $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/canais.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Ocorrencias - '.ucfirst($tipo), 'ocorrencias', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * exportar uma lista ocorrencias para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->ocorrencias_model->get_itens_abertos( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Cria uma lista de operações no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param string $tipo - cada estado da ocorrencia tem suas operações
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
	private function _inicia_listagem( $tipo = '', $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    //(object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'empresa', 'titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) , 'classe_destino' => 'empresas/editar/[id]'),
                                                    (object)array( 'chave' => 'assunto', 'titulo' => 'Assunto', 	'link' => str_replace(array('[col]','[ordem]'), array('assunto',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'assunto') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'assunto' ) ? 'ui-state-highlight'.( ($extras['col'] == 'assunto' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'status', 'titulo' => 'Status', 	'link' => str_replace(array('[col]','[ordem]'), array('status',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'status') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'status' ) ? 'ui-state-highlight'.( ($extras['col'] == 'status' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'prioridade', 'titulo' => 'Prioridade', 	'link' => str_replace(array('[col]','[ordem]'), array('prioridade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'prioridade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'prioridade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'prioridade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'usuarios_nome', 'titulo' => 'Responsável',           'link' => str_replace(array('[col]','[ordem]'), array('usuarios_nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuarios_nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuarios_nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'usuarios_nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'data_inicio', 'titulo' => 'Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'data_retorno',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('data_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'usuario_max','titulo' => 'Usuario de Retorno', 	'link' => str_replace(array('[col]','[ordem]'), array('id_usuario_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_usuario_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_usuario_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_usuario_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'qtde_interacao',  'titulo' => 'Quantidade de Interações',    'link' => str_replace(array('[col]','[ordem]'), array('qtde_interacao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_interacao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_interacao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_interacao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        switch($tipo)
                        {
                            case 'abertas':
                                $data['cabecalho'][] = (object)array( 'chave' => 'data_inicio', 'titulo' => 'Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                $data['cabecalho'][] = (object)array( 'chave' => 'retorno_inicio',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                //$data['cabecalho'][] = (object)array( 'chave' => 'data_retorno',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('data_retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                break;
                            case 'envolvidas':
                                $data['cabecalho'][] = (object)array( 'chave' => 'data_inicio', 'titulo' => 'Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                $data['cabecalho'][] = (object)array( 'chave' => 'data_fim', 'titulo' => 'Ultima ação', 	'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                $data['cabecalho'][] = (object)array( 'chave' => 'retorno_inicio',  'titulo' => 'Data de Retorno',    'link' => str_replace(array('[col]','[ordem]'), array('retorno_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                break;
                            case 'fechadas':
                                $data['cabecalho'][] = (object)array( 'chave' => 'data_fim', 'titulo' => 'Fim', 	'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) );
                                break;
                        }
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
        /**
         * Cria um filtro por id_empresas_status_ocorrencia, empresas_nome_fantasia
         * e id_assunto, opções de aberto, envolvidas, fechadas retornam resultados
         * diferentes
         * @param string $url
         * @param array $valores
         * @param string $tipo - cada estado da ocorrencia tem suas operações 
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array(), $tipo = 'abertas' )
	{
                $config['itens'] = array(
                                        //array( 'name' => 'id_usuario_ativo',              'titulo' => 'Usuario: ',             'tipo' => 'hidden', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_ocorrencia.id_usuario_ativo', 	'valor' => '' ) ),
                                        array( 'name' => 'id_empresas_status_ocorrencia', 'titulo' => 'Usuario: ', 'tipo' => 'hidden', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 	'valor' => '' ) ),
                                        array( 'name' => 'empresas_nome_fantasia',          'titulo' => 'Empresa: ', 'tipo' => 'text', 'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'id_assunto',          'titulo' => 'Assunto: ',             'tipo' => 'select', 'valor' => $this->empresas_ocorrencia_assunto_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_ocorrencia.id_assunto', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',                'titulo' => 'Envolvidos: ',          'tipo' => 'select', 'valor' => $this->usuario_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_ocorrencia.id_usuario_ativo', 	'valor' => '' ) ),
                                        //array( 'name' => 'texto',          'titulo' => 'Titulo: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_ocorrencia.texto', 	'valor' => '' ) ),
                                        //array( 'name' => 'descricao',           'titulo' => 'Descricao: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'canais.descricao', 		'valor' => '' ) ),
                                        );
                
                $valores['usuario'] = $this->sessao['id'];
                $valores['usuario_ativo'] = $this->sessao['id'].')';
                $valores['status_ocorrencia2'] = '2';
                $valores['status_ocorrencia3'] = '3';
                $valores['status_ocorrencia4'] = '4';
                    
                switch($tipo)
                {
                    case 'abertas':
                        $config['itens'][] =  array( 'name' => 'usuario_ativo', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => '' ) );
                        $config['itens'][] =  array( 'name' => 'status_ocorrencia4', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '' ) );
                        break;
                    case 'abertas_geral':
                        //$config['itens'][] =  array( 'name' => 'usuario_ativo', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => '' ) );
                        $config['itens'][] =  array( 'name' => 'status_ocorrencia4', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '' ) );
                        break;
                    case 'envolvidas':
                        $config['itens'][] = array( 'name' => 'usuario', 'where' => array( 'tipo' => 'where',  'campo' => '(empresas_interacao.id_usuario', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'usuario_ativo', 'where' => array( 'tipo' => 'or_where',  'campo' => 'empresas_ocorrencia.id_usuario_ativo', 'valor' => '', 'unescape' => TRUE) );
                        //$config['itens'][] = array( 'name' => 'status_ocorrencia2', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '' ) );
                        //$config['itens'][] = array( 'name' => 'status_ocorrencia3', 'where' => array( 'tipo' => 'or_where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '' ) );
                        $config['itens'][] = array( 'name' => 'status_ocorrencia4', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '', 'unescape' => TRUE ) );
                        break;
                    case 'fechadas':
                        $valores['status_ocorrencia3'] = '3)';
                        $config['itens']['usuario']            =    array( 'name' => 'usuario' , 'where' => array( 'tipo' => 'where',  'campo' => 'empresas_interacao.id_usuario', 'valor' => '' ) );
                        $config['itens']['status_ocorrencia2'] =    array( 'name' => 'status_ocorrencia2' , 'where' => array( 'tipo' => 'where',  'campo' => '(empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => ''  ) );
                        $config['itens']['status_ocorrencia3'] =    array( 'name' => 'status_ocorrencia3' , 'where' => array( 'tipo' => 'or_where',  'campo' => 'empresas_ocorrencia.id_empresas_status_ocorrencia', 'valor' => '', 'unescape' => TRUE ) );
                        break;
                }
                
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		//$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		//$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
}


