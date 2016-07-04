<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**  
 * Classe responsável pela formação das principais partes do layout, meta, grupos e gerenciadores
 * @author Carlos Claro 
 * @package Admin
 *  
 */
class Layout {	
    /** 
     * instancia do CodeIgniter
     * @name CI	 
     * @access private	 
     */
    private $CI;		
    /**
     * Classe que esta chamando o layout, serve para definir o menu ativo
     * @var type string
     */
    private $classe = NULL;
    /**
     * Function principal que chama o layout, usado em menu e sub menu
     * @var type string
     */
    private $function = NULL;	
    /**
     * setado por função, carrega o titulo da pagina, melhoria de SEO
     * @var type string
     */
    private $titulo = NULL;		
    /**
     * seta as keywords do sistema
     * @var type array
     */
    private $keywords = array('');		
    /**
     * description da pagina, NULL by default	 	 
     * @var type string
     */
    private $description = '';
    /**
     * Separador para titulo ou outras situações que precisem de separador
     * @var type string
     */
    private $separador = ' - ';		
    /**
     * agrupa os includes da pagina, normalmente, defaults, jquery, bootstrap...
     * @var type array
     */
    private $file_includes = array();	
    /**
     * pode ser string ou array, guarda os dados do usuario da sessão para montar menus e afins
     * @var type string
     */
    private $usuario = '';
    /**
     * armazena o caminho de breadscrumbs
     * @var type array
     */
    private $breadscrumbs = NULL;
    /**
     * 
     */
    private $menu = NULL;
    /**	 	 
     * Contrutor da Classe	 	 
     */	
    public function __construct() 		
    {
        $this->CI =& get_instance();				
        $this->set_includes_defaults();				
        $this->set_titulo(FALSE);	
    }	
 
