<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de funcoes
 * @version 1.0
 * @access public
 * @package funcoes
 */
class Random extends MY_Controller 
{       
    /**
     * Cria um array para validar a pagina com os campos necessários do formulário
     * @var array
     */
    private $valida = array(
                            array( 'field'   => 'titulo',           'label'   => 'Titulo', 		'rules'   => 'required'),
                            array( 'field'   => 'classe',           'label'   => 'Classe', 		'rules'   => 'trim'),
                            array( 'field'   => 'ativo',            'label'   => 'Ativo', 		'rules'   => 'trim'),
                            array( 'field'   => 'id_pai',           'label'   => 'Setores pai', 	'rules'   => 'trim'),

                            );

    private $conn = FALSE;


    /**
     * Controi a classe e carrega valores de extends
     * e carrega models padrao para esta classe
     * @return void
     */
    public function __construct()
    {
        $valida = FALSE;
        parent::__construct($valida);
        $this->load->model(array('subcategorias_model', 'status_atualizada_model'));
    }

    private function _filtro_json()
    {
        $config = array(
                                '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ,
                                'empresas.servicos_pagina_inicio < '.time() , 
                                'empresas.servicos_pagina_termino > '.time() ,
                                'empresas.bloqueado = 0 ',   
                                'imoveis.reservaimovel = 0 ',
                                'imoveis.vendido = 0 ',
                                'imoveis.locado = 0 ',   
                                'imoveis.invisivel = 0 ',    
                                //'imoveis.ordem_rad between 1 AND 2999999',    
                                );
        return $config;
    }

    private $min = 1;
    private $max = 4000000;
    
    public function ordem_por_cidade( $id_cidade = 2 )
    {
        $this->load->model(array('imoveis_model', 'empresas_model', 'cidades_model'));
        $filtro = implode(' AND ', $this->_filtro_json() );
        $filtro_ = $filtro.' AND imoveis.id_cidade = '.$id_cidade;
        if ( $cidade->qtde <= 20 )
        {
            $edicao[] = $this->set_imoveis( $filtro_ );
        }
        else
        {
            $tipos = $this->imoveis_model->get_tipos_cidades_com_imoveis( $filtro_ );
            foreach( $tipos['itens'] as $tipo )
            {
                $filtro__ = $filtro_.' AND imoveis.id_tipo = '.$tipo->id_tipo;
                if ( $tipo->qtde <= 20 )
                {
                    $edicao[] = $this->set_imoveis( $filtro__ );
                }
                else
                {
                    $empresas = $this->imoveis_model->get_tipos_empresas_cidades_com_imoveis( $filtro__ );
                    foreach( $empresas['itens'] as $empresa )
                    {
                        $filtro___ = $filtro__.' AND imoveis.id_empresa = '.$empresa->id_empresa;
                        $edicao[] = $this->set_imoveis( $filtro___ );

                    }
                }
            }
        }
        ob_start();
        echo 'Hora inicio '.$hora_inicio.PHP_EOL.'---------------------------------------------------------';
        echo 'Hora fim '.date('Y-m-d h:i').PHP_EOL.'--------------------------------------------------------------------------------------------------------------------';
        var_dump($edicao);
        $output = ob_get_contents();
        ob_clean();
        $resumo = fopen( getcwd().'/relatorios/'.$id_cidade.'_resumo_ordem.txt','c');
        fwrite($resumo, $output);
        fclose($resumo);
        echo 'editados: '.count($edicao[0]);
        $edicao = NULL;
        
    }
    
