<?php
class Publicidade_campanhas_Model extends MY_Model {
	
   private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'publicidade_campanhas');
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
    	$data['coluna'] = '
                            publicidade_campanhas.id as id,
                            publicidade_campanhas.id_empresa as id_empresa,
                            FROM_UNIXTIME(publicidade_campanhas.inicio,"%d/%m/%Y %H:%i") as inicio,
                            FROM_UNIXTIME(publicidade_campanhas.termino,"%d/%m/%Y %H:%i") as termino,
                            publicidade_campanhas.id_servico as id_servico,
                            publicidade_campanhas.tipo as tipo,
                            publicidade_campanhas.banner as banner,
                            publicidade_campanhas.banner_alternativo as banner_alternativo,
                            publicidade_campanhas.url as url,
                            publicidade_campanhas.expande as expande,
                            publicidade_campanhas.janela_nova as janela_nova,
                            publicidade_campanhas.diferente_interno as diferente_interno,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_campanhas'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = publicidade_campanhas.id_empresa', 'tipo' => 'INNER' ),
                                );
    							
    	$data['filtro'] = 'publicidade_campanhas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
    public function get_select( $filtro = array(), $coluna = 'publicidade_campanhas.termino', $ordem = 'DESC' )
    {
    	$data['coluna'] = 'publicidade_campanhas.id as id, CONCAT(publicidade_areas.area, " - ", FROM_UNIXTIME(publicidade_campanhas.inicio,"%d/%m/%Y "), " até " , FROM_UNIXTIME(publicidade_campanhas.termino,"%d/%m/%Y") ) as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_campanhas'),
                                array('nome' => 'publicidade_areas', 'where' => 'publicidade_campanhas.id_servico = publicidade_areas.id'),
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
                            count(publicidade_campanhas.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_campanhas'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'termino', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            publicidade_campanhas.id as id,
                            publicidade_areas.area as area,
                            publicidade_campanhas.tipo as tipo,
                            empresas.empresa_nome_fantasia as empresa,
                            FROM_UNIXTIME(publicidade_campanhas.inicio,"%d/%m/%Y ") as inicio,
                            FROM_UNIXTIME(publicidade_campanhas.termino,"%d/%m/%Y ") as termino,
                            CONCAT(FROM_UNIXTIME(publicidade_campanhas.inicio,"%d/%m/%Y "), " ate ", FROM_UNIXTIME(publicidade_campanhas.termino,"%d/%m/%Y ")) as periodo,
                            publicidade_campanhas.nome as nome,
                            publicidade_campanhas.janela_nova as janela_nova,
                            publicidade_campanhas.diferente_interno as diferente_interno,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_campanhas'),
                                array('nome' => 'publicidade_areas', 'where' => 'publicidade_areas.id = publicidade_campanhas.id_servico', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas', 'where' => 'empresas.id = publicidade_campanhas.id_empresa', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$data['group'] = 'publicidade_campanhas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    
    public function get_itens_estatistica( $id, $filtro, $off_set )
    {
        $this->load->model('publicidade_stats_model');
        if ( is_array($filtro) )
        {
            $filtro = $filtro;
        }
        else
        {
            $filtro[] = $filtro;
        }
        $filtro[] = 'id_campanha = '.$id;
        
        $retorno['total'] = $this->publicidade_stats_model->get_total_itens($filtro);
        $retorno['itens'] = $this->publicidade_stats_model->get_itens($filtro, 'publicidade_stats.ano DESC, publicidade_stats.mes DESC','',$off_set);
        return $retorno;
    }
    
    public function get_campos( $id )
    {
        $this->load->model('publicidade_stats_model');
        $filtro = 'publicidade_stats.id_campanha = '.$id;
        $retorno['lista'] = array(
                                (object)array('id' => 'referencia', 'descricao' => 'Referencia'),
                                (object)array('id' => 'views', 'descricao' => 'Views'),
                                (object)array('id' => 'clicks', 'descricao' => 'Clicks'),
                                );
        $retorno['filtro'] = array(''
                                    );
                                //(object)array('id' => 'referencia', 'descricao' => 'Referencia', 'tipo' => 'select', 'valor' => $this->publicidade_stats_model->get_select($filtro)),
        return $retorno;
    }
    
}