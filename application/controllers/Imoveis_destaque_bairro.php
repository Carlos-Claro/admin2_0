<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de categorias
 * @version 1.0
 * @access public
 * @package categorias
 */
class Imoveis_destaque_bairro extends MY_Controller 
{       
        /**
         * Cria um array para validar   a pagina com os campos necessários do formulário
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'area',         'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'id_guia',      'label'   => 'Guia', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_setor',     'label'   => 'Setor', 		'rules'   => 'trim'),
                                array( 'field'   => 'quantia',      'label'   => 'Views', 		'rules'   => 'trim'),
                                array( 'field'   => 'posicao',      'label'   => 'Posição', 		'rules'   => 'trim'),
                                array( 'field'   => 'largura',      'label'   => 'Largura',             'rules'   => 'trim'),
                                array( 'field'   => 'altura',       'label'   => 'Altura',              'rules'   => 'trim'),
                                array( 'field'   => 'peso',         'label'   => 'Peso',                'rules'   => 'trim'),
                                array( 'field'   => 'id_area',      'label'   => 'Area',                'rules'   => 'trim'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model( array('imoveis_destaque_bairro_model', 'imoveis_tipos_model', 'empresas_model', 'cidades_model','bairros_model') );
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
         * cria a listagem de categorias carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o categorias_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($coluna = 'data_fim', $ordem = 'ASC', $off_set = 0, $resposta = 'inc')
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->imoveis_destaque_bairro_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->imoveis_destaque_bairro_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $extras['total_itens'] = $total; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/imoveis_destaque_bairro.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Imoveis destaque listagem - Listar', 'Imoveis_destaque_bairro_Model', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
        /**
         * exportar uma lista categorias para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->imoveis_destaque_bairro_model->get_itens( $filtro->get_filtro() );
		exporta_excel($data, __CLASS__.date('YmdHi'));
	}
	
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com sua validações
         * @version 1.0
         * @access public
         * @return void  - redireciona ou monta o formulario
         */
	public function adicionar( $id_empresa )
	{
            $function = strtolower(__FUNCTION__);
            $class = strtolower(__CLASS__);
            $data = $this->_inicia_select();
            $data['empresa'] = $this->empresas_model->get_item($id_empresa);
            $data['action'] = base_url().$class.'/'.$function;
            $data['tipo'] = 'Imoveis destaque listagem Adicionar';	
            $this->layout
                    ->set_function( $function )
                    ->set_include('js/imoveis_destaque_bairro_edicao.js', TRUE)
                    ->set_include('css/estilo.css', TRUE)  
                    ->set_breadscrumbs('Painel', 'painel',0)
                    ->set_breadscrumbs('imoveis bairro listagem', 'imoveis_destaque_bairro', 0)
                    ->set_breadscrumbs('Adicionar', 'imoveis_destaque_bairro/adicionar', 1)
                    ->set_usuario($this->set_usuario())
                    ->set_menu($this->get_menu($class, $function))
                    ->view('add_Imoveis_destaque_bairro',$data);
	}
        
