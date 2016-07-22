<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Mongo extends Mongo_db
{	
    public function __construct()	
    {		
        $this->db = NULL;
        parent::__construct(NULL);	
    }	
    
    public function get_item_($data)
    {
        if ( isset($data['filtro']) )		
        {			
            foreach ( $data['filtro'] as $f )				
            {
                $this->{$f['tipo']}(array($f['campo'] => $f['valor'] ));
            }			
            
        }
        $retorno = $this->find_one($data['tabela']);	
        return $retorno;	
    }
    
    public function get_itens_( $data, $debug = FALSE ) 	
    {		
        if ( isset($data['coluna']) )
        {
            //$this->select($data['coluna']);		
    
    }	
        if ( isset($data['filtro']) )		
        {			
            foreach ( $data['filtro'] as $f )				
            {
                $this->{$f['tipo']}(array($f['campo'] => $f['valor'] ));
            }			
            
        }		
        if ( isset($data['ordem']) )		
        {			
            $this->order_by($data['ordem']);		
            
        }	
        if ( isset($data['qtde_itens']) )
        {
            $this->limit($data['qtde_itens']);
        }
        else
        {
            $this->limit(N_ITENS);
            
        }
        if ( isset($data['off_set']) )
        {
            $this->offset($data['off_set']);
            
        }
        else
        {
            $this->offset(0);
            
        }
        //,(isset($data['qtde_itens']) ? $data['qtde_itens'] : N_ITENS),(isset($data['off_set']) ? $data['off_set'] : NULL)->result()
        $retorno['itens'] = $this->get($data['tabela']);		
        $retorno['qtde'] = count($retorno['itens']);
        if ( $debug )
        {
            print_r($this->last_query());
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
    	return $this->insert($database, (array)$data); 
    }
    
    
    /**
     * Adiciona informações ao banco de dados com base em db, table, data
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param type $data -> data a ser inserida
     * @return int id da inserção.
     */
    public function adicionar_multi_( $database, $data = array() )
    {
    	return $this->batch_insert($database, $data); 
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
        if ( isset($filtro) )		
        {			
            foreach ( $filtro as $f )				
            {
                $this->{$f['tipo']}(array($f['campo'] => $f['valor'] ));
            }			
            
        }
        
        $this->delete($database);  
        return $this->insert($database, (array)$data); 
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
        if ( isset($filtro) )		
        {			
            foreach ( $filtro as $f )				
            {
                $this->{$f['tipo']}(array($f['campo'] => $f['valor'] ));
            }			
            
        }
        $this->delete($database);
        
    }
    
}