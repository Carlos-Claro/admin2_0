<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imoveis_Naoencontrei extends MY_Controller 
{
	private $valida = array(
                                array( 'field'   => 'cidade_interesse',           'label'   => 'Cidade de Interesse', 		'rules'   => 'trim'),
                                );

	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('contatos_site_model','imoveis_naoencontrei_model', 'imoveis_model', 'cidades_model', 'estados_model'));
	}
	
	public function index()
	{
            $this->listar();
	}
       
        public function sincronizar()
        {
            
            $nao_encontrei = $this->imoveis_naoencontrei_model->get_itens_email_automatico('imoveis_naoencontrei.enviado = 0');
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
                    $cidades = $this->cidades_model->get_itens('cidades.nome like "'.$valor->cidade_interesse.'" ', 'id', 'DESC', 'cidades.id');
                    
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
                                $total = $this->imoveis_naoencontrei_model->get_empresas_sem_resposta('imoveis_naoencontrei_respostas.id_naoencontrei = '.$nao_encontrei->id.' AND imoveis_naoencontrei_respostas.id_empresa = '.$imovel->id_empresa.' AND imoveis_naoencontrei.data < '.strtotime( '-2 day'.date('d M Y') ).' AND imoveis_naoencontrei.data > '.strtotime( '-4 day'.date('d M Y') ) );
                                if($total == 0)
                                {
                                    $retorno[$imovel->id_empresa]->cidades[$chave]->nao_encontrei['itens'] = $valor->dados_nao_encontrei;
                                    $retorno[$imovel->id_empresa]->dados_cidade = $valor->cidade;
                                    $retorno[$imovel->id_empresa]->dados_empresa = $imovel;
                                }
                            }
                            //$retorno[$imovel->id_empresa]->cidades[$chave]->nao_encontrei['itens'] = $valor->dados_nao_encontrei;
                            //$retorno[$imovel->id_empresa]->dados_cidade = $valor->cidade;
                            //$retorno[$imovel->id_empresa]->dados_empresa = $imovel;
                            //$retorno[$imovel->id_empresa]->logo = $valor->cidade;
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
                            $email['mensagem'] .= '<br>Contato feito por:'.$nao_encontrei->nome.PHP_EOL;
                            //$email['mensagem'] .= '<br>E-mail: '.$nao_encontrei->email.PHP_EOL;
                            //$email['mensagem'] .= '<br>Telefone: '.$nao_encontrei->telefone.PHP_EOL;
                            $email['mensagem'] .= '<br>Cidade: '.$nao_encontrei->cidade_interesse.PHP_EOL;
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
                    if($this->envio($email))
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
                $log_mail['to'] = array('comercial@pow.com.br', 'vendas01@pow.com.br', 'programacao@pow.com.br');
                $this->envio($log_mail);
            }
        }
        
	public function listar($coluna = 'id', $ordem = 'DESC', $off_set = 0)
	{
            $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
            $classe = strtolower(__CLASS__);
            $function = strtolower(__FUNCTION__);
            $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->imoveis_naoencontrei_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
            $total = $this->imoveis_naoencontrei_model->get_total_itens( $filtro->get_filtro() );
            $get_url = $filtro->get_url();
            $url = $url.( (empty($get_url) ) ? '?' : $get_url );
            $data['paginacao'] = $this->init_paginacao($total, $url);
            $data['filtro'] = $filtro->get_html();
            $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
            $extras['col'] = $coluna;
            $extras['ordem'] = $ordem; 
            $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
            $this->layout
                        ->set_classe( $classe )
                        ->set_function( $function ) 
                        ->set_include('js/listar.js', TRUE)
                        ->set_include('js/imoveis_naoencontrei.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_breadscrumbs('Painel', 'painel',0)
                        ->set_breadscrumbs('Imóveis Não Encontrei', 'imoveis_naocontrei', 1)
                        ->set_usuario()
                        ->set_menu($this->get_menu($classe, $function))
                        ->view('listar',$data);	
	}
	
	public function editar($codigo = NULL, $ok = FALSE)
	{
		$dados = $this->imoveis_naoencontrei_model->get_item($codigo);
		if ($dados)
		{
			$this->form_validation->set_rules($this->valida);
			if ($this->form_validation->run())
			{
                            $data = $this->_post();
                            unset($data['cidade_hidden']);
                            unset($data['estados']);
                            
                            $id = $this->imoveis_naoencontrei_model->editar($data, array('imoveis_naoencontrei.id' => $codigo));
                            redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
			}
			else
			{
				$function = strtolower(__FUNCTION__);
				$class = strtolower(__CLASS__);
				$data = $this->_inicia_select($codigo, $dados->id_cidade);
                                $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
				$data['tipo'] = 'Imóveis Não Encontrei Editar';
				$data['item'] = $dados;
				$data['mostra_id'] = TRUE;
				$data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
				$this->layout
					->set_function( $function )
					->set_include('js/imoveis_naoencontrei.js', TRUE)
					->set_include('css/estilo.css', TRUE)
                                        ->set_breadscrumbs('Painel', 'painel',0)
                                        ->set_breadscrumbs('Imóveis Não Encontrei', 'imoveis_naoencontrei', 0)
                                        ->set_breadscrumbs('Editar', 'imoveis_naoencontrei', 1)
					->set_usuario($this->set_usuario())
                                        ->set_menu($this->get_menu($class, $function))
					->view('add_imoveis_naoencontrei',$data);
			}
		}
		else 
		{
			redirect('imoveis_naoencontrei/listar');
		}
	}
        
        private function _inicia_select( $id = FALSE, $id_cidade = FALSE ) 
        {
            $retorno['estados'] = $this->estados_model->get_select();
            if(isset($id) && $id)
            {
                if(isset($id_cidade) && $id_cidade)
                {
                    $cidade = $this->cidades_model->get_item($id_cidade);
                    $retorno['estado_select'] = $this->estados_model->get_item($cidade->uf);
                }
            }
            return $retorno;
        }
        
        public function get_cidade() 
        {
            $data = $this->_post(); 
            $retorno = $this->cidades_model->get_select('cidades.uf like "'.$data['uf'].'" ');
            echo json_encode($retorno);
        }
        
	private function _inicia_listagem( $itens, $extras = NULL, $exportar = FALSE )
	{
		if ( $exportar )
		{
			$cabecalho = ' ';
		}
		else 
		{
			$data['cabecalho'] = array(
                                                    (object)array( 'chave' => 'id',     'titulo' => 'ID',       'link' => str_replace(array('[col]','[ordem]'), array('id',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'id') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'id' ) ? 'ui-state-highlight'.( ($extras['col'] == 'id' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'nome', 'titulo' => 'Nome', 	'link' => str_replace(array('[col]','[ordem]'), array('nome',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'nome') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'nome' ) ? 'ui-state-highlight'.( ($extras['col'] == 'nome' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'cidade_interesse', 'titulo' => 'Cidade de Interesse', 	'link' => str_replace(array('[col]','[ordem]'), array('cidade_interesse',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'cidade_interesse') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'cidade_interesse' ) ? 'ui-state-highlight'.( ($extras['col'] == 'cidade_interesse' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'telefone', 'titulo' => 'Telefone', 	'link' => str_replace(array('[col]','[ordem]'), array('telefone',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'telefone') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'telefone' ) ? 'ui-state-highlight'.( ($extras['col'] == 'telefone' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'data', 'titulo' => 'Data', 	'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'enviado', 'titulo' => 'Enviado', 	'link' => str_replace(array('[col]','[ordem]'), array('enviado',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'enviado') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'enviado' ) ? 'ui-state-highlight'.( ($extras['col'] == 'enviado' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'respostas', 'titulo' => 'Respostas', 	'link' => str_replace(array('[col]','[ordem]'), array('respostas',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'respostas') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'respostas' ) ? 'ui-state-highlight'.( ($extras['col'] == 'respostas' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    );
                        
                        $data['operacoes'] = array(
                                                    (object) array('titulo' => 'Editar', 'class' => 'btn btn-info', 'icone' => '<span class="glyphicon glyphicon-pencil"></span>'),
                                                    );
			
			$data['chave'] = 'id';
			$data['itens'] = $itens['itens'];
			$data['extras'] = $extras;
			$this->listagem->inicia( $data );
			$retorno = $this->listagem->get_html();
		}
		return $retorno;
	}
	
	private function _inicia_filtros($url = '', $valores = array() )
	{
                $config['itens'] = array(
                                        array( 'name' => 'id',              'titulo' => 'ID: ',             'tipo' => 'text', 'valor' => '', 'classe' => 'ui-state-default', 'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_naoencontrei.id', 	'valor' => '' ) ),
                                        array( 'name' => 'nome',          'titulo' => 'Nome: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'imoveis_naoencontrei.nome', 	'valor' => '' ) ),
                                        array( 'name' => 'respostas',          'titulo' => 'Respostas: ',           'tipo' => 'text', 'valor' => '', 'classe' => 'form-control ui-state-default', 'where' => array( 'tipo' => 'like', 	'campo' => 'imoveis_naoencontrei.resposta', 	'valor' => '' ) ),
                                        );	
 		$config['colunas'] = 3;
 		$config['extras'] = '';
 		$config['url'] = $url;
 		$config['valores'] = $valores;
 		
 		$filtro = $this->filtro->inicia($config);
 		return $filtro;
		
	}
	
	private function _post()
	{
		return $this->input->post(NULL, TRUE);
	}
}


