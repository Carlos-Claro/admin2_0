<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Controller extende CI_Controller para usar suas libraries
 * @access public
 * @version 1.0
 * @author 
 * @package MY_Controller
 */
class MY_Controller extends CI_Controller 
{
    /**
     * @access public
     * @var null sessao 
     */
    public $sessao = NULL;
    
    /**
     * valida_login, grava_log quando true
     * carrega library necessarias para o sistema 
     * gravação de log quando necessario
     * carrega model necessario para o sistema  
     * gravação de log quando necessario
     * requisita conexão com banco de dados
     *  
     * @param boolean $verifica_login
     * @param boolean $log 
     * @version 1.0
     * @access public
     * @author pow internet carlos claro
     */
    public function __construct( $verifica_login = TRUE, $log = FALSE, $db = 'guiasjp' ) 	
    {		
        parent::__construct();		
        $this->load->library('layout');		
        $this->load->library('listagem');		
        $this->load->library('pagination');		
        $this->load->library('email');
        $this->load->library('menu');
        $this->load->model(array('usuario_model'));
        date_default_timezone_set('America/Sao_Paulo');
        if ( $db )
        {
            //$this->requisita_bd_sessao($db);		
        }
        if ( $verifica_login )		
        {			
            $this->_valida_login();		
        }
        if ( $log )
        {
            $this->_grava_log();	
        } 
        if ( isset($_GET['debug']) )
            {
                error_reporting(-1);
		ini_set('display_errors', 1);
            }
    }	
    
