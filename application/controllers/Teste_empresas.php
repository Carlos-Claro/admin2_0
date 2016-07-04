<?php
if( ! defined ('BASEPATH')) exit ('No direct script access allowed');

class Teste_empresas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('empresas_model'));
    }
    
    public function index()
    {
        $this->monta_tabela();
    }
    
    public function monta_tabela()
    {
        $resultado = $this->empresas_model->get_itens_teste();
        $contador = 0;
        $tabela  = '<table class = "table table-bordered table-striped">';
        foreach ( $resultado['itens'] as $key => $valor )
        {
            if( $contador == 0 )
            {
                $tabela .= '<tr>';
                foreach ( $valor as $key => $value ) 
                {
                    $tabela .= '<th>'.$key.'</th>';
                    //var_dump($valor);
                }
                $tabela .= '</tr>';
            }
            else
            {
                $tabela .= '<tr>';
                foreach ( $valor as $key => $value ) 
                {
                    $tabela .= '<td align = "center">'.$value.'</td>';
                }
                $tabela .= '</tr>'; 
            }
            $contador++;
        }
        $tabela .= '</table>';
        $classe = strtolower(__CLASS__);
        $function = strtolower(__FUNCTION__);
        $data['tabela'] = $tabela;
        $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Teste Empresas', 'teste_empresas', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('add_teste_empresas',$data);	
    }
    
    
    public function monta_cabecalho()
    {
        $resultado = $this->empresas_model->get_itens_teste();
        $contador = 0;
        //$tabela  = '<table BORDER>';
        foreach ($resultado['itens'] as $key => $valor)
        {
            if($contador == 0)
            {
                //$tabela .= '<tr>';
                foreach ($valor as $key => $value) 
                {
                    //$tabela .= '<th>'.$key.'</th>';
                    echo $key.' ';
                }
                //$tabela .= '</tr>';
            }
            $contador++;
        }
        //$tabela .= '</table>';
    }
    
}

