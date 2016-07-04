<?php
if( ! defined ('BASEPATH')) exit ('No direct script access allowed');

class Teste_image_tipo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('image_tipo_model'));
    }
    
    public function index()
    {
        $this->monta_lista();
    }
    
    public function monta_lista()
    {
        $resultado = $this->image_tipo_model->get_itens();
        $lista ='<ul class="list-group">';
        $lista .='<div style="float:left">';
        foreach ($resultado['itens'] as $key => $valor) 
        {
                $lista .='<div>';
                foreach ($valor as $key => $value)
                {
                    $lista .='<li class="list-group-item"><b>'.$key.'</b></li>';
                    var_dump($valor);
                }
                $lista .='</div>';
        }
        $lista .='</div>';
        $lista .='<div style="float:right; width:1218px;">';
        foreach ($resultado['itens'] as $key => $valor) 
        {
            $lista .='<div>';
            foreach ($valor as $key => $value) 
            {
                $lista .='<li class="list-group-item">'.$value.'</li>';
            }
            $lista .='</div>';
        }
        $lista .='</div>';
        $lista .='</ul>';
//        $classe = strtolower(__CLASS__);
//        $function = strtolower(__FUNCTION__);
//        $data['tabela'] = $lista;
//        $this->layout
//                        ->set_classe( $classe )
//                        ->set_function( $function ) 
//                        ->set_include('css/estilo.css', TRUE)
//                        ->set_breadscrumbs('Painel', 'painel',0)
//                        ->set_breadscrumbs('Teste Image Tipo', 'teste_image_tipo', 1)
//                        ->set_usuario()
//                        ->set_menu($this->get_menu($classe, $function))
//                        ->view('add_teste_image_tipo',$data);
    }
}