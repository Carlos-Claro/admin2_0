<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de notícias
 * @version 1.0
 * @access public
 * @package noticias
 */
class Noticias extends MY_Controller 
{
        /**
         * cria um array de 17 posições para validar a pagina com todos os campos do formulario
         * @var array 
         */
	private $valida = array(
                                array( 'field'   => 'titulo',               'label'   => 'Titulo', 		'rules'   => 'required'),
                                array( 'field'   => 'texto',                'label'   => 'Texto', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_categoria',         'label'   => 'Categoria', 		'rules'   => 'trim'),
                                array( 'field'   => 'data',                 'label'   => 'Data', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_editoria',          'label'   => 'Editoria', 		'rules'   => 'trim'),
                                array( 'field'   => 'vitrine',              'label'   => 'Vitrine', 		'rules'   => 'trim'),
                                array( 'field'   => 'vitrine_canal',        'label'   => 'Vitrine Canal', 	'rules'   => 'trim'),
                                array( 'field'   => 'id_canal',             'label'   => 'Canal', 		'rules'   => 'trim'),
                                array( 'field'   => 'link_url',             'label'   => 'Link URL', 		'rules'   => 'trim'),
                                array( 'field'   => 'link_descricao',       'label'   => 'Link Descrição', 	'rules'   => 'trim'),
                                array( 'field'   => 'canal_noticias',       'label'   => 'Canal Noticias', 	'rules'   => 'trim'),
                                array( 'field'   => 'link_video',           'label'   => 'Link Video', 		'rules'   => 'trim'),
                                array( 'field'   => 'tipo_destaque',        'label'   => 'Tipo de Destaque', 	'rules'   => 'trim'),
                                array( 'field'   => 'legenda',              'label'   => 'Legenda', 		'rules'   => 'trim'),
                                array( 'field'   => 'id_cidade',            'label'   => 'Cidade', 		'rules'   => 'trim'),
                                array( 'field'   => 'newsletter',           'label'   => 'Newsletter', 		'rules'   => 'trim'),
                                array( 'field'   => 'tipo_area',            'label'   => 'Tipo Area', 		'rules'   => 'trim'),
                                );
        
        /**
         * cria um array de uma posição para definir o tamanho da imagem
         * @var array
         */
        private $tamanhos = array(
                                array('tipo' => 12, 'width' => '600', 'height' => 'auto', 'pasta' => 'images/noticias/[ano]/[mes]/', 'prefixo' => '', 'salva' => TRUE, 'ftp' => 'guiasjp'),
                                );
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models e librarys padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            parent::__construct();
            $this->load->model(array('noticias_model','categorias_model','cidades_model','canais_noticias_model','editorias_model','images_model','canais_model','noticias_tipo_area_model'));
            $this->load->library(array('anexo_lib'));
                
        }
	
        /**
         * seta a classe listar
         * @version 1.0
         * @access public
         */
	public function index()
	{
            $this->listar();
	}
        
        /**
         * cria a listagem de noticias carregando inicia filtros, itens, total itens,
         * inicia listagem, definir a URL da página, chama o noticias_model que vai 
         * chamar os dados do banco de dados, cria o lay-out de acordo com a listagem,
         * carrega arquivos js e css opcionais
         * @param string $coluna - coluna de ordenação do banco de dados
         * @param string $ordem - A ordem de ordenação do banco de dados - desc ou asc
         * @param string $off_set - pagina que esta visualizando 
         * @version 1.0
         * @access public
         */
	public function listar($coluna = 'data_unix', $ordem = 'DESC', $off_set = 0)
	{
            $i = 'noticias';
            $acesso = $this->set_setor_usuario($i);
            
                $off_set = ( (isset($_GET['per_page'])) ? $_GET['per_page'] : 0 );
                $classe = strtolower(__CLASS__);
                $function = strtolower(__FUNCTION__);
                $url = base_url().$classe.'/'.$function.'/'.$coluna.'/'.$ordem;
                $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
                $filtro = $this->_inicia_filtros( $url, $valores );
                $itens = $this->noticias_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set );
                $total = $this->noticias_model->get_total_itens( $filtro->get_filtro() );
                $get_url = $filtro->get_url();
                $url = $url.( (empty($get_url) ) ? '?' : $get_url );
                $data['paginacao'] = $this->init_paginacao($total, $url);
                $data['filtro'] = $filtro->get_html();
                $extras['url'] = base_url().$classe.'/'.$function.'/[col]/[ordem]/'.$filtro->get_url();
                $extras['col'] = $coluna;
                $extras['ordem'] = $ordem; 
                $extras['total_itens'] = $total; 
                $data['editavel'] = $acesso['edita'];
                $data['listagem'] = $this->_inicia_listagem( $itens, $extras );
                $this->layout
                            ->set_classe( $classe )
                            ->set_function( $function ) 
                            ->set_include('js/listar.js', TRUE)
                            ->set_include('js/noticias.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Noticias', 'noticias', 1)
                            ->set_usuario()
                            ->set_menu($this->get_menu($classe, $function))
                            ->view('listar',$data);	
                
	}
        
