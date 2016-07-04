<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresas_Ocorrencias extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );
        
        private $valida_cad = array(
                                array( 'field'   => 'id',                       'label'   => 'ID',              'rules'   => 'trim'),
                                array( 'field'   => 'empresa_razao_social',     'label'   => 'Razão Social',    'rules'   => 'required'),
                                array( 'field'   => 'empresa_nome_fantasia',    'label'   => 'Nome Fantasia',   'rules'   => 'required'),
                                array( 'field'   => 'empresa_cnpj',             'label'   => 'CNPJ',            'rules'   => 'trim'),
                                array( 'field'   => 'empresa_telefone',         'label'   => 'Telefone empresa','rules'   => 'trim'),
                                array( 'field'   => 'empresa_numero',           'label'   => 'Numero',          'rules'   => 'trim'),
                                array( 'field'   => 'empresa_complemento',      'label'   => 'Complemento',     'rules'   => 'trim'),
                                array( 'field'   => 'cep',                      'label'   => 'CEP',             'rules'   => 'trim'),
                                array( 'field'   => 'endereco',                 'label'   => 'Endereço',        'rules'   => 'trim'),
                                array( 'field'   => 'bairro',                   'label'   => 'Bairro',          'rules'   => 'trim'),
                                array( 'field'   => 'cidade',                   'label'   => 'Cidade',          'rules'   => 'trim'),
                                array( 'field'   => 'empresa_descricao',        'label'   => 'Descrição empresa','rules'  => 'trim'),
                                array( 'field'   => 'empresa_email',            'label'   => 'Email',           'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'empresa_dominio',          'label'   => 'Dominio',         'rules'   => 'trim'),
                                array( 'field'   => 'contato_nome',             'label'   => 'Nome contato',    'rules'   => 'trim'),
                                array( 'field'   => 'contato_email',            'label'   => 'Email contato',   'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'contato_ddd',              'label'   => 'DDD contato',     'rules'   => 'trim'),
                                array( 'field'   => 'contato_telefone',         'label'   => 'Telefone contato','rules'   => 'trim'),
                                array( 'field'   => 'id_subcategoria',          'label'   => 'Categoria Serviço','rules'  => 'trim'),
                                array( 'field'   => 'status_atualizada',        'label'   => 'Status',          'rules'   => 'required'),
                                );
        

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array(
                'empresas_model','subcategorias_model', 'status_atualizada_model',
                'cidades_model','status_ocorrencia','empresas_contato_model',
                'ocorrencias_model','interacao_model','pow_campanhas','empresas_pow_campanhas'));
            $this->load->library(array('ocorrencias','modal_ocorrencias'));
	}
	
        public function get_cep ( $valor )
        {
            $this->load->model('logradouros_model');
            $filtro = 'logradouros.cepi = "'.$valor.'" AND logradouros.id_cidade= 1';
            $retorno = $this->logradouros_model->get_itens($filtro);
            $return = (count($retorno['itens']) > 0 ) ? $retorno['itens'] : FALSE;
            echo json_encode($return);
        }
        
        public function get_endereco ( $valor )
        {
            $this->load->model('logradouros_model');
            $valor = str_replace('_', '%', $valor);
            $valor = urldecode($valor);
            $filtro = 'logradouros.logradouro LIKE "%'.$valor.'%"  AND logradouros.id_cidade= 1';
            $retorno = $this->logradouros_model->get_itens($filtro);
            $return = (count($retorno['itens']) > 0 ) ? $retorno['itens'] : FALSE;
            echo json_encode($return);
        }
        
        public function cadastro_guia ( $id = FALSE, $ok = FALSE )
        {
            $this->form_validation->set_rules($this->valida_cad); 
            if  ( $this->form_validation->run() )
            {
                $data = $this->_post();
                unset($data['cidade']);
                
                $filtro = 'empresas.id = '.$data['id'];
                $id = $data['id'];
                unset($data['id']);
                $altera = $this->empresas_model->editar($data, $filtro);
                redirect( strtolower(__CLASS__) .'/'.  strtolower(__FUNCTION__) . '/' . ( ($altera > 0 ) ? $id.'/1' : $id.'/2' ).'/' );
            }
            else
            {
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $id_ = $id;
                $id = ( $ok && $ok == 2 ) ? $id : FALSE;
                $data = $this->_inicia_cadastro( $id );
                $data['action'] = base_url().$class.'/'.$function;
                $data['tipo'] = 'Empresas Atualiza Cadastro';	
                if ( $ok && $ok == 1 )
                {
                    $filtro_i = ( $id_ ) ? 'empresas.id = '.$id_ : NULL;
                    $item = $this->empresas_model->get_item_cadastro($filtro_i);
                    $data['erro'] = array('class' => 'alert alert-success', 'texto' => 'Registro '.$item->empresa_nome_fantasia.', Salvo com sucesso');
                }
                elseif( $ok && $ok == 2 )
                {
                    $data['erro'] = array('class' => 'alert alert-danger', 'texto' => 'Problemas com o salvamento do arquivo, favor tente novamente.');
                }
                
                $data['desabilitar'] = 'disabled="disabled"';
                
                $this->layout
                        ->set_function( $function )
                        ->set_include('js/cad_empresas.js', TRUE)
                        ->set_include('js/ckeditor/ckeditor.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)  
                        //->set_breadscrumbs('Painel', 'painel',0)
                        //->set_breadscrumbs('Atualiza Cadastro', 'empresas/cadastro_guia', 1)
                        ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, $function))
                        ->view('cad_empresas',$data);
            }
        }
        
        private function _inicia_cadastro( $id = NULL )
        {
            $filtro = ( $id ) ? 'empresas.id = '.$id : NULL;
            $data['item'] = $this->empresas_model->get_item_cadastro($filtro);
            $data['subcategorias'] = $this->subcategorias_model->get_select();
            $data['status_atualizada'] = $this->status_atualizada_model->get_select();
            $data['status_ocorrencia'] = $this->status_ocorrencia->get_select();
            $data['cidades'] = $this->cidades_model->get_select();
            //$data['contato_empresa'] = $this->empresas_contato_model->get_select();
            //$data['cargos'] = $this->empresas_model->get_cargos();
            return $data;
            
        }
        
        
	public function index()
	{
            redirect('painel');
	}
	
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
            $data['modal'] = $this->get_modal(NULL);
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
                        ->view('listar_agendar',$data);	
	}
	
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
		$this->form_validation->set_rules($this->valida_cad); 
		if  ( $this->form_validation->run() )
		{
                        $data = $this->_post();
                        unset($data['cidade']);
                        unset($data['cidades']);
                        unset($data['id']);

			$id = $this->empresas_model->adicionar($data);
			redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
                        //redirect( strtolower(__CLASS__) .'/'.  strtolower(__FUNCTION__) . '/' . ( ($id > 0 ) ? $id.'/1' : $id.'/2' ).'/' );
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
                        $data = $this->_inicia_cadastro(FALSE);
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'Empresas Adicionar';
                        $data['desabilitar'] = 'disabled="disabled"';
			$this->layout
				->set_function( $function )
				->set_include('js/cad_empresas.js', TRUE)
                                ->set_include('js/ckeditor/ckeditor.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Empresas', 'setor', 0)
                                ->set_breadscrumbs('Adicionar', 'empresas/adicionar', 1)
				->set_usuario($this->set_usuario())
				->view('cad_empresas',$data);
		}   
		 
	}
	
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->empresas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida_cad);
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
                                $data = $this->_inicia_cadastro($codigo);
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                                $data['agendamento'] = TRUE;
                                if($data['agendamento']){
                                    $data['modal'] = $this->get_modal(TRUE, NULL, $codigo);
                                    //$data['ocorrencias'] = $this->get_ocorrencia($codigo, $data['status_ocorrencia']);
                                }
                                $this->layout
					->set_function( $function )
					->set_include('js/cad_empresas.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Empresas', 'empresas', 0)
                                        ->set_breadscrumbs('Editar', 'empresas_ocorrencias/editar/'.$codigo, 1)
					->set_usuario($this->set_usuario())
					->view('cad_empresas',$data);
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
                    if($exportar == 'agendados'){
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',             'titulo' => 'ID',               'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'ocorrencia',     'titulo' => 'Ocorrência',       'link' => str_replace(array('[col]','[ordem]'), array('ocorrencia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'ocorrencia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'ocorrencia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'ocorrencia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'status',         'titulo' => 'Status',           'link' => str_replace(array('[col]','[ordem]'), array('status',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'status') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'status' ) ? 'ui-state-highlight'.( ($extras['col'] == 'status' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'inicio',         'titulo' => 'Data Início',      'link' => str_replace(array('[col]','[ordem]'), array('inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'retorno',        'titulo' => 'Data Retorno',     'link' => str_replace(array('[col]','[ordem]'), array('retorno',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'retorno') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'retorno' ) ? 'ui-state-highlight'.( ($extras['col'] == 'retorno' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'obs',            'titulo' => 'Observação',       'link' => str_replace(array('[col]','[ordem]'), array('obs',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'obs') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'obs' ) ? 'ui-state-highlight'.( ($extras['col'] == 'obs' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'usuario',        'titulo' => 'Usuário',          'link' => str_replace(array('[col]','[ordem]'), array('usuario',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'usuario') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'usuario' ) ? 'ui-state-highlight'.( ($extras['col'] == 'usuario' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();                        
                    }
                    else
                    {
                        $cabecalho = ' ';
                    }
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
//                                                    (object)array( 'chave' => 'inscricao',     'titulo' => 'Inscrição',       'link' => str_replace(array('[col]','[ordem]'), array('inscricao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'inscricao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'inscricao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'inscricao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'endereco',     'titulo' => 'Endereço',       'link' => str_replace(array('[col]','[ordem]'), array('endereco',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'endereco') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'endereco' ) ? 'ui-state-highlight'.( ($extras['col'] == 'endereco' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'logradouro',     'titulo' => 'Logradouro Valido',       'link' => str_replace(array('[col]','[ordem]'), array('logradouro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'logradouro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'logradouro' ) ? 'ui-state-highlight'.( ($extras['col'] == 'logradouro' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'telefone',     'titulo' => 'Telefone',       'link' => str_replace(array('[col]','[ordem]'), array('telefone',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'telefone') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'telefone' ) ? 'ui-state-highlight'.( ($extras['col'] == 'telefone' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'email',     'titulo' => 'E-mail',       'link' => str_replace(array('[col]','[ordem]'), array('email',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'email') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'email' ) ? 'ui-state-highlight'.( ($extras['col'] == 'email' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'dominio',     'titulo' => 'Dominio',       'link' => str_replace(array('[col]','[ordem]'), array('dominio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'dominio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'dominio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'dominio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_fantasia',     'titulo' => 'Nome fantasia',       'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'status',     'titulo' => 'Situação Atualização',       'link' => str_replace(array('[col]','[ordem]'), array('status',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'status') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'status' ) ? 'ui-state-highlight'.( ($extras['col'] == 'status' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'atualizou',     'titulo' => 'Usuario Atualizou',       'link' => str_replace(array('[col]','[ordem]'), array('atualizou',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'atualizou') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'atualizou' ) ? 'ui-state-highlight'.( ($extras['col'] == 'atualizou' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
//                                                    (object)array( 'chave' => 'conhece_guia',     'titulo' => 'Conhecia',       'link' => str_replace(array('[col]','[ordem]'), array('conhece_guia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'conhece_guia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'conhece_guia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'conhece_guia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" target="_blank">Add Novo</a>';
 		$config['botoes'] .= ' <a class="btn  btn-info editar">Editar Selecionados</a>';
 		$config['botoes'] .= ' <a class="btn btn-warning agendarModal" data-toggle="modal" data-target="#agendarModal">Agendar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        public function add_agendamento(){
            $data = $this->_post();
            $empresas = explode(',' ,$data['id_empresa']);
            var_dump($empresas);
        }
        
        public function add_campanha()
        {
            $data = $this->_post();
            $dados_campanha = array(
                    'titulo' => $data['titulo'],
                    'descricao' => $data['descricao'],
                    'data_inicio' => $data['data_inicio'],
                    'data_termino' => $data['data_termino'],
                    'estimativa_conversao' => $data['estimativa_conversao'],
                
            );
            $id = $this->pow_campanhas->adicionar($dados_campanha);
            redirect(strtolower(__CLASS__).'/listar/');
        }
        
        public function add_ocorrencia()
        {
            $data = $this->_post();
            $dados_ocorrencia = array(
                'id_empresa' => $data['id_empresa'],
                'data' => $data['data_atualizada'],
                'id_usuario' => $data['usuario_atualizada'],
                'id_contato' => $data['id_contato'],
                'id_empresa_ocorrencia_status' => $data['id_empresa_ocorrencia_status'],
                'assunto' =>  $data['assunto'],
                'texto' => $data['texto'],
                'id_setor' => $data['id_setor']
            );
            $codigo = $this->ocorrencias_model->adicionar($dados_ocorrencia);
            $dados_interacao = array(
                'id_empresa_ocorrencia' => $codigo,
                'id_empresa_status_ocorrencia' => $data['id_empresa_ocorrencia_status'],
                'data_inclusao' => $data['data_atualizada'],
                'data_retorno' => (isset($data['data_retorno']) && $data['data_retorno'] != '') ? $data['data_retorno'] : '' ,
                'obs' => $data['texto'],
                'id_usuario_retorno' =>  $data['usuario_atualizada'],
                'id_contato' => $data['id_contato_hidden'],
             );
             $resposta = $this->interacao_model->adicionar($dados_interacao);
             redirect(strtolower(__CLASS__).'/editar/'.$dados_ocorrencia['id_empresa']);
        }
        
        public function get_ocorrencia($filtro = NULL, $status = NULL)
        {
            $id = $this->input->post('id_ocorrencia');
            $id_empresa = $this->input->post('id_empresa');

            if($id != NULL){
                $config['itens'] = $this->ocorrencias_model->get_item($id);
               // $config['contato'] = $this->empresas_contato_model->get_select();
                $config['status'] = $this->status_ocorrencia->get_select();
                
                $this->ocorrencias->inicia($config);
                echo $this->ocorrencias->get_item_html(); 
                return NULL; 
            }
           
            $config['itens'] = $this->ocorrencias_model->get_itens($filtro);
            //$config['contato'] = $this->empresas_contato_model->get_select($filtro);
            $config['status'] = $status;
            
            $this->ocorrencias->inicia($config);
            
            return $this->ocorrencias->get_itens_html('light'); 
        }
        
        public function add_interacao()
        {
            $data = $this->_post();
            $dados_interacao = array(
                'id_empresa_ocorrencia' => $data['id_empresa'],
                'id_empresa_status_ocorrencia' => $data['id_empresa_ocorrencia_status'],
                'data_inclusao' => $data['data_atualizada'],
                'data_retorno' => (isset($data['data_retorno']) && $data['data_retorno'] != '') ? $data['data_retorno'] : '' ,
                'obs' => $data['texto'],
                'id_usuario_retorno' =>  $data['usuario_atualizada'],
                'id_contato' => $data['id_contato_hidden'],
             );
            $resposta = $this->interacao_model->adicionar($dados_interacao);
            redirect(strtolower(__CLASS__).'/editar/'.$data['id_empresa_original']);
        }
        
        public function add_contato_empresa()
        {
            $data = array(
                'id_empresa' => $this->input->post('id_empresa'),
                'nome' => $this->input->post('nome'),
                'telefone' => $this->input->post('telefone'),
                'email' => $this->input->post('email'),
                'status' => '1',
                'funcao' => $this->input->post('funcao'),
                'obs' => $this->input->post('obs'),
            );
            $codigo = $this->empresas_model->add_contato($data);
            echo $codigo;
        }
        
        public function get_formulario_interacao($filtro = NULL)
        {
            $id_empresa = $this->input->post('id_empresa');
            //$config['contato'] = $this->empresas_contato_model->get_select($id_empresa);
            $config['status'] = $this->status_ocorrencia->get_select();
            $this->ocorrencias->inicia($config);
            $data['visivel'] = 'block';
            $data['contato'] = 'none';
            echo $this->ocorrencias->get_formulario($id_empresa, $data); 
        }
        
        public function listar_agendados($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->empresas_model->get_agendados( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->empresas_model->get_total_itens_agendamento( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras, 'agendados' );
            $data['modal'] = $this->get_modal();
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
                        ->view('listar_agendar',$data);	
	}
        
        public function get_modal($interacao = FALSE, $dados = NULL, $id_empresa = NULL)
        {
            if($interacao)
            {
                $contato_empresa = $this->empresas_model->get_contato($id_empresa);
                $itens = array(
                    'titulo' => 'Contato',
                    'contato' => $contato_empresa,
                    'rodape' => 'contato',
                    'id_empresa' => $id_empresa,
                );
            }
            else
            {
                $cargos = $this->empresas_model->get_cargos($id_empresa);
                $campanhas = $this->pow_campanhas->get_select();
                $itens = array(
                    'titulo' => 'Agendamento',
                    'cargos' => $cargos,
                    'campanhas' => $campanhas,
                    'rodape' => 'agendamento',
                    'id_empresa' => $id_empresa,
                ); 
            }
            $retorno  = $this->modal_ocorrencias->monta_html($itens);
            return $retorno;
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
