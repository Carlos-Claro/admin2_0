<?php
/**
 * Classe que trata o arquivo XML com base nas empresas solicitadas
 * @version 3.1
 * @author Carlos Claro <carlos@carlosclaro.com.br>
 * @since 2015-04-23
 * @copyright (c) 2015, POW internet / Carlos Claro
 * @package Integracao
 */
class MY_XML {
    /**
     * Array com as respostas processadas do sistema.
     * @var array vai conter duas variaveis distintas [erro] = textos com os erros de arquivos E [conteudo] com os valores do xml
     */
    public $resposta = array();

    /**
     *
     * @var array object valores referentes a empresa
     */
    private $empresas = array();
   
    /**
     * Instancia o CI para processamento
     * @var array()
     */
    private $CI;
    
    private $qtde_max = 10;
    
    /**
     * Armazena dados de ftp necessários para processamento.
     * @var array - com as conexões que vão rodar
     */
    protected $conecta = NULL;
    
    public $pasta_raiz = NULL;
    
    /**
     * Pasta de origem dos xml, será importado os arquivos para ela, diretamento do cliente
     * @var string -  endereço getcwd.temporaria/originais/ano/mes/dia
     */
    private $pasta_origem = NULL;
    
    private $pasta_origem_processados = NULL;
    
    /**
     * Local de armazenamento dos arquivos modificados em formato POW
     * @var string - endereço getcwd.temporaria/modificados/ano/mes/dia 
     */
    private $pasta_modificados = NULL;
    
    private $pasta_modificados_processados = NULL;
    
    public $pasta_download = NULL;
    
    /**
     * Reserva para relatorio de erros e procedimentos fora do padrão na importação dos arquivos.
     * @var array - multiposição 
     */
    private $retorno_gera_arquivo = array();
    
    private $log = array();
    private $log_geral = array();
    
    private $dados_empresa = array();
    
    
    /**
     * Contrutor da classe, 
     * @param array $empresas
     * @return \XML
     */
    public function __construct() 
    {
        if ( isset($_GET['xml']) )
        {
            error_reporting(E_ALL);
            error_reporting(-1);
            ini_set('display_errors', 1);
        }
        //parent:: __construct();
        $this->CI =& get_instance();
        $this->CI->load->library('xml_formatos');
        $this->CI->load->library('ftp');
        $this->CI->load->model('sistema_model');
        $this->pasta_raiz = getcwd().'/temporario/';
        $this->pasta_origem = $this->pasta_raiz.'originais/'.date('Y').'/'.date('m').'/'.date('d').'/';
        $this->pasta_origem_processados = $this->pasta_raiz.'processados/originais/'.date('Y').'/'.date('m').'/'.date('d').'/';
        $this->pasta_modificados = $this->pasta_raiz.'modificados/'.date('Y').'/'.date('m').'/'.date('d').'/';
        $this->pasta_modificados_processados = $this->pasta_raiz.'processados/modificados/'.date('Y').'/'.date('m').'/'.date('d').'/';
        $this->pasta_download = $this->pasta_raiz.'download/'.date('Y').'/'.date('m').'/'.date('d').'/';
        $this->_verifica_pasta( $this->pasta_origem );
        $this->_verifica_pasta( $this->pasta_modificados );
        $this->_verifica_pasta( $this->pasta_origem_processados );
        $this->_verifica_pasta( $this->pasta_modificados_processados );
        $this->_verifica_pasta( $this->pasta_download );
        
        
        $keys = json_decode(KEYS);
        $chave_db = LOCALHOST ? 'localhost' : 'server'; 
        $this->conecta = $keys->ftp;
    }
    
    private function inicia()
    {
        return TRUE;
    }
    
