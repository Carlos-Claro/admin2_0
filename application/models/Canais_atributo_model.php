<?php
class Canais_Atributo_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canais_atributo');
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
                                array('nome' => 'canais_atributo'),
                                );
    							
    	$data['filtro'] = 'canais_atributo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_canal( $filtro = '')
    {
    	$data['coluna'] = '
                            canais_atributo.id as id,
                            canais_atributo.id_canais as id_canais,
                            canais.titulo as titulo_canais,
                            canais_atributo.id_canais_tipo_atributo as id_canais_tipo_atributo,
                            canais_atributo.id_canais_tipo_relacao as id_canais_tipo_relacao,
                            canais_atributo.id_relacionado as id_relacionado,
                            canais_atributo.qtde as qtde,
                            canais_atributo.ordem as ordem,
                            canais_atributo.camada as camada,
                            canais_atributo.n_coluna_lg_sm as n_coluna_lg_sm,
                            canais_atributo.n_coluna_md as n_coluna_md,
                            canais_atributo.n_coluna_xs as n_coluna_xs,
                            canais_atributo.tipo_ordem as tipo_ordem,
                            canais_atributo.campo_ordem as campo_ordem,
                            canais_atributo.titulo as titulo,
                            canais_atributo.qtde_caracteres_descricao as qtde_caracteres_descricao,
                            canais_atributo.qtde_colunas as qtde_colunas, 
                            canais_atributo.classe as classe, 
                            canais_atributo.classe_master as classe_master, 
                            canais_atributo.link_mais as link_mais,
                            canais_atributo.titulo_exibe as titulo_exibe, 
                            canais_atributo.posicao_image as posicao_image, 
                            canais_atributo.mostra_estrela as mostra_estrela,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_atributo'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_atributo.id_canais', 'tipo' => 'RIGHT'),
                                );
    							
    	$data['filtro'] = $filtro; //'canais_atributo.id_canais = '.$id.' AND camada like "%a%" ';
        $data['col'] = 'ordem';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais_atributo.id as id, canais_atributo.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_atributo'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_atributo.id_canais'),
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
                            canais_atributo.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_atributo'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_atributo.id_canais'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais_atributo.id as id,
                            canais_atributo.id_canais as id_canais,
                            canais.titulo as titulo_canais,
                            canais_atributo.id_canais_tipo_atributo as id_canais_tipo_atributo,
                            canais_atributo.id_canais_tipo_relacao as id_canais_tipo_relacao,
                            canais_atributo.id_relacionado as id_relacionado,
                            canais_atributo.qtde as qtde,
                            canais_atributo.ordem as ordem,
                            canais_atributo.camada as camada,
                            canais_atributo.n_coluna_lg_sm as n_coluna_lg_sm,
                            canais_atributo.n_coluna_md as n_coluna_md,
                            canais_atributo.n_coluna_xs as n_coluna_xs,
                            canais_atributo.tipo_ordem as tipo_ordem,
                            canais_atributo.campo_ordem as campo_ordem,
                            canais_atributo.titulo as titulo,
                            canais_atributo.qtde_caracteres_descricao as qtde_caracteres_descricao,
                            canais_atributo.qtde_colunas as qtde_colunas, 
                            canais_atributo.classe as classe, 
                            canais_atributo.classe_master as classe_master, 
                            canais_atributo.link_mais as link_mais,
                            canais_atributo.titulo_exibe as titulo_exibe, 
                            canais_atributo.posicao_image as posicao_image, 
                            canais_atributo.mostra_estrela as mostra_estrela,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_atributo'),
                                array('nome' => 'canais', 'where' => 'canais.id = canais_atributo.id_canais'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'titulo_canais';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_por_canal( $filtro = '', $coluna = 'ordem', $ordem = 'ASC')
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'canais_atributo'),
                                );
    							
    	$data['filtro'] = $filtro; //'canais_atributo.id_canais = '.$id.' AND camada like "%a%" ';
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    //Tipo de Atributo
    public function get_select_tipo_atributo( $filtro = array(),$coluna = 'tipo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais_tipo_atributo.id as id, canais_tipo_atributo.tipo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_tipo_atributo'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    //Tipo de Relação
    public function get_item_tipo_relacao( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'canais_tipo_relacao'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_tipo_relacao( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais_tipo_relacao.id as id, canais_tipo_relacao.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais_tipo_relacao'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
}