        /**
         * exportar uma lista notícia para um arquivo excel
         * @version 1.0
         * @access public
         */
	public function exportar()
	{
            $url = base_url().strtolower(__CLASS__).'/'.__FUNCTION__;
            $valores = ( isset($_GET['b']) ? $_GET['b'] : array() );
            $filtro = $this->_inicia_filtros( $url, $valores );
            $data = $this->noticias_model->get_itens( $filtro->get_filtro() );
            exporta_excel($data, __CLASS__.date('YmdHi'));
	}
        
        /**
         * Monta o formulario em branco e Adiciona os campos de valida no banco de dados com sua validações
         * @version 1.0
         * @access public
         * @return void  - redireciona ou monta o formulario
         */
	public function adicionar()
	{
            $i = 'noticias';
            $acesso = $this->set_setor_usuario($i);
            $this->form_validation->set_rules($this->valida); 
            if  ( $this->form_validation->run() )
            {
                    $data = $this->_post();
                    $images = isset($data['image']) ? $data['image'] : NULL;
                    unset($data['image']);
                    unset($data['files']);

                    if(isset($data['data']) && !empty($data['data']))
                    {
                        $exp_a = explode('-', $data['data']);
                        $exp_b = explode(' ', $exp_a[2]);
                        $data['data'] = strtotime($exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1]);
                    }
                    
                    $id = $this->noticias_model->adicionar($data);
                    $data_log = array('id_noticia' => $id, 'id_usuario' => $this->get_id_usuario(), 'acao' => 'adicionar');
                    $this->noticias_model->adicionar_has($data_log);
                    if ( isset($images) )
                    {
                        foreach( $images as $image )
                        {
                            $this->_gera_image($image['nome'], $id, $image['descricao'],0,$exp_b[0],$exp_a[1]);
                        } 
                    }
                    
                    //redirect(strtolower(__CLASS__).'/listar');
                    redirect(strtolower(__CLASS__).'/editar/'.$id.'/1');
            }
            else
            {
                    $function = strtolower(__FUNCTION__);
                    $class = strtolower(__CLASS__);
                    $data = $this->_inicia_select();
                    //$data['ckeditor_texto'] = $this->inicia_ckeditor('texto');
                    $data['action']  = base_url().$class.'/'.$function;
                    $data['data_url'] = base_url().$class.'/tratar_upload/fazer_upload';
                    $data['familia'] = $class;
                    $data['editavel'] = $acesso['edita'];
                    $data['tipo'] = 'Noticias Adicionar';	
                    $this->layout
                            ->set_function( $function )
                            ->set_include('js/noticias.js', TRUE)
                            ->set_include('js/noticias_upload.js', TRUE)
                            ->set_include('js/upload/funcs.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)  
                            //->set_include('css/jquery.fileupload.css', TRUE)
                            ->set_breadscrumbs('Painel', 'painel',0)
                            ->set_breadscrumbs('Noticias', 'noticias', 0)
                            ->set_breadscrumbs('Adicionar', 'noticias/adicionar', 1)
                            ->set_usuario($this->set_usuario())
                            ->set_menu($this->get_menu($class, $function))
                            ->view('add_noticias',$data);
            }   
		 
	}
        
