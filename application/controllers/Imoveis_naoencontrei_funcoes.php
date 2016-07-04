<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imoveis_Naoencontrei_funcoes extends MY_Controller 
{
	public function __construct()
	{
            parent::__construct(FALSE);
            $this->load->model(array('contatos_site_model','imoveis_naoencontrei_model', 'imoveis_model', 'cidades_model'));
	}
	
	public function index()
	{
            //$this->listar();
	}
        
        public function tester($id_empresa = NULL)
        {
            die();
            if(isset($id_empresa) && $id_empresa)
            {
                $imoveis = $this->imoveis_model->get_itens_fotos('imoveis.id_empresa = '.$id_empresa);
                $retorno = array();
                
                foreach($imoveis['itens'] as $valor)
                {
                    for($i = 1; $i < 9; $i++)
                    {
                        $foto = 'foto'.$i;
                        if(isset($valor->$foto) && !empty($valor->$foto))
                        {
                            $valor->$foto = str_replace('http://www.marcelimachadoimoveis.com.br/powsites/81750/imo/', '', $valor->$foto);
                            $retorno[$valor->id][$i] = $valor->$foto;
                        }
                    }
                }
                
                if(isset($retorno) && !empty($retorno))
                {
                    foreach($retorno as $key => $value)
                    {
                        foreach($value as $k => $v)
                        {
                            $campo_foto = 'foto'.$k;
                            $dados[$key][$campo_foto] = $v;
                        }
                    }
                    
                    $caminho = getcwd().'/';
                    $caminho = str_replace('admin2_0/', 'powsites/'.$id_empresa.'/imo/', $caminho);
                    
                    if(isset($dados) && !empty($dados))
                    {
                        foreach($dados as $id_imovel => $fotos)
                        {
                            $thumb = '';
                            
                            foreach($fotos as $f_k => $f_v)
                            {
                                $exp_a = explode('_', $f_v);
                                $count = count($exp_a);
                                $ultimo = $exp_a[$count-1];
                                $thumb = $exp_a[$count-1];

                                $F_old_name = $caminho.$f_v;
                                $TM_F_old_name = $caminho.str_replace('F', 'TM_F', $f_v);
                                $T_F_old_name = $caminho.str_replace('F', 'T_F', $f_v);
                                $F650_F_old_name = $caminho.str_replace('F', '650F_F', $f_v);
                                
                                $F_new_name = $caminho.'F_'.$id_imovel.'_'.$ultimo;
                                $TM_F_new_name = $caminho.'TM_F_'.$id_imovel.'_'.$ultimo;
                                $T_F_new_name = $caminho.'T_F_'.$id_imovel.'_'.$ultimo;
                                $F650_new_name = $caminho.'650F_F_'.$id_imovel.'_'.$ultimo;
                                
                                $data_img[$id_imovel][$f_k] = 'F_'.$id_imovel.'_'.$ultimo;
                                
                                //rename($F_old_name, $F_new_name);
                                //rename($TM_F_old_name, $TM_F_new_name);
                                //rename($T_F_old_name, $T_F_new_name);
                                //rename($F650_F_old_name, $F650_new_name);
                            }
                            $thumb = explode('.',$thumb);
                            $data_img[$id_imovel]['thumb1'] = 'T_F_'.$id_imovel.'_1.'.$thumb[1];
                        }
                        
                        if(isset($data_img) && $data_img)
                        {
                            foreach($data_img as $imovel => $dados_edt)
                            {
                                //$this->imoveis_model->editar($dados_edt,'imoveis.id = '.$imovel.' AND imoveis.id_empresa = '.$id_empresa,1);
                            }
                        }
                    }
                }
            }
        }
        
        
        public function analisar_cidades()
        {
            $nao_encontrei = $this->imoveis_naoencontrei_model->get_itens('imoveis_naoencontrei.enviado = 0 AND imoveis_naoencontrei.id_cidade = 0');
            if($nao_encontrei['qtde'] > 0 )
            {
                foreach($nao_encontrei['itens'] as $chave => $valor)
                {
                    $cidade_interesse = trim($valor->cidade_interesse);
                    $cidade_interesse = str_replace(' ', '%', $cidade_interesse);
                    
                    $cidade = $this->cidades_model->get_itens('cidades.nome like "'.$valor->cidade_interesse.'" ', 'id', 'DESC', 'cidades.id');
                    if(isset($cidade) && $cidade['qtde'] > 0)
                    {
                        foreach($cidade['itens'] as $key => $value)
                        {
                            $retorno[$valor->id] = $value->id;
                        }
                    }
                    else
                    {
                        $edt['enviado'] = 2;
                        $this->imoveis_naoencontrei_model->editar($edt, 'imoveis_naoencontrei.id = '.$valor->id);
                    }
                }
                
                if(isset($retorno) && !empty($retorno))
                {
                    foreach($retorno as $k => $v)
                    {
                        $edt_naoencontrei['id_cidade'] = $v;
                        $this->imoveis_naoencontrei_model->editar($edt_naoencontrei, 'imoveis_naoencontrei.id = '.$k);
                    }
                }
            }
        }
       
        public function retorna_time( $tempo )
        {
            echo date('Y-m-d H:i', $tempo);
        }
        
        public function sincronizar()
        {
            
            //$nao_encontrei = $this->imoveis_naoencontrei_model->get_itens_email_automatico('imoveis_naoencontrei.enviado = 0');
            $nao_encontrei = $this->imoveis_naoencontrei_model->get_itens_email_automatico('imoveis_naoencontrei.enviado = 0 AND imoveis_naoencontrei.data < '.strtotime( '-2 day'.date('d M Y') ).' AND imoveis_naoencontrei.data > '.strtotime( '-5 day'.date('d M Y') ) );
            $cidades = $this->_pesquisar_cidades_nao_encontrei($nao_encontrei);
            $empresas = $this->_pesquisar_empresas_nao_encontrei($cidades);
            $emails = $this->_enviar_email($empresas);
            //print_r($emails);
            //$this->email->print_debugger();
        }
        
        /*
         * Pesquisa cidades de acordo com o que foi preenchido no campo de cidade de interesse 
         * do formulário do Não encontrei. Se Ocorrer tudo certo retorna um array no formato de
         * id da cidade => objeto dados_nao_encontrei => dados da pessoas que não encontraram o imovel[],
         * id da cidade => objeto cidade => dados da cidade  que deseja o imovel[], se não retorna NULL.
         * 
         * @author Breno Henrique Moreno Nunes
         * @params array $data
         * @return array $retorno
         */
        private function _pesquisar_cidades_nao_encontrei($data =  array())
        {
             
            $retorno = NULL;
            if(isset($data) && $data['qtde'] > 0)
            {
                foreach($data['itens'] as $chave => $valor)
                {
                    //$cidades = $this->cidades_model->get_itens('cidades.nome like "'.$valor->cidade_interesse.'" ', 'id', 'DESC', 'cidades.id');
                    $cidades = $this->cidades_model->get_itens('cidades.id = '.$valor->id_cidade, 'id', 'DESC', 'cidades.id');
                    if(isset($cidades) && $cidades['qtde'] > 0)
                    {
                        foreach($cidades['itens'] as $key => $value)
                        {
                            //$retorno[$value->id]->id_nao_encontrei[] = $valor->id;
                            $retorno[$value->id]->dados_nao_encontrei[] = $valor;
                            $retorno[$value->id]->cidade = $value;
                        }
                    }
                    else
                    {
                        $edt['enviado'] = 2;
                        $this->imoveis_naoencontrei_model->editar($edt, 'imoveis_naoencontrei.id = '.$valor->id);
                    }
                }
            }
            return $retorno;
        }
        
        /*
         * Pesquisa empresa e os imoveis desta empresa que de acordo com o id da cidade passado no filtro.
         * Se Ocorrer tudo certo retorna um array no formato de
         * id da empresa => objeto cidades => array com a chave sendo o id da cidade => dados das pessoas que não encontraram o imovel[],
         * id da empresa => objeto logo => logo do portal,
         * id da empresa => objeto dados_empresa => array com dados da empresa[], se não retorna NULL.
         * 
         * @author Breno Henrique Moreno Nunes
         * @params array $data
         * @return array $retorno
         */
        private function _pesquisar_empresas_nao_encontrei($data =  array())
        {
            $retorno = NULL;
            
            $filtro  = '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") ) ';
            $filtro .= ' AND empresas.servicos_pagina_inicio < '.time();
            $filtro .= ' AND empresas.servicos_pagina_termino > '.time();
            $filtro .= ' AND imoveis.reservaimovel = 0 ';
            $filtro .= ' AND imoveis.vendido = 0 ';
            $filtro .= ' AND imoveis.locado = 0 ';
            $filtro .= ' AND empresas.bloqueado = 0 ';
            $filtro .= ' AND empresas.pagina_visivel = 1 ';
            $filtro .= ' AND imoveis.invisivel = 0 ';
            $filtro .= ' AND hotsite_parametros.receber_nao_encontrei = 1 ';
            
            if(isset($data) && !empty($data))
            {
                foreach($data as $chave => $valor)
                {
                    $imoveis = $this->imoveis_model->get_itens_email_automatico($filtro.' AND imoveis.id_cidade = '.$chave);
                    
                    if(isset($imoveis) && $imoveis['qtde'] > 0)
                    {
                        foreach($imoveis['itens'] as $imovel)
                        {
                            foreach($valor->dados_nao_encontrei as $nao_encontrei)
                            {
                                $total = $this->imoveis_naoencontrei_model->get_empresas_sem_resposta('imoveis_naoencontrei_respostas.id_naoencontrei = '.$nao_encontrei->id.' AND imoveis_naoencontrei_respostas.id_empresa = '.$imovel->id_empresa);
                                if($total == 0)
                                {
                                    $retorno[$imovel->id_empresa]->cidades[$chave]->nao_encontrei['itens'] = $valor->dados_nao_encontrei;
                                    $retorno[$imovel->id_empresa]->dados_cidade = $valor->cidade;
                                    $retorno[$imovel->id_empresa]->dados_empresa = $imovel;
                                }
                            }
                        }
                    }
                }
            }
            return $retorno;
        }
        
         /*
         * Encaminha emails as imobiliarias contendo os dados das pessoas que não encontraram
         * o imóvel que procuravam desde que as imobiliarias tenham imóveis na cidade de interesse.
         * Encaminha email de LOG.
         * 
         * @author Breno Henrique Moreno Nunes
         * @params array $data
         */
        private function _enviar_email($data = array())
        {
            if(isset($data) && $data)
            {
                /*
                $contatos_site['data'] = strtotime(date('Y-m-d H:i:s'));
                $contatos_site['assunto'] = 'Não Encontrei';
                $contatos_site['origem'] = 'n1';
                $contatos_site['sms_enviado'] = 0;
                */
                
                $email['email'] = 'siteenvia@pow.com.br';
                
                $env = 0;
                //$a = 0;
                
                foreach($data as $item)
                {
                    /*
                    if($a > 1)
                    {
                        die();
                    }
                    */
                    
                    $logo = strtolower($item->dados_cidade->portal.'/imagens/'.$item->dados_cidade->topo);
                    $portal = str_replace('http://', '', $item->dados_cidade->portal);
                    
                    //$contatos_site['id_empresa'] = $item->dados_empresa->id_empresa;
                    
                    $email['assunto'] = 'Contato de '.$portal;
                    $email['mensagem']  = '<img src="'.$logo.'" alt="'.$logo.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png" alt="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
                    $email['mensagem'] .= '<hr>Contato enviado pelo Portal '.$portal.PHP_EOL;
                    $email['mensagem'] .= '<br><br>';
                    $email['mensagem'] .= '<h3>Olá '.$item->dados_empresa->autorizador_nome.', queremos lembrar que os contatos abaixo estão pendentes e podem significar uma importante oportunidade de negócio para você.</h3><br>';
                    //$email['mensagem'] .= 'Assunto: Não encontrei'.PHP_EOL;
                    
                    foreach($item->cidades as $chave => $empresa)
                    {
                        
                        foreach($empresa->nao_encontrei['itens'] as $nao_encontrei)
                        {
                            $array_ids[] = $nao_encontrei->id;
                            $email['mensagem'] .= '<hr><br>';
                            $email['mensagem'] .= '<br>Contato feito por '.$nao_encontrei->nome.PHP_EOL;
                            //$email['mensagem'] .= '<br>E-mail: '.$nao_encontrei->email.PHP_EOL;
                            //$email['mensagem'] .= '<br>Telefone: '.$nao_encontrei->telefone.PHP_EOL;
                            //$email['mensagem'] .= '<br>Cidade: '.$nao_encontrei->cidade_interesse.PHP_EOL;
                            $email['mensagem'] .= '<br>Cidade: '.$item->dados_cidade->nome.PHP_EOL;
                            $email['mensagem'] .= '<br>Data: '.date('d/m/Y H:i',$nao_encontrei->data).PHP_EOL;	
                            $email['mensagem'] .= '<br><br>Mensagem:'.PHP_EOL;
                            $email['mensagem'] .= $nao_encontrei->pedido.'<br>'.PHP_EOL;
                            
                        }
                    }
                    
                    $email['mensagem'] .= '<hr>'.PHP_EOL;
                    $email['mensagem'] .= '<br><br><h3>Para visualizar estes e outros contatos recebidos do portal '.$portal.' acesse o seu painel de controle de seu anúncio utilizando seu login '.$item->dados_empresa->login.' e a sua senha secreta atráves do link http://www.pow.com.br/paineldecontrole </h3>'.PHP_EOL;
                    $email['mensagem'] .= '<br>Se não deseja receber mais este tipo de email acesse o seu Painel de Controle atráves do link http://www.pow.com.br/paineldecontrole e desmarque esta opção.<br>'.PHP_EOL;
                    $email['mensagem'] .= '<br>Em caso de dúvidas entre em contato com o POW Internet através'.PHP_EOL;
                    $email['mensagem'] .= '<br>do fone (41) 3382-1581 ou pelo email vendas01@pow.com.br'.PHP_EOL;
                    $email['mensagem'] .= '<br><br>Obrigado por fazer parte da rede www.PortaisImobiliarios.com'.PHP_EOL;
                    $email['mensagem'] .= '<br><br>O Portal '.$portal.' é integrante da rede www.PortaisImobiliarios.com, um serviço POW Internet.'.PHP_EOL;
                    $email['mensagem'] .= '<br><br><i>"Graça seja convosco, e paz, da parte de Deus nosso Pai, e do Senhor Jesus Cristo."<br> 1CO 1:3O sangue de Jesus Cristo, filho do Deus vivo, te purifica de todos os pecados.</i>'.PHP_EOL;
                    
                    /*
                    $contatos_site['nome'] = $item->dados_empresa->autorizador_nome;
                    $contatos_site['email'] = $item->dados_empresa->autorizador_email;
                    $contatos_site['mensagem'] = 'Lembrete encaminhado ao';
                    */
                    
                    //$email['to'] é o email da imobiliaria
                    $email['to'] = $item->dados_empresa->autorizador_email;
                    //$email['to'] = 'programacao01@pow.com.br';
                    $envio = $this->envio($email);
                    if($envio)
                    {
                        //$this->contatos_site_model->adicionar($contatos_site);
                        $env++;
                    }
                    //$a++;
                }
                
                if($env > 0 )
                {
                    $edt['enviado'] = 1;
                    $this->imoveis_naoencontrei_model->editar($edt, 'imoveis_naoencontrei.id IN ('.  implode(',', $array_ids).') ');
                }
                
                $log_mail['assunto']  = 'Log de E-mails automáticos';
                $log_mail['mensagem'] = 'E-mails automaticos encaminhados para '.$env.' empresas na data de '.date('d/m/Y');
                $log_mail['email'] = 'siteenvia@pow.com.br';
                $log_mail['to'] = array('comercial@pow.com.br', 'vendas01@pow.com.br', 'programacao@pow.com.br', 'programacao01@pow.com.br');
                $this->envio($log_mail);
            }
        }
        
}