    /**
     * grava log no banco de dados com base em post e get request
     * @version 1.0
     * @access private
     * @author Carlos Claro
     */
    private function _grava_log()	
    {		
        $get = $this->_get_post( isset($_GET) ? $_GET : '' );		
        $post = $this->_get_post( isset($_POST) ? $_POST : '' );
        $sesion = $this->_get_post( isset($this->sessao) ? $this->sessao : '' );
        $log = array(
            'ip' 			=> $_SERVER['REMOTE_ADDR'],					
            'url' 			=> ( isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'] ),					
            'getpost'                   => ( ! empty($get) ? 'get: '.$get : '' ) . ( ! empty($post) ? ' post: '.$post : '' ) . ( ! empty($sesion) ? ' sessao: '.$sesion : '' ),					
            'dt'			=> date('Y-m-d H:i:s'),					
            'user_agent'                => $_SERVER['HTTP_USER_AGENT'],		
            );		
        //$this->db->insert('log', $log);	
    }
    
    public function trabalhando( )
    {
        
    }
    
    /**
     * auxiliar da function _grava_log e retorna os get e post requisitados
     * 
     * @param string $tipo
     * @return string 
     * @version 1.0
     * @access private
     * @author 
     */
    private function _get_post( $tipo = NULL )	
    {		
        $retorno = '';
        if ( isset($tipo) && ! empty($tipo) )
        {
            foreach ( $tipo as $c => $v )		
            {			
                if ( is_array($v) )			
                {				
                    foreach ( $v as $key => $value )				
                    {					
                        $retorno .= ', ['.$c.'] ['.$key.'] => '.$value;				
                    }			
                }			
                else			
                {				
                    $retorno .= ', ['.$c.'] => '.$v;			
                }		 
            }
        }
        return $retorno;	
    }
    
    public function set_setor_usuario( $classe )
    {
        $this->load->model(array('usuario_model','setores_model'));
        $filtro = 'classe = "'.$classe.'"';
        $setor = $this->setores_model->get_item_por_filtro($filtro);
        $retorno['status'] = FALSE;
        $retorno['edita'] = FALSE;
        if ( isset($setor->id) )
        {
            $filtro_usuarios = 'id_usuario = '.$this->get_id_usuario().' AND id_setor = '.$setor->id;
            $usuarios = $this->usuario_model->get_usuarios_has_setores($filtro_usuarios);
            if ( isset($usuarios) && $usuarios['qtde'] > 0 )
            {
                $retorno['status'] = TRUE;
                $retorno['edita'] = $usuarios['itens'][0]->edita;
            }
            else
            {
                redirect(base_url().'painel/?inacessivel='.  urlencode($classe));
                exit();
            }
            
        }
        return $retorno;
        
    }
    
    /**
     * Validação de login
     * set sessao para validar login
     * redireciona url para login/logout se não tiver sessao e faz um refresh
     * @version 1.0
     * @access private
     * @author 
     */
    private function _valida_login()	
    {		
        $this->sessao = ( $this->session->userdata('login') ) ? $this->session->all_userdata() : NULL;	
        if( ! isset($this->sessao['login'])  )		
        {			
            redirect(base_url().'login/logout','refresh');			
        }	
    }
    
    /**
     * iniciação da paginação
     * criando um array para inicializar a paginação
     * @param int $total_itens 
     * @param string $url base da url
     * @return string - link da paginação
     * @version 1.0
     * @access public
     * 
     */
    public function init_paginacao($total_itens = 0, $url) 
    {
        $config = array(			
            'page_query_string'         => TRUE,			
            'base_url' 			=> $url,			
            'total_rows' 		=> $total_itens,			
            'per_page' 			=> N_ITENS,			
            'next_link'			=> '<span class="btn btn-primary glyphicon glyphicon-forward"></span>',			
            'prev_link'			=> '<span class="btn btn-primary glyphicon glyphicon-backward"></span>',
            'use_page_numbers'          => FALSE,			
            'num_tag_open'		=> '<span class="btn btn-primary" >',			
            'num_tag_close'		=> '</span>',
            'cur_tag_open'              => '<span class="btn btn-info">',
            'cur_tag_close'             => '</span>'
            );
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
    
    /**
     * carregando uma requisição de conexão com o banco de dados
     * @version 1.0
     * @access public
     * @author 
     */
    public function requisita_bd_sessao($db) 
    {
        $this->load->database($db);
        /*
        $table = $this->db->list_tables();
        var_dump($table);die();
         * 
         */
    }
    
    /**
     * criando um array onde numeros recebem letras
     * @param string $data 
     * @return array 
     * @version 1.0
     * @access public
     * @author 
     */
    public function array_padrao( $data )	
    {	
        $retorno = array(0 => 'z', 1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'i', '00' => 'z', '01' => 'a', '02' => 'b', '03' => 'c', '04' => 'd', '05' => 'e', '06' => 'f', '07' => 'g', '08' => 'h', '09' => 'i', 10 => 'j', 11 => 'k', 12 => 'l', 13 => 'm', 14 => 'n', 15 => 'o', 16 => 'p', 17 => 'q', 18 => 'r', 19 => 's', 20 => 't', 21 => 'u', 22 => 'v', 23 => 'x', 24 => 'z', 25 => 'q',	26 => 'r', 27 => 's', 28 => 't', 29 => 'u', 30 => 'v', 31 => 'x');		
        return $retorno[$data];			
        
    }
    
    /**
     * set usuario onde return carrega sessao['usuario'] que é o nome do usuario
     * @return string
     * @version 1.0
     * @access public
     */		
    public function set_usuario()		
    {				
        return $this->sessao['usuario'];		
        
    }
    
    /**
     * id_usuario onde return retorna sessao['id']
     * @return string 
     * @version 1.0
     * @access public
     */
    public function get_id_usuario()
    {
        return $this->sessao['id'];
    }
    
    /**
     * envio de email com seus respectivos campos, e anexos
     * [assunto]
     * [mensagem]
     * [email] -> from
     * [to] -> array ou separado por ,
     * [bcc]
     * [anexo] -> endereco fisico do arquivo
     * @param array $data  com os campos necessários para envio do email usando o codeigniter
     * @return bollean - se enviou corretamente true
     * @version 1.0
     * @access public
     */
    public function envio( $data )	
    {	
        $this->email->clear();
        if ( ! LOCALHOST )
        {
            
            if ( ! isset($data['iagente']) )
            {
                $config['mailtype'] = 'html';
                $config['useragent'] = 'GuiaSJP';
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.pow.com.br';
                $config['smtp_user'] = 'autenticacao@pow.com.br';
                $config['smtp_pass'] = 'c2a0r1l2';
                $config['smtp_port'] = '587';
            }
            else
            {
                $config['mailtype'] = 'html';
                $config['useragent'] = 'GuiaSJP';
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp1.corporate-mail-us.com';
                $config['smtp_user'] = 'programacao@pow.com.br';
                $config['smtp_pass'] = 'powi2015';
                $config['smtp_port'] = '587';
            }
            $mail = $this->email->initialize($config);
            $subject = (isset($data['assunto']) && $data['assunto']) ? $data['assunto'] : 'GuiaSJP';		
            $mensagem = (isset($data['mensagem']) && $data['mensagem']) ? $data['mensagem'] : '';
            $from = (isset($data['email']) && $data['email']) ? $data['email'] : 'programacao@pow.com.br';
            $to = (isset($data['to']) && $data['to']) ? $data['to'] : 'programacao@pow.com.br';
            $bcc = (isset($data['bcc']) && $data['bcc']) ? $data['bcc'] : '';
            $this->email
                    ->from($from)
                    ->to($to)
                    ->bcc($bcc)
                    ->subject($subject)
                    ->message($mensagem);
            if( isset($data['anexo']) && !empty($data['anexo']) )
            {
                $this->email->attach($data['anexo']);
            }
            $send['status'] = $this->email->send();
            if ( isset($data['retorno']) && $data['retorno'] )
            {
                $send['debugger'] = $this->email->print_debugger();
                $retorno = $send;
            }
            else
            {
                $retorno = $send['status'];
            }
        }
        else
        {
            $retorno = array();
        }
        return  $retorno;
    }
    
    /**
     * upload de imagens, se formato existir salvar em uma pastar e dar um nome
     * @param array $file arquivo a ser carregado
     * @param string $dir diretorio da imagem
     * @param string $replace subistitui a imagem pela carregada
     * @return boolean
     * @version 1.0
     * @access public
     */
    public function upload( $file, $dir = 'images/upload/', $replace = NULL )	
    {	
        if( $file['name'] )		
        {
            if(preg_match("/\.(jpg|png|gif|JPG|PNG|pdf|doc){1}$/i", $file['name'][0], $ext))
            {
                $nome = explode('.', $file['name'][0]);			
                $name = tira_acento($nome[0]).date('Mhis').'.'.$ext[1];
                $imagem_dir = $dir;
                $imagem_dir = getcwd().'/'.$dir;
                $imagem_dir = str_replace('/admin2_0', '',$imagem_dir);
                if(isset($replace) && $replace)
                {
                    $imagem_dir = str_replace('[id]', $replace, $imagem_dir);
                }
                $pasta = $imagem_dir;
                if(!is_dir($pasta))
                {
                    mkdir($pasta, 0777, TRUE);
                }
//                else
//                {
//                    chmod($pasta, 0777);
//                }
                $imagem_dir .= $name;
                $data = (move_uploaded_file( $file['tmp_name'][0], $imagem_dir)) ? array('name' => $name, 'pasta' => $dir ) : NULL ;
            }
        }
        else		
        {			
            $data = NULL;		
        }		
        return $data;
    }	
    
    /**
     * 
     * @param string $classe
     * @param string $function
     * @return string 
     */
    public function get_menu ( $classe, $function )
    {
        $filtro = 'usuarios.id = '.$this->session->userdata('id');
        $itens = $this->usuario_model->get_itens_menu($filtro);
        if ( count($itens['itens']) > 0 )
        {
            $data['itens'] = $itens['itens'];
            $data['selecionado'] = array('classe' => $classe, 'function' => $function);
            $retorno = $this->menu->inicia($data);
        }
        else
        {
            $retorno = '';
        }
        return $retorno;
    }
    /**
     * Tratar as datas em 3 opções diferentes
     * date:"02/02/2002", date_time:"02/02/2002 14:00" e
     * date_time_full:"02/02/02 14:00:00"
     * @param array $datas
     * @param string $options
     * @param array $delimitador
     * @return array 
     * @version 1.0
     * @access public
     * @author Breno[programacao01@pow.com.br]
     */
    public function tratar_datas($datas = array(), $options = '', $delimitador = array())
    {
        $retorno = NULL;
        if(isset($datas) && $datas)
        {
            foreach($datas as $chave => $valor)
            {
                if(isset($valor) && $valor)
                {
                    $exp_a = explode($delimitador[0], $valor);
                    switch($options)
                    {
                        case 'date': // Ex.: 02/02/2002
                            $retorno[$chave] = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                            break;
                        case 'date_time': // Ex.: 03/03/2003 15:00
                            $exp_b = explode($delimitador[1], $exp_a[2]);
                            $retorno[$chave] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1].':00';
                            break;
                        case 'date_time_full': // Ex.: 05/05/2005 17:00:00
                            $exp_b = explode($delimitador[1], $exp_a[2]);
                            $retorno[$chave] = $exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1];
                            break;
                    }
                }
                else 
                {
                    $retorno[$chave] = NULL;
                }
            }
        }
        return $retorno;
    }
 
