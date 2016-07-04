<?php
class Culinaria_Receitas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'culinaria_receitas');
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
                                array('nome' => 'culinaria_receitas'),
                                );
    							
    	$data['filtro'] = 'culinaria_receitas.id = '.$id;
    	$retorno = $this->get_itens_($data);
        if ( isset($retorno['itens'][0]) )
        {
            $ret['item'] = $retorno['itens'][0];
            $this->load->model('images_model');
            $filtro = 'image_pai.id_pai = '.$ret['item']->id.' AND image_pai.id_image_tipo = 11 AND image_pai.moderada = 0';
            $ret['image_moderar'] = $this->images_model->get_item_por_pai($filtro);
            
        }
    	return $ret;
    }
    
    public function get_select( $filtro = array(), $coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'culinaria_receitas.id as id, culinaria_receitas.titulo as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_receitas '),
                                //array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'culinaria_receitas.id_categoria as id';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_receitas'),
                                array('nome' => 'culinaria_categorias', 'where' => 'culinaria_categorias.id = culinaria_receitas.id_categoria', 'tipo' => 'INNER')
                                );
    							
    	$data['filtro'] = 'culinaria_receitas.id = '.$filtro;
        $retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            culinaria_receitas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_receitas'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = 'Distinct	
                            culinaria_receitas.id as id,
                            culinaria_receitas.nome as nome,
                            culinaria_receitas.data as data,
                            culinaria_receitas.data_cadastro as data_cadastro,
                            culinaria_receitas.titulo as titulo,
                            culinaria_receitas.ingredientes as ingredientes,
                            culinaria_receitas.modo_preparo as modo_preparo,
                            culinaria_receitas.imagem as imagem,
                            culinaria_receitas.email as email,
                            culinaria_receitas.telefone as telefone,
                            IF ( culinaria_receitas.liberado = 0, "Aguardando","Liberado") as liberado,
                            culinaria_receitas.aceito as aceito,
                            culinaria_categorias.nome as categoria,
                            count(distinct image_pai.id) as qtde_fotos,
                            IF (count(image_moderada.id) > 0, "sim", "não") as fotos_moderar
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'culinaria_receitas'),
                                array('nome' => 'culinaria_categorias', 'where' => 'culinaria_categorias.id = culinaria_receitas.id_categoria', 'tipo' => 'INNER'),
                                array('nome' => 'culinaria_receitas_cadastro', 'where' => 'culinaria_receitas_cadastro.id_culinaria_receitas = culinaria_receitas.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cadastros', 'where' => 'culinaria_receitas_cadastro.id_cadastro = cadastros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'image_pai', 'where' => 'culinaria_receitas.id = image_pai.id_pai AND image_pai.id_image_tipo = 11 ', 'tipo' => 'LEFT'),
                                array('nome' => 'image_pai image_moderada', 'where' => 'culinaria_receitas.id = image_moderada.id_pai AND image_pai.id_image_tipo = 11 AND image_pai.moderada = 0', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'culinaria_receitas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
}