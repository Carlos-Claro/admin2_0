<?php
class Imoveis_destaques_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_destaques');
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
    	$data['coluna'] = '
                            imoveis_destaques.id as id,
                            imoveis_destaques.id_vitrine as id_empresa,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = imoveis_destaques.id_empresa', 'tipo' => 'INNER' ),
                                );
    							
    	$data['filtro'] = 'imoveis_destaques.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'imoveis_destaques.cidade', $ordem = 'DESC' )
    {
    	$data['coluna'] = 'imoveis_destaques.id as id, CONCAT(imoveis_destaques.id_vitrine, " - ", cidades.nome ) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques'),
                                array('nome' => 'cidades', 'where' => 'imoveis_destaques.cidade = cidades.id'),
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
                            count(imoveis_destaques.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'termino', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_destaques.id as id,
                            imoveis_destaques.tipo as tipo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'imoveis_destaques.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}