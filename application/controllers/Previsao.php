<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Previsao extends MY_Controller 
{

    public function __construct() 
    {
        $valida = ( isset($_GET['usuario'] ) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86' ) ? FALSE : TRUE;
        parent::__construct($valida);
        $this->load->model('previsao_model');
        $this->load->library(array('previsao_xml','previsao_tempo'));
    }
    
    /**
     * Função que pega a previsão do tempo da url informada na função set_endereço e trata o xml retornado
     * para montar em html a previsão.Retorna string com html formado ou mensagem de erro.
     * 
     * @author Breno Henrique Moreno Nunes
     * @return string $retorno
     */
    public function get_datas()
    {
        $this->previsao_xml->set_endereco('http://servicos.cptec.inpe.br/XML/cidade/7dias/4965/previsao.xml');
        $this->previsao_xml->get_xml();
        $objeto_xml = $this->previsao_xml->resposta;
        if(!isset($objeto_xml['erro']))
        {
            foreach ($objeto_xml['conteudo']->previsao as $data) 
            {
                $consulta = $this->previsao_model->get_item_por_data('tempo_previsao.dia = '.$data->dia);
                if (isset($consulta) && $consulta) 
                {
                    $dados['itens'] = $consulta;
                    $this->previsao_tempo->inicia($dados);
                    $retorno[] = $this->previsao_tempo->get_html();
                }
                else
                {
                    $retorno = 'erro no html';
                }
            }
        }
        return $retorno;
    }

    
     /**
     * Função para pegar a previsão do tempo e salvar ou atualizar no banco.
     * Encaminha email com mensagem de sucesso ou erro.
     * 
     * @author Breno Henrique Moreno Nunes
     * @return string $retorno
     */
    public function sincronizar() 
    {
        $this->previsao_xml->set_endereco('http://servicos.cptec.inpe.br/XML/cidade/7dias/4965/previsao.xml');
        $objeto_xml = $this->previsao_xml->get_xml();
        $mensagens = '';
        $email = array(
                'email' => 'envio@powimoveis.com.br',
                'nome' => 'Log',
                'to' => 'programacao@pow.com.br',
                'assunto' => 'Log de Previsão do Tempo',
                'mensagem ' => '',
        );
        if(!isset($objeto_xml['erro']))
        {
            foreach ($objeto_xml['conteudo']->previsao as $data) 
            {
                $consulta = $this->previsao_model->get_item('tempo_previsao.dia = "'.$data->dia.'"');
                if(isset($consulta)) 
                {
                    //$filtro = array( 'id' => $consulta->id );
                    $retorno = $this->previsao_model->editar($data, 'tempo_previsao.id = '.$consulta->id);
                } 
                else 
                {
                    $retorno = $this->previsao_model->adicionar($data);
                }
                $dados = $this->previsao_model->get_item_por_data('tempo_previsao.dia = "'.$data->dia.'"');
                $mensagens .= 'Sucesso na previsão do dia '.$data->dia.' Previsão de '.$dados->descricao.'<br>';
            }
        }
        $email['mensagem'] = $mensagens;
        $resposta = $this->log_previsao($email);
    }
    
    /**
     * Função que recebe como parâmetro o email ao qual será encaminhado o Log das informações.
     * Retorna verdadeiro caso $email exista ou falso caso contrario.
     * 
     * @author Breno Henrique Moreno Nunes
     * @param string $email
     * @return boolean $retorno 
     */
    public function log_previsao($email)
    {
        if (isset($email))
        {
            $this->envio($email);
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
        return $retorno;
    }
}
