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
class Imoveis_images_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis_images');
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
    
    public function adicionar_multi( $data = array() )
    {
        return $this->adicionar_multi_($this->database, $data);
    }
    
    public function get_arquivo( $filtro = array())
    {
    	$data['coluna'] = ' 
                            imoveis_images.arquivo as arquivo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens'][0]) && $retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis_images.id as id, 
                            imoveis_images.arquivo as descricao,' ;
        
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['ordem'] = 'ASC';
        $data['col'] = 'arquivo';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_item_por_imovel( $id_imovel )
    {
    	$data['coluna'] = '
                            imoveis_images.id as id,
                            imoveis_images.id_imovel as id_imovel, 
                            imoveis_images.id_empresa as id_empresa, 
                            imoveis_images.titulo as titulo,
                            imoveis_images.data as data,
                            imoveis_images.ordem as ordem,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                );
    							
    	$data['filtro'] = 'imoveis_images.id_imovel = '.$id_imovel;
        $data['ordem'] = 'ASC';
        $data['col'] = 'ordem';
    	$retorno = $this->get_itens_($data);
    	return (isset($retorno['itens']) && $retorno['itens']) ? $retorno['itens'] : NULL;
    }
    
    
    public function get_item_por_filtro( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis_images.id as id,
                            imoveis_images.id_imovel as id_imovel, 
                            imoveis_images.id_empresa as id_empresa, 
                            imoveis_images.titulo as titulo,
                            imoveis_images.data as data,
                            imoveis_images.ordem as ordem,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                array('nome' => 'imoveis',         'where' => 'imoveis_images.id_imovel = imoveis.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'imoveis_images.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
    public function get_verifica_item( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis_images.id
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->id) ? TRUE : FALSE;
    }
	
    	
    public function get_total_images ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(imoveis_images.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                array('nome' => 'imoveis',         'where' => 'imoveis_images.id_imovel = imoveis.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'imoveis_images.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'imoveis_images.id';
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'imoveis_images.id', $ordem = 'ASC', $off_set = NULL, $qtde_itens = NULL )
    {
    	$data['coluna'] = '
                            imoveis_images.id as id,
                            imoveis_images.id_imovel as id_imovel, 
                            imoveis_images.id_empresa as id_empresa, 
                            imoveis_images.titulo as titulo,
                            imoveis_images.data as data,
                            imoveis_images.ordem as ordem
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
                                array('nome' => 'imoveis',         'where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',         'where' => 'empresas.id = imoveis_images.id_empresa', 'tipo' => 'LEFT'),
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
    	
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_arquivo_chave_por_filtro( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis_images.arquivo as arquivo,
                            imoveis_images.id as id,
                            imoveis_images.titulo as titulo,
                            imoveis_images.id_empresa as id_empresa,
                            imoveis_images.id_imovel as id_imovel,
                            imoveis_images.ordem as ordem
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_images'),
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
    
}