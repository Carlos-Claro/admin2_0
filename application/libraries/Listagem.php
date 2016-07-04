<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 */

class Listagem 
{
	private $cabecalho;
	private $itens;
	private $total_itens;
	private $chave = NULL;
	private $extras = FALSE;
        private $operacoes = FALSE;
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
			$this->inicia($config);
                        $this->load->helper('url');
		}
	}
	
	public function get_html()
	{
		$retorno = '';
                if( isset($this->extras['total_itens']) )
                {
                    $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="alert alert-success text-center">Total de itens: '.$this->extras['total_itens'].'</div></div>';
                }
                if(isset($this->extras['cabecalho']) && $this->extras['cabecalho'])
                {
                    $cabecalho = $this->extras['cabecalho'];
                    $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    $retorno .= '   <div class="row alert alert-info">';
                    $retorno .= '       <div class="col-lg-6">';
                    $retorno .= '           <h4>'.$this->extras['opcao'].' : '.$cabecalho->titulo.'</h4>';
                    $retorno .= '           <input type="hidden" value="'.$cabecalho->id.'" class="valor_pai">';
                    $retorno .= '       </div>';
                    $retorno .= '   </div>';
                    $retorno .= '</div>';
                }
		$retorno .= '<table border="0" class="table table-striped tb_carlos">';
		$retorno .= $this->_set_cabecalho();
		$retorno .= $this->_set_itens();
		$retorno .= '</table>';
		return $retorno;
	}
	
	private function _set_itens()
	{
		$retorno = '';
		if ( isset( $this->itens ) && count( $this->itens ) > 0 )
		{
			$chave = $this->chave;
			for ( $i = 0; count($this->itens) > $i; $i++ )
			{
				$retorno .= '<tr class="sel_linha">';
				$item = $this->itens[$i];
				if ( isset( $this->chave ) )
				{
					$retorno .= '<td><input type="checkbox" value="'.$item->$chave.'" class="sel_linha" /></td>';
				}
				foreach ( $this->cabecalho as $c )
				{
					$ch = $c->chave;
                                        
                                        $retorno .= '<td>';
                                        $valor_ = $item->{$ch};
                                        
                                        if ( isset($c->acao) && $c->acao )
                                        {
                                            $acao = $c->acao;
                                            $valor_ = $acao($valor_);
                                        }
                                        if(isset($c->classe_destino) && $c->classe_destino)
                                        {
                                            $retorno .= $this->_set_link_completo($c, $item, $valor_);
                                        }
                                        else
                                        {
                                             $retorno .= urldecode($valor_);
                                        }
				}
                                if(isset($this->operacoes) && $this->operacoes)
                                {
                                    foreach($this->operacoes as $operacao)
                                    {
                                        $retorno .= '<td>';
                                        $retorno .= '<a class="'.$operacao->class.' '.  strtolower($operacao->titulo).'" data-item="'.$item->id.'" '.( isset($operacao->target) ? 'target="'.$operacao->target.'"' : '' );
                                        if(isset($operacao->link) && $operacao->link)
                                        {
                                            if(strripos($operacao->link, '[id]'))
                                            {
                                                $id_link = str_replace('[id]', $item->id, $operacao->link);
                                            }
                                            else
                                            {
                                                $id_link = $operacao->link;
                                            }
                                            $retorno .= 'href="'.base_url().$id_link.'"';
                                        }
                                        $retorno .= ' >'.$operacao->icone.' '.$operacao->titulo;
                                        $retorno .= '</a> ';
                                        $retorno .= '</td> ';
                                    }
                                }
                                $retorno .= '</tr>';
				unset($item);
			}
		}
		else
		{
			$retorno .= '<tr><td>Nenhum Item Encontrado</td></tr>';
		}
		return $retorno;
	}
        
        
        private function _set_link_completo($c = NULL, $item = NULL, $chave = NULL)
        {
            $retorno = NULL;
            //$link = (isset($c->nav_menu) && empty($c->nav_menu)) ? base_url().'canais_conteudo/listar' : base_url().$c->classe_destino;
            if(isset($item->id) && $item->id)
            {
                if(strripos($c->classe_destino, '[id]'))
                {
                    $id_link = str_replace('[id]', $item->id, $c->classe_destino);
                }
                $link = base_url().$id_link;
                $retorno = '<a href="'.$link.'">'.$chave.'</a>';
                //$retorno = (strripos($c->classe_destino, '?')) ? '<a href="'.$link.$item->id.'">'.$chave.'</a>' : '<a href="'.$link.'/'.$item->id.'">'.urldecode($chave).'</a>';
            }
            else
            {
                $retorno = urldecode($chave);
            }
            return $retorno;
        }
        
	private function _set_cabecalho()
	{
		$retorno = '<tr>';
		if ( isset( $this->chave ) )
		{
			$retorno .= '<th><input type="checkbox" value="0" id="sel_todos" /></th>';
		}
		
		foreach ( $this->cabecalho as $c )
		{
			$retorno .= '<th class="'.( (isset($c->class)) ? $c->class : '' ).'">';
			if ( isset($c->link) )
			{
				$retorno .= '<a href="'.$c->link.'">';
				$retorno .= $c->titulo;
				$retorno .= '</a>'; 
			}
			else
			{
				$retorno .= $c->titulo;
			}
			
		}
                $retorno .= '</th>';
                if(isset($this->operacoes) && $this->operacoes)
                {
                    $retorno .= '<th colspan="'.((count($this->operacoes) > 1) ? count($this->operacoes) : '0').'" >Operação</th>';
                }
		$retorno .= '</tr>';
		return $retorno;
	}
	
	public function inicia( $config )
	{
		if ( isset( $config['cabecalho'] ) )
		{
			$this->cabecalho = $config['cabecalho'];
		}
		if ( isset( $config['itens']) )
		{
			$this->itens = $config['itens'];
		}
		if ( isset( $config['total_itens']) )
		{
			$this->total_itens = $config['total_itens'];
		}
		if ( isset( $config['chave']) )
		{
			$this->chave = $config['chave'];
		}
		if ( isset( $config['extras']) )
		{
			$this->extras = $config['extras'];
		}
                if ( isset( $config['operacoes']) )
		{
			$this->operacoes = $config['operacoes'];
		}
                
		return $this;
	}
	
}