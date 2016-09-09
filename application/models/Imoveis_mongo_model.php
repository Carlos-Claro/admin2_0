<?php

class Imoveis_mongo_model extends MY_Mongo {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * @param type $data
     * @return type
     */
    public function adicionar($data = array())
    {
        return $this->adicionar_('imoveis', $data);
    }

    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_('imoveis', $data);
    }

    public function editar($data = array())
    {
        $filtro[] = array('tipo' => 'where', 'campo' => '_id', 'valor' => $data->_id);
        return $this->editar_('imoveis', $data, $filtro, array('upsert'=>TRUE));
    }

    public function excluir($filtro)
    {
        return $this->excluir_('imoveis', $filtro);
    }

    public function get_item($id = '')
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'][] = array('tipo' => 'where', 'campo' => '_id', 'valor' => $id);
        $retorno = $this->get_item_($data);
        return $retorno;
    }
    
    /**
     * 
     * @param type $filtro
     * @param type $coluna
     * @param int $ordem girando entre -1 e 1
     * @param int $off_set paginador
     * @param int $qtde_itens limitador de resultado
     * @return object com o conteudo dos itens
     */
    public function get_itens( $filtro = array(), $coluna = 'ordem', $ordem = -1, $off_set = 0, $qtde_itens = 20 )
    {
        $data['tabela'] = 'imoveis';
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
        $data['filtro'] = $filtro;
        $data['ordem'] = array($coluna => $ordem);
        $retorno = $this->get_itens_($data);
        return isset($retorno) ? $retorno : NULL;
    }
    
    public function get_ids ()
    {
        $data['coluna']= array('id');
        $data['tabela'] = 'imoveis';
        //$data['off_set'] = $off_set;
        $data['qtde_itens'] = 50000;
        //$data['filtro'] = $filtro;
        $data['ordem'] = array('_id' => 'ASC');
        $retorno = $this->get_itens_($data);
        return isset($retorno) ? $retorno : NULL;
        
    }
    
}