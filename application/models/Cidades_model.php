<?php
class Cidades_model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cidades');
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
                                array('nome' => 'cidades'),
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_itens_por_id_in( $id )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    							
    	$data['filtro'] = 'cidades.id in ('.$id.')';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
	
    public function get_id_por_nome_uf( $nome = '', $uf = NULL )
    {
        if ( !empty($nome) )
        {
            $data['coluna'] = '*';
            $data['tabela'] = array(
                                    array('nome' => 'cidades'),
                                    );

            $data['filtro'] = 'cidades.nome like "'.str_replace(array("'","´"), '', $nome).'" '.( isset($uf) ? 'AND cidades.uf = "'.$uf.'"' : '' );
            $consulta = $this->get_itens_($data);
            if ( $consulta['qtde'] == 1 && isset($consulta['itens'][0]) )
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
	
	
    public function get_select( $filtro = array(), $coluna = 'cidades.nome', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'cidades.id as id, CONCAT(cidades.nome, "-",cidades.uf) as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'cidades.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = noticias.id_cidade', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            cidades.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['qtde'];
    }
    
    public function get_uf_por_cidade( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '
                            facebook_groups.id_group as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'facebook_groups', 'where' => 'facebook_groups.id_cidade = cidades.id ', 'tipo' => 'INNER')
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return ( isset($retorno['itens'][0]) ? $retorno['itens'] : FALSE );
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $group = NULL, $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cidades.id as id,
                            cidades.nome as nome,
                            cidades.topo as topo,
                            cidades.portal as portal,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        if(isset($group) && $group)
        {
            $data['group'] = $group;
        }
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
}