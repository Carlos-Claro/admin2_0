<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de empresas
 * @version 1.0
 * @access public
 * @package canais
 */
class Empresas extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario 
         * @var array
         */
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );
        
        /**
         * Cria um array para validar o cadastro de empresas com os campos necessarios do formulario
         * @var array
         */
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
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e librarys padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array(
                'empresas_model', 'empresas_auxiliar_model','subcategorias_model', 'status_atualizada_model','logradouros_model',
                'cidades_model','empresas_status_ocorrencia_model','empresas_contato_model','setores_model',
                'ocorrencias_model','interacao_model','pow_cargos_model','sistema_model'));
            $this->load->library('ocorrencias');
	}
        
        public function envia_email_teste()
        {
            $this->envio(array());
        }
        
        public function verifica_images_por_empresa( $id_empresa )
        {
            $this->load->model('imoveis_model');
            $filtro = 'imoveis.id_empresa = '.$id_empresa.' AND imoveis.vendido = 0 and imoveis.locado = 0';
            $imoveis = $this->imoveis_model->get_itens($filtro);
            $retorno = '<table border="1"><tr>'
                    . '<th>id</th>'
                    . '<th>nome</th>'
                    . '<th>referencia</th>'
                    . '<th>posicao</th>'
                    . '<th>local_correto</th>'
                    . '</tr>';
            $count = 0;
            foreach( $imoveis['itens'] as $imovel )
            {
                $image = str_replace('admin2_0','',  getcwd()).'imoveis_imagens/'.$imovel->foto1;
                if ( ! file_exists($image) )
                {
                    //$retorno .= $imovel->id.',';
                    $retorno .= '<tr>';
                    $retorno .= '<td>'.$imovel->id.'</td>';
                    $retorno .= '<td>'.$imovel->nome.'</td>';
                    $retorno .= '<td>'.$imovel->referencia.'</td>';
                    $retorno .= '<td>'.$image.'</td>';
                    $retorno .= '<td>'.str_replace('/home/guiasjp/www/','http://www.guiasjp.com/',$image).'</td>';
                    //var_dump($imovel);
                    $retorno .= '</tr>';
                    $count++;
                }
                
            }
            $retorno .= '</table>';
            echo 'qtde: '.$count.' - imoveis sem imagens<br>';
            echo $retorno;
            //var_dump($imoveis);
        }
        
        
        /**
         * Carrega o CEP da empresa
         * @param string $valor
         * @version 1.0
         * @access public
         */
        public function get_cep ( $valor )
        {
            $this->load->model('logradouros_model');
            $filtro = 'logradouros.cep = "'.$valor.'"';
            $retorno = $this->logradouros_model->get_itens($filtro);
            $return = (count($retorno['itens']) > 0 ) ? $retorno['itens'] : FALSE;
            echo json_encode($return);
        }
        
        /**
         * Carreega o endereco da empresa
         * @param string $valor
         * @version 1.0
         * @access public
         */
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
        
        /**
         * 
         * @param bool $id
         * @param bool $ok
         */
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
        
        public function envia_email( $id, $email = NULL )
        {
            $filtro_i = 'empresas.id = '.$id;
            $item = $this->empresas_model->get_item_cadastro($filtro_i);
            if( isset($item) && isset($email) )
            {
                $mensagem = '<img src="'.base_url().'images/topo.gif"></br>'.PHP_EOL;
                $mensagem .= '<h4>Olá,</h4>'.PHP_EOL;
                $mensagem .= '<p>Entramos em contato com sua empresa para atualizar algumas informações que são divulgadas no <strong>Portal GuiaSJP.com</strong>.</p>'.PHP_EOL;
                $mensagem .= '<p>O <strong>GuiaSJP.com</strong> é o Portal online da cidade de São José dos Pinhais, nele divulgamos empresas locais, produtos, serviços, notícias e entretenimento em nossa cidade.</p>'.PHP_EOL;
                $mensagem .= '<p>Acesse e conheça: <a href="http://www.guiasjp.com/">http://www.GuiaSJP.com/</a></p>'.PHP_EOL;
                $mensagem .= '<p>Precisamos confirmar os dados abaixo para continuar apresentando gratuitamente suas informações em nosso portal.</p>'.PHP_EOL;
                $mensagem .= '<p>Caso os dados estejam corretos, favor retornar esse email com um OK, caso precise corrigir algo, basta informar o novo dado no campo específico abaixo e nos retornar o email com um ALTERAR.</p>';
                $mensagem .= '<p>Os dados a conferir e a complementar são:</p>'.PHP_EOL;
                $mensagem .= '<br><br>'.PHP_EOL;
                $mensagem .= '<p><strong>Razão Social:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_razao_social.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Nome Fantasia:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_nome_fantasia.'</p>'.PHP_EOL;
 
                $mensagem .= '<p><strong>CEP:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->cep.'</p>'.PHP_EOL;
 
                $mensagem .= '<p><strong>Endereço:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->endereco.'</p>'.PHP_EOL;
 
                $mensagem .= '<p><strong>Numero:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_numero.'</p>'.PHP_EOL;
 
                $mensagem .= '<p><strong>Bairro:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->bairro.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Telefone empresa:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_telefone.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Empresa E-mail:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_email.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Empresa Site:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_dominio.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Contato Nome:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->contato_nome.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Contato E-mail:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->contato_email.'</p>'.PHP_EOL;
                $mensagem .= '<p><strong>Descrição da empresa:</strong></p>'.PHP_EOL;
                $mensagem .= '<p>'.$item->empresa_descricao.'</p>'.PHP_EOL;
                
                $mensagem .= '<br><br>'.PHP_EOL;
 
                $mensagem .= '<h5>Atenciosamente,</h5>'.PHP_EOL;
                $mensagem .= '<br><br>'.PHP_EOL;
                //$mensagem .= '<p>Gabrieli Camila Biauki</p>'.PHP_EOL;
                $mensagem .= '<p>Pow Soluções em Internet!</p>'.PHP_EOL;
                $mensagem .= '<p><a href="http://www.powinternet.com">www.powinternet.com</a></p>'.PHP_EOL;
                $mensagem .= '<p>Rua Visconde do Rio Branco, 2591 sala 02</p>'.PHP_EOL;
                $mensagem .= '<p>83005-420 - São José dos Pinhais - PR.</p>'.PHP_EOL;
                $mensagem .= '<p>(041) 3382 1581</p>'.PHP_EOL;
                $mensagem .= '<br><br>'.PHP_EOL;
                $mensagem .= '<p>Mais informações podem ser obtidas no endereço:</p>'.PHP_EOL;
                $mensagem .= '<p><a href="http://www.guiasjp.com/publicidade">http://www.guiasjp.com/publicidade</a></p>'.PHP_EOL;
                              
                
                
                $data['assunto'] = 'Contato do Portal GuiaSJP.com';
                $data['mensagem'] = $mensagem;
                $data['email'] = 'cadastro@guiasjp.com';
                $data['to'] = array((isset($email) ? $email : $item->empresa_email));
                $data['bcc'] = array('programacao@pow.com.br','cadastro@guiasjp.com');
                $email = $this->envio($data);
                $retorno = 1;
            }
            else
            {
                $retorno = 0;
            }
            echo json_encode($retorno);
        }
        
        private function _inicia_cadastro( $id = FALSE )
        {
            $filtro = ( $id ) ? 'empresas.id = '.$id : NULL;
            $data['item'] = $this->empresas_model->get_item_cadastro($filtro);
            $data['subcategorias'] = $this->subcategorias_model->get_select();
            $data['status_atualizada'] = $this->status_atualizada_model->get_select();
            $data['status_ocorrencia'] = $this->empresas_status_ocorrencia_model->get_select();
            $data['cidades'] = $this->cidades_model->get_select();
            $data['cargos'] = $this->pow_cargos_model->get_select();
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
                                ->set_breadscrumbs('Empresas', 'empresas/listar', 1)
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
				$data['tipo'] = 'Empresas Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                                $data['ocorrencias'] = $this->ocorrencias->get_ocorrencia();
                                $data['interacoes'] = $this->ocorrencias->get_interacoes_ocorrencia($codigo);
                                $this->layout
					->set_function( $function )
					->set_include('js/cad_empresas.js', TRUE)
                                        ->set_include('js/datetimepicker/moment.js', TRUE)
                                        ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
					//->set_include('js/datepicker/bootstrap-datepicker.js', TRUE)
                                        //->set_include('js/datepicker/locales/bootstrap-datepicker.pt-BR.js', TRUE)
					//->set_include('css/datepicker.css', TRUE)
					->set_include('css/estilo.css', TRUE)
					->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Empresas', 'empresas/listar', 1)
					->set_usuario($this->set_usuario())
					->view('cad_empresas',$data);
			}
		}
		else 
		{
			redirect('empresas/listar');
		}
	}
	
        
        private function _inicia_select( $id = FALSE, $id_categoria = FALSE ) 
        {
            $this->load->model(
                                array(
                                    'categorias_model',
                                    'estados_model', 
                                    'sistema_model', 
                                    'planos_pi_model', 
                                    'promocao_model', 
                                    'planos_mensal_model', 
                                    'planos_sites_model',
                                    'publicidade_campanhas_model',
                                    'imoveis_destaques_model',
                                    'imoveis_dest_listagem_model',
                                    )
                                );
            if(isset($id) && $id)
            {
                $empresa = $this->empresas_model->get_item($id);
                $retorno['logradouro'] = $this->logradouros_model->get_itens('logradouros.id ='.$empresa->id_logradouro);
            }
            if ( $id_categoria )
            {
                $filtro_subcategoria = 'id_categoria = '.$id_categoria;
                $parametro_categoria = FALSE;
            }
            else
            {
                $filtro_subcategoria = array();
                $parametro_categoria = TRUE;
            }
            $retorno['subcategorias'] = $this->subcategorias_model->get_select($filtro_subcategoria,$parametro_categoria);
            $retorno['categorias'] = $this->categorias_model->get_select();
            $retorno['status_atualizada'] = $this->status_atualizada_model->get_select();
            $retorno['status_ocorrencia'] = $this->empresas_status_ocorrencia_model->get_select();
            $retorno['cidades'] = $this->cidades_model->get_select();
            $retorno['estados'] = $this->estados_model->get_select();
            $retorno['cargos'] = $this->pow_cargos_model->get_select();
            $retorno['sistema'] = $this->sistema_model->get_select();
            $retorno['planos_pi'] = $this->planos_pi_model->get_select();
            $retorno['empresas_promocao'] = $this->promocao_model->get_select();
            $retorno['planos_mensal'] = $this->planos_mensal_model->get_select();
            $retorno['planos_sites'] = $this->planos_sites_model->get_select();
            $retorno['publicidade'] = $this->publicidade_campanhas_model->get_select('publicidade_campanhas.id_empresa = '.$id);
            $retorno['destaques'] = $this->imoveis_destaques_model->get_select('imoveis_destaques.id_empresa = '.$id);
            $retorno['dest_listagem'] = $this->imoveis_dest_listagem_model->get_select('imoveis_dest_listagem.id_empresa = '.$id);
            $retorno['largura'] = array(
                                        (object)array('id' => 772,'descricao' => 772),
                                        (object)array('id' => 950,'descricao' => 950),
                                        (object)array('id' => 1170,'descricao' => 1170),
                                        );
            $retorno['negocio'] = array(
                                        (object)array('id' => 1,'descricao' => 'Venda'),
                                        (object)array('id' => 2,'descricao' => 'Locaçao'),
                                        );
            $retorno['pagina_tipo'] = array(
                                        (object)array('id' => 'normal','descricao' => 'Normal'),
                                        (object)array('id' => 'imoveis','descricao' => 'Imoveis'),
                                        );
            $retorno['pagina_visivel'] = array(
                                        (object)array('id' => 2,'descricao' => 'Gratis'),
                                        (object)array('id' => 1,'descricao' => 'Sim'),
                                        (object)array('id' => 0,'descricao' => 'Nao'),
                                        );
            $retorno['modelo'] = array(
                                        (object)array('id' => 1,'descricao' => 'Azul'),
                                        (object)array('id' => 2,'descricao' => 'Amarelo'),
                                        (object)array('id' => 3,'descricao' => 'Laranja'),
                                        (object)array('id' => 4,'descricao' => 'Branco'),
                                        (object)array('id' => 5,'descricao' => 'Marron'),
                                        (object)array('id' => 6,'descricao' => 'Preto'),
                                        (object)array('id' => 7,'descricao' => 'Verde'),
                                        (object)array('id' => 8,'descricao' => 'Vermelho'),
                                        );
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
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'contrato',     'titulo' => 'Contrato',       'link' => str_replace(array('[col]','[ordem]'), array('contrato',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'contrato') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'contrato' ) ? 'ui-state-highlight'.( ($extras['col'] == 'contrato' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'subcategoria',     'titulo' => 'Categoria',       'link' => str_replace(array('[col]','[ordem]'), array('subcategoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'subcategoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'subcategoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'subcategoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_fantasia',     'titulo' => 'Nome fantasia',       'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'endereco',     'titulo' => 'Endereço',       'link' => str_replace(array('[col]','[ordem]'), array('endereco',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'endereco') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'endereco' ) ? 'ui-state-highlight'.( ($extras['col'] == 'endereco' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'dominio',     'titulo' => 'Dominio',       'link' => str_replace(array('[col]','[ordem]'), array('dominio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'dominio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'dominio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'dominio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id', 	'valor' => '' ) ),
                                        array( 'name' => 'razao_social',   'titulo' => 'Razão Social: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_razao_social', 	'valor' => '' ) ),
                                        array( 'name' => 'empresa_cnpj',   'titulo' => 'CNPJ: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_cnpj', 	'valor' => '' ) ),
                                        array( 'name' => 'nome_fantasia',   'titulo' => 'Nome fantasia: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'status',          'titulo' => 'Status atualiza: ','tipo' => 'select', 'valor' => $this->status_atualizada_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.status_atualizada', 		'valor' => '' ) ),
                                        array( 'name' => 'subcategoria',    'titulo' => 'Categoria: ',      'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id_subcategoria', 		'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 2;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;              
 		//$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        public function validar_email()
        {
            $this->load->helper('email');
            $retorno = (valid_email($this->input->get('email'))) ? TRUE : FALSE ;
            echo json_encode($retorno); 
        }

        public function get_assunto($sufix = NULL)
        {
            $this->load->model('empresas_ocorrencia_assunto_model');
            $busca = $this->empresas_ocorrencia_assunto_model->get_select();
            $config['valor'] = $busca; 
            $config['nome']  = 'assunto-'.$sufix; 
            $config['extra'] = 'id="assunto-'.$sufix.'"'; 
            $retorno  = '<label for="assunto-'.$sufix.'">Assunto</label>'; 
            $retorno .= form_select($config);
            echo $retorno;
        }
        
        public function get_setor($sufix = NULL)
        {
            $busca = $this->pow_cargos_model->get_select();
            $config['valor'] = $busca; 
            $config['nome']  = 'setores-io'; 
            $config['extra'] = 'id="setores-'.$sufix.'"'; 
            $config['class'] = 'setores-io'; 
            $retorno  = '<label for="setores-'.$sufix.'">Setor POW</label>'; 
            $retorno .= form_select($config);
            echo $retorno;
        }
        
        public function get_usuario($setor = '', $sufix = NULL, $multi = FALSE)
        {
            $this->load->model('usuario_model');
            $busca = $this->usuario_model->get_select_has_cargo('usuarios_has_cargos.id_pow_cargos = '.$setor.' AND usuarios.ativo = 1 ');
            $config['valor'] = $busca; 
            $config['nome']  = 'usuario-retorno-'.$sufix; 
            $config['extra'] = 'id="usuario-retorno-'.$sufix.'" '.((isset($multi) && $multi) ? 'multiple="multiple"' : ''); 
            $retorno  = '<label for="usuario-retorno-'.$sufix.'">Usuário POW</label>';
            $retorno .= form_select($config);
            echo $retorno;
        }
        
        public function get_status($sufix = NULL)
        {
            $busca =  $this->empresas_status_ocorrencia_model->get_select() ;
            $config['valor'] = $busca; 
            $config['nome']  = 'status-'.$sufix; 
            $config['extra'] = 'id="status-'.$sufix.'"'; 
            $retorno  = '<label for="status-'.$sufix.'">Status</label>'; 
            $retorno .= form_select($config);
            echo $retorno;
        }
        
        public function get_contatos_empresa($id_empresa = '', $sufix = '')
        {
            $busca = $this->empresas_contato_model->get_select('empresas_contato.id_empresa = '.$id_empresa);
            $config['valor'] = $busca; 
            $config['nome']  = 'id-contato-'.$sufix; 
            $config['extra'] = 'id="id-contato-'.$sufix.'"'; 
            $retorno  = '<label for="id-contato-'.$sufix.'">Contatos da Empresa</label>';
            $retorno .= form_select($config);
            echo $retorno;
        }
        
        public function get_edita_contatos_empresa($id_empresa = '', $id_contato = '',$sufix = '')
        {
            $this->load->model('empresas_contatos_atributos_model');
            $retorno['dados'] = $this->empresas_contato_model->get_contato_por_id('empresas_contato.id_empresa = '.$id_empresa.' AND empresas_contato.id = '.$id_contato);
            $retorno['atributos'] = $this->empresas_contatos_atributos_model->get_itens('empresas_contatos_atributos.id_contato = '.$id_contato);
            echo json_encode($retorno);
        }
        
        public function get_emails($id_ocorrencia = '')
        {
            $retorno = $this->interacao_model->get_emails_campanha('empresas_ocorrencia.id = '.$id_ocorrencia);
            echo json_encode($retorno);
        }
        
        public function add_contato()
        {
            $data = $this->_post();
            
            $campos = $data['campos'];
            $valores = $data['valores'];
            
            unset($data['campos']);
            unset($data['valores']);
            unset($data['conhece_guia']);
            unset($data['data_atualizada']);
            unset($data['usuario_atualizada']);
            //echo json_encode($data);
            
            $id = $this->empresas_contato_model->adicionar($data);
            
            $dados['id_contato'] = $id;
            if( isset($campos) && isset($valores) )
            {
                $this->load->model('empresas_contatos_atributos_model');
                foreach($campos as $key => $value)
                {
                    if( !empty($value) && !empty($valores[$key]) )
                    {
                        $dados['campo'] = $value;
                        $dados['valor'] = $valores[$key];
                        $this->empresas_contatos_atributos_model->adicionar($dados);
                    }
                }
            }
            echo $id;
        }
        
        public function edt_contato($contato = '')
        {
            $data = $this->_post();
            
            $campos = $data['campos'];
            $valores = $data['valores'];
            
            unset($data['campos']);
            unset($data['valores']);
            unset($data['conhece_guia']);
            unset($data['data_atualizada']);
            unset($data['usuario_atualizada']);
            
            $id = $this->empresas_contato_model->editar($data, 'empresas_contato.id = '.$contato);
            
            $this->load->model('empresas_contatos_atributos_model');
            $this->empresas_contatos_atributos_model->excluir('empresas_contatos_atributos.id_contato = '.$contato);
            
            $dados['id_contato'] = $contato;
            if( isset($campos) && isset($valores) )
            {
                foreach($campos as $key => $value)
                {
                    if( !empty($value) && !empty($valores[$key]) )
                    {
                        $dados['campo'] = $value;
                        $dados['valor'] = $valores[$key];
                        $this->empresas_contatos_atributos_model->adicionar($dados);
                    }
                }
            }
            echo $id;
        }
        
        public function add_ocorrencia()
        {
            $data_oc = $this->_post();
            //$data_oc['data'] = date('Y-m-d H:i:s');
            $data_oc['id_usuario_ativo'] = ((isset($data_oc['id_usuario_ativo']) && !empty($data_oc['id_usuario_ativo'])) ? $data_oc['id_usuario_ativo'] : $this->sessao['id']);
            
            $data_in['id_usuario'] = $this->sessao['id'];
            //$data_in['periodo'] = $data_oc['periodo'];
            
            /*
            if(isset($data_oc['data_retorno']) && !empty($data_oc['data_retorno']))
            {
                $exp_a = explode('/', $data_oc['data_retorno']);
                $data_in['data_retorno'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
            }*/
            
            if(isset($data_oc['data_retorno_inicio']) && !empty($data_oc['data_retorno_inicio']) && $data_oc['data_retorno_inicio'] != '00-00-0000 00:00')
            {
                $exp_a = explode('/', $data_oc['data_retorno_inicio']);
                $exp_b = explode(' ', $exp_a[2]);
                $data_oc['data_retorno_inicio'] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1].':00';
            }
            
            if(isset($data_oc['data_retorno_fim']) && !empty($data_oc['data_retorno_fim']) && $data_oc['data_retorno_fim'] != '00-00-0000 00:00')
            {
                $exp_c = explode('/', $data_oc['data_retorno_fim']);
                $exp_d = explode(' ', $exp_c[2]);
                $data_oc['data_retorno_fim'] = $exp_d[0].'-'.$exp_c[1].'-'.$exp_c[0].' '.$exp_d[1].':00';
            }
            
            $data_in['data_retorno_inicio'] = $data_oc['data_retorno_inicio'];
            $data_in['data_retorno_fim'] = $data_oc['data_retorno_fim'];
            
            unset($data_oc['id_usuario_retorno']);
            //unset($data_oc['data_retorno']);
            //unset($data_oc['periodo']);
            
            unset($data_oc['conhece_guia']);
            unset($data_oc['data_atualizada']);
            unset($data_oc['usuario_atualizada']);
            unset($data_oc['email_automatico']);
            
            $id_oc = $this->ocorrencias_model->adicionar($data_oc);
            $data_in['id_empresas_ocorrencia'] = $id_oc;
            $data_in['id_empresas_status_ocorrencia'] = $data_oc['id_empresas_status_ocorrencia'];
            $data_in['id_contato'] = $data_oc['id_contato'];
            //$data_in['data_inclusao'] = date('Y-m-d H:i:s');
            $data_in['obs'] = $data_oc['texto'];
            
            $id_in = $this->interacao_model->adicionar($data_in);
            
            if( (isset($data_oc['id_usuario_ativo']) && !empty($data_oc['id_usuario_ativo']) && $data_oc['id_usuario_ativo'] != $this->sessao['id'] ) )
            {
                $this->send_email($id_oc);
            }
            
            $retorno['id'] = $id_in;
            echo json_encode($retorno);
        }
        
        public function add_interacao()
        {
            $data = $this->_post();
            $data['id_usuario'] = $this->sessao['id'];
            $usuario_ativo = ((isset($data['id_usuario_ativo']) && !empty($data['id_usuario_ativo'])) ? $data['id_usuario_ativo'] : $this->sessao['id']);
            
            /*
            if(isset($data['data_retorno']) && !empty($data['data_retorno']))
            {
                $exp_a = explode('/', $data['data_retorno']);
                $data['data_retorno'] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
            }*/
            
            if(isset($data['data_retorno_inicio']) && !empty($data['data_retorno_inicio']) && $data['data_retorno_inicio'] != '00-00-0000 00:00')
            {
                $exp_a = explode('/', $data['data_retorno_inicio']);
                $exp_b = explode(' ', $exp_a[2]);
                $data['data_retorno_inicio'] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1].':00';
            }
            
            if(isset($data['data_retorno_fim']) && !empty($data['data_retorno_fim']) && $data['data_retorno_fim'] != '00-00-0000 00:00')
            {
                $exp_c = explode('/', $data['data_retorno_fim']);
                $exp_d = explode(' ', $exp_c[2]);
                $data['data_retorno_fim'] = $exp_d[0].'-'.$exp_c[1].'-'.$exp_c[0].' '.$exp_d[1].':00';
            }
            
            $edt_oc['data_retorno_inicio'] = $data['data_retorno_inicio'];
            $edt_oc['data_retorno_fim'] = $data['data_retorno_fim'];
            $edt_oc['id_empresas_status_ocorrencia'] = $data['id_empresas_status_ocorrencia'];
            $edt_oc['id_usuario_ativo'] = $usuario_ativo;
            $edt_oc['prioridade'] = $data['prioridade'];
            
            unset($data['id_usuario_ativo']);
            unset($data['prioridade']);
            unset($data['conhece_guia']);
            unset($data['data_atualizada']);
            unset($data['usuario_atualizada']);
            
            $this->ocorrencias_model->editar($edt_oc, 'empresas_ocorrencia.id = '.$data['id_empresas_ocorrencia']);
            
            $id = $this->interacao_model->adicionar($data);
            
            if( isset($usuario_ativo) && !empty($usuario_ativo) && $usuario_ativo != $this->sessao['id']  )
            {
                $this->send_email($data['id_empresas_ocorrencia']);
            }
            
            $retorno = $id;
            
            echo json_encode($retorno);
        }
        
        public function add_opiniao()
        {
            $data = $this->_post();
            $data['id_usuario'] = $this->sessao['id'];
            $edt_oc['prioridade'] = $data['prioridade'];
            
            unset($data['prioridade']);
            unset($data['conhece_guia']);
            unset($data['data_atualizada']);
            unset($data['usuario_atualizada']);
            
            $this->ocorrencias_model->editar($edt_oc, 'empresas_ocorrencia.id = '.$data['id_empresas_ocorrencia']);
            
            $id = $this->interacao_model->adicionar($data);
            
            $this->send_email($data['id_empresas_ocorrencia'], 'Opinião');
            
            $retorno = $id;
            
            echo json_encode($retorno);
        }
        
        public function send_email($ocorrencia = '', $tipo = '')
        {
            $this->load->helper('email');
            $retorno = FALSE;
            if(isset($ocorrencia) && $ocorrencia)
            {
                $dados = $this->ocorrencias_model->get_itens_email_automatico('empresas_ocorrencia.id = '.$ocorrencia);
                $email['email'] = 'programacao@pow.com.br';
                $email['to'] = $dados->usuario_email;
                if(valid_email($email['to']))
                {
                    $email['assunto']   = '('.$tipo.') Direcionamento de Chamado, prioridade: '.$dados->prioridade;
                    $email['mensagem']  = 'O Chamado de Nº <b>'.$ocorrencia.'</b> foi direcionado a você.<br>'.PHP_EOL;
                    
                    $email['mensagem'] .= $dados->assunto.'<br>'.PHP_EOL;
                    $email['mensagem'] .= 'Clique no link para saber mais:  <a href="'.base_url().'empresas/editar/'.$dados->id.'">Clique Aqui</a><br>'.PHP_EOL;
                    $email['mensagem'] .= '<br><br><br><br>';
                    $email['mensagem'] .= 'POW Internet<br>';
                    $email['mensagem'] .= '(41)3382-1581<br>';
                    $email['mensagem'] .= 'www.pow.com.br<br>';
                    $email['mensagem'] .= 'www.guiasjp.com<br>';
                    $email['mensagem'] .= 'www.powsites.com.br<br>';
                    $email['mensagem'] .= 'www.portaisimobiliarios.com<br>';
                    $email['mensagem'] .= 'www.sitesimobiliarios.com<br>';
                    $email['mensagem'] .= '<br><br><br><br>';
                    $email['mensagem'] .= '<i>Esse é um e-mail automático por gentileza não responder.<i><br>';
                    $retorno = $this->envio($email);
                }
            }
            return $retorno;
        }
        
        public function add_tempo()
        {
            $this->load->model('empresas_ocorrencia_tempo_model');
            $this->load->helper('cookie');
            
            $retorno = NULL; 
            
            $data = $this->_post();
            $data['id_usuario'] = $this->sessao['id'];
            
            unset($data['conhece_guia']);
            unset($data['data_atualizada']);
            unset($data['usuario_atualizada']);
            
            if(isset($data['tempo_inicio']) && $data['tempo_inicio'])
            {
                $data['tempo_inicio'] = date('H:i:s');
                $id = $this->empresas_ocorrencia_tempo_model->adicionar($data);
                
                $this->set_cookie_tempo('tempo_ocorrencia['.$data['id_ocorrencia'].']',$id);
                
                $retorno = array('inicio' => $data['tempo_inicio'], 'id' => $id);
            }
            
            if(isset($data['tempo_fim']) && $data['tempo_fim'])
            {
                $fim['tempo_fim'] = date('H:i:s');
                $id = $this->empresas_ocorrencia_tempo_model->editar($fim, 'empresas_ocorrencia_tempo.id = '.$data['id']);
                
                $this->unset_cookie_tempo('tempo_ocorrencia['.$data['id_ocorrencia'].']');
                
                $retorno = array('fim' => $data['tempo_fim'], 'id' => $data['id']);
            }
            
            echo json_encode($retorno);
        }
        
        public function set_cookie_tempo($nome = '', $valor = '')
        {
            if(isset($nome) && $nome)
            {
                $this->load->helper('cookie');
                if ( ! isset($_COOKIE[$nome]) )
                {
                    $array = array(
                               'name'   => $nome,
                               'value'  => $valor,
                               'expire' => 0,
                               'path'   => '/'
                                );
                    set_cookie($array);
                }
            }
        }
        
        public function get_cookie_tempo ($nome = '')
        {
            if(isset($nome) && $nome)
            {
                $this->load->helper('cookie');
                $this->load->model('empresas_ocorrencia_tempo_model');
                $tempo = get_cookie($nome);
                if ( $tempo && isset($tempo) )
                {
                    foreach($tempo as $key => $value)
                    {
                        $pesquisa = $this->empresas_ocorrencia_tempo_model->get_itens('id_ocorrencia = '.$key);
                        $r[$key][$value]['existe'] = (isset($pesquisa['itens']) && $pesquisa['itens']) ? TRUE : FALSE;
                    }
                    
                    $retorno = $r;
                }
                else
                {
                    $retorno = FALSE;
                }
                echo json_encode($retorno);
            }
        }
        
        public function unset_cookie_tempo($nome = '')
        {
            $this->load->helper('cookie');
            delete_cookie($nome);
        }
        
        /*
        public function email_automatico()
        {
            $data =  $this->_post();
            $dados = $this->ocorrencias_model->get_itens_email_automatico('empresas_ocorrencia.id = '.$data['id']);
            
            $email = array(
                'assunto' => 'Ocorrência encaminhada',
                'email' => 'programacao@pow.com.br',
                'to' => $dados->email_usuario,
            );
            
            $mensagem  = ' Ocorrência de Nº '.$dados->id_ocorrencia.' foi passada a você.<br>';
            $mensagem .= ' Para dar prosseguimento <a href="'.base_url().'empresas/editar/'.$dados->id.'">Clique Aqui</a><br>';
            $email['mensagem'] =  $mensagem;
            
            $retorno = $this->envio($email);
            echo $retorno;
        }*/
        
        public function pesquisa_empresa( $busca = '', $retorno = 'json' )
        {
            $busca = trim(urldecode($busca));
            $busca = str_replace(array(' ',',','.'), '%', $busca);
            $filtro = 'empresas.empresa_nome_fantasia LIKE "%'.$busca.'%" OR empresas.empresa_razao_social LIKE "%'.$busca.'%" OR empresas.empresa_cnpj LIKE "%'.$busca.'%"';
            $itens = $this->empresas_model->get_select( $filtro );
            switch( $retorno )
            {
                case 'json':
                    echo isset($itens) ? json_encode($itens) : array();
                    break;
                case 'retorno':
                    return isset($itens) ? json_encode($itens) : array();
            }
        }
        
        public function administrar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $i = 'empresas/administrar';
            $acesso = $this->set_setor_usuario($i);
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_administrar( $url, $valores );
            $itens = $this->empresas_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->empresas_model->get_total_itens( $filtro->get_filtro() );
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
                        ->set_include('js/empresas.js', TRUE)
                        ->set_include('js/administrar_empresas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Empresas', 'empresas', 1)
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
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'contrato',     'titulo' => 'Contrato',       'link' => str_replace(array('[col]','[ordem]'), array('contrato',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'contrato') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'contrato' ) ? 'ui-state-highlight'.( ($extras['col'] == 'contrato' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'subcategoria',     'titulo' => 'Categoria',       'link' => str_replace(array('[col]','[ordem]'), array('subcategoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'subcategoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'subcategoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'subcategoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome_fantasia',     'titulo' => 'Nome fantasia',       'link' => str_replace(array('[col]','[ordem]'), array('nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome_fantasia' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome_fantasia' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'bloqueado',     'titulo' => 'Status',       'link' => str_replace(array('[col]','[ordem]'), array('bloqueado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'bloqueado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'bloqueado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'bloqueado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'endereco',     'titulo' => 'Endereço',       'link' => str_replace(array('[col]','[ordem]'), array('endereco',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'endereco') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'endereco' ) ? 'ui-state-highlight'.( ($extras['col'] == 'endereco' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'dominio',     'titulo' => 'Dominio',       'link' => str_replace(array('[col]','[ordem]'), array('dominio',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'dominio') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'dominio' ) ? 'ui-state-highlight'.( ($extras['col'] == 'dominio' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Administrar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => strtolower(__CLASS__).'/editar_administrar/[id]', 'target' => '_blank'),
                                                    (object) array('titulo' => 'Ocorrência', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>', 'link' => strtolower(__CLASS__).'/editar/[id]', 'target' => '_blank'),
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
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id', 	'valor' => '' ) ),
                                        array( 'name' => 'subcategoria',    'titulo' => 'Categoria: ',      'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.id_subcategoria', 		'valor' => '' ) ),
                                        array( 'name' => 'razao_social',    'titulo' => 'Razão Social: ',   'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_razao_social', 	'valor' => '' ) ),
                                        array( 'name' => 'nome_fantasia',   'titulo' => 'Nome fantasia: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'empresa_cnpj',    'titulo' => 'CNPJ: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas.empresa_cnpj', 	'valor' => '' ) ),
                                        array( 'name' => 'contrato',        'titulo' => 'Contrato: ',       'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.contrato', 	'valor' => '' ) ),
                                        array( 'name' => 'sistema',         'titulo' => 'Sistema: ',        'tipo' => 'select', 'valor' => $this->sistema_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas.sistema', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;              
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes']  .= ' <button class="btn btn-info administrar_varios">Administrar vários</button>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        
	public function editar_administrar($codigo = NULL, $ok = FALSE)
	{
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $i = 'empresas/administrar';
            $acesso = $this->set_setor_usuario($i);
            if ( $acesso['status'] )
            {
		$dados = $this->empresas_model->get_item_administrar($codigo);
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
				$data = $this->_inicia_select($codigo, $dados->id_categoria);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
				$data['tipo'] = 'Empresas Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
                                $data['editavel'] = $acesso['edita'];
                                $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                                $this->layout
					->set_function( $function )
					->set_include('js/administrar_empresas.js', TRUE)
					->set_include('js/auto_save.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Empresas', 'empresas/listar', 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($classe, $function))
					->view('administrar_empresas',$data);
			}
		}
		else 
		{
			redirect('empresas/administrar');
		}
                
            }
            else
            {
                redirect('painel');
            }
	}
	
        
        public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post(FALSE);
            if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
            {
                $data['usuario_atualizada'] = $this->sessao['id'];
                $data['data_atualizada'] = date('Y-m-d H:i');
            
                if ( is_array($dados['campo']) )
                {
                    $filtro = 'empresas.id = '.$dados['id'];
                    for( $a = 0; $a < count($dados['campo']); $a++ )
                    {
                        $data[$dados['campo'][$a]] = $dados['valor'][$a];
                        
                    }
                }
                else
                {
                    $filtro = 'empresas.id = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                }
                $afetados = $this->empresas_model->editar($data, $filtro);
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
                $retorno['id'] = $this->empresas_model->adicionar($data);
                if ( isset($retorno['id']) && $retorno['id'] )
                {
                    $retorno['status'] = TRUE;
                    $retorno['muda_url'] = base_url().'empresas/administrar/'.$retorno['id'].'/';
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
        
        public function auxiliar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros_auxiliar( $url, $valores );
            $itens = $this->empresas_auxiliar_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->empresas_auxiliar_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem_auxiliar( $itens, $extras );
            $i = 'empresas/auxiliar';
            $acesso = $this->set_setor_usuario($i);
            $data['editavel'] = $acesso['edita'];
            $data['editou'] = isset($_GET['editou']) ? $_GET['editou'] : FALSE; 
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/empresas_auxiliar.js', TRUE)
                        ->set_include('js/administrar_empresas.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Empresas', 'empresas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
        
        
	private function _inicia_listagem_auxiliar( $itens, $extras = NULL, $exportar = FALSE, $ordenacao = TRUE )
	{
		if ( $exportar )
		{
                        $cabecalho = ' ';
		}
		else 
		{
                    
			$data['cabecalho'] = array(
                                                    array( 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'data',           'titulo' => 'Cadastrado',   'classe' => 'col-lg-3 col-sm-3 col-md-4 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? ' '.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'contrato',       'titulo' => 'Contrato',     'classe' => 'col-lg-3 col-sm-3 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'subcategoria',   'titulo' => 'Categoria',    'classe' => 'col-lg-3 col-sm-3 col-md-4 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('subcategoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'subcategoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'subcategoria' ) ? ' '.( ($extras['col'] == 'subcategoria' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'inscricao',      'titulo' => 'Inscrição',    'classe' => 'col-lg-3 col-sm-3 col-md-4 col-xs-6',  ),
                                                    )),
                                                    array('titulo' => 'Contato', 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'contato_nome',   'titulo' => 'Nome',         'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6', 'link' => str_replace(array('[col]','[ordem]'), array('contato_nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'contato_nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'contato_nome' ) ? ' '.( ($extras['col'] == 'contato_nome' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )  ),
                                                        (object)array( 'chave' => 'contato_email',  'titulo' => 'Email',        'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',  ),
                                                        (object)array( 'chave' => 'contato_telefone',       'titulo' => 'Telefone',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                    )),
                                                    array('titulo' => 'Autorizador', 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'autorizador',   'titulo' => 'Nome',         'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',  ),
                                                        (object)array( 'chave' => 'autorizador_email',  'titulo' => 'Email',        'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'autorizador_telefone',       'titulo' => 'Telefone',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'autorizador_cpf',       'titulo' => 'CPF',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'autorizador_nascimento',       'titulo' => 'Nascimento',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'conferir_cpf',       'titulo' => '',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                    )),
                                                    array('titulo' => 'Planos e datas', 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'plano_desejado',  'titulo' => 'Plano desejado',        'classe' => 'col-lg-5 col-sm-5 col-md-5 col-xs-6',   ),
                                                        (object)array( 'chave' => 'plano_mensal',       'titulo' => 'Plano mensal',     'classe' => 'col-lg-5 col-sm-5 col-md-5 col-xs-6',   ),
                                                        (object)array( 'chave' => 'dia_pgto',       'titulo' => 'Dia pagamento',     'classe' => 'col-lg-2 col-sm-2 col-md-2 col-xs-6',   ),
                                                    )),
                                                    array('titulo' => 'Logradouro', 'classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'logradouro',             'titulo' => 'Status',       'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',  'link' => str_replace(array('[col]','[ordem]'), array('logradouro',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'logradouro') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'logradouro' ) ? ' '.( ($extras['col'] == 'logradouro' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down' )    ),
                                                        (object)array( 'chave' => 'endereco',               'titulo' => 'Logradouro',   'classe' => 'col-lg-5 col-sm-5 col-md-5 col-xs-12', 'link' => str_replace(array('[col]','[ordem]'), array('endereco',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'endereco') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'endereco' ) ? ' '.( ($extras['col'] == 'endereco' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down'  ), ),
                                                        (object)array( 'chave' => 'empresa_numero',         'titulo' => 'Numero',       'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-4',   ),
                                                        (object)array( 'chave' => 'empresa_complemento',    'titulo' => 'Complemento',  'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-8',   ),
                                                        (object)array( 'chave' => 'bairro',                 'titulo' => 'Bairro',       'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'cidade',                 'titulo' => 'Cidade',       'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   'link' => str_replace(array('[col]','[ordem]'), array('cidade',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cidade') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cidade' ) ? ' '.( ($extras['col'] == 'cidade' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down'), ),
                                                        (object)array( 'chave' => 'empresa_cep',            'titulo' => 'CEP',          'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                    )),
                                                    array('classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'empresa_razao_social',           'titulo' => 'Razão Social', 'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12',   ),
                                                        (object)array( 'chave' => 'empresa_nome_fantasia',          'titulo' => 'Nome fantasia','classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12',  'link' => str_replace(array('[col]','[ordem]'), array('empresa_nome_fantasia',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'empresa_nome_fantasia') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'empresa_nome_fantasia' ) ? ' '.( ($extras['col'] == 'empresa_nome_fantasia' && $extras['ordem'] == 'ASC') ? '  glyphicon glyphicon-chevron-down' : '  glyphicon glyphicon-chevron-up' ) : ' glyphicon glyphicon-chevron-down'), ),
                                                        (object)array( 'chave' => 'empresa_cnpj',                   'titulo' => 'CNPJ',         'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'conferir_cnpj',                   'titulo' => '',         'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'empresa_telefone',               'titulo' => 'Telefone',     'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'empresa_email',                  'titulo' => 'Email',        'classe' => 'col-lg-4 col-sm-4 col-md-4 col-xs-6',   ),
                                                        (object)array( 'chave' => 'pagina_creci',   'titulo' => 'Creci',         'classe' => 'col-lg-3 col-sm-3 col-md-3 col-xs-6',  ),
                                                    )),
                                                    array('classe' => 'alert alert-success', 'itens' => array(
                                                        (object)array( 'chave' => 'aprovado',   'titulo' => 'Status',         'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6', 'acao' => 'set_aprovado' ),
                                                        (object)array( 'chave' => 'inscricao',   'titulo' => '',         'classe' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6', 'acao' => 'set_btn_contrato' ),
                                                    )),
                            );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Mudar Status', 'class' => 'col-lg-4 col-sm-4 col-md-4 col-xs-4 btn btn-warning pull-left', 'icone' => '<span class="glyphicon glyphicon-ok"></span>', 'link' => 'empresas/editar_auxiliar/aprovado/[id]', 'extra' => ''),
                                                    (object) array('titulo' => 'Aprovar', 'class' => 'col-lg-4 col-sm-4 col-md-4 col-xs-4 btn btn-info pull-left', 'icone' => '<span class="glyphicon glyphicon-ok"></span>', 'link' => 'empresas/salvar_auxiliar/[id]', 'extra' => ''),
                                                    (object) array('titulo' => 'Reprovar', 'class' => 'col-lg-4 col-sm-4 col-md-4 col-xs-4 btn btn-danger pull-right remover-auxiliar', 'icone' => '<span class="glyphicon glyphicon-remove"></span>', 'extra' => ''),
                                                    );
                        /**
                         * qtde_por_linha = caso exista vai definir quantos elemento mostrar por linha na listagem, para objetos muito extensos, usar 1
                         * titulo = isset-> vai inserir o titulo do elemento.
                         * chave = utilizado para definir data-item da linha
                         * * load em listagem_etiqueta
                         */
			$data['qtde_por_linha'] = 1;
			$data['chave'] = 'id';
                        $data['ordenacao'] = $ordenacao;
                        
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$data['titulo'] = 'Empresas Auxiliares';
                        $this->load->library('listagem_etiqueta');
			$this->listagem_etiqueta->inicia( $data );
			$retorno = $this->listagem_etiqueta->get_html();
		}
		return $retorno;
	}
        
	private function _inicia_filtros_auxiliar($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id_subcategoria',    'titulo' => 'Subcategoria: ',      'tipo' => 'select', 'valor' => $this->subcategorias_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_auxiliar.id_subcategoria', 		'valor' => '' ) ),
                                        array( 'name' => 'id_cidade',    'titulo' => 'Cidade: ',   'tipo' => 'select', 'valor' => $this->cidades_model->get_select(), 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_auxiliar.id_cidade', 	'valor' => '' ) ),
                                        array( 'name' => 'razao_social',    'titulo' => 'Razão Social: ',   'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_auxiliar.empresa_razao_social', 	'valor' => '' ) ),
                                        array( 'name' => 'nome_fantasia',   'titulo' => 'Nome fantasia: ',  'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_auxiliar.empresa_nome_fantasia', 	'valor' => '' ) ),
                                        array( 'name' => 'empresa_cnpj',    'titulo' => 'CNPJ: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'empresas_auxiliar.empresa_cnpj', 	'valor' => '' ) ),
                                        array( 'name' => 'contrato',        'titulo' => 'Contrato: ',       'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'empresas_auxiliar.contrato', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 4;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;              
 		$config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
 		$config['botoes']  .= ' <button class="btn btn-info administrar_varios">Administrar vários</button>';
 		//$config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary" >Add Novo</a>';
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
        
        public function editar_auxiliar($campo = 'aprovado', $id = NULL)
        {
            if ( isset($id) )
            {
                $item = $this->empresas_auxiliar_model->get_item($id);
                if ( isset($item) )
                {
                    $i = 0;
                    if ( $item->{$campo} == 0 )
                    {
                        $i = 1;
                    }
                    $filtro = array($campo => $i);
                    $editou = $this->empresas_auxiliar_model->editar($filtro, array('id' => $id));
                    redirect(base_url().'empresas/auxiliar?editou='.$editou);
                }
                else
                {
                    die('Não foi possivel alterar esta empresa, verifique com o administrador do sistema. erro: sem item '.$id);
                }
            }
            else
            {
                redirect(base_url().'empresas/auxiliar');
            }
        }
        
        public function salvar_auxiliar($id = NULL)
        {
            if ( isset($id) )
            {
                $item = $this->empresas_auxiliar_model->get_item($id);
                if ( isset($item->empresa_telefone) && ! empty($item->empresa_telefone) )
                {
                    $item->empresa_telefone = $item->contato_telefone;
                }
                $id_empresa = $this->empresas_model->adicionar($item);
                if ( isset($id_empresa) && $id_empresa )
                {
                    $this->empresas_auxiliar_model->excluir(array('id' => $id));
                    redirect(base_url().'empresas/editar_administrar/'.$id_empresa);
                }
                else
                {
                    die('Não foi possivel salver esta empresa, verifique com o administrador do sistema. erro: '.$id);
                }
            }
            else
            {
                redirect(base_url().'empresas/auxiliar');
            }
        }
        
        public function remover_auxiliar($id = NULL)
        {
            $num = $this->empresas_auxiliar_model->excluir(array('id' => $id));
            echo json_encode($num);
        }
        
	private function _post( $normal = TRUE )
	{
            
            $data = $this->input->post(NULL, TRUE);
            if ( $normal )
            {
                if ( ! isset( $data['conhece_guia'] ) )
                {
                        $data['conhece_guia'] = 0;
                }
            }
            else
            {
                if ( isset($data['campo']) && $data['campo'] == 'data' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_abertura' ) 
                {
                    $data['valor'] = converte_data_mysql($data['valor']);
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_portal' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_site' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && ( strstr($data['campo'], 'inicio') || strstr($data['campo'], 'termino') ) ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'autorizador_telefone' ) 
                {
                    $valor = str_replace(array('(',')','-'), '', $data['valor']);
                    $a = explode(' ',$valor);
                    unset($data['valor'], $data['campo']);
                    $data['campo'][0] = 'autorizador_ddd';
                    $data['valor'][0] = $a[0];
                    $data['campo'][1] = 'autorizador_telefone';
                    $data['valor'][1] = $a[1];
                }
                if ( isset($data['campo']) && $data['campo'] == 'contato_telefone' ) 
                {
                    $valor = str_replace(array('(',')','-'), '', $data['valor']);
                    $a = explode(' ',$valor);
                    unset($data['valor'], $data['campo']);
                    $data['campo'][0] = 'contato_ddd';
                    $data['valor'][0] = $a[0];
                    $data['campo'][1] = 'contato_telefone';
                    $data['valor'][1] = $a[1];
                }
            }
            $data['usuario_atualizada'] = $this->sessao['id'];
            $data['data_atualizada'] = date('Y-m-d H:i');
            
            

            return $data;
	}
}
