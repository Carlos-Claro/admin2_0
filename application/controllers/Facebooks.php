<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebooks extends MY_Controller 
{
	private $valida_group = array(
                                    array( 'field'   => 'titulo',             'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                    array( 'field'   => 'mensagem',             'label'   => 'Mensagem', 		'rules'   => 'trim'),
                                    array( 'field'   => 'link',            'label'   => 'Link', 		'rules'   => 'trim'),
                                );
        
        private $valida_page = array(
                                    array( 'field'   => 'mensagem',             'label'   => 'Mensagem', 		'rules'   => 'required|trim'),
                                );
        
        private $valida_cad_page = array(
                                        array( 'field'   => 'id_page[]',             'label'   => 'Id Fan Page', 		'rules'   => 'required|trim|is_unique[facebook_pages.id_page]'),
                                    );
    
        private $valida_cad_cidade = array(
                                        array( 'field'   => 'uf_estado',             'label'   => 'Estado', 		'rules'   => 'required|trim'),
                                        array( 'field'   => 'id_cidade',             'label'   => 'Cidade', 		'rules'   => 'required|trim'),
                                    );
        
        private $valida_cad_categoria = array(
                                        array( 'field'   => 'categoria',             'label'   => 'Categoria', 		'rules'   => 'required|trim'),
                                    );
        
	public function __construct()
	{
            parent::__construct();
            //$this->load->model(array('facebook_groups_model','facebook_pages_model','cidades_model','estados_model','facebook_categorias_model'));
            $this->load->model(array('facebook_groups_model','facebook_pages_model','cidades_model','estados_model'));
            $this->load->library('facebook_lib');
	}
        
        
        public function index()
	{
            
            $this->listar_groups();
	}
        //
	/* Facebook Groups*/
	public function listar_groups($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            //phpinfo();
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_groups( $url, $valores );
            $itens = $this->facebook_groups_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->facebook_groups_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem_groups( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/facebook.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Facebook Groups', 'facebook_groups', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        public function cad_categoria_groups($ok = FALSE)
        {
                $this->form_validation->set_rules($this->valida_cad_categoria); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    $dados['id_categoria'] = $data['categoria'];
                    foreach($data['groups'] as $codigo)
                    {
                        $id = $this->facebook_groups_model->editar($dados, array('facebook_groups.id_group' => $codigo));
                    }
                    redirect(strtolower(__CLASS__).'/cad_categoria_groups/1'); 
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = TRUE, $cidade = FALSE, 'facebook_groups');
                    $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Categoria Facebook Groups Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Groups', 'facebook_groups', 0)
                            ->set_breadscrumbs('Categoria', 'facebook_groups/categoria', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_categorias',$data);
		}
           
        }
        
        public function cad_cidade_groups($ok = FALSE)
        {
            $this->form_validation->set_rules($this->valida_cad_cidade); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    $grupo = $data['groups'];
                    unset($data['groups']);
                    unset($data['uf_estado']);
                    foreach($grupo as $codigo)
                    {
                        $id = $this->facebook_groups_model->editar($data, array('facebook_groups.id_group' => $codigo));
                    }
                    redirect(strtolower(__CLASS__).'/cad_cidade_groups/1'); 
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = TRUE, $cidade = TRUE, 'facebook_groups');
                    $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Cidade Facebook Groups Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Groups', 'facebook_groups', 0)
                            ->set_breadscrumbs('Cidade', 'facebook_groups/cidade', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_cidades',$data);
		}
           
        }
        
        public function update_groups($ok = FALSE)
        {
            $user = $this->facebook_lib->getUser();
            if(!$user)
            {
                $params = array('scope' => 'publish_stream, user_groups, manage_pages, publish_actions');
                header("Location: " . $this->facebook_lib->getLoginUrl($params));
                exit;
            }
            else
            {
                $itens = $this->facebook_lib->get_groups();
                $grupos_banco = $this->facebook_groups_model->get_select();
                if(isset($itens['grupos']['data']) && ($itens['grupos']['data']))
                {
                    foreach ($grupos_banco as $grupo)
                    {
                        if(! in_array($grupo->id, $itens['id']) )
                        {
                             $this->facebook_groups_model->excluir('id_group = '.$grupo->id);
                        }
                    }
                    foreach( $itens['grupos']['data'] as $dados )
                    {
                        $valor = $this->facebook_groups_model->get_itens_por_id($dados['id']);
                        if(!$valor)
                        {
                           $data['id_group'] = $dados['id'];
                           $data['nome'] = $dados['name'];
                           $data['data_cadastro'] = date('Y-m-d H:i:s');  
                           $this->facebook_groups_model->adicionar($data);
                        }
                    }
                }
            }
           redirect(strtolower(__CLASS__).'/listar_groups/'); 
        }
        
        public function postar_groups()
	{
            $user = $this->facebook_lib->getUser();
            if(!$user)
            {
                $params = array('scope' => 'publish_stream, user_groups, manage_pages, publish_actions');
                header("Location: " . $this->facebook_lib->getLoginUrl($params));
                exit;
            }
            else
            {
		$this->form_validation->set_rules($this->valida_group); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'selecionar_cidade') && ( isset($data['todas_cidades'])) )
                    {
                        //postar em todos os grupos de determinada cidade
                        $cidade = $this->facebook_groups_model->get_grupos_por_cidade('id_cidade = "'.$data['id_cidade'].'" ');
                        foreach ($cidade as $groups)
                        {
                           $data['fb_opcao'][] = $groups->id;
                        }
                        $post = $this->facebook_lib->postar($data,0);
                    }
                    else if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'selecionar_cidade') && ( !isset($data['todas_cidades'])))
                    {
                        //postar em alguns grupos de determinada cidade
                        $post = $this->facebook_lib->postar($data,0);
                    }
                    else if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'postar_facebook_categorias') )
                    {
                        //postar em todos os grupos de determinada categoria
                        $categoria = $this->facebook_groups_model->get_grupos_por_categoria('id_categoria = "'.$data['tipo_select'].'" ');
                        foreach ($categoria as $groups)
                        {
                           $data['fb_opcao'][] = $groups->id;
                        }
                        $post = $this->facebook_lib->postar($data,0);
                    }
                    else if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'selecionar_categoria') )
                    {
                        //postar em alguns os grupos de determinada categoria
                        $post = $this->facebook_lib->postar($data,0);
                    }
                    else if(isset($data['postar_selecionar']) && $data['postar_selecionar'] == 'postar_estados')
                    {
                        //postar em todos os grupos do estado
                        $estado = $this->cidades_model->get_uf_por_cidade('uf = "'.$data['tipo_select'].'" ');
                        foreach ($estado as $groups)
                        {
                            $data['fb_opcao'][] = $groups->id;
                        }
                        $post = $this->facebook_lib->postar($data,0);
                    }
                    if(isset($post) && $post)
                    {
                        foreach($post as $key => $item)
                        {
                            $retorno = $this->facebook_groups_model->get_nome_por_id('id_group = '.$key);
                            if( isset($post[$key]) && ($post[$key]) && !isset($post['error']))
                            {
                                echo 'postado com sucesso em: '.$retorno->nome.'<br>';
                            }
                            else
                            {
                                echo 'erro ao postar em: '.$retorno->nome.'<br>';
                            }
                        }
                    }
                    else
                    {
                         echo 'erro na postagem <br>';
                    }
                    echo '<a href="'.base_url().'facebook_groups/listar_groups"> Voltar </a> <br>';
                    //redirect(strtolower(__CLASS__).'/listar/'); 
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = FALSE, $cidade = TRUE, 'facebook_groups');
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Post Facebook Groups Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Groups', 'facebook_groups', 0)
                            ->set_breadscrumbs('Postar', 'facebook_groups/postar', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_post',$data);
		}
            }
        }
        
        private function _inicia_listagem_groups( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID Group',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome',   'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                         
                            );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
                        $data['operacoes'] = FALSE;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
        private function _inicia_filtros_groups($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id_group',           'titulo' => 'ID Group ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'facebook_groups.id_group', 		'valor' => '' ) ),
                                        array( 'name' => 'nome',            'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'facebook_groups.nome', 	'valor' => '' ) ),
                    );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/update_groups'.'" class="btn btn-primary" target="_blank">Atualizar Grupos</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/postar_groups'.'" class="btn btn-primary" target="_blank">Postar em Grupo</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/cad_cidade_groups'.'" class="btn btn-primary" target="_blank">Cadastrar Cidade de Grupos</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/cad_categoria_groups'.'" class="btn btn-primary" target="_blank">Cadastrar Categoria de Grupos</a>';
                
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        /* Facebook Pages */
        public function listar_pages($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_pages( $url, $valores );
            $itens = $this->facebook_pages_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->facebook_pages_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem_pages( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/facebook.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Facebook Pages', 'facebook_pages', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        public function adicionar_pages($ok = FALSE)
        {
            $user = $this->facebook_lib->getUser();
            if(!$user)
            {
                $params = array('scope' => 'publish_stream, user_groups, manage_pages, publish_actions');
                header("Location: " . $this->facebook_lib->getLoginUrl($params));
                exit;
            }
            else
            {
                $this->form_validation->set_rules($this->valida_cad_page); 
                $this->form_validation->set_message('is_unique', 'Esta página já esta gravada');
                $this->form_validation->set_message('required', 'O campo ID é obrigatório');
                if  ( $this->form_validation->run() )
                {
                    $post = $this->_post();
                    $id_page = $post['id_page'];
                    $itens = $this->facebook_lib->get_pages($id_page);
                    $dados = array();
                    foreach($itens as $key => $value)
                    {
                        $dados['id_page'] = $key;
                        $dados['nome'] = $value['name'];
                        $dados['link'] = $value['link'];
                        $dados['data_cadastro'] = date('Y-m-d H:i:s');
                        $this->facebook_pages_model->adicionar($dados); 
                    }
                    redirect(strtolower(__CLASS__).'/adicionar_pages/1');
                }
                else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook();
                    $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Page Facebook Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Pages', 'facebook_pages', 0)
                            ->set_breadscrumbs('Adicionar', 'facebook_pages/adicionar', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_pages',$data);
                }
            }
        }
        
        public function cad_categoria_pages($ok = FALSE)
        {
                $this->form_validation->set_rules($this->valida_cad_categoria); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    $dados['id_categoria'] = $data['categoria'];
                    foreach($data['groups'] as $codigo)
                    {
                        $id = $this->facebook_pages_model->editar($dados, array('facebook_pages.id_page' => $codigo));
                    }
                    redirect(strtolower(__CLASS__).'/cad_categoria_pages/1'); 
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = TRUE, $cidade = FALSE, 'facebook_pages');
                    $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Categoria Facebook Pages Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Pages', 'facebook_pages', 0)
                            ->set_breadscrumbs('Categoria', 'facebook_pages/categoria', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_categorias',$data);
		}
           
        }
        
        public function cad_cidade_pages($ok = FALSE)
        {
                $this->form_validation->set_rules($this->valida_cad_cidade); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    $grupo = $data['groups'];
                    unset($data['groups']);
                    unset($data['uf_estado']);
                    foreach($grupo as $codigo)
                    {
                        $id = $this->facebook_pages_model->editar($data, array('facebook_pages.id_page' => $codigo));
                    }
                    redirect(strtolower(__CLASS__).'/cad_cidade_pages/1'); 
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = TRUE, $cidade = TRUE, 'facebook_pages');
                    $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Cidade Facebook Pages Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Pages', 'facebook_pages', 0)
                            ->set_breadscrumbs('Cidade', 'facebook_pages/cidade', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_cidades',$data);
		}
           
        }
        
        public function postar_pages()
	{
            $user = $this->facebook_lib->getUser();
            if(!$user)
            {
                $params = array('scope' => 'publish_stream, user_groups, manage_pages, publish_actions');
                header("Location: " . $this->facebook_lib->getLoginUrl($params));
                exit;
            }
            else
            {
		$this->form_validation->set_rules($this->valida_page); 
		if  ( $this->form_validation->run() )
		{
                    $data = $this->_post();
                    $data['foto'] = $_FILES['file']['tmp_name'];
                    if( ($data['postar_selecionar'] == 'selecionar_cidade') && (isset($data['todas_cidades'])) )
                    {
                        //postar em todas as pages de determinada cidade
                        $cidade = $this->facebook_pages_model->get_pages_por_cidade('id_cidade ='.$data['id_cidade']);
                        foreach($cidade as $pages)
                        {
                            $data['fb_opcao'][] = $pages->id; 
                        }
                        $post = $this->facebook_lib->postar($data,1);
                    }
                    else if(($data['postar_selecionar'] == 'selecionar_cidade') && ( !isset($data['todas_cidades'])))
                    {
                        //postar em algumas pages de determinada cidade
                        $post = $this->facebook_lib->postar($data,1);
                    }
                    else if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'postar_facebook_categorias') )
                    {
                        //postar em todas as pages de determinada categoria
                        $categoria = $this->facebook_pages_model->get_pages_por_categoria('id_categoria = "'.$data['tipo_select'].'" ');
                        foreach ($categoria as $pages)
                        {
                           $data['fb_opcao'][] = $pages->id;
                        }
                        $post = $this->facebook_lib->postar($data,1);
                    }
                    else if(isset($data['postar_selecionar']) && ($data['postar_selecionar'] == 'selecionar_categoria') )
                    {
                        //postar em alguns os grupos de determinada categoria
                        $post = $this->facebook_lib->postar($data,1);
                    }
                    else if(isset($data['postar_selecionar']) && $data['postar_selecionar'] == 'postar_estados')
                    {
                        //postar em todos os grupos do estado
                        $estado = $this->cidades_model->get_uf_por_cidade('uf = "'.$data['tipo_select'].'" ');
                        foreach ($estado as $pages)
                        {
                            $data['pages'][] = $pages->id;
                        }
                        $post = $this->facebook_lib->postar($data,1);
                    }
                    if(isset($post) && $post)
                    {
                        foreach($post as $key => $item)
                        {
                            $retorno = $this->facebook_pages_model->get_nome_por_id('id_page = '.$key);
                            if( isset($post[$key]) && ($post[$key]) && !isset($post['error']))
                            {
                                echo 'postado com sucesso em: '.$retorno->nome.'<br>';
                            }
                            else
                            {
                                echo 'erro ao postar em: '.$retorno->nome.'<br>';
                            }
                        }
                    }
                    else
                    {
                         echo 'erro na postagem <br>';
                    }
                    echo '<a href="'.base_url().'facebook_groups/listar_pages"> Voltar </a> <br>';
		}
		else
                {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_facebook($filtro = FALSE, $cidade = TRUE, 'facebook_pages');
                    $data['action'] = base_url().$class.'/'.$function;
                    $data['tipo'] = 'Post Facebook Pages Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/facebook.js', TRUE)
                            ->set_include('js/ckeditor/ckeditor.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Facebook Pages', 'facebook_pages/listar', 0)
                            ->set_breadscrumbs('Postar', 'facebook_pages/postar', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_facebook_post',$data);
		}
            }
        }
        
        private function _inicia_listagem_pages( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID Página',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome',   'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'link',   'titulo' => 'Link', 	'link' => str_replace(array('[col]','[ordem]'), array('link',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'link') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'link' ) ? 'ui-state-highlight'.( ($extras['col'] == 'link' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                         
                            );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
        private function _inicia_filtros_pages($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'facebook_pages.id_page', 	'valor' => '' ) ),
                                        array( 'name' => 'link',           'titulo' => 'Link ',         'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'facebook_pages.link', 		'valor' => '' ) ),
                                        array( 'name' => 'nome',            'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'facebook_pages.nome', 	'valor' => '' ) ),
                    );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar_pages'.'" class="btn btn-primary" target="_blank">Adicionar FanPage</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/postar_pages'.'" class="btn btn-primary" target="_blank">Postar em FanPage</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/cad_cidade_pages'.'" class="btn btn-primary" target="_blank">Cadastrar Cidade de Páginas</a>';
                $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/cad_categoria_pages'.'" class="btn btn-primary" target="_blank">Cadastrar Categoria de Páginas</a>';
                
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        /* Funções em comum */
        private function _inicia_facebook($filtro = FALSE, $cidade = FALSE, $classe = NULL)
        {
            $data = NULL;
            if(isset($classe) && $classe)
            {
                $model = $classe.'_model';
                $data['classe'] = $classe;
                $data['item'] = (isset($filtro) && $filtro) ?  $this->$model->get_select('id_cidade = 0') : $this->$model->get_select() ;
                $data['item_categoria'] = (isset($filtro) && $filtro) ?  $this->$model->get_select('id_categoria = 0') : $this->$model->get_select() ;
            }
            $data['categoria'] = $this->facebook_categorias_model->get_select();
            if(isset($cidade) && $cidade)
            {
                 $data['cidade'] = $this->cidades_model->get_select();
                 $data['estado'] = $this->estados_model->get_select();
            }
            return $data;
        }
        
        public function montar_filtro()
        {
            $data = $this->_post();
            if(isset($data['valor']) && $data['valor'] != 'Selecione..')
            {
                $model = $data['valor'].'_model';
                $item = $this->$model->get_select();
                $retorno = $this->facebook_lib->montar_item($data['valor'], $item);
            }
            else
            {
                $retorno = '';
            }
            echo $retorno;
        }
        
        public function montar_select() 
        {
            $data = $this->_post();
            $estado = $this->cidades_model->get_select('uf = "'.$data['tipo_select'].'"');
            $retorno = $this->facebook_lib->montar_select_cidade($estado);
            echo $retorno;
        }
        
        public function montar_check() 
        {
            $data = $this->_post();
            $model = $data['operacao'].'_model';
            if(isset($data['id_cidade']))
            {
                $cidade = $this->$model->get_select('id_cidade = '.$data['id_cidade']);
                $retorno = $this->facebook_lib->montar_checks($cidade);
            }
            else if($data['filtro'] == 'facebook_categorias')
            {
                $categorias = $this->$model->get_select('id_categoria = '.$data['tipo_select']);
                $retorno = $this->facebook_lib->montar_checks($categorias);
            }
            echo $retorno;
        }
        
        private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}