        /**
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->imoveis_destaque_bairro_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            $id = $this->Imoveis_destaque_bairro_Model->editar($data, array('Imoveis_destaque_bairro.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
                                $data = $this->_inicia_select($dados);
				$data['tipo'] = 'Imoveis bairro listagem Editar';//$data = $this->_init_selects();
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
                                        ->set_include('js/imoveis_destaque_bairro_edicao.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
					->set_usuario($this->set_usuario())
                                        ->set_breadscrumbs('Imoveis Bairro listagem', 'Imoveis_destaque_bairro', 0)
                                        ->set_breadscrumbs('editar', '', 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_Imoveis_destaque_bairro',$data);
			}
		}
		else 
		{
			redirect('Imoveis_destaque_bairro/listar');
		}
	}
        
        /**
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function estatisticas($codigo = NULL, $ok = FALSE)
	{
		die('em construção.');
	}
        
        /**
         * Deleta uma categoria e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->imoveis_destaque_bairro_model->excluir('imoveis_destaque_bairro.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
        private function _inicia_select( $dados = NULL ) 
        {
            $retorno = array();
            $filtro = array();
            if(isset($dados))
            {
                $filtro = 'bairros.cidade = '.$dados->id_cidade;
            }
            $retorno['negocio'][] = (object)array('id' => 1, 'descricao' => 'Venda');
            $retorno['negocio'][] = (object)array('id' => 2, 'descricao' => 'Locação');
            $retorno['cidades'] = $this->cidades_model->get_select();
            $retorno['bairros'] = $this->bairros_model->get_select($filtro,'bairros.nome','ASC',FALSE);
            $retorno['imoveis_tipos'] = $this->imoveis_tipos_model->get_select();
            //$retorno['empresas'] = $this->empresas_model->get_select();
            return $retorno;
        }
        
        /**
         * Cria uma lista de categorias no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
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
                                                    (object)array( 'chave' => 'tipo',   'titulo' => 'Tipo', 	'link' => str_replace(array('[col]','[ordem]'), array('tipo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'tipo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'tipo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'tipo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'empresa','titulo' => 'Empresa', 	'link' => str_replace(array('[col]','[ordem]'), array('empresa',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa' ) ? 'ui-state-highlight'.( ($extras['col'] == 'empresa' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'cidade','titulo' => 'Cidade','link' => str_replace(array('[col]','[ordem]'), array('cidade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cidade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cidade' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cidade' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'bairro','titulo' => 'Bairro','link' => str_replace(array('[col]','[ordem]'), array('bairro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'Bairro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'bairro' ) ? 'ui-state-highlight'.( ($extras['col'] == 'bairro' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_inicio','titulo' => 'Inicio','link' => str_replace(array('[col]','[ordem]'), array('imoveis_destaque_bairro.data_inicio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'imoveis_destaque_bairro.data_inicio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'imoveis_destaque_bairro.data_inicio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'imoveis_destaque_bairro.data_inicio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_fim','titulo' => 'Fim','link' => str_replace(array('[col]','[ordem]'), array('imoveis_destaque_bairro.data_fim',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'imoveis_destaque_bairro.data_fim') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'imoveis_destaque_bairro.data_fim' ) ? 'ui-state-highlight'.( ($extras['col'] == 'imoveis_destaque_bairro.data_fim' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'negocio_','titulo' => 'Negocio','link' => str_replace(array('[col]','[ordem]'), array('negocio_',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'negocio_') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'negocio_' ) ? 'ui-state-highlight'.( ($extras['col'] == 'negocio_' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'qtde_ativas','titulo' => 'Ativas','link' => str_replace(array('[col]','[ordem]'), array('qtde_ativas',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_ativas') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_ativas' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_ativas' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por id, titulo, descricao e menu_ativo
         * cria botões de adicionar e exportar
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
            if(!empty($valores['data_inicio']))
            {
                $valores['data_inicio'] = converte_data_mysql($valores['data_inicio'].' 00:00');
            }
            if(!empty($valores['data_fim']))
            {
                $valores['data_fim'] = converte_data_mysql($valores['data_fim'].' 23:59');                
            }
                $config['itens'] = array(
                                        array( 'name' => 'id',             'titulo' => 'ID: ',           'tipo' => 'text',              'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.id', 	'valor' => '' ) ),
                                        array( 'name' => 'id_tipo',        'titulo' => 'Tipo: ',         'tipo' => 'select',            'valor' => $this->imoveis_tipos_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.id_tipo', 	'valor' => '' ) ),
                                        array( 'name' => 'id_cidade',      'titulo' => 'Cidade: ',       'tipo' => 'select',            'valor' => $this->cidades_model->get_select(), 'classe' => 'cidades ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.id_cidade', 		'valor' => '' ) ),
                                        array( 'name' => 'id_bairro',      'titulo' => 'Bairro: ',       'tipo' => 'select',            'valor' => $this->bairros_model->get_select(), 'classe' => 'bairros ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.id_bairro', 		'valor' => '' ) ),
                                        array( 'name' => 'id_empresa',     'titulo' => 'Empresa: ',      'tipo' => 'select',            'valor' => $this->empresas_model->get_select(), 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.id_empre sa', 		'valor' => '' ) ),
                                        array( 'name' => 'data_inicio',    'titulo' => 'Data Inicio: ',  'acao' => 'reverte_data_mysql','tipo' => 'text',   'valor' => '', 'classe' => 'ui-state-default form-control data', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.data_inicio>', 		'valor' => ''), 'extra' => 'placeholder="00/00/0000"' ),
                                        array( 'name' => 'data_fim',       'titulo' => 'Data Fim: ',     'acao' => 'reverte_data_mysql','tipo' => 'text',   'valor' => '', 'classe' => 'ui-state-default form-control data', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.data_fim<', 		'valor' => '' ),'extra' => 'placeholder="00/00/0000"' ),
                                        array( 'name' => 'negocio',        'titulo' => 'Negocio: ',      'tipo' => 'select',            'valor' => (object)array((object)array('id' => '1', 'descricao' => 'Venda'),(object)array('id' => '2', 'descricao' => 'Locação')), 'classe' => 'ui-state-default form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_destaque_bairro.negocio', 		'valor' => '' ))
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		//$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		$config['botoes']  = ' <button type="button" class="btn btn-warning">Para adicionar novas campanhas utilize o cadastro da empresa</button>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        /**
         * 
         * @param array $dados
         */
        private function _valida_data($dados) {
            /**
            * @param $dma = Data Mes e ano retornados do banco de dados
            */
            $array_sinal = array( 'data_fim' => '<' , 'data_inicio' => '>' );
            $dados_campo = array( 'data_inicio' => 'data_fim', 'data_fim' => 'data_inicio' );
            $filtro = 'imoveis_destaque_bairro.id = "'.$dados['id'].'" AND imoveis_destaque_bairro.'.$dados_campo[ $dados['campo'] ].$array_sinal[ $dados['campo'] ].' "'.$dados['valor'].'"';
            $data_item = $this->imoveis_destaque_bairro_model->get_total_itens( $filtro );
            return (($data_item) ? $data_item : FALSE);
        }
        
