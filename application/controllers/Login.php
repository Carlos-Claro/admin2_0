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
class Login extends MY_Controller {
	/**
	 * 
	 * Variavel de verificação de formulário caso não html5
	 * @var valida_login
	 */
	private $valida_login = array(
                                        //array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required|valid_email'),
                                        array( 'field'   => 'email', 'label'   => 'E-mail', 'rules'   => 'required'),
                  			array( 'field'   => 'senha', 'label'   => 'Senha', 'rules'   => 'required|min_length[5]')
                                        );
	
	public function __construct()
	{
            parent::__construct(FALSE);
            $this->load->model('login_model');
            $this->load->model('usuario_model');
            //$this->load->library('facebook_lib');
	}
	
	public function index()
	{
            //redirect('http://www.guiasjp.com/admin2_0/');
            
            if(isset($this->session->userdata['login']))
            {
                redirect('painel/index');
            }
            else
            {
                $this->login();
            }
	}
	
	public function login()
	{
            $this->form_validation->set_rules($this->valida_login); 
            if  ( $this->form_validation->run() )
            {
                $email = $this->input->post('email', TRUE);
                $senha = $this->input->post('senha', TRUE);
                $login_v = $this->login_model->verifica($email, $senha);
                if( $login_v )
                {
                    if ( $login_v->ativo == 1 )
                    {
                        $sessao = array(
                                        'id' 		=> $login_v->user,
                                        'usuario'  	=> $login_v->nome,
                                        'login' 	=> TRUE,
                           );
                        $this->session->set_userdata($sessao);	
                        redirect('painel','refresh');

                    }
                    else
                    {
                        $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
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
                else
                {
                    $login_c = $this->login_model->verifica_empresa($email, $senha);
                    if( $login_c )
                    {
                        if ( isset($login_c->id_empresa) && $login_c->id_empresa )
                        {
                            $sessao = array(
                                            'id_empresa'     => $login_c->id_empresa,
                                            'id_user'        => $login_c->user,
                                            'usuario'   => $login_c->nome,
                                            'tipo'   => 'empresa',
                                            'login' 	=> TRUE,
                            );
                            $this->session->set_userdata($sessao);	
                            redirect('empresas_direto/editar/'.$login_c->id_empresa,'refresh');

                        }
                        else
                        {   
                            $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
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
                    else
                    {
                        $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
                        $data['erro'] = array( 'class' => 'text-danger', 'texto' => 'E-mail ou senha inválidos.');
                        $this->layout
                                ->set_titulo('Login')
                                ->set_keywords('')
                                ->set_description('')
                                ->set_include('js/login.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->view('login', $data, 'layout/sem_menu'); 
                    }
                }
            }
            else
            {
                $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
                $data['erro'] = array( 'class' => 'text-info', 'texto' => 'Preencha seus dados de acesso.<br/><br/>');
                $this->layout
                                ->set_titulo('Login')
                                ->set_keywords('')
                                ->set_description('')
                                ->set_include('js/login.js', TRUE)
                                ->set_include('css/estilo.css', TRUE)
                                ->view('login', $data, 'layout/sem_menu'); 
            }
	}

	public function esqueceu(  )
	{
            $email = $this->input->post('email', TRUE);
            if ( isset($email) && $email )
            {
                $this->load->helper('email');
                if ( valid_email($email) )
                {
                    $user = $this->login_model->esqueceu_senha($email);
                    if ( $user && $user->ativo > 0 )
                    {
                        $login = $user->email;
                        $senha = $this->_gera_senha();
                        $this->usuario_model->altera_senha($user->id, md5( $senha['bd'] ) );

                        $this->load->library('email');
                        $this->email->from('ll@pow.com.br', 'PowImoveis');
                        $this->email->to($email); 

                        $this->email->subject('Recuperar Senha');
                        $mensagem = 'Olá, '.$user->nome.PHP_EOL;
                        $mensagem .= PHP_EOL.'O sistema gerou uma nova senha de acesso para você, Guarde-a em um lugar seguro.'.PHP_EOL;
                        $mensagem .= PHP_EOL.'Login: '.$user->email.PHP_EOL;
                        $mensagem .= 'Senha: '.$senha['user'].PHP_EOL;
                        $mensagem .= PHP_EOL.'Obrigado por usar nosso painel.'.PHP_EOL.'Att.'.PHP_EOL.'Administrador.';
                        $this->email->message($mensagem);	
                        $this->email->send();

                        echo '<p class="text-success">Sua senha foi alterada e enviada para seu e-mail com sucesso.</p>';

                    }
                    else
                    {
                        echo '<p class="text-warning">Usuário inativo ou inexistente, contate o administrador.</p>';
                    }
                }
                else
                {
                    echo '<p class="text-error">E-mail Inválido!</p>';
                }
            }
            else
            {
                echo '<p class="text-warning">Preencha o campo e-mail!</p>';
            }
	}
	
        public function get_senha_empresa()
        {
            $cnpj = $this->input->post('cnpj', TRUE);
            if ( isset($cnpj) && $cnpj && ! empty($cnpj) )
            {
                $this->load->model('empresas_model');
                $filtro = 'empresas.inscricao = "'.$cnpj.'"';
                $empresa = $this->empresas_model->get_item_cadastro($filtro);
                if ( $empresa )
                {

                    $this->load->library('email');
                    $this->email->from('ll@pow.com.br', 'PowImoveis');
                    $this->email->to($empresa->autorizador_email); 

                    $this->email->subject('Recuperar Senha, Guiasjp / Portais imobiliários');
                    $mensagem = 'Olá, '.$empresa->autorizador_nome.PHP_EOL;
                    $mensagem .= PHP_EOL.'O sistema gerou uma nova senha de acesso para você, Guarde-a em um lugar seguro.'.PHP_EOL;
                    $mensagem .= PHP_EOL.'Login: '.$empresa->inscricao.PHP_EOL;
                    $mensagem .= 'Senha: '.$empresa->senha.PHP_EOL;
                    $mensagem .= PHP_EOL.'Obrigado por usar nosso painel.'.PHP_EOL.'Att.'.PHP_EOL.'Administrador.';
                    $this->email->message($mensagem);	
                    $this->email->send();
                    $email_mescla = $this->mescla_email($empresa->autorizador_email);
                    echo '<p class="text-success">Sua senha foi enviada para seu e-mail: '.$email_mescla.', com sucesso. Verifique sua conta.</p>';

                }
                else
                {
                    echo '<p class="text-warning">Usuário inativo ou inexistente, contate o administrador.</p>';
                }
            }
            else
            {
                echo '<p class="text-warning">Preencha o campo login!</p>';
            }
        }
        
        public function mescla_email( $email )
        {
            $partes_email = explode('@', $email);
            $qtde_parte1 = strlen($partes_email[0]);
            switch ( $qtde_parte1 )
            {
                case 1:
                    $mescla_parte1 = '*';
                    break;
                case 2:
                    $mescla_parte1 = substr($partes_email[0], 0, -1).'*';
                    break;
                case 3:
                    $mescla_parte1 = substr($partes_email[0], 0, -2).'**';
                default:
                    $qtde = ceil($qtde_parte1/2);
                    
                    $mescla_parte1 = str_pad(substr($partes_email[0], 0, -($qtde) ), $qtde_parte1, "*", STR_PAD_RIGHT);
                    break;
            }
            
            return $mescla_parte1.'@'.$partes_email[1];
        }
        
        public function esqueceu_senha_empresa()
        {
            $data['action'] = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
            $data['erro'] = array( 'class' => 'text-info', 'texto' => 'Preencha seus dados de acesso.<br/><br/>');
            $this->layout
                            ->set_titulo('Login')
                            ->set_keywords('')
                            ->set_description('')
                            ->set_include('js/esqueceu_senha_empresa.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->view('esqueceu', $data, 'layout/sem_menu'); 
        }
        
	private function _gera_senha(  )
	{
		$senha['user'] = $this->array_padrao(date('j')).$this->array_padrao(date('N')).date('N').$this->array_padrao(date('m')).$this->array_padrao(date('t')).date('i');
		$senha['bd'] = $senha['user'];
		return $senha;		
	}
	
	public function logout()
	{
		$this->session->unset_userdata('login');
                //$this->facebook_lib->destroySession();
		redirect('login/index/');
	}	
	
}
