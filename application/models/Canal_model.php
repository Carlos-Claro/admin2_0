<?php
class Canal_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canal');
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
                                array('nome' => 'canal'),
                                );
    							
    	$data['filtro'] = 'canal.id_canal = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_id( $id = '' )
    {
    	$data['coluna'] = '
                            canal.id_canal as id,
                            canal.desc_canal as nome,
                            canal.assinatura as assinatura,
                          ';
        
    	$data['tabela'] = array(
                                array('nome' => 'canal'),
                                );
    							
    	$data['filtro'] = 'canal.id_canal = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_nome_por_id( $id = '')
    {
    	$data['coluna'] = '
                            canal.desc_canal as titulo,
                          ';
        
    	$data['tabela'] = array(
                                array('nome' => 'canal'),
                                );
    							
    	$data['filtro'] = 'canal.id_canal = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'canal.id_canal as id, canal.desc_canal as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'canal'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            canal.id_canal as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canal'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canal.id_canal as id,
                            canal.desc_canal as nome,
                            canal.assinatura as assinatura,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canal'),
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