<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Página de gerenciamento de empresas
 * @version 1.0
 * @access public
 * @package canais
 */
class Hotsite_parametros extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hotsite_parametros_model');
    }
    public function salvar_campo()
        {
            $retorno = array();
            $dados = $this->_post(FALSE);
            if ( isset($dados['id']) && ! empty($dados['id']) && $dados['id'] )
            {
            
                if ( is_array($dados['campo']) )
                {
                    $filtro = 'hotsite_parametros.id_empresa = '.$dados['id'];
                    for( $a = 0; $a < count($dados['campo']); $a++ )
                    {
                        $data[$dados['campo'][$a]] = $dados['valor'][$a];
                        
                    }
                }
                else
                {
                    $filtro = 'hotsite_parametros.id_empresa = '.$dados['id'];
                    $data[$dados['campo']] = $dados['valor'];
                }
                $afetados = $this->hotsite_parametros_model->editar($data, $filtro);
                if ( $afetados > 0 )
                {
                    $retorno['status'] = TRUE;
                    $retorno['id'] = $dados['id'];
                    $retorno['muda_url'] = FALSE;
                }
                else
                {
                    $retorno['status'] = TRUE;
                    $retorno['id'] = $dados['id'];
                    $retorno['muda_url'] = FALSE;
                }
            }
            else
            {
                $data[$dados['campo']] = $dados['valor'];
                $retorno['id'] = $this->hotsite_parametros_model->adicionar($data);
                if ( isset($retorno['id']) && $retorno['id'] )
                {
                    $retorno['status'] = TRUE;
                    $retorno['muda_url'] = base_url().'empresas/administrar/'.$retorno['id'].'/';
                }
                else
                {
                    $retorno['status'] = FALSE;
                    $retorno['mensagem'] = 'Não foi possivel adicionar';
                    $retorno['muda_url'] = FALSE;
                }
            }
            echo json_encode($retorno);
        }
        
        private function _post( $normal = TRUE )
	{
            
            $data = $this->input->post(NULL, TRUE);
            if ( $normal )
            {
                if ( ! isset( $data['conhece_guia'] ) )
                {
                        $data['conhece_guia'] = 0;
                }
            }
            else
            {
                if ( isset($data['campo']) && $data['campo'] == 'data' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_abertura' ) 
                {
                    $data['valor'] = converte_data_mysql($data['valor']);
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_portal' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'data_site' ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && ( strstr($data['campo'], 'inicio') || strstr($data['campo'], 'termino') ) ) 
                {
                    $data['valor'] = converte_data_unixtime(converte_data_mysql($data['valor']));
                }
                if ( isset($data['campo']) && $data['campo'] == 'autorizador_telefone' ) 
                {
                    $valor = str_replace(array('(',')','-'), '', $data['valor']);
                    $a = explode(' ',$valor);
                    unset($data['valor'], $data['campo']);
                    $data['campo'][0] = 'autorizador_ddd';
                    $data['valor'][0] = $a[0];
                    $data['campo'][1] = 'autorizador_telefone';
                    $data['valor'][1] = $a[1];
                }
                if ( isset($data['campo']) && $data['campo'] == 'contato_telefone' ) 
                {
                    $valor = str_replace(array('(',')','-'), '', $data['valor']);
                    $a = explode(' ',$valor);
                    unset($data['valor'], $data['campo']);
                    $data['campo'][0] = 'contato_ddd';
                    $data['valor'][0] = $a[0];
                    $data['campo'][1] = 'contato_telefone';
                    $data['valor'][1] = $a[1];
                }
            }
            $data['usuario_atualizada'] = $this->sessao['id'];
            $data['data_atualizada'] = date('Y-m-d H:i');
            
            

            return $data;
	}
}