        public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post(FALSE);
            $data['id_empresa'] = $dados['id_empresa'];
            
            $retorno['status'] = TRUE;
            
            if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
            {
                /**
                 * Verifica se o campo vindo pelo post é data_fim
                 */
                if(isset($dados['campo']) && $dados['campo']==='data_fim')
                {   
                    if( ! empty($dados['valor']) )
                    {
                        $data_item = $this->_valida_data($dados);
                        /**
                         * Se $data_item vier falso 
                         */
                        if( ! $data_item )
                        {
                            $retorno['status'] = FALSE;
                            $retorno['mensagem'] = 'A data de fim menor que a data de inicio';
                        }
                        else
                        {
                            $retorno['status'] = TRUE;
                        }
                    }
                }
                if ( is_array($dados['campo']) )
                {
                    $filtro = 'imoveis_destaque_bairro.id = '.$dados['id'];
                    for( $a = 0; $a < count($dados['campo']); $a++ )
                    {
                        $data[$dados['campo'][$a]] = $dados['valor'][$a];
                    }
                }
                else
                {
                    $filtro = 'imoveis_destaque_bairro.id = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                }
                /**
                 * Retorno é instanciado como True no começo da função
                 * Se o campo for data_inicio ele faz uma verificação antes
                 * pra ver se a data inicio é maior que a fim
                 * se for menor o retorno é false.
                 * (o mesmo se aplica para o data_fim)
                 */
                if($retorno['status'])
                {
                    $afetados = $this->imoveis_destaque_bairro_model->editar($data, $filtro);
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
            }
            else
            {
                $data[$dados['campo']] = $dados['valor'];
                $retorno['id'] = $this->imoveis_destaque_bairro_model->adicionar($data);
                if ( isset($retorno['id']) && $retorno['id'] )
                {
                    $retorno['status'] = TRUE;
                    $retorno['muda_url'] = base_url().'imoveis_destaque_bairro/editar/'.$retorno['id'].'/';
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
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post( $edita = FALSE )
	{
		$data = $this->input->post(NULL, TRUE);
                if ( ! $edita )
                {
                    if ( isset($data['campo']) && $data['campo'] == 'data_inicio') 
                    {
                        empty($data['valor']) ? '' :$data['valor'] = converte_data_mysql($data['valor']);
                    }
                    if ( isset($data['campo']) && $data['campo'] == 'data_fim' ) 
                    {
                        empty($data['valor']) ? '' : $data['valor'] = converte_data_mysql($data['valor']);
                    }
                }
		return $data;
	}
}


