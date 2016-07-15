<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de funcoes
 * @version 1.0
 * @access public
 * @package funcoes
 */
class Funcoes extends MY_Controller 
{       
        /**
         * Cria um array para validar a pagina com os campos necessários do formulário
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );
        
        private $conn = FALSE;
        
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            if ( COMMAND_LINE )
            {
                $valida = FALSE;
            }
            else
            {
                if ( isset($_GET['own']) )
                {
                    $valida = FALSE;
                    
                }
                else
                {
                    $valida = ( isset($_GET['usuario'] ) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86' ) ? FALSE : TRUE;
                    
                }
            }
            parent::__construct($valida);
            $this->load->model(array('subcategorias_model', 'status_atualizada_model'));
	}
        
        
        public function atualiza_ip_pow()
        {
            $this->load->model( array('ip_pow_model') );
            $ip = $this->ip_pow_model->get_item();
            if ( $ip->id !== $_SERVER['REMOTE_ADDR'] )
            {
                $data = array('id' => $_SERVER['REMOTE_ADDR']);
                $filtro = array('id' => $ip->id );
                $this->ip_pow_model->editar($data, $filtro);
            }
            
        }
        
        public function envia_mail_aws()
        {
            $send = $this->envio(array());
            var_dump($send);
        }
        
    public function sincroniza_empresas_has_imoveis()
    {
        $this->load->model( array('imoveis_model','empresas_has_imoveis_model') );
        $ultimo_imovel = $this->empresas_has_imoveis_model->get_max_item();
        var_dump($ultimo_imovel);
        $imoveis = $this->imoveis_model->get_imoveis_has_empresas($ultimo_imovel);
        if ( isset($imoveis) && count($imoveis) > 0)
        {
            $add = $this->empresas_has_imoveis_model->adicionar_multi($imoveis);
            echo '<br>itens inseridos: '.count($imoveis);
        }
            echo '<br>Tabela Atualizada';
        
    }
        
    public function transfere_imagens_banco( $offset = 0, $id_empresa = FALSE, $sem_ultimo = FALSE )
    {
        $this->load->model( array('imoveis_model', 'imoveis_images_model') );
        $complemento = NULL;
        if ( $id_empresa )
        {
            $complemento = ' AND empresas.id = '.$id_empresa;
        }
        if ( ! $sem_ultimo )
        {
            $ultimo = $this->imoveis_images_model->get_itens($complemento, 'imoveis_images.id', 'DESC', 0, 1);
        }
        $filtro = 'empresas.sistema = 0';
        if ( isset($ultimo) )
        {
            $filtro .= ' AND imoveis.id > '.$ultimo->id_imovel.( $id_empresa ? ' AND imoveis.id_empresa = '.$id_empresa : '' );
        }
        else 
        {
            $filtro .= ! empty($complemento) && isset($complemento) ? $complemento : '';
        }
        $imoveis = $this->imoveis_model->get_itens_fotos( $offset, $filtro );
        if ( isset($imoveis) && $imoveis['qtde'] > 0 )
        {
            $retorno = array();
            $array_result = NULL;
            $array_inclusao = NULL;
            foreach( $imoveis['itens'] as $imovel )
            {
                
                echo 'imovel-> '.$imovel->id.'<br>'.PHP_EOL;
                for( $a = 1; $a < 12; $a++ )
                {
                    $foto = 'foto'.$a;
                    $descricao = 'descricao'.$a;
                    if ( isset($imovel->$foto) && ! empty( $imovel->$foto ) && $imovel->$foto )
                    {
                        $array_inclusao[] = array('arquivo' => $imovel->$foto, 'id_empresa' => $imovel->id_empresa, 'id_imovel' => $imovel->id, 'data' => date('Y-m-d H:i', $imovel->time ), 'titulo' => (isset($imovel->$descricao) ? trim($imovel->$descricao) : ''), 'ordem' => $a );
                    }
                }
            }
            if ( isset($array_inclusao))
            {
                $array_result = $this->imoveis_images_model->adicionar_multi($array_inclusao);
            }
            echo '----------------Fim--------------------------------<br>';
            if ( ! $id_empresa )
            {
                $this->transfere_imagens_banco();
            }
        }
        else
        {
            echo 'nenhum imóvel pendente.';
        }
    }
    
    public function transfere_imagens_banco_por_empresa( $id_empresa = FALSE )
    {
        $this->load->model( array('imoveis_model', 'imoveis_images_model') );
        $complemento = 'imoveis.id_empresa = '.$id_empresa;
        $imoveis = $this->imoveis_model->get_itens_fotos( 0, $complemento );
        if ( isset($imoveis) && $imoveis['qtde'] > 0 )
        {
            $retorno = array();
            $array_result = NULL;
            $array_inclusao = NULL;
            foreach( $imoveis['itens'] as $imovel )
            {
                $qtde_tem = $this->imoveis_images_model->get_total_images( 'imoveis_images.id_imovel = '.$imovel->id );
                if ( ! $qtde_tem )
                {
                    echo 'imovel-> '.$imovel->id.'<br>'.PHP_EOL;
                    for( $a = 1; $a < 12; $a++ )
                    {
                        $foto = 'foto'.$a;
                        $descricao = 'descricao'.$a;
                        if ( isset($imovel->$foto) && ! empty( $imovel->$foto ) && $imovel->$foto )
                        {
                            $array_inclusao[] = array('arquivo' => $imovel->$foto, 'id_empresa' => $imovel->id_empresa, 'id_imovel' => $imovel->id, 'data' => date('Y-m-d H:i', $imovel->time ), 'titulo' => (isset($imovel->$descricao) ? trim($imovel->$descricao) : ''), 'ordem' => $a );
                        }
                    }
                    
                }
                else
                {
                    echo 'imovel->'.$imovel->id.' Ja tem images <br>'.PHP_EOL;
                }
            }
            if ( isset($array_inclusao))
            {
                $array_result = $this->imoveis_images_model->adicionar_multi($array_inclusao);
            }
            echo '----------------Fim--------------------------------<br>';
        }
        else
        {
            echo 'nenhum imóvel pendente.';
        }
    }
        
        
    public function transfere_imagens_banco_faltando( )
    {
        $this->load->model( array('imoveis_model', 'imoveis_images_model') );
        $complemento = 'imoveis.id > 810000';
        $imoveis = $this->imoveis_model->get_itens_fotos( 0, $complemento );
        if ( isset($imoveis) && $imoveis['qtde'] > 0 )
        {
            $retorno = array();
            $array_result = NULL;
            $array_inclusao = NULL;
            foreach( $imoveis['itens'] as $imovel )
            {
                $qtde_tem = $this->imoveis_images_model->get_total_images( 'imoveis_images.id_imovel = '.$imovel->id );
                if ( ! $qtde_tem )
                {
                    echo 'imovel-> '.$imovel->id.'<br>'.PHP_EOL;
                    for( $a = 1; $a < 12; $a++ )
                    {
                        $foto = 'foto'.$a;
                        $descricao = 'descricao'.$a;
                        if ( isset($imovel->$foto) && ! empty( $imovel->$foto ) && $imovel->$foto )
                        {
                            $array_inclusao[] = array('arquivo' => $imovel->$foto, 'id_empresa' => $imovel->id_empresa, 'id_imovel' => $imovel->id, 'data' => date('Y-m-d H:i', $imovel->time ), 'titulo' => (isset($imovel->$descricao) ? trim($imovel->$descricao) : ''), 'ordem' => $a );
                        }
                    }
                    
                }
                else
                {
                    echo 'imovel->'.$imovel->id.' Ja tem images <br>'.PHP_EOL;
                }
            }
            if ( isset($array_inclusao))
            {
                $array_result = $this->imoveis_images_model->adicionar_multi($array_inclusao);
            }
            echo '----------------Fim--------------------------------<br>';
        }
        else
        {
            echo 'nenhum imóvel pendente.';
        }
    }
    
    public function duplica_imoveis_para_empresas($id_origem, $id_destino)
    {
        $this->load->model(array('imoveis_model', 'imoveis_images_model'));
        
        
        
    }
    
        
    public function aplica_tem_site()
    {
        $this->load->model('empresas_model');
        $filtro[] = 'servicos_pagina = 1 ';
        $filtro[] = 'servicos_pagina_inicio <= '.time();
        $filtro[] = 'servicos_pagina_termino >= '.time();
        $filtro[] = '( (pagina_linhas >"0") or (plano_desejado>=100 and plano_desejado<200) ) ';
        $itens = $this->empresas_model->get_itens($filtro);
        if ( isset($itens['itens']) && $itens['qtde'] > 0 )
        {
            foreach( $itens['itens'] as $item )
            {
                $filtro = array('id' => $item->id );
                $data = array('tem_site' => 1);
                $this->empresas_model->editar($data, $filtro);
                echo '<br> Empresa tem site - '.$item->id.' - '.$item->empresa_nome_fantasia;
            }
        }
    }

    public function aplica_pagina_tipo( $tipo = 'imoveis' ) 
    {
        $this->load->model('empresas_model');
        switch( $tipo )
        {
            case 'imoveis':
                break;
            case 'ecommerce':
                break;
            case 'padrao':
                break;
            case 'institucional':
                break;
        }

        $filtro[] = 'servicos_pagina = 1 ';
        $filtro[] = 'servicos_pagina_inicio <= '.time();
        $filtro[] = 'servicos_pagina_termino >= '.time();
        $filtro[] = '( (pagina_linhas >"0") or (plano_desejado>=100 and plano_desejado<200) ) ';
        $itens = $this->empresas_model->get_itens($filtro);
        if ( isset($itens['itens']) && $itens['qtde'] > 0 )
        {
            foreach( $itens['itens'] as $item )
            {
                $filtro = array('id' => $item->id );
                $data = array('tem_site' => 1);
                $this->empresas_model->editar($data, $filtro);
                echo '<br> Empresa tem site - '.$item->id.' - '.$item->empresa_nome_fantasia;
            }
        }

    }
    
        public function agrupa_log_pesquisa_portais ( )
        {
            $this->load->model('log_pesquisa_portais_model');
            $itens = $this->log_pesquisa_portais_model->get_itens_por_cidade_filtro();
            var_dump( $itens );
        }
        
        
        public function images_rede()
        {
            $endereco = 'http://www.rededeportais.com.br/images_admin/deletar_imoveis_inativos';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Pow_carlos_robot');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
            curl_setopt($ch, CURLOPT_URL, $endereco);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $retorno = curl_exec($ch);
            curl_close($ch);
            var_dump($retorno);
        }
        
        public function corrigi_clics()
        {
            die('acesso não autorizado....');
            $this->load->model(array('imoveis_model','logs_model'));
            $filtro = 'imoveis.clics > imoveis.views';
            $imoveis = $this->imoveis_model->get_itens($filtro);
            if ( isset($imoveis['itens']) && $imoveis['qtde'] > 0 )
            {
                foreach( $imoveis['itens'] as $imovel )
                {
                    $qtde = $this->logs_model->get_qtde_clicks_por_id_tabela($imovel->id);
                    
                    $data = 'clics = '.$qtde;
                    $filtro = 'imoveis.id = '.$imovel->id;
                    $affected = $this->imoveis_model->editar($data,$filtro);
                    echo '<br>imovel: '.$imovel->id.' - Clicks: '.$qtde.' - afetado: '.$affected.' - clicks errados: '.$imovel->clics;
                }
            }
            //var_dump($imoveis);
        }
        
        /*
         * Função utilizada apenas para atualizar a base de dados da empresa de contatos
         * identificando quem é o autorizador  e contato
         * @deprecated
        */
        public function atualizar_contatos_empresa()
        {
            $this->load->model(array('empresas_model','empresas_contato_model'));
            $contatos = $this->empresas_contato_model->get_autorizador();
            
            foreach($contatos['itens'] as $contato)
            {
                $empresas = $this->empresas_model->get_itens_contato('empresas.id = '.$contato->id_empresa);
                foreach($empresas as $empresa)
                {
                    if($contato->email == $empresa->autorizador_email)
                    {
                        echo 'autorizador - '.$contato->email.' - <br>';//var_dump($empresa);
                    }
                }
                die();
            }
            
        }
	
