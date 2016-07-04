<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 */

class Modal 
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
        
        public function get_modal($data = '')
        {
            $dados['item'] = $data;
            $retorno = $this->CI->load->view('view_modal',$dados, TRUE);
            return $retorno;
        }
        
        /*
	private $titulo;
	private $body;
	private $rodape;
	private $filtro = NULL;
	private $extras = 'myModal';
	/**
	 * 
	 * Contrutor da Classe
	 */
        /*
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
                    $this->inicia($config);
		}
	}
	
	public function get_html()
        {
                $retorno  = '<div id="'.$this->extras['id'].'" value="'.$this->filtro.'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">';
		$retorno .= '   <div class="modal-dialog modal-lg">';
                $retorno .= '       <div class="modal-content">';
                $retorno .= $this->_set_cabecalho();
                $retorno .= $this->_set_body();
                $retorno .= $this->_set_rodape();
                $retorno .= '       </div>';                
                $retorno .= '   </div>';                
                $retorno .= '</div>';                
		return $retorno;
	}
	
	private function _set_body()
	{

            $retorno = '<div class="modal-body row">';
            if ( isset( $this->body ) && count( $this->body ) > 0 )
            {
                $retorno .= $this->body;
            }
            else
            {
                $retorno .= '<div class="col-lg-12 col-md-12"></div>';
            }
            $retorno .= '</div>';
            return $retorno;
	}
	
	private function _set_cabecalho()
	{
            $retorno  = '<div class="modal-header">';
            $retorno .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            if(isset($this->titulo))
            {            
                $retorno .= '<h4 class="modal-title" id="myModalLabel">'.$this->titulo.'</h4>';
            }
            else
            {
                $retorno .= '';
            }
            $retorno .= '</div>';
            
            return $retorno;
	}
	private function _set_rodape()
	{
            $retorno  = '<div class="modal-footer">';
            if(isset($this->rodape) && count($this->rodape) > 0)
            {
                $retorno .= $this->rodape;
            }
            else
            {
                $retorno .= '';
            }
            $retorno .= '</div>';
            
            return $retorno;
	}
	
	public function inicia( $config )
	{
		$this->titulo = (isset( $config['titulo'] )) ? $config['titulo'] : NULL;
                $this->rodape = (isset( $config['rodape'] )) ? $config['rodape'] : NULL;        
                $this->body = (isset( $config['body'] )) ? $config['body'] : NULL;        
                $this->filtro = (isset( $config['filtro'] )) ? $config['filtro'] : NULL;        
                $this->extras = (isset( $config['extras'] )) ? $config['extras'] : NULL;        
		return $this;
	}*/
	
}