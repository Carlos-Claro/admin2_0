<?php
/**
 * Classe que gerencia images
 * Salva em Image_arquivo
 * Liga com Image_tipo em image_pai
 * @author Carlos Claro <programacao@pow.com.br/carlos@carlosclaro.com.br>
 * @access public
 * @package GuiaSJP
 * @since 1.0
 * @version 1.0
 */
class Images_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // 
        parent::__construct();		
        $this->database = array('db' => 'guiasjp');
    }
	
    /*
     * Image arquivo
     */
    
    public function adicionar_arquivo( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'image_arquivo';
        return $this->adicionar_($database, $data);
    }
    
    public function editar_arquivo($data = array(),$filtro = array())
    {
        $database = $this->database;
        $database['table'] = 'image_arquivo';
        return $this->editar_($database, $data, $filtro);
    }

    public function excluir_arquivo($filtro)
    {
        $database = $this->database;
        $database['table'] = 'image_arquivo';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_arquivo( $filtro = array())
    {
    	$data['coluna'] = ' 
                            image_arquivo.arquivo as arquivo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                );
    							
    	//$data['filtro'] = 'image_arquivo.id = '.$id;
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_arquivo_por_id( $id = '' )
    {
    	$data['coluna'] = ' 
                            image_arquivo.arquivo as arquivo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                );
    							
    	$data['filtro'] = 'image_arquivo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_id( $id = '' )
    {
    	$data['coluna'] = ' 
                            image_arquivo.id as id_arquivo,
                            image_arquivo.arquivo as arquivo,
                            image_tipo.pasta as pasta,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                array('nome' => 'image_pai',   'where' => 'image_pai.id_image_arquivo = image_arquivo.id',      'tipo' => 'INNER' ),
                                array('nome' => 'image_tipo',  'where' => 'image_tipo.id = image_pai.id_image_tipo',  'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'image_arquivo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    	
    public function get_item_por_id_( $id = '' )
    {
    	$data['coluna'] = ' 
                            image_arquivo.id as id_arquivo,
                            image_arquivo.arquivo as arquivo,
                            image_tipo.pasta as pasta,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                array('nome' => 'image_pai',   'where' => 'image_pai.id_image_arquivo = image_arquivo.id',      'tipo' => 'INNER' ),
                                array('nome' => 'image_tipo',  'where' => 'image_tipo.id = image_pai.id_image_tipo',  'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'image_pai.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    	
    public function get_select_arquivo( $filtro = array() )
    {
    	$data['coluna'] = '
                            image_arquivo.id as id, 
                            image_arquivo.arquivo as descricao,' ;
        
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['ordem'] = 'ASC';
        $data['col'] = 'arquivo';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_arquivo_por_tipo( $id_image_tipo, $id_pai, $pagina = NULL , $aplica = NULL)
    {
    	$data['coluna'] = '
                        image_arquivo.id as id_image,
                        image_tipo.id as id_tipo_image,
                        image_tipo.tipo as tipo,
                        image_tipo.descricao as descricao,
                        image_tipo.pasta as pasta,
                        image_arquivo.arquivo as arquivo,
                        image_pai.id as id,
                        image_pai.titulo as titulo,
                        image_pai.descricao as descricao_pai,
                        image_pai.id_pai as id_pai,
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'image_arquivo'),
                                array('nome' => 'image_pai',   'where' => 'image_pai.id_image_arquivo = image_arquivo.id',      'tipo' => 'INNER' ),
                                array('nome' => 'image_tipo',  'where' => 'image_tipo.id = image_pai.id_image_tipo',  'tipo' => 'INNER'),
                                );
    	$data['filtro'] = 'image_pai.id_pai = '.$id_pai.' AND image_pai.id_image_tipo = '.$id_image_tipo;
        if ( isset($pagina) )
        {
            $data['qtde_itens'] = 6;
            $data['off_set'] = $pagina * $data['qtde_itens'];
        }
    	$retorno = $this->get_itens_($data); 
    	return $retorno;
    } 
    
    
    /*
     * Image Tipo
     */
    
    public function adicionar_tipo( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'image_tipo';
        return $this->adicionar_($database, $data);
    }
    
    public function editar_tipo($data = array(),$filtro = array())
    {
        $database = $this->database;
        $database['table'] = 'image_tipo';
        return $this->editar_($database, $data, $filtro);
    }

    public function excluir_tipo($filtro)
    {
        $database = $this->database;
        $database['table'] = 'image_tipo';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_select_tipo_image( $filtro = NULL )
    {
    	$data['coluna'] = '
                            image_tipo.id as id, 
                            image_tipo.descricao as descricao';
        
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
                                );
    							
    	$data['filtro'] = 'image_tipo.tipo = "'.$filtro.'"';
        $data['ordem'] = 'ASC';
        $data['col'] = 'descricao';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    
    public function get_tipo_por_id( $id )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
                                );
        $data['filtro'] = 'image_tipo.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_id_tipo_image($filtro = array())
    {
        $data['coluna'] = 'image_tipo.id as id, image_tipo.pasta as pasta';
    	$data['tabela'] = array(
                                array('nome' => 'image_tipo'),
                                );
        $data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    /*
     * Image Pai
     */
    
    public function adicionar_pai( $data = array() )
    {
        //$database = $this->da;
        $database = $this->database;
        $database['table'] = 'image_pai';
        return $this->adicionar_($database, $data);
    }
    
    public function editar_pai($data = array(),$filtro = array())
    {
        $database = $this->database;
        $database['table'] = 'image_pai';
        return $this->editar_($database, $data, $filtro);
    }

    public function excluir_pai($filtro)
    {
        $database = $this->database;
        $database['table'] = 'image_pai';
        return $this->excluir_($database, $filtro);
    }
    
    public function get_item_por_pai( $filtro = array() )
    {
    	$data['coluna'] = '
                            image_pai.id as id,
                            image_pai.id_image_arquivo as id_image_arquivo, 
                            image_pai.id_image_tipo as id_image_tipo, 
                            image_pai.id_pai as id_pai, 
                            image_tipo.pasta as pasta,
                            image_pai.descricao as descricao, 
                            image_arquivo.arquivo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens']) && $retorno['itens']) ? $retorno['itens'] : NULL;
    }
    
    public function get_item_por_id_pai( $id = '' )
    {
    	$data['coluna'] = '
                            image_pai.id as id,
                            image_pai.id_image_arquivo as id_image_arquivo, 
                            image_pai.id_image_tipo as id_image_tipo, 
                            image_pai.id_pai as id_pai, 
                            image_tipo.pasta as pasta,
                            image_pai.descricao as descricao, 
                            image_arquivo.id as id_arquivo,
                            image_arquivo.arquivo as arquivo
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = 'image_pai.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_por_filtro( $filtro = array() )
    {
    	$data['coluna'] = '
                            image_arquivo.arquivo,
                            DATE_FORMAT(image_arquivo.data, "%Y/%m/%d %T") as data,
                            image_pai.titulo as titulo,
                            image_pai.id_pai as id_pai,
                            image_pai.descricao as descricao,
                            image_tipo.tipo as tabela,
                            image_tipo.pasta as pasta,
                            image_arquivo.id_empresa as id_empresa,
                            empresas.empresa_nome_fantasia as empresa
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'image_arquivo.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
    public function get_arquivo_chave_por_filtro( $filtro = array() )
    {
    	$data['coluna'] = '
                            image_arquivo.arquivo,
                            image_arquivo.id as id_arquivo,
                            image_pai.titulo as titulo,
                            image_pai.ordem as ordem,
                            image_pai.id as id_image_pai,
                            image_pai.id_pai as id_pai,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'image_arquivo.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$itens = $this->get_itens_($data);
        $retorno = FALSE;
        if ( isset($itens['itens']) && $itens['qtde'] > 0 )
        {
            foreach ( $itens['itens'] as $item )
            {
                $retorno[$item->arquivo] = $item;
            }
        }
    	return $retorno;
    }
	
    	
    public function get_verifica_item( $filtro = array() )
    {
    	$data['coluna'] = '
                            image_arquivo.id
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'image_arquivo.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? TRUE : FALSE;
    }
	
    	
    public function get_total_images ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(image_arquivo.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_tipo',       'where' => 'image_pai.id_image_tipo = image_tipo.id', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo',    'where' => 'image_pai.id_image_arquivo = image_arquivo.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'image_arquivo.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'image_pai.titulo';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'image_pai.id', $ordem = 'ASC', $off_set = NULL, $qtde_itens = NULL )
    {
    	$data['coluna'] = '
                            image_arquivo.arquivo as arquivo,
                            image_arquivo.id as id_arquivo,
                            image_pai.id as id_image_pai,
                            image_pai.id_pai as id_pai,
                            DATE_FORMAT(image_arquivo.data, "%Y/%m/%d %T") as data,
                            image_pai.titulo as titulo,
                            image_pai.descricao as descricao,
                            image_tipo.tipo as tabela,
                            image_tipo.pasta as pasta,
                            image_arquivo.id_empresa as id_empresa,
                            empresas.empresa_nome_fantasia as empresa
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                array('nome' => 'image_tipo',       'where' => 'image_tipo.id = image_pai.id_image_tipo', 'tipo' => 'INNER'),
                                array('nome' => 'image_arquivo',    'where' => 'image_arquivo.id = image_pai.id_image_arquivo', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'empresas.id = image_arquivo.id_empresa', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = $qtde_itens;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'image_arquivo.id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
   
    public function get_id_image_arquivo( $id )
    {
    	$data['coluna'] = 'count(id_image_arquivo) as qtde';
    	$data['tabela'] = array(
                                array('nome' => 'image_pai'),
                                );
        $data['filtro'] = 'image_pai.id_image_arquivo = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : NULL;
    }
    
    public function get_itens_imoveis_images( $filtro = array(), $coluna = 'imoveis_images.ordem', $ordem = 'ASC', $off_set = NULL, $qtde_itens = NULL )
    {
    	$data['coluna'] = '
                            imoveis_images.id as id,
                            imoveis_images.arquivo as arquivo,
                            DATE_FORMAT(imoveis_images.data, "%Y/%m/%d %T") as data,
                            imoveis_images.titulo as titulo,
                            imoveis_images.ordem as ordem,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = $qtde_itens;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'imoveis_images.id';
    	$retorno = $this->get_itens_($data);
    	
    	return ( isset($retorno['itens']) && $retorno['qtde'] > 0 ) ? $retorno['itens'] : NULL;
    }
    
}