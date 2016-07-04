<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
//if ( session_status() == PHP_SESSION_NONE ) 
//{
//    session_start();
//}

require_once( APPPATH . 'libraries/Facebook/GraphObject.php' );
require_once( APPPATH . 'libraries/Facebook/GraphSessionInfo.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookSession.php' );
require_once( APPPATH . 'libraries/Facebook/Entities/AccessToken.php' );
require_once( APPPATH . 'libraries/Facebook/HttpClients/FacebookCurl.php' );
require_once( APPPATH . 'libraries/Facebook/HttpClients/FacebookHttpable.php' );
require_once( APPPATH . 'libraries/Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookResponse.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookSDKException.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookRequestException.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookAuthorizationException.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookRequest.php' );
require_once( APPPATH . 'libraries/Facebook/FacebookRedirectLoginHelper.php' );
 
use Facebook\GraphSessionInfo;
use Facebook\FacebookSession;
use Facebook\FacebookCurl;
use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookResponse;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphObject;

class Facebook_Lib
{
    private $CI;
    private $helper;
    private $session;
    
    private $appId = '706906052718408'; 
    private $appSecret = '4594ebac4d6775f6b6304e2d3f0b03b0'; 
    private $redirectUrl = 'http://www.isaojose.com/m/admin/facebook_groups/';//http://www.rededeportais.com.br/admin/facebook_groups;
    private $permissions = array('email', 'user_location','user_birthday');//array('email', 'user_location','user_birthday','user_groups','manage_pages','publish_actions');
    
    public function __construct() 
    {
        $this->CI =& get_instance();

        FacebookSession::setDefaultApplication( $this->appId, $this->appSecret );
        $this->helper = new FacebookRedirectLoginHelper( $this->redirectUrl );
        
        if ( $this->CI->session->userdata('fb_token') ) 
        {
            $this->session = new FacebookSession( $this->CI->session->userdata('fb_token') );
            try 
            {
                if ( ! $this->session->validate() ) 
                {
                    $this->session = false;
                }
            } 
            catch ( Exception $e ) 
            {
                $this->session = false;
            }
        }
        else 
        {
            try 
            {
                $this->session = $this->helper->getSessionFromRedirect();
            } 
            catch(FacebookRequestException $ex)
            {
            }
            catch(\Exception $ex) 
            {
            }
        }
        if ( $this->session ) 
        {
            $this->CI->session->set_userdata( 'fb_token', $this->session->getToken() );
            $this->session = new FacebookSession( $this->session->getToken() );
        }
    }
    
    public function get_login_url() 
    {
        return $this->helper->getLoginUrl( $this->permissions );
    }
    
    public function get_session()
    {
        return $this->session;
    }
 
    public function get_logout_url() 
    {
        if ( $this->session ) 
        {
            return $this->helper->getLogoutUrl( $this->session, site_url( 'logout' ) );
        }
        return false;
    }
    
    public function get_user() 
    {
        if ( $this->session ) 
        {
            try
            {
                $request = (new FacebookRequest( $this->session, 'GET', '/me' ))->execute();
                $user = $request->getGraphObject()->asArray();
                return $user;
            } 
            catch(FacebookRequestException $e) 
            {
                return false;
                //echo "Exception occured, code: " . $e->getCode();
                //echo " with message: " . $e->getMessage();
            }   
        }
    }
    
    public function get_groups()
    {
        if ( $this->session ) 
        {
            try
            {
                $request = (new FacebookRequest( $this->session, 'GET', '/me/groups' ))->execute();
                $groups = $request->getGraphObject()->asArray();
                return groups;
            } 
            catch(FacebookRequestException $e) 
            {
                return false;
            }   
        }
    }
  
   public function get_permissions()
   {
        if ( $this->session ) 
        {
            try
            {
                $request = (new FacebookRequest( $this->session, 'GET', '/me/permissions' ))->execute();
                $permissions = $request->getGraphObject()->asArray();
                return $permissions;
            } 
            catch(FacebookRequestException $e) 
            {
                return false;
            }   
        }
   }
    
    public function set_message($dados = array())
    {
        if(isset($dados) && $dados)
        {
            if ( $this->session ) 
            {
                try
                {
                    $request = (new FacebookRequest( $this->session, 'POST', '/me/feed', array(
                        'link' => $dados['link'],
                        'message' => $dados['message'] ,
                    ) ))->execute();
                    $message = $request->getGraphObject()->asArray();
                    return $message;
                } 
                catch(FacebookRequestException $e) 
                {
                    return false;
                }   
            }
        }
    }
}
*/

include_once APPPATH . 'libraries/src/facebook.php';

class Facebook_lib extends Facebook 
{
    
