<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de sincronização da dados mongodb
 * @version 1.0
 * @access public
 * @package Mongo_db
 */
class Mongo extends MY_Controller 
{       
    
    private $ponto = 500000;
    private $ponto_max_primeiro = 4000000;
    private $ponto_max_geral = 3499999;
        
    /**
     * Controi a classe e carrega valores de extends
     * e carrega models padrao para esta classe
     * @return void
     */
    public function __construct()
    {
        if ( COMMAND_LINE )
        {
            $valida = FALSE;
        }
        else
        {
            if ( isset($_GET['own']) )
            {
                $valida = FALSE;
		error_reporting(E_ALL);

            }
            else
            {
                $valida = ( isset($_GET['usuario'] ) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86' ) ? FALSE : TRUE;

            }
        }
        parent::__construct($valida);
        $this->load->library(array('Mongo_db', 'MY_Mongo'));
        $this->load->model( array('imoveis_mongo_model','cidades_mongo_model', 'imoveis_model', 'cidades_model', 'imoveis_relevancia_model') );
    }
        
    private $criterios = array( 
                                'descricao', 
                                array( 'preco_venda', 'preco_locacao', 'preco_locacao_dia'), 
                                'images', 
                                'logradouro', 
                                array('latitude', 'longitude') 
                            );
                                //'data_atualizacao', 
    
    private function _set_pontos( $imovel )
    {
        $pontos = 0;
        if ( empty($imovel->descricao) )
        {
            $pontos++;
        }
        $tem_preco = FALSE;
        foreach ( array( 'preco_venda', 'preco_locacao', 'preco_locacao_dia') as $preco )
        {
            if ( $imovel->{$preco} && $imovel->{$preco} > 0 )
            {
                $tem_preco = TRUE;
            }
        }
        if ( ! $tem_preco )
        {
            $pontos++;
        }
        if ( ! ( isset($imovel->images) && count($imovel->images) ) )
        {
            $pontos++;
        }
        if ( ! ( isset($imovel->imoveis_tipos_id) && $imovel->imoveis_tipos_id ) )
        {
            $pontos++;
        }
        /*
        $time = time() - 31536000;
        if ( $time > $imovel->data_atualizacao )
        {
            $pontos++;
        }
         * 
         *
        if ( empty($imovel->logradouro) )
        {
            $pontos++;
        }
        /*
        if ( ! ( isset($imovel->latitude) && isset($imovel->longitude) ) || ! ( empty($imovel->latitude)  && empty($imovel->longitude) )  )
        {
            $pontos++;
        }
         * 
         */
        return $pontos;
    }
    
    private function _set_range( $pontos, $filtro )
    {
        if ( $pontos )
        {
            $max_rand = $this->ponto_max_geral - ( ( $pontos * $this->ponto ) );
            $min_rand = $max_rand - ( $this->ponto );
            $ordem = rand($min_rand, $max_rand);
        }
        else
        {
            $primeiro = $this->imoveis_relevancia_model->get_total_itens($filtro['where']);
            if ( $primeiro )
            {
                $max_rand = $this->ponto_max_geral;
                $min_rand = $max_rand - $this->ponto;
                $ordem = rand($min_rand, $max_rand);
            }
            else
            {
                $max_rand = $this->ponto_max_primeiro;
                $min_rand = $max_rand - $this->ponto;
                $ordem = rand($min_rand, $max_rand);
                $this->imoveis_relevancia_model->adicionar($filtro['add']);
            }
        }
        return $ordem;
    }
    
    private function _set_ordem( $imovel )
    {
        $pontos = $this->_set_pontos($imovel);
        $filtro['where'][] = array('tipo' => 'where', 'campo' => 'id_empresa', 'valor' => $imovel->id_empresa);
        $filtro['where'][] = array('tipo' => 'where', 'campo' => 'tipo_negocio', 'valor' => $imovel->tipo);
        $filtro['where'][] = array('tipo' => 'where', 'campo' => 'id_tipo', 'valor' => $imovel->imoveis_tipos_id);
        $filtro['where'][] = array('tipo' => 'where', 'campo' => 'id_cidade', 'valor' => $imovel->cidades_id);
        $filtro['add']['id_empresa'] = $imovel->id_empresa;
        $filtro['add']['tipo_negocio'] = $imovel->tipo;
        $filtro['add']['id_tipo'] = $imovel->imoveis_tipos_id;
        $filtro['add']['id_cidade'] = $imovel->cidades_id;
        $ordem = $this->_set_range($pontos, $filtro);
        return $ordem;
    }

    public function set_sincroniza(){
            $this->sincroniza_imoveis();
    }
    
    public function sincroniza_imoveis()
    {
        $filtro_padrao = $this->filtro_padrao();
        $itens = $this->imoveis_model->get_itens_com_foto($filtro_padrao,'imoveis.data_atualizacao DESC, imoveis.id_cidade ASC','DESC, ASC',0,1000);
        if ( isset($itens['itens']) && count($itens['itens']) > 0 )
        {
            echo ' Iniciando: '.date('Y-m-d H:i:s').PHP_EOL;
            $conta = 0;
            foreach( $itens['itens'] as $item )
            {
                $update = $item;
                if ( isset($item->images) )
                {
                    $a = 0;
                    $arquivos = array();
                    foreach( $item->images as $image )
                    {
                        if ( isset($image->id) )
                        {
                            if ( $a == 0 )
                            {
                                $arquivos[$image->id] = set_arquivo_image($item->_id, $image->arquivo, $item->id_empresa, 1, TRUE, $image->id, 'destaque', TRUE);
                            }
                            else
                            {
                                $arquivos[$image->id] = set_arquivo_image($item->_id, $image->arquivo, $item->id_empresa, 1, TRUE, $image->id, 'destaque', FALSE);
                            }
                            $arquivos[$image->id]['original'] = ( isset($arquivos[$image->id]['original']) ? $arquivos[$image->id]['original'] : $image->arquivo );
                            $arquivos[$image->id]['titulo'] = (isset($image->titulo) && ! empty($image->titulo) ? $image->titulo : $item->nome );
                            $arquivos[$image->id]['id'] = $image->id;
                            $a++;
                        }
                    }
                    $update->images = $arquivos;
                    unset($arquivos);
                }
                $update->data_atualizacao = date('Y-m-d H:i:s', $item->data_atualizacao);
                $update->data_update = date('Y-m-d H:i:s');
                if ( isset($item->preco) && ! empty($item->preco) )
                {
                    $update->preco = doubleval($item->preco);
                }
                if ( isset($item->area) && ! empty($item->area) )
                {
                    $update->area = doubleval($item->area);
                }
                if ( isset($item->area_terreno) && ! empty($item->area_terreno) )
                {
                    $update->area_terreno = doubleval($item->area_terreno);
                }
                if ( isset($item->area_util) && ! empty($item->area_util) )
                {
                    $update->area_util = doubleval($item->area_util);
                }
                if ( isset($item->quartos) && ! empty($item->quartos) )
                {
                    $update->quartos = (int)$item->quartos;
                }
                if ( isset($item->garagens) && ! empty($item->garagens) )
                {
                    $update->garagens = (int)$item->garagens;
                }
                if ( isset($item->banheiros) && ! empty($item->banheiros) )
                {
                    $update->banheiros = (int)$item->banheiros;
                }
                if ( isset($item->latitude) && ! empty($item->latitude) )
                {
                    $update->latitude = (int)$item->latitude;
                }
                if ( isset($item->longitude) && ! empty($item->longitude) )
                {
                    $update->longitude = (int)$item->longitude;
                }
                $update->ordem = $this->_set_ordem($item);
                $tem = $this->imoveis_mongo_model->get_item($item->_id);
                if ( isset($tem) && $tem )
                {
                    $this->imoveis_mongo_model->editar($update);
                }
                else
                {
                    $this->imoveis_mongo_model->adicionar($update);
                }
                $data = array('integra_mongo_db' => date('Y-m-d H:i:s'), 'ordem_rad' => $update->ordem );
                $filtro = array('id' => $item->_id);
                $update_imovel = $this->imoveis_model->editar($data,$filtro  );

                $conta++;
            } 
            
            echo ' importados: '.$conta.' '.date('Y-m-d H:i:s').PHP_EOL;
        }
        else
        {
            echo ' nenhum imovel retornado na pesquisa.';var_dump($filtro_padrao).PHP_EOL;
        }
        //$this->set_sincroniza();
    }

    private function filtro_padrao()
    {
        $filtro = '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") ) '
                    . ' AND empresas.servicos_pagina_inicio < '.time().'  '
                    . 'AND empresas.servicos_pagina_termino > '.time().' '
                    . 'AND  empresas.bloqueado = 0  '
                . 'AND imoveis.reservaimovel = 0  '
                . 'AND imoveis.vendido = 0 '
                . 'AND imoveis.locado = 0 '
                . 'AND imoveis.invisivel = 0 '
                //. 'AND ( imoveis.id_cidade = 2 OR imoveis.id_cidade = 1 ) '
                //. 'AND ( imoveis.latitude is not null AND imoveis.latitude != "" ) '
                . 'AND ( imoveis.integra_mongo_db < "'.date( 'Y-m-d H:i', mktime(0, 0, 0,date("m"),date("d"),date("Y") ) ).'" OR imoveis.integra_mongo_db IS NULL )'
                ;
        return $filtro;
    }
    
    public function deleta_historico()
    {
        $this->load->model('imoveis_historico_model');
        $filtro = 'data_deleta BETWEEN "'.date( 'Y-m-d H:i', mktime(0, 0, 0,date("m"),date("d")-3,date("Y") ) ).'" AND "'.date('Y-m-d H:i').'"';
        $itens = $this->imoveis_historico_model->get_itens($filtro);
        foreach ( $itens['itens'] as $item )
        {
            $this->deleta_registro($item);
        }
    }
    
    private function deleta_registro( $item )
    {
        $images = str_replace('codEmpresa', $item->id_empresa, URL_INTEGRACAO_LOCAL).'destaque_'.$item->id.'*.*';
        shell_exec('rm -f '.$images);
        $filtro_[] = array('tipo' => 'where', 'campo' => '_id', 'valor' => $item->id);
        $this->imoveis_mongo_model->excluir($filtro_);
        unset($filtro);
    }
    
    public function limpa_relevancia()
    {
        $this->imoveis_relevancia_model->truncate();
    }
    
    
    public function get_itens_mongo()
    {
        $this->load->model(array('imoveis_mongo_model'));
        $filtro[] = array('tipo' => 'where', 'valor' => array('imoveis_tipos_link' => array('$eq' => 'apartamento')));
        //$resultado_mongo = $this->imoveis_mongo_model->get_item('899930');
        $resultado_mongo = $this->imoveis_mongo_model->get_itens($filtro);
        
        
        var_dump($resultado_mongo);

    }
    
    public function sincroniza_excluidos()
    {
        $resultado_mongo = $this->imoveis_mongo_model->get_ids();
        $deletados = 0;
        foreach($resultado_mongo['itens'] as $item)
        {
            $f = 'imoveis.id = ' . $item->_id 
                . ' AND imoveis.reservaimovel = 0  '
                . 'AND imoveis.vendido = 0 '
                . 'AND imoveis.locado = 0 '
                . 'AND imoveis.invisivel = 0 ';
            $qtde = $this->imoveis_model->get_total_itens($f);
            var_dump($qtde, $item->_id);
            if ( ! $qtde || ! isset($item->id) )
            {
                $deletados++;
                $item->id = $item->_id;
                $this->deleta_registro($item);
                var_dump($f);
            }
            unset($f, $qtde);
            //die();
        }
        var_dump('itens deletados: '. $deletados);
    }

    
    public function deleta_por_empresa( $id_empresa = FALSE )
    {
        if ( $id_empresa )
        {
            $filtro[] = array('tipo' => 'where', 'campo' => 'id_empresa', 'valor' => $id_empresa );
            $resultado_mongo = $this->imoveis_mongo_model->get_itens($filtro, 'ordem', -1, 0, 200000 );
            $deletados = 0;
            foreach($resultado_mongo['itens'] as $item)
            {
                $deletados++;
                $this->deleta_registro($item);
            }
            echo 'itens deletados: '. $deletados;
        }
        else
        {
            echo 'Nenhum resultado para esta empresa.';
        }
    }

    
    /**
     * Redireciona para o painel
     * @version 1.0
     * @access public
     */
    public function index()
    {
        redirect('painel');
    }



    private function _post()
    {
            $data = $this->input->post(NULL, TRUE);
            return $data;
    }
}