    private function _processa_log( $empresa )
    {
        $mensagem = '';
        $mensagem .= '<table width="100%">';
        $mensagem .= '<tr><th colspan="3"><h1>Relatório de Integração com sistema da <a href="http://www.portaisimobiliarios.com.br/">Rede Portais Imobiliário</a> / <a href="'.$empresa->portal.'">'.$empresa->portal.'</a></h1></th></tr>';
        $mensagem .= '<tr><td>Empresa:</td><td colspan="2">'.$empresa->id.' - '.$empresa->empresa_nome_fantasia.'</td></tr>';
        $mensagem .= '<tr><td>Contato:</td><td colspan="2">'.$empresa->contato_nome.'</td></tr>';
        $mensagem .= '<tr><td>Email Contato:</td><td colspan="2">'.$empresa->contato_email.'</td></tr>';
        $this->log_geral[$empresa->id]['empresa_nome_fantasia'] = $empresa->empresa_nome_fantasia;
        $log = $this->log[$empresa->id];
        $log_erro = FALSE;
        //var_dump($log,$empresa);
        if ( isset($log['excedente']) )
        {
            $mensagem_excedente = '<tr><td colspan="3"><h3>Excedente</h3></td></tr>';
            $mensagem_excedente .= '<tr><td>Limite de Imóveis:</td><td colspan="2">'.$empresa->pagina_limite_produtos.'</td></tr>';
            $mensagem_excedente .= '<tr><td>Qtde Imóveis excedentes:</td><td colspan="2">'.count($log['excedente']).'</td></tr>';
            $this->log_geral[$empresa->id]['excedente']['qtde'] = count($log['excedente']);
            $mensagem_excedente .= '<tr><td>Lista de imóveis que excederam ao limite:</td><td colspan="2">';
            $this->log_geral[$empresa->id]['excedente']['itens'] = '';
            $qtde_excedente = 0;
            foreach( $log['excedente'] as $excedente )
            {
                $mensagem_excedente .= ( ! $qtde_excedente ) ? $excedente : ', '.$excedente;  
                $this->log_geral[$empresa->id]['excedente']['itens'] .= ( ! $qtde_excedente ) ? $excedente : ', '.$excedente; 
                $qtde_excedente++;
            }
            $mensagem_excedente .= '</td></tr>';
            $mensagem .= $mensagem_excedente;
            $log_erro['excedente'] = $mensagem_excedente;
        }
        if ( isset($log['imoveis_existentes']) && count($log['imoveis_existentes']) > 0 )
        {
            
            $mensagem_excluidos = '<tr><td colspan="3"><h3>Exclusão</h3></td></tr>';
            $mensagem_excluidos .= '<tr><td>Qtde Excluidos:</td><td colspan="2">'.count($log['imoveis_existentes']).'</td></tr>';
            $this->log_geral[$empresa->id]['excluidos']['qtde'] = count($log['imoveis_existentes']);
            $mensagem_excluidos .= '<tr><td>Listagem de imóveis excluidos:</td><td colspan="2">';
            $this->log_geral[$empresa->id]['excluidos']['itens'] = '';
            $qtde_inexistentes = 0;
            foreach( $log['imoveis_existentes']  as $chave_existente => $referencia_existente )
            {
                $mensagem_excluidos .= ( ! $qtde_inexistentes ) ? $referencia_existente->referencia : ', '.$referencia_existente->referencia;
                $this->log_geral[$empresa->id]['excluidos']['itens'] .= ( ! $qtde_inexistentes ) ? $referencia_existente->referencia : ', '.$referencia_existente->referencia;
                $deleta_images = $this->CI->imoveis_images_model->excluir( 'imoveis_images.id_imovel = '.$chave_existente );
                $deleta_existente = $this->CI->imoveis_model->excluir( 'imoveis.id = '.$chave_existente );
                if ( $deleta_existente )
                {
                    $this->CI->imoveis_historico_model->adicionar($referencia_existente);
                }
                $qtde_inexistentes++;
            }
            $mensagem_excluidos .= '</td></tr>';
            $mensagem .= $mensagem_excluidos;
            $log_erro['excluidos'] = $mensagem_excluidos;
        }
        if ( isset($log['status']) )
        {
            $mensagem .= '<tr><td colspan="3"><h3>Cadastro</h3></td></tr>';
            $mensagem .= '<tr><td>Qtde Cadastrado:</td><td colspan="2">'.count($log['status']).'</td></tr>';
            $qtde_imoveis = count($log['status']);
            $this->log_geral[$empresa->id]['cadastro']['qtde'] = count($log['status']);
            $mensagem .= '<tr><th>Nosso id</th><th>Sua Referência</th><th>Status</th></tr>';
            foreach( $log['status'] as $chave_status => $valor_status )
            {
                $mensagem .= '<tr>';
                $mensagem .= '<td>'.(isset($valor_status['id']) ? $valor_status['id'] : '' ).'</td>';
                $mensagem .= '<td>'.$chave_status.'</td>';
                $mensagem .= '<td>';
                if ( $valor_status['sucesso'] )
                {
                    if ( isset($valor_status['altera']) )
                    {
                        $mensagem .= $valor_status['altera'] ? 'Alterado' : 'Sem alteração';
                    }
                    else
                    {
                        $mensagem .=  'Adicionado';
                    }
                }
                else
                {
                    $mensagem .= 'Erro ao inserir';
                }
                $mensagem .= '</td>';
                $mensagem .= '</tr>';
            }
        }
        if ( isset( $log['erro'] ) )
        {
            $mensagem .= '<tr><td colspan="3"><h3>Observações</h3></td></tr>';
            if ( isset($log['erro']['bairro']) )
            {
	        	$log_erro['bairro'] = TRUE;
                $mensagem_bairro = '<tr><td colspan="3"><h3>Bairros não encontrados:</h3></td></tr>';
                $mensagem_bairro .= '<tr><td>Qtde:</td><td colspan="2">'.count($log['erro']['bairro']).'</td></tr>';
                $this->log_geral[$empresa->id]['bairro']['qtde'] = count($log['erro']['bairro']);
                $mensagem_bairro .= '<tr><td>Listagem de Bairros:</td><td colspan="2">';
                $this->log_geral[$empresa->id]['bairro']['itens'] = '';
                $qtde_bairros = 0;
                foreach( $log['erro']['bairro']  as $chave_bairro => $valor_bairro )
                {
                    $mensagem_bairro .= ( ! $qtde_bairros ) ? $chave_bairro.' - '.$valor_bairro : ', '.$chave_bairro.' - '.$valor_bairro;
                    $this->log_geral[$empresa->id]['bairro']['itens'] .= ( ! $qtde_bairros ) ? $chave_bairro.' - '.$valor_bairro : ', '.$chave_bairro.' - '.$valor_bairro;
                    $qtde_bairros++;
                }
                $mensagem_bairro .= '</td></tr>';
                $mensagem .= $mensagem_bairro;
                $log_erro['bairro'] = $mensagem_bairro;
            }
            if ( isset($log['erro']['tipo']) )
            {
                $mensagem_tipo = '<tr><td colspan="3"><h3>Tipos não encontrados:</h3></td></tr>';
                $mensagem_tipo .= '<tr><td>Qtde:</td><td colspan="2">'.count($log['erro']['tipo']).'</td></tr>';
                $this->log_geral[$empresa->id]['tipo']['qtde'] = count($log['erro']['tipo']);
                $mensagem_tipo .= '<tr><td>Listagem de Tipos:</td><td colspan="2">';
                $qtde_tipos = 0;
                foreach( $log['erro']['tipo']  as $chave_tipo => $valor_tipo )
                {
                    $mensagem_tipo .= ( ! $qtde_tipos ) ? $chave_tipo.' - '.$valor_tipo : ', '.$chave_tipo.' - '.$valor_tipo;
                    $this->log_geral[$empresa->id]['tipo']['itens'][] = $valor_tipo;
                    $this->_set_tipo_nao_existe($valor_tipo);
                    $qtde_tipos++;
                }
                $mensagem_tipo .= '</td></tr>';
                $mensagem .= $mensagem_tipo;
                $log_erro['tipo'] = $mensagem_tipo;
            }
            if ( isset($log['erro']['cidade']) )
            {
            	$log_erro['cidade'] = TRUE;
                $mensagem_cidade = '<tr><td colspan="3"><h3>Cidades não encontradas:</h3></td></tr>';
                $mensagem_cidade .= '<tr><td>Qtde:</td><td colspan="2">'.count($log['erro']['cidade']).'</td></tr>';
                $this->log_geral[$empresa->id]['cidade']['qtde'] = count($log['erro']['cidade']);
                $mensagem_cidade .= '<tr><td>Listagem de Cidades:</td><td colspan="2">';
                $qtde_cidades = 0;
                foreach( $log['erro']['cidade']  as $chave_cidade => $valor_cidade )
                {
                    $mensagem_cidade .= ( ! $qtde_cidades ) ? $chave_cidade.' - '.$valor_cidade : ', '.$chave_cidade.' - '.$valor_cidade;
                    $this->log_geral[$empresa->id]['cidade']['itens'][] = $valor_cidade;
                    $qtde_cidades++;
                }
                $mensagem_cidade .= '</td></tr>';
                $mensagem .= $mensagem_cidade;
                $log_erro['cidade'] = $mensagem_cidade;
            }
        }
        
        
        $mensagem .= '<tr><td colspan="3"><p>Obrigado por utilizar os serviços POW Internet, qualquer duvida ou sugestão: (41) 3382-1581 ou e-mail: comercial@pow.com.br</p></td></tr>';
        
        
        /**
         *  'id' => string '1835' (length=4)
            'empresa_nome_fantasia' => string 'Rocco ImÃ³veis SÃ£o JosÃ©' (length=25)
            'contato_nome' => string 'Rosana Ansaloni' (length=15)
            'contato_email' => string 'imobiliariarocco@yahoo.com.br' (length=29)
            'pagina_limite_ofertas' => string '8' (length=1)
            'pagina_limite_produtos' => string '3000' (length=4)
            'email_log' => string '0' (length=1)
            'portal' => string 'http://www.ImoveisSaoJose.com' (length=29)
         */
        $mensagem .= '</table>';
        if ( $empresa->email_log == 0 )
        {
            $array_envio = array(
                                'assunto'       => 'Atualização diária Rede de Portais Imobiliários.',
                                'mensagem'      => $mensagem,
                                'to'            => $empresa->contato_email,
                                'bcc'           => 'programacao@pow.com.br',
                                );
            if ( strstr($_SERVER['HTTP_HOST'],'localhost') )
            {
                echo $mensagem;
            }
            else
            {
                $enviado = $this->envio($array_envio);
            }
        }
        if ( $log_erro )
        {
        	$mensagem_erro = '<table width="100%">';
        	$mensagem_erro .= '<tr><th colspan="3"><h1>Relatório de Erros do sistema</h1></th></tr>';
        	$mensagem_erro .= '<tr><td>Empresa:</td><td colspan="2">'.$empresa->id.' - '.$empresa->empresa_nome_fantasia.'</td></tr>';
        	$mensagem_erro .= '<tr><td>Contato:</td><td colspan="2">'.$empresa->contato_nome.'</td></tr>';
        	$mensagem_erro .= '<tr><td>Email Contato:</td><td colspan="2">'.$empresa->contato_email.'</td></tr>';
        	/*
        	 * 3 - piazzetta
        	 * 5 - Solange
        	 * 
        	 */
	       	$enviar = FALSE;
	       	$enviar_geral = FALSE;
       		$array_sender = array();
        	foreach( $log_erro as $chave_erro => $valor_erro )
        	{
        		$enviar = FALSE;
        		$assunto = 	'Erro de sistema '.$chave_erro;
        		switch ( $chave_erro )
        		{
        			case 'excedente':
        				$sender = 'vendas01@pow.com.br';
        				$envio = TRUE;
        				$enviar_geral = TRUE;
        				break;
        			case 'excluidos':
        				$sender = 'programacao@pow.com.br, vendas01@pow.com.br';
        				if ( ! isset($qtde_imoveis) )
        				{
        					$envio = TRUE;
	        				$enviar_geral = TRUE;
        				}
        				elseif ( $qtde_inexistentes <= $qtde_imoveis )
        				{
        					$envio = TRUE;
	        				$enviar_geral = TRUE;
        				}
        				break;
        			
        		}
        		$mensagem_erro .= $valor_erro;
        		if ( $enviar )
        		{
        			if ( count($array_sender) == 0 )
        			{
        				$array_sender[] = $sender;
        			}
        			else
        			{
		        		if ( ! array_search($sender, $array_sender) )
		        		{
		        			$array_sender = array_push($array_sender,$sender);
		        		}
        			}
        		}
        	}
        	
        	$mensagem_erro .= '</table>';
        	$array_envio_erro = array(
        			'assunto'       => 'Atualização de erros integração: '.$empresa->id.' - '.$empresa->empresa_nome_fantasia,
        			'mensagem'      => $mensagem_erro,
        			'to'            => 'vendas01@pow.com.br',
        	);
        	if ( strstr($_SERVER['HTTP_HOST'],'localhost') )
        	{
        		echo $mensagem_erro;
        	}
                else
                {
                    if ( $enviar_geral )
                    {
                        $enviado = $this->envio($array_envio_erro);
                    }
                    
                }
        }
        
    }
    
    
    /**
     * envio de email com seus respectivos campos, e anexos
     * [assunto]
     * [mensagem]
     * [email] -> from
     * [to] -> array ou separado por ,
     * [bcc]
     * [anexo] -> endereco fisico do arquivo
     * @param array $data  com os campos necessários para envio do email usando o codeigniter
     * @return bollean - se enviou corretamente true
     * @version 1.0
     * @access public
     */
    private function envio( $data )	
    {	
        $this->CI->email->clear();
        $config['mailtype'] = 'html';
        $config['protocol'] = 'smtp';
        $config['useragent'] = 'GuiaSJP';
        $config['smtp_host'] = 'smtp.pow.com.br';
        $config['smtp_user'] = 'autenticacao@guiasjp.com';
        $config['smtp_pass'] = 'c2a0r1l2';
        $config['smtp_port'] = '587';
        $mail = $this->CI->email->initialize($config);
        $subject = (isset($data['assunto']) && $data['assunto']) ? $data['assunto'] : 'GuiaSJP';		
        $mensagem = (isset($data['mensagem']) && $data['mensagem']) ? $data['mensagem'] : '';
        $from = (isset($data['email']) && $data['email']) ? $data['email'] : 'programacao@pow.com.br';
        $to = (isset($data['to']) && $data['to']) ? $data['to'] : 'programacao@pow.com.br';
        $bcc = (isset($data['bcc']) && $data['bcc']) ? $data['bcc'] : '';
        $this->CI->email
                ->from($from)
                ->to($to)
                ->bcc($bcc)
                ->subject($subject)
                ->message($mensagem);
        if( isset($data['anexo']) && !empty($data['anexo']) )
        {
            $this->CI->email->attach($data['anexo']);
        }
        return  $this->CI->email->send();
    }
    
