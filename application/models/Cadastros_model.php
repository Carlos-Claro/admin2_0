<?php
class Cadastros_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'cadastros');
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
    
    /*public function adicionar_has( $data = array() )
    {
    	$retorno = $this->db->insert('cadastros', $data); 
	return $this->db->insert_id();
    }
     * 
     */
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    							
    	$data['filtro'] = 'cadastros.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_email( $filtro = NULL )
    {
    	$data['coluna'] = '
                            cadastros.id as id,
                            cadastros.id_canal as id_canal,
                            cadastros.nome as nome,
                            cadastros.email as email,
                            cadastros.sexo as sexo, 
                            cadastros.nascimento as nascimento, 
                            cadastros.fone as fone, 
                            cadastros.endereco as endereco,
                            cadastros.complemento as complemento,
                            cadastros.cep as cep,
                            cadastros.cidade as cidade,
                            cadastros.estado as estado,
                            cadastros.bairro as bairro,
                          ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_select( $filtro = array(),$coluna = 'nome', $ordem = 'ASC', $qtde_itens = NULL )
    {
    	$data['coluna'] = 'cadastros.id as id, cadastros.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        if(isset($qtde_itens))
        {
            $data['off_set'] = 0;
            $data['qtde_itens'] = $qtde_itens;
                    
        }
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    
    public function get_item_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'cadastros.id_canal as id_canal';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                array('nome' => 'canal', 'where' => 'cadastros.id_canal = canal.id_canal', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'cadastros.id = '.$filtro;
    	$retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]->id_canal) ? $retorno['itens'][0]->id_canal : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            cadastros.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            cadastros.id as id,
                            cadastros.id_canal as id_canal,
                            cadastros.data as data,
                            cadastros.nome as nome,
                            cadastros.email as email,
                            cadastros.sexo as sexo, 
                            cadastros.nascimento as nascimento, 
                            cadastros.fone as fone, 
                            cadastros.endereco as endereco,
                            cadastros.complemento as complemento,
                            cadastros.cep as cep,
                            cadastros.cidade as cidade,
                            cadastros.estado as estado,
                            cadastros.id_escolaridade as id_escolaridade,
                            cadastros.id_como as id_como,
                            cadastros.news as news,
                            cadastros.bairro as bairro,
                            cadastros.id_cidade as id_cidade,
                            cadastros.id_acesso as id_acesso,
                            cadastros.id_origem as id_origem,
                            cadastros.status as status,
                            cadastros.id_pais as id_pais,
                            cadastros.id_time as id_time,
                            cadastros.datatu as datatu,
                            cadastros.ordem as ordem,
                            cadastros.foto as foto,
                            cadastros.foto_autorizada as foto_autorizada,
                            cadastros.erro as erro,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                //array('nome' => 'subcategorias', 'where' => 'empresas.id_subcategoria = subcategorias.id', 'tipo' => 'INNER'),
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
    
}