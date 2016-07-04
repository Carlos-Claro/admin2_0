<?php
class Imoveis_Naoencontrei_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_naoencontrei');
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
                                array('nome' => 'imoveis_naoencontrei'),
                                //array('nome' => 'cadastros', 'where' => 'cadastros.id = imoveis_naoencontrei.id_cadastro', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'imoveis_naoencontrei.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'pedido', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_naoencontrei.id as id, imoveis_naoencontrei.pedido as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei'),
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
                            imoveis_naoencontrei.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_naoencontrei.id as id,
                            FROM_UNIXTIME(imoveis_naoencontrei.data,"%d/%m/%Y %h:%i:%s") as data,
                            imoveis_naoencontrei.id_cadastro as id_cadastro,
                            imoveis_naoencontrei.pedido as pedido, 
                            imoveis_naoencontrei.respostas as respostas,
                            imoveis_naoencontrei.cidade_interesse as cidade_interesse, 
                            imoveis_naoencontrei.enviado as enviado,
                            cadastros.nome as nome,
                            cadastros.email as email,
                            cadastros.fone as telefone,
                            cadastros.cidade as cidade,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei'),
                                array('nome' => 'cadastros', 'where' => 'cadastros.id = imoveis_naoencontrei.id_cadastro', 'tipo' => 'INNER'),
                                //array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
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
    
    public function get_itens_email_automatico( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_naoencontrei.id as id,
                            imoveis_naoencontrei.data as data,
                            imoveis_naoencontrei.pedido as pedido, 
                            imoveis_naoencontrei.id_cidade as id_cidade, 
                            imoveis_naoencontrei.cidade_interesse as cidade_interesse, 
                            cadastros.nome as nome,
                            cadastros.cidade as cidade,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei'),
                                array('nome' => 'cadastros', 'where' => 'cadastros.id = imoveis_naoencontrei.id_cadastro', 'tipo' => 'INNER'),
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
    
    public function get_empresas_sem_resposta( $filtro = array() )
    {
        $data['coluna'] = '	
                            imoveis_naoencontrei_respostas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei_respostas'),
                                array('nome' => 'imoveis_naoencontrei', 'where' => 'imoveis_naoencontrei.id = imoveis_naoencontrei_respostas.id_naoencontrei', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
}