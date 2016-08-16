<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Classe que gerencia as campanhas de email mkt pow internet, integrando com IAgente
 * 
 * @version 1.0
 * @access public
 * @package Email_mkt
 */
class Email_mkt extends MY_Controller 
{
        
        /**
         * 
         * 
         * @return void 
         */
	public function __construct()
	{
            parent::__construct(FALSE);
            //$this->load->model(array('email_model', 'email_atributos_model', 'email_tipo_atributo_model'));
	}
	
        
        public function administrar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $i = 'email_mkt/administrar';
            $acesso = $this->set_setor_usuario($i);
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_administrar( $url, $valores );
            $itens = $this->email_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->email_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem_administrar( $itens, $extras );
            
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/email_administrar.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('E-mail Mkt', 'email_mkt', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
                
	}
        
        
	private function _inicia_listagem_administrar( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
                        $cabecalho = ' ';
		}
		else 
		{
                        $lista = array(
                                        //(object)array('id' => 'nome',   'descricao' => 'nome',          'acao' => FALSE ),
                                        (object)array('id' => 'id','descricao' => 'ID',             'acao' => FALSE ),
                                        (object)array('id' => 'titulo','descricao' => 'Titulo',     'acao' => FALSE ),
                                        (object)array('id' => 'ativo', 'descricao' => 'Ativo',      'acao' => FALSE ),
                                        );
                    foreach ( $lista as $cabecalho )
                    {
                        $data['cabecalho'][] = (object)array( 
                                                                'chave'     => $cabecalho->id, 
                                                                'acao'      => $cabecalho->acao,    
                                                                'titulo'    => $cabecalho->descricao,       
                                                                'link'      => str_replace(array('[col]','[ordem]'), array($cabecalho->id,( ($extras['ordem'] == 'ASC' && $extras['col'] == $cabecalho->id) ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == $cabecalho->id ) ? 'ui-state-highlight'.( ($extras['col'] == $cabecalho->id && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) 
                                                            );
                    }
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar',        'class' => 'btn btn-info',      'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => strtolower(__CLASS__).'/editar_administrar/[id]', 'target' => '_blank'),
                                                    (object) array('titulo' => 'Estatisticas',  'class' => 'btn btn-warning',   'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => strtolower(__CLASS__).'/estatistica/[id]', 'target' => '_blank'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
        
	private function _inicia_filtros_administrar($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'email.id',      'valor' => '' ) ),
                                        array( 'name' => 'titulo',    'titulo' => 'Titulo: ',               'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'email.titulo',  'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;              
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes']  .= ' <button class="btn btn-info administrar_varios">Administrar vários</button>';
 		$config['botoes']  .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar_administrar'.'" class="btn btn-default">Add novo</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
        public function editar_administrar ( $id = NULL, $ok = NULL )
        {
            $i = 'email_mkt/administrar';
            $acesso = $this->set_setor_usuario($i);
            if ( $acesso['status'] )
            {
                    $item = (isset($id)) ? $this->email_model->get_item($id) : FALSE;
                    $data = $this->_inicia_select($item, TRUE);
                    $classe = __CLASS__;
                    $function = __FUNCTION__;
                    $layout = $this->layout;
                    $layout->set_breadscrumbs('Painel', 'painel',0)->set_breadscrumbs('E-mail_mkt', 'email_mkt', 0);
                    if ( isset($ok) )
                    {
                        if ( $ok == 1 )
                        { 

                            $data['erro']['class'] = 'alert alert-success';
                            $data['erro']['texto'] = 'Seu email foi salvo com sucesso.';
                        }
                        else
                        {
                            $data['erro']['class'] = 'alert alert-danger';
                            $data['erro']['texto'] = 'A ação teve problemas para ser executada, ou salvo sem modificação.';
                        }
                    }
                    $data['item'] = $item;
                    $data['action'] = base_url().strtolower($classe).'/'.strtolower($function).'/'.( isset($id) ? $id : '' );
                    $data['editavel'] = $acesso['edita'];
                    $layout
                                ->set_classe( $classe )
                                ->set_function( $function ) 
                                ->set_include('js/administrar_email.js', TRUE)
                                ->set_include('js/auto_save.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->set_usuario()
                                ->set_menu($this->get_menu($classe, $function))
                                ->view('administrar_email',$data);	
            }
            else
            {
                redirect('painel');
            }
        }

        
        private function _inicia_select( $item = FALSE, $edita = FALSE )
        {
            $retorno = array();
            $retorno['abas'] = $this->email_tipo_atributo_model->get_select();
            
            //$retorno['regras'] = $this->set_has('regras','return',0, $item, $edita);
            return $retorno;
        }
        
        private function _get_itens( $item, $id )
        {
            $ativos = $this->email_atributos_model->get_itens('id_tipo_atributo = '.$item->id.' AND id_email = '.$id);
            $retorno['ativos'] = array();
            if ( isset($ativos['itens']) && $ativos['qtde'] > 0 )
            {
                foreach( $ativos['itens'] as $ativo )
                {
                    $retorno['ativos'][$ativo->id] = $ativo;
                }
            }
            $retorno['itens'] = $this->email_atributos_model->get_itens('id_tipo_atributo = '.$item->id);
            return $retorno;
        }
        
        private function _get_item( $item, $id )
        {
            $data = $this->_get_itens($item, $id);
            $data['item'] = $item;
            $data['id'] = $id;
            $retorno = $this->layout->view('atributo', $data, 'layout/sem_head' , TRUE);
            return $retorno;
        }

        


        public function get_elemento( $id, $elemento, $tipo_retorno = 'echo' )
        {
            $retorno = '';
            $item_tipo_atributo = $this->email_tipo_atributo_model->get_item_por_filtro('function = "'.$elemento.'"');
            $function = '_get_item';
            $retorno = $this->{$function}( $item_tipo_atributo, $id );
            
            switch($tipo_retorno)
            {
                case 'echo':
                    echo $retorno;
                    break;
                case 'json':
                    echo json_encode($retorno);
                    break;
                case 'return':
                    return $retorno;
            }
        }
        
        
        public function set_has( $tipo, $item, $itens_pre, $itens )
        {
            $this->load->library('layout');
            
            
            $has = '';
            if(isset($item) && $item)
            {
                $filtro = 'id_email ='.$item->id;
                $itens = $this->$model->get_itens($filtro);
                if($itens['qtde'] > 0 )
                {
                     foreach ($itens['itens'] as $valor)
                     {
                         $data['item'] = $valor;
                         $data['ordem'] = $valor->id;
                         $has .= $this->layout->view('add_email'.$tipo, $data, 'layout/sem_head' , TRUE);
                     }
                }
                else 
                {
                    if ( $edita )
                    {
                        $data['ordem'] = $ordem;
                        $has .= $this->layout->view('add_email'.$tipo, $data, 'layout/sem_head' , TRUE);
                    }
                }
            }
            else
            {
                
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
                $model = 'email_'.$tipo.'_model';
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
                $model = 'email_'.$tipo.'_model';
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
        
        /**
         * seta a classe images
         * @version 1.0
         * @access public
         */
	public function index()
	{
            
            
            
            /*
            $this->load->library('disparador');
            
            $a = $this->disparador->inicia();
            $raw_email = $this->disparador->send_raw_email();
             * 
             */
	}
        
        private function inicia_select()
        {
            
        }
        
        public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post(FALSE);
            if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
            {
                if ( is_array($dados['campo']) )
                {
                    $filtro = 'email.id = '.$dados['id'];
                    for( $a = 0; $a < count($dados['campo']); $a++ )
                    {
                        $data[$dados['campo'][$a]] = $dados['valor'][$a];
                        
                    }
                }
                else
                {
                    $filtro = 'email.id = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                }
                $afetados = $this->email_model->editar($data, $filtro);
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
                $retorno['id'] = $this->email_model->adicionar($data);
                if ( isset($retorno['id']) && $retorno['id'] )
                {
                    $retorno['status'] = TRUE;
                    $retorno['muda_url'] = base_url().'email_mkt/editar_administrar/'.$retorno['id'].'/';
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
        
        private function _post()
        {
            $data = $this->input->post(NULL, TRUE);
            return $data;
        }
        
        public function recebe()
        {
            
        }
        
        public function envia_geral()
        {
            
        }
        
        public function envio_semanal()
        {
            
        }
        
        public function atualiza_lista()
        {
            $this->load->model(array('contatos_site_model','email_lista_model','email_lista_itens_model'));
            $filtro[] = 'contatos_site.data >= '.mktime (0, 0, 0, date("m")-1, date("d"),  date("Y"));
            $filtro[] = 'empresas.id_subcategoria = 138';
            $filtro[] = 'cadastros.news = 1';
            $filtro[] = 'contatos_site.sincronizado = 0';
            $filtro[] = 'tabela = "imoveis"';
            $contatos = $this->contatos_site_model->get_itens_disparo($filtro);
            $contador = 0;
            if ( isset($contatos['itens']) && $contatos['qtde'] > 0 )
            {
                foreach( $contatos['itens'] as $chave => $valor )
                {
                    $verifica = $this->email_lista_model->get_item_por_filtro('id_cadastro = '.$valor->id_cadastro);
                    if ( isset($verifica) && $verifica )
                    {
                        
                    }
                    else
                    {
                        $array_lista = array(
                                            'id_cadastro' => $valor->id_cadastro,
                                            'email' => $valor->email,
                                            'nome' => $valor->nome,
                                            'tabela_relacao' => $valor->tabela,
                                            );
                        $id_lista = $this->email_lista_model->adicionar($array_lista);
                        $cidades = explode(',',$valor->cidades);
                        foreach($cidades as $cidade)
                        {
                            if ( isset($cidade) && !empty($cidade) )
                            {
                                $array_itens = array(
                                                    'id_email_lista' => $id_lista,
                                                    'chave' => 'id_cidade',
                                                    'valor' => $cidade,
                                                    );
                                $this->email_lista_itens_model->adicionar($array_itens);
                            }
                        }
                        
                        $negocios = explode(',',$valor->tipo_negocio_item);
                        foreach($negocios as $negocio)
                        {
                            if ( isset($negocio) && !empty($negocio) )
                            {
                                $array_itens = array(
                                                    'id_email_lista' => $id_lista,
                                                    'chave' => 'negocio',
                                                    'valor' => $negocio,
                                                    );
                                $this->email_lista_itens_model->adicionar($array_itens);
                            }
                        }
                        
                        $tipos = explode(',',$valor->id_tipo_item);
                        foreach($tipos as $tipo)
                        {
                            if ( isset($tipo) && !empty($tipo) )
                            {
                                $array_itens = array(
                                                    'id_email_lista' => $id_lista,
                                                    'chave' => 'id_tipo',
                                                    'valor' => $tipo,
                                                    );
                                $this->email_lista_itens_model->adicionar($array_itens);
                            }
                        }
                        //die();
                    }
                    $contador++;
                }
                
            }
            echo $contador.' adicionados';
            /*
             * 
      ["qtde_contatos"]=>
      string(1) "1"
      ["id_itens"]=>
      string(6) "851964"
      ["id_tipo_item"]=>
      string(1) "1"
      ["cidades"]=>
      string(1) "2"
      ["tipos_item"]=>
      string(11) "apartamento"
      ["tipo_negocio_item"]=>
      string(7) "locacao"
             */
        }
        
        public function disparo_padrao()
        {
            $tempo_abertura = time();
            $data_abertura = date('Y-m-d H:i');
            $this->load->model(array('cidades_model','imoveis_model'));
            $this->load->model(array('contatos_site_model'));
            $filtro[] = 'contatos_site.data >= '.mktime (0, 0, 0, date("m")-3, date("d"),  date("Y"));
            $filtro[] = 'contatos_site.data <= '.mktime (0, 0, 0, date("m"), date("d")-1,  date("Y"));
            $filtro[] = 'empresas.id_subcategoria = 138';
            $filtro[] = 'cadastros.news = 1';
            $filtro[] = 'contatos_site.sincronizado = 0';
            $filtro[] = 'tabela = "imoveis"';
            $contatos = $this->contatos_site_model->get_itens_disparo($filtro,'id','DESC',0,100);
            $contador = 0;
            if ( isset($contatos['itens']) && $contatos['qtde'] > 0 )
            {
                foreach ( $contatos['itens'] as $contato )
                {
                    $corpo_email = $this->set_corpo( $contato );
                    if ( $corpo_email )
                    {
                        $disparo = array(
                                        'iagente' => TRUE,
                                        'assunto' => 'Encontre seu novo Imóvel',
                                        'mensagem' => $corpo_email,
                                        'email' => 'Encontre seu novo Imóvel <seu-novo_imovel@rededeportais.com.br>',
                                        'to' => $contato->email,
                                        'retorno' => TRUE
                                        );
                        //$retorno_disparo = $this->envio($disparo);
                        echo $corpo_email;
                        $contador++;
                        $ids = explode(',', $contato->id);
                        foreach( $ids as $id )
                        {
                  //          $atualiza = $this->contatos_site_model->editar(array('sincronizado' => 1),array( 'id' => $id));
                        }
                    }
                    
                }
                $tempo_fechou = time();
                $data_fechou = date('Y-m-d H:i');
                $disparo = array(
                                'iagente' => TRUE,
                                'assunto' => 'Resultado de disparos',
                                'mensagem' => 'Total de '.$contatos['qtde'].' com sucesso: '.$contador.' em : '.($tempo_fechou - $tempo_abertura).'s de: '.$data_abertura.' até '.$data_fechou,
                                'email' => 'Encontre seu novo Imóvel <seu-novo_imovel@rededeportais.com.br>',
                                'to' => 'programacao@pow.com.br',
                                'retorno' => TRUE
                                );
                //$retorno_disparo = $this->envio($disparo);
            }
            
        }
        
        /**
         * 
         */
        public function set_corpo( $item )
        {
            $this->load->library('email_mkt_');
            $data['contato'] = $item;
            //var_dump($item);
            $cidade = $this->cidades_model->get_itens_por_id_in($item->cidades);
            $data['logo'] = $cidade[0]->topo;
            $filtro = $this->_filtro($item->cidades,$item->id_tipo_item,$item->tipo_negocio_item);
            $data['itens'] = $this->imoveis_model->get_itens_email($filtro->get_filtro(),'rand()','',0,8);
            $data['cidade'] = array('titulo' => $cidade[0]->nome, 'link' => $cidade[0]->link, 'portal' => $cidade[0]->portal);
            $data['titulo'] = 'Confira alguns imóveis selecionados para você';
            $retorno = FALSE;
            if ( isset($data['itens']['itens']) )
            {
                $retorno = $this->email_mkt_->html($data);
            }
            return $retorno;
    }
    
    public function limpa_envios()
    {
        $this->load->model(array('contatos_site_model'));
        $data = array( 'sincronizado' => 0 );
        $filtro = 'sincronizado = 1';
        $a = $this->contatos_site_model->editar($data,$filtro);
        echo date('Y-d-m H:i').' - '.$a.PHP_EOL;
    }
    
    
       
    private function _filtro($cidades, $tipos, $negocios)
    {
        $config['valores']['vendido'] = 1;
        $config['valores']['reservaimovel'] = 1;
        $config['valores']['locado'] = 1;
        $config['valores']['invisivel'] = 1;
        $config['valores']['vencimento'] = 1;
        $config['valores']['bloqueio'] = 1;
        $config['valores']['servico_ini'] = time();
        $config['valores']['servico_fim'] = time();
        $config['itens'] = array(
                                array( 'name' => 'vencimento',   'tipo' => 'inativo',   'where' => '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ),
                                array( 'name' => 'servico_ini',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => '' ) ), 
                                array( 'name' => 'servico_fim',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => '' ) ),
                                array( 'name' => 'reservaimovel','tipo' => 'inativo',   'where' => 'imoveis.reservaimovel = 0 '),
                                array( 'name' => 'vendido',      'tipo' => 'inativo',   'where' => 'imoveis.vendido = 0 '),
                                array( 'name' => 'locado',       'tipo' => 'inativo',   'where' => 'imoveis.locado = 0 '),   
                                array( 'name' => 'bloqueio',     'tipo' => 'inativo',   'where' => 'empresas.bloqueado = 0 '),   
                                array( 'name' => 'invisivel',    'tipo' => 'inativo',   'where' => 'imoveis.invisivel = 0 '),    
                                );
        if ( isset($cidades) && $cidades )
        {
            $config['valores']['id_cidade'] = $cidades;
            $config['itens'][] = array( 'name' => 'id_cidade',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where_in',  'campo' => 'imoveis.id_cidade',    'valor' => '' ) );
        }
        if ( isset($tipos) )
        {
            $config['valores']['id_tipo'] = $tipos;
            $config['itens'][] = array( 'name' => 'id_tipo',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where_in',  'campo' => 'imoveis.id_tipo',    'valor' => '' ) );
        }
        if ( isset($negocios) && $negocios )
        {
            $n = explode(',', $negocios);
            if ( count($n) > 1 )
            {
                $config['valores']['group_start'] = 1;
                $config['itens'][] = array( 'name' => 'group_start',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'group_start',  'campo' => '',    'valor' => '' ) );
                foreach( $n as $m )
                {
                    $tm = trim($m);
                    $config['valores'][$tm] = 1;
                    $config['itens'][] = array( 'name' => $tm,  'tipo' => 'inativo',   'where' => array( 'tipo' => 'or_where',  'campo' => 'imoveis.'.$tm,    'valor' => '' ) );
                }
                $config['valores']['group_end'] = 1;
                $config['itens'][] = array( 'name' => 'group_end',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'group_end',  'campo' => '',    'valor' => '' ) );
                
            }
            else
            {
                    $tm = trim($n[0]);
                    $config['valores'][$n[0]] = 1;
                    $config['itens'][] = array( 'name' => $tm,  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$n[0],    'valor' => '' ) );
            }
        }
        return $this->filtro->inicia($config);
    }
    
        
}