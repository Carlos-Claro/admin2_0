<?php
class Bairros_equivalentes_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'bairros_equivalentes');
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
                                array('nome' => 'bairros_equivalentes'),
                                );
    							
    	$data['filtro'] = 'bairros_equivalentes.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_id_por_nome_cidade( $nome = '', $id_cidade = 0 )
    {
        if ( ! empty($nome) && $id_cidade )
        {
            $data['coluna'] = '*';
            $data['tabela'] = array(
                                    array('nome' => 'bairros_equivalentes'),
                                    );

            $data['filtro'] = 'bairros_equivalentes.nome_equivalente like "'.str_replace(array("'","' ","´"), '',$nome).'" AND bairros_equivalentes.id_cidade = '.$id_cidade;
            $consulta = $this->get_itens_($data);
            if ( isset($consulta['itens'][0]) )
            {
                $retorno = $consulta['itens'][0]->id_bairro;
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
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'bairros_equivalentes.id as id, bairros_equivalentes.nome_equivalente as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_equivalentes'),
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
                            bairros_equivalentes.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_equivalentes'),
                                array('nome' => 'cidades', 'where' => 'bairros_equivalentes.id_cidade = cidades.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros', 'where' => 'bairros_equivalentes.id_bairro = bairros.id', 'tipo' => 'LEFT'),
                                
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            bairros_equivalentes.id as id,
                            bairros_equivalentes.id_cidade as id_cidade,
                            bairros_equivalentes.id_bairro as id_bairro,
                            bairros_equivalentes.nome_equivalente as nome_equivalente,
                            cidades.nome as cidades,
                            bairros.nome as bairros
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_equivalentes'),
                                array('nome' => 'cidades', 'where' => 'bairros_equivalentes.id_cidade = cidades.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros', 'where' => 'bairros_equivalentes.id_bairro = bairros.id', 'tipo' => 'LEFT'),
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
