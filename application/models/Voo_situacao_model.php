<?php
class Voo_Situacao_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'voo_situacao');
    }
	
    public function adicionar( $data = array() )
    {
        return $this->adicionar_($this->database, $data);
    }
    
    public function editar($data = array(),$filtro = array())
    {
        return $this->editar_($this->database, $data, $filtro);
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'voo_situacao'),
                                );
    							
    	$data['filtro'] = 'voo_situacao.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'voo_situacao.id as id, voo_situacao.qtde_voos as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'voo_situacao '),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            voo_situacao.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'voo_situacao'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            voo_situacao.id as id,
                            voo_situacao.qtde_voos as qtde_voos,
                            voo_situacao.atrasados as atrasados,
                            voo_situacao.atrasados_momento as atrasados_momento,
                            voo_situacao.cancelados as cancelados,
                            voo_situacao.data as data,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'voo_situacao'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}