<?php

class Imoveis_mongo_model extends MY_Mongo {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function adicionar($data = array())
    {
        return $this->insert_('imoveis', $data);
    }
    
    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_('imoveis', $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_('imoveis', $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_('imoveis', $filtro);
    }

    public function get_item($id = '')
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'][] = array('tipo' => 'where','valor' => array('_id' => $id) );
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'ordem_rad', $ordem = -1, $off_set = 0, $qtde_itens = 20 )
    {
        $data['tabela'] = 'imoveis';
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
        $data['filtro'] = $filtro;
        $data['ordem'] = array($coluna => $ordem);
        $retorno = $this->get_itens_($data);
        return isset($retorno) ? $retorno : NULL;
    }
    
}