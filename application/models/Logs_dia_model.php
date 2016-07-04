<?php
class Logs_dia_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('logs'));		
    }
	
    public function get_itens_group_data( $controller = 'empresas', $filtro = array(), $off_set = NULL, $qtde_itens = N_ITENS )
    {
        $group = 'logs_dia.data';
        $coluna = 'logs_dia.data'; 
        $ordem = 'DESC';
    	$data['coluna'] = '	
                            CONCAT( "'.$controller.'", "/", logs_dia.id_tabela, "/", DATE_FORMAT(logs_dia.data, "%Y-%m-%d") ) AS id,
                            DATE_FORMAT(logs_dia.data, "%w") AS dayweek,
                            DATE_FORMAT(logs_dia.data, "%d-%m-%Y") AS data,
                            SUM(logs_dia.views) AS views,
                            SUM(logs_dia.clicks) AS clicks
                            ';
                            //logs_dia.id_tabela AS id_tabela,
                            //GROUP_CONCAT(locais.nome SEPARATOR ", ") as locais,
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
    	$data['group'] = 'logs_dia.data';
    	$data['db'] = 'logs';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_total_views_clicks( $controller = 'empresas', $filtro = array(), $off_set = NULL, $qtde_itens = N_ITENS )
    {
        //$group = 'logs_dia.data';
        $coluna = 'logs_dia.id_local'; 
        $ordem = 'DESC';
    	$data['coluna'] = '	
                            SUM(logs_dia.views) AS views,
                            SUM(logs_dia.clicks) AS clicks
                            ';
                            //logs_dia.id_tabela AS id_tabela,
                            //GROUP_CONCAT(locais.nome SEPARATOR ", ") as locais,
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
    	//$data['group'] = 'logs_dia.data';
    	$data['db'] = 'logs';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_total_group_data( $controller = 'empresas', $filtro = array() )
    {
        $group = 'logs_dia.data';
        $coluna = 'logs_dia.id_local'; 
        $ordem = 'ASC';
    	$data['coluna'] = '	
                            logs_dia.id
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'logs_dia.data';
    	$data['db'] = 'logs';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_total_group_locais( $controller = 'empresas', $filtro = array() )
    {
        $group = 'logs_dia.data';
        $coluna = 'logs_dia.id_local'; 
        $ordem = 'ASC';
    	$data['coluna'] = '	
                            logs_dia.id
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'logs_dia.id_local';
    	$data['db'] = 'logs';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens_group_locais( $controller = 'empresas', $filtro = array(), $group = 'logs_dia.id_local', $coluna = 'logs_dia.id_local', $ordem = 'ASC', $off_set = NULL, $qtde_itens = N_ITENS )
    {
    	$data['coluna'] = '	
                            CONCAT( "'.$controller.'", "/", logs_dia.id_tabela, "/", locais.id ) AS id,
                            locais.nome as nome,
                            CONCAT(locais.resumo, " - ", locais.onde_esta) as descricao,
                            SUM(logs_dia.views) AS views,
                            SUM(logs_dia.clicks) AS clicks
                            ';
                            
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['off_set'] = NULL;
        $data['qtde_itens'] = $qtde_itens;
    	$data['group'] = 'logs_dia.id_local';
    	$data['db'] = 'logs';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    

    public function get_itens( $filtro = array(), $group = 'logs_dia.data', $coluna = 'logs_dia.id_local', $ordem = 'ASC', $off_set = NULL, $qtde_itens = N_ITENS )
    {
    	$data['coluna'] = '	
                            logs_dia.id_tabela as id_tabela,
                            logs_dia.id_local as id_local,
                            DATE_FORMAT(logs_dia.data, ,"%Y-%m-%d") as data,
                            SUM(logs_dia.views) as views,
                            SUM(logs_dia.clicks) as clicks,
                            locais.id as id_local,
                            locais.nome as local_titulo,
                            locais.onde_esta as local_resumo,
                            locais.onde_esta as local_onde_esta,
                            
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'logs_dia'),
                                array('nome' => 'locais', 'where' => 'logs_dia.id_local = locais.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['off_set'] = NULL;
        $data['qtde_itens'] = $qtde_itens;
    	$data['group'] = 'logs_dia.data';
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data,1);
    	
    	return $retorno;
    }
    
}