        /**
         * monta o formulario ou edita as informações com base na $this->valida
         * @param string $codigo com o registro a ser editado
         * @param boolean $ok verifica se os dados foram salvos
         * @version 1.0
         * @access public
         */
	public function editar($codigo = NULL, $ok = FALSE)
	{
            $i = 'noticias';
            $acesso = $this->set_setor_usuario($i);
            $dados = $this->noticias_model->get_item($codigo);
            if ($dados)
            {
                    $this->form_validation->set_rules($this->valida);
                    if ($this->form_validation->run())
                    {
                        $data = $this->_post();

                        $images = isset($data['image']) ? $data['image'] : NULL;
                        unset($data['image']);
                        unset($data['files']);


                        if(isset($data['data']) && !empty($data['data']))
                        {
                            $exp_a = explode('-', $data['data']);
                            $exp_b = explode(' ', $exp_a[2]);
                            $data['data'] = strtotime($exp_b[0].'-'.$exp_a[1].'-'.$exp_a[0].' '.$exp_b[1]);
                            //var_dump($data['data'], $exp_a, $exp_b); die();
                        }

                        $id = $this->noticias_model->editar($data, array('noticias.id' => $codigo));
                        $data_log = array('id_noticia' => $codigo, 'id_usuario' => $this->get_id_usuario(), 'acao' => 'editar');
                        $this->noticias_model->adicionar_has($data_log);
                        if ( isset($images) )
                        {
                            foreach( $images as $image )
                            {
                                $this->_gera_image($image['nome'], $codigo, $image['descricao'],0,$exp_b[0],$exp_a[1]);
                            } 
                        }

                        redirect(strtolower(__CLASS__).'/editar/'.$codigo.'/1');
                    }
                    else
                    {
                            $function = strtolower(__FUNCTION__);
                            $class = strtolower(__CLASS__);
                            $data = $this->_inicia_select($codigo);
                            //$data['ckeditor_texto'] = $this->inicia_ckeditor('texto');
                            $data['action'] = base_url().$class.'/'.$function.'/'.$codigo;
                            //$data['action_anexo'] = base_url().'anexos/images/'.$class.'/'.$codigo;
                            //$data['action_novo'] = base_url().$class.'/adicionar/';
                            $data['familia'] = $class;
                            $data['data_url'] = base_url().$class.'/tratar_upload/fazer_upload';
                            $data['tipo'] = 'Noticias Editar';//$data = $this->_init_selects();
                            $data['item'] = $dados;
                            $data['mostra_id'] = TRUE;
                            $data['editavel'] = $acesso['edita'];
                            $data['erro'] = ($ok) ? array('class' => 'alert alert-success', 'texto' => 'Os dados foram salvos com sucesso') : '';
                            $this->layout
                                    ->set_function( $function )
                                    ->set_include('js/noticias.js', TRUE)
                                    ->set_include('js/noticias_upload.js', TRUE)
                                    ->set_include('js/upload/funcs.js', TRUE)
                                    ->set_include('css/estilo.css', TRUE)
                                    //->set_include('css/jquery.fileupload.css', TRUE)
                                    ->set_breadscrumbs('Painel', 'painel',0)
                                    ->set_breadscrumbs('Noticias', 'noticias', 0)
                                    ->set_breadscrumbs($dados->titulo, 'noticias', 1)
                                    //->set_breadscrumbs('Editar', 'noticias/editar/'.$codigo, 1)
                                    ->set_usuario($this->set_usuario())
                                    ->set_menu($this->get_menu($class, $function))
                                    ->view('add_noticias',$data);
                    }
            }
            else 
            {
                    redirect('noticias/listar');
            }
	}
        
