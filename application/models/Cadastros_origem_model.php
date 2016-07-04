<?php
class Cadastros_origem_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cadastros_origem');
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
    
    public function get_max_id()
    {
        $query = $this->db->select_max('id_origem');
        $retorno = $query->get('cadastros_origem')->result();
        return isset($retorno[0]->id_origem)?($retorno[0]->id_origem + 1) : 0;
    }

    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
        $data['tabela'] = array(
                                array('nome' => 'cadastros_origem'),
                                );
    							
    	$data['filtro'] = 'cadastros_origem.id_origem = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'cadastros_origem.id_origem as id_origem, cadastros_origem.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros_origem'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            cadastros_origem.id_origem as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros_origem'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id_origem', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cadastros_origem.id_origem as id,
                            cadastros_origem.destino as destino,
                            cadastros_origem.titulo as titulo, 
                            cadastros_origem.vantagens as vantagens,
                            cadastros_origem.outras_vantagens as outras_vantagens'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastros_origem'),
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