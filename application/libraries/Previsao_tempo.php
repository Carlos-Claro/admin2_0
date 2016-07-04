<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Previsao_tempo
{
	/**
	 * 
	 * Itens do campo de previsão
	 * @var $itens
	 */
	private $itens;
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
	
	public function get_html()
	{
            setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
            date_default_timezone_set('America/Sao_Paulo');
            $retorno = '';
            if ( $this->itens )
            {
                    $mes = date('M');
                    $dia = $this->itens->dia;
                    $retorno .= '<div class="pull-right" id="previsao_tempo"><div class="menor">';
                    $retorno .= '<p class="txt-white">'.strftime("%b", strtotime($mes)).'</p><p class="dia-mes">'.strftime("%d", strtotime($dia)).'</p></div>';
                    $retorno .= '<div class="maior txt-white"><img src="'.base_url().'images/clima/'.$this->itens->img.'" alt="'.$this->itens->descricao.'" />';
                    $retorno .= '<p class="text-right">SJP</p>';
                    $retorno .= '<p class="text-right txt-yellow"> Mínima: '.$this->itens->minima.'°</p><p class="text-right txt-yellow">Máxima: '.$this->itens->maxima.'°</p>';
                    $retorno .= '<p class="txt-yellow">Radiação Ultravioleta: '.$this->itens->iuv.' IUV</p></div>';
            }
            else
            {
                    $retorno.= 'Problemas com o HTML';
            }
            return $retorno; 
	}
	
	public function inicia($config)
	{
		$this->itens 	= ( isset($config['itens']) ? $config['itens'] : FALSE );
		return $this;
	}
	
}	
