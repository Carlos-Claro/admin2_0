<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Classe que gerencia o login no sistema, com verificação de status e hierarquia.
 * @access public
 * @version 0.1
 * @author Carlos Claro
 * @package Login
 * 
 */
class Anuncios extends MY_Controller {
	/**
	 * 
	 * Variavel de verificação de formulário caso não html5
	 * @var valida_login
	 */
	private $valida_anuncio = array(
                                        //array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required|valid_email'),
                                        array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required'),
                  			array( 'field'   => 'senha', 'label'   => 'Senha', 'rules'   => 'required|min_length[5]')
                                        );
	
	public function __construct()
	{
		parent::__construct(FALSE);
	}
	
	public function index()
	{
            $this->produtos();
        }
        
        public function produtos()
        {
            $data['erro'] = array('class' => 'text-warning', 'texto' => 'Usuário Inativo, Contate o administrador para liberação do acesso.');
            $this->layout
                    ->set_titulo('Login')
                    ->set_keywords('')
                    ->set_description('')
                    ->set_include('js/login.js', TRUE)
                    ->set_include('css/estilo.css', TRUE)
                    ->view('login', $data, 'layout/sem_menu'); 
        }
        
}