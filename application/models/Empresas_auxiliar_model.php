<?php
class Empresas_auxiliar_Model extends MY_Model {
	
private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas_auxiliar');
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
                            contrato,
                    id_subcategoria,	
                    data,	
                    inscricao,
                    senha,
                    contato_nome,
                    contato_email,	
                    contato_ddd,
                    contato_telefone,
                    id_autorizador,	
                    autorizador_ddd,	
                    autorizador_telefone,	
                    autorizador_cargo,	
                    autorizador_email,	
                    id_logradouro,	
                    empresa_razao_social,	
                    empresa_nome_fantasia,	
                    empresa_numero,	
                    empresa_complemento,	
                    empresa_telefone,	
                    empresa_cnpj,	
                    empresa_email,	
                    empresa_descricao,
                    empresa_dominio,	
                    empresa_funcionarios,	
                    aprovado,	
                    pre_cadastro,	
                    latitude,	
                    longitude,	
                    pagina_creci,	
                    empresa_endereco,	
                    empresa_bairro,	
                    empresa_cep,	
                    plano_desejado,
                    plano_mensal,	
                    dia_pgto,	
                    pagina_tipo,	
                    plano_promocao,	
                    plano_publicidade,	
                    venda_ativa,
                    modelo,
                    desconto_pub,	
                    data_portal,	
                    aprovado,	
                    data_site	

                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_auxiliar'),

                                );
    							
    	$data['filtro'] = 'empresas_auxiliar.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas.id as id, empresas.empresa_nome_fantasia as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = N_ITENS )
    {
    	$data['coluna'] = '	
                            empresas_auxiliar.id as id,
                            empresas_auxiliar.contrato as contrato,
                            CONCAT(subcategorias.nome, " / ", categorias.nome) as subcategoria,
                            FROM_UNIXTIME(empresas_auxiliar.data,"%d/%m/%Y %H:%i") as data,
                            empresas_auxiliar.inscricao as inscricao,
                            empresas_auxiliar.contato_nome as contato_nome,
                            empresas_auxiliar.contato_email as contato_email,
                            CONCAT(empresas_auxiliar.contato_ddd, " - ", empresas_auxiliar.contato_telefone) as contato_telefone,
                            autorizadores.nome as autorizador,
                            autorizadores.nascimento as autorizador_nascimento,
                            autorizadores.cpf as autorizador_cpf,
                            CONCAT(empresas_auxiliar.autorizador_ddd, " - ", empresas_auxiliar.autorizador_telefone) as autorizador_telefone,
                            empresas_auxiliar.autorizador_email as autorizador_email,
                            IF (empresas_auxiliar.id_logradouro > 0, "Endereço cadastrado", "Cadastrar logradouro") as logradouro,
                            IF (empresas_auxiliar.id_logradouro > 0, logradouros.logradouro, empresas_auxiliar.empresa_endereco) as endereco,
                            IF (empresas_auxiliar.id_logradouro > 0, logradouros.bairro, empresas_auxiliar.empresa_bairro) as bairro,
                            empresas_auxiliar.empresa_numero as empresa_numero,
                            empresas_auxiliar.empresa_complemento as empresa_complemento,
                            empresas_auxiliar.empresa_bairro,
                            empresas_auxiliar.empresa_telefone as empresa_telefone, 
                            empresas_auxiliar.empresa_email as empresa_email, 
                            empresas_auxiliar.empresa_razao_social as empresa_razao_social, 
                            empresas_auxiliar.empresa_nome_fantasia as empresa_nome_fantasia, 
                            empresas_auxiliar.empresa_cnpj as empresa_cnpj,
                            empresas_auxiliar.id_cidade	as id_cidade,
                            CONCAT(cidades.nome, "-", cidades.uf) as cidade,
                            empresas_auxiliar.empresa_cep as empresa_cep,
                            empresas_auxiliar.pagina_creci as pagina_creci,
                            empresas_auxiliar.aprovado as aprovado,
                            planos_sites.nome as plano_desejado,	
                            planos_mensal.nome as plano_mensal,	
                            "<a href=\"http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/Cnpjreva_Solicitacao.asp\" target=\"_blank\">Conferir cnpj</a>" as conferir_cnpj,
                            "<a href=\"http://www.receita.fazenda.gov.br/Aplicacoes/ATCTA/CPF/ConsultaPublica.asp\" target=\"_blank\">Conferir cpf</a>" as conferir_cpf,
                            empresas_auxiliar.dia_pgto as dia_pgto

                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_auxiliar'),
                                array('nome' => 'subcategorias', 'where' => 'empresas_auxiliar.id_subcategoria = subcategorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = empresas_auxiliar.id_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros', 'where' => 'empresas_auxiliar.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'autorizadores', 'where' => 'empresas_auxiliar.id_autorizador = autorizadores.id', 'tipo' => 'LEFT'),
                                array('nome' => 'planos_mensal', 'where' => 'empresas_auxiliar.plano_mensal = planos_mensal.id', 'tipo' => 'LEFT'),
                                array('nome' => 'planos_sites', 'where' => 'empresas_auxiliar.plano_desejado = planos_sites.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
                $data['qtde_itens'] = $qtde_itens;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_campanhas( $filtro = array(), $coluna = 'empresas.empresa_nome_fantasia', $ordem = 'ASC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            DISTINCT empresas.id as id_empresa,
                            empresas.empresa_nome_fantasia as empresa,
                            empresas.empresa_telefone as telefone,
                            logradouros.cidade as cidade,
                            logradouros.bairro as bairro,
                            status_atualizada.titulo as status,
                            CONCAT(subcategorias.nome, "(", categorias.nome ,")") as classificacao,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'subcategorias', 'where' => 'subcategorias.id = empresas.id_subcategoria', 'tipo' => 'INNER'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = subcategorias.id_categoria', 'tipo' => 'INNER'),                
                                array('nome' => 'logradouros', 'where' => 'logradouros.id = empresas.id_logradouro', 'tipo' => 'INNER'),
                                array('nome' => 'status_atualizada', 'where' => 'empresas.status_atualizada = status_atualizada.id', 'tipo' => 'LEFT'),                    
                                array('nome' => 'cidades', 'where' => 'cidades.id = logradouros.id_cidade', 'tipo' => 'INNER'),                    
                                //array('nome' => 'empresas_pow_campanhas', 'where' => 'empresas_pow_campanhas.id_empresas <> empresas.id ', 'tipo' => 'INNER'),                    
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
    
    public function get_total_itens_campanhas( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'subcategorias', 'where' => 'subcategorias.id = empresas.id_subcategoria', 'tipo' => 'INNER'),
                                array('nome' => 'categorias', 'where' => 'categorias.id = subcategorias.id_categoria', 'tipo' => 'INNER'),                
                                array('nome' => 'logradouros', 'where' => 'logradouros.id = empresas.id_logradouro', 'tipo' => 'INNER'),
                                array('nome' => 'status_atualizada', 'where' => 'empresas.status_atualizada = status_atualizada.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    
    public function get_item_cadastro( $filtro = NULL )
    {
        $data['coluna'] = '
                            empresas.id as id,
                            empresas.empresa_razao_social as empresa_razao_social,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            empresas.empresa_cnpj as empresa_cnpj,
                            empresas.inscricao as inscricao,
                            empresas.senha as senha,
                            empresas.empresa_telefone as empresa_telefone,
                            empresas.id_logradouro as id_logradouro,
                            empresas.empresa_numero as empresa_numero,
                            empresas.empresa_complemento as empresa_complemento,
                            IF (empresas.id_logradouro = 0, empresas.empresa_cep, logradouros.cep) as cep,
                            IF (empresas.id_logradouro = 0, empresas.empresa_endereco, logradouros.logradouro) as endereco,
                            IF (empresas.id_logradouro = 0, empresas.empresa_bairro, logradouros.bairro) as bairro,
                            logradouros.id_cidade as id_cidade,
                            logradouros.cidade as cidade,
                            empresas.empresa_descricao as empresa_descricao,
                            empresas.empresa_email as empresa_email,
                            empresas.empresa_dominio as empresa_dominio,
                            empresas.contato_nome as contato_nome,
                            empresas.empresa_email as empresa_email,
                            empresas.contato_email as contato_email,
                            empresas.contato_ddd as contato_ddd,
                            empresas.autorizador_email as autorizador_email,
                            autorizadores.nome as autorizador_nome,
                            empresas.contato_telefone as contato_telefone,
                            empresas.id_subcategoria as id_subcategoria,
                            empresas.conhece_guia as conhece_guia,
                            empresas.status_atualizada as status_atualizada
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'autorizadores', 'where' => 'empresas.id_autorizador = autorizadores.id', 'tipo' => 'LEFT'),
                                );
        $data['filtro'] = isset($filtro) ? $filtro : 'logradouros.id_cidade = 1 AND empresas.status_atualizada IN (9)';
        $data['col'] = 'empresas.id';
        $data['ordem'] = 'random';
        
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
        
    }
    
     /*
     * @deprecated 
     * Função utilizada apenas para atualizar a base de dados da empresa de contatos
     * identificando quem é o autorizador  e contato
     */
    public function get_itens_contato($filtro = array())
    {
        $data['coluna'] = '
                            empresas.id as id,
                            empresas.contato_nome as contato_nome,
                            empresas.contato_email as contato_email,
                            empresas.autorizador_email as autorizador_email,
                            autorizadores.nome as autorizador_nome,
                            empresas.autorizador_cargo as autorizador_cargo
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'autorizadores', 'where' => 'autorizadores.id = empresas.id', 'tipo' => 'INNER'),
                                );
        //$data['filtro'] = isset($filtro) ? $filtro : 'logradouros.id_cidade = 1 AND empresas.status_atualizada IN (9)';
        if(isset($filtro) && $filtro)
        {
            $data['filtro'] = $filtro;
        }
        $retorno = $this->get_itens_($data,1);
        return isset($retorno['itens']) ? $retorno['itens'] : FALSE;
    }
    
    
    public function get_itens_por_sistema ( $sistema = NULL )
    {
        
        $data['coluna'] = '
                            empresas.id as id, 
                            empresas.chave_empresa as chave, 
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.sistema as sistema, 
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                );
        $data['filtro'] = array(
                                'empresas.servicos_pagina_inicio < '.time(),
                                'empresas.servicos_pagina_termino > '.time(),
                                'empresas.pagina_visivel > 0',
                                );
        if ( isset($sistema) )
        {
            $data['filtro'][] = 'empresas.sistema = '.$sistema;
        }
        else
        {
            $data['filtro'][] = 'empresas.sistema > 0';
            $data['filtro'][] = '( empresas.ultima_integracao IS NULL OR empresas.ultima_integracao < "'.  date('Y-m-d').'" )';
        }
                                
        $data['col'] = 'empresas.id';
        $data['ordem'] = 'ASC';
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens']) ? $retorno['itens'] : FALSE;
    }
    
    public function get_valores_por_empresa ( $empresa = NULL )
    {
        $data['coluna'] = '
                            empresas.id as id, 
                            empresas.chave_empresa as chave, 
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.sistema as sistema, 
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                );
        if ( $empresa != 82075 )
        {
            $data['filtro'] = array(
                                    'empresas.servicos_pagina_inicio < '.time(),
                                    'empresas.servicos_pagina_termino > '.time(),
                                    'empresas.pagina_visivel > 0',
                                    );
        }
        if ( isset($empresa) )
        {
            $data['filtro'][] = 'empresas.id = '.$empresa;
        }
        else
        {
            die('id_invalido - erro - 355 - forçando porta.');
        }
                                
        $data['col'] = 'empresas.id';
        $data['ordem'] = 'ASC';
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens']) ? $retorno['itens'] : FALSE;
    }
    
    public function get_email_por_empresa( $id_empresa )
    {
        $data['coluna'] = '
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia, 
                            empresas.contato_nome as contato_nome, 
                            empresas.contato_email as contato_email, 
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                );
        $data['filtro'] = 'empresas.id = '.$id_empresa;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
        
    }
    
    public function get_itens_por_empresa( $id_empresa )
    {
        $data['coluna'] = '
                            empresas.id as id, 
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia, 
                            empresas.contato_nome as contato_nome, 
                            empresas.contato_email as contato_email, 
                            empresas.pagina_limite_ofertas as pagina_limite_ofertas, 
                            empresas.pagina_limite_produtos as pagina_limite_produtos, 
                            empresas.contato_nome as contato_nome, 
                            empresas.contato_email as contato_email, 
                            empresas.email_log as email_log, 
                            cidades.portal as portal
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT' ),
                                array('nome' => 'cidades', 'where' => 'logradouros.id_cidade = cidades.id', 'tipo' => 'LEFT')
                                );
        $data['filtro'] = 'empresas.id = '.$id_empresa;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
        
    }
}