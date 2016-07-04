<?php

class Imoveis_Model extends MY_Model
{

    private $database = NULL;

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->database = array('db' => 'guiasjp', 'table' => 'imoveis');
    }

    public function adicionar($data = array())
    {
        return $this->adicionar_($this->database, $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_($this->database, $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }

    public function get_item($id = '')
    {
        $data['coluna'] = '*';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
        );

        $data['filtro'] = 'imoveis.id = ' . $id;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }

    public function get_item_por_filtro($filtro = NULL)
    {
        $data['coluna'] = ' 
                            imoveis.id as id,
                            imoveis.nome as nome, 
                            IF (imoveis.venda = 1, "venda", "locacao" ) as negocio, 
                            imoveis.id_tipo as id_tipo,
                            imoveis.id_cidade as id_cidade,
                            imoveis.bairro_combo as id_bairro,
                            imoveis.id_empresa as id_empresa,
                            imoveis.referencia as referencia, 
                            FROM_UNIXTIME(imoveis.data, "%Y-%m-%d %h:%i:%s") as data_cadastro, 
                            FROM_UNIXTIME(imoveis.data, "%Y-%m-%d %h:%i:%s") as data, 
                            NOW() as data_deleta,
                            imoveis_images.arquivo as image,
                            imoveis.views as views,
                            imoveis.clics as clicks
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'imoveis_images', 'where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'LEFT'),
        );

        $data['filtro'] = $filtro;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }

    public function get_id_chave_por_id_empresa($id_empresa)
    {
        $data['coluna'] = '
                            imoveis.id as id,
                            imoveis.nome as nome, 
                            IF (imoveis.venda = 1, "venda", "locacao" ) as negocio, 
                            imoveis.id_tipo as id_tipo,
                            imoveis.id_cidade as id_cidade,
                            imoveis.bairro_combo as id_bairro,
                            imoveis.id_empresa as id_empresa,
                            imoveis.referencia as referencia, 
                            FROM_UNIXTIME(imoveis.data, "%Y-%m-%d %h:%i:%s") as data_cadastro, 
                            NOW() as data_deleta,
                            ';
        /* 
        imoveis_images.arquivo as image,
        imoveis.views as views,
        imoveis.clics as clicks
        array('nome' => 'imoveis_images', 'where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'LEFT'),
         * 
         */
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
        );

        $data['filtro'] = 'imoveis.id_empresa = ' . $id_empresa;
        $valor = $this->get_itens_($data);
        if (isset($valor['itens'])) {
            foreach ($valor['itens'] as $item) {
                $retorno[$item->id] = $item;
            }
        } else {
            $retorno = NULL;
        }
        return isset($retorno) ? $retorno : NULL;
    }

    public function get_select($filtro = array(), $coluna = 'nome', $ordem = 'ASC')
    {
        $data['coluna'] = 'imoveis.id as id, imoveis.nome as descricao ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
        );

        $data['filtro'] = $filtro;
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $retorno = $this->get_itens_($data);
        return $retorno['itens'];
    }

    public function get_imoveis_has_empresas($ultimo = FALSE)
    {
        $data['coluna'] = 'imoveis.id as id_imovel, imoveis.id_empresa as id_empresa';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
        );

        $data['filtro'] = $ultimo ? 'id > ' . $ultimo : NULL;
        $data['col'] = 'id';
        $data['ordem'] = 'ASC';
        $retorno = $this->get_itens_($data);
        return $retorno['itens'];
    }

    public function get_total_itens($filtro = array())
    {
        $data['coluna'] = '	
                            imoveis.id as id,
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'id';
        $retorno = $this->get_itens_($data);

        return $retorno['qtde'];
    }

    public function get_itens($filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL, $qtde_itens = 8 )
    {
        $data['coluna'] = '	
                            imoveis.id as id,
                            imoveis.id_empresa as id_empresa,
                            imoveis.id_cidade as id_cidade,
                            imoveis.nome as nome, 
                            imoveis.referencia as referencia, 
                            imoveis.preco_venda as preco_venda, 
                            imoveis.venda as venda, 
                            imoveis.preco_locacao as preco_locacao, 
                            imoveis.locacao as locacao, 
                            imoveis.preco_locacao_dia as preco_locacao_dia, 
                            imoveis.descricao as descricao,
                            imoveis.bairro_combo as bairro_combo,
                            imoveis.id_tipo as id_tipo,
                            imoveis_images.arquivo as foto1,
                            imoveis_tipos.nome as tipo,
                            CONCAT(bairros.nome, \' / \', cidades.nome) as bairro_cidade,
                            from_unixtime(imoveis.data_atualizacao, \'%d/%m/%Y %h:%i:%s\') as data_atualizacao,
                            CONCAT(imoveis.preco_venda,\'-\',imoveis.preco_locacao,\'-\',imoveis.preco_locacao_dia) as valores
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
            array('nome' => 'imoveis_images', 'where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'LEFT'),
            array('nome' => 'imoveis_tipos', 'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'LEFT'),
            array('nome' => 'bairros', 'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
        );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = $qtde_itens;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $data['group'] = 'imoveis.id';
        $retorno = $this->get_itens_($data);

        return $retorno;
    }

    public function get_itens_email( $filtro = array(), $coluna = 'ordem_rad', $ordem = 'ASC', $off_set = 0, $quantidade = N_ITENS, $destaque = FALSE )
    {
        /**
         * 
         */
    	$data['coluna'] = '	
                            imoveis.id as id_imovel, 
                            imoveis.nome as nome,
                            IF ( imoveis.preco_venda > 0, imoveis.preco_venda , IF ( imoveis.preco_locacao > 0 , imoveis.preco_locacao, imoveis.preco_locacao_dia )   ) as preco,
                            imoveis.preco_venda as preco_venda,
                            imoveis.preco_locacao as preco_locacao,
                            imoveis.preco_locacao_dia as preco_locacao_dia,
                            imoveis.logradouro as logradouro,
                            IF( imoveis.video, 1, 0 ) as video,
                            imoveis.condominio as terreno,
                            imoveis.quartos as quartos,
                            imoveis.garagens as garagens,
                            imoveis.banheiros as banheiros,
                            imoveis.area as area, 
                            imoveis.area_terreno as area_terreno,
                            imoveis.area_util as area_util,
                            empresas.mudou as mudou,
                            IF ( imoveis.bairro <> bairros.nome, imoveis.bairro, "")  as vila,
                            bairros.nome as bairro,
                            bairros.link as bairros_link,
                            imoveis_tipos.nome as imoveis_tipos_titulo,
                            imoveis_tipos.english as imoveis_tipos_english,
                            imoveis_tipos.link as imoveis_tipos_link,
                            cidades.link as cidades_link,
                            IF ( imoveis.venda = 1, "venda" , IF ( imoveis.locacao = 1 , "locacao", "locacao_dia" )   ) as tipo,
                            imoveis.venda as tipo_venda,
                            imoveis.locacao as tipo_locacao,
                            imoveis.locacao_dia as tipo_locacao_dia,
                            empresas.id as id_empresa,
                            imoveis.views as views,
                            imoveis.id_cidade as imovel_id_cidade,
                            bairros.cidade as bairro_cidade,
                            cidades.id as cidades_id,
                            cidades.nome as cidade_nome,
                            cidades.uf as uf,
                            imoveis.bairro_combo as bairro_combo,
                            empresas.empresa_nome_fantasia as nome_empresa,
                            empresas.nome_seo as imobiliaria_nome_seo,
                            imoveis.longitude as longitude,
                            imoveis.latitude as latitude,
                            logradouros.logradouro as logradouro_,
                            imoveis.descricao as descricao,
                            imoveis.referencia as referencia, 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade'),
                                );
        $this->load->model('images_model');
    	$data['filtro'] = $filtro;
        if ( $coluna == 'preco_venda' )
        {
            $data['filtro'][] = '( imoveis.preco_venda > 0 )';
        }
        if ( $coluna == 'preco_locacao' )
        {
            $data['filtro'][] = '( imoveis.preco_locacao > 0 )';
        }
        if ( $coluna == 'area_util' )
        {
            $data['filtro'][] = '( imoveis.area_util > 1 )';
        }
        if ( $coluna == 'bairros_link' )
        {
            $data['filtro'][] = '( imoveis.bairro_combo > 0 )';
        }
        $data['off_set'] = isset($off_set) ? $off_set : 0;
        $data['qtde_itens'] = $quantidade;
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem; 
    	$data['group'] = 'imoveis.id'; 
    	$retorno_db = $this->get_itens_($data);
        $retorno = array('itens' => NULL, 'qtde' => 0);
        if ( isset($retorno_db['itens']) && $retorno_db['qtde'] > 0 )
        {
            foreach ( $retorno_db['itens'] as $chave => $item )
            {
                $retorno['itens'][$chave] = $item;
                $retorno['itens'][$chave]->images = $this->images_model->get_itens_imoveis_images('imoveis_images.id_imovel = '.$item->id_imovel, 'imoveis_images.ordem', 'ASC', 0, 1);
            }
        }
    	return $retorno;
    }
    
    public function get_itens_transfere($filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL)
    {
        $data['coluna'] = 'id_estilo,
                            id_tipo,
                            id_cidade,
                            id_logradouro,
                            esquina,
                            data,
                            data_atualizacao,
                            nome,
                            titulo_inicial,
                            descricao,
                            link,
                            venda,
                            locacao,
                            condominio_valor,
                            invisivel,
                            views,
                            clics,
                            referencia,
                            mapa_x,
                            mapa_y,
                            area,
                            quartos,
                            garagens,
                            mobiliado,
                            cobertura,
                            condominio,
                            area_terreno,
                            iptu,
                            numero,
                            complemento,
                            preco_venda,
                            preco_locacao,
                            logradouro,
                            bairro,
                            cep,
                            cidade,
                            lazer,
                            comercial,
                            residencial,
                            chave,
                            vencimento,
                            seguro,
                            anotacoes,
                            reservaimovel,
                            latitude,
                            longitude,
                            video,
                            mostramapa,
                            bairro_combo	bigint(30)
                            semimobiliado	char(1)
                            novo	char(1)
                            troca	char(1)
                            vendido	char(1)
                            proprietario	varchar(100)
                            id_corretor	bigint(30)
                            fone_proprietario	varchar(12)
                            preco_locacao_dia	decimal(20,2)
                            area_util	decimal(20,2)
                            suites	varchar(100)
                            fotos_externas	varchar(250)
                            troca_texto	longtext
                            id_linha	bigint(20)
                            locado	char(1)
                            superdestaque	tinyint(1)
                            locacao_dia	tinyint(1)
                            destaque	tinyint(1)
                            thumb1m	varchar(250)
                            sistema	int(11)
                            fs1	varchar(255)
                            fs2	varchar(255)
                            fs3	varchar(255)
                            fs4	varchar(255)
                            fs5	varchar(255)
                            fs6	varchar(255)
                            fs7	varchar(255)
                            fs8	varchar(255)
                            destaque_listagem	tinyint(1)
                            ordem_rad	bigint(20)
                            banheiros	varchar(100)
                            data_atualiza_foto	datetime
                            data_atualiza_fotos	datetime
                            foto9	varchar(250)
                            thumb9	varchar(250)
                            foto9_descricao	text
                            foto10	varchar(250)
                            thumb10	varchar(250)
                            foto10_descricao	text
                            foto11	varchar(250)
                            thumb11	varchar(250)
                            foto11_descricao	text
                            foto12	varchar(250)
                            thumb12	varchar(250)
                            foto12_descricao	text
                            integrarportais	tinyint(1)
                            destaque_full	int(1)
                            preco_titulo	varchar(100)
                            preco_complemento
                            imoveis.id_cidade as id_cidade,
                            imoveis.nome as nome, 
                            imoveis.referencia as referencia, 
                            imoveis.preco_venda as preco_venda, 
                            imoveis.venda as venda, 
                            imoveis.preco_locacao as preco_locacao, 
                            imoveis.locacao as locacao, 
                            imoveis.preco_locacao_dia as preco_locacao_dia, 
                            imoveis.descricao as descricao,
                            imoveis.bairro_combo as bairro_combo,
                            imoveis.id_tipo as id_tipo,
                            imoveis_images.arquivo as foto1,
                            imoveis.views as views,
                            imoveis.clics as clics,
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
            array('nome' => 'imoveis_images', 'where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'LEFT'),
            array('nome' => 'imoveis_tipos', 'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'LEFT'),
        );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $retorno = $this->get_itens_($data);

        return $retorno;
    }

    public function get_itens_fotos($off_set = NULL, $filtro = array(), $coluna = 'id', $ordem = 'ASC')
    {
        $data['coluna'] = '	
                            imoveis.id as id,
                            imoveis.id_empresa as id_empresa,
                            imoveis.data as time,
                            imoveis.foto1 as foto1,
                            imoveis.foto1_descricao as descricao1,
                            imoveis.foto2 as foto2,
                            imoveis.foto2_descricao as descricao2,
                            imoveis.foto3 as foto3,
                            imoveis.foto3_descricao as descricao3,
                            imoveis.foto4 as foto4,
                            imoveis.foto4_descricao as descricao4,
                            imoveis.foto5 as foto5,
                            imoveis.foto5_descricao as descricao5,
                            imoveis.foto6 as foto6,
                            imoveis.foto6_descricao as descricao6,
                            imoveis.foto7 as foto7,
                            imoveis.foto7_descricao as descricao7,
                            imoveis.foto8 as foto8,
                            imoveis.foto8_descricao as descricao8,
                            imoveis.foto9 as foto9,
                            imoveis.foto9_descricao as descricao9,
                            imoveis.foto10 as foto10,
                            imoveis.foto10_descricao as descricao10,
                            imoveis.foto11 as foto11,
                            imoveis.foto11_descricao as descricao11,
                            imoveis.foto12 as foto12,
                            imoveis.foto12_descricao as descricao12,
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
        );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
            $data['qtde_itens'] = 10000000;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $retorno = $this->get_itens_($data);

        return $retorno;
    }

    public function get_itens_email_automatico($filtro = array(), $coluna = 'imoveis.id', $ordem = 'DESC', $off_set = NULL)
    {
        $data['coluna'] = '	
                            imoveis.id_empresa as id_empresa,
                            empresas.empresa_razao_social as empresa,
                            autorizadores.nome as autorizador_nome,
                            empresas.autorizador_email as autorizador_email,
                            empresas.inscricao as login,
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
            array('nome' => 'autorizadores', 'where' => 'autorizadores.id = empresas.id_autorizador', 'tipo' => 'LEFT'),
            array('nome' => 'hotsite_parametros', 'where' => 'hotsite_parametros.id_empresa = empresas.id', 'tipo' => 'LEFT'),
        );
        $data['filtro'] = $filtro;
        if (isset($off_set)) {
            $data['off_set'] = $off_set;
        }
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $data['group'] = 'imoveis.id_empresa';
        $retorno = $this->get_itens_($data);

        return $retorno;
    }

    public function get_item_naoencontrei($filtro = array())
    {

        $data['coluna'] = '	
                            imoveis_naoencontrei_respostas.id as id,
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis_naoencontrei_respostas'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'id';
        $retorno = $this->get_itens_($data);

        return $retorno['qtde'];
    }

    public function get_cidades_com_imoveis($filtro)
    {
        $data['coluna'] = '	
                            imoveis.id_cidade as id_cidade,
                            count(imoveis.id) as qtde,
                            cidades.nome as cidade
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'imoveis.id_cidade';
        $data['col'] = 'qtde';
        $data['ordem'] = 'DESC';
        $retorno = $this->get_itens_($data);

        return $retorno;

    }

    public function get_tipos_cidades_com_imoveis($filtro)
    {
        $data['coluna'] = '	
                            imoveis.id_tipo as id_tipo,
                            count(imoveis.id) as qtde
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
            array('nome' => 'imoveis_tipos', 'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'imoveis.id_tipo';
        $retorno = $this->get_itens_($data);

        return $retorno;

    }

    public function get_tipos_empresas_cidades_com_imoveis($filtro)
    {
        $data['coluna'] = '	
                            imoveis.id_empresa as id_empresa,
                            count(imoveis.id) as qtde
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
            array('nome' => 'imoveis_tipos', 'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'imoveis.id_empresa';
        $retorno = $this->get_itens_($data);

        return $retorno;

    }

    public function get_id_imoveis($filtro)
    {
        $data['coluna'] = '	
                            imoveis.id as id
                            ';
        $data['tabela'] = array(
            array('nome' => 'imoveis'),
            array('nome' => 'empresas', 'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
            array('nome' => 'imoveis_tipos', 'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
            array('nome' => 'cidades', 'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
        );
        $data['filtro'] = $filtro;
        $data['group'] = 'imoveis.id';
        $retorno = $this->get_itens_($data);

        return $retorno['itens'];

    }

}