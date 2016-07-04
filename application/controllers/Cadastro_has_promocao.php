<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de Cadastro_promocao
 * @version 1.0
 * @access public
 * @package cadastro_promocao
 * @author Luiz Eduardo <programacao01@pow.com.br>
 */
class Cadastro_has_promocao extends MY_Controller 
{
        /**
         * cria um array validar a página com os campos necessários
         * @var array valida
         */
	private $valida = array(
                                array( 'field'   => 'id_cadastro',                'label'   => 'ID Cadastro',  	                'rules'   => 'required'),
                                array( 'field'   => 'id_promocao',                'label'   => 'ID Promocao',    	        'rules'   => 'required'),
                                array( 'field'   => 'vencedor',                   'label'   => 'Vencedor',    	                'rules'   => 'trim'),
                                array( 'field'   => 'data_cadastro',              'label'   => 'Data Cadastro',    	        'rules'   => 'trim'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega model padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('cadastro_has_promocao_model','cadastro_promocao_model','cadastros_model'));
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
         * criar a listagem de casdastros_origem caregando o inicia filtros, itens, total itens, inicia listagem,
         * Definir a URL da pagina,
         * chama o cadastros_otigem_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param int $off_set - pagina que esta vizualizando
         * @version 1.0
         * @access public
         */
        public function listar( $coluna = 'cadastro_has_promocao.id', $ordem = 'ASC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.($coluna).'/'.$ordem.'/'.$off_set;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $filter =  $filtro->get_filtro();
            $itens = $this->cadastro_has_promocao_model->get_itens( $filter, $coluna, $ordem, $off_set );
            $total = $this->cadastro_has_promocao_model->get_total_itens( $filter );
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
                        ->set_include('js/cadastro_has_promocao.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_include('js/datetimepicker/moment.js', TRUE)
                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                        ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Cadastro Has Promocao', 'cadastro_has_promocao', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        /**
         * Exportar um cadastro origem para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
		$url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
		$valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
		$filtro = $this->_inicia_filtros( $url, $valores );
		$data = $this->cadastro_has_promocao_model->get_itens( $filtro->get_filtro() );
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
                            $id = $this->cadastro_has_promocao_model->editar($data, 'cadastro_has_promocao.id = '.$codigo);
                        }
                        else
                        {
                            $id = $this->cadastro_has_promocao_model->adicionar($data, 'cadastro_has_promocao.id = '.$codigo);
                        }
                        redirect(strtolower(__CLASS__).'/editar/'.(isset($codigo)?$codigo:$id).'/1');
                }
                else
                {
                        $function = strtolower(__FUNCTION__);
                        $class = strtolower(__CLASS__);
                        $data = $this->_inicia_select(isset($codigo)?$codigo:NULL);//erro
                        $data['action'] = base_url().$class.'/'.$function.'/'.isset($codigo) ? $codigo : '';
                        $data['tipo'] = 'cadastro_has_promocao Editar';//$data = $this->_init_selects();
                        $data['item'] = isset($codigo)? $this->cadastro_has_promocao_model->get_item($codigo) : NULL;
                        $data['mostra_id'] = isset($codigo)? TRUE : NULL;
                        $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                        $this->layout
                                ->set_function( $function )
                                ->set_include('js/cadastro_has_promocao.js', TRUE)
                                ->set_include('js/datepicker/bootstrap-datepicker.js', TRUE)
                                ->set_include('js/datepicker/locales/bootstrap-datepicker.pt-BR .js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->set_include('css/datepicker.css', TRUE)
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Cadastro Has Promocao', 'cadastro_has_promocao', 0)
                                ->set_breadscrumbs((isset($codigo)?'Editar':'Adicionar'), 'cadastro_has_promocao/editar/'.(isset($codigo)?$codigo:''),1)
                                ->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
                                ->view('add_cadastro_has_promocao',$data);
                }
	}
        
        /**
         * deleta um cadastro site e suas conexões
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->cadastro_has_promocao_model->excluir('cadastro_has_promocao.id in ('.implode(',',$selecionados).')');
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
        private function _inicia_select($id = FALSE)
        {
            $this->load->model(array('cadastro_has_promocao_model'));
            $retorno['promocao'] = $this->cadastro_promocao_model->get_select();
            if(isset($id) && $id)
            {
                $dica = $this->cadastro_has_promocao_model->get_item($id);
                $retorno['cadastro'] = $this->cadastros_model->get_item($dica->id_cadastro);
            }
            return $retorno;
            
            
        }
        
        /**
         * cria a lista de cadastros_origem no estilo normal,
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
                                                    (object)array( 'chave' => 'id',                'titulo' => 'ID',               'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome',              'titulo' => 'Nome',             'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'promocao',          'titulo' => 'Promoção',         'link' => str_replace(array('[col]','[ordem]'), array('promocao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'promocao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'promocao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'promocao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'vencedor',          'titulo' => 'Vencedor',         'link' => str_replace(array('[col]','[ordem]'), array('vencedor',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'vencedor') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'vencedor' ) ? 'ui-state-highlight'.( ($extras['col'] == 'vencedor' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data_cadastro',     'titulo' => 'Data Cadastro',    'link' => str_replace(array('[col]','[ordem]'), array('data_cadastro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data_cadastro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data_cadastro' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data_cadastro' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por titulo para a listagem normal de cadastros_origem
         * cria os botões de exportar, adicionar e deletar selecionados
         * @param string $url
         * @param array $valores
         * @return array $retorno - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'nome',         'titulo' => 'Nome: ',         'tipo' => 'text', 'valor' => $this->cadastros_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'like', 	'campo' => 'cadastros.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'promocao',     'titulo' => 'Promoção: ',     'tipo' => 'select', 'valor' => $this->cadastro_promocao_model->get_select(), 'classe' => 'form-control', 'where' => array( 'tipo' => 'where', 	'campo' => 'cadastro_promocao.id', 	'valor' => '' ) ),
                                        );	
                
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		$config['botoes'] = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/editar'.'" class="btn btn-primary">Add Novo</a>';
 		$config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a>';
 		
                $filtro = $this->filtro->inicia($config);
 		return $filtro;
	}
        
        public function get_cadastro($valor = '')
        {
            $retorno = $this->cadastros_model->get_select('nome like "%'.$valor.'%"', 'nome', 'ASC', 20);
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
                $data['data_cadastro'] = converte_data_mysql($data['data_cadastro']);
                //var_dump($data);
                unset($data['busca_cadastro']);
                //die();
		return $data;
	}
}