    //public $appId = '268663819977764';
    //public $secret = '337f22f9fd3dfe53e05b37e5bf70f40a';
    //private $redirectUri = 'http://www.rededeportais.com.br/admin/facebook_groups';
	 
    public $appId = '706906052718408';
    public $secret = '4594ebac4d6775f6b6304e2d3f0b03b0';
    public $redirectUri = 'http://www.isaojose.com/m/admin/facebook_groups/';
    
    private $CI;
    
    public function __construct($config = FALSE)
    {
        $this->CI =& get_instance();
        $config = array(
            'appId' => $this->appId,
            'secret' => $this->secret,
            'fileUpload' => TRUE,
            'allowSignedRequest' => FALSE 
        );
        parent::__construct($config);
    }
    
    private function login($code = '')
    {
        $retorno = FALSE;
        $token_url  = 'https://graph.facebook.com/oauth/access_token?';
        $token_url .= 'client_id='.$this->appId;
        $token_url .= '&redirect_uri='.urlencode($this->redirectUri);
        $token_url .= '&client_secret='.$this->secret.'&code='.$code;
        $response = curl_executavel($token_url);
        if($response)
        {
            $params = NULL;
            parse_str($response, $params);
            if(isset($params['access_token']) && $params['access_token'])
            {
                $this->CI->session->set_userdata('access_token',$params['access_token']);
                $graph_url = "https://graph.facebook.com/me?access_token=". $params['access_token'];
                $retorno = json_decode(curl_executavel($graph_url));
            }
        }
        return $retorno;
    }
    
    public function inicia()
    {
        if(isset($_GET['code']))
        {
            $this->login($_GET['code']);
        }
        else if(isset($_GET['error']))
        {
            echo 'recusou';
        }
        else
        {
            $params = array(
                'client_id' => $this->appId,
                'redirect_uri' => $this->redirectUri, 
                'scope' => 'email,user_website,user_location,user_birthday'
            );
            redirect($this->getLoginUrl($params));
            //redirect('https://www.facebook.com/dialog/oauth?client_id='.$this->appId.'&redirect_uri='.$this->redirectUri.'&scope=email,user_website,user_location');
        }
    }
    
    public function postar($itens = NULL, $tipo = NULL)
    {
        try
        {
            switch ($tipo)
            {
                case 0:
                            $dados = array( 'message' => $itens['mensagem'], 'name' => $itens['titulo'], 'link' => $itens['link'] );
                            foreach( $itens['fb_opcao'] as $group)
                            {
                                $post_data[$group] = $this->api('/' . $group . '/feed', 'POST', $dados);
                            }
                            $retorno = $post_data;
                            break;
                case 1:
                            if(isset($itens['foto']) && $itens['foto'])
                            {
                                $this->setFileUploadSupport(true);
                                $dados = array( 'message' => $itens['mensagem'], 'image' => '@' . realpath($itens['foto']) );
                                foreach ($itens['fb_opcao'] as $page)
                                {
                                    $dados['access_token'] = $this->_get_acess_token($page);
                                    $post_data[$page] = $this->api('/' . $page . '/photos', 'POST', $dados);
                                }
                            }
                            else
                            {
                                $dados = array( 'message' => $itens['mensagem'], );
                                foreach ($itens['fb_opcao'] as $page)
                                {
                                    $dados['access_token'] = $this->_get_acess_token($page);
                                    $post_data[$page] = $this->api('/' . $page .'/feed', 'POST', $dados);
                                }
                            }
                            $retorno = $post_data;
                            break;
                default:
                            $retorno = FALSE;
                            break;
            }
        }
        catch(FacebookApiException $e)
        {
            error_log($e->getType());
            error_log($e->getMessage());
            $retorno = $e;
        }
        return $retorno;
    }
    
    public function get_user()
    {
        try
        {
            $grupos = $this->api('me/');
            $retorno[] = $grupos;
        }
        catch(FacebookApiException $e)
        {
            error_log($e->getType());
            error_log($e->getMessage());
            $retorno = FALSE;
        }
        return $retorno;
    }
    
    public function get_permissions()
    {
        try
        {
            $grupos = $this->api('me/permissions');
            $retorno[] = $grupos;
        }
        catch(FacebookApiException $e)
        {
            error_log($e->getType());
            error_log($e->getMessage());
            $retorno = FALSE;
        }
        return $retorno;
    }
    
    public function get_groups()
    {
        try
        {
            $grupos = $this->api('me/groups');
            $retorno['grupos'] = $grupos;
            foreach ($grupos['data'] as $grupo)
            {
                $retorno['id'][] = $grupo['id'];
            }
        }
        catch(FacebookApiException $e)
        {
            error_log($e->getType());
            error_log($e->getMessage());
            $retorno = FALSE;
        }
        return $retorno;
    }

