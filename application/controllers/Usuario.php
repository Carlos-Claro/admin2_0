<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de usuarios
 * @version 1.0
 * @access public
 * @package usuarios
 */
class Usuario extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessrios do formulario
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'nome',             'label'   => 'Nome', 		'rules'   => 'required'),
                                array( 'field'   => 'email',            'label'   => 'E-mail', 		'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'senha',            'label'   => 'Senha', 		'rules'   => 'trim'),
                                array( 'field'   => 'resenha',          'label'   => 'Redigite a senha','rules'   => 'trim|matches[resenha]'),
                                array( 'field'   => 'telefone',         'label'   => 'Telefone',        'rules'   => 'trim'),
                                array( 'field'   => 'data_cadastro',    'label'   => 'Data Cadastro',   'rules'   => 'trim'),
                                array( 'field'   => 'empresa',          'label'   => 'Empresa',         'rules'   => 'trim'),
                                //array( 'field'   => 'cargos[]',            'label'   => 'Cargo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'observacao',       'label'   => 'Observação',      'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),

                                );

        /**
         * Constroi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model('usuario_model');
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
         * Cria a listagem de canais carregando inicia filtros, itens, total itens,
         * inicia listagem, definir URL da pagina, chama o usuario_model que vai 
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
            $itens = $this->usuario_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->usuario_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/usuario.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Usuários', 'usuario', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * Exportar uma lista usuarios para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->usuario_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e adiciona os campos de valida no banco de dados com suas validações
         * @version 1.0
         * @access public
         * @return void - redireciona ou monta o formulario
         */
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
                        if ( isset($data['sel']) )
                        {
                            $sel = $data['sel'];
                            unset($data['sel']);
                        }
                        else
                        {
                            $sel = array();
                        }
                        
                        if(isset($data['cargos']) && $data['cargos'])
                        {
                            $cargos = $data['cargos'];
                            unset($data['cargos']);
                        }
                        
			$id = $this->usuario_model->adicionar($data);
                        
                        if(isset($cargos) && $cargos)
                        {
                            foreach($cargos as $cargo)
                            {
                                $cargo = array('id_usuario' => $id, 'id_pow_cargos' => $cargo);
                                $this->usuario_model->adicionar_has_cargos($cargo);
                            }
                        }
                        
                        foreach ( $sel as $s ) 
                        {
                            $d = array( 'id_usuario' => $id, 'id_setor' => $s );
                            $this->usuario_model->adicionar_has($d);
                        }
                        
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
                        $data['ckeditor_observacao'] = $this->inicia_ckeditor('observacao');  
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Usuário Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/usuario.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Usuários', 'usuario', 0)
                                ->set_breadscrumbs('Adicionar', 'usuario/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_usuario',$data);
		}
		
	}
	
        /**
         * Monta o formulario ou edita as informações do perfil com base na this->valida 
         * @param bool $ok - verifica se os dados foram salvos 
         * @version 1.0
         * @access public
         */
        public function edita_perfil($ok = FALSE)
        {
            $codigo = $this->sessao['id'];
            $dados = $this->usuario_model->get_item($codigo);
            if ($dados)
            {
                    $this->form_validation->set_rules($this->valida);
                    if ($this->form_validation->run())
                    {
                            $data = $this->_post();
                            $data['ativo'] = 1;
                            unset($data['sel']);
                            
                            $this->usuario_model->excluir_has_cargos('usuarios_has_cargos.id_usuario = '.$codigo);
                            if(isset($data['cargos']) && $data['cargos'])
                            {
                                $cargos = $data['cargos'];
                                $data_cargo['id_usuario'] = $codigo;
                                foreach($cargos as $cargo)
                                {
                                    $data_cargo['id_pow_cargos'] = $cargo;
                                    $this->usuario_model->adicionar_has_cargos($data_cargo);
                                }
                                unset($data['cargos']);
                            }
                            
                            $id = $this->usuario_model->editar($data, array('usuarios.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/edita_perfil/1');
                    }
                    else
                    {
                            $function = strtolower(__FUNCTION__);
                            $class = strtolower(__CLASS__);
                            $data = $this->_inicia_select($codigo);
                            $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                            $data['action_novo'] = base_url().$class.'/adicionar/';
                            $data['tipo'] = 'Usuário Editar';//$data = $this->_init_selects();
                            $data['item'] = $dados;
                            $data['mostra_id'] = TRUE;
                            $data['self'] = true;
                            $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                            
                            $this->layout
                                    ->set_function( $function )
                                    ->set_include('js/usuario.js', TRUE)
                                    ->set_include('css/estilo.css', TRUE)
                                    ->set_breadscrumbs('Painel', 'painel',0)
                                    ->set_breadscrumbs('Usuários', 'usuario', 0)
                                    ->set_breadscrumbs('Editar', 'usuario/editar/'.$codigo, 1)
                                    ->set_usuario($this->set_usuario())
                                    ->set_menu($this->get_menu($class, $function))
                                    ->view('add_usuario',$data);
                    }
            }
            else 
            {
                    redirect('painel/');
            }
            
        }
        
        /**
         * Monta o formulario ou edita as informações com base na this->valida 
         * @param string $codigo
         * @param bool $ok - verifica se os dados foram salvos 
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->usuario_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                $this->usuario_model->excluir_has_cargos('usuarios_has_cargos.id_usuario = '.$codigo);
                                if(isset($data['cargos']) && $data['cargos'])
                                {
                                    $cargos = $data['cargos'];
                                    $data_cargo['id_usuario'] = $codigo;
                                    foreach($cargos as $cargo)
                                    {
                                        $data_cargo['id_pow_cargos'] = $cargo;
                                        $this->usuario_model->adicionar_has_cargos($data_cargo);
                                    }
                                    unset($data['cargos']);
                                }
                                
                                
				$id = $this->usuario_model->editar($data, array('usuarios.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['ckeditor_observacao'] = $this->inicia_ckeditor('observacao');  
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['action_novo'] = base_url().$class.'/adicionar/';
				$data['tipo'] = 'Usuário Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/usuario.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Usuários', 'usuario', 0)
                                        ->set_breadscrumbs($dados->nome, 'usuario', 1)
                                        //->set_breadscrumbs('Editar', 'usuario/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_usuario',$data);
			}
		}
		else 
		{
			redirect('usuario/listar');
		}
	}
        
        public function monta_cronograma( $id_usuario )
        {
            $this->load->model( array('tarefas_model') );
            $data['datas'] = $this->tarefas_model->get_maior_menor_data_por_usuario($id_usuario);
            //var_dump($data['datas']);
            $filtro = 'usuarios.id = '.$id_usuario.' AND tarefas.id_tarefas_status = 1';
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
            //echo $retorno['cronograma'];
            echo json_encode($retorno);
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
            $this->load->model(array('setores_model', 'empresas_model','pow_cargos_model'));
            $retorno['setores'] = $this->setores_model->get_select();
            $retorno['empresas'] = $this->empresas_model->get_select();
            $retorno['cargos'] = $this->pow_cargos_model->get_select();
            if ( $id )
            {
                $retorno['cargos_selecionados'] = $this->usuario_model->get_selected_cargos('usuarios.id = '.$id);
                $retorno['selecionados'] = $this->usuario_model->get_item_has($id);
            }
            return $retorno;
        }
        
        /**
         * Deleta um usuario e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
                $this->usuario_model->excluir_has('usuarios_setores.id_usuario in ('.implode(',',$selecionados).')');    
                $this->usuario_model->excluir_has_cargos('usuarios_has_cargos.id_usuario in ('.implode(',',$selecionados).')');    
		$quantidade = $this->usuario_model->excluir('usuarios.id in ('.implode(',',$selecionados).')');
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
         * chama os campos necessarios para criar o cabeçalho e 
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
                                                    (object)array( 'chave' => 'nome',   'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'empresa','titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'cargo',  'titulo' => 'Cargo', 	'link' => str_replace(array('[col]','[ordem]'), array('cargo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cargo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cargo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cargo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'email',  'titulo' => 'E-mail', 	'link' => str_replace(array('[col]','[ordem]'), array('email',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'email') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'email' ) ? 'ui-state-highlight'.( ($extras['col'] == 'email' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'ativo',  'titulo' => 'Ativo',    'link' => str_replace(array('[col]','[ordem]'), array('ativo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ativo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'ativo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'ativo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'setores','titulo' => 'Setores',    'link' => str_replace(array('[col]','[ordem]'), array('setores',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'setores') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'setores' ) ? 'ui-state-highlight'.( ($extras['col'] == 'setores' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por id, nome e email,
         * cria botões de adicionar, exportar e deletar selecionados
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'usuarios.id', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',            'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'usuarios.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'email',           'titulo' => 'E-mail: ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'usuarios.email', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
        /**
         * Mostra se esta ativo ou inativo
         * @return array $retorno
         * @version 1.0
         * @access private
         */
	private function _get_ativo()
	{
		$retorno = array(
							(object)array('id' => '0', 'descricao' => 'Inativo'),
							(object)array('id' => '1', 'descricao' => 'Ativo'),
							);
		return $retorno;
	}
        
        /**
         * Mostra se usuario é admin ou vendedor
         * @return array $retorno
         */
        private function _get_tipo()
        {
            $retorno = array(
                            (object)array('id' => 'ADM', 'descricao' => 'Administrador'),
                            (object)array('id' => 'NOR', 'descricao' => 'Vendedor')
            );
            return $retorno;
        }
	
        public function set_select()
        {
            $filtro = 'ativo = 1';
            $retorno = $this->usuario_model->get_select($filtro);
            echo json_encode($retorno);
        }
        
        public function has_setores()
        {
            $post = $this->input->post(NULL, TRUE);
            if ( isset($post) )
            {
                $add = $post;
                if ( isset($post['edita']) )
                {
                    unset($post['edita']);
                }
                $this->usuario_model->excluir_has($post);
                $this->usuario_model->adicionar_has($add);
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['message'] = 'Adicionado com sucesso.';
            }
            else
            {
                $retorno['erro']['status'] = TRUE;
                $retorno['erro']['message'] = 'Não foi possivel alterar setor.';
            }
            echo json_encode($retorno);
        }
        
        public function deleta_has_setores()
        {
            $post = $this->input->post(NULL, TRUE);
            if ( isset($post) )
            {
                $this->usuario_model->excluir_has($post);
                $retorno['erro']['status'] = FALSE;
                $retorno['erro']['message'] = 'excluido com sucesso.';
            }
            else
            {
                $retorno['erro']['status'] = TRUE;
                $retorno['erro']['message'] = 'Não foi possivel alterar setor.';
            }
            echo json_encode($retorno);
        }
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data - com todos os campos setados no formulario
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
                
                if ( empty($data['senha']) )
                {
                    unset($data['senha']);
                }
                else
                {
                    $data['senha'] = md5($data['senha']);
                }
                if ( empty($data['resenha']) )
                {
                    unset($data['resenha']);
                }
                else
                {
                    unset($data['resenha']);
                }
		return $data;
	}
}
