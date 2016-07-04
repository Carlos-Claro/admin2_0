<?php

class Previsao_XML {

    /**
     * Array com as respostas processadas do sistema.
     * @var array vai conter duas variaveis distintas [erro] = textos com os erros de arquivos E [conteudo] com os valores do xml
     */
    public $resposta = array();

    /**
     *
     * @var array object valores referentes a cidade
     */
    public $endereco = array();

    
    public function _get_endereco($endereco) 
    {
        return $this->endereco;
    }

    public function set_endereco($endereco = NULL) 
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function get_xml() 
    {
        if (isset($this->endereco) && $this->endereco) 
        {
            if ($this->curl_executavel($this->endereco))
            {
                $this->resposta['conteudo'] = simplexml_load_file($this->endereco);
            }
            else
            {
                $this->resposta['erro'][] = __CLASS__ . ' - ' . __FUNCTION__ . 'Cidade: ' . $valor->titulo . ', chave: ' . $valor->chave . ' , id: ' . $valor->id . ', hora: ' . date('d/m/Y H:i') . ' Teve problemas de comunica&ccedil;&atilde;o com o servidor e n&atilde;o pode ser sincronizado. pelo arquivo n&atilde;o existir, estar indisponivel no momento ou n&atilde;o estar com as permiss&otilde;es corretas de leitura, o arquivo montado ficou em branco ';
            }
        }
        return $this->resposta;
    }

    public function curl_executavel($endereco) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endereco);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $retorno = curl_exec($ch);
        curl_close($ch);
        return $retorno;
    }

}