    public function get_pages($dados)
    {
        $retorno = NULL;
        if(isset($dados) && $dados)
        {
            try
            {
                foreach($dados as $id)
                {
                    $retorno[$id] = $this->api('/'.$id);
                }
            }
            catch(FacebookApiException $e)
            {
                error_log($e->getType());
                error_log($e->getMessage());
                $retorno = FALSE;
            }
        }
       
        return $retorno;
    }
    
    public function _get_acess_token($id)
    {
        if (isset($id) && ($id))
        {
            $access_token = $this->api(array(
                'method' => 'fql.query',
                'query' => 'SELECT access_token FROM page where page_id = ' . $id
            ));
            $retorno = (isset($access_token) && ($access_token)) ? $access_token[0]['access_token'] : FALSE;
        }
        else
        {
            $retorno = FALSE;
        }
        return $retorno;
    }
    
    public function get_extended_access_token($dados = NULL)
    {
        $retorno = NULL;
        if(isset($dados) && $dados)
        {
            foreach($dados as $item)
            {
                $FANPAGE_ID = $item->id;
                $this->setExtendedAccessToken();
                $accounts = $this->api('/me/accounts', 'GET');
                $page_access_token = null;
                foreach($accounts['data'] as $account)
                {
                    if($account['id'] == $FANPAGE_ID)
                    {
                        $page_access_token = $account['access_token'];
                    }
                }
                if($page_access_token !== null)
                {
                    $retorno[$item->id]['token_acesso'] = $page_access_token;
                }
            }
        }
        return $retorno;
    }
    
    
    public function montar_item($valor = NULL, $itens = NULL)
    {
        $retorno = '';
        if(isset($valor) && $valor)
        {
            $retorno .= '<div class="row alert alert-info">';
            $retorno .= '    <div class="col-lg-6">';
            $retorno .= '        <div class="form-group ">';
            $retorno .= '            <label for="tipo_select">Selecione '.  ucfirst($valor) .'</label>';
            $retorno .= '            <div class="controls ">';
            $config['valor'] =          $itens;
            $config['nome']  =          'tipo_select';
            $config['extra'] =          'class="form-control"';     
            $retorno .=                 form_select($config, set_value('tipo_select', isset($itens->id) ? $itens->id : '')); 
            $retorno .='             </div>';
            $retorno .='         </div>';
            $retorno .='     </div>';
            $retorno .='     <div class="col-lg-6">';
            $retorno .='          <label for="postar_selecionar"> Postar / Selecionar  </label>';
            $retorno .='          <select name="postar_selecionar" id="postar_selecionar" disabled class="form-control">';
            $retorno .='             <option title="selecione" value="0" selected="selected">Selecione..</option>';
            $retorno .='             <option value="postar_'.$valor.'" title="postar_'.$valor.'">Postar em todos os grupos dos(as) '.ucfirst($valor).'</option>';
            $retorno .=              $this->tratar_option($valor);
            $retorno .='          </select>';
            $retorno .='     </div>';
            $retorno .='     <div class="col-lg-8">';
            $retorno .='         <div class="form-group " id="valores"></div>';
            $retorno .='     </div>'; 
            $retorno .='</div>';
        }
        return $retorno;
    }
    
    private function tratar_option($valor = NULL)
    {
        switch ($valor)
        {
            case 'estados';
                $valor ='<option value="selecionar_cidade" title="selecionar_cidade">Selecionar Cidade</option>';
                break;
            case 'facebook_categorias';
                $valor ='<option value="selecionar_categoria" title="selecionar_categoria">Selecionar Grupos da Categoria</option>';
                break;
            default :
                $valor ='';
                break;
        }  
        return $valor;
    }
    
    public function montar_checks($valor = NULL)
    {
         if(isset($valor) && $valor)
         {
             $dados = $valor;
             $config['valor'] = $dados; 
             $config['nome'] = 'fb_opcao'; 
             $config['extra'] = 'col-lg-3'; 
             $retorno = form_checkbox_($config, set_value('fb_opcao', array())); 
         }
        else 
        {
            $retorno = NULL;
        }
        return $retorno;
    }
    
    public function montar_select_cidade($valor = NULL)
    {
         if(isset($valor) && $valor)
         {
             $dados = $valor;
             $config['valor'] = $dados; 
             $config['nome'] = 'id_cidade'; 
             $config['extra'] = 'class="form-control"'; 
             $retorno = form_select($config, set_value('id_cidade', isset($item->id) ? $item->id : '')); 
         }
        else 
        {
            $retorno = NULL;
        }
        return $retorno;
    }
}