        /**
         * Edita o campo vitrine marcando
         * @version 1.0
         * @access public
         * @author Carlos Claro
         */
        public function marcar_vitrine()
        {
            
            $selecionados = $this->input->post('selecionados');
            $quantidade = 0;
            foreach($selecionados as $key => $value)
            {
                $item = $this->noticias_model->editar(array('vitrine' => 1), array('noticias.id' => $value));
                if ( $item )
                {
                    $quantidade++;
                }
            }

            if ($quantidade>0)
            {
                    print $quantidade.' itens foram Marcados.';
            }
            else 
            {
                    print 'Nenhum item Marcados.';
            }
        }
        
        /**
         * edita o campo vitrine desmarcando
         * @version 1.0
         * @access public
         */
        public function desmarcar_vitrine()
        {
            $selecionados = $this->input->post('selecionados');
            $quantidade = 0;
            foreach($selecionados as $key => $value)
            {
                $item = $this->noticias_model->editar(array('vitrine' => 0), array('noticias.id' => $value));
                if ( $item )
                {
                    $quantidade++;
                }
            }

            if ($quantidade>0)
            {
                    print $quantidade.' itens foram DesMarcados.';
            }
            else 
            {
                    print 'Nenhum item DesMarcados.';
            }
            
        }
        
        /**
         * Edita vitrine canal marcando
         * @version 1.0
         * @access public
         */
        public function marcar_vitrine_canal()
        {
            $selecionados = $this->input->post('selecionados');
            $quantidade = 0;
            foreach($selecionados as $key => $value)
            {
                $item = $this->noticias_model->editar(array('vitrine_canal' => 1), array('noticias.id' => $value));
                if ( $item )
                {
                    $quantidade++;
                }
            }

            if ($quantidade>0)
            {
                    print $quantidade.' itens foram Marcados.';
            }
            else 
            {
                    print 'Nenhum item Marcados.';
            }
        }
        
        /**
         * Edita vitrine canal desmarcando
         * @version 1.0
         * @access public
         */
        public function desmarcar_vitrine_canal()
        {
            $selecionados = $this->input->post('selecionados');
            $quantidade = 0;
            foreach($selecionados as $key => $value)
            {
                $item = $this->noticias_model->editar(array('vitrine_canal' => 0), array('noticias.id' => $value));
                if ( $item )
                {
                    $quantidade++;
                }
            }

            if ($quantidade>0)
            {
                    print $quantidade.' itens foram DesMarcados.';
            }
            else 
            {
                    print 'Nenhum item DesMarcados.';
            }
            
        }
        
        
        /**
        * Gera imagens certas galeria de noticias
        * @param type $arquivo - arquivo com extensão
        * @param type $id_arquivo - id do pai
        * @param type $titulo - titulo inserido para a imagem
        * @param type $id_cadastro - id da pessoa que esta cadastrando. nao obrigatorio
        * @return $arq - nome do arquivo
        */
         private function _gera_image( $arquivo, $id_arquivo, $titulo, $id_cadastro = 0, $ano, $mes )
        {
           $endereco_image = URL_IMAGE . 'images/upload/'.$arquivo;
           $local_image = CWD_IMAGE . '/images/upload/'.$arquivo;
           $image_info = getimagesize($endereco_image);
           //var_dump($this->tamanhos);die();
           $replace_a = array('[ano]','[mes]');
           $replace_b = array($ano, $mes);
           switch($image_info["mime"])
           {
               case "image/jpeg":
                   foreach ( $this->tamanhos as $tamanho )
                   {
                        
                       $tamanho['image_local'] = $local_image;
                       $tamanho['pasta'] = str_replace($replace_a, $replace_b, $tamanho['pasta']);
                       var_dump($tamanho['pasta']);
                       $arq = $this->_set_jpg($image_info, $endereco_image, $arquivo, $id_arquivo,$tamanho, $titulo, $id_cadastro);
                   }
                   break;
               case "image/gif":
                   foreach ( $this->tamanhos as $tamanho )
                   {
                       $tamanho['image_local'] = $local_image;
                       $tamanho['pasta'] = str_replace($replace_a, $replace_b, $tamanho['pasta']);
                       $arq = $this->_set_gif($image_info, $endereco_image, $arquivo, $id_arquivo, $tamanho, $titulo, $id_cadastro);
                   }
                   break;
               case "image/png":
                   foreach ( $this->tamanhos as $tamanho )
                   {
                       $tamanho['image_local'] = $local_image;
                       $tamanho['pasta'] = str_replace($replace_a, $replace_b, $tamanho['pasta']);
                       $arq = $this->_set_png($image_info, $endereco_image, $arquivo, $id_arquivo, $tamanho, $titulo, $id_cadastro);
                   }
                   break;
               default:
                   $arq = FALSE;
                   break;
           }
           unlink($local_image);
           return $arq;
        }
       
