<?php
class Bairros_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'bairros');
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
                                array('nome' => 'bairros'),
                                );
    							
    	$data['filtro'] = 'bairros.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_id_por_nome_cidade( $nome = '', $id_cidade = 0 )
    {
        if ( ! empty($nome) && $id_cidade )
        {
            $data['coluna'] = '*';
            $data['tabela'] = array(
                                    array('nome' => 'bairros'),
                                    );

            $data['filtro'] = 'bairros.nome like "'.str_replace(array("'","' ","´"), '',$nome).'" AND bairros.cidade = '.$id_cidade;
            $consulta = $this->get_itens_($data);
            if ( isset($consulta['itens'][0]) )
            {
                $retorno = $consulta['itens'][0]->id;
            }
            else
            {
                $retorno = NULL;
            }
            
        }
        else
        {
            $retorno = NULL;
        }
        
    	return $retorno;
    }
    
    public function get_select( $filtro = array(),$coluna = 'cidades.nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'bairros.id as id, CONCAT(bairros.nome, " - ", cidades.nome, "/", cidades.uf) as descricao, bairros.link as link ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'cidades', 'where' => 'bairros.cidade = cidades.id', 'tipo' => 'LEFT'),
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
                            bairros.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'cidades', 'where' => 'bairros.cidade = cidades.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            bairros.id as id,
                            bairros.codigo as codigo,
                            bairros.nome as nome,
                            bairros.cidade as cidade,
                            bairros.mapa as mapa,
                            bairros.zona as zona, 
                            bairros.libera as libera, 
                            bairros.link as link, 
                            cidades.nome as nome_cidades
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'cidades', 'where' => 'bairros.cidade = cidades.id', 'tipo' => 'LEFT'),
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
