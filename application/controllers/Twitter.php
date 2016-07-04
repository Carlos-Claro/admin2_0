<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( APPPATH . 'libraries/twitteroauth/OAuth.php' );
require_once( APPPATH . 'libraries/twitteroauth/twitteroauth.php' );

class Twitter extends MY_Controller
{
    /*
     * @author Breno Henrique Moreno Nunes
     * @property string representa a chave necessaria para validação e  obtenção de token.
     */
    private $consumer_key = 'GAV00A8EO0Ki8ev9QLcyyfSX8';
    
    /*
     * @author Breno Henrique Moreno Nunes
     * @property string representa o segredo necessario para validação e obtenção de token.
     */
    private $consumer_secret = 'JBzkKftUpoTpq2Bggh8UJIX3t7jT74gOxVa1fEWNjbpPuJar5o';
    
    /*
     * @author Breno Henrique Moreno Nunes
     * @property string representa a url de retorno após a autenticação do twitter.
     */
    private $oauth_callback = 'http://www.isaojose.com/m/admin/twitter/callback/';
    
    
    /*
     * @author Breno Henrique Moreno Nunes
     * @Função construtora da classe.
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->model(array('cadastros_model'));
    }
    
    public function index()
    {
        
    }
    
    /*
     * Função que utiliza a chave e o segredo da classe para realizar a autenticação 
     * do usuário.Recupera e Salva tokens temporários em sessões. Caso tenha sucesso
     * monta a url de autorização e redireciona o usuário para a página de autorização do twitter. 
     * Em caso de falha redireciona o usuario a outra tela.
     * 
     * @author Breno Henrique Moreno Nunes
     * @since 1.0 (16/10/2014)
     * @return void
     */
    public function inicia()
    {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
        
        $request_token = $connection->getRequestToken($this->oauth_callback);
        
        $twitter_tokens = array(
            'oauth_token' => $request_token['oauth_token'],
            'oauth_token_secret' => $request_token['oauth_token_secret'],
        );
        $this->session->set_userdata($twitter_tokens);
        
        $token = $request_token['oauth_token'];
        
        switch ($connection->http_code) 
        {
            case 200:
                $url = $connection->getAuthorizeURL($token);
                redirect($url);
                break;
            default:
                redirect(strtolower(__CLASS__).'/index');
                //echo 'Não foi possível conectar-se ao twitter.';
                break;
         }
    }
    
    
    /*
     * Função que utiliza a chave, o segredo, e os tokens temporários salvos em sessão 
     * para realizar a autorização do usuario. Requisita os tokens que vão permitir o acesso do usuário,
     * passando o verificador retornado pelo twitter. 
     * Salva tokens de acesso na sessão.Remove os tokens temporários.
     * Caso tenha sucesso redireciona o usuário para a página principal. 
     * Em caso de falha limpa o conteudo das sessões e redireciona para a página do painel.
     * 
     * @author Breno Henrique Moreno Nunes
     * @since 1.0 (16/10/2014)
     * @return void
     */
    public function callback()
    {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->session->userdata('oauth_token'), $this->session->userdata('oauth_token_secret'));
        if(!isset($_GET['denied']))
        {
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
        
            $twitter_tokens = array('access_token' => $access_token);
            $this->session->set_userdata($twitter_tokens);

            $this->session->unset_userdata('oauth_token');
            $this->session->unset_userdata('oauth_token_secret');

            if (200 == $connection->http_code)
                redirect(strtolower(__CLASS__).'/principal/');
            else
                redirect(strtolower(__CLASS__).'/clear_sessions/');
        }
        else 
        {
            redirect('painel');
        }
    }
    
    /*
     * Função de exemplo que utiliza a api do twitter para recuperar os seus seguidores
     * e retornar no máximo 5. É possivel tambem verificar se a aplicação possui
     * permissão para determinada ação.
     * 
     * @author Breno Henrique Moreno Nunes
     * @since 1.0 (16/10/2014)
     * @return void
     */
    public function principal()
    {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret,  $this->session->userdata['access_token']['oauth_token'],$this->session->userdata['access_token']['oauth_token_secret']);
        //Pegar os dados do usuario logado.
        $user = $connection->get('account/verify_credentials');
        //pegar os twits mais recentes enviados por usuario autenticado
        $post = $connection->get('direct_messages/sent');
        if(!isset($post->errors) && !$post->errors)
        {
            var_dump($post);
            /*
            $data_user = array(
                'data' => strtotime(date('Y-m-d H:i:s')),
                'nome' => $user->name,
                'datatu' => date('Y-m-d H:i:s'),
            );
            $this->cadastros_model->adicionar($data_user);
            */
            echo '<a href="https://twitter.com/sjp_pr" class="twitter-follow-button" data-show-count="false" data-lang="pt">Seguir @sjp_pr</a><br><br>';
            echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
            echo '<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://twitter.com/guiasjp" data-text="Compartilhe o GuiaSJP" data-via="sjp_pr" data-lang="pt" data-size="large">Tweetar</a></br><br>';
            echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
            echo '<a class="twitter-timeline"  href="https://twitter.com/sjp_pr" data-widget-id="522816392525914112">Tweets de @sjp_pr</a>';
            echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";
        }
        else
        {
            echo '<script type="text/javascript">alert("Você não habilitou a permissão necessária para continuar o processo.")</script>';
            //$this->inicia();
        }
    }


    /*
     * Função que limpa os dados da sessão e redireciona para a tela do painel.
     * (Equivale ao Logout)
     * 
     * @author Breno Henrique Moreno Nunes
     * @since 1.0 (16/10/2014)
     * @return void
     */
    public function clear_sessions()
    {
        $this->session->unset_userdata('access_token');
        redirect('painel');
    }
}
