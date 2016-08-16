<?php

class Email_mkt_
{
    
    private $CI;
    
    /**
     * Instancia CI
     */
    public function __construct() 
    {
        $this->CI =& get_instance();
    }
    
    
    /**
     * 
     * @param type $data
     */
    public function html($data = NULL)
    {
        $retorno = '';
        $data['imoveis'] = array();
        if ( isset($data['itens']['itens']) )
        {
            foreach( $data['itens']['itens'] as $imovel )
            {
                $data['imoveis'][] = $this->_set_item_email($imovel, TRUE, $data['cidade']['portal'].'/');
            }
        }
        $retorno = $this->CI->layout->view('emails/corpo_padrao', $data, 'layout/sem_head', TRUE);
        //echo $retorno;die();
        return $retorno;
    }
 
    
        
    public function _set_item_email ( $item = NULL, $email = FALSE, $dominio = 'http://www.icuritiba.com/')
    {
        $retorno = '';
        $tn = 0;
        if ( $item->tipo_venda == 1 && $item->tipo_locacao == 1 )
        {
            $tipo = (object)array('classe' => 'venda_locacao', 'titulo' => 'Venda ou Locação');
        }
        elseif ( $item->tipo_locacao_dia == 1 && $item->tipo_locacao == 1 )
        {
            $tipo = (object)array('classe' => 'locacao_locacao_dia', 'titulo' => 'Locação');
        }
        elseif ( $item->tipo_venda == 1 )
        {
            $tn = 'venda';
            $tipo = (object)array('classe' => 'venda', 'titulo' => 'Imóvel para Venda');
        }
        elseif ( $item->tipo_locacao == 1 ) 
        {
            $tn = 'locacao';
            $tipo = (object)array('classe' => 'locacao', 'titulo' => 'Imóvel para Locação');
        }
        elseif ( $item->tipo_locacao_dia == 1 )
        {
            $tipo = (object)array('classe' => 'locacao_dia', 'titulo' => 'Imóvel para Locação temporada');
        }
        $titulo = (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );
        $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >';
        $retorno .= '   <div class="thumbnail '.$tipo->classe.' " style="background-color:#EEEEEE; height:360px;">';
        $retorno .= '<h3>';
        $retorno .= '<a href="'. $this->_set_link($item, 'e', $dominio).'" title="'.(isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' ).'">';
        $retorno .= $tipo->titulo;
        $retorno .= '</a>';
        $retorno .= '</h3>';
        $retorno .= '<center>';
        $retorno .= '<a href="'. $this->_set_link($item, 'e', $dominio).'" title="'.(isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' ).'">';
        $retorno .= '<img class="img-responsive" style="width:200px; height:200px;" width="200" height="200" src="';
        if ( isset($item->images) && !empty($item->images) )
        {
            $arquivo = set_arquivo_image($item->id_imovel, $item->images[0]->arquivo, $item->id_empresa, $item->mudou, TRUE,1,'destaque',TRUE);
            $retorno .= $arquivo['arquivo'];
        } 
        else
        {
            $retorno .= 'http://www.icuritiba.com/imagens/naodisponivel.jpg';
        }
        $retorno .= '" alt="'.( ( ! empty($item->image_descricao) ) ? $item->image_descricao : $titulo ).'" style="width:300px; height:200px;">';
        $retorno .= '</a>';
        $retorno .= '</center>';
        $retorno .= '<div class="caption" style="height: 100px;" >';
        $retorno .= (isset($item->imoveis_tipos_titulo)) ? '<h4><strong>'.$item->imoveis_tipos_titulo.' </strong></h4>' : '';
        $retorno .= (isset($item->bairro) && ! empty($item->bairro) ) ? '<h5><strong>'.$item->bairro.' </strong></h5>' : '';
        $retorno .= '<p class="border-bottom">'.$item->cidade_nome.'/'.$item->uf.' </p>';
        $retorno .= '<a style="position:absolute; bottom:10px; right:'.( $email ? '40px' : '20px').'; padding:2px; " href="'.$this->_set_link($item, 'e').'" class="btn '.( $email ? 'btn-primary' : 'btn-link' ).' pull-right">Mais detalhes</a>';
        $retorno .= '</div>';
        $retorno .= '   </div>';
        $retorno .= '</div>';
        $retorno .= '<div class="btn-group">';
        if ( ! empty($item->bairro) )
        {
            $retorno .= '<br><center><a href="'.$dominio.'imoveis/'.$item->cidades_link.'/'.$tn.'/'.$item->imoveis_tipos_link.'/'.$item->bairros_link.'" class="btn btn-default">Veja mais: '.$item->imoveis_tipos_titulo.' / '.$item->bairro.' / '.str_replace('Imóvel para', '', $tipo->titulo).'</a></center>';
        }
        else
        {
            $retorno .= '<br><center><a href="'.$dominio.'imoveis/'.$item->cidades_link.'/'.$tn.'/'.$item->imoveis_tipos_link.'" class="btn btn-default">Veja mais: '.$item->imoveis_tipos_titulo.' / '.str_replace('Imóvel para', '', $tipo->titulo).'</a></center>';

        }
        $retorno .= '</div>';
        return $retorno;
    }

    private function _set_valor( $item )
    {
        $retorno = '';
        if ( $item->preco_venda != '0.00' && $item->preco_venda > 0 )
        {
            $retorno = number_format($item->preco_venda, 2, ',', '.');
        }
        if ( $item->preco_locacao != '0.00' && $item->preco_locacao  > 0 )
        {
            $retorno = number_format($item->preco_locacao, 2, ',', '.');
        }
        if ( $item->preco_locacao_dia != '0.00' && $item->preco_locacao_dia > 0 )
        {
            $retorno = number_format($item->preco_locacao_dia, 2, ',', '.');
        }
        return $retorno;
    }

    public function _set_link( $item, $origem = NULL, $dominio = 'http://www.icuritiba.com/' )
    {
        $venda = isset($item->tipo_venda) ? $item->tipo_venda : $item->venda;
        $locacao = isset($item->tipo_locacao) ? $item->tipo_locacao : $item->locacao;
        $locacao_dia = isset($item->tipo_locacao_dia) ? $item->tipo_locacao_dia : $item->locacao_dia;
        $separador = '-';
        $retorno = strtolower($dominio).'imovel/';
        $retorno .= $venda == 1 ? 'venda'.$separador : '';
        $retorno .= $locacao == 1 ? 'locacao'.$separador : '';
        $retorno .= $locacao_dia == 1 ? 'locacao_dia'.$separador : '';
        $retorno .= $item->imoveis_tipos_link.$separador;
        $retorno .= $item->cidades_link.$separador;
        $retorno .= $item->bairros_link.$separador;
        $retorno .= $item->imobiliaria_nome_seo;
        $retorno = trim($retorno);
        $retorno .= '/'.(isset($item->id_imovel) ? $item->id_imovel : $item->id);

        if ( isset($origem) && ! empty($origem) )
        {
            $retorno .= '/'.$origem;
        }

        return $retorno;
    }
        
}