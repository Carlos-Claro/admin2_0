<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de integrações
 * @version 1.0
 * @access public
 * @package integracao
 */
class Integracao extends MY_Controller 
{
        /**
         * Constroi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct(FALSE);
            $this->load->model( array('empresas_model','imoveis_model') );
            $this->load->library('MY_XML');
            //$this->load->library('xml_formatos');
	}
        
        public function testa_cidade()
        {
            $this->load->model('cidades_model');
            $cidade = 'curitiba';
            $cidades = $this->cidades_model->get_id_por_nome_uf($cidade);
            var_dump($cidades);
        }
        
        /**
         * seta a classe listar
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->my_xml->inicia();
            die();
	}
        
        
        public function processa_xml()
        {
            $this->my_xml->processa_xml();
        }
        
        public function pega_todos (  )
        {
            error_reporting(E_ALL);
            $empresas = $this->empresas_model->get_itens_por_sistema();
            if ( $empresas && count($empresas) > 0 )
            {
                $respostas = $this->my_xml->get_por_empresas( $empresas );
            }
            else
            {
                die('Nenhuma empresas retornada, verifique sua consulta.');
            }
            
        }
        
        public function por_empresa ( $empresa = FALSE )
        {
            error_reporting(E_ALL);
            if ( $empresa )
            {
                $empresas = $this->empresas_model->get_valores_por_empresa($empresa);
                var_dump($empresas);die();
                if ( $empresas && count($empresas) > 0 )
                {
                    $respostas = $this->my_xml->get_por_empresas( $empresas );
                    sleep(10);
                    $this->my_xml->altera_arquivos();
                    sleep(10);
                    $this->my_xml->processa_xml();
                    //$this->_processa_resposta_pega_arquivos($respostas);
                }
                else
                {
                    die('Nenhuma empresas retornada, verifique sua consulta.');
                }
            }
            else
            {
                die('Envie um id correto.');
            }
        }
        
        public function por_sistema ( $sistema = '' )
        {
            //error_reporting(E_ALL);
            if ( ! empty($sistema) )
            {
                $empresas = $this->empresas_model->get_itens_por_sistema($sistema);
                if ( $empresas && count($empresas) > 0 )
                {
                    $respostas = $this->my_xml->get_por_empresas( $empresas );
                    //$this->_processa_resposta_pega_arquivos($respostas);
                }
                else
                {
                    die('Nenhuma empresas retornada, verifique sua consulta.');
                }
            }
            else
            {
                die('Nenhum sistema selecionado');
            }
        }
	
        public function processa_arquivos( )
        {
            $this->my_xml->altera_arquivos();
            
        }
        
        public function get_xml ( )
        {
        }
        
        private function deleta_pasta( $diretorio )
        {
            if ( is_dir($diretorio) )
            {
                $pastas = scandir($diretorio);
                foreach( $pastas as $arquivo )
                {
                    if ( $arquivo != '.' && $arquivo != '..' )
                    {
                        unlink($diretorio.$arquivo);
                    }
                }
                rmdir($diretorio);
            }
        }
        
        public function limpa_pastas()
        {
            $raiz = $this->my_xml->pasta_raiz;
            $dia_mes_ano_atual = date('Y-m-d');
            $array_data_atual = explode('-',$dia_mes_ano_atual);
            $dia_mes_ano_deleta = date('Y-m-d', strtotime('-4 day'));
            $array_data_deleta = explode('-',$dia_mes_ano_deleta);
            $originais = $raiz.'originais/'.$array_data_deleta[0].'/'.$array_data_deleta[1].'/'.$array_data_deleta[2].'/';
            $modificados = $raiz.'modificados/'.$array_data_deleta[0].'/'.$array_data_deleta[1].'/'.$array_data_deleta[2].'/';
            $processados_originais = $raiz.'processados/originais/'.$array_data_deleta[0].'/'.$array_data_deleta[1].'/'.$array_data_deleta[2].'/';
            $processados_modificados = $raiz.'processados/modificados/'.$array_data_deleta[0].'/'.$array_data_deleta[1].'/'.$array_data_deleta[2].'/';
            $download = $raiz.'download/'.$array_data_deleta[0].'/'.$array_data_deleta[1].'/'.$array_data_deleta[2].'/';
            $this->deleta_pasta($originais);
            $this->deleta_pasta($modificados);
            $this->deleta_pasta($processados_originais);
            $this->deleta_pasta($processados_modificados);
            $this->deleta_pasta($download);
            //$pasta_originais = scandir($originais);
            
            
            
        }
        
        public function download( $id_empresa = NULL )
        {
            if ( isset($id_empresa) )
            {
                 $empresas = $this->empresas_model->get_valores_por_empresa($id_empresa);
                if ( $empresas && count($empresas) > 0 )
                {
                    $respostas = $this->my_xml->get_arquivo_por_empresa( $empresas );
                    $this->load->helper('download');
                    foreach ( $respostas as $item )
                    {
                        if ( $item['erro']['status'] )
                        {
                            var_dump( $item['erro']['mensagem'] );
                        }
                        else
                        {
                            $data = file_get_contents($item['arquivo']);
                            $arquivo = $id_empresa.'-'.time().'.xml';
                            force_download($arquivo,$data);
                        }
                    }
                    
                }
                
            }
        }
        
}