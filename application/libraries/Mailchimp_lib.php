<?php

class Mailchimp_lib
{
    
    /*
     * chave da API da conta de login do Mailchimp
     */
    private $apikey   = 'fbe08e2a6f9b50208a3e34fced7e8135-us5';
    
    /*
     * parte da url da api, onde consta a versão, sem o protocolo, classe, metodos e parametros.
     * Ex.: (https:// == protocolo)  api.mailchimp.com/2.0// (list/list == Classe e Método) Não vai funcionar
     * Ex.: api.mailchimp.com/1.3//  Vai funcionar
     */
    private $versao = 'api.mailchimp.com/2.0//';
    
    /*
     * url para conexão com API, é montada no momento da instancia da classe, 
     * e completada quando chamada em função.
     */
    private $url;
    
    /*
     * Construtor da Classe que trata a chave da API para montar a URL de requisição. 
     */
    public function __construct() 
    {
        $code = explode('-',  $this->apikey);
        $this->url = 'https://'.$code[1].'.'.$this->versao;
    }
    
    /*
     * Retorna as listas disponíveis para incluir dentro da campanha.
     * @return array $retorno array associativo com descricao e id das listas.
     */
    public function get_lists()
    {
        $params['apikey'] = $this->apikey;
        $lists = $this->get_results('lists/list', $params);
        foreach($lists->data as $item)
        {
            $retorno[] = (object) array( 'descricao' =>  $item->name, 'id' =>  $item->id ); 
        }
        return $retorno;
    }
    
    /*
     * Recebe o método e os parametros para fazer a requisição a API. 
     * @param  string $metodo  método da API Ex.: lists/list
     * @param  array  $options array associativo de acordo com o metodo passado por parâmetro
     * @return array  $retorno array associativo com a resposta da requisição da API.
     */
    public function get_results($metodo = '', $params = array())
    {
        $retorno = $this->_curl($this->url.$metodo, $params);
        return $retorno;
    }
    
    /*
     * Responsável por utilizar o método de curl nativo do php, para fazer a requisição da API.
     * @param string $url url para conexão do curl
     * @param array $options array associativo com opções da API
     * @return array $retorno array associativo com a resposta da requisição da API.
     */
    private function _curl($url = NULL, $params = array())
    {
        $retorno = NULL;
        if(isset($url) && $url)
        {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            
            $retorno = curl_exec($ch);
            
            curl_close($ch); 
        }
        return json_decode($retorno);
    }
    
}
