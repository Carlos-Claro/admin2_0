<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de notícias para clientes POW, simularem ambiente.
 * @version 1.0
 * @access public
 * @package noticias
 */
class Noticias_aplicativo extends MY_Controller 
{
        /**
         * cria um array de 17 posições para validar a pagina com todos os campos do formulario
         * @var array 
         */
	private $valida = array(
                                array( 'field'   => 'titulo',               'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'texto',                'label'   => 'Texto', 		'rules'   => 'trim'),
                                array( 'field'   => 'data',                 'label'   => 'Data', 		'rules'   => 'required'),
                                array( 'field'   => 'vitrine',              'label'   => 'Vitrine', 		'rules'   => 'trim'),
                                );
        
        /**
         * cria um array de uma posição para definir o tamanho da imagem
         * @var array
         */
        private $tamanhos = array(
                                array('tipo' => 33, 'width' => '600', 'height' => 'auto', 'pasta' => 'powsites/[codEmpresa]/noticias/[ano]/[mes]/', 'prefixo' => '', 'salva' => TRUE),
                                );
        
        private $id_empresa = NULL;
        private $empresa = NULL;
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e librarys padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            $valida = FALSE;
            parent::__construct($valida);
            $this->load->model(array('noticias_model','categorias_model','cidades_model','canais_noticias_model','editorias_model','images_model','canais_model','noticias_tipo_area_model'));
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
         * Inicia todos os selecionaveis do view,
         * sendo eles: select do categorias_model, editorias_model, cidades_model,
         * canais_noticias_model, select_2014 do canais_model, select_tipo_image
         * do images_model e select do noticias_tipo_area_model
         * @param bool $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['categoria'] = $this->categorias_model->get_select();
            $retorno['editoria'] = $this->editorias_model->get_select();
            $retorno['cidade'] = $this->cidades_model->get_select();
            $retorno['canal'] = $this->canais_noticias_model->get_select();
            $retorno['canal_2014'] = $this->canais_model->get_select_2014();
            $retorno['imagens'] = $this->images_model->get_select_tipo_image(strtolower(__CLASS__));
            $retorno['tipo_area'] = $this->noticias_tipo_area_model->get_select();
            
            return $retorno;
        }
        
        /**
         * Cria uma lista de notcícias no estilo listagem normal,
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
                                                    (object)array( 'chave' => 'data', 'titulo' => 'Data ', 	'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'categoria', 'titulo' => 'Categoria', 	'link' => str_replace(array('[col]','[ordem]'), array('categoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'categoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'categoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'categoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'vitrine',  'titulo' => 'Home',    'link' => str_replace(array('[col]','[ordem]'), array('vitrine',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'vitrine') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'vitrine' ) ? 'ui-state-highlight'.( ($extras['col'] == 'vitrine' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
         * Cria um filtro por id, editora, data_inicio, data_fim, titulo,
         * vitrine, vitrine_canal, tipo_area e canais,
         * cria botões de adicionar, exportar, deletar selecionados, 
         * marcar vitrine selecionados, desmarcar vitrine selecionados,
         * marcar vitrine canal selecionados, desmarcas vitrine canal selecionados
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
            //var_dump($valores);
            $this->load->model(array('noticias_tipo_area_model','canais_model'));
            $config['itens'] = array(
                                    array( 'name' => 'id_empresa',      'titulo' => ' ',                                     'tipo' => 'hidden',       'valor' => '',                                              'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id_empresa',               'valor' => '' ) ),
                                    array( 'name' => 'id',              'titulo' => 'ID: ',                                 'tipo' => 'text',       'valor' => '',                                              'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id',               'valor' => '' ) ),
                                    array( 'name' => 'data_inicio',     'titulo' => 'Data Inicio (yyyy-mm-dd hh:mm): ',     'tipo' => 'text',       'valor' => '',                                              'classe' => 'data_hora data-inicio form-control ui-state-default',      'where' => NULL ),
                                    array( 'name' => 'data_inicio_hide','titulo' => '',                                     'tipo' => 'hidden',     'valor' => '',                                              'classe' => 'data-inicio-unix form-control ui-state-default',           'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.data >',           'valor' => '' ) ),
                                    array( 'name' => 'data_fim',        'titulo' => 'Data Fim (yyyy-mm-dd hh:mm): ',        'tipo' => 'text',       'valor' => '',                                              'classe' => 'data_hora data-fim form-control ui-state-default',         'where' => NULL ),
                                    array( 'name' => 'data_fim_hide',   'titulo' => '',                                     'tipo' => 'hidden',     'valor' => '',                                              'classe' => 'data-fim-unix form-control ui-state-default',              'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.data <',           'valor' => '' ) ),
                                    array( 'name' => 'titulo',          'titulo' => 'Titulo: ',                             'tipo' => 'text',       'valor' => '',                                              'classe' => 'form-control  ui-state-default',                           'where' => array( 'tipo' => 'like', 	'campo' => 'noticias.titulo',           'valor' => '' ) ),                                        
                                    );	
            $config['colunas'] = 4;
            $config['extras'] = '';
            $config['url'] = $url;
            $config['valores'] = $valores;
            $config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary">Add Novo</a>';
            $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
            //$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
            $config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a><br><br>';

            $filtro = $this->filtro->inicia($config);
            return $filtro;
		
	}
        
        /**
         * Retorna a data em formato unix 
         * @version 1.0
         * @access public
         */
        public function get_timeunix()
        {
            $data = $this->input->post();
            $a = explode(' ', $data['valor']);
            $dia = explode('-', $a[0] );
            $hora = explode(':', ( isset( $a[1] ) ? $a[1] : '00:00' ) );
            $retorno = mktime($hora[0], $hora[1], '00', $dia[1], $dia[2], $dia[0] );
            echo $retorno;
        }
	
        public function get($coluna = 'data_unix', $ordem = 'DESC', $off_set = 0)
        {
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( '', $valores );
            $itens = $this->noticias_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set, 5);
            echo json_encode($itens['itens']);
        }
}

	