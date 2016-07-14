<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de Logs
 * @version 1.2
 * @access public
 * @package logs
 */
class Logs extends MY_Controller 
{       
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct(FALSE);
            $this->load->model(array('logs_model'));
	}
        
        /*
                                'data_agrupamento' => array(
                                                'campo' => 'data', 
                                                'proximo' => 'cidade', 
                                                'comparacao' => 'LIKE', 
                                                'fecha' => FALSE,
                                                'is_null' => FALSE,
                                                ) , */
        private $campos = array(
                                'cidade' => array(
                                                'campo' => 'cidade', 
                                                'proximo' => 'negocio', 
                                                'comparacao' => 'LIKE', 
                                                'fecha' => FALSE,
                                                'is_null' => FALSE,
                                                ) , 
                                'negocio' => array(
                                                'campo' => 'negocio', 
                                                'proximo' => 'tipo', 
                                                'comparacao' => 'LIKE', 
                                                'fecha' => FALSE,
                                                'is_null' => TRUE,
                                                ) , 
                                'tipo' => array(
                                                'campo' => 'tipo', 
                                                'proximo' => 'bairro', 
                                                'comparacao' => 'LIKE', 
                                                'fecha' => FALSE,
                                                'is_null' => TRUE,
                                                ) , 
                                'bairro' => array(
                                                'campo' => 'bairro', 
                                                'proximo' => 'valor_min', 
                                                'comparacao' => '=', 
                                                'fecha' => FALSE,
                                                'is_null' => TRUE,
                                                ) , 
                                'valor_min' => array(
                                                'campo' => 'valor_min', 
                                                'proximo' => 'valor_max', 
                                                'comparacao' => '=', 
                                                'fecha' => TRUE,
                                                'is_null' => FALSE,
                                                ) , 
                                );
        
        public function index()
        {
            die('inativo.');
            $todos_dias = $this->log_pesquisa_portais_model->get_qtde_group();
            if ( isset($todos_dias['itens']) && $todos_dias['qtde'] > 0 )
            {
                foreach( $todos_dias['itens'] as $dias )
                {
                    if ( isset($filtro) )
                    {
                        unset($filtro);
                    }
                    if ( isset($valores) )
                    {
                        unset($valores);
                    }
                    $filtro_dia = 'data BETWEEN "'.$dias->data_agrupamento.' 00:00" AND "'.$dias->data_agrupamento.' 23:59:59" ';
                    $por_dia = $this->log_pesquisa_portais_model->get_qtde_group($filtro_dia,'cidade');
                    if ( isset($por_dia['itens']) && $por_dia['qtde'] > 0 )
                    {
                        if ( $por_dia['qtde'] == 1 )
                        {
                            $this->set_inclusao($filtro_dia, 'cidade');
                        }
                        else
                        {
                            foreach( $por_dia['itens'] as $dia )
                            {
                                $inclusao = $this->set_por_dia($dia, $filtro_dia);
                            }
                        }
                    }
                    unset($filtro);
                }
            }
            //var_dump($por_data);
            
        }
        
        public function set_correcao_dias()
        {
            $menor_dia = $this->logs_model->get_min_date();
            var_dump($menor_dia);die('dia');
            $dia = $menor_dia;
            
            $logs = $this->logs_model->get_itens_insert_dia($dia);
            if ( isset($logs['itens']) && $logs['qtde'] > 0 )
            {
                foreach ( $logs['itens'] as $item )
                {
                    $add[] = (array)$item;
                }
                $this->logs_model->adicionar_dia($add);
            }
            $filtro_deleta = 'logs_insert.data = "'.$dia.'"';
            $deletados_ = $this->logs_model->excluir_insert($filtro_deleta);
            var_dump($deletados);
        }
        
        public function set_por_dia( )
        {
            $dia = date('Y-m-d',mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
            
            $logs = $this->logs_model->get_itens_insert_dia($dia);
            if ( isset($logs['itens']) && $logs['qtde'] > 0 )
            {
                foreach ( $logs['itens'] as $item )
                {
                    $add[] = (array)$item;
                }
                $this->logs_model->adicionar_dia($add);
            }
            $filtro_deleta = 'logs_insert.data < "'.$dia.'"';
            $this->logs_model->excluir_insert($filtro_deleta);
        }
        
        private function set_inclusao( $filtro, $campo, $unico = TRUE )
        {
            $item_por_valor = $this->log_pesquisa_portais_model->get_qtde_group($filtro,$campo);
            if ( $unico )
            {
                $array_inclusao = $this->_set_array_inclusao($item_por_valor['itens'][0]);
                var_dump($array_inclusao);
                //$this->log_pesquisa_portais_por_dia_model->adicionar($array_inclusao);
            }
            else
            {
                foreach( $item_por_valor['itens'] as $item )
                {
                    $array_inclusao = $this->_set_array_inclusao($item);
                    var_dump($array_inclusao);
                    //$this->log_pesquisa_portais_por_dia_model->adicionar($array_inclusao);
                }
            }
            return TRUE;
        }
        
        private function _set_array_inclusao( $item )
        {
            $array = array();
            $array['data'] = $item->data_agrupamento;
            $array['cidade'] = $item->cidade;
            $array['bairro'] = $item->bairro;
            $array['negocio'] = $item->negocio;
            $array['tipo'] = $item->tipo;
            $array['valor_min'] = $item->valor_min;
            $array['valor_max'] = $item->valor_max;
            $array['qtde'] = $item->qtde;
            return $array;
        }
        
        public function get_estatistica_empresa( $id_empresa = FALSE )
        {
            $this->load->library('Gnuplot_base');
            $this->load->model(array('empresas_model', 'logs_dia_model'));
            $retorno = FALSE;
            if ( $id_empresa )
            {
                //$empresa = $this->empresas_model->get_item($id_empresa);
                $itens = $this->logs_dia_model->get_itens_group_data('locais.tabela = "empresas" AND id_tabela = '.$id_empresa);
                $data_plot = (object)array(
                                    'itens' => $itens,
                                    'images' => (object)array(
                                        'views' => (object)array(
                                                    'titulo' => 'Views por_dia',
                                                    'push' => array('data', 'views'),
                                        ),
                                        'clicks' => (object)array(
                                                    'titulo' => 'Clicks por dia',
                                                    'push' => array('data', 'clicks'),
                                        ),
                                    ),
                                    'empresa' => $empresa,    
                                    'tipo' => 'histogram',
                                );
                try
                {
                    $plot = $this->gnuplot_base->get_images($data_plot);
                    if ( $plot->status )
                    {
                        echo '<img src="'.$plot->views->url.'">';
                        echo '<img src="'.$plot->clicks->url.'">';
                    }
                    
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
            }
            
        }
        
}
                                