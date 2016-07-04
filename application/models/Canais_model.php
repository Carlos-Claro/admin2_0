<?php
class Canais_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'canais');
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
    
    
    public function adicionar_has( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'canais_subcategorias';
        return $this->adicionar_($database, $data);
    }
    
    public function excluir_has($filtro)
    {
        $database = $this->database;
        $database['table'] = 'canais_subcategorias';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                );
    							
    	$data['filtro'] = 'canais.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array(),$coluna = 'titulo', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'canais.id as id, canais.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_2014()
    {
    	$data['coluna'] = 'canais.id as id, canais.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                );
    							
    	$data['filtro'] = 'tem_noticia = 1';
        $data['col'] = 'titulo';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected_2014( $id = NULL )
    {
    	$data['coluna'] = 'canais.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                array('nome' => 'noticias', 'where' => 'noticias.id_canais = canais.id', 'tipo' => 'INNER')
                                );
    							
    	//$data['filtro'] = 'canais.id = '.$filtro;
    	$data['filtro'] = 'noticias.id = '.$id;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : NULL;
    }
    
    public function get_item_selected( $filtro = NULL )
    {
    	$data['coluna'] = 'canais.id as id, canais_subcategorias.id_subcategorias as id_subcategorias';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                array('nome' => 'canais_subcategorias', 'where' => 'canais_subcategorias.id_canais = canais.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'canais.id = '.$filtro;
    	$consulta = $this->get_itens_($data);
        $retorno = array();
        foreach ($consulta['itens'] as $ativo )
        {
            $retorno[$ativo->id_subcategorias] = $ativo->id_subcategorias;
        }
        return $retorno;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            canais.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            canais.id as id,
                            canais.titulo as titulo,
                            canais.descricao as descricao,
                            canais.link as link, 
                            canais.classe as classe, 
                            canais.title as title,
                            canais.description as description, 
                            canais.posicao_menu as posicao_menu, 
                            canais.menu_ativo as menu_ativo,
                            canais.ativo as ativo,
                            canais.ordem as ordem,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'canais'),
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
    
    public function get_emfoco( $filtro = array())
    {
        $data['coluna'] = '	
                            emfoco_categorias.id as id_categoria,
                            emfoco_categorias.nome as nome_categoria,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'emfoco_categorias'),
                                //array('nome' => 'emfoco_eventos', 'where' => 'emfoco_eventos.id_categoria = emfoco_categorias.id', 'tipo' => 'INNER'),
                                //array('nome' => 'emfoco_fotos', 'where' => 'emfoco_fotos.id_evento = emfoco_eventos.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['col'] = 'emfoco_categorias.id';
    	$data['ordem'] = 'ASC';
        $pesquisa = $this->get_itens_($data);
        foreach($pesquisa['itens'] as $item)
        {
            $retorno[$item->id_categoria] = $item;
            $retorno[$item->id_categoria]->eventos = $this->get_evts('emfoco_eventos.id_categoria = '.$item->id_categoria);
        }
    	return $retorno;
    	//$retorno = $this->get_itens_($data);
    	//return $retorno['itens'];
    }
    
    public function get_evts($filtro = array())
    {
        $data['coluna'] = '	
                            emfoco_eventos.id as id_evento,
                            emfoco_eventos.titulo as titulo_evento,
                            emfoco_eventos.descricao as descricao_evento,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'emfoco_eventos'),
                                );
    	$data['filtro'] = $filtro;
        $pesquisa = $this->get_itens_($data);
        foreach($pesquisa['itens'] as $item)
        {
            $retorno[$item->id_evento] = $item;
            $retorno[$item->id_evento]->fotos = $this->get_img('id_evento = '.$item->id_evento);
        }
    	return $retorno;
    	//$retorno = $this->get_itens_($data);
    	//return $retorno['itens'];
    }
    
    public function get_img($filtro = array())
    {
        $data['coluna'] = '	
                            emfoco_fotos.id as id_foto, 
                            emfoco_fotos.foto as foto, 
                            emfoco_fotos.thumb as thumb, 
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'emfoco_fotos'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
}