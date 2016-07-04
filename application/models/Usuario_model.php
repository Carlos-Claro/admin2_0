<?php

/**
 * Desabilitar e substituir para o Usuarios_model
 * Deletar após fazer as devidas alterações.
 * @deprecated 2015/05/30 version 1.0
 */
class Usuario_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'usuarios');
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
        $database['table'] = 'usuarios_setores';
        return $this->adicionar_($database, $data);
        /*
            $this->db->insert('usuarios_setores', $data); 
            return $this->db->insert_id();
         * 
         */
    }
    
    public function excluir_has($filtro)
    {
        $database = $this->database;
        $database['table'] = 'usuarios_setores';
        return $this->excluir_($database, $filtro);
        /*
        $this->db->delete('usuarios_setores',$filtro);
        return $this->db->affected_rows();
         * 
         */
    }
    
    public function adicionar_has_cargos( $data = array() )
    {
        $database = $this->database;
        $database['table'] = 'usuarios_has_cargos';
        $this->adicionar_($database, $data);
        /*
    	$this->db->insert('usuarios_has_cargos', $data); 
        return $this->db->insert_id();
         * 
         */
    }
    
    public function editar_has_cargos($data = array(),$filtro = array())
    {
        $database = $this->database;
        $database['table'] = 'usuarios_has_cargos';
        return $this->editar_($database, $data, $filtro);
        /*
        $this->db->update('usuarios_has_cargos', $data, $filtro);  
        return $this->db->affected_rows();
         * 
         */
    }

    
    public function excluir_has_cargos($filtro)
    {
        $database = $this->database;
        $database['table'] = 'usuarios_has_cargos';
        return $this->excluir_($database, $filtro);
        /*
        $this->db->delete('usuarios_has_cargos',$filtro);
        return $this->db->affected_rows();
         * 
         */
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
    	$data['filtro'] = 'usuarios.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_has( $id = '' )
    {
    	$data['coluna'] = ' IF ( setores.id_pai = 0, setores.titulo, CONCAT(b.titulo, " / ", setores.titulo) ) as descricao, usuarios_setores.id_setor as id, usuarios_setores.edita as edita ';
        
    	$data['tabela'] = array(
                                array('nome' => 'usuarios_setores'),
                                array('nome' => 'usuarios', 'where' => 'usuarios_setores.id_usuario = usuarios.id', 'tipo' => 'INNER'),
                                array('nome' => 'setores', 'where' => 'usuarios_setores.id_setor = setores.id', 'tipo' => 'INNER'),
                                array('nome' => 'setores b', 'where' => 'b.id = setores.id_pai', 'tipo' => 'LEFT')
                                );
    							
    	$data['filtro'] = 'usuarios.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens']) ? $retorno['itens'] : array();
    }
	
    public function get_usuarios_has_setores( $filtro = '' )
    {
    	$data['coluna'] = ' usuarios_setores.id_setor as id_setor, usuarios_setores.id_usuario as id_usuario, usuarios_setores.edita as edita ';
        
    	$data['tabela'] = array(
                                array('nome' => 'usuarios_setores'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno) ? $retorno : FALSE;
    }
	
	
    public function altera_senha( $id_usuario, $senha )
    {
    	$where = array('id' => $id_usuario);
    	$valor = array('senha' => $senha);
    	$table = 'usuarios';
    	$up = $this->db->update($table, $valor, $where);
    	return $up; 
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'usuarios.id as id, usuarios.nome as descricao, usuarios.email as email ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['ordem'] = 'ASC';
    	$data['col'] = 'usuarios.nome';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_has_cargo( $filtro = array() )
    {
    	$data['coluna'] = 'usuarios.id as id, usuarios.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                array('nome' => 'usuarios_has_cargos', 'where' => 'usuarios_has_cargos.id_usuario = usuarios.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['group'] = 'usuarios.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_selected_cargos( $filtro = array() )
    {
        $data['coluna'] = 'usuarios_has_cargos.id_pow_cargos as id';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                array('nome' => 'usuarios_has_cargos', 'where' => 'usuarios_has_cargos.id_usuario = usuarios.id', 'tipo' => 'INNER'),
                                );
    							
    	$data['filtro'] = $filtro;
        $consulta = $this->get_itens_($data);
        $retorno = array();
        foreach ($consulta['itens'] as $cargos )
        {
            $retorno[$cargos->id] = $cargos->id;
        }
        return $retorno;
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            usuarios.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_usuarios_cargos( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            usuarios.id as id,
                            usuarios.nome as nome,
                            usuarios.email as email,
                            pow_cargos.titulo as cargo_titulo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                array('nome' => 'pow_cargos', 'where' => 'pow_cargos.id = usuarios.id_pow_cargos', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'usuarios.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            usuarios.id as id,
                            usuarios.nome as nome,
                            usuarios.email as email,
                            if(usuarios.id_empresa = 0, usuarios.empresa, CONCAT(empresas.id, ", ", empresas.empresa_nome_fantasia) ) as empresa,
                            pow_cargos.titulo as cargo,
                            IF ( usuarios.ativo = 0, "Inativo", "Ativo" )as ativo,
                            GROUP_CONCAT(setores.titulo SEPARATOR ", ") as setores
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                array('nome' => 'empresas', 'where' => 'usuarios.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                array('nome' => 'usuarios_setores', 'where' => 'usuarios.id = usuarios_setores.id_usuario', 'tipo' => 'LEFT'),
                                array('nome' => 'setores', 'where' => 'usuarios_setores.id_setor = setores.id', 'tipo' => 'LEFT'),
                                array('nome' => 'usuarios_has_cargos', 'where' => 'usuarios_has_cargos.id_usuario = usuarios.id', 'tipo' => 'LEFT'),
                                array('nome' => 'pow_cargos', 'where' => 'pow_cargos.id = usuarios_has_cargos.id_pow_cargos', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'usuarios_has_cargos.id_usuario';
        //$data['group'] = 'usuarios.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_menu ( $filtro )
    {
        $data = $this->_set_campos_menu();
    	$data['filtro'] = $filtro.' AND id_pai = 0';
    	
    	$itens = $this->get_itens_($data);
    	if ( $itens['qtde'] > 0 )
        {
            foreach ( $itens['itens'] as $item )
            {
                $d = $this->_set_campos_menu();
                $d['filtro'] = 'setores.id_pai = '.$item->id;
                $i = $this->get_itens_($d);
                $retorno['itens'][$item->id] = $item;
                $retorno['itens'][$item->id]->itens = ( $i['qtde'] > 0 ) ? $i['itens'] : NULL;
            }
        }
        else 
        {
            $retorno = FALSE;
     
        }
    	return $retorno;
    }
    
    private function _set_campos_menu ()
    {
        $data['coluna'] = ' DISTINCT
                            setores.id as id,
                            setores.titulo as titulo,
                            setores.classe as classe
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'setores'),
                                array('nome' => 'usuarios_setores', 'where' => 'setores.id = usuarios_setores.id_setor',    'tipo' => 'INNER'),
                                array('nome' => 'usuarios',         'where' => 'usuarios.id = usuarios_setores.id_usuario', 'tipo' => 'INNER'),
                                );
        $data['col'] = 'setores.titulo';
    	$data['ordem'] = 'ASC';
        return $data;
    }
    
}