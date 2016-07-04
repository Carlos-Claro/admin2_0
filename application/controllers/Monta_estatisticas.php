<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monta_estatisticas extends MY_Controller 
{
        /**
         * Cria um array para validar a pagina com os campos necessarios do formulario 
         * @var array
         */
	private $valida = array(
                                //array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                                //array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                                //array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                                //array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                                );
        
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e librarys padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct(TRUE, FALSE, 'rede');
	}
        
        
        
	public function index()
	{
            echo 'teste';
            $this->load->model('reserva_log_model');
            var_dump($this->reserva_log_model->get_itens());
            //redirect('painel');
	}
	
	
}
