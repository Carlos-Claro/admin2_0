<?php
use Gregwar\GnuPlot\GnuPlot;

/**
 * Classe de biblioteca que utiliza o composer com a classe Gregwar/GnuPlot/GnuPlot
 * Monta graficos descartaveis guardados numa planilha temposraria
 * @version 0.1
 * @since 20160331
 */
class Gnuplot_base {
    
    private $plot = NULL;
    private $empresa = array();
    private $item = array();
    private $itens = array();
    private $images = array();
    private $tipo = FALSE;
    
    
    public function __construct() {
        $this->plot = new Gnuplot;
    }
    /**
     * 
     * @param type $data[empresa]
     * @param type $data[itens]
     * @param type $data[images]
     * @param type $data[tipo]
     * @return string
     */
    public function get_images( $data )
    {
        $this->empresa = $data->empresa;
        $this->itens = $data->itens;
        $this->images = $data->images;
        $this->tipo = $data->tipo;
        
        $retorno = $this->_processa_dados();
        
        return $retorno;
    }
    
    private function _processa_dados()
    {
        $retorno = (object)array();
        $retorno->status = FALSE;
        if ( isset($this->images) )
        {
            if ( is_object($this->images) )
            {
                foreach ( $this->images as $chave => $image )
                {
                    $retorno->$chave = $this->_set_image($image);
                }
            }
            else
            {
                $retorno[] = $this->_set_image($this->images);
            }
            $retorno->status = TRUE;
        }
        else
        {
            throw new Exception('nenhuma imagem setada');
        }
        return $retorno;
    }
    
    private function _set_image( $image )
    {
        $retorno = FALSE;
        if ( isset($this->itens) && $this->itens['qtde'] > 0 )
        {
            $titulo = $image->titulo;
            unset($push);
            $push = array();
            $max_range = 0;
            $min_range = 100000;
            foreach ( $this->itens['itens'] as $item )
            {
                $chave_0 = $image->push[0];
                $chave_1 = $image->push[1];
                $push[] = array($item->$chave_0, $item->$chave_1);
                $max_range = ( $item->$chave_1 > $max_range  ) ? $item->$chave_1 : $max_range;
                $min_range = ( $item->$chave_1 < $min_range  ) ? $item->$chave_1 : $min_range;
            }
            $retorno = $this->histogram($titulo, $push, $min_range, $max_range);
        }
        else
        {
            throw new Exception('Sem itens Setados');
        }
        return $retorno;
    }
    
    private function histogram($titulo, $pushes, $min_range, $max_range)
    {
        $arquivo = $this->item->id.'-'.str_replace(' ', '',$titulo).'-'.time().'.png';
        $endereco_salva = CWD_PLOT.''.$arquivo;
        $endereco_retorno = URL_IMAGE_PLOT.''.$arquivo;
        $this->plot
            ->setTitle(0, $titulo)
            ->setGraphTitle($titulo)
            ;
        $this->plot
            ->setXLabel('Datas')
            ->setXTimeFormat('%d-%m-%Y')
            ;
        $this->plot
            ->setYLabel('Valores')
            ->setYRange(($min_range -1),$max_range )            
            ;
        $this->plot
            ->enableHistogram()
            ;
                
        foreach( $pushes as $push )
        {
            $this->plot
                ->push($push[0], (int)$push[1])
                ;
            
        }
        $this->plot
            ->setWidth(570)
            ->setHeight(350)
            ;
        $this->plot
            ->writePng($arquivo)
            ;
        sleep(5);
        if (file_exists(CWD_IMAGE.'/admin2_0/'.$arquivo) )
        {
            copy(CWD_IMAGE.'/admin2_0/'.$arquivo, $endereco_salva);
            unlink(CWD_IMAGE.'/admin2_0/'.$arquivo);
        }
        return (object)array('url' => $endereco_retorno);
    }
    
    public function get_views_clicks_por_data($data)
    {
        $this->item = $data->item;
        $this->itens = $data->itens;
        $this->tipo = $data->tipo;
        
        $retorno = FALSE;
        if ( isset($this->itens) && $this->itens['qtde'] > 0 )
        {
            $titulo = 'Views';
            unset($push);
            $push = array();
            $max_range = 0;
            $min_range = 100000;
            foreach ( $this->itens['itens'] as $item )
            {
                $chave_0 = 'Data';
                $chave_1 = 'Views';
                $push[] = array($item->data, $item->views);
                $max_range = ( $item->views > $max_range  ) ? $item->views : $max_range;
                $min_range = ( $item->views < $min_range  ) ? $item->views : $min_range;
            }
            $retorno = $this->histogram($titulo, $push, $min_range, $max_range);
        }
        else
        {
            throw new Exception('Sem itens Setados');
        }
        return $retorno;
        
    }
    
    
    private function linhas( $data )
    {
        $plot
            ->setXLabel('X')
            ->setYRange(0, 20)
            ->setYLabel('Y')
            ->push(0, 1)
            ->push(1, 10)
            ->push(2, 3)
            ->addLabel(2, 3, 'This is a good point')
            ->push(3, 2.6)
            ->push(4, 5.3)
            ->setTitle(0, 'The curve')
            ->writePng('date.png')
            ;

    }
    
}