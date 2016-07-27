<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{	
    private $db = NULL;
    
    public function __construct( $dbs = array('guiasjp') )	
    {		
        $this->db = NULL;
        parent::__construct();	
        foreach( $dbs as $db )
        {
            $this->db[$db] = $this->load->database($db, TRUE);
        }
    }	
    
    
    
    public function get_itens_( $data, $debug = FALSE ) 	
    {		
        $data['db'] = isset($data['db']) ? $data['db'] : 'guiasjp';
        $query = $this->db[$data['db']]->select($data['coluna'], FALSE);		
        $query->from( $data['tabela'][0]['nome'] );		
        unset( $data['tabela'][0] );		
        if ( isset($data['tabela']) && is_array($data['tabela']) )		
        {			
            foreach ( $data['tabela'] as $join )			
            {				
                $query->join($join['nome'], $join['where'], ( isset( $join['tipo'] ) ? $join['tipo'] : 'left' ) );			
                
            }		
            
        }		
        //var_dump($data['filtro']);
        if ( isset($data['filtro']) )		
        {			
            if ( is_array($data['filtro']) )			
            {				
                foreach ( $data['filtro'] as $f )				
                {
                    if(is_array($f) )
                    {
                        if ( strstr($f['tipo'],'group') )
                        {
                            $query->{$f['tipo']} ();				
                            
                        }
                        else
                        {
                            $query->{$f['tipo']} ($f['campo'], $f['valor'], ( (isset($f['unescape']) && $f['unescape']) ? FALSE : NULL ) );				
                        }
                    }
                    else
                    {
                        $query->where($f);		
                    }
                }			
            }			
            else 			
            {				
                $query->where($data['filtro']);			
            }		
            
        }		
        if ( isset( $data['group']) )		
        {			
            $query->group_by($data['group']);		
            
        }		
        if ( isset($data['ordem']) )		
        {			
            $query->order_by($data['col'], $data['ordem']);		
            
        }		
        if ( isset($data['off_set']) )		
        {			
            $query->limit( isset($data['qtde_itens']) ? $data['qtde_itens'] : N_ITENS, $data['off_set'] );		
            
        }		
        $db_load = $data['db'];
        unset($data);		 
        $retorno['itens'] = $query->get()->result();		
        $retorno['qtde'] = count($retorno['itens']);
        if ( $debug )
        {
            //var_dump($this->db->last_query());
            print_r($this->db[$db_load]->last_query());
        }
        return $retorno;	
        
    }
	
    /**
     * Adiciona informações ao banco de dados com base em db, table, data
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param type $data -> data a ser inserida
     * @return int id da inserção.
     */
    public function adicionar_( $database, $data = array() )
    {
        $database['db'] = isset($database['db']) ? $database['db'] : 'guiasjp';
    	$this->db[$database['db']]->insert($database['table'], $data); 
        return $this->db[$database['db']]->insert_id();
    }
    
    
    /**
     * Adiciona informações ao banco de dados com base em db, table, data
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param type $data -> data a ser inserida
     * @return int id da inserção.
     */
    public function adicionar_multi_( $database, $data = array() )
    {
        $database['db'] = isset($database['db']) ? $database['db'] : 'guiasjp';
    	$this->db[$database['db']]->insert_batch($database['table'], $data); 
        return $this->db[$database['db']]->insert_id();
    }
    
    /**
     * 
     * Edita informações ao banco de dados com base em db, table, data e filtro
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param array $data -> data a ser alterada 
     * @param array $filtro -> fiktro a ser utilizado
     * @return int qtde de ocorrencias
     */
    public function editar_($database, $data = array(),$filtro = array())
    {
        $database['db'] = isset($database['db']) ? $database['db'] : 'guiasjp';
        $this->db[$database['db']]->update($database['table'], $data, $filtro);  
        return $this->db[$database['db']]->affected_rows();
    }
    
    /**
     * 
     * deleta informações ao banco de dados com base em db, table, filtro
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param array $filtro -> fiktro a ser utilizado
     * @return int qtde de ocorrencias
     */
    public function excluir_($database, $filtro)
    {
        $database['db'] = isset($database['db']) ? $database['db'] : 'guiasjp';
        $this->db[$database['db']]->delete($database['table'],$filtro);
        return $this->db[$database['db']]->affected_rows();
    }
    
    /**
     * 
     * limpa a tabela ao banco de dados com base em db, table, filtro
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     */
    public function truncate_($database)
    {
        $database['db'] = isset($database['db']) ? $database['db'] : 'guiasjp';
        $this->db[$database['db']]->truncate($database['table']);
        return TRUE;
    }
    
}