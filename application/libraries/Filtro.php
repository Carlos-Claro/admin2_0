<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 * formato padrao de chamada a classe
 * $config['valores'] = $_GET['b']
 * $config['itens'] = array( 'name' => 'nome', 'tipo' => 'text', 'classe' => 'classes', 'valor' => '', 'where' => array( 'tipo' => 'where', 'campo' => 'login', 'valor' => 'teste' ) )
 * $config['colunas'] = 1
 * $config['extras'] = onsubmit="aprova"
 * $config['url'] = http://www.site.com.br/classe/funcao
 * $config['botoes'] = botao
 */

class Filtro
{
	/**
	 * 
	 * Itens do campo de busca
	 * @var $itens
	 */
	private $itens;
	/**
	 * 
	 * Valores do campo de busca
	 * @var campos
	 */
	private $valores;
	
	private $extras;
        
	private $botoes;
	
	private $url;
	/**
	 * 
	 * Qtde colunas da montagem do menu
	 * @var colunas
	 */
	private $colunas = 1;
	
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
			$this->inicia($config);
		}
	}
	
	private function _get_campo( $item = array() )
	{
		$retorno = '';
		switch ( $item['tipo'] )
		{
			case 'hidden':
                                $retorno .= '<input class="'.( isset($item['classe']) ? $item['classe'] : '' ).'" name="b['.$item['name'].']" type="'.$item['tipo'].'" value="'.$item['valor'].'" >'.PHP_EOL;
                                break;
			case 'text':
				$retorno .= '<input class="form-control ';
                                $retorno .= ( isset($item['classe']) ? $item['classe'] : '' ).'" '
                                        . 'name="b['.$item['name'].']" type="'.$item['tipo'].'" '
                                        . 'value="';
                                if ( array_key_exists($item['name'], $this->valores) )
                                {
                                    $retorno .= isset($item['acao']) ? $item['acao']($this->valores[$item['name'] ]) : $this->valores[$item['name'] ];
                                }
                                $retorno .= '" '.(isset($item['extra']) ? $item['extra'] : '').'>'.PHP_EOL;
				break;
			case 'select':
				$retorno .= '<select style="width:100%;"  name="b['.$item['name'].']" class="'.( isset($item['classe']) ? $item['classe'] : '' ).'" '.(isset($item['extra']) ? $item['extra'] : '').'>'.PHP_EOL;
				$retorno .= '<option value="">Selecione...</option>'.PHP_EOL;
				foreach ( $item['valor'] as $valor )
				{
					$retorno .= '<option value="'.$valor->id.'" '.( ( isset($this->valores[$item['name']]) && $this->valores[$item['name']] == $valor->id ) ? 'selected="selected"' : '' ).'>'.$valor->descricao.'</option>'.PHP_EOL;
				}
				$retorno .= '</select>'.PHP_EOL;
				break;
			case 'radio':
				break;
                        case 'clicavel':
                            $retorno .= $this->_get_modal($item['name'], isset($item['selecionado']) ? $item['selecionado'] : $item['titulo'], $item['valor']);
                            break;
                        case 'inativo' :
                            $retorno .= '';
                            break;
		}
		return $retorno;
	}
        
        private function _get_modal( $item = NULL, $titulo = NULL, $valores = NULL  )
        {
            $retorno = '<button type="button" class="btn btn-default col-lg-12 '.$item.'" data-toggle="modal" data-target="#modal-'.$item.'" data-item="'.(isset($titulo->id) ? $titulo->id : 'i').'">'.(isset($titulo->descricao) ? $titulo->descricao : $titulo).'</button>';
            $retorno .= '<div class="modal fade" id="modal-'. $item .'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
            $retorno .= '<div class="modal-dialog modal-pesquisa">';
            $retorno .= '<div class="modal-content">';
            $retorno .= '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4>Escolha '. (isset($titulo->descricao) ? $titulo->descricao : $titulo) .':</h4></div>';
            $retorno .= '<div class="modal-body container ">';
            $retorno .= '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
            $retorno .= '<div class="escolhidos"><ul></ul></div><br>';
            $retorno .= '<a href="#" class="btn btn-success pesquisar-'.$item.'" id="pesquisar-modal">Pesquisar</a>';
            $retorno .= '</div>';
            $retorno .= '<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">';
            $retorno .= '<div class="itens">';
            $itens['valor'] = $valores;
            $itens['titulo'] = isset($titulo->descricao) ? $titulo->descricao : $titulo;
            $itens['link'] = $item;
            $retorno .= isset($itens) ? form_selecionavel($itens) : '';
            $retorno .= '</div><!-- .itens -->';
            $retorno .= '</div><!-- .col-lg-9 -->';
            $retorno .= '</div><!-- .modal-body -->';   
            $retorno .= '<div class="modal-footer">';
            $retorno .= '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>';
            $retorno .= '</div><!-- .modal-footer -->';
            $retorno .= '</div>';
            $retorno .= '</div><!-- .modal-dialog -->';
            $retorno .= '</div><!-- .modal -->';
            return $retorno;
        }
	
	public function get_html()
	{
		$retorno = '';
		if ( $this->itens )
		{
			//$retorno .= '<form action="'.$this->url.'" method="get" '.( ($this->extras) ? $this->extras : '' ).' role="form" class="form-inline">';
			$retorno .= '<form action="'.$this->url.'" method="get" '.( ($this->extras) ? $this->extras : '' ).' role="form" class="form">';
                        $retorno .= '   <h4>Pesquisar</h4>';
                        //$retorno .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $retorno .= '   <div class="row">';
			$coluna = floor(12/($this->colunas));
                        
			foreach ( $this->itens as $item )
			{
                            if(isset($item['tipo']) && !empty($item['tipo']) )
                            {
				if ( $item['tipo'] != 'hidden' )
				{
					$retorno .= '<div class="col-lg-'.$coluna.' col-md-'.$coluna.' col-sm-'.$coluna.' col-xs-'.$coluna.'" >'.PHP_EOL;
					$retorno .= '   <div class="form-group" >'.PHP_EOL;
					$retorno .= '       <label for="'.$item['titulo'].'">'.$item['titulo'].'</label>'.PHP_EOL;
					$retorno .=         $this->_get_campo($item).PHP_EOL;
					$retorno .= '   </div>'.PHP_EOL;
					$retorno .= '</div>'.PHP_EOL;
				}
				else 
				{
					$retorno .= $this->_get_campo($item);
				}
                            }
			}
                        $retorno .= '       <div class="col-lg-'.$coluna.' col-md-6 col-sm-6 col-xs-6">'.PHP_EOL;
                        $retorno .= '           <br>'.PHP_EOL;
                        $retorno .= '           <button type="submit" class="btn btn-primary btn-block">Buscar</button>'.PHP_EOL;
                        $retorno .= '       </div>'.PHP_EOL;
                        $retorno .= '       <div class="col-lg-'.$coluna.' col-md-6 col-sm-6 col-xs-6">'.PHP_EOL;
                        $retorno .= '           <br>'.PHP_EOL;
                        $retorno .= '           <a href="'.$this->url.'" class="btn btn-default btn-block">Limpar</a>'.PHP_EOL;
                        $retorno .= '       </div>'.PHP_EOL;
			$retorno .= '   </div>'.PHP_EOL;
			$retorno .= '   <div class="row">'.PHP_EOL;
			$retorno .= '       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'.PHP_EOL;
			//$retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        //$retorno .= '<button type="submit" class="btn btn-primary">Buscar</button><a href="'.$this->url.'" class="btn btn-default">Limpar</a>';
			$retorno .= ( ($this->botoes) ? str_replace('[filtro]', $this->get_url(), $this->botoes) : '' );
			$retorno .= '       </div>'.PHP_EOL;
			$retorno .= '   </div>'.PHP_EOL;
			$retorno .= '</form>'.PHP_EOL;
                       
		}
		return $retorno; 
	}
	
	public function get_url($build_query = FALSE)
	{
		$retorno = '';
                if(isset($build_query) && $build_query)
                {
                    $retorno .= '?'.http_build_query($build_query);
                }
		elseif ( $this->valores )
		{
                        $a = 0;
                        foreach ( $this->valores as $chave => $valor )
                        {
                                if ( $a == 0 )
                                {
                                        $retorno .= '?b['.$chave.']='.urlencode($valor);
                                }
                                else 
                                {
                                        $retorno .= '&b['.$chave.']='.urlencode($valor);
                                }
                                $a++;
                        }
		}
		return $retorno;
	}
        
	public function get_filtro()
	{
		$retorno = array();
		if ( $this->itens )
		{
                    foreach ( $this->itens as $item )
                    {
                        if ( isset( $this->valores[ $item['name'] ] ) && ! empty( $this->valores[ $item['name'] ] ) )
                        {
                            if ( is_array( $item['where'] ) )
                            {
                                $retorno[] = array( 'tipo' => $item['where']['tipo'], 'campo' =>  $item['where']['campo'] , 'valor' => $this->valores[ $item['name'] ], 'unescape' => ( (isset($item['where']['unescape']) && $item['where']['unescape']) ? TRUE : NULL ) );
                            }
                            else 
                            {
                                if ( isset($item['where']) && $item['where'] )
                                {
                                    $retorno[] = $item['where'];
                                }
                            }
                        }
                    }
		}
		return $retorno;
	}
	
	public function inicia($config)
	{
		$this->itens 	= ( isset($config['itens']) ? $config['itens'] : FALSE );
		$this->valores 	= ( isset($config['valores']) ? $config['valores'] : array() );
		$this->colunas 	= ( isset($config['colunas']) ? $config['colunas'] : FALSE );
		$this->url 	= ( isset($config['url']) ? $config['url'] : FALSE );
		$this->extras	= ( isset($config['extras']) ? $config['extras'] : FALSE );
		$this->botoes 	= ( isset($config['botoes']) ? $config['botoes'] : FALSE );
		
		return $this;
	}
	
}	