<?php
class Onibus_Linhas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'onibus_linhas');
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
                                array('nome' => 'onibus_linhas'),
                                );
    							
    	$data['filtro'] = 'onibus_linhas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'onibus_linhas.id as id, onibus_linhas.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'onibus_linhas'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_categoria( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'onibus_categorias.id as id, onibus_categorias.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'onibus_categorias'),
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
                            onibus_linhas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'onibus_linhas'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            onibus_linhas.id as id,
                            onibus_linhas.id_categoria as id_categoria,
                            onibus_linhas.nome as nome,
                            onibus_linhas.itinerario as itinerario,
                            onibus_categorias.nome as nome_categoria,
                            onibus_linhas.texto as texto,
                            onibus_linhas.link as link, 
                            onibus_linhas.altura_iframe as altura_iframe, 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'onibus_linhas'),
                                array('nome' => 'onibus_categorias', 'where' => 'onibus_categorias.id = onibus_linhas.id_categoria', 'tipo' => 'INNER'),
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