    /**
     * Processamento de arquivos xml com base no xml_formatos->formato_padrao 
     * Processo:
     * Pega arquivos da pasta_modificado do dia...
     * capta dados da emrpesa e dos imoveis dela
     * processa os campos do formato padrao com base logica e qtde de itens contratados e retorna $this->log com os dados de processamento.
     * 
     */
    public function processa_xml( $limite_itens = 25 )
    {
        $this->CI->load->helper('file');
        $this->CI->load->model( array( 'empresas_model', 'imoveis_model', 'imoveis_historico_model', 'imoveis_tipos_model', 'imoveis_equi_cs_model', 'bairros_model', 'bairros_equivalentes_model', 'cidades_model', 'imoveis_images_model' ) );
        $arquivos = get_dir_file_info( $this->pasta_modificados );
        if ( $arquivos )
        {
        	$contador_voltas = 0;
            foreach( $arquivos as $chave => $atributo )
            {
	                $conteudo = simplexml_load_file( $atributo['server_path'], 'SimpleXMLElement', LIBXML_NOEMPTYTAG );
	                $nome = explode('_', substr($chave, 0, -4 ));
	                $id_empresa = $nome[0];
	                $empresa = $this->CI->empresas_model->get_itens_por_empresa($id_empresa);
	                $this->dados_empresa[$id_empresa] = $empresa;
	                $this->log[$id_empresa]['imoveis_existentes'] = $this->CI->imoveis_model->get_id_chave_por_id_empresa( $id_empresa );
                        $qtde = 0;
                        $qtde_total = count($conteudo);
                        if ( $qtde_total > 1 )
                        {
                            foreach( $conteudo as $imovel )
                            {
                                $array_inclusao = NULL;
                                if ( $empresa->pagina_limite_produtos > $qtde )
                                {
                                    $data = $this->_set_array_imovel($imovel, $id_empresa);
                                    $registro_imovel = $this->CI->imoveis_model->get_item_por_filtro( $data['filtro'] );
                                    $fotos = isset($data['data']['fotos']) ? $data['data']['fotos'] : NULL;
                                    unset($data['data']['fotos']);
                                    if ( isset($registro_imovel) )
                                    {
                                        if ( isset($this->log[$id_empresa]['imoveis_existentes'][$registro_imovel->id]) )
                                        {
                                            unset( $this->log[$id_empresa]['imoveis_existentes'][$registro_imovel->id] );
                                        }
                                        //editar
                                        $filtro = 'imoveis.id = '.$registro_imovel->id;
                                        $this->log[$id_empresa]['status'][$data['referencia']]['altera'] = $this->CI->imoveis_model->editar( $data['data'], $filtro );
                                        $this->log[$id_empresa]['status'][$data['referencia']]['id'] = $registro_imovel->id;
                                        $this->log[$id_empresa]['status'][$data['referencia']]['sucesso'] = 1;
                                        $this->processa_images_salvas($fotos, $id_empresa, $registro_imovel->id, $registro_imovel->data);
                                        if ( $this->log[$id_empresa]['status'][$data['referencia']]['altera'] )
                                        {
                                            $data_altera = array( 'data_atualizacao' => time() );
                                            $this->CI->imoveis_model->editar( $data_altera, $filtro );
                                        }
                                    }
                                    else
                                    {
                                        //adicionar
                                        $data['data']['data'] = time();
                                        $data['data']['data_atualizacao'] = time();
                                        $id_imovel = $this->CI->imoveis_model->adicionar( $data['data'] );
                                        if ( $id_imovel )
                                        {
                                            $this->log[$id_empresa]['status'][$data['referencia']]['id'] = $id_imovel;
                                            $this->log[$id_empresa]['status'][$data['referencia']]['sucesso'] = 1;
                                            $this->processa_images_salvas($fotos, $id_empresa, $id_imovel,$data['data']['data']);
                                            //$this->add_fotos($fotos, $id_empresa, $id_imovel, $data['data']);
                                        }
                                        else
                                        {
                                            $this->log[$id_empresa]['status'][$data['referencia']]['sucesso'] = 0;
                                        }
                                    }
                                }
                                else
                                {
                                    $this->log[$id_empresa]['excedente'][] = (string)$imovel->ref;
                                }

                                $qtde++;

                            }
                        }
                        else
                        {
                            unset($this->log[$id_empresa]['imoveis_existentes']);
                        }
	                $this->_armazena_processados($atributo['server_path'], $id_empresa);
	                $this->_processa_log($empresa);
	                $contador_voltas++;
	                if ( $contador_voltas > $limite_itens )
	                {
						die('parou em '.$contador_voltas);	                	
	                }
	            }
            //die();
        }
    }
    
    private function processa_images_salvas( $fotos, $id_empresa, $id_imovel, $data )
    {
        $chave_arquivos = $this->CI->imoveis_images_model->get_arquivo_chave_por_filtro('imoveis_images.id_imovel = '.$id_imovel);
        $array_inclusao = NULL;
        if ( $fotos['fotos'] )
        {
            
            foreach( $fotos['fotos'] as $foto )
            {
                $arquivo = $this->_set_valor($foto['arquivo'],'varchar');
                $legenda = $this->_set_valor($foto['titulo'],'varchar');
                $ordem = $this->_set_valor($foto['ordem'],'varchar');
                if ( isset( $chave_arquivos[$arquivo] ) )
                {
                    if ( ( $legenda != $chave_arquivos[$arquivo]->titulo ) || ( $foto['ordem'] != $chave_arquivos[$arquivo]->ordem ) )
                    {
                        $update['titulo'] = $legenda;
                        $update['ordem'] = $foto['ordem'];
                        $filtro = array('id' => $chave_arquivos[$arquivo]->id);
                        $this->CI->imoveis_images_model->editar($update, $filtro);
                    }
                    unset($chave_arquivos[$arquivo]);
                }
                else
                {
                    $array_inclusao[] = array('arquivo' => $arquivo, 'id_empresa' => $id_empresa, 'id_imovel' => $id_imovel, 'data' => $data, 'titulo' => $legenda, 'ordem' => ( isset($ordem) ? $ordem : 100) );
                }
            }
            if ( isset($chave_arquivos) && count($chave_arquivos) > 0 && $chave_arquivos )
            {
                foreach ( $chave_arquivos as $chaves )
                {
                    $this->CI->imoveis_images_model->excluir(array('id' => $chaves->id));
                }
            }
            if ( isset($array_inclusao) )
            {
                $this->CI->imoveis_images_model->adicionar_multi($array_inclusao);
            }
            
        }
    }
    
    private function add_foto ( $foto, $id_empresa, $id_imovel, $data )
    {
        $array_arquivo = array('arquivo' => $this->_set_valor($foto['arquivo'], 'varchar'), 'id_empresa' => $id_empresa, 'data' => date('Y-m-d H:i', $data ) );
        $id_arquivo = $this->CI->images_model->adicionar_arquivo($array_arquivo);
        if ( $id_arquivo )
        {
            $array_pai = array('id_image_tipo' => 31, 'id_image_arquivo' => $id_arquivo, 'id_pai' => $id_imovel, 'titulo' => $this->_set_valor($foto['titulo'], 'varchar') , 'ordem' => (isset($foto['ordem']) ? $foto['ordem'] : 100) );
            $id_pai = $this->CI->images_model->adicionar_pai($array_pai);
            if( ! $id_pai )
            {
                $this->log[$id_empresa]['status'][$data['referencia']]['foto'][$foto['ordem']] = 'Problemas ao adicionar o relação de arquivo:'.$id_arquivo.'.';
            }
        }
        else
        {
            $this->log[$id_empresa]['status'][$data['referencia']]['foto'][$foto['ordem']] = 'Problemas ao adicionar o arquivo.';
        }
        
    }
    
    /**
     * Monta array que vai adicionar ou alterar arquivo no banco de dados
     * @param type $imovel
     * @param type $id_empresa
     * @return type
     */
    private function _set_array_imovel ( $imovel = NULL, $id_empresa = FALSE )
    {
        //var_dump($imovel);die();
        $retorno = NULL;
        if ( isset($imovel) )
        {
            foreach ( $this->CI->xml_formatos->formato_padrao as $chave => $atributos )
            {
                if ( $chave == 'id' )
                {
                    $retorno['filtro'] = 'imoveis.chave = "'.$imovel->$chave.'" AND imoveis.id_empresa = '.$id_empresa;
                    $retorno['data'][$atributos['campo']] = (string)$imovel->$chave;
                }
                elseif ( $chave == 'ref' )
                {
                    $retorno['referencia'] = (string)$imovel->$chave;
                    if ( isset( $imovel->$chave ) )
                    {
                        $retorno['data'][$atributos['campo']] = (string)$imovel->$chave;
                    }
                }
                elseif ( $chave == 'id_empresa' )
                {
                    $retorno['data']['id_empresa'] = (string)$id_empresa;
                }
                else
                {
                    if ( $atributos['tratamento'] )
                    {
                        $function = $atributos['tratamento'];
                        if ( isset($atributos['complemento']) )
                        {
                            if ( isset( $imovel->$chave ) )
                            {
                                if ( is_array( $atributos['complemento'] ) )
                                {
                                    foreach( $atributos['complemento'] as $complemento )
                                    {
                                        $valor_complemento[$complemento] = isset($retorno['data'][$complemento]) ? $retorno['data'][$complemento] : NULL;
                                    }
                                    $retorno_tratamento = $this->$function( $imovel->$chave, $valor_complemento );
                                }
                                else
                                {
                                    //var_dump($imovel->chave, $atributos['complemento']);
                                    $retorno_tratamento = $this->$function( $imovel->$chave, $retorno['data'][ $atributos['complemento'] ] );
                                }
                                if ( is_array( $atributos['campo'] ) )
                                {
                                    foreach( $atributos['campo'] as $campo )
                                    {
                                        $retorno['data'][$campo] = isset( $retorno_tratamento[$campo] ) ? $this->_set_valor( $retorno_tratamento[$campo], $atributos['tipo'] ) : 0;
                                    }
                                }
                                else
                                {
                                    //var_dump($retorno_tratamento,$atributos['campo'] );
                                    $retorno['data'][ $atributos['campo'] ] = $this->_set_valor( $retorno_tratamento, $atributos['tipo'] );
                                }
                            }
                        }
                        else
                        {
                            if ( isset( $imovel->$chave ) )
                            {
                                $retorno['data'][$atributos['campo']] = $this->_set_valor( $imovel->$chave, $atributos['tipo'] );
                            }
                        }
                    }
                    else
                    {
                        if ( isset( $imovel->$chave ) )
                        {
                            $retorno['data'][$atributos['campo']] = $this->_set_valor( htmlspecialchars_decode( $imovel->$chave ), $atributos['tipo'] );
                        }
                    }
                }
            }
        }
        if ( isset($retorno['data']['estado']) )
        {
            unset($retorno['data']['estado']);
        }
        if ( isset($retorno['data'][0]) )
        {
            unset($retorno['data'][0]);
        }
        //var_dump($retorno);
        return $retorno;
    }
    
    private function _set_valor( $valor, $tipo )
    {
        
        $valor = is_array($valor) ? $valor : trim($valor);
        $retorno = '';
        switch ( $tipo )
        {
            case 'array':
                //var_dump($valor);die();
                $retorno = ( isset($valor) && $valor ) ? $valor : FALSE;
                break;
            case 'int':
            case 'decimal':
                $retorno = ( isset($valor) && $valor && ( ! empty($valor) ) ) ? $valor : 0;
                break;
            case 'date':
            case 'varchar':
            case 'text':
            default:
                $retorno = (string)$valor;
                break;
        }
        return $retorno;
    }
    
