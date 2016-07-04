<?php
class Empresas_Model extends MY_Model {
	
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'empresas');
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
    	$data['coluna'] = ' empresas.*, 
                            autorizadores.nome as autorizador_nome,
                            autorizadores.cpf as autorizador_cpf,
                            autorizadores.nascimento as autorizador_nascimento,
                            logradouros.cep as cep,
                            logradouros.logradouro as endereco,
                            logradouros.bairro as bairro,
                            hotsite_parametros.noticias as noticias,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'autorizadores', 'where' => 'empresas.id_autorizador = autorizadores.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'hotsite_parametros', 'where' => 'empresas.id = hotsite_parametros.id_empresa', 'tipo' => 'LEFT'),

                                );
    							
    	$data['filtro'] = 'empresas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_item_administrar( $id )
    {
        $data['coluna'] = ' 
                            empresas.id as id, 
                            empresas.contrato as contrato, 
                            empresas.id_subcategoria as id_subcategoria,
                            subcategorias.id_categoria as id_categoria,
                            CONCAT(subcategorias.nome," / ",categorias.nome) as subcategoria,
                            FROM_UNIXTIME(empresas.data,"%d/%m/%Y %H:%i") as data,
                            empresas.inscricao as inscricao,
                            empresas.senha as senha,
                            empresas.contato_nome as contato_nome,
                            empresas.contato_email as contato_email,
                            CONCAT(empresas.contato_ddd,empresas.contato_telefone) as contato_telefone,
                            empresas.id_autorizador as id_autorizador,
                            autorizadores.nome as autorizador_nome,
                            autorizadores.cpf as autorizador_cpf,
                            autorizadores.nascimento as autorizador_nascimento,
                            empresas.autorizador_cargo as autorizador_cargo,
                            empresas.autorizador_email as autorizador_email,
                            CONCAT(empresas.autorizador_ddd,empresas.autorizador_telefone) as autorizador_telefone,
                            empresas.id_logradouro as id_logradouro,
                            IF ( empresas.id_logradouro > 0, logradouros.logradouro, empresas.empresa_endereco ) as logradouro,
                            IF ( empresas.id_logradouro > 0, logradouros.bairro, empresas.empresa_bairro ) as bairro,
                            IF ( empresas.id_logradouro > 0, cidades.nome, "" ) as cidade,
                            IF ( empresas.id_logradouro > 0, cidades.uf, "" ) as estado,
                            IF ( empresas.id_logradouro > 0, logradouros.cep, empresas.empresa_cep ) as cep,
                            empresas.empresa_numero as empresa_numero,
                            empresas.empresa_complemento as empresa_complemento,
                            IF ( empresas.id_logradouro > 0, cidades.ddd, "" ) as ddd,
                            empresas.empresa_razao_social as empresa_razao_social,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            empresas.empresa_telefone as empresa_telefone,
                            empresas.empresa_cnpj as empresa_cnpj,
                            empresas.empresa_email as empresa_email,
                            empresas.empresa_descricao as empresa_descricao,
                            empresas.empresa_a_24h as empresa_a_24h,
                            empresas.empresa_a_internet as empresa_a_internet,
                            empresas.empresa_dominio as empresa_dominio,
                            empresas.empresa_funcionarios as empresa_funcionarios,
                            empresas.aprovado as aprovado,
                            empresas.pre_cadastro as pre_cadastro,
                            empresas.ordenacao as ordenacao,
                            empresas.descricao_linhas as descricao_linhas,
                            FROM_UNIXTIME(empresas.servicos_descricao_inicio,"%d/%m/%Y %H:%i") as servicos_descricao_inicio,
                            FROM_UNIXTIME(empresas.servicos_descricao_termino,"%d/%m/%Y %H:%i") as servicos_descricao_termino,
                            empresas.servicos_negrito as servicos_negrito,
                            FROM_UNIXTIME(empresas.servicos_negrito_inicio,"%d/%m/%Y %H:%i") as servicos_negrito_inicio,
                            FROM_UNIXTIME(empresas.servicos_negrito_termino,"%d/%m/%Y %H:%i") as servicos_negrito_termino,
                            empresas.servicos_www as servicos_www,
                            FROM_UNIXTIME(empresas.servicos_www_inicio,"%d/%m/%Y %H:%i") as servicos_www_inicio,
                            FROM_UNIXTIME(empresas.servicos_www_termino,"%d/%m/%Y %H:%i") as servicos_www_termino,
                            empresas.servicos_mapa as servicos_mapa,
                            FROM_UNIXTIME(empresas.servicos_mapa_inicio,"%d/%m/%Y %H:%i") as servicos_mapa_inicio,
                            FROM_UNIXTIME(empresas.servicos_mapa_termino,"%d/%m/%Y %H:%i") as servicos_mapa_termino,
                            empresas.mapa as mapa,
                            empresas.servicos_pagina as servicos_pagina,
                            FROM_UNIXTIME(empresas.servicos_pagina_inicio,"%d/%m/%Y %H:%i") as servicos_pagina_inicio,
                            FROM_UNIXTIME(empresas.servicos_pagina_termino,"%d/%m/%Y %H:%i") as servicos_pagina_termino,
                            empresas.pagina_logo_pequeno as pagina_logo_pequeno,
                            empresas.pagina_logo_grande as pagina_logo_grande,
                            empresas.pagina_logo_grande_tipo as pagina_logo_grande_tipo,
                            empresas.pagina_texto as pagina_texto,
                            empresas.pagina_foto1 as pagina_foto1,
                            empresas.pagina_foto1_descricao as pagina_foto1_descricao,
                            empresas.pagina_foto1_tipo as pagina_foto1_tipo,
                            empresas.pagina_foto2 as pagina_foto2,
                            empresas.pagina_foto2_descricao as pagina_foto2_descricao,
                            empresas.pagina_foto2_tipo as pagina_foto2_tipo,
                            empresas.pagina_foto3 as pagina_foto3,
                            empresas.pagina_foto3_descricao as pagina_foto3_descricao,
                            empresas.pagina_foto3_tipo as pagina_foto3_tipo,
                            empresas.pagina_foto4 as pagina_foto4,
                            empresas.pagina_foto4_descricao as pagina_foto4_descricao,
                            empresas.pagina_foto4_tipo as pagina_foto4_tipo,
                            empresas.pagina_foto5 as pagina_foto5,
                            empresas.pagina_foto5_descricao as pagina_foto5_descricao,
                            empresas.pagina_foto5_tipo as pagina_foto5_tipo,
                            empresas.pagina_funcionamento as pagina_funcionamento,
                            empresas.pagina_fax as pagina_fax,
                            empresas.pagina_titulo as pagina_titulo,
                            empresas.pagina_nome_inicial as pagina_nome_inicial,
                            empresas.pagina_titulo_negrito as pagina_titulo_negrito,
                            empresas.pagina_titulo_italico as pagina_titulo_italico,
                            empresas.pagina_link as pagina_link,
                            empresas.pagina_link_desc as pagina_link_desc,
                            empresas.pagina_limite_produtos as pagina_limite_produtos,
                            empresas.pagina_limite_ofertas as pagina_limite_ofertas,
                            empresas.pagina_limite_lancamentos as pagina_limite_lancamento,
                            empresas.pagina_atendimento as pagina_atendimento,
                            empresas.pagina_nossacidade as pagina_nossacidade,
                            empresas.servicos_clube as servicos_clube,
                            FROM_UNIXTIME(empresas.servicos_clube_inicio,"%d/%m/%Y %H:%i") as servicos_clube_inicio,
                            FROM_UNIXTIME(empresas.servicos_clube_termino,"%d/%m/%Y %H:%i") as servicos_clube_termino,
                            empresas.servicos_clube_texto as servicos_clube_texto,
                            empresas.aciap as aciap,
                            empresas.minisite_views as minisite_views,
                            empresas.pagina_visivel as pagina_visivel,
                            empresas.pagina_links as pagina_links,
                            empresas.pagina_linhas as pagina_linhas,
                            empresas.pagina_tipo as pagina_tipo,
                            empresas.pagina_creci as pagina_creci,
                            empresas.servicos_destaque_venda as servicos_destaque_venda,
                            FROM_UNIXTIME(empresas.servicos_destaque_venda_inicio,"%d/%m/%Y %H:%i") as servicos_destaque_venda_inicio,
                            FROM_UNIXTIME(empresas.servicos_destaque_venda_termino,"%d/%m/%Y %H:%i") as servicos_destaque_venda_termino,
                            empresas.servicos_destaque_locacao as servicos_destaque_locacao,
                            FROM_UNIXTIME(empresas.servicos_destaque_locacao_inicio,"%d/%m/%Y %H:%i") as servicos_destaque_locacao_inicio,
                            FROM_UNIXTIME(empresas.servicos_destaque_locacao_termino,"%d/%m/%Y %H:%i") as servicos_destaque_locacao_termino,
                            empresas.pub_guiasjp as pub_guiasjp,
                            empresas.menu_guiasjp as menu_guiasjp,
                            empresas.publicidade_scb as publicidade_scb,
                            empresas.pagina_visivel2 as pagina_visivel2,
                            empresas.pagina_visivel3 as pagina_visivel3,
                            empresas.latitude as latitude,
                            empresas.longitude as longitude,
                            empresas.servicos_sac_online as servicos_sac_online,
                            FROM_UNIXTIME(empresas.servicos_sac_online_inicio,"%d/%m/%Y %H:%i") as servicos_sac_online_inicio,
                            FROM_UNIXTIME(empresas.servicos_sac_online_termino,"%d/%m/%Y %H:%i") as servicos_sac_online_termino,
                            empresas.boletos_usuario as boletos_usuario,
                            empresas.boletos_senha as boletos_senha,
                            empresas.shopping as shopping,
                            empresas.itens_liberados as itens_liberados,
                            empresas.servicos_album as servicos_album,
                            FROM_UNIXTIME(empresas.servicos_album_inicio,"%d/%m/%Y %H:%i") as servicos_album_inicio,
                            FROM_UNIXTIME(empresas.servicos_album_termino,"%d/%m/%Y %H:%i") as servicos_album_termino,
                            empresas.servicos_album_limite as servicos_album_limite,
                            empresas.servicos_sms_limite as servicos_sms_limite,
                            empresas.empresa_fone_sms as empresa_fone_sms,
                            empresas.empresa_emaillocacao as empresa_emaillocacao,
                            empresas.palavraschave as palavraschave,
                            empresas.foto_contato as foto_contato,
                            empresas.plano_desejado as plano_desejado,
                            empresas.plano_mensal as plano_mensal,
                            empresas.dia_pgto as dia_pgto,
                            empresas.plano_promocao as plano_promocao,
                            empresas.titulo_site as titulo_site,
                            empresas.venda_ativa as venda_ativa,
                            empresas.plano_publicidade as plano_publicidade,
                            empresas.modelo as modelo,
                            empresas.desconto_pub as desconto_pub,
                            empresas.largura as largura,
                            empresas.mudou as mudou,
                            empresas.chave_empresa as chave_empresa,
                            empresas.nome_seo as nome_seo,
                            empresas.sistema as sistema,
                            empresas.empresa_emailagenda as empresa_emailagenda,
                            empresas.mobile as mobile,
                            empresas.status_atualizada as status_atualizada,
                            DATE_FORMAT(empresas.data_atualizada, "%d/%m/%Y %H:%i")as  data_atualizada,
                            DATE_FORMAT(empresas.data_abertura, "%d/%m/%Y %H:%i")as  data_abertura,
                            empresas.usuario_atualizada as usuario_atualizada,
                            empresas.conhece_guia as conhece_guia,
                            empresas.empresa_endereco as empresa_endereco,
                            empresas.empresa_cep as empresa_cep,
                            empresas.empresa_bairro as empresa_bairro,
                            DATE_FORMAT(empresas.dt_integra,"%d/%m/%Y %H:%i") as dt_integra,
                            empresas.bloqueado as bloqueado,
                            empresas.email_log as email_log,
                            empresas.newsletter_ativo as newsletter_ativo,
                            empresas.versao_powsite as versao_powsite,
                            DATE_FORMAT(empresas.ultima_integracao, "%d/%m/%Y %H:%i") as ultima_integracao,
                            empresas.tem_site as tem_site,
                            FROM_UNIXTIME(empresas.data_portal,"%d/%m/%Y %H:%i") as data_portal,
                            FROM_UNIXTIME(empresas.data_site,"%d/%m/%Y %H:%i") as data_site,
                            empresas.versao_powsite as versao_powsite,
                            empresas.plano_promocao as plano_promocao,
                            empresas.dia_pgto as dia_pgto,
                            empresas.plano_mensal as plano_mensal,
                            empresas.plano_desejado as plano_desejado,
                            empresas.desconto_pub as desconto_pub,
                            cidades.ddd as cidade_ddd,
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'subcategorias', 'where' => 'empresas.id_subcategoria = subcategorias.id', 'tipo' => 'LEFT'),
                                array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'LEFT'),
                                array('nome' => 'autorizadores', 'where' => 'empresas.id_autorizador = autorizadores.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades', 'where' => 'logradouros.id_cidade = cidades.id', 'tipo' => 'LEFT'),

                                );

        $data['filtro'] = 'empresas.id = '.$id;
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
        /**
         * empresas.id, empresas.contrato, empresas.id_subcategoria, empresas.data, empresas.inscricao, 
         * empresas.contato_nome, empresas.contato_email, empresas.contato_ddd, empresas.contato_telefone, 
         * empresas.id_autorizador, empresas.autorizador_ddd, empresas.autorizador_telefone, empresas.autorizador_cargo, empresas.autorizador_email,
         * empresas.id_logradouro, empresas.empresa_numero, empresas.empresa_complemento, empresas.empresa_telefone, empresas.empresa_email, empresas.empresa_dominio, empresas.empresa_endereco, empresas.empresa_cep, empresas.empresa_bairro
         * empresas.empresa_razao_social, empresas.empresa_nome_fantasia, empresas.empresa_cnpj,
         * empresas.empresa_descricao,
         * empresas.chave_empresa, empresas.sistema,
         * empresas.status_atualizada, empresas.data_atualizada, empresas.usuario_atualizada, empresas.conhece_guia,

         id, contrato, subcategoria, inscricao, endereco, logradouro, telefone, email, dominio, razao_social, nome_fantasia, cnpj, status, atualizou, conhece_guia
         */
    	$data['coluna'] = '	
                            empresas.id as id,
                            empresas.contrato as contrato,
                            CONCAT(subcategorias.nome, " / ", categorias.nome) as subcategoria,
                            empresas.inscricao as inscricao,
                            CONCAT( 
                                    IF (empresas.id_logradouro = 0, empresas.empresa_cep, logradouros.cep), " - ",
                                    IF (empresas.id_logradouro = 0, empresas.empresa_endereco, logradouros.logradouro), ", ",
                                    empresas.empresa_numero, " ", empresas.empresa_complemento, " - ",
                                    IF (empresas.id_logradouro = 0, empresas.empresa_bairro, logradouros.bairro), " - ",
                                    logradouros.cidade 
                                    ) as endereco,
                            IF (empresas.id_logradouro > 0, "Endereço ok", "Cadastrar logradouro") as logradouro,
                            empresas.empresa_telefone as telefone, 
                            empresas.empresa_email as email, 
                            empresas.empresa_dominio as dominio,
                            empresas.empresa_razao_social as razao_social, 
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.empresa_cnpj as cnpj,
                            status_atualizada.titulo as status,
                            usuarios.nome as atualizou,
                            IF (empresas.bloqueado = 1, "Bloqueado", "Liberado") as bloqueado,
                            IF (empresas.conhece_guia = 1, "Conhecia", "Não Conhecia") as conhece_guia
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'subcategorias', 'where' => 'empresas.id_subcategoria = subcategorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'status_atualizada', 'where' => 'empresas.status_atualizada = status_atualizada.id', 'tipo' => 'LEFT'),
                                array('nome' => 'usuarios', 'where' => 'empresas.usuario_atualizada = usuarios.id', 'tipo' => 'LEFT'),
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