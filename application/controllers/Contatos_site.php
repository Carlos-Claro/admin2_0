<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de contatos_site
 * @version 1.0
 * @access public
 * @package tarefas
 * @author Luiz Eduardo <programacao01@pow.com.br>
 */
class Contatos_site extends MY_Controller 
{
        /**
         * cria um array validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'data',                'label'   => 'Data', 		'rules'   => 'required'),
                                array( 'field'   => 'id_empresa',          'label'   => 'Empresa', 		'rules'   => 'required'),
                                array( 'field'   => 'nome',                'label'   => 'Nome', 		'rules'   => 'required'),
                                array( 'field'   => 'email',               'label'   => 'E-mail', 		'rules'   => 'required|valid_email'),
                                array( 'field'   => 'assunto',             'label'   => 'Assunto', 		'rules'   => 'required'),
                                array( 'field'   => 'mensagem',            'label'   => 'Mensagem', 		'rules'   => 'required|min_length[3]'),
                                array( 'field'   => 'fone',                'label'   => 'Fone', 		'rules'   => 'required'),
                                array( 'field'   => 'origem',              'label'   => 'Local de Origem', 	'rules'   => 'required'),
                                array( 'field'   => 'cidade',              'label'   => 'Cidade', 		'rules'   => 'required'),
                                array( 'field'   => 'id_item',             'label'   => 'ID Item', 		'rules'   => 'required|numeric'),
                                array( 'field'   => 'id_cidade',           'label'   => 'ID Cidade', 		'rules'   => 'required|numeric'),
                                array( 'field'   => 'sms_enviado',         'label'   => 'SMS Enviado', 		'rules'   => 'required|numeric'),
                                array( 'field'   => 'estado',              'label'   => 'Estado', 		'rules'   => 'required'),
                                array( 'field'   => 'portal',              'label'   => 'Portal', 		'rules'   => 'required'),
                                array( 'field'   => 'id_tipo_item',        'label'   => 'ID Tipo Item', 	'rules'   => 'required|numeric'),
                                array( 'field'   => 'tipo_negocio_item',   'label'   => 'Tipo Negocio Item', 	'rules'   => 'required'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('contatos_site_model','contatos_site_origem_model','empresas_model','subcategorias_model', 'cidades_model'));
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
         * criar a listagem de contatos_site caregando o inicia filtros, itens, total itens, inicia listagem,
         * Definir a URL da pagina,
         * chama o contatos_site_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param int $off_set - pagina que esta vizualizando
         * @version 1.0
         * @access public
         */
        public function listar( $coluna = 'contatos_site.id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->contatos_site_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->contatos_site_model->get_total_itens( $filter );
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
                        ->set_include('js/contatos_site.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/datetimepicker/moment.js', TRUE)
                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                        ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Contatos site', 'contatos_site', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * Exportar um contato site para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->contatos_site_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
        
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com sua validações ou
         * Monta o formulario ou edita as informações com base na $this->valida
         * @param type $codigo com o registro a ser editado
         * @param type $ok verifica se os dados foram salvos
         * @access public
         * @version 1.0
         */
        public function editar($codigo = NULL, $ok = FALSE)
	{
                $this->form_validation->set_rules($this->valida);
                if ($this->form_validation->run())
                {
                        $data = $this->_post();
                        if(isset($codigo))
                        {
                            $id = $this->contatos_site_model->editar($data, 'contatos_site.id = '.$codigo);
                        }
                        else
                        {
                            $id = $this->contatos_site_model->adicionar($data, 'contatos_site.id = '.$codigo);
                        }
                        redirect(strtolower(__CLASS__).'/editar/'.(isset($codigo)?$codigo:$id).'/1');
                }
                else
                {
                        $function = strtolower(__FUNCTION__);
                        $class = strtolower(__CLASS__);
                        $data = $this->_inicia_select(isset($codigo)?$codigo:NULL);//erro
                        $data['action'] = base_url().$class.'/'.$function.'/'.isset($codigo) ? $codigo : '';
                        $data['tipo'] = 'Contatos Site Editar';//$data = $this->_init_selects();
                        $data['item'] = isset($codigo)? $this->contatos_site_model->get_item($codigo) : NULL;
                        $data['mostra_id'] = isset($codigo)? TRUE : NULL;
                        $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                        $this->layout
                                ->set_function( $function )
                                ->set_include('js/contatos_site.js', TRUE)
                                ->set_include('js/datepicker/bootstrap-datepicker.js', TRUE)
                                ->set_include('js/datepicker/locales/bootstrap-datepicker.pt-BR .js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->set_include('css/datepicker.css', TRUE)
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Contatos site', 'contatos_site', 0)
                                ->set_breadscrumbs((isset($codigo)?'Editar':'Adicionar'), 'contatos_site/editar/'.(isset($codigo)?$codigo:''),1)
                                ->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
                                ->view('add_contatos_site',$data);
                }
	}
        
        /**
         * deleta um contato site e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->contatos_site_model->excluir('contatos_site.id in ('.implode(',',$selecionados).')');
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
         * Inicia todos os selecionaveis do view,
         * sendo eles: local de origem e empresas
         * @param string $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = FALSE )
        {
            $this->load->model(array('contatos_site_origem_model'));
            $retorno['local_origem'] = $this->contatos_site_origem_model->get_select();
            if(isset($id) && $id)
            {
                $dica = $this->contatos_site_model->get_item($id);
                $retorno['empresa'] = $this->empresas_model->get_item($dica->id_empresa);
            }
            return $retorno;
        }
        
        /**
         * cria a lista de contatos_site no estilo normal,
         * chama os campos necessarios para criar a cabeçalho e define id como chave
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
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',              'titulo' => 'ID',              'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data',            'titulo' => 'Data', 	        'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'empresas',        'titulo' => 'Empresa',         'link' => str_replace(array('[col]','[ordem]'), array('empresas',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresas') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresas' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresas' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome',            'titulo' => 'Nome',            'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'email',           'titulo' => 'E-mail',          'link' => str_replace(array('[col]','[ordem]'), array('email',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'email') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'email' ) ? 'ui-state-highlight'.( ($extras['col'] == 'email' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'assunto',         'titulo' => 'Assunto',         'link' => str_replace(array('[col]','[ordem]'), array('assunto',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'assunto') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'assunto' ) ? 'ui-state-highlight'.( ($extras['col'] == 'assunto' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_item',         'titulo' => 'ID Item',         'link' => str_replace(array('[col]','[ordem]'), array('id_item',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_item') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_item' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_item' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_tipo_item',    'titulo' => 'ID Tipo Item',    'link' => str_replace(array('[col]','[ordem]'), array('id_tipo_item',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_tipo_item') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_tipo_item' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_tipo_item' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'id_local_origem', 'titulo' => 'Local de Origem', 'link' => str_replace(array('[col]','[ordem]'), array('id_local_origem',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id_local_origem') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id_local_origem' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id_local_origem' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por email, local_origem e nome para a listagem normal de contatos_site
         * cria os botões de exportar e adicionar
         * @param string $url
         * @param array $valores
         * @return array $retorno - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'email',          'titulo' => 'E-mail: ',              'tipo' => 'text',   'valor' => '', 'classe' => 'form-control ', 'where' => array( 'tipo' => 'like', 	'campo' => 'contatos_site.email', 	'valor' => '' ) ),
                                        array( 'name' => 'local_origem',   'titulo' => 'Origem: ',              'tipo' => 'select', 'valor' => $this->contatos_site_origem_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'contatos_site_origem.origem', 	'valor' => '' ) ),
                                        array( 'name' => 'subcategoria',   'titulo' => 'Categorias Empresa: ',  'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id_subcategoria',          'valor' => '' ) ),
                                        array( 'name' => 'nome',           'titulo' => 'Nome: ',                'tipo' => 'text',   'valor' => '', 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'contatos_site.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'cidade',         'titulo' => 'Cidade: ',              'tipo' => 'select', 'valor' => $this->cidades_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'contatos_site.id_cidade', 	'valor' => '' ) ),
                                        array( 'name' => 'data_ini',       'titulo' => 'Data Inicio: ',         'tipo' => 'text',   'valor' => '', 'acao' => 'set_time_to_data_pt_br', 'classe' => 'form-control data_hora_pt_br', 'where' => array( 'tipo' => 'where', 	'campo' => 'contatos_site.data >= ', 	'valor' => '' ) ),
                                        array( 'name' => 'data_fim',       'titulo' => 'Data Fim: ',            'tipo' => 'text',   'valor' => '', 'acao' => 'set_time_to_data_pt_br', 'classe' => 'form-control data_hora_pt_br', 'where' => array( 'tipo' => 'where', 	'campo' => 'contatos_site.data <= ', 	'valor' => '' ) ),
                                        );	
                
                if ( isset($valores['data_ini']) )
                {
                    $valores['data_ini'] = strstr($valores['data_ini'],'/') ? converte_data_unixtime(converte_data_mysql($valores['data_ini'])) : $valores['data_ini'];
                }
                if ( isset($valores['data_fim']) )
                {
                    $valores['data_fim'] = strstr($valores['data_fim'],'/') ? converte_data_unixtime(converte_data_mysql($valores['data_fim'])) : $valores['data_fim'];
                }
                
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        /**
         * Carrega informações de empresas
         * @param string $valor
         * @version 1.0
         * @access public
         */
        public function get_empresa($valor = '')
        {
            $retorno = $this->empresas_model->get_select('empresa_nome_fantasia like "%'.$valor.'%"');
            echo json_encode($retorno);
        }
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['data']= converte_data_unixtime(converte_data_mysql($data['data']));
                unset($data['busca_empresa']);
		return $data;
	}
}


