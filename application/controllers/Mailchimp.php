<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailchimp extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'options[title]',            'label'   => 'Titulo da Campanha', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'options[from_name]',           'label'   => 'Nome do Remetente', 		'rules'   => 'required|trim'),
                                array( 'field'   => 'options[from_email]',            'label'   => 'Email do Remetente', 		'rules'   => 'required|trim|valid_email'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->library('mailchimp_lib');
	}
	
	public function index()
	{
            $this->campanha();
	}
	
	public function campanha()
	{
		$this->form_validation->set_rules($this->valida); 
		if  ( $this->form_validation->run() )
		{
			$data = $this->_post();
			//redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
		}
		else
		{
			$function = strtolower(__FUNCTION__);
			$class = strtolower(__CLASS__);
			$data = $this->_inicia_select();
			$data['action'] = base_url().$class.'/'.$function;
			$data['tipo'] = 'MailChimp Campanha Adicionar';	
			$this->layout
				->set_function( $function )
				->set_include('js/mailchimp.js', TRUE)
				->set_include('css/estilo.css', TRUE)  
                                ->set_breadscrumbs('Painel', 'painel',0)
                                ->set_breadscrumbs('Mailchimp - Campanhas', 'mailchimp', 0)
                                ->set_breadscrumbs('Adicionar', 'mailchimp/adicionar', 1)
				->set_usuario($this->set_usuario())
                                ->set_menu($this->get_menu($class, $function))
				->view('add_mailchimp_campanha',$data);
		}   
		 
	}
        
        private function _inicia_select()
        {
            $retorno['lists'] = $this->mailchimp_lib->get_lists();
            return $retorno;
        }
        
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
		return $data;
	}
}


