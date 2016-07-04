<?php
class Empresas_has_imoveis_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('logs'));		
    }
	
    public function adicionar( $data = array() )
    {
        $database = array('db' => 'logs','table' => 'empresas_has_imoveis');
        return $this->adicionar_($database, $data);
    }
    
    public function adicionar_multi( $data = array() )
    {
        $database = array('db' => 'logs','table' => 'empresas_has_imoveis');
        return $this->adicionar_multi_($database, $data);
    }

    public function excluir($filtro)
    {
        $database = array('db' => 'logs','table' => 'empresas_has_imoveis');
        return $this->excluir_($database, $filtro);
    }
    
    
    public function get_max_item(  )
    {
    	$data['coluna'] = '	
                            MAX(id_imovel) as ultimo_imovel
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_has_imoveis'),
                                );
    	$data['db'] = 'logs';
        $data['col'] = 'id_imovel';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->ultimo_imovel) ? $retorno['itens'][0]->ultimo_imovel : FALSE;
    }
    
    
    
}