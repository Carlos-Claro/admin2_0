<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ocorrencias 
{
        
        private $CI = NULL;
	
        /**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
            $this->CI =& get_instance();
            if ( $config )
            {
                $this->inicia($config);
            }
	}
        
        public function get_ocorrencia()
        {
            $this->CI->load->model(array('pow_cargos_model', 'usuarios_model'));
            $data['setores'] = $this->CI->pow_cargos_model->get_select();
            $data['usuarios'] = $this->CI->usuarios_model->get_select('usuarios.ativo = 1');
            $retorno = $this->CI->load->view('view_ocorrencia',$data, TRUE);
            return $retorno;
        }
        
        public function get_interacoes_ocorrencia($id_empresa = NULL)
        {
            $retorno  = NULL;
            if(isset($id_empresa) && $id_empresa)
            {
                $this->CI->load->model('ocorrencias_model');
                $data['itens'] = $this->CI->ocorrencias_model->get_itens('empresas_ocorrencia.id_empresa = '.$id_empresa);
                $retorno = $this->CI->load->view('view_interacao',$data, TRUE);
            }
            return $retorno;
        }
}