        /**
         * Redireciona para o painel
         * @version 1.0
         * @access public
         */
        public function index()
	{
            redirect('painel');
	}
	
        /**
         * @version 1.0
         * @access public
         */
        public function atualiza_lista_ip()
        {
            error_reporting(E_ALL);
            $this->load->model(array('registro_ip_model','ip_robot_model'));
            $filtro = '(user_agent like "%bot%" OR user_agent like "%baidu%" OR user_agent like "%crawler%" ) AND data between "'.date("Y-m-d H:i",mktime (0, 0, 0, date("m")  , date("d")-30, date("Y") ) ).'" and "'.date("Y-m-d H:i").'" AND ip_robot.id IS NULL';
            $registros = $this->registro_ip_model->get_item_robot($filtro);
            $qtde = 0;
            foreach ( $registros as $registro )
            {
                $explode = explode(' ', $registro->user_agent);
                foreach ( $explode as $e )
                {
                    $faq = '';
                    $descricao = '';
                    $descri = FALSE;
                    $fasq = FALSE;
                    if ( strstr($e,'http') && ! $fasq )
                    {
                        $faq = $e;
                        $fasq = TRUE;
                    }
                    if ( ( strstr($e,'bot') || strstr($e,'baidu') ) && ! $descri )
                    {
                        $descricao = $e;
                        $descri = TRUE;
                    }
                }
                
                $data = array(
                            'ip' => $registro->ip,
                            'faq' => $faq,
                            'descricao' => $descricao,
                            'data_inclusao' => date('Y-m-d H-i')
                            );
                $ip_robot = $this->ip_robot_model->get_item_por_ip($data['ip']);
                if ( ! isset($ip_robot) )
                {
                    $this->ip_robot_model->adicionar($data);
                    $qtde++;
                }
                //var_dump($data);
            }
            echo 'Total = '.$qtde;
            $this->registro_ip_model->truncate();
            //var_dump($registros);
        }
        