    private function verifica_tipo ( $valor, $complemento )
    {
        $this->CI->load->model('imoveis_equi_cs_model');
        $filtro = 'imoveis_equi_cs.tipo like "'.$this->string_convert(str_replace(' ','%', addslashes($valor) )).'" AND imoveis_equi_cs.pendente = 0';
        $tipo = $this->CI->imoveis_equi_cs_model->get_item_por_filtro($filtro);
        if ( isset($tipo) )
        {
            $retorno = array('id_tipo' => $tipo->id_tipo,'id_estilo' => $tipo->id_estilo,'id_linha' => 0, 'comercial' => $tipo->comercial, 'residencial' => $tipo->residencial );
        }
        else
        {
            $retorno = array('id_tipo' => 14,'id_estilo' => 0,'id_linha' => 0, 'comercial' => 0, 'residencial' => 0);
            $this->log[$complemento['id_empresa']]['erro']['tipo'][$complemento['referencia']] = $valor;
        }
        return $retorno;
    }
    
    private function verifica_area ( $valor, $complemento )
    {
        $tipo = $this->CI->imoveis_tipos_model->get_item($complemento);
        $valor = $this->valor_tira_especiais($valor);
        if ( isset($tipo) )
        {
            if ( $tipo->tipo_area == 'c' )
            {
                $retorno = array('area' => $valor,'area_terreno' => 0);
            }
            else
            {
                $retorno = array('area' => 0,'area_terreno' => $valor);
            }
        }
        else
        {
            $retorno = array('area' => 0,'area_terreno' => 0);
        }
        return $retorno;
    }
    
    private function verifica_area_terreno( $valor, $complemento )
    {
        $valor = $this->valor_tira_especiais($valor);
        if ( isset($complemento) && ! empty($complemento) && $complemento )
        {
            $retorno = $complemento;
        }
        else
        {
            $retorno = $valor;
        }
        return $retorno;
    }
    
    private function verifica_bairro ( $bairro_nome, $complemento )
    {
        $bairro = $this->CI->bairros_model->get_id_por_nome_cidade($bairro_nome, $complemento['id_cidade']);
        if ( isset($bairro) )
        {
            $retorno = array( 'bairro_combo' => $bairro, 'bairro' => $bairro_nome );
        }
        else
        {
            $bairro_equivalente = $this->CI->bairros_equivalentes_model->get_id_por_nome_cidade($bairro_nome, $complemento['id_cidade']);
            if ( isset($bairro_equivalente) )
            {
                $retorno = array( 'bairro_combo' => $bairro_equivalente, 'bairro' => $bairro_nome );
                
            }
            else
            {
                $retorno = array( 'bairro_combo' => 0, 'bairro' => $bairro_nome );
                $this->log[$complemento['id_empresa']]['erro']['bairro'][$complemento['referencia']] = $bairro_nome.' / '.$complemento['cidade'].' / '.$complemento['estado'];
            }
        }
        return $retorno;
    }
    
    private function verifica_estado( $estado )
    {
        $this->CI->load->model('estados_model');
        $estado_retorno = $this->CI->estados_model->get_item_por_nome($estado);
        return $estado_retorno;
    }
    
    private function verifica_cidade ( $valor, $complemento )
    {
        if ( isset($complemento['estado']) && ! empty( $complemento['estado'] ) )
        {
            $estado = strlen($complemento['estado']) > 2 ? $this->verifica_estado($complemento['estado']) : $complemento['estado'];
            if( isset($estado) )
            {
                $cidade = $this->CI->cidades_model->get_id_por_nome_uf( $valor, $estado );
            }
            else
            {
                $cidade = $this->CI->cidades_model->get_id_por_nome_uf( $valor );
            }
        }
        else
        {
            $cidade = $this->CI->cidades_model->get_id_por_nome_uf( $valor );
        }
        
        if ( isset($cidade) )
        {
            $retorno = array('cidade' => $valor,'id_cidade' => $cidade);
            
        }
        else
        {
            $retorno = array('cidade' => $valor,'id_cidade' => 0);
            $this->log[$complemento['id_empresa']]['erro']['cidade'][$complemento['referencia']] = $cidade.' / '.$complemento['estado'];
        }
        return $retorno;
    }
    
    private function verifica_fotos ( $valor, $complemento )
    {
        $limite = $this->dados_empresa[$complemento['id_empresa']]->pagina_limite_ofertas;
        $retorno['fotos'] = FALSE;
        
        $a = 2;
        foreach ( $valor as $fotos )
        {
            $b = 1;
            foreach ( $fotos as $foto )  
            {
                $retorno['fotos'][] = array(
                                                'arquivo' => $foto->foto_url,
                                                'ordem' => ( ( isset($foto->foto_ordem) && ! empty($foto->foto_ordem) ) ? $foto->foto_ordem : ($foto->foto_principal == 1 ? 1 : $a) ),
                                                'titulo' => $foto->foto_legenda,
                                                );
                $a++;
            }
        }
        return $retorno;
        /**
         * Alteração para suporte a mais imagens por empresa...
         * @since 2016-01-11
        for( $imag = 1; $imag < 13; $imag++) 
        {
            $retorno['foto'.$imag] = '';
            $retorno['foto'.$imag.'_descricao'] = '';
        }
        
        $a = 1;
        foreach ( $valor as $fotos )
        {
            $b = 1;
            foreach ( $fotos as $foto )  
            {
                if ( $b <= $limite )
                {
                    if ( $foto->foto_ordem <= $limite )
                    {
                        $retorno['foto'.( isset($foto->foto_ordem) && ! empty($foto->foto_ordem) ? $foto->foto_ordem : $a)]  = $foto->foto_url;
                        $retorno['foto'.( isset($foto->foto_ordem) && ! empty($foto->foto_ordem) ? $foto->foto_ordem : $a).'_descricao']   = $foto->foto_legenda;
                        $b++;
                    }
                    $a++;
                }
            }
        }
         */
    }
    
    /**
     * Função q gerencia a alteração dos arquivos com base na pasta_origem, pega todos e transforma no xml padrao da pow, utilizando função transforma_xml
     * @return void emails e transferencia de arquivos para a pasta certa
     */
    public function altera_arquivos()
    {
        $this->CI->load->helper('file');
        $arquivos = get_dir_file_info( $this->pasta_origem );
        if ( $arquivos )
        {
            foreach( $arquivos as $chave => $atributo )
            {
                //var_dump($chave, $atributo);
                $nome = explode('_', substr($chave, 0, -4 ));
                $sistema = $nome[0];
                $id_empresa = $nome[1];
                if ( $atributo['size'] > 0 )
                {
                    $this->_transforma_xml($atributo['server_path'], $id_empresa, $sistema);
                }
                else
                {
                    // tratar erro de size
                }
                
            }
            //var_dump($arquivos);
        }
        else
        {
            die('Nenhum arquivo encontrado, tente novamente mais tarde');
        }
        
    }
    
