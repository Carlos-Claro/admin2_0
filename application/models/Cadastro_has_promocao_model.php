<?php
class Cadastro_has_promocao_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cadastro_has_promocao');
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
    
    public function get_gerar_vencedor($id, $qtde)
    {
        $data['coluna'] = ' cadastros.nome as nome,
                            cadastro_has_promocao.id as id,
                            cadastro_promocao.id as id_promocao,
                            cadastros.email as email,
                            cadastros.fone as fone, 
                            cadastro_has_promocao.data_cadastro as data_cadastro,
                            cadastro_has_promocao.vencedor as vencedor'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
                                array('nome' => 'cadastros', 'where' => 'cadastro_has_promocao.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastro_promocao', 'where' => 'cadastro_has_promocao.id_promocao = cadastro_promocao.id', 'tipo' => 'LEFT'),
                               
                                );
        $data['filtro'][] = 'cadastro_promocao.id ='.$id;
        $data['filtro'][] = 'cadastros.nome !=""';
        $data['off_set'] = 0;
        $data['qtde_itens'] = $qtde;
    	$data['col'] = 'id';
    	$data['ordem'] = 'RANDOM';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_vencedor($id)
    {
        $data['coluna'] = ' cadastros.nome as nome,
                            cadastro_has_promocao.id as id,
                            cadastro_promocao.id as id_promocao,
                            cadastros.email as email,
                            cadastros.fone as fone, 
                            cadastro_has_promocao.data_cadastro as data_cadastro,
                            cadastro_has_promocao.vencedor as vencedor'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
                                array('nome' => 'cadastros', 'where' => 'cadastro_has_promocao.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastro_promocao', 'where' => 'cadastro_has_promocao.id_promocao = cadastro_promocao.id', 'tipo' => 'LEFT'),
                               
                                );
        $data['filtro'][] = 'cadastro_has_promocao.vencedor = 1 and cadastro_promocao.id ='.$id;
        $data['off_set'] = 0;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = ' cadastro_has_promocao.id as id,
                            cadastro_has_promocao.id_cadastro as id_cadastro,
                            cadastro_has_promocao.id_promocao as id_promocao,
                            cadastro_has_promocao.vencedor as vencedor,
                            DATE_FORMAT(cadastro_has_promocao.data_cadastro,"%d/%m/%Y %H:%i:%s") as data_cadastro,
                            cadastros.nome as nome,
                            cadastro_promocao.titulo as promocao';
        $data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
                                array('nome' => 'cadastros', 'where' => 'cadastro_has_promocao.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastro_promocao', 'where' => 'cadastro_has_promocao.id_promocao = cadastro_promocao.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'cadastro_has_promocao.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'vencedor', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'cadastro_has_promocao.id as id, cadastro_has_promocao.vencedor as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
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
                            cadastro_has_promocao.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
                                array('nome' => 'cadastros', 'where' => 'cadastro_has_promocao.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastro_promocao', 'where' => 'cadastro_has_promocao.id_promocao = cadastro_promocao.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cadastro_has_promocao.id as id,
                            cadastro_has_promocao.id_cadastro as id_cadastro,
                            cadastro_has_promocao.id_promocao as id_promocao,
                            cadastro_has_promocao.vencedor as vencedor,
                            DATE_FORMAT(cadastro_has_promocao.data_cadastro,"%d/%m/%Y %H:%i:%s") as data_cadastro,
                            cadastros.nome as nome,
                            cadastro_promocao.titulo as promocao,
                            cadastros.email as email,
                            cadastros.fone as fone'; 
    	$data['tabela'] = array(
                                array('nome' => 'cadastro_has_promocao'),
                                array('nome' => 'cadastros', 'where' => 'cadastro_has_promocao.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastro_promocao', 'where' => 'cadastro_has_promocao.id_promocao = cadastro_promocao.id', 'tipo' => 'LEFT'),
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