    /**
     * Monta um link com base no POST da pagina requisitada e da classe matriz
     * @param string $classe - ajuda a montar os models, pesquisa e retorno
     * @return string $retorno com o link montado
     */
    public function monta_link($classe = NULL)
    {
        $model = $classe.'_model';
        $this->load->model($model);
        $valor = $this->input->post('valor');
        $id = $this->input->post('id');
        $retorno = NULL;
        $max_id = $this->{$model}->get_max_id();
        if(isset($valor) && $valor)
        {
            //$link = str_replace(' ','+',$valor);
            $link = tira_acento($link);
            
            $filtro = $classe.'.link like "'.$link.'" ';
            if ( ! empty($id) )
            {
                $filtro .= 'AND '.$classe.'.id = '.$id.' ';
            }
            $data = $this->{$model}->get_select($filtro);
            $qtde = count($data);
            if( $qtde > 0)
            {
                $retorno = $link.substr( md5( time() ), 0, 5);
                //$retorno = $link.'_'.$id;
            }
            else
            {
                $retorno = $link;
                //$retorno = $link.'_'.($max_id->id + 1);
            }
        }
        return $retorno;
    }
    
    /**
     * 
     * Função que carrega os arquivo js necessario para iniciar o ckeditor juntamente com o ckfinder,
     * e transforma o campo com o id passado por parametro em um campo de texto rico para edição 
     * e upload de imagens.
     * 
     * @author Breno Henrique Moreno Nunes
     * @param string $id
     * @return array|boolean
     */
    public function inicia_ckeditor($id = '')
    {
        $retorno = NULL;
        if(isset($id) && !empty($id))
        {
            $this->load->helper('ckeditor'); 
            $data['ckeditor'] = array
            (
                //id da textarea a ser substituída pelo CKEditor
                'id'   => $id,

                // caminho da pasta do CKEditor relativo a pasta raiz do CodeIgniter
                'path' => 'js/ckeditor',

                // configurações opcionais
                'config' => array
                (
                    'toolbar' => "Full",
                    'width'   => "100%",
                    'height'  => "15%",
                    'filebrowserBrowseUrl'      => base_url().'js/ckeditor/ckfinder/ckfinder.html',
                    'filebrowserImageBrowseUrl' => base_url().'js/ckeditor/ckfinder/ckfinder.html?Type=Images',
                    'filebrowserFlashBrowseUrl' => base_url().'js/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                    'filebrowserUploadUrl'      => base_url().'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                    'filebrowserImageUploadUrl' => base_url().'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                    'filebrowserFlashUploadUrl' => base_url().'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                )
            );
            $retorno = $data['ckeditor'];
        }
        return $retorno;
    }
    