       /**
        * Deletar imagem
        * verifica se imagem foi selecionada, se sim, verifica se imagem ainda existente,
        * se sim, verifica se imagem existe do banco de dados, se sim a imagem é excluida
        * @version 1.0
        * @access public
        */
        public function deleta_image()
        {
           $this->load->model('images_model');
           $post = $this->input->post(NULL, TRUE);
           $noticia = $this->noticias_model->get_item($post['noticia']);
           $arquivo = $this->images_model->get_item_por_id_pai( $post['id'] );
           if ( isset($arquivo) )
           {
               $replace_a = array('[ano]','[mes]');
               $replace_b = array($noticia->ano,$noticia->mes);
               $arquivo_ = LOCAL_IMAGE.'/'.str_replace($replace_a, $replace_b, $arquivo->pasta).$arquivo->arquivo;
               if ( file_exists($arquivo_) )
               {
                   $exc = $this->images_model->excluir_pai( 'image_pai.id = '.$post['id'] );
                   if ( $exc )
                   {
                       unlink($arquivo_);
                       $exc_a = $this->images_model->excluir_arquivo('image_arquivo.id = '.$arquivo->id_arquivo);
                       $retorno['erro'] = FALSE;
                       $retorno['id'] = $post['id'];
                   }
                   else
                   {
                       $retorno['erro'] = TRUE;
                       $retorno['mensagem'] = 'Esta não consta no banco de dados, tente reiniciar o navegador e tentar novamente.';
                   }
               }
               else
               {
                   $retorno['erro'] = TRUE;
                   $retorno['mensagem'] = 'Esta imagem não existe mais, tente reiniciar o navegador e tentar novamente.';
               }
           }
           else
           {
               $retorno['erro'] = TRUE;
               $retorno['mensagem'] = 'Nenhuma imagem selecionada, tente reiniciar esta pagina e tentar novamente.';
           }
           echo json_encode($retorno);
        }
       
       /**
        * Apaga varias imgens de uma só vez
        * @param string $id_noticia
        * @version 1.0
        * @access public
        */
        public function deleta_images( $id_noticia = NULL )
        {
           if ( isset($id_noticia) )
           {
               $noticia = $this->noticias_model->get_item( $id_noticia );
               $arquivos = $this->images_model->get_arquivo_por_tipo(12, $noticia->id );
               if ( isset($arquivos['itens']) && $arquivos['qtde'] > 0 )
               {
                   $this->load->model('images_model');
                   foreach( $arquivos['itens'] as $arquivo )
                   {
                       if ( isset($arquivo) )
                       {
                           $replace_a = array('[ano]','[mes]');
                           $replace_b = array($noticia->ano,$noticia->mes);
                           $arquivo_ = LOCAL_IMAGE.str_replace($replace_a, $replace_b, $arquivo->pasta).$arquivo->arquivo;
                           if ( file_exists($arquivo_) )
                           {
                               $exc = $this->images_model->excluir_pai( 'image_pai.id = '.$arquivo->id );
                               if ( $exc )
                               {
                                   unlink($arquivo);
                                   $exc_a = $this->images_model->exclui_arquivo('image_arquivo.id = '.$arquivo->id_image);
                               }
                           }
                       }
                   }
               }
           }
        }
       
