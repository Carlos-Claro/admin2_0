<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Mongo extends Mongo_db
{	
    
    public function __construct()	
    {		
        parent::__construct(0);	
    }	
    
    
    
    public function get_itens_( $data, $debug = FALSE ) 	
    {		
        
        if ( isset($data['coluna']) )
        {
            $this->select($data['coluna']);		
            
        }
        if ( isset($data['filtro']) )		
        {			
            foreach ( $data['filtro'] as $f )				
            {
                $this->{$f['tipo']} ($f['valor']);				
            }			
            
        }		
        if ( isset($data['ordem']) )		
        {			
            $this->order_by($data['ordem']);		
            
        }	
        if (isset($data['qtde_itens'])) 
        { 
            $this->limit($data['qtde_itens']);
        }
        else
        {
            $this->limit(N_ITENS);
        }
        if (isset($data['off_set']) )
        {
            $this->offset($data['off_set']);
        }
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
    	$this->insert($database, $data); 
        return $this->insert_id();
    }
    
    
    /**
     * Adiciona informações ao banco de dados com base em db, table, data
     * @param array $database -> [db] = guiasjp, [table] = deifinido pelo usuario
     * @param type $data -> data a ser inserida
     * @return int id da inserção.
     */
    public function adicionar_multi_( $database, $data = array() )
    {
    	return $this->insert_batch($database, $data); 
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
        $this->update($database, $data, $filtro);  
        return $this->affected_rows();
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
        $this->delete($database,$filtro);
        return $this->affected_rows();
    }
    
}