    /**
     * carrega pasta de destino de imagem
     * @param string $pasta pasta onde a imagem esta localizada
     * @return string $retorno
     * @version 1.0
     * @access private
     */
    private function _set_pasta_destino( $pasta = '' )
    {
        //die();
        $retorno = LOCAL_IMAGE.'/'.$pasta;
        //var_dump($retorno);die();
        if ( ! is_dir($retorno) )
        {
            if ( ! mkdir($retorno, 0777, TRUE) )
            {
                $dir = explode('/',  substr(trim($retorno),0,-1));
                $pop = array_pop($dir);
                $pasta = implode('/',$dir);
                if ( mkdir($pasta, 0777, TRUE) )
                {
                    mkdir($retorno, 0777, TRUE);
                }
            }
        }
        return $retorno;
    }
    
    /**
     * faz upload da imagem para a pasta destino
     * @param type $ftp
     * @param type $destino
     * @param type $arquivo_local
     */
    public function ftp_upload ( $caracteristicas = array(), $nome_arquivo )
    {
        $this->load->library('ftp');
        $keys = json_decode(KEYS);
        $array_ftp = (array)$keys->ftp->{$caracteristicas['ftp']};
        $conn = $this->ftp->connect($array_ftp);
        $files = $this->ftp->list_files($caracteristicas['pasta']);
        if ( ! $files )
        {
            $mk = $this->ftp->mkdir($caracteristicas['pasta']);
        }
        $retorno_v = $this->ftp->upload($caracteristicas['destino'], $caracteristicas['pasta'].$nome_arquivo, 'ascii', 0775);
        $this->ftp->close();
    }
    
    
    /**
     * redimensiona e salva a imagem em formato .jpg
     * @param array $image_info informaçoes da imagem
     * @param string $endereco_image endereço onde a imagem se encontra
     * @param string $arquivo arquivo
     * @param string $id_arquivo id do arquivo
     * @param array $tamanho tamanho do arquivo
     * @param string $titulo titulo do arquivo
     * @param int $id_cadastro id de cadastro
     * @return boolean $retorno
     * @version 1.0
     * @access public
     * @author Carlos Claro
     */
    public function _set_jpg( $image_info, $endereco_image, $arquivo, $id_arquivo, $tamanho, $titulo, $id_cadastro = 0)
    {
        if ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefromjpeg($endereco_image); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,( ( $tamanho['height'] == 'auto' ) ? 0 : 40 ),$new_width,$new_height,$image_info[0],( ( $tamanho['height'] == 'auto' ) ? $image_info[1] : $new_height ) );
        $pasta_destino = $this->_set_pasta_destino($tamanho['pasta']);
        $nome_arquivo = $tamanho['prefixo'].$arquivo;
        $destino = $pasta_destino.''.$nome_arquivo;
        if ( imagejpeg($dst_img,$destino,70) )
        {
            if ( $tamanho['salva'] )
            {
                $data_arquivo = array('arquivo' => $nome_arquivo, 'data' => date('Y-m-d H:i'), 'id_empresa' => $id_cadastro );
                $id_image_arquivo = $this->images_model->adicionar_arquivo($data_arquivo);
                $data_image_pai = array( 'id_image_tipo' => $tamanho['tipo'], 'id_image_arquivo' => $id_image_arquivo, 'id_pai' => $id_arquivo, 'descricao' => $titulo, 'moderada' => 0, 'id_cadastro' => $id_cadastro  );
                $this->images_model->adicionar_pai($data_image_pai);
            }
            if ( isset($tamanho['ftp']) )
            {
                $tamanho['destino'] = $destino;
                $this->ftp_upload($tamanho, $nome_arquivo);
                unlink($destino);
            }
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    /**
     * carregando imigem em formato .gif
     * @param array $image_info  informações da imagem
     * @param string $endereco_image  endereço onde a imagem se encontra
     * @param string $arquivo  arquivo
     * @param string $id_arquivo  id do arquivo
     * @param array $tamanho  tamanho do arquivo
     * @param string $titulo  titulo do arquivo
     * @param int $id_cadastro  id do cadastro
     * @return boolean $retorno
     * @version 1.0
     * @access public
     * @author Carlos Claro
     */
    public function _set_gif( $image_info, $endereco_image, $arquivo, $id_arquivo, $tamanho, $titulo, $id_cadastro = 0)
    {
        
        if ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefromgif($endereco_image); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$image_info[0],( ( $tamanho['height'] == 'auto' ) ? $image_info[1] : $new_height ) );
        $pasta_destino = $this->_set_pasta_destino($tamanho['pasta']);
        $nome_arquivo = $tamanho['prefixo'].$arquivo;
        $destino = $pasta_destino.''.$nome_arquivo;
        if ( imagegif($dst_img,$destino) )
        {
            if ( $tamanho['salva'] )
            {
                $data_arquivo = array('arquivo' => $nome_arquivo, 'data' => date('Y-m-d H:i'), 'id_empresa' => $id_cadastro );
                $id_image_arquivo = $this->images_model->adicionar_arquivo($data_arquivo);
                $data_image_pai = array( 'id_image_tipo' => $tamanho['tipo'], 'id_image_arquivo' => $id_image_arquivo, 'id_pai' => $id_arquivo, 'descricao' => $titulo, 'moderada' => 0, 'id_cadastro' => $id_cadastro );
                $this->images_model->adicionar_pai($data_image_pai);
            }
            if ( isset($tamanho['ftp']) )
            {
                $tamanho['destino'] = $destino;
                $this->ftp_upload($tamanho, $nome_arquivo);
                unlink($destino);
            }
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    /**
     * carregando imagem em formato .png
     * @param array $image_info  imformações da imagem
     * @param string $endereco_image  endereço onde a imagem se encontra
     * @param string $arquivo   arquivo
     * @param string $id_arquivo  id do arquivo
     * @param array $tamanho  tamanho do arquivo
     * @param string $titulo  titulo do arquivo
     * @param int $id_cadastro  id do cadastro
     * @return boolean
     * @version 1.0
     * @access public
     */
    public function _set_png( $image_info, $endereco_image, $arquivo, $id_arquivo, $tamanho, $titulo, $id_cadastro = 0)
    {
        if ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefrompng($endereco_image); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$image_info[0],( ( $tamanho['height'] == 'auto' ) ? $image_info[1] : $new_height ) );
        $pasta_destino = $this->_set_pasta_destino($tamanho['pasta']);
        $nome_arquivo = $tamanho['prefixo'].$arquivo;
        $destino = $pasta_destino.''.$nome_arquivo;
        if ( imagepng($dst_img,$destino) )
        {
            if ( $tamanho['salva'] )
            {
                $data_arquivo = array('arquivo' => $nome_arquivo, 'data' => date('Y-m-d H:i'), 'id_empresa' => $id_cadastro );
                $id_image_arquivo = $this->images_model->adicionar_arquivo($data_arquivo);
                $data_image_pai = array( 'id_image_tipo' => $tamanho['tipo'], 'id_image_arquivo' => $id_image_arquivo, 'id_pai' => $id_arquivo, 'descricao' => $titulo, 'moderada' => 0, 'id_cadastro' => $id_cadastro  );
                $this->images_model->adicionar_pai($data_image_pai);
            }
            if ( isset($tamanho['ftp']) )
            {
                $tamanho['destino'] = $destino;
                $this->ftp_upload($tamanho, $nome_arquivo);
                unlink($destino);
            }
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    
    
    
    
    
}