	/**
         * Inicia todos os selecionaveis do view,
         * sendo eles: select do categorias_model, editorias_model, cidades_model,
         * canais_noticias_model, select_2014 do canais_model, select_tipo_image
         * do images_model e select do noticias_tipo_area_model
         * @param bool $id
         * @return array $retorno
         * @version 1.0
         * @access private
         */
        private function _inicia_select( $id = FALSE ) 
        {
            $retorno['categoria'] = $this->categorias_model->get_select();
            $retorno['editoria'] = $this->editorias_model->get_select();
            $retorno['cidade'] = $this->cidades_model->get_select();
            $retorno['canal'] = $this->canais_noticias_model->get_select();
            $retorno['canal_2014'] = $this->canais_model->get_select_2014();
            $retorno['imagens'] = $this->images_model->get_select_tipo_image(strtolower(__CLASS__));
            $retorno['tipo_area'] = $this->noticias_tipo_area_model->get_select();
            if ( $id )
            {
                $filtro_has = array('id_noticia = '.$id);
                $retorno['log'] = $this->noticias_model->get_itens_has($filtro_has);
            }
            return $retorno;
        }
        
        /**
         * Deleta uma noticia e suas conexoes
         * @param string $id
         * @version 1.0
         * @access public
         */
	public function remover($id = NULL)
	{
		$selecionados = $this->input->post('selecionados');
                
                foreach($selecionados as $key => $value)
                {
                    $this->deleta_images($value);
                }
                
		$quantidade = $this->noticias_model->excluir('noticias.id in ('.implode(',',$selecionados).')');
		if ($quantidade>0)
		{
			print $quantidade.' itens foram apagados.';
		}
		else 
		{
			print 'Nenhum item apagado.';
		}
	}
	
