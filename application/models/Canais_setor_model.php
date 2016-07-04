<?php
class Canais_Setor_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canais_setor');
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
                                array('nome' => 'canais_setor'),
                                );
    							
    	$data['filtro'] = 'canais_setor.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_pai( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                );
    							
    	$data['filtro'] = 'canais_setor.id_pai = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais_setor.id as id, canais_setor.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_pai( $filtro = array(),$coluna = 'canais_setor.titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '
                            canais_setor.id as id, 
                            IF(setor.id_pai = 0, CONCAT(canais_setor.titulo, " (", setor.titulo, ")" ), CONCAT(canais_setor.titulo," (", canais.titulo,")") ) as descricao 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_setor.id_canais', 'tipo' => 'INNER'),
                                array('nome' => 'canais_setor setor', 'where' => 'setor.id = canais_setor.id_pai', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'canais_setor.id_canais as id_canais';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_setor.id_canais', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'canais_setor.id = '.$filtro;
        $retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id_canais) ? $retorno['itens'][0]->id_canais : NULL;
    }
    
    public function get_select_conteudo( $filtro = array() )
    {
    	$data['coluna'] = 'canais_setor.id as id, CONCAT(canais_conteudo.titulo," / ",canais_setor.titulo) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                array('nome' => 'canais_conteudo', 'where' => 'canais_conteudo.id_canais_setor = canais_setor.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            canais_setor.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais_setor.id as id,
                            canais_setor.id_canais as id_canais,
                            canais.titulo as titulo_canal,
                            canais_setor.id_pai as id_pai,
                            canais_setor.titulo as titulo,
                            canais_setor.link as link, 
                            canais_setor.descricao as descricao,
                            canais_setor.title as title,
                            canais_setor.description as description, 
                            canais_setor.ordem as ordem,
                            canais_setor.ativo as ativo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_setor.id_canais', 'tipo' => 'INNER'),
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
    
    // Itens do Pai
    public function get_itens_pai($id = '', $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais_setor.id as id,
                            canais_setor.id_canais as id_canais,
                            canais.titulo as titulo_canal,
                            (SELECT canais_setor.titulo FROM canais_setor WHERE canais_setor.id = '.$id.') as titulo_pai, 
                            canais_setor.id_pai as id_pai,
                            canais_setor.titulo as titulo,
                            canais_setor.link as link, 
                            canais_setor.descricao as descricao,
                            canais_setor.title as title,
                            canais_setor.description as description, 
                            canais_setor.ordem as ordem,
                            canais_setor.ativo as ativo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_setor.id_canais', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'canais_setor.id';
    	$retorno = $this->get_itens_($data,1);
    	
    	return $retorno;
    }
    
    //
    public function get_itens_canal( $id = '' )
    {
    	$data['coluna'] = '
                            canais.id as id, 
                            canais.titulo as titulo,
                            canais_setor.id as id_setor, 
                            canais_setor.titulo as titulo_setor 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                array('nome' => 'canais_setor', 'where' => 'canais_setor.id_canais = canais.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'canais.id = '.$id;
    	$itens = $this->get_itens_($data);
        $retorno = array();
        if(isset($itens) && $itens)
        {
            foreach($itens['itens'] as $item)
            {
                $pai = $this->get_itens_setor_pai($item->id_setor);
                if(isset($pai) && $pai)
                {
                    foreach($pai as $valor)
                    {
                        $retorno[$item->id][$valor->id_pai][] = $valor;
                    }
                }
            }
        }
    	return $retorno;
    }
    
    public function get_itens_setor_pai( $id = '' )
    {
    	$data['coluna'] = '
                            canais_setor.id as id, 
                            canais_setor.id_pai as id_pai, 
                            canais_setor.titulo as titulo 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_setor'),
                                );
    							
    	$data['filtro'] = 'canais_setor.id_pai = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens']) && $retorno['itens']) ? $retorno['itens'] : NULL;
    }
    
}