    public function ordenacao_por_relevancia()
    {
        $this->load->model(array('imoveis_model', 'empresas_model', 'cidades_model'));
        $filtro = implode(' AND ', $this->_filtro_json() );
        $cidades = $this->imoveis_model->get_cidades_com_imoveis( $filtro );
        foreach ( $cidades['itens'] as $cidade )
        {
            //var_dump($cidade);die();
            $hora_inicio = date('Y-m-d h:i');
            $filtro_ = $filtro.' AND imoveis.id_cidade = '.$cidade->id_cidade;
            if ( $cidade->qtde <= 20 )
            {
                $edicao[] = $this->set_imoveis( $filtro_ );
            }
            else
            {
                $tipos = $this->imoveis_model->get_tipos_cidades_com_imoveis( $filtro_ );
                foreach( $tipos['itens'] as $tipo )
                {
                    $filtro__ = $filtro_.' AND imoveis.id_tipo = '.$tipo->id_tipo;
                    if ( $tipo->qtde <= 20 )
                    {
                        $edicao[] = $this->set_imoveis( $filtro__ );
                    }
                    else
                    {
                        $empresas = $this->imoveis_model->get_tipos_empresas_cidades_com_imoveis( $filtro__ );
                        foreach( $empresas['itens'] as $empresa )
                        {
                            $filtro___ = $filtro__.' AND imoveis.id_empresa = '.$empresa->id_empresa;
                            $edicao[] = $this->set_imoveis( $filtro___ );
                            
                        }
                    }
                }
            }
        }
        ob_start();
        echo 'Hora inicio '.$hora_inicio.PHP_EOL.'---------------------------------------------------------';
        echo 'Hora fim '.date('Y-m-d h:i').PHP_EOL.'--------------------------------------------------------------------------------------------------------------------';
        var_dump($edicao);
        $output = ob_get_contents();
        ob_clean();
        $resumo = fopen( getcwd().'/relatorios/resumo_ordem.txt','c');
        fwrite($resumo, $output);
        fclose($resumo);
        echo 'editados: '.count($edicao);
        $edicao = NULL;


    }

    public function set_imoveis( $filtro )
    {
        $imoveis = $this->imoveis_model->get_itens( $filtro );
        $primeiro['venda'] = FALSE;
        $primeiro['locacao'] = FALSE;
        foreach ( $imoveis['itens'] as $imovel )
        {
            $fatores = $this->tira_pontos($imovel, $primeiro);
            $retorno[$imovel->id] = $this->altera_valor($imovel->id, $fatores );
            $primeiro = $fatores['primeiro'];
        }
        return $retorno;
    }


    public function tira_pontos( $imovel, $primeiro )
    {
        $array = array('descricao','bairro_combo','foto1', array( 'preco_venda', 'preco_locacao', 'preco_locacao_dia') );
        $fatores['primeiro']['venda'] = FALSE;
        $fatores['primeiro']['locacao'] = FALSE;
        $pontos = 0;
        $venda = $imovel->venda ? TRUE : FALSE;
        $locacao = $imovel->locacao ? TRUE : FALSE;
        foreach( $array as $chave )
        {
            if ( is_array($chave) )
            {
                $ponto_falso = 0;
                foreach( $chave as $c )
                {
                    if ( $imovel->$c )
                    {
                        $ponto_falso++;
                    }
                }
                if ( ! $ponto_falso )
                {
                    $pontos++;
                }
            }
            else
            {
                if ( empty($imovel->$chave) )
                {
                    $pontos++;
                }
            }
        }
        if ( $pontos )
        {
            $valor_max = 2499999 - ( $pontos * 500000 );
            $fatores['max'] = $valor_max > 0 ? $valor_max : 500000;
            $valor_min = $fatores['max'] - 500000;
            $fatores['min'] = $valor_min >= 0 ? $valor_min : 0;
            $fatores['primeiro'][($venda ? 'venda' : 'locacao')] = $primeiro[($venda ? 'venda' : 'locacao')];
            $fatores['pontos'] = $pontos;
        }
        else
        {
            if ( $primeiro[($venda ? 'venda' : 'locacao')] )
            {
                $fatores['min'] = 3000000;
                $fatores['max'] = 3499999;
                $fatores['primeiro'][($venda ? 'venda' : 'locacao')] = $primeiro[($venda ? 'venda' : 'locacao')];
            }
            else
            {
                $fatores['min'] = 3500000;
                $fatores['max'] = 4000000;
                $fatores['primeiro'][($venda ? 'venda' : 'locacao')] = TRUE;
            }
        }
        return $fatores;
    }


    public function altera_valor($id_imovel, $fatores )
    {
        $editou['valor'] = rand( $fatores['min'], $fatores['max'] );
        $editou['fatores'] = $fatores;
        $data_editar = array( 'ordem_rad' => $editou['valor'] );
        $data_filtro = array( 'id' => $id_imovel );
        $editou['editou'] = $this->imoveis_model->editar($data_editar,$data_filtro);
        return $editou;
    }


   
}