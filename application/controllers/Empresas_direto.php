<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresas_Direto extends MY_Controller 
{
        private $valida_cad = array(
                                array( 'field'   => 'empresa_razao_social',     'label'   => 'Razão Social',    'rules'   => 'required|trim'),
                                array( 'field'   => 'empresa_nome_fantasia',    'label'   => 'Nome Fantasia',   'rules'   => 'required|trim'),
                                array( 'field'   => 'pagina_nome_inicial',      'label'   => 'Nome Inicial',    'rules'   => 'required|trim'),
                                array( 'field'   => 'contato_nome',             'label'   => 'Nome contato',    'rules'   => 'required|trim'),
                                array( 'field'   => 'empresa_numero',           'label'   => 'Numero',          'rules'   => 'required|trim'),
                                array( 'field'   => 'pagina_creci',             'label'   => 'Creci',           'rules'   => 'trim'),
                                array( 'field'   => 'empresa_telefone',         'label'   => 'Telefone empresa','rules'   => 'trim'),
                                array( 'field'   => 'empresa_complemento',      'label'   => 'Complemento',     'rules'   => 'trim'),
                                array( 'field'   => 'empresa_descricao',        'label'   => 'Descrição empresa','rules'  => 'trim'),
                                array( 'field'   => 'empresa_email',            'label'   => 'Email',           'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'empresa_dominio',          'label'   => 'Dominio',         'rules'   => 'trim'),
                                array( 'field'   => 'contato_email',            'label'   => 'Email contato',   'rules'   => 'trim|valid_email'),
                                array( 'field'   => 'contato_ddd',              'label'   => 'DDD contato',     'rules'   => 'trim'),
                                array( 'field'   => 'contato_telefone',         'label'   => 'Telefone contato','rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('empresas_model','empresas_contato_model', 'images_model'));
            $this->load->library(array('anexo_lib'));
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
        
	public function editar($codigo = NULL, $ok = FALSE)
	{
            if($codigo == $this->sessao['id_empresa'])
            {
		$dados = $this->empresas_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida_cad);
			if ($this->form_validation->run())
			{
				$data = $this->_post();
                                
                                $data_img['arquivo'] = $data['uploaded_files'];
                                $data_img['descricao'] = $data['descricao_files'];
                                $data_img['id_image_tipo'] = $data['image_tipo'];
                                $data_img['lixo'] = $data['lixo'];
                                $data_img['id_pai'] = $data['id_pai'];

                                unset($data['uploaded_files']);
                                unset($data['descricao_files']);
                                unset($data['image_tipo']);
                                unset($data['lixo']);
                                unset($data['id_pai']);
                                unset($data['familia']);
                                unset($data['files']);

                                $this->anexo_lib->atualizar_uploads($data_img);
                                
				$id = $this->empresas_model->editar($data, array('empresas.id' => $codigo));
				redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                                $data['familia'] = $class;
                                $data['data_url'] = base_url().$class.'/tratar_upload/fazer_upload';
				$data['tipo'] = 'Empresas Direto Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                                $this->layout
					->set_function( $function )
					->set_include('js/empresas_direto.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_include('css/jquery.fileupload.css', TRUE)
					->set_usuario($this->set_usuario())
					->view('add_empresas_direto',$data,'layout/empresas');
			}
		}
		else 
		{
                    redirect('login/logout');
		}
            }
            else
            {
                redirect('login/logout');
            }
	}
        
        public function tratar_upload($tipo = NULL)
        {
            if(isset($tipo) && !empty($tipo))
            {
                $data = $this->_post();
                switch($tipo)
                {
                    case 'fazer_upload':
                        $retorno = $this->anexo_lib->do_upload($data);
                        break;
                    case 'deletar_upload':
                        $retorno = $this->anexo_lib->deletar_arquivo($data);
                        break;
                    case 'remover_upload':
                        $retorno = $this->anexo_lib->remover_arquivo($data);
                        break;
                }
                print_r($retorno);
            }
        }
        
        public function get_tipo_images()
        {
            $data = $this->_post();
            $images = $this->images_model->get_arquivo_por_tipo($data['id_image_tipo'], $data['id_pai'] );
            $itens = $images['itens'];
            echo json_encode($itens);
        }
        
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['imagens'] = $this->images_model->get_select_tipo_image(strtolower(__CLASS__));
            return $retorno;
        }
        
	private function _post()
	{
            $data = $this->input->post(NULL, TRUE);
            $data['files'] = (isset($_FILES['files']) && $_FILES['files']) ? $_FILES['files'] : NULL;
            return $data;
	}
}