        /**
         * cria a listagem de funcoes carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o empresas_model que vai 
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
            $itens = $this->empresas_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->empresas_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/empresas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Empresas', 'empresas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista canais para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->empresas_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
	public function adicionar()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			$id = $this->empresas_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Empresas Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/empresas.js', TRUE)
                                ->set_include('js/ckeditor/ckeditor.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Empresas', 'setor', 0)
                                ->set_breadscrumbs('Adicionar', 'empresas/adicionar', 1)
				->set_usuario($this->set_usuario())
				->view('add_empresas',$data);
		}   
		 
	}
	
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->empresas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
				$id = $this->empresas_model->editar($data, array('empresas.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
				$data['tipo'] = 'Empresas Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/empresas.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Empresas', 'empresas', 0)
                                        ->set_breadscrumbs('Editar', 'empresas/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
					->view('add_empresas',$data);
			}
		}
		else 
		{
			redirect('empresas/listar');
		}
	}
	
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['pai'] = $this->empresas_model->get_select();
            return $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->empresas_model->excluir('empresas.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
	private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
                    /*
                     * ,                             , , , , , dominio, razao_social, nome_fantasia, cnpj, status, atualizou, conhece_guia
                     */
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'contrato',     'titulo' => 'Contrato',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'contrato') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'contrato' ) ? 'ui-state-highlight'.( ($extras['col'] == 'contrato' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'subcategoria',     'titulo' => 'Categoria',       'link' => str_replace(array('[col]','[ordem]'), array('subcategoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'subcategoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'subcategoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'subcategoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'inscricao',     'titulo' => 'Inscrição',       'link' => str_replace(array('[col]','[ordem]'), array('inscricao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'inscricao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'inscricao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'inscricao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'endereco',     'titulo' => 'Endereço',       'link' => str_replace(array('[col]','[ordem]'), array('endereco',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'endereco') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'endereco' ) ? 'ui-state-highlight'.( ($extras['col'] == 'endereco' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'logradouro',     'titulo' => 'Logradouro Valido',       'link' => str_replace(array('[col]','[ordem]'), array('logradouro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'logradouro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'logradouro' ) ? 'ui-state-highlight'.( ($extras['col'] == 'logradouro' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'telefone',     'titulo' => 'Telefone',       'link' => str_replace(array('[col]','[ordem]'), array('telefone',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'telefone') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'telefone' ) ? 'ui-state-highlight'.( ($extras['col'] == 'telefone' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'email',     'titulo' => 'E-mail',       'link' => str_replace(array('[col]','[ordem]'), array('email',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'email') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'email' ) ? 'ui-state-highlight'.( ($extras['col'] == 'email' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'dominio',     'titulo' => 'Dominio',       'link' => str_replace(array('[col]','[ordem]'), array('dominio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'dominio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'dominio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'dominio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_fantasia',     'titulo' => 'Nome fantasia',       'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'status',     'titulo' => 'Situação Atualização',       'link' => str_replace(array('[col]','[ordem]'), array('status',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'status') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'status' ) ? 'ui-state-highlight'.( ($extras['col'] == 'status' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'atualizou',     'titulo' => 'Usuario Atualizou',       'link' => str_replace(array('[col]','[ordem]'), array('atualizou',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'atualizou') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'atualizou' ) ? 'ui-state-highlight'.( ($extras['col'] == 'atualizou' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'conhece_guia',     'titulo' => 'Conhecia',       'link' => str_replace(array('[col]','[ordem]'), array('conhece_guia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'conhece_guia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'conhece_guia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'conhece_guia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),

                            
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id', 	'valor' => '' ) ),
                                        array( 'name' => 'nome_fantasia',   'titulo' => 'nome fantasia: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'status',          'titulo' => 'Status atualiza: ','tipo' => 'select', 'valor' => $this->status_atualizada_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.status_atualizada', 		'valor' => '' ) ),
                                        array( 'name' => 'subcategoria',    'titulo' => 'Categoria: ',      'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id_subcategoria', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
	
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		if ( ! isset( $data['conhece_guia'] ) )
		{
			$data['conhece_guia'] = 0;
		}
                $data['data_atualizada'] = date('Y-m-d H:i');
                $data['usuario_atualizada'] = $this->sessao['id'];
                
               
		return $data;
	}
}