        /**
         * Cria uma lista de notcícias no estilo listagem normal,
         * chama os campos necessários para criar o cabeçalho e 
         * define id como chave
         * @param array $itens
         * @param array $extras
         * @param bool $exportar - se falso cabeçalho fica vazio
         * @return array $retorno - instancia com a classe listagem
         * @version 1.0
         * @access private
         */
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
                                                    (object)array( 'chave' => 'data', 'titulo' => 'Data ', 	'link' => str_replace(array('[col]','[ordem]'), array('data',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'data') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'data' ) ? 'ui-state-highlight'.( ($extras['col'] == 'data' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'categoria', 'titulo' => 'Categoria', 	'link' => str_replace(array('[col]','[ordem]'), array('categoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'categoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'categoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'categoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'editoria', 'titulo' => 'Editoria', 	'link' => str_replace(array('[col]','[ordem]'), array('editoria',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'editoria') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'editoria' ) ? 'ui-state-highlight'.( ($extras['col'] == 'editoria' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'titulo', 'titulo' => 'Titulo', 	'link' => str_replace(array('[col]','[ordem]'), array('titulo',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'titulo') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'titulo' ) ? 'ui-state-highlight'.( ($extras['col'] == 'titulo' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'tipo_area',  'titulo' => 'Área',    'link' => str_replace(array('[col]','[ordem]'), array('tipo_area',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'tipo_area') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'tipo_area' ) ? 'ui-state-highlight'.( ($extras['col'] == 'tipo_area' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'canais',  'titulo' => 'Canais',    'link' => str_replace(array('[col]','[ordem]'), array('canais',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'canais') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'canais' ) ? 'ui-state-highlight'.( ($extras['col'] == 'canais' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'canais_noticias',  'titulo' => 'Autor',    'link' => str_replace(array('[col]','[ordem]'), array('canais_noticias',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'canais_noticias') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'canais_noticias' ) ? 'ui-state-highlight'.( ($extras['col'] == 'canais_noticias' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'vitrine',  'titulo' => 'Vitrine',    'link' => str_replace(array('[col]','[ordem]'), array('vitrine',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'vitrine') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'vitrine' ) ? 'ui-state-highlight'.( ($extras['col'] == 'vitrine' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'vitrine_canal',  'titulo' => 'Vitrine Canal',    'link' => str_replace(array('[col]','[ordem]'), array('vitrine_canal',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'vitrine_canal') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'vitrine_canal' ) ? 'ui-state-highlight'.( ($extras['col'] == 'vitrine_canal' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    (object)array( 'chave' => 'log',  'titulo' => 'Log',    'link' => str_replace(array('[col]','[ordem]'), array('log',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'log') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'log' ) ? 'ui-state-highlight'.( ($extras['col'] == 'log' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
                                                    //(object)array( 'chave' => 'qtde_visualizacao',  'titulo' => 'Clicks',    'link' => str_replace(array('[col]','[ordem]'), array('qtde_visualizacao',( ($extras['ordem'] == 'ASC' && $extras['col'] == 'qtde_visualizacao') ? 'DESC' : 'ASC' ) ), $extras['url']), 'class' => ( ($extras['col'] == 'qtde_visualizacao' ) ? 'ui-state-highlight'.( ($extras['col'] == 'qtde_visualizacao' && $extras['ordem'] == 'ASC') ? ' ui-icon-caract-1-n' : ' ui-icon-caract-1-s' ) : 'ui-state-disabled ui-icon-caract-1-s' ) ),
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
	
        /**
         * Cria um filtro por id, editora, data_inicio, data_fim, titulo,
         * vitrine, vitrine_canal, tipo_area e canais,
         * cria botões de adicionar, exportar, deletar selecionados, 
         * marcar vitrine selecionados, desmarcar vitrine selecionados,
         * marcar vitrine canal selecionados, desmarcas vitrine canal selecionados
         * @param string $url
         * @param array $valores
         * @return array $filtro - instancia com a classe filtro
         * @version 1.0
         * @access private
         */
	private function _inicia_filtros($url = '', $valores = array() )
	{
            $this->load->model(array('noticias_tipo_area_model','canais_model'));
            $config['itens'] = array(
                                    array( 'name' => 'id',              'titulo' => 'ID: ',                                 'tipo' => 'text',       'valor' => '',                                              'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id',               'valor' => '' ) ),
                                    array( 'name' => 'editoria',        'titulo' => 'Editoria: ',                           'tipo' => 'select',     'valor' => $this->editorias_model->get_select(),            'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id_editoria', 	'valor' => '' ) ),
                                    array( 'name' => 'data_inicio',     'titulo' => 'Data Inicio (yyyy-mm-dd hh:mm): ',     'tipo' => 'text',       'valor' => '',                                              'classe' => 'data_hora data-inicio form-control ui-state-default',      'where' => NULL ),
                                    array( 'name' => 'data_inicio_hide','titulo' => '',                                     'tipo' => 'hidden',     'valor' => '',                                              'classe' => 'data-inicio-unix form-control ui-state-default',           'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.data >',           'valor' => '' ) ),
                                    array( 'name' => 'data_fim',        'titulo' => 'Data Fim (yyyy-mm-dd hh:mm): ',        'tipo' => 'text',       'valor' => '',                                              'classe' => 'data_hora data-fim form-control ui-state-default',         'where' => NULL ),
                                    array( 'name' => 'data_fim_hide',   'titulo' => '',                                     'tipo' => 'hidden',     'valor' => '',                                              'classe' => 'data-fim-unix form-control ui-state-default',              'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.data <',           'valor' => '' ) ),
                                    array( 'name' => 'titulo',          'titulo' => 'Titulo: ',                             'tipo' => 'text',       'valor' => '',                                              'classe' => 'form-control  ui-state-default',                           'where' => array( 'tipo' => 'like', 	'campo' => 'noticias.titulo',           'valor' => '' ) ),                                        
                                    array( 'name' => 'vitrine',         'titulo' => 'Vitrine: ',                            'tipo' => 'select',     'valor' => $this->_get_select_sim(),                        'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.vitrine', 		'valor' => '' ) ),
                                    array( 'name' => 'vitrine_canal',   'titulo' => 'Vitrine Canal: ',                      'tipo' => 'select',     'valor' => $this->_get_select_sim(),                        'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.vitrine_canal',    'valor' => '' ) ),
                                    array( 'name' => 'tipo_area',       'titulo' => 'Área: ',                               'tipo' => 'select',     'valor' => $this->noticias_tipo_area_model->get_select(),   'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.tipo_area',        'valor' => '' ) ),
                                    array( 'name' => 'canais',          'titulo' => 'Canais: ',                             'tipo' => 'select',     'valor' => $this->canais_model->get_select(),               'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id_canais',        'valor' => '' ) ),
                                    array( 'name' => 'canais_noticias',          'titulo' => 'Autor: ',                     'tipo' => 'select',     'valor' => $this->canais_noticias_model->get_select(),      'classe' => 'form-control ui-state-default',                            'where' => array( 'tipo' => 'where', 	'campo' => 'noticias.id_canal',         'valor' => '' ) ),
                                    );	
            $config['colunas'] = 4;
            $config['extras'] = '';
            $config['url'] = $url;
            $config['valores'] = $valores;
            $config['botoes']  = ' <a href="'.base_url().strtolower(__CLASS__).'/adicionar'.'" class="btn btn-primary">Add Novo</a>';
            $config['botoes'] .= ' <a href="'.base_url().strtolower(__CLASS__).'/exportar[filtro]'.'" class="btn btn-default">Exportar</a>';
            //$config['botoes'] .= ' <a  class="btn  btn-info editar">Editar Selecionados</a>';
            $config['botoes'] .= ' <a class="btn  btn-danger deletar">Deletar Selecionados</a><br><br>';
            $config['botoes'] .= ' <a class="btn  btn-primary marcar-vitrine">Marcar vitrine Selecionados</a>';
            $config['botoes'] .= ' <a class="btn  btn-danger desmarcar-vitrine">Desmarcar vitrine Selecionados</a><br><br>';
            $config['botoes'] .= ' <a class="btn  btn-primary marcar-vitrine-canal">Marcar vitrine Canal Selecionados</a>';
            $config['botoes'] .= ' <a class="btn  btn-danger desmarcar-vitrine-canal">Desmarcar vitrine Canal Selecionados</a>';

            $filtro = $this->filtro->inicia($config);
            return $filtro;
		
	}
        
        /**
         * Retorna a data em formato unix 
         * @version 1.0
         * @access public
         */
        public function get_timeunix()
        {
            $data = $this->input->post();
            $a = explode(' ', $data['valor']);
            $dia = explode('-', $a[0] );
            $hora = explode(':', ( isset( $a[1] ) ? $a[1] : '00:00' ) );
            $retorno = mktime($hora[0], $hora[1], '00', $dia[1], $dia[2], $dia[0] );
            echo $retorno;
        }
	
        /**
         * 
         * @return type
         * @version 1.0
         * @access private
         * @deprecated 
         */
        private function _get_select_sim()
        {
            $retorno = array( (object)array('id' => 1, 'descricao' => 'Marcado' ) );
            return $retorno;
        }
        
        /**
         * request o post do formulario para ser usado no editar e adicionar,
         * trata valores de checkbox
         * @return array $data com todos os campos setados do formulario.
         * @version 1.0
         * @access private
         */
	private function _post()
	{
		$data = $this->input->post(NULL, TRUE);
                $data['texto'] = $this->input->post('texto');
                $data['vitrine'] = ( (isset($data['vitrine']) ) ? 1 : 0);
                $data['vitrine_canal'] = ( (isset($data['vitrine_canal']) ) ? 1 : 0);
                $data['newsletter'] = ( (isset($data['newsletter']) ) ? 1 : 0);
                $data['canal_noticias'] = ( (isset($data['canal_noticias']) ) ? 1 : 0);
                $data['files'] = (isset($_FILES['files']) && $_FILES['files']) ? $_FILES['files'] : NULL;
		return $data;
	}
}