    /**
     * Função para transforma os xml em pasta_origem no formato original para pasta_modificados no formato pow
     * @param string $pacote - endereço fisico do arquivo a ser modificado
     * @param int $id_empresa - id da empresa para uso e consultas
     * @param int $id_sistema - id do sistema que vai usar a tranformação
     */
    private function _transforma_xml( $pacote, $id_empresa, $id_sistema )
    {
            $sistema = $this->CI->sistema_model->get_item($id_sistema);
            //var_dump($sistema);die();
            if ( isset($sistema) )
            {
                if ( $sistema->tag == 'pow' )
                {
                    $this->_transporta_xml($pacote, $id_empresa);
                }
                else
                {
                    //var_dump($id_empresa);
                    $info = new finfo( FILEINFO_MIME);
                    $is_xml = $info->file($pacote); 
                    //var_dump($is_xml);die();
                    if ( strstr($is_xml, 'xml') )
                    {
                        //$options = ['LIBXML_NOEMPTYTAG','LIBXML_NOENT', 'LIBXML_NOERROR'];
                        $conteudo_original = simplexml_load_file( $pacote, 'SimpleXMLElement',  LIBXML_NOEMPTYTAG | LIBXML_NOENT );
                        //var_dump($conteudo_original);die();
                        $tag = $sistema->tag;
                        $variaveis = $this->CI->xml_formatos->{$tag};
                        $sequencia = 0;
                        $retorno = array();
                        $v = isset($variaveis['chave']) ? $variaveis['chave'] : NULL;
                        $imoveis = ( isset($v) ? $conteudo_original->{$v} : $conteudo_original );
                        $vs = isset($variaveis['subchave']) ? $variaveis['subchave'] : NULL;
                        $imoveis = ( isset($vs) ? $imoveis->{$vs} : $imoveis );
                        foreach ( $imoveis as $imovel )
                        {
                            foreach( $variaveis['campos'] as $chave => $campo )
                            {
                                if ( $campo )
                                {
                                    if ( $campo['tratamento'] )
                                    {
                                        $function = $campo['tratamento'];
                                        if ( isset( $campo['complemento'] ) )
                                        {
                                            if ( is_array($campo['complemento']) )
                                            {
                                                $valor_complemento = array();
                                                foreach( $campo['complemento'] as $conta_complemento => $complemento )
                                                {
                                                    $valor_complemento[$complemento] = $retorno[$sequencia][$complemento]; 
                                                }
                                                $function_retorno = $this->$function($imovel->$chave,$valor_complemento);
                                                foreach( $campo['equivalente'] as $equivalente )
                                                {
                                                    $retorno[ $sequencia ][ $equivalente ] = $function_retorno[ $equivalente ];
                                                }
                                            }
                                            else
                                            {
                                                if ( $campo['complemento'] == 'itens_self' )
                                                {
                                                    $function_retorno = $this->$function( $imovel->$chave, $campo['itens']);
                                                    if ( is_array($campo['equivalente']) )
                                                    {
                                                        foreach( $campo['equivalente'] as $equivalente )
                                                        {
                                                            $retorno[$sequencia][$equivalente] = $function_retorno[ $equivalente ];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $retorno[$sequencia][ $campo['equivalente'] ] = $function_retorno;
                                                    }
                                                }
                                                else
                                                {
                                                    //echo $chave;
                                                    $function_retorno = $this->$function($imovel->$chave, $retorno[$sequencia][ $campo['complemento'] ]);
                                                    if ( is_array($campo['equivalente']) )
                                                    {
                                                        foreach( $campo['equivalente'] as $equivalente )
                                                        {
                                                            $retorno[$sequencia][$equivalente] = $function_retorno[ $equivalente ];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $retorno[$sequencia][ $campo['equivalente'] ] = $function_retorno;
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $function_retorno = $this->$function($imovel->$chave);
                                            if ( is_array($campo['equivalente']) )
                                            {
                                                foreach( $campo['equivalente'] as $equivalente )
                                                {
                                                    $retorno[$sequencia][$equivalente] = $function_retorno[$equivalente];
                                                }
                                            }
                                            else
                                            {
                                                $retorno[$sequencia][$campo['equivalente']] = $function_retorno;
                                            }

                                        }

                                    }
                                    else // tratamento
                                    {
                                        if ( is_array($campo['equivalente']) )
                                        {
                                            foreach( $campo['equivalente'] as $equivalente )
                                            {
                                                $retorno[$sequencia][$equivalente] = $imovel->$chave;
                                            }
                                        }
                                        else
                                        {
                                            $retorno[$sequencia][$campo['equivalente']] = $imovel->$chave;
                                        }
                                    }
                                }
                            }
                            $sequencia++;
                        }
                        /**
                         * força montagem
                         */
                        if ( isset($retorno) && $retorno && count($retorno) > 0  )
                        {
                            $this->_montar_xml( $retorno, $id_empresa, $sistema->tag );
                        }
                        else
                        {
                            //reportar erro de processamento;
                        }
                        
                    }
                    else
                    {
                        //tratar erro de não xml o arquivo
                    }
                    
                }
                //armazena dados
                $this->_armazena_originais($pacote, $id_sistema, $id_empresa);
            }
    }
    
    private function _armazena_processados( $pacote, $empresa )
    {
        $arquivo_nome = $this->pasta_modificados_processados.$empresa.'_'.time().'.xml';
        rename($pacote, $arquivo_nome);
    }
    
    private function _armazena_originais( $pacote, $sistema, $empresa )
    {
        $arquivo_nome = $this->pasta_origem_processados.$sistema.'_'.$empresa.'_'.time().'.xml';
        rename($pacote, $arquivo_nome);
    }
    
    private function _transporta_xml( $pacote, $id_empresa )
    {
        $arquivo_nome = $this->pasta_modificados.$id_empresa.'.xml';
        copy($pacote,$arquivo_nome);
        
    }
    
    /**
     * Com base em imoveis, e id_empresa monta o arquivo xml em local fisico pasta_modificados
     * @param array $imoveis - simpleXMLFIle já modificado para montagem do arquivo dinamico
     * @param int $id_empresa - id da empresa para ser utilizado no nome do arquivo.
     * 
     * @version 3.1 - modificação de verificação se sistema casa soft { abre e insee o conteudo } else { deleta o arquivo existente e cria outro limpo };
     * @author Carlos
     */
    private function _montar_xml( $imoveis, $id_empresa, $tag )
    {
        
        $arquivo_nome = $this->pasta_modificados.$id_empresa.'.xml';
        $conteudo = '';
        foreach ( $imoveis as $imovel )
        {
            $conteudo .= '<imovel>';
            foreach( $imovel as $chave => $valor )
            {
                //var_dump($chave,$valor);
                //echo $chave.'<br>';
                $conteudo .= '<'.$chave.'>';
                if ( is_array($valor) )
                {
                    foreach ( $valor as $chave_a => $valor_a )
                    {
                        if ( is_array($valor_a) )
                        {
                            if ( $chave_a == 'foto' )
                            {
                                foreach ( $valor_a as $chave_b => $valor_b )
                                {
                                    $conteudo .= '<'.$chave_a.'>';
                                    foreach( $valor_b as $chave_c => $valor_c )
                                    {
                                        $conteudo .= '<'.$chave_c.'>';
                                        $conteudo .= htmlspecialchars($valor_c,ENT_XML1, 'UTF-8');
                                        $conteudo .= '</'.$chave_c.'>'.PHP_EOL;
                                    }
                                    $conteudo .= '</'.$chave_a.'>'.PHP_EOL;
                                }
                            }
                            else
                            {
                                $conteudo .= '<'.$chave_a.'>';
                                $conteudo .= htmlspecialchars($valor_a,ENT_XML1, 'UTF-8');
                                $conteudo .= '</'.$chave_a.'>'.PHP_EOL;
                            }
                        }
                        else
                        {
                        	$conteudo .= '<'.$chave_a.'>';
                            $conteudo .= htmlspecialchars($valor_a,ENT_XML1, 'UTF-8');
                        	$conteudo .= '</'.$chave_a.'>'.PHP_EOL;
                        }
                        
                        
                    }
                }
                else
                {
                    $convertido = $this->string_convert($valor);
                    if ( $convertido )
                    {
                        $conteudo .= htmlspecialchars($convertido,ENT_XML1, 'UTF-8');
                    }
                    else
                    {
                        $conteudo .= htmlspecialchars((string)$valor,ENT_XML1, 'UTF-8');
                    }
                }
                $conteudo .= '</'.$chave.'>'.PHP_EOL;
            }
            $conteudo .= '</imovel>';
        }
        //var_dump($conteudo);die();
        if ( isset($conteudo) && ! empty($conteudo) && $conteudo != '<imovel></imovel>' )
        {
	        if (file_exists( $arquivo_nome ) )
	        {
	            if ( $tag == 'casasoft' )
	            {
	                $atual = file_get_contents($arquivo_nome);
	                $valor_anterior = str_replace('</pow>', '', $atual);
	                $arquivo = fopen( $arquivo_nome, 'w+' );
	                fwrite($arquivo, $valor_anterior);
	            }
	            else 
	            {
	                unlink($arquivo_nome);
	                $arquivo = fopen( $arquivo_nome, 'x' );
	                fwrite($arquivo, '<?xml version="1.0" encoding="UTF-8"?><pow>' );
	            }
	        }
	        else
	        {
	            $arquivo = fopen( $arquivo_nome, 'x' );
	            fwrite($arquivo, '<?xml version="1.0" encoding="UTF-8"?><pow>' );
	        }
	        fwrite($arquivo, $conteudo);
	        fwrite($arquivo, '</pow>' );
	        fclose($arquivo);
        }
        else
        {
        	$mensagem_erro = '<table width="100%">';
        	$mensagem_erro .= '<tr><th colspan="3"><h1>Relatório de Erros do sistema</h1></th></tr>';
        	$mensagem_erro .= '<tr><td>Empresa:</td><td colspan="2">'.$id_empresa.'</td></tr>';
        	$mensagem_erro .= '<tr><td colspan="2">Não foi possivel gerar o arquivo xml padrão.</td></tr>';
        	$mensagem_erro .= '</table>';
        	$array_envio_erro = array(
        			'assunto'       => 'Atualização de erros integração',
        			'mensagem'      => $mensagem_erro,
        			'to'            => 'comercial@pow.com.br, vendas01@pow.com.br',
        			'bcc'           => 'programacao@pow.com.br',
        	);
        	$enviado = $this->envio($array_envio_erro);
        }
    }
    
    private function string_convert( $valor )
    {
        $encoding = mb_detect_encoding($valor.'x','UTF-8, ISO-8859-1');
        //var_dump($encoding);
        //var_dump(utf8_encode((string)$valor));
        $retorno = iconv("UTF-8", $encoding."//TRANSLIT", (string)$valor);//mb_convert_encoding($valor, "UTF-8", "auto");
        //var_dump($valor, $retorno);
        //echo $retorno;
        //echo $valor;
        return $retorno;
    }
    
    private function trata_dados_iniciais_dominio_imovel( $valor )
    {
        $retorno = array();
        $retorno['tipoimovel'] = trim($valor->Tipo);
        $retorno['nome_imovel'] = trim($valor->Nome);
        $retorno['temporada'] = isset($valor->Temporada) && ! empty($valor->Temporada) ? 1 : 0;
        $retorno['venda'] = isset($valor->Venda) && ! empty($valor->Venda) ? 1 : 0;
        $retorno['locacao'] = isset($valor->Locacao) && ! empty($valor->Locacao) ? 1 : 0;
        $retorno['id'] = $valor->CodigoInterno;
        $retorno['ref'] = trim($valor->Codigo);
        return $retorno;
    }
    
    private function trata_dados_localizacao_dominio_imovel( $valor )
    {
        $retorno = array();
        $retorno['estado'] = $valor->UF;
        $retorno['cidade'] = $valor->Cidade;
        $retorno['bairro'] = $valor->Bairro;
        $retorno['endereco'] = $valor->Endereco.', '.$valor->Numero;
        $retorno['cep'] = $valor->Cep;
        $retorno['latitude'] = $valor->Latitude;
        $retorno['longitude'] = $valor->Longitude;
        return $retorno;
    }
    
    private function trata_dados_valores_dominio_imovel( $valor )
    {
        $retorno = array();
        $retorno['preco_venda'] = $valor->ValorMaximoDeVenda;
        $retorno['preco_locacao'] = $valor->ValorMaximoDeLocacao;
        $retorno['preco_temporada'] = 0;
        $retorno['condominio_valor'] = $valor->Condominio;
        return $retorno;
    }
    
    private function trata_dados_dados_imovel_dominio_imovel( $valor )
    {
        $retorno = array();
        $retorno['descricao'] = $valor->DescricaoParaOSiteEPortais;
        $retorno['area'] = $valor->AreaTotalConstruida;
        $retorno['area_util'] = $valor->AreaUtil;
        $retorno['quartos'] = $valor->BanheirosSociais;
        $retorno['banheiro'] = $valor->Dormitorios;
        
        $retorno['area_terreno'] = $valor->AreaDoTerreno;
        $retorno['suites'] = $valor->Suites;
        $retorno['vagas'] = $valor->VagasDeGaragem;
        
        return $retorno;
    }
    
    private function trata_details_vivareal( $valor )
    {
        $retorno = array();
        $retorno['tipoimovel'] = $valor->PropertyType;
        $retorno['descricao'] = $valor->Description;
        $retorno['preco_venda'] = $valor->ListPrice;
        $retorno['preco_locacao'] = $valor->RentalPrice;
        $retorno['condominio_valor'] = $valor->PropertyAdministrationFee;
        $retorno['area'] = $valor->LotArea;
        $retorno['area_util'] = $valor->LivingArea;
        $retorno['quartos'] = $valor->Bedrooms;
        $retorno['banheiro'] = $valor->Bathrooms;
        $retorno['suites'] = $valor->Suites;
        $retorno['vagas'] = $valor->Garage;
        return $retorno;
    }
    
    private function trata_location_vivareal( $valor )
    {
        $retorno = array();
        if ( isset($valor->Address['publiclyVisible']) && (string)$valor->Address['publiclyVisible'][0] == 'false' )
        {
            $retorno['endereco'] = '';
            $retorno['cep'] = '';
        }
        else
        {
            $retorno['endereco'] = $valor->Address;
            $retorno['cep'] = $valor->PostalCode;
        }
        $retorno['estado'] = $valor->State;
        $retorno['cidade'] = $valor->City;
        $retorno['bairro'] = $valor->Neighborhood;
        $retorno['latitude'] = $valor->Latitude;
        $retorno['longitude'] = $valor->Longitude;
        //var_dump($retorno);die();
        return $retorno;
    }
    
    private function verifica_nao_informado( $valor )
    {
        $valor_ = $this->string_convert($valor);
        $valor_ = strtolower($valor_);
        if ( $valor_ == 'nao informado' || $valor_ == 'não informado' || $valor_ == 'endereço não informado' )
        {
            $retorno = '';
        }
        else
        {
            $retorno = $this->string_convert($valor);
        }
        return $retorno;
    }
    
    private function verifica_venda_quickfast( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'venda' => 1, 'preco_venda' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'venda' => 0, 'preco_venda' => 0);
        }
        return $retorno;
    }
    
    private function verifica_locacao_quickfast( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'locacao' => 1, 'preco_locacao' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'locacao' => 0, 'preco_locacao' => 0);
        }
        return $retorno;
    }
    
    private function verifica_venda_gaia( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'venda' => 1, 'preco_venda' => $valor );
        }
        else
        {
            $retorno = array( 'venda' => 0, 'preco_venda' => 0);
        }
        return $retorno;
    }
    
    private function verifica_locacao_gaia( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'locacao' => 1, 'preco_locacao' => $valor );
        }
        else
        {
            $retorno = array( 'locacao' => 0, 'preco_locacao' => 0);
        }
        return $retorno;
    }
    
    private function verifica_temporada_gaia( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'temporada' => 1, 'preco_temporada' => $valor );
        }
        else
        {
            $retorno = array( 'temporada' => 0, 'preco_temporada' => 0);
        }
        return $retorno;
    }
    
    private function verifica_venda_carlosfranco( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'venda' => 1, 'preco_venda' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'venda' => 0, 'preco_venda' => 0);
        }
        return $retorno;
    }
    
    private function verifica_locacao_carlosfranco( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'locacao' => 1, 'preco_locacao' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'locacao' => 0, 'preco_locacao' => 0);
        }
        return $retorno;
    }
    
    private function verifica_temporada_carlosfranco( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'temporada' => 1, 'preco_temporada' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'temporada' => 0, 'preco_temporada' => 0);
        }
        return $retorno;
    }
    
    private function verifica_venda_webgestor( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'venda' => 1, 'preco_venda' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'venda' => 0, 'preco_venda' => 0);
        }
        return $retorno;
    }
    
    private function verifica_locacao_webgestor( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'locacao' => 1, 'preco_locacao' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'locacao' => 0, 'preco_locacao' => 0);
        }
        return $retorno;
    }
    
    private function verifica_temporada_webgestor( $valor = NULL )
    {
        if ( isset($valor) && ! empty($valor) && $valor > 0 )
        {
            $retorno = array( 'temporada' => 1, 'preco_temporada' => $this->numero_to_decimal($valor) );
        }
        else
        {
            $retorno = array( 'temporada' => 0, 'preco_temporada' => 0);
        }
        return $retorno;
    }
    
    private function verifica_ocultar_endereco( $valor, $complemento )
    {
        if ( $valor == 0 )
        {
            foreach ( $complemento as $chave => $v )
            {
                $retorno[$chave] = $this->string_convert($v);
            }
        }
        else
        {
            foreach( $complemento as $chave => $v )
            {
                $retorno[$chave] = '';
            }
        }
        return $retorno;
    }
    
    
    private function verifica_sim_nao ( $valor )
    {
        $valor = strtolower($valor);
        if ( $valor == 'sim' )
        {
            $retorno = 1;
        }
        else
        {
            $retorno = 0;
        }
        return $retorno;
    }
    
    private function verifica_sim_nao_numeral ( $valor )
    {
        $valor = strtolower($valor);
        $valor = str_replace(array('vaga','vagas'), '', $valor);
        $valor = trim($valor);
        if ( $valor == 'sim' )
        {
            $retorno = 1;
        }
        elseif( $valor == 'não' )
        {
            $retorno = 0;
        }
        else
        {
            $retorno = $valor;
        }
        return $retorno;
    }
    
    private function numero_to_decimal ( $valor )
    {
        $valor = str_replace(array('.'), '', $valor);
        $retorno = str_replace(',', '.', $valor);
        return $retorno;
        
    }
    
    private function retorna_endereco ( $valor, $itens )
    {
        $visivel = ( $valor->visivel == 'S' ) ? TRUE : FALSE;
        if ( $visivel )
        {
            $retorno['endereco'] = (isset($valor->tipo) ? $valor->tipo.' ' : '').(isset($valor->logradouro) ? $valor->logradouro : '');
            $retorno['numero'] = (isset($valor->numero) ? $valor->numero : '');
            $retorno['complemento'] = (isset($valor->complemento) ? $valor->complemento : '');
        }
        else
        {
            $retorno = array('endereco'=>'','numero'=>'','complemento'=>'');
        }
        return $retorno;
    }
    
    private function set_fotos( $fotos, $itens )
    {
        $retorno = array();
        foreach( $itens as $chave => $campos )
        {
            $sequencia = 0;
            if ( isset($fotos->$chave) )
            {
                foreach( $fotos->$chave as $foto )
                {
                    foreach( $campos as $campo => $valor )
                    {
                        $retorno[strtolower($chave)][$sequencia][$valor['equivalente']] = $foto->$campo;
                    }
                    $sequencia++;
                }
            }
        }
        return $retorno;
    }
    
    private function set_fotos_vivareal( $fotos, $itens )
    {
        $retorno = array();
        foreach( $itens as $chave => $campos )
        {
            $sequencia = 0;
            if ( isset($fotos->$chave) )
            {
                foreach( $fotos->$chave as $foto )
                {
                    
                    //echo 'foto: '.$foto;
                    if ( strstr( $foto, 'youtube') )
                    {
                        
                    }
                    else
                    {
                        $retorno['foto'][$sequencia][$campos['equivalente']] = $foto;
                        $sequencia++;
                    }
                }
            }
        }
        return $retorno;
    }
    
    
    private function set_video_vivareal( $fotos, $itens )
    {
        //var_dump($fotos, $itens); die();
        $retorno = array();
        if ( isset($fotos->$chave) )
        {
            foreach( $fotos->$chave as $foto )
            {

                //echo 'foto: '.$foto;
                if ( strstr( $foto, 'youtube') )
                {
                    $retorno['video'] = $foto;

                }
            }
        }
        return $retorno;
    }
    
    private function set_fotos_gaia( $fotos, $itens )
    {
        $retorno = array();
        foreach( $itens as $chave => $campos )
        {
            $sequencia = 0;
            if ( isset($fotos->$chave) )
            {
                foreach( $fotos->$chave as $foto )
                {
                    foreach( $campos as $campo => $valor )
                    {
                        $retorno[strtolower($chave)][$sequencia][$valor['equivalente']] = $foto->$campo;
                    }
                    $sequencia++;
                }
            }
            
        }
        return $retorno;
    }
    
    private function set_fotos_casasoft( $fotos, $itens )
    {
        $fotos = $fotos->fotos;
        $retorno = array();
        foreach( $itens as $chave => $campos )
        {
            //var_dump($chave,$campos);
            $sequencia = 0;
            foreach( $fotos->$chave as $foto )
            {
                foreach( $campos as $campo => $valor )
                {
                    $retorno[strtolower($chave)][$sequencia][$valor['equivalente']] = $foto->$campo;
                }
                $sequencia++;
            }
        }
        return $retorno;
    }
    
    private function set_fotos_minhaprimeira_casa( $valor, $itens )
    {
        $fotos = ( isset($valor->foto) ? $valor->foto : FALSE );
        if ( $valor->foto )
        {
            $sequencia = 0;
            foreach( $fotos as $chave => $item )
            {
                $retorno['foto'][$sequencia] = array('foto_url' => $item);
                $sequencia++;
            }
        }
        else
        {
            $retorno = array(  );
        }
        return $retorno;
    }
    
    private function set_fotos_dominio_imovel( $fotos, $itens )
    {
        $retorno = array();
        foreach( $itens as $chave => $campos )
        {
            $sequencia = 0;
            if ( isset($fotos->$chave) )
            {
                //var_dump($fotos->$chave, $campos);die();
                foreach( $fotos->$chave as $foto )
                {
                    foreach( $campos as $chave_campo => $campo )
                    {
                        $retorno['foto'][$sequencia][$campo['equivalente']] = $foto->$chave_campo;
                    }
                    $sequencia++;
                }
            }
        }
        return $retorno;
    }
    
    private function concatena_campo( $valor, $valor_a )
    {
        if ( $valor_a )
        {
            $retorno = $this->string_convert((string)$valor_a).' '.$this->string_convert((string)$valor);
        }
        else
        {
            $retorno = $this->string_convert((string)$valor_a);
        }
        return $retorno;
    }
    
    private function valor_tira_especiais ( $valor )
    {
        $retorno = $this->_set_valor_tira_especiais($valor);
        return $retorno;
    }
    
    private function valor_cento( $valor )
    {
        $retorno = ( $valor * 100 );
        return $retorno;
    }
    
    private function valor_por_tipo_negocio ( $valor, $complemento = array() )
    {
        $retorno = array();
        if ( $complemento['venda'] )
        {
            $retorno['preco_venda'] = $this->_set_valor_tira_especiais($valor);
            $retorno['preco_locacao'] = 0;
            $retorno['preco_temporada'] = 0;
        }
        elseif ( $complemento['locacao'] )
        {
            $retorno['preco_venda'] = 0;
            $retorno['preco_locacao'] = $this->_set_valor_tira_especiais($valor);
            $retorno['preco_temporada'] = 0;
        }
        elseif ( $complemento['temporada'] )
        {
            $retorno['preco_venda'] = 0;
            $retorno['preco_locacao'] = 0;
            $retorno['preco_temporada'] = $this->_set_valor_tira_especiais($valor);
        }
        else
        {
            $retorno['preco_venda'] = 0;
            $retorno['preco_locacao'] = 0;
            $retorno['preco_temporada'] = 0;
        }
        return $retorno;
     
    }
    
    private function _set_valor_tira_especiais( $valor )
    {
        $valor = str_replace(array('R','$','','m','²','a','p','l','h','e'), '', (string)$valor);
        return trim($valor);
    }
    
    private function verifica_tipo_string( $valor = '' )
    {
        $valor = $this->string_convert($valor);
        if ( ! empty($valor) )
        {
            $valor = strtolower($valor);
            switch( $valor )
            {
                case 'venda':
                        $retorno = array( 'venda' => 1, 'locacao' => 0, 'temporada' => 0 );
                    break;
                case 'locacao':
                case 'locação':
                case 'aluguel':
                case 'temporada':
                        $retorno = array( 'venda' => 0, 'locacao' => 1, 'temporada' => 0 );
                    break;
                case 'locacao_temporada':
                        $retorno = array( 'venda' => 0, 'locacao' => 0, 'temporada' => 1 );
                    break;
                case 'v':
                        $retorno = array( 'venda' => 1, 'locacao' => 0, 'temporada' => 0 );
                    break;
                case 'l':
                        $retorno = array( 'venda' => 0, 'locacao' => 1, 'temporada' => 0 );
                    break;
                case 'r':
                        $retorno = array( 'comercial' => 0, 'residencial' => 1);
                    break;
                case 'c':
                        $retorno = array( 'comercial' => 1, 'residencial' => 0);
                    break;
                case 'e':
                        $retorno = array( 'comercial' => 1, 'residencial' => 1);
                    break;
                case 'Residencial':
                        $retorno = array( 'comercial' => 0, 'residencial' => 1);
                    break;
                case 'Comercial':
                        $retorno = array( 'comercial' => 1, 'residencial' => 0);
                    break;
                default:
                        $retorno = array( 'venda' => 0, 'locacao' => 0, 'temporada' => 0, 'comercial' => 0, 'residencial' => 0);
                    break;
            }
        }
        else
        {
            $retorno = array( 'venda' => 0, 'locacao' => 0, 'temporada' => 0, 'comercial' => 0, 'residencial' => 0 );
        }
        return $retorno;
    }
    
    private function verifica_tipo_string_vivareal( $valor = '' )
    {
        $valor = $this->string_convert($valor);
        if ( ! empty($valor) )
        {
            $valor = strtolower($valor);
            switch( $valor )
            {
                case 'For Sale':
                case 'for sale':
                        $retorno = array( 'venda' => 1, 'locacao' => 0, 'temporada' => 0 );
                    break;
                case 'For Rent':
                case 'for rent':
                        $retorno = array( 'venda' => 0, 'locacao' => 1, 'temporada' => 0 );
                    break;
                case 'Sale/Rent':
                case 'For Sale/Rent':
                        $retorno = array( 'venda' => 1, 'locacao' => 1, 'temporada' => 0 );
                    break;
                default:
                        $retorno = array( 'venda' => 0, 'locacao' => 0, 'temporada' => 0, 'comercial' => 0, 'residencial' => 0);
                    break;
            }
        }
        else
        {
            $retorno = array( 'venda' => 0, 'locacao' => 0, 'temporada' => 0, 'comercial' => 0, 'residencial' => 0 );
        }
        return $retorno;
    }
    
    private function trata_caracteristicas_imovelpro( $valor )
    {
        $retorno['tipoimovel'] = $this->string_convert($valor->tipo_imovel);
        $retorno['mobiliado'] = $this->verifica_sim_nao($valor->mobiliado);
        $retorno['condominio'] = $this->verifica_sim_nao($valor->em_condominio);
        return $retorno;
    }
    
    private function trata_ambientes_imovelpro ( $valor )
    {
        //'quartos','suites','banheiro','vagas'
        $retorno['quartos'] = $this->string_convert($valor->dormitorios);
        $retorno['suites'] = $this->string_convert($valor->sendo_suites);
        $retorno['banheiro'] = $this->string_convert($valor->banheiros);
        $retorno['vagas'] = $this->string_convert($valor->garagens);
        return $retorno;
    }
    
    private function trata_endereco_imovelpro ( $valor )
    {
        $retorno['cep'] = $this->string_convert($valor->cep);
        $retorno['endereco'] = $this->string_convert($valor->logradouro);
        $retorno['numero'] = $this->string_convert($valor->numero);
        $retorno['complemento'] = ( ! empty($valor->numero_apto_ou_sala) ? 'ap: '.$this->string_convert($valor->numero_apto_ou_sala).' ' : '' ).
                ( ! empty($valor->bloco) ? 'bloco: '.$this->string_convert($valor->bloco).' ' : '' ). 
                ( ! empty($valor->andar) ? 'andar: '.$this->string_convert($valor->andar).' ' : '' ). 
                ( ! empty($valor->complemento) ? 'complemento: '.$this->string_convert($valor->complemento).' ' : '' ). 
                ( ! empty($valor->quadra) ? 'quadra: '.$this->string_convert($valor->quadra).' ' : '' ). 
                ( ! empty($valor->lote) ? 'lote: '.$this->string_convert($valor->lote).' ' : '' ). 
        $retorno['bairro'] = $this->string_convert($valor->bairro);
        $retorno['cidade'] = $this->string_convert($valor->cidade);
        $retorno['estado'] = $this->string_convert($valor->estado);
        return $retorno;
    }
    
    private function trata_descricao_imovelpro ( $valor )
    {
        $retorno['nome_imovel'] = $this->string_convert($valor->mini_descricao);
        $retorno['descricao'] = $this->string_convert($valor->descricao_externa);
        return $retorno;
    }
    
    private function trata_medidas_imovelpro ( $valor )
    {
        $retorno['area'] = $this->numero_to_decimal($valor->area_total);
        $retorno['area_util'] = $this->numero_to_decimal($valor->area_util);
        return $retorno;
    }
    
    private function trata_negociacao_imovelpro ( $valor )
    {
        if ( isset($valor->venda) )
        {
            $retorno['venda'] = isset($valor->venda->imovel_a_venda) ? $this->verifica_sim_nao($valor->venda->imovel_a_venda) : 1;
            $retorno['preco_venda'] = $this->numero_to_decimal($valor->venda->preco);
        }
        else
        {
            $retorno['venda'] = 0;
            $retorno['preco_venda'] = '';
        }
        if ( isset($valor->locacao) )
        {
            $retorno['locacao'] = isset($valor->locacao->imovel_para_locacao) ? $this->verifica_sim_nao($valor->locacao->imovel_para_locacao) : 1;
            $retorno['preco_locacao'] = $this->numero_to_decimal($valor->locacao->preco);
        }
        else
        {
            $retorno['locacao'] = 0;
            $retorno['preco_locacao'] = '';
        }
        if ( isset($valor->locacao_temporada) )
        {
            $retorno['temporada'] = isset($valor->locacao_temporada->imovel_para_locacao_temporada) ? $this->verifica_sim_nao($valor->locacao_temporada->imovel_para_locacao_temporada) : 1;
            $retorno['preco_temporada'] = $this->numero_to_decimal($valor->locacao_temporada->preco);
        }
        else
        {
            $retorno['temporada'] = 0;
            $retorno['preco_temporada'] = '';
        }
        return $retorno;
    }
    
    private function trata_google_imovelpro ( $valor )
    {
        $retorno['latitude'] = $this->string_convert($valor->latitude);
        $retorno['longitude'] = $this->string_convert($valor->longitude);
        return $retorno;
    }
    
    private function _verifica_pasta( $pasta = NULL )
    {
        if ( isset($pasta) )
        {
            $origem = substr( $pasta,0,-1);
            if ( ! is_dir($origem) )
            {
                $pasta_a = explode('/',$origem);
                array_pop($pasta_a);
                $endereco_a = implode('/',$pasta_a);
                if ( ! is_dir($endereco_a) )
                {
                    array_pop($pasta_a);
                    $endereco_b = implode('/',$pasta_a);
                    if ( ! is_dir($endereco_b) )
                    {
                        array_pop($pasta_a);
                        $endereco_c = implode('/', $pasta_a);
                        if ( ! is_dir($endereco_c) )
                        {
                            array_pop($pasta_a);
                            $endereco_d = implode('/',$pasta_a);
                            if ( ! is_dir($endereco_d) )
                            {
                                mkdir( $endereco_d, 0777, TRUE );
                                mkdir( $endereco_c, 0777, TRUE );
                                mkdir( $endereco_b, 0777, TRUE );
                                mkdir( $endereco_a, 0777, TRUE );
                                mkdir( $origem, 0777, TRUE );
                                
                            }
                            else
                            {
                                mkdir( $endereco_c, 0777, TRUE );
                                mkdir( $endereco_b, 0777, TRUE );
                                mkdir( $endereco_a, 0777, TRUE );
                                mkdir( $origem, 0777, TRUE );
                            }
                        }
                        else
                        {
                            mkdir( $endereco_b, 0777, TRUE );
                            mkdir( $endereco_a, 0777, TRUE );
                            mkdir( $origem, 0777, TRUE );
                            
                        }    
                    }
                    else
                    {
                        mkdir( $endereco_a, 0777, TRUE );
                        mkdir( $origem, 0777, TRUE );
                    }
                }
                else
                {
                    mkdir( $origem, 0777, TRUE );
                }
            }
        }
    }
    
    public function get_arquivo_por_empresa( $empresas )
    {
        $retorno = $this->get_por_empresas($empresas, TRUE);
        return $retorno;
    }
    
    /**
     * 
     */
    public function get_por_empresas ( $empresas, $pasta_download = FALSE )
    {
        if ( isset( $empresas ) && count( $empresas ) > 0 )
        {
            foreach ( $empresas as $chave => $empresa )
            {
                
                $data[$empresa->id]['arquivo'] = ( $pasta_download ? $this->pasta_download : $this->pasta_origem ) .$empresa->sistema.'_'.$empresa->id.'.xml';
                $data[$empresa->id]['erro'] = array();
                //var_dump($empresa, $data);die();
                switch ( $empresa->sistema )
                {
                    /**
                     * imobex
                     */
                    case 1:
                    case 12:
                    case 13:
                    case 14:
                        $array_ftp = (array)$this->conecta->imobex;
                        $this->CI->ftp->connect($array_ftp);
                        $retorno = $this->CI->ftp->download($empresa->chave.'/imobex.xml',$data[$empresa->id]['arquivo'],'auto');
                        $this->CI->ftp->close();
                        if ( $retorno )
                        {
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'Arquivo '.$empresa->chave.', de '.$empresa->id.' - '.$empresa->nome_fantasia.', não pode ser criado, favor verificar.';
                        }
                        break;
                    /**
                     * CasaSoft
                     */
                    case 3:
                        
                        $arquivo_v = 'casasoft'.$empresa->chave.'V9.xml';
                        $arquivo_l = 'casasoft'.($empresa->chave == 14001 ? 14000 : $empresa->chave).'L9.xml';
                        $array_ftp = (array)$this->conecta->casasoft;
                        $this->CI->ftp->connect($array_ftp);
                        $tem_arquivo_v = $this->CI->ftp->list_files($arquivo_v);
                        $nome_arquivo = substr( $data[$empresa->id]['arquivo'],0,-4);
                        //var_dump($data[$empresa->id]['arquivo'], $arquivo_v, $nome_arquivo.'_V.xml');
                        if ( $tem_arquivo_v )
                        {
                            $retorno_v = $this->CI->ftp->download($arquivo_v, $nome_arquivo.'_V.xml','auto');
                        }
                        //var_dump($tem_arquivo_v, $retorno_v);die();
                        $tem_arquivo_l = $this->CI->ftp->list_files($arquivo_l);
                        if ( $tem_arquivo_l )
                        {
                            $retorno_l = $this->CI->ftp->download($arquivo_l, $nome_arquivo.'_L.xml','auto');
                        }
                        $this->CI->ftp->close();
                        if ( ( isset($retorno_v) && $retorno_v ) || ( isset($retorno_l) && $retorno_l ) )
                        {
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'Arquivo '.$empresa->chave.', de '.$empresa->id.' - '.$empresa->nome_fantasia.', não pode ser criado, favor verificar.';
                        }
                        break;
                    /**
                     * I-Value Gaia
                     */
                    case 8:
                    case 15:
                    case 16:
                    case 17:
                        $contexto_criador = array('http' => array('header' => 'Content-type: application/xml;charset=UTF-8'));
                        $contexto = stream_context_create($contexto_criador);
                        $xml = $this->curl_executavel(str_replace('www.','app.',$empresa->chave),'ISO-8859-1');
                        if( ! empty( $xml ) )
                        {
                            $xml = str_replace('&', '&amp;', $xml);
                            //var_dump($xml);die();
                            $conteudo = trim(utf8_decode($xml));
                            $conteudo = substr($conteudo,1);
                            $arquivo = fopen( $data[$empresa->id]['arquivo'], 'x', 1, $contexto );
                            fwrite($arquivo, $conteudo );
                            fclose($arquivo);
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'O arquivo: '.$empresa->chave.' , de '.$empresa->id.' - '.$empresa->nome_fantasia.', Não esta disponível ou não tem autorização para ser lido';
                        }
                        break;
                    /**
                     * imobex-importa
                     */
                    case 21:
                        $xml = $this->curl_executavel($empresa->chave);
                        if ( $xml )
                        {
                            $arquivo = fopen( $data[$empresa->id]['arquivo'], 'x' );
                            fwrite($arquivo, '<?xml version="1.0" encoding="UTF-8"?>');
                            fwrite($arquivo, $xml );
                            fwrite($arquivo, '' );
                            fclose($arquivo);
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }   
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'Arquivo: '.$empresa->chave.' de '.$empresa->id.' '.$empresa->nome_fantasia.' não esta disponível ou não tem autorização para ser lido no momento.';
                        }
                        break;
                    /**
                     * ImovelPro
                     */
                    case 4:
                        $contexto_criador = array('http' => array('method' => 'GET', 'header' => 'User-agent: POW Internet / XML 1.0; +http://www.powinternet.com.br \r\n'));
                        $contexto = stream_context_create($contexto_criador);
                        $xml = file_get_contents($empresa->chave,NULL,$contexto);
                        if ( $xml )
                        {
                            $arquivo = fopen( $data[$empresa->id]['arquivo'], 'x' );
                            fwrite($arquivo, $xml );
                            fclose($arquivo);
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }   
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'Arquivo: '.$empresa->chave.' de '.$empresa->id.' '.$empresa->nome_fantasia.' não esta disponível ou não tem autorização para ser lido no momento.';
                        }
                        break;
                    /**
                     * Vista
                     */
                    case 2:
                        /*
                        $endereco = 'http://cityflor-rest.vistahost.com.br/imoveis/listar?key=cd8f8cb7934e9099fec737a25cff7f11&showtotal=1';//.$empresa->chave;
                        $endereco .= '&pesquisa='.( json_encode(array('paginacao' => array( 'pagina' => 1, 'quantidade' => 50 ), 'fields' => $this->CI->xml_formatos->vista_json['lista'] ) ) );
                        
                        //$endereco = 'http://cityflor-rest.vistahost.com.br/imoveis/listarcampos?key=cd8f8cb7934e9099fec737a25cff7f11&showtotal=1';//.$empresa->chave;
                        //var_dump($endereco);
                        //die();
                        $json = $this->curl_executavel($endereco, 'UTF-8', TRUE);
                        var_dump(json_decode($json));
                        die();

                        $data = array();
                         * 
                         */
                        //break;
                    /**
                     * POW
                     */
                    case 5:
                    /**
                     * MinhaPrimeiraCasa
                     */
                    case 6:
                    /**
                     * ImobiBrasil
                     */
                    case 7:
                    /**
                     * QuickFast
                     */
                    case 9:
                    case 11:
                    /**
                     * ViaNet
                     */
                    case 10:
                    /**
                     * ViaNet
                     */
                    case 18:
                    /**
                     * imobex-importa
                     */
                    case 21:
                    /**
                     * imonov
                     */
                    case 22:
                    /**
                     * carlosfranco
                     */
                    case 23:
                    default:
                        $xml = $this->curl_executavel($empresa->chave);
                        
			if ( isset($xml) && ! empty($xml)  )
                        {
                            $arquivo = fopen( $data[$empresa->id]['arquivo'], 'x' );
                            fwrite($arquivo, $xml );
                            fclose($arquivo);
                            $data[$empresa->id]['erro']['status'] = FALSE;
                            $data[$empresa->id]['erro']['data'] = date('Y-m-d H:i');
                        }   
                        else
                        {
                            $data[$empresa->id]['erro']['status'] = TRUE;
                            $data[$empresa->id]['erro']['mensagem'] = 'Arquivo: '.$empresa->chave.' de '.$empresa->id.' '.$empresa->nome_fantasia.' não esta disponível ou não tem autorização para ser lido no momento.';
                        }
                        break;
                }
                //salvar primeiro log
                if ( ! $pasta_download )
                {
                    $this->_processa_resposta_pega_arquivos($data[$empresa->id],$empresa->id );
                }
            }
        }
                
                
        return $data;
    }
    
    private function _processa_resposta_pega_arquivos ( $resposta, $id_empresa )
    {
        if ( $resposta['erro']['status'] )
        {
            $array_envio = array(
                                'assunto'       => 'Comunicado Rede de Portais Imobiliários.',
                                'mensagem'      => isset($resposta['erro']['mensagem']) ? $resposta['erro']['mensagem'].' Nosso Servidor para desbloqueio: '.$_SERVER['SERVER_ADDR'] : '',
                                'email'         => 'programacao@pow.com.br',
                                'bcc'           => 'comercial@pow.com.br',
                                'to'            => 'programacao@pow.com.br',
                                );
            $this->envio($array_envio);
        }
        else
        {
            $data = array( 'ultima_integracao' =>  $resposta['erro']['data'] );
            $filtro = 'empresas.id = '.$id_empresa;
            $this->CI->empresas_model->editar($data,$filtro);
        }
    }
    
    /**
     * 
     Severity: Warning

Message: curl_setopt(): CURLOPT_FOLLOWLOCATION cannot be activated when an open_basedir is set

Filename: libraries/MY_XML.php

Line Number: 1597
     */
    
    public function curl_executavel( $endereco, $header = 'UTF-8', $json = FALSE )
    {
        $ch = curl_init();
        if ( $json )
        {
            curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
        }
        else
        {
            $headers = array(
                "Content-type: application/xml;charset=".$header,
                "Accept: application/xml",
            ); 
            curl_setopt($ch, CURLOPT_ENCODING, $header);
        }
        
        //header ('charset='.$header);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
        curl_setopt($ch, CURLOPT_URL, $endereco);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);
        $retorno = curl_exec($ch);
        /*
         * 
         */
        $debug['erro'] = curl_errno( $ch );
        $debug['erromsg']  = curl_error( $ch );
        $debug['info']  = curl_getinfo( $ch );
        if ( isset($_GET['debug']) )
        {
            var_dump($debug);
        }

        curl_close($ch);
        
//	var_dump($debug);
	return $retorno;
    }
    
    
    /**
     * Verifica no banco de dados se ja esta cadastrado para revisão o $tipo
     * @param string $tipo o tipo a ser verificado
     * @return void 
     */
    private function _set_tipo_nao_existe( $tipo = NULL )
    {
        if ( isset($tipo) )
        {
            //imoveis_equi_cs_model
            $filtro = 'imoveis_equi_cs.tipo like "'.str_replace('"','',$tipo).'"';
            $tem = $this->CI->imoveis_equi_cs_model->get_total_itens($filtro);
            if ( $tem == 0  )
            {
                $array_insert = array('tipo' => (string)$tipo, 'pendente' => 1);
                $this->CI->imoveis_equi_cs_model->adicionar($array_insert);
            }
        }
    }
    
    
    
}
