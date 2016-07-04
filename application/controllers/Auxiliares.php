<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auxiliares extends MY_Controller 
{

	public function __construct()
	{
            parent::__construct();
            //$this->load->model(array(''));
	}
	
	public function index()
	{
            $this->listar();
	}
        
        public function editorias()
        {
            redirect('editorias');
        }
	
        public function onibus_linhas()
        {
            redirect('onibus_linhas');
        }
        
        public function cargos()
        {
            redirect('pow_cargos');
        }
        
        public function cadastros_guiasjp()
        {
            redirect('cadastros');
        }
        
        public function email_automatico()
        {
            redirect('email_automatico');
        }
        
        public function empresas_contato()
        {
            redirect('empresas_contato');
        }
	
}
