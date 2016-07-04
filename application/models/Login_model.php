<?php
class Login_Model extends MY_Model {

    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('guiasjp'));		
    }
    
    public function verifica($email, $senha)
    {
        $data['coluna'] = 'id as user, nome as nome, ativo as ativo';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
        $filtro[] = array( 'campo' => 'email', 'valor' => $email, 'tipo' => 'where' );
        $filtro[] = array( 'campo' => 'senha', 'valor' => md5($senha), 'tipo' => 'where' );
    	$data['filtro'] = $filtro;
        //$data['col'] = $coluna;
    	//$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0])) ? $retorno['itens'][0] : FALSE;
    }
    
    public function verifica_empresa($email, $senha)
    {
        $data['coluna'] = 'empresas.id as id_empresa, autorizadores.id as user, autorizadores.nome as nome, autorizadores.aprovado as ativo';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'autorizadores', 'where' => 'autorizadores.id = empresas.id_autorizador'),
                                );
    							
        $filtro[] = array( 'campo' => 'inscricao', 'valor' => $email, 'tipo' => 'where' );
        $filtro[] = array( 'campo' => 'senha', 'valor' => $senha, 'tipo' => 'where' );
        //$filtro = array( 'inscricao' => $email, 'senha' => $senha );
    	$data['filtro'] = $filtro;
        //$data['col'] = $coluna;
    	//$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0])) ? $retorno['itens'][0] : FALSE;
    }
    
    public function esqueceu_senha( $email )
    { 
        $data['coluna'] = 'id as id, nome as nome, ativo as ativo, email as email';
    	$data['tabela'] = array(
                                array('nome' => 'usuarios'),
                                );
    							
        $filtro[] = array( 'campo' => 'email', 'valor' => $email, 'tipo' => 'where' );
        //$filtro = array( 'email' => $email );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
        return (isset($retorno['itens'][0])) ? $retorno['itens'][0] : FALSE;
    }
}