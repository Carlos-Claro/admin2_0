<?php
class Imoveis_equi_cs_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_equi_cs');
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
    	$data['coluna'] = 'imoveis_equi_cs.id as id,
                           imoveis_equi_cs.tipo as tipo,
                           imoveis_equi_cs.id_tipo as id_tipo,
                           imoveis_equi_cs.id_estilo as id_estilo, 
                           imoveis_equi_cs.residencial as residencial, 
                           imoveis_equi_cs.comercial as comercial,
                           imoveis_equi_cs.lazer as lazer, 
                           imoveis_equi_cs.tipo_area as tipo_area, 
                           imoveis_equi_cs.sistema as sistema,
                           imoveis_equi_cs.pendente as pendente,
                           imoveis_tipos.nome as tipos,
                           imoveis_estilos.nome as estilos,
                           sistema.nome as sistemas,
                           ';
        $data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_equi_cs.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_estilos', 'where' => 'imoveis_equi_cs.id_estilo = imoveis_estilos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'sistema', 'where' => 'imoveis_equi_cs.sistema = sistema.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'imoveis_equi_cs.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return ($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_filtro( $filtro = NULL )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'tipo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_equi_cs.id as id, imoveis_equi_cs.tipo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_tipo_area( $filtro = array(),$coluna = 'descricao', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'DISTINCT imoveis_equi_cs.tipo_area as id, imoveis_equi_cs.tipo_area as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
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
                            imoveis_equi_cs.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_equi_cs.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_estilos', 'where' => 'imoveis_equi_cs.id_estilo = imoveis_estilos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'sistema', 'where' => 'imoveis_equi_cs.sistema = sistema.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'imoveis_equi_cs.id as id,
                           imoveis_equi_cs.tipo as tipo,
                           imoveis_equi_cs.id_tipo as id_tipo,
                           imoveis_equi_cs.id_estilo as id_estilo, 
                           imoveis_equi_cs.residencial as residencial, 
                           imoveis_equi_cs.comercial as comercial,
                           imoveis_equi_cs.lazer as lazer, 
                           imoveis_equi_cs.tipo_area as tipo_area, 
                           imoveis_equi_cs.sistema as sistema,
                           imoveis_equi_cs.pendente as pendente,
                           imoveis_tipos.nome as tipos,
                           imoveis_estilos.nome as estilos,
                           sistema.nome as sistemas,
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_equi_cs.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_estilos', 'where' => 'imoveis_equi_cs.id_estilo = imoveis_estilos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'sistema', 'where' => 'imoveis_equi_cs.sistema = sistema.id', 'tipo' => 'LEFT'),
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
    
    public function get_itens_pendentes( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'imoveis_equi_cs.id as id,
                           imoveis_equi_cs.tipo as tipo,
                           imoveis_equi_cs.id_tipo as id_tipo,
                           imoveis_equi_cs.id_estilo as id_estilo, 
                           imoveis_equi_cs.residencial as residencial, 
                           imoveis_equi_cs.comercial as comercial,
                           imoveis_equi_cs.lazer as lazer, 
                           imoveis_equi_cs.tipo_area as tipo_area, 
                           imoveis_equi_cs.sistema as sistema,
                           imoveis_tipos.nome as tipos,
                           imoveis_estilos.nome as estilos,
                           sistema.nome as sistemas,
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_equi_cs'),
                                array('nome' => 'imoveis_tipos', 'where' => 'imoveis_equi_cs.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_estilos', 'where' => 'imoveis_equi_cs.id_estilo = imoveis_estilos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'sistema', 'where' => 'imoveis_equi_cs.sistema = sistema.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = 'imoveis_equi_cs.pendente = 1';
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
