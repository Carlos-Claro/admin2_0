<?php
class Tarefas_projetos_iteracao_has_usuarios_Model extends MY_Model {
    private $coluna = '';
    private $tabela = NULL;
    private $database = NULL;
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
        $this->database = array('db' => 'guiasjp', 'table' => 'tarefas_projetos_iteracao_has_usuarios');
    }
    
    private function _set_colunas( $completo = FALSE )
    {
        $coluna = 
                  'tarefas_projetos_iteracao_has_usuarios.id as id'
                . ', tarefas_projetos_iteracao_has_usuarios.id_tarefas_projetos_iteracao as id_tarefas_projetos_iteracao'
                . ', tarefas_projetos_iteracao_has_usuarios.id_usuario as id_usuario'
                . ', tarefas_projetos_iteracao_has_usuarios.lido as lido'
                ;
        
        return $coluna;
    }
    private function _set_tabelas( $completo = FALSE, $inner = NULL )
    {
        $tabela = array();
        $tabela[] = array('nome' => 'tarefas_projetos_iteracao_has_usuarios');
        $tabela[] = array('nome' => 'tarefas_projetos_iteracao','where' => 'tarefas_projetos_iteracao_has_usuarios.id_tarefas_projetos_iteracao = tarefas_projetos_iteracao.id', 'tipo' => 'INNER');
        $tabela[] = array('nome' => 'usuarios', 'where' => 'tarefas_projetos_iteracao_has_usuarios.id_usuario = usuarios.id', 'tipo' => 'INNER');
        return $tabela;
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
        $data['coluna'] = $this->_set_colunas();
        $data['tabela'] = $this->_set_tabelas();
        $data['filtro'] = 'tarefas_projetos_iteracao_has_usuarios.id_usuario = '.$id;
        $usuario = $this->get_itens_($data);
        $retorno = isset($usuario['itens'][0]) ? $usuario['itens'][0] : NULL;
        return $retorno;
    }

    public function get_select( $filtro = array(), $coluna = 'id_tarefas_projetos_iteracao', $ordem = 'ASC' )
    {
        // TODO: necessário?
    }

    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = 'count(tarefas_projetos_iteracao_has_usuarios.id_usuario) as qtde, ';
        $data['tabela'] = $this->_set_tabelas();
        $data['filtro'] = $filtro;
        $data['group'] = 'tarefas_projetos_iteracao_has_usuaios.id_usuario';
        $retorno = $this->get_itens_($data);

        return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    public function get_itens( $filtro = array(), $coluna = 'id_usuario', $ordem = 'DESC', $off_set = NULL )
    {
        $data['coluna'] = $this->_set_colunas();
        $data['tabela'] = $this->_set_tabelas();
        $data['filtro'] = $filtro;
        if ( isset($off_set) )
        {
            $data['off_set'] = $off_set;
        }
        $data['group'] = 'tarefas_projetos_iteracao_has_usuarios.id_usuario';
        $data['col'] = $coluna;
        $data['ordem'] = $ordem;
        $retorno = $this->get_itens_($data);
        return $retorno;
    }

    /**
     * Retorna o total de iterações com o usuário que não foram verificadas (lidas)
     * @param int|$id_usuario ID do usuário
     * @return int Quantidade total de iterações não lidas
     */
    public function get_iteracoes_abertas($id_usuario)
    {
        // SELECT
        $data['coluna'] = 'COUNT(*) as qtde_iteracoes';

        // FROM
        $data['tabela'] = array(
            array('nome' => 'tarefas_projetos_iteracao_has_usuarios')
        );

        // WHERE
        $data['filtro'][] = 'tarefas_projetos_iteracao_has_usuarios.id_usuario = ' . $id_usuario;
        $data['filtro'][] = 'tarefas_projetos_iteracao_has_usuarios.lido = 0';

        $retorno = $this->get_itens_($data);

        // TODO: Verificar qual a maneira correta de pegar os dados do retorno?
        if (isset($retorno) && $retorno['qtde'] >= 1)
            return $retorno['itens'][0]->qtde_iteracoes;

        return null;
    }

    /**
     * Retorna todos os ids de portfolios que possuem interação aberta para o usuário
     * @param int $id_usuario ID do usuário
     * @return array ID dos projetos
     */
    public function get_portfolios($id_usuario)
    {
        // consulta a ser feita para retornar o id portfolio
        /*
         * SELECT
         *   tarefas_portfolio.id as id_portfolio
         *
         * FROM
         *   tarefas_projetos_iteracao_has_usuarios
         *
         *   INNER JOIN
         *   tarefas_projetos_iteracao
         *   ON
         *   tarefas_projetos_iteracao_has_usuarios.id_tarefas_projetos_iteracao = tarefas_projetos_iteracao.id
         *
         *   INNER JOIN
         *   tarefas_projetos
         *   ON
         *   tarefas_projetos_iteracao.id_tarefas_projetos = tarefas_projetos.id
         *
         *   INNER JOIN
         *   tarefas_portfolio
         *   ON
         *   tarefas_projetos.id_tarefas_portfolio = tarefas_portfolio.id
         *
         * WHERE
         *   tarefas_projetos_iteracao_has_usuarios.lido = 0]
         *
         */

        // SELECT
        $data['coluna'] =
            'tarefas_portfolio.id as id_portfolio';


        // FROM
        $data['tabela'] = array(
            array('nome' => 'tarefas_projetos_iteracao_has_usuarios'),
            array('nome' => 'tarefas_projetos_iteracao', 'where' => 'tarefas_projetos_iteracao_has_usuarios.id_tarefas_projetos_iteracao = tarefas_projetos_iteracao.id', 'tipo' => 'INNER'),
            array('nome' => 'tarefas_projetos', 'where' => 'tarefas_projetos_iteracao.id_tarefas_projetos = tarefas_projetos.id', 'tipo' => 'INNER'),
            array('nome' => 'tarefas_portfolio', 'where' => 'tarefas_projetos.id_tarefas_portfolio = tarefas_portfolio.id', 'tipo' => 'INNER')
        );

        // WHERE
        $data['filtro'][] = 'tarefas_projetos_iteracao_has_usuarios.id_usuario = ' . $id_usuario;
        $data['filtro'][] = 'tarefas_projetos_iteracao_has_usuarios.lido = 0';

        $data['group'] = 'tarefas_portfolio.id';

        $retorno = $this->get_itens_($data);
        return $retorno;
    }

    /**
     * Marca todas as interações como lidas
     * @param int|$id_usuario ID do usuário
     */
    public function zera_contador($id_usuario)
    {
        $data = array(
            'lido' => 1
        );

        $filtro = array(
            'id_usuario' => $id_usuario,
            'lido' => 0
        );

        $this->editar($data, $filtro);
    }
}