<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de portfolios
 * @version 1.0
 * @access public
 * @package tarefas
 */
class Tarefas_projetos extends MY_Controller 
{
        /**
         * cria um array de 2 posições para validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'item[titulo]',               'label'   => 'Titulo',                          'rules'   => 'trim'),
                                array( 'field'   => 'item[descricao]',            'label'   => 'Descricao',                       'rules'   => 'trim'),                          
                                array( 'field'   => 'item[id_responsavel]',       'label'   => 'O Responsável ',                  'rules'   => 'trim'),
                                array( 'field'   => 'item[id_setor_responsavel]', 'label'   => 'O Setor Responsável ',            'rules'   => 'trim'),
                                );
        /**
         *variavel global que receberá todos os usuarios ativos.
         * @var array todos os usuarios.
         */
        private  $usuarios = array();
    /**
         * Controi a classe e carrega valores de extends
         * e carrega models e helpers padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('tarefas_projetos_model','tarefas_portfolio_model','usuarios_model', 'tarefas_projetos_status_model'));
            $this->usuarios = $this->usuarios_model->get_select('usuarios.ativo = 1 AND usuarios.id_empresa = 6288');
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
        public function listar( $id_portfolio = TRUE, $coluna = 'tarefas_projetos.titulo', $ordem = 'ASC', $off_set = 0)
	{
            if ( ! $id_portfolio )
            {
                redirect(base_url().'tarefas_portfolio','refresh');
                exit();
            }
            $item =  $this->tarefas_portfolio_model->get_item($id_portfolio);
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$id_portfolio.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $valores['id_tarefas_portfolio'] = $id_portfolio;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->tarefas_projetos_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->tarefas_projetos_model->get_total_itens( $filter );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : ($get_url) );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$off_set.'/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $extras['portfolio'] = $item;
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras, FALSE, $id_portfolio );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/tarefas_projetos.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Portfolio', 'tarefas_portfolio/listar', 0)
                        ->set_breadscrumbs('Projetos', 'tarefas_projetos', 1)
                        ->set_breadscrumbs($item->titulo, 'tarefas_portfolio', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post();
            if ( isset($dados['id_tarefas_portfolio']) )
            {
                if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
                {
                    $filtro = 'tarefas_projetos.id = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                    $afetados = $this->tarefas_projetos_model->editar($data, $filtro);
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
                    $data['id_tarefas_portfolio'] = $dados['id_tarefas_portfolio'];
                    $data['id_responsavel'] = 1;
                    $data['id_setor_responsavel'] = 5;
                    $retorno['id'] = $this->tarefas_projetos_model->adicionar($data);
                    if ( isset($retorno['id']) && $retorno['id'] )
                    {
                        $retorno['status'] = TRUE;
                        $retorno['muda_url'] = base_url().'tarefas_projetos/editar/'.$dados['id_tarefas_portfolio'].'/'.$retorno['id'].'/';
                    }
                    else
                    {
                        $retorno['status'] = FALSE;
                        $retorno['mensagem'] = 'Não foi possivel adicionar';
                        $retorno['muda_url'] = FALSE;
                    }
                }
            }
            else
            {
                $retorno['status'] = FALSE;
                $retorno['mensagem'] = 'Nenhum portfolio seleiconado, contato o administrador';
                $retorno['muda_url'] = FALSE;
            }
            echo json_encode($retorno);
        }
        
        
        /**
         * 
         * @param int $id
         * @param bollean $ok
         * @version 1.0
         * @access public
         */
        public function editar ( $id_tarefas_portfolio = FALSE, $id = NULL, $ok = NULL )
        {
            $i = 'tarefas_portfolio';
            $acesso = $this->set_setor_usuario($i);
            if ( $acesso['status'] )
            {
                $this->load->model(array('tarefas_projetos_model','tarefas_projetos_has_usuarios_model','tarefas_projetos_aceite_model','tarefas_projetos_marcos_model','tarefas_projetos_comunicacao_model','tarefas_projetos_qualidade_model','tarefas_projetos_riscos_model'));
                $this->form_validation->set_rules($this->valida);
                if ($this->form_validation->run())
                {
                    $dados = $this->_post();
                    if ( isset($id) )
                    {
                        $affect = $this->tarefas_projetos_model->editar($dados, 'tarefas_projetos.id = '.$id);
                        if ( $affect && $affect > 0 )
                        {
                            redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_tarefas_portfolio.'/'.$id.'/'.$ok );
                        }
                        else
                        {
                            redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_tarefas_portfolio.'/'.$id.'/'.$ok );
                        }
                    }
                    else
                    {
                        //Adicionar e carregar todos os Model de Tarefa projetos
                        //var_dump($dados);die();
                        $dados['item']['id_tarefas_portfolio'] = $dados['id_tarefas_portfolio'];
                        $id_projetos = $this->tarefas_projetos_model->adicionar($dados['item']);

                        if( isset($id_projetos) && $id_projetos )
                        {
                            $projetos['id_tarefas_projetos'] =  $id_projetos;
                            unset($dados['item']); 
                            foreach($dados as $chave => $item  )
                            {          
                                $model = 'tarefas_projetos_'.$chave.'_model'; 
                                foreach ( $item as $valor)
                                {
                                    $valor['id_tarefas_projetos'] =  $id_projetos;
                                    $this->$model->adicionar($valor);
                                }
                            }
                            redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_tarefas_portfolio.'/'.$id_projetos.'/'.$ok );
                        }
                        else
                        {
                            redirect(base_url().strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/'.$id_tarefas_portfolio.'/'.$id_projetos.'/'.$ok );
                        }
                    }
                }
                else
                {
                    $item = (isset($id)) ? $this->tarefas_projetos_model->get_item($id) : FALSE;
                    $data = $this->_inicia_select($item, TRUE);
                    $data['id_tarefas_portfolio'] = $id_tarefas_portfolio; 
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
                    $data['item'] = $item;
                    $data['item_tarefas_portfolio'] = $this->tarefas_portfolio_model->get_item($id_tarefas_portfolio);
                    if ( isset($id) )
                    {
                        $layout->set_breadscrumbs($item->tarefas_portfolio, 'tarefas_projetos/listar/'.$id_tarefas_portfolio,0);
                        $layout->set_breadscrumbs('Editar', '',1);
                        $data['id'] =  $id;
                    }
                    else
                    {
                        $layout->set_breadscrumbs($data['item_tarefas_portfolio']->titulo, 'tarefas_projetos/listar/'.$id_tarefas_portfolio,0);
                        $layout->set_breadscrumbs('Adicionar Projeto', 'editar/',1);
                    }
                    $data['action'] = base_url().strtolower($classe).'/'.strtolower($function).'/'.$id_tarefas_portfolio.'/'.( isset($id) ? $id : '' );
                    if ( isset($item) && $item )
                    {
                        if ( $item->id_responsavel == $this->get_id_usuario() )
                        {
                            $editavel = TRUE;
                        }
                        else
                        {
                            $editavel = FALSE;
                            if ( $item )
                            {
                                $filtro = 'id_tarefas_projetos ='.$item->id.' AND id_usuario = '.$this->get_id_usuario();
                                $usuario_has = $this->tarefas_projetos_has_usuarios_model->get_itens($filtro);
                                $editavel = $usuario_has['qtde'] > 0 ? TRUE : FALSE;
                            }
                        }
                        
                    }
                    else
                    {
                        $editavel = TRUE;
                    }
                    $data['id_usuario_sessao'] = $this->get_id_usuario();
                    $data['editavel'] = $editavel;
                    $layout
                                ->set_classe( $classe )
                                ->set_function( $function ) 
                                ->set_include('js/listar.js', TRUE)
                                ->set_include('js/tarefas_portfolio.js', TRUE)
                                ->set_include('js/tarefas_projetos.js', TRUE)
                                ->set_include('js/auto_save.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->set_usuario()
                                ->set_menu($this->get_menu($classe, $function))
                                ->view('add_tarefas_projetos',$data);	
                }
            }
            else
            {
                redirect('painel');
            }
        }
        
        public function monta_cronograma( $id_tarefas_projeto = FALSE )
        {
            $retorno['status'] = FALSE;
            if ( $id_tarefas_projeto )
            {
                $this->load->model( array('tarefas_model') );
                $data['datas'] = $this->tarefas_model->get_maior_menor_data($id_tarefas_projeto);
                //var_dump($data['datas']);
                $filtro = 'tarefas.id_tarefas_projeto = '.$id_tarefas_projeto;
                $tarefas = $this->tarefas_model->get_itens($filtro, 'tarefas.data_inicio', 'ASC');
                if ( isset($tarefas) && $tarefas['qtde'] > 0 )
                {
                    foreach( $tarefas['itens'] as $tarefa )
                    {
                        $data['tarefas'][$tarefa->id]['item'] = $tarefa;
                        $data['tarefas'][$tarefa->id]['horas_trabalhado'] = get_tempo($this->tarefas_model->get_tempo_trabalhado($tarefa->id));
                    }
                    $this->load->library('cronograma');
                    $retorno['cronograma'] = $this->cronograma->get_html($data);
                    $retorno['status'] = TRUE;
                }
                else
                {
                    $retorno['mensagem'] = 'Nenhum tarefa registrada.';
                }
            }
            echo json_encode($retorno);
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
        private function _inicia_select( $item = FALSE, $edita = FALSE )
        {
            $this->load->model(array('pow_cargos_model'));
            $this->load->model(array('tarefas_projetos_model','tarefas_projetos_has_usuarios_model','tarefas_projetos_aceite_model','tarefas_projetos_marcos_model','tarefas_projetos_comunicacao_model','tarefas_projetos_qualidade_model','tarefas_projetos_riscos_model','tarefas_projetos_iteracao_model'));
            $retorno['usuarios'] = $this->usuarios;
            $retorno['status'] = $this->tarefas_projetos_status_model->get_select();
            $retorno['cargos'] = $this->pow_cargos_model->get_select();
            $retorno['tarefas_portfolio'] = $this->tarefas_portfolio_model->get_select();
            $retorno['id'] = $this->tarefas_projetos_model->get_itens();
            $retorno['has_usuarios'] = $this->set_has('has_usuarios', 'return',0,$item, $edita);
            $retorno['aceite'] = $this->set_has('aceite','return',0,$item, $edita);
            $retorno['marcos'] = $this->set_has('marcos','return',0,$item, $edita);
            $retorno['comunicacao'] = $this->set_has('comunicacao','return',0,$item, $edita);
            $retorno['qualidade'] = $this->set_has('qualidade','return',0,$item, $edita);
            $retorno['riscos'] = $this->set_has('riscos','return',0, $item, $edita);
            $retorno['iteracoes'] = $this->set_iteracao('return', $item);
            return $retorno;
        }
        
        public function set_has( $tipo = 'has_usuarios', $tipo_retorno = 'return', $ordem = 0, $item = NULL, $edita = TRUE )
        {
            ob_clean();
            $data['edita'] = $edita;
            $model = 'tarefas_projetos_'.$tipo.'_model';
            $this->load->model($model);
            $this->load->library('layout');
            $has = '';
            if(isset($item) && $item)
            {
                $filtro = 'id_tarefas_projetos ='.$item->id;
                $itens = $this->$model->get_itens($filtro);
                if($itens['qtde'] > 0 )
                {
                     foreach ($itens['itens'] as $valor)
                     {
                         $data['item'] = $valor;
                         $data['ordem'] = $valor->id;
                         $data['usuarios'] = $this->usuarios;
                         $has .= $this->layout->view('add_tarefas_projetos_'.$tipo, $data, 'layout/sem_head' , TRUE);
                     }
                }
                else 
                {
                    if ( $edita )
                    {
                        $data['ordem'] = $ordem;
                        $data['usuarios'] = $this->usuarios;
                        $has .= $this->layout->view('add_tarefas_projetos_'.$tipo, $data, 'layout/sem_head' , TRUE);
                    }
                }
            }
            else 
            {
                $data['ordem'] = $ordem;
                $data['usuarios'] = $this->usuarios;
                $has = $this->layout->view('add_tarefas_projetos_'.$tipo, $data, 'layout/sem_head' , TRUE);
            }
            if($tipo_retorno !== 'return')
            {
                echo $has;
            }
            else
            {
                return $has;
            }
        }
        
        public function set_deleta( $tipo, $id = FALSE )
        {
            $retorno = array('status' => FALSE);
            if ( $id )
            {
                $model = 'tarefas_projetos_'.$tipo.'_model';
                $this->load->model($model);
                $filtro = 'id = '.$id;
                $retorno['status'] = $this->$model->excluir($filtro);
            }
            echo json_encode( $retorno );
        }
        
        public function set_salva( $tipo, $id = FALSE )
        {
            $retorno = array('status' => FALSE);
            $data = $this->input->post(NULL, TRUE);
            if ( isset($data) )
            {
                $model = 'tarefas_projetos_'.$tipo.'_model';
                $this->load->model($model);
                if ( $id )
                {
                    $filtro = 'id = '.$id;
                    $status = $this->$model->editar($data,$filtro);
                    $retorno['status'] = ( $status ) ? TRUE : FALSE;
                    $retorno['id'] = $id;
                }
                else
                {
                    $add = $this->$model->adicionar($data);
                    if ( $add )
                    {
                        $retorno['status'] = TRUE;
                        $retorno['id'] = $add;
                    }
                }
                
            }
            echo json_encode( $retorno );
        }
        
        public function set_salva_iteracao(  )
        {
            $retorno = array('status' => FALSE);
            $data = $this->input->post(NULL, TRUE);
            $avisados = $data['avisados'];
            unset($data['avisados']);
            $data['data'] = date('Y-m-d H:i');
            if ( isset($data) )
            {
                // adiciona a iteração
                $model = 'tarefas_projetos_iteracao_model';
                $this->load->model($model);
                $add = $this->$model->adicionar($data);

                // se a iteração foi adicionada, lista os usuarios avisados pra mandar email
                if ( $add )
                {
                    // le os usuarios e tarefas_projetos_iteracao_has_usuarios_model
                    $this->load->model(array('usuario_model', 'tarefas_projetos_iteracao_has_usuarios_model'));
                    
                    // filtra os usuarios pelo id
                    $filtro_usuarios = 'usuarios.id IN ('.  implode(',', $avisados).')';
                    $usuarios = $this->usuario_model->get_select($filtro_usuarios);
                    
                    // usuario que fez a iteração
                    $filtro_usuario = 'usuarios.id = ' . $data['id_usuario'];
                    $usuario = $this->usuario_model->get_select($filtro_usuario);
                    $data['usuario_nome'] = $usuario[0]->descricao;
                    foreach($usuarios as $usuario)
                    {
                        // adiciona o usuario a iteração
                        $this->tarefas_projetos_iteracao_has_usuarios_model->adicionar([
                            'id_tarefas_projetos_iteracao' => $add,
                            'id_usuario' => $usuario->id
                        ]);
                        
                        // captura o email do usuario
                        $email_usuarios[] = $usuario->email;
                    }

                    // monta o email
                    $to = implode(",", $email_usuarios);
                    $from = 'noreply@pow.com.br';
                    $item = $this->tarefas_projetos_model->get_item($data['id_tarefas_projetos']);
                    $assunto  = 'Projeto: '.$item->id.' - '.$item->titulo.' - Aviso de comunicação de projeto '; 
                    
                    $l = $assunto.'<br>';
                    $l .= $data['message'].'<br><br>';
                    $l .= '<a href="'.base_url().'tarefas_projetos/editar/'.$item->id_tarefas_portfolio.'/'.$item->id.'">clique aqui para ir ao projeto</a>';

                    // envia o email
                    $dados_envio = array(
                                        'to' => $to,
                                        'email' => $from,
                                        'mensagem' => $l,
                                        'assunto' => $assunto,
                                        );
                    $status =  $this->envio($dados_envio); 
                    $retorno['status'] = TRUE;
                    $retorno['id'] = $add;
                    $retorno['data'] = $data;
                }
                
            }
            echo json_encode( $retorno );
        }
    
        public function requisita_iteracao($id_projeto = FALSE, $id_pai = FALSE)
        {
            $this->load->model('tarefas_projetos_iteracao_model');
            if ( $id_projeto )
            {
                $item = (object)array();
                $item->id = $id_projeto;
            }
            else
            {
                $item = NULL;
            }
            $retorno = $this->set_iteracao('return', $item, $id_pai);
            
            echo $retorno;
        }
        
        public function set_iteracao($tipo_retorno = 'return',$item = NULL ,$pai = FALSE)
        {
            $iteracao = '';
            $data['usuarios'] = $this->usuarios;
            if( (isset($item) && $item) )
            {
                if ( $pai )
                {
                    $filtro = 'tarefas_projetos_iteracao.id_pai ='.$item->id;
                    $coluna = 'id';
                    $ordem = 'ASC';
                }
                else
                {
                    $filtro = 'tarefas_projetos_iteracao.id_tarefas_projetos ='.$item->id.' AND tarefas_projetos_iteracao.id_pai IS NULL';
                    $coluna = 'id';
                    $ordem = 'DESC';
                }

                $itens = $this->tarefas_projetos_iteracao_model->get_itens($filtro, $coluna, $ordem);
                if($itens['qtde']  > 0 )
                {
                    $iteracao .= '<ul class="list-group">';
                    $data['edita'] = FALSE;
                    foreach ($itens['itens'] as $valor )
                    {
                        $data['item_iteracao'] = $valor;
                        $data['ordem'] = $valor->id;
                        $iteracao .= $this->layout->view('add_tarefas_projetos_iteracao',$data, 'layout/sem_head' , TRUE);
                        if ( $valor->qtde_respostas > 0 )
                        {
                            $iteracao .= $this->set_iteracao('return',$valor, TRUE);
                            $iteracao .= '</li>';
                        }
                    }
                    $iteracao .= '</ul>';
                }
                
            }
            else
            {
                $data['ordem'] = 0;
                $data['id_pai'] = $pai;
                $iteracao .= $this->layout->view('add_tarefas_projetos_iteracao',$data, 'layout/sem_head' , TRUE);
            }
            if($tipo_retorno !== 'return')
            {
                echo $iteracao;
            }
            else
            {
                return $iteracao;
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
	private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE, $id_portfolio = FALSE )
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
                                                    (object)array( 'chave' => 'id',             'titulo' => 'ID',               'classe' => 'hide',    'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? ' '.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'titulo',         'titulo' => 'Titulo',           'classe' => 'col-lg-8 col-sm-8 col-md-8 col-xs-12',          'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? ''.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' ) ),
                                                    (object)array( 'chave' => 'responsavel',    'titulo' => 'Gerente Portfólio','classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-12 pull-left',    'link' => str_replace(array('[col]','[ordem]'), array('responsavel',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'responsavel') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'responsavel' ) ? ''.( ($extras['col'] == 'responsavel' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up') : ' glyphicon glyphicon-chevron-down' ) ),
                                                    );
			
                        $data['operacoes'] = array(
                                                    //(object) array('titulo' => 'Ver Cronograma',  'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-12 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-calendar"></span>', 'extra' => 'data-portfolio="'.$id_portfolio.'"'),
                                                    //(object) array('titulo' => 'Ver Tarefas',  'class' => 'col-lg-4 col-sm-3 col-md-4 col-xs-12 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-th-list"></span>'),
                                                    (object) array('titulo' => 'Editar',        'class' => 'col-lg-3 col-sm-3 col-md-3 col-xs-12 btn btn-info pull-right', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'extra' => 'data-portfolio="'.$id_portfolio.'"'),
                                                    );
                        /**
                         * qtde_por_linha = caso exista vai definir quantos elemento mostrar por linha na listagem, para objetos muito extensos, usar 1
                         * titulo = isset-> vai inserir o titulo do elemento.
                         * chave = utilizado para definir data-item da linha
                         * * load em listagem_etiqueta
                         */
			$data['qtde_por_linha'] = 2;
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$data['titulo'] = 'Projetos - '.$extras['portfolio']->titulo;
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
                                        array( 'name' => 'id_tarefas_portfolio',    'titulo' => 'Portfolio: ',      'tipo' => 'select', 'valor' => $this->tarefas_portfolio_model->get_select(), 'classe' => 'form-control ', 'extra' => 'disabled="disabled"',  'where' => array( 'tipo' => 'WHERE', 	'campo' => 'tarefas_projetos.id_tarefas_portfolio', 	'valor' => '' ) ),
                                        array( 'name' => 'titulo',                  'titulo' => 'Titulo: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas_projetos.titulo', 	'valor' => '' ) ),
                                        array( 'name' => 'descricao',               'titulo' => 'Descricao: ',      'tipo' => 'text', 'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'tarefas_projetos.descricao', 	'valor' => '' ) ),
                                        array( 'name' => 'status',                  'titulo' => 'Status: ',         'tipo' => 'select', 'valor' => $this->tarefas_projetos_status_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'tarefas_projetos.status_projeto', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/editar/'.$valores['id_tarefas_portfolio'].'/" class="btn btn-primary" target="_blank">Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
	}
        
        public function pdf( $id_projeto = FALSE )
        {
            if ( $id_projeto )
            {
                $this->load->helper('download');
                
                $item = $this->tarefas_projetos_model->get_item($id_projeto);
                $data = $this->_inicia_select($item, FALSE);
                $data['item'] = $item;
                $data['print'] = TRUE;
                $data['item_tarefas_portfolio'] = $this->tarefas_portfolio_model->get_item($item->id_tarefas_portfolio);
                $l = $this->layout;
                $l->view('pdf_projeto',$data, 'layout/sem_menu');
                //$this->load->library('html_pdf');
                //$pdf = $this->html_pdf->retorna_pdf($html);
                //force_download('a.pdf',$pdf);
                
            }
            else
            {
                die('Não autorizado');
            }
        }
        
        public function avisa_equipe( $id_projeto = FALSE )
        {
            ob_clean();
            if ( $id_projeto )
            {
                //$this->load->helper('download');
                $this->load->model(array('tarefas_projetos_model','tarefas_projetos_has_usuarios_model','tarefas_projetos_aceite_model','tarefas_projetos_marcos_model','tarefas_projetos_comunicacao_model','tarefas_projetos_qualidade_model','tarefas_projetos_riscos_model'));
                
                $item = $this->tarefas_projetos_model->get_item($id_projeto);
                $filtro = 'id_tarefas_projetos ='.$item->id;
                $itens = $this->tarefas_projetos_has_usuarios_model->get_itens($filtro);
                if ( isset($itens['itens']) && $itens['qtde'] > 0 )
                {
                    $data = $this->_inicia_select($item, FALSE);
                    $data['item'] = $item;
                    $data['item_tarefas_portfolio'] = $this->tarefas_portfolio_model->get_item($item->id_tarefas_portfolio);
                    
                    $l = $this->layout->view('pdf_projeto',$data, 'layout/sem_menu', TRUE);
                    if ( ! empty($l) )
                    {
                        foreach($itens['itens'] as $usuario)
                        {
                            $email_usuarios[] = $usuario->email;
                        }
                        $to = implode(",", $email_usuarios);
                        $from = 'noreply@pow.com.br';
                        $assunto  = $id_projeto.' - '.$data['item']->titulo . ' - Aviso de comunicação de projeto '; 

                        $dados_envio = array(
                                            'to' => $to,
                                            'email' => $from,
                                            'mensagem' => $l,
                                            'assunto' => $assunto,
                                            );
                        $status =  $this->envio($dados_envio); 
                        if ( $status )
                        {
                            $retorno['status']['erro'] = TRUE;
                            $retorno['status']['message'] = 'Enviado';
                        }
                        else
                        {
                            $retorno['status']['erro'] = $status['status'];
                            $retorno['status']['message'] = $status['debugger'];
                        }
                        
                    }
                    else
                    {
                        
                        
                        $retorno['status']['erro'] = TRUE;
                        $retorno['status']['message'] = 'Arquivo em branco.';
                        
                    }
                    
                    
                }
                else
                {
                    $retorno['status']['erro'] = TRUE;
                    $retorno['status']['message'] = 'Não tem nenhum integrante da equipe para ser avisado.';
                }
            }
            else
            {
                    $retorno['status']['erro'] = TRUE;
                    $retorno['status']['message'] = 'Não foi possvel enviar.';
            }
            echo json_encode($retorno);
        }
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @param bool $tarefa
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post()
	{
		$dados = $this->input->post(NULL, TRUE);
                unset($dados['id_responsavel']);
		return $dados;
	}
}


