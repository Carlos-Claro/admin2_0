<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canais_Atributo extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'qtde',           'label'   => 'Quantidade', 		'rules'   => 'trim'),
                                array( 'field'   => 'ordem',           'label'   => 'Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'camada',           'label'   => 'Camada', 	'rules'   => 'required|trim'),
                                array( 'field'   => 'n_coluna_lg_sm',           'label'   => 'Nº Coluna Grande', 	'rules'   => 'trim'),
                                array( 'field'   => 'n_coluna_md',           'label'   => 'Nº Coluna Média', 	'rules'   => 'trim'),
                                array( 'field'   => 'n_coluna_xs',           'label'   => 'Nº Coluna Pequena', 	'rules'   => 'trim'),
                                array( 'field'   => 'tipo_ordem',           'label'   => 'Tipo de Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'campo_ordem',           'label'   => 'Campo de Ordem', 	'rules'   => 'trim'),
                                array( 'field'   => 'qtde_caracteres_descricao',           'label'   => 'Qtde de Caractesre para descrição', 	'rules'   => 'trim'),
                                array( 'field'   => 'qtde_colunas',           'label'   => 'Qtde de colunas', 	'rules'   => 'trim'),
                                array( 'field'   => 'classe',            'label'   => 'Classe',         'rules'   => 'trim'),
                                array( 'field'   => 'classe_master',            'label'   => 'Classe Master',         'rules'   => 'trim'),
                                array( 'field'   => 'posicao_image',            'label'   => 'Posição da Imagem',         'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('canais_atributo_model','canais_model'));
	}
	
        public function editar($id_canal = NULL, $ok = FALSE)
	{
                $canal = $this->canais_model->get_item($id_canal);
		$dados = $this->canais_atributo_model->get_item_por_canal('canais_atributo.id_canais in ('.$id_canal.') AND canais_atributo.camada like "%a%"');
                $function = strtolower(__FUNCTION__);
                $class = strtolower(__CLASS__);
                $data['action'] = base_url().$class.'/'.$function.'/'.$id_canal;
                $data['action_adicionar'] = base_url().$class.'/adicionar/'.$id_canal;
                $data['canal'] = $canal;
                $data['tipo'] = 'Canais Atributo Editar';//$data = $this->_init_selects();
                $data['item'] = $dados;
                $data['mostra_id'] = TRUE;
                $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                $this->layout
                        ->set_function( $function )
                        ->set_include('js/canais_atributo.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Canais', 'canais', 0)
                        ->set_breadscrumbs($canal->titulo.' - Atributos', 'canais', 1)
                        ->set_usuario($this->set_usuario())
                        ->set_menu($this->get_menu($class, $function))
                        ->view('add_canais_atributo',$data);
	}
        
        public function get_tipos($tipo = NULL)
        {
            if(isset($tipo) && $tipo)
            {
                $funcao = 'get_select_tipo_'.$tipo;
                $retorno = $this->canais_atributo_model->$funcao();
                echo json_encode($retorno);
            }
        }
        
        public function get_camadas()
        {
            $data = $this->_post();
            $retorno = '';
            if(isset($data['camada']) && isset($data['canal']))
            {
                sort($data['camada']);
                $camada = implode("", $data['camada']);
                $filtro = 'canais_atributo.id_canais in ('.$data['canal'].') AND canais_atributo.camada like "%'.$camada.'%"';
                $retorno = $this->canais_atributo_model->get_itens_por_canal($filtro);
            }
            echo json_encode($retorno);
        }
        
        public function get_elementos($id = NULL)
        {
            $retorno = '';
            if(isset($id) && $id)
            {
                $retorno = $this->canais_atributo_model->get_item($id);
            }
            echo json_encode($retorno);
        }
        
        public function editar_atributo()
        {
            $post =  $this->_post();
            $codigo = $post['id'];
            $data = array();
            foreach($post['campo'] as $key => $value) { $data[$value] = $post['valor'][$key]; }
            $retorno = $this->canais_atributo_model->editar($data, 'canais_atributo.id = '.$codigo);
            //$data =  array($post['name'] => $post['valor']);
            //$codigo = $post['id'];
            //echo $retorno;
        }
        
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
		$quantidade = $this->canais_atributo_model->excluir('canais_atributo.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}