    /**
     * Seta as informações do Usuario do sistema
     * @param type $usuario default FALSE
     * @return \Layout
     */
    public function set_usuario( $usuario = FALSE )	
    {
        if ( $usuario )
        {
            $this->usuario = $usuario;
        }
        else
        {
            $this->usuario = $this->CI->sessao['usuario'];
        }
        return $this;	
    }	
    /**
     * Carrega o usuario quando solicitado
     * @return type string
     */
    private function get_usuario()	
    {		
        return $this->usuario;	
        
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_classe( $classe )	
    {		
        $this->classe = $classe;		
        return $this;	
    }	
    /**
     * carrega a classe
     * @return type string
     */
    private function get_menu()	
    {		
        return $this->menu;	
    }	
    /**
     * Seta a classe para a variavel
     * @param type $classe
     * @return \Layout
     */
    public function set_menu( $menu )	
    {		
        $this->menu = $menu;		
        return $this;	
    }	
    /**
     * carrega a classe
     * @return type string
     */
    private function get_classe()	
    {		
        return $this->classe;	
    }	
    /**
     * Seta a função para a variavel
     * @param type $function
     * @return \Layout
     */
    public function set_function( $function )	
    {		
        $this->function = $function;		
        return $this;	
    }	
    /**
     * carrega a funcao para a variavel
     * @return type string
     */
    private function get_function()	
    {		
        return $this->function;	
    }	
    /**
     * Seta o titulo da página
     * @param type $titulo
     * @return \Layout
     */
    public function set_titulo($titulo) 		
    {				
        if ($titulo) 				
        {						
            $this->titulo = $titulo . $this->separador . ' Pow Imóveis :.';				
        }				
        else 				
        {						
            $this->titulo = 'Pow Imóveis  :.';				
        }				
        return $this;		
    }
    /**
     * Carrega o titulo quando solicitado
     * @return type string
     */
    private function get_titulo() 		
    {				
        return $this->titulo;		
    }	
    /**
     * Seta os keywords para a variavel
     * @param type array $keywords
     * @return \Layout
     */
    public function set_keywords( $keywords )
    {				
        if (is_array($keywords))				
        {						
            $this->keywords = $keywords;				
        }					
        else				
        {						
            $this->keywords[] = $keywords;				
        }				
        return $this;		
        
    }
    /**
     * Carrega as keywords quando necessário
     * @deprecated since version 2015-09-15 função não ultilizada
     *  @return type string
     */
    private function get_keywords() 		
    {	/**			
        if (count($this->keywords) > 0)
        {						
            $this->keywords = implode(', ', $this->keywords);				
        }				
        return $this->keywords;	
     * 
     */	
    }	
    /**
     * Seta description da pagina para SEO
     * @param type $description
     * @return \Layout
     */
    public function set_description($description) 		
    {			
        $this->description  = $description;				
        return $this;		
    }	
    /**
     * carrega description quando solicitado
     * @return type string
     */
    private function get_description() 		
    {				
        return $this->description;		
    }		
    /**
     * Seta os includes usando a função set_include
     */
    private function set_includes_defaults() 		
    {				
        $this						
                ->set_include('css/bootstrap.css', TRUE)			
                ->set_include('js/jquery-2.0.3.min.js', TRUE)
                ->set_include('js/bootstrap.min.js', TRUE)
                //->set_include('js/jquery.maskedinput.js', TRUE)
                ->set_include('js/meiomask.js', TRUE)
                ->set_include('js/ckeditor/ckeditor.js', TRUE)
                ->set_include('js/ckeditor/config.js', TRUE)
                ->set_include('js/datetimepicker/moment.js', TRUE)
                ->set_include('js/datetimepicker/bootstrap-datetimepicker.min.js', TRUE)
                ->set_include('js/tarefas.js', TRUE)
                ->set_include('js/funcs.js', TRUE)
                ->set_include('css/datetimepicker/bootstrap-datetimepicker.min.css', TRUE)
                ->set_include('css/estilo.css', TRUE);				
    }
    /**
     * Seta os includes, verificando extensão e se entra no pacote, separa por extensão
     * @param string $path endereçamento do arquivo
     * @param type $prepend_base_url - se utiliza o endereço completo
     * @return \Layout
     */
    public function set_include($path, $prepend_base_url = TRUE)		
    {				
        if ($prepend_base_url)				
        {						
            $path = base_url().$path;				
            
        }	
        if (preg_match('/js$/', $path))				
        {						
            $this->file_includes['js'][] = $path;				
        }				
        elseif (preg_match('/css$/', $path))				
        {						
            $this->file_includes['css'][] = $path;				
        }				
        return $this;		
    
    }
    /**
     * monta os includes por tipo para colar no layout
     * @return string
     */
    public function get_includes()		
    {				
        $final_includes = '';				
        foreach ($this->file_includes['css'] as $include) 				
        {						
            $final_includes .= '<link rel="stylesheet" href="'.$include.'?t='.time().'" type="text/css" />'.PHP_EOL;				
            
        }				
        foreach ($this->file_includes['js'] as $include) 				
        {						
            $final_includes .= '<script type="text/javascript" src="'.$include.'?t='.time().'"></script>'.PHP_EOL;				
            
        }				
        return $final_includes;		
        
    }	
    /**
     * Monta breadscumbs 
     * @param type $title
     * @param string $url
     * @param type $active
     * @param type $prepend_base_url
     * @return \Layout
     */
    public function set_breadscrumbs($title, $url, $active = 0, $prepend_base_url = TRUE) 		
    {				
        if ($prepend_base_url)				
        {						
            $url = base_url().$url;				
        }				
        $this->breadscrumbs[] = (object) array('title'=>$title, 'url'=>$url, 'active'=>$active);
        return $this;		
    }	
    /**
     * monta e retorna o breadscrumbs do sistema
     * @return type string
     */
    private function get_breadscrumbs() 		
    {
        if ( isset( $this->breadscrumbs ) && count( $this->breadscrumbs ) > 0 )
        {
            $retorno = '<ol class="breadcrumb"><li>Onde estou: </li>';
            foreach ( $this->breadscrumbs as $breads )
            {
                if ( $breads->active == 1 )
                {
                    $retorno .= '<li class="active">'.$breads->title.'</li>';
                }
                else
                {
                    $retorno .= '<li class=""><a href="'.$breads->url.'">'.$breads->title.'</a></li>';
                }
            }
            $retorno .= '</ol>';
        }
        else
        {
            $retorno = '';
        }
        return $retorno;
    }	
    
    public function mata_this()
    {
        unset($this);
        $this->CI =& get_instance();		
    }
    
    public function view($view_name, $params = array(), $layout = 'layout/layout', $retorno_html = FALSE)		
    {	
        $view_content = $this->CI->load->view($view_name, $params, TRUE);	
        if ( $this->CI->input->is_ajax_request() && ! $retorno_html )
        {	
                print $view_content;				
        }				
        else				
        {	
            $parametros = array(											
                'conteudo'              => $view_content,
                'includes'              => $this->get_includes(),
                'titulo'		=> $this->get_titulo(),
                'keywords' 		=> $this->get_keywords(),
                'description'           => $this->get_description(),
                'function' 		=> $this->get_function(),
                'classe'		=> $this->get_classe(),
                'usuario'               => $this->get_usuario(),
                'breadscrumbs'          => $this->get_breadscrumbs(),
                'menu'                  => $this->get_menu()
                );
            if( $retorno_html )
            {
                return $this->CI->load->view($layout, $parametros , $retorno_html );				
            }
            else 
            {
                 $this->CI->load->view($layout, $parametros);				
            }
        }		
    }
    
}
                            