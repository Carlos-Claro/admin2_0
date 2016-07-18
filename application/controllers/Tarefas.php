<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de tarefas
 * @version 1.1
 * @access public
 * @package tarefas
 * 2015-09-08 disparo de email.
 */

class Tarefas extends MY_Controller 
{
        /**
         * cria um array de 2 posições para validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'descricao',           'label'   => 'Descricao', 		'rules'   => 'trim'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e helpers padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('tarefas_model', 'tarefas_projetos_model', 'tarefas_status_model',
                'usuario_model','images_model', 'tarefas_projetos_iteracao_has_usuarios_model'));
            $this->load->helper('ckeditor_helper');
            //$this->load->library('anexo_lib');
	}
	
        public function agenda( $tipo = 'completa' )
        {
            $id_usuario = $this->sessao['id'];
            $passado = $this->tarefas_model->get_tarefas_abertas($id_usuario,'passado', 'DESC');
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

        public function set_qtde_iteracoes()
        {
            $id_usuario = $this->sessao['id'];
            $total = $this->tarefas_projetos_iteracao_has_usuarios_model->get_iteracoes_abertas($id_usuario);
            echo $total;
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
         * Definir a URL da pagina,
         * chama o tarefas_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param int $off_set - pagina que esta vizualizando
         * @version 1.0
         * @access public
         */
        public function listar( $coluna = 'tarefas.id_tarefas_status', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_model->get_total_itens( $filter );
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
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/datetimepicker/moment.js', TRUE)
                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                        ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Projetos', 'tarefas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * criar a listagem de tarefas caregando o inicia filtros, itens, total itens, inicia listagem,
         * Definir a URL da pagina,
         * chama o tarefas_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param int $off_set - pagina que esta vizualizando
         * @version 1.0
         * @access public
         */
        public function listar_retorno( $id_tarefas_projeto, $coluna = 'tarefas.id_tarefas_status', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id_tarefas_projeto.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tarefas_projeto'] = $id_tarefas_projeto;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_model->get_total_itens( $filter );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            //$data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/'.$id_tarefas_projeto.'/[col]/[ordem]/'.$off_set.'/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras, FALSE, FALSE, $id_tarefas_projeto );
            echo json_encode($data);
	}
        
        /**
         * criar um relatorio de tarefas em execução caregando o
         * inicia filtros relatorios, itens relatorios, total itens relatorios, inicia listagem relatorios,
         * Definir a URL da pagina,
         * chama o tarefas_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @version 1.0
         * @access public
         */
        public function listar_relatorios( $coluna = 'tarefas_tempo.id_tarefas', $ordem = 'ASC')
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_relatorios( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_model->get_itens_relatorios( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_model->get_total_itens_relatorios( $filter );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$off_set.'/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $data['listagem'] = $this->_inicia_listagem_relatorios( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/datetimepicker/moment.js', TRUE)
                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                        ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Projetos em execução', 'tarefas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * criar um relatorio de todas as tarefas caregando o
         * inicia filtros relatorios tarefas, itens relatorios tarefas,
         * total itens relatorios tarefas, inicia listagem relatorios tarefas,
         * Definir a URL da pagina
         * chama o tarefas_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @version 1.0
         * @access public
         */
        public function listar_relatorios_tarefas( $coluna = 'id', $ordem = 'ASC')
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_relatorios_tarefas( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_model->get_itens_relatorios_tarefas( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_model->get_total_itens_relatorios_tarefas( $filter );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $data['listagem'] = $this->_inicia_listagem_relatorios_tarefas( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/datetimepicker/moment.js', TRUE)
                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                        ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Projetos iniciadas', 'tarefas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        
        
        /*public function relatorios ()
        {
            $retorno = $this->tarefas_model->get_itens_relatorios();
            var_dump($retorno);
            foreach ($retorno['itens'] as $key => $item)
            {
                //var_dump($item->dia);
                //$data_inicio = explode(' ',$item->data_inicio);
                //var_dump($data_inicio);
                //$dia = explode('-', $data_inicio[0]);
                //var_dump($dia);
                //echo 'Dia'.' = '.$dia[2].'<br>';
                //echo $key.'= '.$item->data_inicio.'<br>';
                foreach ($item as $chave => $valor)
                {
                    echo $chave.'= '.$valor.'<br>';
                }
            }
        }*/
        
        /**
         * Exportar uma tarefa para um arquivo excel
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
         * verifica se usuario ja visualizou a tarefa 
         * @version 1.0
         * @access public
         */
        public function verificar_novas()
        {
            $visualizacao = $this->tarefas_model->get_visualizacao_usuario( $this->get_id_usuario() );
            
        }
        
        /**
         * verifica se trabalhando,
         * se qtde de tarefas maior que 0 mensagem = Trabalhando,
         * se não mensagem = nenhuma tarefa iniciada
         * @version 1.0
         * @access public
         */
        public function verificar_trabalhando()
        {
            $this->load->model( array('tarefas_interacoes_model','tarefas_atividades_model','tarefas_tempo_model') );
            
            $trabalhando = $this->tarefas_tempo_model->get_itens('tarefas_tempo.id_usuario = '.$this->get_id_usuario().' AND tarefas_tempo.data_fim IS NULL');
            if ( $trabalhando['qtde'] > 0 )
            {
                $trabalho_atual = $trabalhando['itens'][0];
                $retorno['atividade'] = $this->tarefas_atividades_model->get_item($trabalho_atual->id_tarefas_atividades);
                $retorno['item'] = $this->tarefas_tempo_model->get_item($trabalho_atual->id);
                $retorno['erro']['status'] = TRUE;
                $retorno['erro']['mensagem'] = 'trabalhando';
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Nenhuma tarefa Iniciada';
            }
            echo json_encode($retorno);
        }
        
        /**
         * Carrega interações, atividades e tempo da tarefa,
         * mostra que tinham tarefas em aberto que foram fechadas com o horario atual,
         * inicia o trabalho em uma tarefa e manda uma mensagem,
         * trata eventuais erros que podem ocorer ao começar a trabalhar em uma tarefa
         * @version 1.0
         * @access public
         */
        public function trabalhar()
        {
            $this->load->model( array('tarefas_interacoes_model','tarefas_atividades_model','tarefas_tempo_model') );
            $dados = $this->_post(FALSE);
            $retorno['erro']['status'] = FALSE;
            $retorno['erro']['mensagem'] = '';
            if ( isset($dados) && $dados )
            {
                $data_inicio = date('Y-m-d H:i');
                $trabalhando = $this->tarefas_tempo_model->get_itens('tarefas_tempo.id_usuario = '.$this->get_id_usuario().' AND tarefas_tempo.data_fim IS NULL');
                if ( $trabalhando['qtde'] > 0 )
                {
                    $retorno['erro']['mensagem'] .= 'Você tinha tarefas em aberto, foram fechadas com o horario atual. ';
                    
                    foreach( $trabalhando['itens'] as $tempo )
                    {
                        $data_tempo = array('data_fim' => $data_inicio );
                        $filtro_tempo = array('id' => $tempo->id );
                        $this->tarefas_tempo_model->editar($data_tempo,$filtro_tempo);
                    }
                }
                $dados['data_inicio'] = $data_inicio;
                $id_tempo = $this->tarefas_tempo_model->adicionar($dados);
                if ( isset($id_tempo) && $id_tempo )
                {
                    $dados_interacao = $dados;
                    unset($dados_interacao['data_inicio']);
                    $dados_interacao['data'] = $data_inicio;
                    $dados_interacao['descricao'] = 'Começou a trabalhar na tarefa.';
                    $id_interacao = $this->tarefas_interacoes_model->adicionar($dados_interacao);
                    $retorno['atividade'] = $this->tarefas_atividades_model->get_item($dados['id_tarefas_atividades']);
                    $retorno['interacao'] = $this->tarefas_interacoes_model->get_item($id_interacao);
                    $retorno['item'] = $this->tarefas_tempo_model->get_item($id_tempo);
                    $retorno['erro']['status'] = TRUE;
                    $retorno['erro']['mensagem'] .= ' - trabalho iniciado';
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] .= 'Problemas ao iniciar trabalhos.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] .= 'Não foi possivel iniciar, tente de novo.';
            }
            echo json_encode($retorno);
        }
        
        /**
         * Carrega intereções, ativiades e tempo da tarefa,
         * pausa a tarefa que esta em execução e manda uma mensagem,
         * trata eventuais erros que podem ocorrer ao pausar a tarefa
         * @version 1.0
         * @access public
         */
        public function pausar()
        {
            $this->load->model( array('tarefas_interacoes_model','tarefas_atividades_model','tarefas_tempo_model') );
            $dados = $this->_post(FALSE);
            $retorno['erro']['status'] = FALSE;
            $retorno['erro']['mensagem'] = '';
            if ( isset($dados) && $dados )
            {
                $data_fim = date('Y-m-d H:i');
                $data_tempo = array('data_fim' => $data_fim );
                $filtro_tempo = array('id' => $dados['id_tempo'] );
                $afetados = $this->tarefas_tempo_model->editar($data_tempo,$filtro_tempo);
                
                if ( isset($afetados) && $afetados )
                {
                    $id_tempo = $dados['id_tempo'];
                    unset($dados['id_tempo']);
                    $dados_interacao = $dados;
                    $dados_interacao['data'] = $data_fim;
                    $dados_interacao['descricao'] = 'Parou de trabalhar na tarefa.';
                    $id_interacao = $this->tarefas_interacoes_model->adicionar($dados_interacao);
                    $retorno['atividade'] = $this->tarefas_atividades_model->get_item($dados['id_tarefas_atividades']);
                    $retorno['interacao'] = $this->tarefas_interacoes_model->get_item($id_interacao);
                    $retorno['item'] = $this->tarefas_tempo_model->get_item($id_tempo);
                    $retorno['erro']['status'] = TRUE;
                    $retorno['erro']['mensagem'] = 'Projeto pausada com sucesso';
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] .= 'Problemas ao pausar trabalhos.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] .= 'Não foi possivel Pausar, tente de novo.';
            }
            echo json_encode($retorno);
        }
        
        /**
         * Adicionar intereções a uma tividade,
         * se interação adicionada verifica se iniciado e manda uma mensagem de sucesso,
         * se não manda uma mensagem de falha
         * @param string $resposta 
         * @version 1.0
         * @access public
         */
        public function adicionar_interacoes( $resposta = 'json' )       
        {            
            $this->load->model(array ('tarefas_interacoes_model','tarefas_atividades_model','usuarios_model'));
            $dados = $this->_post(FALSE);
            $dados['data'] = date('Y-m-d H:i');
            $id_interacoes = $this->tarefas_interacoes_model->adicionar($dados);                            
            $nome_usuarios_interacoes = array();
            $email_usuarios_interacoes = array();
            if ( isset($id_interacoes) && $id_interacoes )
            {
                $retorno['item'] = $this->tarefas_interacoes_model->get_item( $id_interacoes );
                $retorno['erro']['id_tarefas_atividades'] = $dados['id_tarefas_atividades'];
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'interação inserida com sucesso.';
                $retorno['erro']['class'] = ' alert-success ';
                $filtro = 'id_tarefas_atividades = '.$dados['id_tarefas_atividades'] ;
                $usuarios = $this->tarefas_atividades_model->get_select_has ($filtro) ;
                   
               foreach ($usuarios as $usuarios_envolvidos_interacoes)
               {
                   $nome_usuarios_interacoes[]  = $usuarios_envolvidos_interacoes->descricao;
                   $email_usuarios_interacoes[] = $usuarios_envolvidos_interacoes->email;
               } 
            $usuario_principal_interacoes = $this->usuarios_model->get_item ($dados['id_usuario']);                 
            $tarefa = $this->tarefas_model->get_item($dados['id_tarefas']);                 
            $to = implode(' ,', $email_usuarios_interacoes);
            $from = $usuario_principal_interacoes->email;
            $assunto = 'Voce esta envolvido em uma nova interacao';   
            $mensagem = '<br>voce esta emvolvido em uma nova interacao: '.$id_interacoes
                       . '<br> <a href="'.base_url().'tarefas/editar/'.$tarefa['item']->id_tarefas_projeto.'/'.$tarefa['item']->id.'/#'.$dados['id_tarefas_atividades'].'">Atividade</a>' 
                       . '<br>Descricao: '.$dados['descricao'].''
                       . '<br>Usuario que iniciou a interacao: '.$usuario_principal_interacoes->nome.' ';   
            $dados_envio_interacoes = array(
                                        
                                        'to'=> $to,
                                        'from'=> $from,
                                        'assunto'=> $assunto,
                                        'iagente'=> TRUE,
                                        'mensagem'=> $mensagem,
                                        );
                     $this->envio($dados_envio_interacoes);     
            }         
            else
            {
                $retorno['erro']['id_tarefas_atividades'] = $dados['id_tarefas_atividades'];
                $retorno['erro']['status'] = TRUE;
                $retorno['erro']['mensagem'] = 'Problemas para inserir interação, Tente novamente.';
                $retorno['erro']['class'] = ' alert-danger ';
               
            }   
            echo json_encode($retorno);
        }
        
        /**
         * adicionar uma nova atividade,
         * se usuario selecionado maior que 0 então atividade pode ser adicionada,
         * se tarefa adicionada alert-success,
         * se não alert-danger
         * @param string $resposta
         * @version 1.1
         * @access public
         * 2015-09-08 disparo de email de aviso, testado....
         */
        public function adicionar_atividade( $resposta = 'json' )
        {   
            $this->load->model('usuarios_model');
            $this->load->model( array('tarefas_atividades_model', 'tarefas_interacoes_model') );
            $dados = $this->_post(FALSE);
            if ( isset($dados['usuarios']) )
            {
                $usuarios = $dados['usuarios'];
                unset($dados['usuarios']);
            }
            else
            {
                $usuarios = array();
            } 
            $nome_usuarios_atividades = array();
            $email_usuarios_atividades = array(); 
            $id_atividade = $this->tarefas_atividades_model->adicionar($dados);                 
            if ( isset($id_atividade) && $id_atividade )
            {
               if ( isset($usuarios) && count($usuarios) > 0 )
                {
                    foreach ( $usuarios as $usuario )
                    {
                        $data_add = array('id_tarefas_atividades' => $id_atividade, 'id_usuarios'=> $usuario );
                        $this->tarefas_atividades_model->adicionar_has($data_add);
                        $usuarios_info_atividades = $this->usuarios_model->get_item($usuario);                                              
                        $nome_usuarios_atividades[]= $usuarios_info_atividades->nome;
                        $email_usuarios_atividades[]= $usuarios_info_atividades->email;                                                                      
                        unset($data_add);                                                  
                    }
                }         
                /**
                 * Melhoria para disparo de email
                 * @since 1.1
                 * @author Everton programacao01@pow.com.br
                 */
                $usuario_principal_atividade = $this->usuarios_model->get_item($usuario);
                $tarefa = $this->tarefas_model->get_item($dados['id_tarefas']);
                $to = implode(", ",$email_usuarios_atividades );
                $from = $usuario_principal_atividade->email;
                $assunto ='Voce esta envolvido em uma nova atividade'.$id_atividade ;
                
                $mensagem_atividade = 'Voce esta envolvido em uma nova atividade: '.$id_atividade.''
                        . '<br><a href="'.base_url().'tarefas/editar/'.$tarefa['item']->id_tarefas_projeto.'/'.$tarefa['item']->id.'/#'.$id_atividade.'">Tarefa</a>'
                        . '<br> Previsao em horas: '.$dados['previsao_tempo'].' hs'
                        . '<br> Data Fim da Atividade: '.$dados['data_fim'].''
                        . '<br> Descricao da Atividade: '.$dados['descricao'].''
                        . '<br> Usuario que inicializou a Atividade: '.$usuario_principal_atividade->nome ;            
                $dados_envio_atividades = array(
                                                'to' => $to,
                                                'from'=> $from,
                                                'mensagem'=> $mensagem_atividade,
                                                'assunto'=>$assunto,
                                                );              
                                               $this->envio($dados_envio_atividades);                                                                              
                $data_interacao = array( 'id_tarefas' => $dados['id_tarefas'] , 'id_tarefas_atividades' => $id_atividade, 'descricao' => 'Abertura de atividade', 'data' => date('Y-m-d H:i'), 'id_usuario' => $this->get_id_usuario() );
                $interacao = $this->tarefas_interacoes_model->adicionar($data_interacao);
                $retorno['item'] = $this->tarefas_atividades_model->get_item( $id_atividade );
               
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Atividade inserida com sucesso.';
                $retorno['erro']['class'] = ' alert-success ';
            }
            else
            {
                $retorno['erro']['status'] = TRUE;
                $retorno['erro']['mensagem'] = 'Problemas para inserir atividade, Tente novamente.';
                $retorno['erro']['class'] = ' alert-danger ';
                
            }
            echo json_encode($retorno);
         
        }
        
        /**
         * @param string $id id da atividade
         * @version 1.0
         * @access public
         */
        public function add_usuario()
        {
            $this->load->model(array('tarefas_model'));
            $post = $this->input->post(NULL,TRUE);
            if ( isset($post['id_usuarios']) && isset($post['id_tarefas']) )
            {
                $filtro = array('id_tarefas' => $post['id_tarefas'], 'id_usuarios' => $post['id_usuarios']);
                $afetado = $this->tarefas_model->adicionar_has_usuario($filtro);
                    $retorno['erro']['status'] = TRUE;
                    $retorno['erro']['mensagem'] = 'Item adicionado com sucesso.';
                    $retorno['erro']['id'] = $post['id_usuarios'];
                    $retorno['erro']['nome'] = $post['nome'];

            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel adicionar, item inválido, tente novamente.';
            }
            
            echo json_encode($retorno);
            
        }
        
        
        /**
         * @param string $id id da atividade
         * @version 1.0
         * @access public
         */
        public function deleta_usuario( $id = NULL )
        {
            $this->load->model(array('tarefas_model'));
            $post = $this->input->post(NULL,TRUE);
            if ( isset($post['id_usuario']) && isset($post['id_tarefa']) )
            {
                $filtro = array('id_tarefas' => $post['id_tarefa'], 'id_usuarios' => $post['id_usuario']);
                $afetado = $this->tarefas_model->excluir_has_usuario($filtro);
                if ( $afetado )
                {
                    $retorno['erro']['status'] = TRUE;
                    $retorno['erro']['mensagem'] = 'Item removido com sucesso.';
                    $retorno['erro']['id'] = $post['id_usuario'];

                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Problemas ao remover, tente novamente.';

                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel deletar, item inválido, tente novamente.';
            }
            
            echo json_encode($retorno);
            
        }
        
        
        /**
         * Verifica se o item é valido, verifica se o item ja não esta fechado, se não estiver
         * faz as verificações com o banco e se tudo certo atividade e excluida se não
         * mensgem de erro ao excluir
         * @param string $id id da atividade
         * @version 1.0
         * @access public
         */
        public function deleta_atividade( $id = NULL )
        {
            $this->load->model(array('tarefas_atividades_model', 'tarefas_interacoes_model'));
            $item = $this->tarefas_atividades_model->get_item( $id );
            if ( isset($item) && $item )
            {
                if ( $item->id_tarefa_status == 1 )
                {
                    $afetado = $this->tarefas_atividades_model->excluir('tarefas_atividades.id = '.$id);
                    $afetado_usuarios = $this->tarefas_atividades_model->excluir_has('tarefas_atividades_has_usuarios.id_tarefas_atividades = '.$id);
                    $afetados_interacoes = $this->tarefas_interacoes_model->excluir('tarefas_interacoes.id_tarefas_atividades = '.$id);
                    if ( $afetado )
                    {
                        $retorno['erro']['status'] = TRUE;
                        $retorno['erro']['mensagem'] = 'Item removido com sucesso.';
                        $retorno['erro']['id'] = $id;
                        
                    }
                    else
                    {
                        $retorno['erro']['status'] = FALSE;
                        $retorno['erro']['mensagem'] = 'Problemas ao remover, tente novamente.';
                        
                    }
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Este item já esta fechado e não pode ser removido.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel deletar, item inválido, tente novamente.';
            }
            
            echo json_encode($retorno);
            
        }
        
        /**
         * Verifica se o item é valido, verifica se item ainda não fechado, 
         * faz as verificações com o banco, se estiver certo a atividade é fechada,
         * se não retorna mensagem de erro
         * @param string $id id da atividade
         * @param bool $geral descrição
         * @version 1.0
         * @access public
         */
        public function fechar_atividade( $id = NULL, $geral = FALSE )
        {
            $this->load->model(array('tarefas_atividades_model', 'tarefas_interacoes_model') );
            $item = $this->tarefas_atividades_model->get_item( $id );
            if ( isset($item) && $item )
            {
                if ( $item->id_tarefa_status == 1 )
                {
                    $data = array('tarefas_atividades.id_tarefas_status' => 2);
                    $filtro = array('tarefas_atividades.id' => $id);
                    $afetado = $this->tarefas_atividades_model->editar($data,$filtro);
                    if ( $afetado )
                    {
                        $data_interacoes = array( 'id_tarefas' => $item->id_tarefas, 'id_tarefas_atividades' => $item->id, 'id_usuario' => $this->get_id_usuario(), 'descricao' => $geral ? 'Fechamento de tarefa geral' : 'fechamento de atividade', 'data' => date('Y-m-d H:i') );
                        $this->tarefas_interacoes_model->adicionar($data_interacoes);
                        $retorno['erro']['status'] = TRUE;
                        $retorno['erro']['mensagem'] = 'Item fechado com sucesso.';
                        $retorno['erro']['id'] = $id;
                        
                    }
                    else
                    {
                        $retorno['erro']['status'] = FALSE;
                        $retorno['erro']['mensagem'] = 'Problemas ao fechar, tente novamente.';
                        
                    }
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Este item já esta fechado e não pode ser fechado novamente.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel Fechar, item inválido, tente novamente.';
            }
            
            echo json_encode($retorno);
            
        }
        
        /**
         * Verifica se item valido, se for verifica se item ainda não fechado, se não estiver 
         * verifica o banco de dados se tudo certo a terefa é fechada se não retona uma mensagem de erro
         * @param string $id id da tarefa
         * @version 1.0
         * @access public
         */
        public function fechar_tarefa( $id = NULL )
        {
            $this->load->model(array('tarefas_atividades_model', 'tarefas_interacoes_model') );
            $item = $this->tarefas_model->get_item( $id );
            if ( isset($item['item']) && $item['item'] )
            {
                if ( $item['item']->id_tarefa_status == 1 )
                {
                    
                    $data = array('tarefas.id_tarefas_status' => 2);
                    $filtro = array('tarefas.id' => $id);
                    $afetado_tarefa = $this->tarefas_model->editar($data,$filtro);
                    if ( isset($item['atividades']['itens']) && count($item['atividades']['itens']) )
                    {
                        foreach ($item['atividades']['itens'] as $atividade )
                        {
                            $this->fechar_atividade($atividade->id,TRUE);
                        }
                    }
                    if ( $afetado_tarefa )
                    {
                        $data_interacoes = array( 'id_tarefas' => $item['item']->id, 'id_tarefas_atividades' => 0, 'id_usuario' => $this->get_id_usuario(), 'descricao' => 'fechamento de tarefa', 'data' => date('Y-m-d H:i') );
                        $this->tarefas_interacoes_model->adicionar($data_interacoes);
                        $retorno['erro']['status'] = TRUE;
                        $retorno['erro']['mensagem'] = 'Item fechado com sucesso.';
                        $retorno['erro']['id'] = $id;
                        
                    }
                    else
                    {
                        $retorno['erro']['status'] = FALSE;
                        $retorno['erro']['mensagem'] = 'Problemas ao fechar, tente novamente.';
                        
                    }
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Este item já esta fechado e não pode ser fechado novamente.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel Fechar, item inválido, tente novamente.';
            }
            
            echo json_encode($retorno);
            
        }
        
        /**
         * Verifica se item é valido, se for, verifica se item ja aberto, se não aberto, 
         * verifica o banco de dados e se tudo certo a tarefa é reaberta, se não retorna uma mensagem de erro
         * @param string $id id da tarefa
         * @version 1.0
         * @access public
         */
        public function reabrir_tarefa( $id = NULL )
        {
            $this->load->model(array('tarefas_atividades_model', 'tarefas_interacoes_model') );
            $item = $this->tarefas_model->get_item( $id );
            if ( isset($item['item']) && $item['item'] )
            {
                if ( $item['item']->id_tarefa_status == 2 )
                {
                    
                    $data = array('tarefas.id_tarefas_status' => 1);
                    $filtro = array('tarefas.id' => $id);
                    $afetado_tarefa = $this->tarefas_model->editar($data,$filtro);
                    if ( $afetado_tarefa )
                    {
                        $data_interacoes = array( 'id_tarefas' => $item['item']->id, 'id_tarefas_atividades' => 0, 'id_usuario' => $this->get_id_usuario(), 'descricao' => 'fechamento de tarefa', 'data' => date('Y-m-d H:i') );
                        $this->tarefas_interacoes_model->adicionar($data_interacoes);
                        $retorno['erro']['status'] = TRUE;
                        $retorno['erro']['mensagem'] = 'Item Reaberto com sucesso.';
                        $retorno['erro']['id'] = $id;
                        
                    }
                    else
                    {
                        $retorno['erro']['status'] = FALSE;
                        $retorno['erro']['mensagem'] = 'Problemas ao Reabrir, tente novamente.';
                        
                    }
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Este item já esta Aberto e não pode ser Aberto novamente.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Impossivel Reabrir, item inválido, Contate o administrador com o código 171.';
            }
            
            echo json_encode($retorno);
            
        }
        
        


        /**
         * 
         * @param null $id
         * @param null $ok
         * @version 1.0
         * @access public
         */
        public function editar ( $id_tarefas_projeto, $id = NULL, $ok = NULL, $elemento = FALSE )
        {
                              
            $data = $this->_inicia_select();
            $classe = __CLASS__;
            $function = __FUNCTION__;
            $dados = $this->_post(TRUE);
            $layout = $this->layout;
            $layout->set_breadscrumbs('Painel', 'painel',0)->set_breadscrumbs('Portfolios', 'tarefas_portfolio/listar', 0);
      
            if ( isset($dados) && $dados )
            {            
                $id = $this->tarefas_model->adicionar($dados['item']);
                if ( $id )
                {
                    if ( isset($dados['upload']) )
                    {
                          
                        if ($dirh) {
                            while (($dirElement = readdir($dirh)) !== false) {
                                
                            }
                            closedir($dirh);
                        }
                        $dados['upload']['id_pai'] = $id;
                        
                        $this->anexo_lib->atualizar_uploads($dados['upload']);
                    }
                    
                    $this->load->model('usuarios_model');
                    $this->load->model('empresas_model');
                    
                    $nome_usuarios = array();
                    $email_usuarios = array();                      
                    if ( isset($dados['usuarios']) )
                    {
                        foreach( $dados['usuarios'] as $id_usuario )
                        {
                            $usuarios_has = array('id_tarefas' => $id, 'id_usuarios' => $id_usuario);
                            $this->tarefas_model->adicionar_has_usuario($usuarios_has);
                            $usuario_info = $this->usuarios_model->get_item ($id_usuario);
                            $nome_usuarios[]= $usuario_info->nome;
                            $email_usuarios[]=$usuario_info->email;
                                                   
                        }
                    }                                     
                    $usuario_principal = $this->usuarios_model->get_item ($dados['item']['id_usuario']);
                    $empresas_nome = array();
                    if ( isset($dados['empresas']) )
                    {
                        foreach( $dados['empresas'] as $id_empresa )
                        {
                            $empresas_has = array('id_tarefas' => $id, 'id_empresas' => $id_empresa);
                            $this->tarefas_model->adicionar_has_empresa($empresas_has);
                            
                            $empresa_info =  $this->empresas_model->get_item($id_empresa);
                          
                            $empresas_nome[]=$empresa_info->empresa_nome_fantasia;
                            
                        }
                    }
                    $to = implode(",", $email_usuarios);
                    $from = $usuario_principal->email;
                    $assunto  = 'Voce este envolvido em uma nova tarefa '.$dados['item']['titulo']; 
                    
                    $mensagem = 'No dia: '.$dados['item']['data_inicio'].PHP_EOL.'
                                 Foi aberta uma nova tarefa:'.$id.PHP_EOL.'<br>
                                 <a href="'.base_url().'tarefas/editar/'.$id_tarefas_projeto.'/'.$id.'">'.$dados['item']['titulo'].'</a>'.PHP_EOL.'<br>
                                 Usuarios Envolvidos: '.implode(", ", $nome_usuarios).PHP_EOL.'<br>
                                 Data de inicio: '.$dados['item']['data_inicio'].PHP_EOL.'<br>
                                 Data do fim: '.$dados['item']['data_fim'].PHP_EOL.'<br>
                                 Usuario que abriu a tarefa: '.$usuario_principal->nome.' E-mail:'.$usuario_principal->email.PHP_EOL ;
                
                    $dados_envio = array(
                                        'to' => $to,
                                        'email' => $from,
                                        'mensagem' => $mensagem,
                                        'assunto' => $assunto,
                                        );

                      $this->envio($dados_envio); 

                    redirect(strtolower(__CLASS__).'/editar/'.$id_tarefas_projeto.'/'.$id.'/1');
                    exit;
                }
                else
                {
                    $data['erro']['class'] = 'alert alert-danger';
                    $data['erro']['texto'] = 'Não foi possivel inserir seu projeto, por favor tente novamente.';
                }
            }
            if ( isset($id) && $id )
            {
                if ( isset($ok) && $ok == 1 )
                {
                    $data['erro']['class'] = 'alert alert-success';
                    $data['erro']['texto'] = 'Seu Projeto foi adicionada com sucesso.';
                }
                $visualizacao = array ( 'id_tarefas' => $id, 'id_usuario' => $this->get_id_usuario(), 'data_view' => date('Y-m-d H:i') );
                $data_visualizacao = $this->tarefas_model->adicionar_visualizacao($visualizacao);
                $data['item'] = $this->tarefas_model->get_item($id);
                $layout->set_breadscrumbs($data['item']['item']->tarefas_porfolio_titulo, 'tarefas_projetos/listar/'.$data['item']['item']->id_tarefas_porfolio,0);
                $layout->set_breadscrumbs($data['item']['item']->tarefas_projeto_titulo, 'tarefas_projetos/editar/'.$data['item']['item']->id_tarefas_porfolio.'/'.$data['item']['item']->id_tarefas_projeto,0);
                $layout->set_breadscrumbs('Editar', 'editar/'.$id,1);
                if ( ! isset($data['item']) )
                {
                    $data['erro']['class'] = 'alert alert-danger';
                    $data['erro']['texto'] = 'Nenhum item encontrado.';
                }
                $data['id_tarefas_projeto'] = $data['item']['item']->id_tarefas_projeto;

            }
            else
            {
                $data['id_tarefas_projeto'] = $id_tarefas_projeto;
                
            }
            if ( $elemento )
            {
                $this->load->model('tarefas_projetos_aceite_model');
                $aceite = $this->tarefas_projetos_aceite_model->get_item( $elemento );
                $data['inicia'] = array(
                                    'titulo' => $aceite->objetivo,
                                    'descricao' => $aceite->indicador.' - '.$aceite->meta,
                                    'data_fim' => reverte_data_mysql($aceite->data_medida),
                                    );
            }
            $data['action'] = base_url().strtolower($classe).'/'.strtolower($function).'/'.$data['id_tarefas_projeto'].'/'.( isset($id) ? $id : '' );
            $data['data_url'] = base_url().$classe.'/tratar_upload/fazer_upload';
            $data['familia'] = strtolower($classe);
            $data['tarefas_projeto'] = $this->tarefas_projetos_model->get_item($data['id_tarefas_projeto']);
            $layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/tarefas_lista.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('css/jquery.fileupload.css', TRUE)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('add_tarefas',$data);	
 
            } 
            
        /**
         * verifica se dados foi iniciado, se sim, verifica o banco de dados, 
         * se tudo certo campo é editado, se não retorna mensagem de erro 
         * @version 1.0
         * @access public
         */
        public function edita_campo( )
        {
            $dados = $this->input->post(NULL, TRUE);
            if ( isset($dados) && $dados ) 
            {
                $filtro = 'tarefas.id = '.$dados['id'];
                $data = array($dados['campo'] => $dados['valor']);
                $afetado = $this->tarefas_model->editar($data,$filtro);
                if ( $afetado && $afetado > 0 )
                {
                    $retorno['erro']['campo'] = $dados['campo'];
                    $retorno['erro']['valor'] = $dados['valor'];
                    $retorno['erro']['status'] = TRUE;
                    $retorno['erro']['mensagem'] = 'Alterado com sucesso.';
                }
                else
                {
                    $retorno['erro']['status'] = FALSE;
                    $retorno['erro']['mensagem'] = 'Erro ao alterar, tente novamente.';
                }
            }
            else
            {
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['mensagem'] = 'Erro ao alterar, tente novamente.';
            }
            
            echo json_encode($retorno);
            
            
        }
        
        /*
        public function tratar_upload($tipo = NULL)
        {
            if(isset($tipo) && !empty($tipo))
            {
                $data = $this->_post();
                switch($tipo)
                {
                    case 'fazer_upload':
                        $retorno = $this->anexo_lib->do_upload($data['upload']);
                        break;
                    case 'deletar_upload':
                        $retorno = $this->anexo_lib->deletar_arquivo($data['upload']);
                        break;
                    case 'remover_upload':
                        $retorno = $this->anexo_lib->remover_arquivo($data['upload']);
                        break;
                }
                print_r($retorno);
            }
        }
        
        public function get_tipo_images()
        {
            $data = $this->input->post(NULL, TRUE);
            $images = $this->images_model->get_arquivo_por_tipo($data['id_image_tipo'], $data['id_pai'] );
            $itens = $images['itens'];
            echo json_encode($itens);
        }*/
        
        /**
         * Inicia todos os selecionaveis do view,
         * sendo eles: usuarios_selecionados, empresas_selecionados, select_tipo_image,
         * select do usuarios_model, select do tarefas_status_model, id_usuario
         * @param string $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = NULL )
        {
            $this->load->model(array('tarefas_status_model'));
            if ( isset($id) )
            {
                $retorno['usuarios_selecionados'] = $this->tarefas_model->get_usuarios_selecionados($id);
                $retorno['empresas_selecionados'] = $this->tarefas_model->get_empresas_selecionados($id);
            }
            $retorno['imagens'] = $this->images_model->get_select_tipo_image(strtolower(__CLASS__));
            $retorno['usuarios'] = $this->usuario_model->get_select('usuarios.ativo = 1 AND usuarios.id_empresa = 6288');
            $retorno['status'] = $this->tarefas_status_model->get_select();
            $retorno['id_usuario'] = $this->get_id_usuario();
            
            //$retorno['ckeditor_texto'] = $this->inicia_ckeditor('descricao');
            return $retorno;
            
        }
        
        /**
         * 
         * @param string $id_tarefas
         * @version 1.0
         * @access public
         */
        public function carrega_campos_atividade( $id_tarefas = NULL )
        {
            $retorno = '';
            if ( isset($id_tarefas) && $id_tarefas )
            {
                $data = array( 'id' => $id_tarefas );
                $data['usuarios'] = $this->tarefas_model->get_select_usuarios_selecionados($id_tarefas);
                $retorno = $this->layout 
                            ->view('add_tarefas_atividades', $data, 'layout/sem_head', TRUE); 
            }
            echo $retorno;
        }
        
        /**
         * 
         * @param string $data
         * @param bool $return
         * @return void
         * @version 1.0
         * @access public
         */
        public function set_campo_salvo( $data = NULL, $return = FALSE )
        {
            //var_dump($data);
            if ( ! isset($data) )
            {
                $data = $this->input->post(NULL,TRUE);
            }
            $retorno = $this->layout
                        ->view('add_tarefas_campo_salvo', $data, 'layout/sem_head', TRUE); 
            if ( $return )
            {
                return $retorno;
            }
            else
            {
                echo $retorno;
            }
            
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
	private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE, $ordenacao = TRUE, $id_tarefas_projeto = 0 )
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
                                                    (object)array( 'chave' => 'id',             'titulo' => 'ID',               'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-9',              'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'tarefa_status',  'titulo' => 'Status',           'classe' => 'pull-right col-lg-3 col-sm-3 col-md-3 col-xs-3',   'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? ''.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'titulo',         'titulo' => 'Titulo',           'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? ''.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    //(object)array( 'chave' => 'data_inicio',    'titulo' => 'Data de inicio', 	'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3',              'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? ''.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    //(object)array( 'chave' => 'data_fim',       'titulo' => 'Data de Fim', 	'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3',              'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? ''.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    //(object)array( 'chave' => 'usuario',        'titulo' => 'Quem Abriu', 	'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3',              'link' => str_replace(array('[col]','[ordem]'), array('usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuario' ) ? ''.( ($extras['col'] == 'usuario' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    //(object)array( 'chave' => 'usuarios',       'titulo' => 'Envolvidos', 	'classe' => 'col-lg-9 col-sm-9 col-md-9 col-xs-9',              'link' => str_replace(array('[col]','[ordem]'), array('usuarios',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuarios') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuarios' ) ? ''.( ($extras['col'] == 'usuarios' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'qtde_atividades','titulo' => 'Qtde Atividades', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('qtde_atividades',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_atividades') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_atividades' ) ? ''.( ($extras['col'] == 'qtde_atividades' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    //(object)array( 'chave' => 'empresas',       'titulo' => 'Empresas', 	'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6',              'link' => str_replace(array('[col]','[ordem]'), array('empresas',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresas') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresas' ) ? ''.( ($extras['col'] == 'empresas' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    );
			
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Acessar', 'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-3 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => 'tarefas/editar/'.$id_tarefas_projeto.'/[id]', 'extra' => 'target="_blank"'),
                                                    );
                        /**
                         * qtde_por_linha = caso exista vai definir quantos elemento mostrar por linha na listagem, para objetos muito extensos, usar 1
                         * titulo = isset-> vai inserir o titulo do elemento.
                         * chave = utilizado para definir data-item da linha
                         * * load em listagem_etiqueta
                         */
			$data['qtde_por_linha'] = 2;
			$data['chave'] = 'id';
                        $data['ordenacao'] = $ordenacao;
                        
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$data['titulo'] = 'Tarefas';
                        $this->load->library('listagem_etiqueta');
			$this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		}
		return $retorno;
	}
        
        /**
         * cria a lista de tarefas em execução no estilo normal de listagem,
         * chama os campos necessarios para criar o cabeçalho e define id como chave
         * @param array $itens
         * @param string $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
        private function _inicia_listagem_relatorios( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',                  'titulo' => 'ID Tarefa',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_atividade',        'titulo' => 'ID Atividade', 	'link' => str_replace(array('[col]','[ordem]'), array('id_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_usuario',          'titulo' => 'ID Usuario', 	'link' => str_replace(array('[col]','[ordem]'), array('id_usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_usuario' ) ? 'ui-state-highlight'.( ($extras['col'] == 'd_usuario' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_usuario',        'titulo' => 'Nome Usuario',    'link' => str_replace(array('[col]','[ordem]'), array('nome_usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_usuario' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_usuario' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo_tarefa',       'titulo' => 'Titulo Tarefa',    'link' => str_replace(array('[col]','[ordem]'), array('titulo_tarefa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo_tarefa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo_tarefa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo_tarefa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo_atividade',    'titulo' => 'Titulo Atividade',       'link' => str_replace(array('[col]','[ordem]'), array('titulo_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_inicio',         'titulo' => 'Data Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'tarefa_atividade',    'titulo' => 'Tarefa/Atividade',    'link' => str_replace(array('[col]','[ordem]'), array('tarefa_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'tarefa_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'tarefa_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'tarefa_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'pai',  'titulo' => 'Setor Pai',    'link' => str_replace(array('[col]','[ordem]'), array('pai',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'pai') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'pai' ) ? 'ui-state-highlight'.( ($extras['col'] == 'pai' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Ver', 'class' => 'btn btn-info ', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
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
         * cria a lista de todas as tarefas no estilo normal de listagem
         * chama os campos necessarios para criar o cabeçalho e define id como chave
         * @param array $itens
         * @param string $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
	private function _inicia_listagem_relatorios_tarefas( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID Tarefa',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_atividade', 'titulo' => 'ID Atividade', 	'link' => str_replace(array('[col]','[ordem]'), array('id_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_usuario',         'titulo' => 'ID Usuario', 	'link' => str_replace(array('[col]','[ordem]'), array('id_usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_usuario' ) ? 'ui-state-highlight'.( ($extras['col'] == 'd_usuario' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_usuario',  'titulo' => 'Nome Usuario',    'link' => str_replace(array('[col]','[ordem]'), array('nome_usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_usuario' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_usuario' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo_tarefa',  'titulo' => 'Titulo Tarefa',    'link' => str_replace(array('[col]','[ordem]'), array('titulo_tarefa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo_tarefa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo_tarefa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo_tarefa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo_atividade',     'titulo' => 'Titulo Atividade',       'link' => str_replace(array('[col]','[ordem]'), array('titulo_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_inicio','titulo' => 'Data Inicio', 	'link' => str_replace(array('[col]','[ordem]'), array('data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_fim','titulo' => 'Data Fim', 	'link' => str_replace(array('[col]','[ordem]'), array('data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_fim' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_fim' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'tarefa_atividade',  'titulo' => 'Tarefa/Atividade',    'link' => str_replace(array('[col]','[ordem]'), array('tarefa_atividade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'tarefa_atividade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'tarefa_atividade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'tarefa_atividade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'pai',  'titulo' => 'Setor Pai',    'link' => str_replace(array('[col]','[ordem]'), array('pai',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'pai') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'pai' ) ? 'ui-state-highlight'.( ($extras['col'] == 'pai' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Ver', 'class' => 'btn btn-info ', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
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
                                        array( 'name' => 'id_tarefas_projeto', 'titulo' => 'Projeto: ',             'tipo' => 'select', 'valor' => $this->tarefas_projetos_model->get_select(), 'classe' => 'form-control ', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas.id_tarefas_projeto', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',          'titulo' => 'Titulo: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'descricao',       'titulo' => 'Descricao: ',       'tipo' => 'text', 'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas.descricao', 	'valor' => '' ) ),
                                        array( 'name' => 'status',          'titulo' => 'Status: ',             'tipo' => 'select', 'valor' => $this->tarefas_status_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas.id_tarefas_status', 	'valor' => '' ) ),
                                        );	
                
                $valores['id_user'] = 1;
                $config['itens'][] = array('name' => 'id_user', 'where' => '( tarefas.id_usuario = '.$this->get_id_usuario().' OR tarefas_has_usuarios.id_usuarios = '.$this->get_id_usuario().' )');
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        /**
         * cria um filtro por status para a listagem normal de usuarios trabalhando
         * @param string $url
         * @param array $valores
         * @return array $retorno - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
        private function _inicia_filtros_relatorios($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'status',          'titulo' => 'Status: ',             'tipo' => 'select', 'valor' => $this->usuario_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'usuarios.id', 	'valor' => '' ) ),
                                        );	
                
                //$valores['id_user'] = 1;
                //$config['itens'][] = array('name' => 'id_user', 'where' => '( tarefas.id_usuario = '.$this->get_id_usuario().' OR tarefas_has_usuarios.id_usuarios = '.$this->get_id_usuario().' )');
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		//$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        /**
         * cria um filtro para a listagem normal de usuarios trabalhando ou que ja trabalharam 
         * em uma determinada tarefa
         * @param type $url
         * @param type $valores
         * @return array $retorno - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros_relatorios_tarefas($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'usuarios',        'titulo' => 'Usuários: ',            'tipo' => 'select', 'valor' => $this->usuario_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'usuarios.id', 	         'valor' => '' ) ),
                                        array( 'name' => 'tarefas',         'titulo' => 'Tarefas: ',             'tipo' => 'select', 'valor' => $this->tarefas_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas.id', 	         'valor' => '' ) ),
                                        array( 'name' => 'data_inicio',     'titulo' => 'Data Início: ',         'tipo' => 'text', 'valor' => '',                                   'classe' => 'data_hora data-inicio form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas_tempo.data_inicio >', 	 'valor' => '' ) ),
                                        array( 'name' => 'data_fim',        'titulo' => 'Data Fim: ',            'tipo' => 'text', 'valor' => '',                                   'classe' => 'data_hora data-fim form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas_tempo.data_fim <', 	         'valor' => '' ) ),
                                        );	
                //$valores['id_user'] = 1;
                //$config['itens'][] = array('name' => 'id_user', 'where' => '( tarefas.id_usuario = '.$this->get_id_usuario().' OR tarefas_has_usuarios.id_usuarios = '.$this->get_id_usuario().' )');
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		//$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		
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
	private function _post( $tarefa = TRUE )
	{
         
		$data = $this->input->post(NULL, TRUE);
                if( $tarefa )
                {
                    if ( isset($data) && $data )
                    {
                        $empresas = NULL;
                        $usuarios = NULL;
                        if ( isset($data['empresas']) )
                        {
                            $empresas = $data['empresas'];
                            unset($data['empresas']);
                        }
                        if ( isset($data['usuarios']) )
                        {
                            $usuarios = $data['usuarios'];
                            unset($data['usuarios']);
                        }
                    
                        /*
                        if ( isset($data['upload']) )
                        {
                            $upload = $data['upload'];
                            unset($data['upload']);
                        }
                        if ( isset($_FILES) && !empty($_FILES) )
                        {
                            $upload['files'] = $_FILES['files'];
                            unset($_FILES['files']);
                        }*/
                        $data['data_inicio'] = converte_data_mysql($data['data_inicio']);
                        $data['data_fim'] = converte_data_mysql($data['data_fim']);
                        $itens = $data;
                        unset($data);
                        $data['item'] = $itens;
                        //unset($data['item']['files']);
                        $data['item']['id_usuario'] = $this->get_id_usuario();
                        $data['empresas'] = $empresas;
                        $data['usuarios'] = $usuarios;
                  
                
                        //$data['upload'] = $upload;
                        
                   
                    }
                }
                else
                {
                    $data['id_usuario'] = $this->get_id_usuario();
                }
                
		return $data;
	}
}


