<?php
class Canais_Noticias_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canais_noticias');
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
                                array('nome' => 'canais_noticias'),
                                );
    							
    	$data['filtro'] = 'canais_noticias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_id( $id = '' )
    {
    	$data['coluna'] = '
                            canais_noticias.id as id,
                            canais_noticias.id_canal as id_canal,
                            canais_noticias.nome as nome,
                            canais_noticias.area as area,
                          ';
        
    	$data['tabela'] = array(
                                array('nome' => 'canais_noticias'),
                                );
    							
    	$data['filtro'] = 'canais_noticias.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'canais_noticias.id as id, canais_noticias.area as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'canais_noticias'),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = 'canais_noticias.area';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'canais_noticias.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'canais_noticias', 'where' => 'canais_noticias.id = noticias.id_canal', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            canais_noticias.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_noticias'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais_noticias.id as id,
                            canais_noticias.id_canal as id_canal,
                            canais_noticias.nome as nome,
                            canais_noticias.area as area,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_noticias'),
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