<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de anexos
 * @version 1.0
 * @access public
 * @package tarefas
 */
class Anexos extends MY_Controller 
{
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void 
         */
	public function __construct()
	{
		parent::__construct(FALSE);
		$this->load->model('images_model');
	}
	
        /**
         * seta a classe images
         * @version 1.0
         * @access public
         */
	public function index()
	{
		$this->images();
	}
	
        /**
         * adiciona a classe images
         * @version 1.0
         * @access public
         */
	public function adicionar ()
	{
		$this->images();
	}
	
        /**
         * criar as images para anexo,
         * chama o imges_model que vai chamar os dados do banco de dados,
         * criar o lay-out de acordo com a listagem, carrega arquivos js e css opcionais
         * @param string $familia
         * @param string $id
         * @param string $id_pai
         * @param string $sub
         * @version 1.0
         * @access public
         */
	public function images( $familia = NULL, $id = NULL, $id_pai = NULL, $sub = NULL )
	{
                if ( isset($familia) && isset($id) )
                {
                    
                    $model = ( $familia == 'padrao' ) ? 'pagina_model' : $familia.'_model';
                    $data['id_image_tipo'] = $this->images_model->get_id_tipo_image('image_tipo.tipo = "'.$familia.'" ');
                    $data['imagens'] = $this->images_model->get_select_tipo_image($familia);
                    $data['link_voltar'] = base_url().$familia.'/editar/'.$id;
                    if(isset($id_pai) && $id_pai)
                    {
                        $data['link_voltar'] .= '/'.$id_pai;
                        if(isset($sub) && $sub)
                        {
                            $data['link_voltar']  = str_replace('editar', 'editar_nivel_2', $data['link_voltar']);
                            $data['link_voltar'] .= '/'.$sub;
                        }
                    }
                    $data['familia'] = $familia;
                    $data['id_pai'] = $id;
                    $this->load->model($model);
                    $data['itens'] = $this->$model->get_item($id);
                    /*
                    $data['item'] = $this
                                        ->images_model
                                        ->get_itens('image_pai.id_pai = '. $id);
                    if ( $data['item'] )
                    {
                       $data['images'] = $this->images_model->get_arquivo_por_tipo($data['id_image_tipo']->id, $id );
                    }
                    else 
                    {
                        $data['images']['qtde'] = 0;
                    }*/
                }
                else
                {
                        echo '<script type="text/javascript">alert("setor inválido, tente novamente");</script>';
                        redirect($familia.'/listar/');
                }
                $class = strtolower(__CLASS__);
                $data['data_url'] = base_url().$class.'/upload_image';//$this->upload_image();
		$this->layout
                            ->set_function( __FUNCTION__ )
                            ->set_include('js/anexos.js', TRUE)
                            ->set_include('css/estilo.css', TRUE)
                            ->set_include('css/jquery.fileupload.css', TRUE)
                            //->set_breadscrumbs('Painel', 'painel',0)
                            //->set_breadscrumbs(ucfirst($familia), $familia, 1)
                            ->set_usuario($this->set_usuario())
                            ->view('add_anexos',$data);
	}
        
        /**
         * 
         * @version 1.0
         * @access public
         */
        public function get_images_por_tipo()
        {
            $data = $this->_post();
            $images = $this->images_model->get_arquivo_por_tipo($data['id_image_tipo'], $data['id_pai'] );
            $itens = $images['itens'];
            echo json_encode($itens);
        }
        
        public function upload_image()
        {
            $dados = $this->_post();
            $dir = $this->images_model->get_id_tipo_image('id = '.$dados['id_image_tipo'].' AND image_tipo.tipo = "'.$dados['familia'].'"');
            if($dados['familia'] == 'noticias')
            {
                $diretorio = str_replace('[ano]', date('Y'), $dir->pasta);
                $diretorio = str_replace('[mes]', date('m'), $diretorio);
            }
            else
            {
                $diretorio = $dir->pasta ;
            }
            $data['arquivo'] = $this->upload( $dados['files'] , $diretorio, $dados['id_pai']); 
            if( isset ( $data['arquivo'] ) )
            {
                $img_dados = array(
                                    'arquivo' => $data['arquivo']['name'], 
                                    'data' => date('Y-m-d H:i:s')
                                    );
                $id = $this->images_model->adicionar_arquivo( $img_dados );
                $return['return'] = 'success-'.$id.'-'.$data['arquivo']['pasta'].'-'.$data['arquivo']['name'];
            }
            echo json_encode($return['return']);
        }
        
	public function adicionar_image ()
	{
            $data = $this->_post();
            unset($data['files']);
            $id = $this->images_model->adicionar_pai($data);
            echo json_encode($id);
	}
	
        public function deleta_image()
        {
            $data = $this->_post();
            $image = $this->images_model->get_arquivo_por_id($data['id_image_arquivo']);
            $arquivo  = getcwd().'/'.$image->arquivo;
            $arquivo  = str_replace('admin2_0/', 'images/'.$data['familia'].'/', $arquivo);
            //echo $arquivo;
            if ( file_exists($arquivo) )
            {
                unlink($arquivo);
            }
            $quantidade = $this->images_model->excluir_arquivo('image_arquivo.id = '.$data['id_image_arquivo'] );
            if ($quantidade>0)
            {
                    print $quantidade;
            }
            else 
            {
                    print 0;
            }
        }
        
	public function remover_image($resposta = 'print')
	{
            $data = $this->_post(FALSE);
            if ( $data['id'] )
            {
                $image = $this->images_model->get_item_por_id_($data['id']);
                $filtro_deleta = 'id = '.$data['id'];
            }
            else
            {
                $image = $this->images_model->get_item_por_id($data['id_image_arquivo']);
                $filtro_deleta = 'id_image_arquivo = '.$data['id_image_arquivo'] ;
            }
            $arquivo  = CWD_IMAGE.'/'.$image->pasta;
            if(stristr($image->pasta, '[id]'))
            {
                $arquivo  = str_replace('[id]', $data['id_pai'], $arquivo);
            }
            $arquivo .= $image->arquivo;
            $this->load->helper("file");
            if ( file_exists($arquivo) )
            {
                    unlink($arquivo);
            }
            $quantidade = $this->images_model->excluir_pai( $filtro_deleta );
            $image_arquivo = $this->images_model->get_id_image_arquivo($image->id_arquivo);
            if($image_arquivo <= 1)
            {
                $quantidade_arquivo = $this->images_model->excluir_arquivo('image_arquivo.id = '.$image->id_arquivo );
            }
            if ( $resposta == 'print' )
            {
                print ( ($quantidade > 0) ? $quantidade.' itens foram apagados.' : 'Nenhum item apagado.') ;
            }
            else
            {
                echo json_encode( ($quantidade > 0) ? array('status' => TRUE) : array('status' => FALSE) );
            }
	}
	
    public function upload_temporario( $tipo = 'receita' )
    {
        $files = $_FILES;
        $post = $this->_post();
        $retorno = ( isset($post['resposta_type']) && $post['resposta_type'] == 'html' ) ? '' : array();
        $retorno = $this->_processa_images( $files[ $post['input'] ], $post );
        if ( isset($post['resposta_type']) )
        {
            switch ( $post['resposta_type'] )
            {
                case 'json':
                    echo json_encode($retorno);
                    break;
                case 'html':
                    echo $retorno;
                    break;
            }
            
        }
        else
        {
            echo $retorno;
        }
    }
    
    public function deleta_temporario()
    {
        $post = $this->_post();
        $arquivo = CWD_IMAGE.'/images/upload/'.$post['arquivo'];
        if ( file_exists($arquivo) )
        {
            unlink($arquivo);
            $retorno['erro'] = FALSE;
            $retorno['id'] = $post['sequencia'];
        }
        else
        {
            $retorno['erro'] = TRUE;
            $retorno['mensagem'] = 'Esta imagem já foi removida, restaure sua pagina.';
        }
        echo json_encode($retorno);
    }
    
    private function _processa_images ( $images, $post )
    {
        $retorno = NULL;
        if ( isset($images['name']) )
        {
            if (is_array($images['name']) )
            {
                for( $a = 0; count( $images['name'] ) > $a; $a++ )
                {
                    $image = array(
                                    'tmp_name' => $images['tmp_name'][$a],
                                    'name' => $images['name'][$a],
                                    'type' => $images['type'][$a],
                                    'size' => $images['size'][$a],
                                    );
                    $retorno[$a] = $this->_processa_image( $image, $post, $a );
                    unset($image);
                }
            }
            else
            {
                $retorno = $this->_processa_image( $images, $post, 0 );
            }
        }
        return $retorno;
        
    }
    
    private function _processa_image ( $images, $post, $chave )
    {
        $retorno = NULL;
        if ( isset($images['name']) )
        {
            $type = explode('|',$post['type']);
            if ( strstr( $images['type'], '/' ) )
            {
                $e_type = explode( '/', $images['type'] );
                $type_compare = $e_type[1];
            }
            else
            {
                if ( ! empty($images['type']) )
                {
                    $type_compare = $images['type'];
                }
                else
                {
                    $type_compare = substr($images['name'], -4);
                    $type_compare = str_replace('.', '', $type_compare);
                }
            }
            if ( in_array($type_compare,$type)  )
            {
                if ( $images['size'] <= $post['limite_kb'] )
                {
                    $arquivo_nome = md5( $images['name'].time() ).'.'.$type_compare;
                    $arquivo_dir = CWD_IMAGE.'/'.( isset($post['pasta']) ? $post['pasta'] : 'images/upload/' ).$arquivo_nome;
                    if ( move_uploaded_file( $images["tmp_name"], $arquivo_dir ) )
                    {
                        $retorno = array('chave' => $chave, 'erro' => FALSE, 'arquivo' => $arquivo_nome );
                    }
                    else
                    {
                        $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problemas no Upload do arquivo.' );
                    }
                }
                else
                {
                    $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'O arquivo deve ter no maximo: '.$post['limite_kb'].' kb' );
                }
            }
            else
            {
                $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'O arquivo '.$images['name'].' deve utilizar os seguintes formatos: '.implode(', ',$type) );
            }
        }
        return $retorno;
        
    }
    
    
    public function upload_image_com_resposta()
    {
        $this->load->model('image_tipo_model');
        $post = $this->_post();
        $tipo = $this->image_tipo_model->get_item($post['tipo']);
        $post['pasta'] = $tipo->pasta;
        $retorno = $this->_processa_images($_FILES['upload'], $post);
        if ( ! $retorno['erro'] )
        {
            $img_dados = array(
                                'arquivo' => $retorno['arquivo'], 
                                'data' => date('Y-m-d H:i:s')
                                );
            $id_arquivo = $this->images_model->adicionar_arquivo( $img_dados );
            if ( isset($id_arquivo) )
            {
                $data_pai = array('id_image_tipo' => $post['tipo'],'id_image_arquivo' => $id_arquivo, 'id_pai' => $post['id_pai']);
                $id_pai = $this->images_model->adicionar_pai( $data_pai );
                if ( isset($id_pai) )
                {
                    $retorno['id_pai'] = $id_pai;
                    $retorno['pasta'] = $tipo->pasta;
                }
                else
                {
                    $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problema ao salvar a relação de arquivo' );
                }
            }
            else
            {
                $retorno = array('chave' => $chave, 'erro' => TRUE, 'mensagem' => 'Problemas ao salvar o arquivo no banco de dados.' );
            }
            
        }
        echo json_encode($retorno);
    }
        
    
    public function upload_via_modal( $tabela = FALSE, $classe = FALSE, $id = FALSE, $tipo = FALSE )
    {
        $dados = $this->_post();
        if ( isset($dados) && $dados )
        {
            /*
             *  {   ["id"]=>   string(5) "81881"   
             * ["tabela"]=>   string(8) "empresas"   
             * ["classe"]=>   string(18) "pagina_logo_grande"   
             * ["tipo"]=>   string(5) "campo"   
             * ["limite_kb"]=>   string(7) "1886080"   
             * ["type"]=>   string(12) "jpeg|png|gif"   
             * ["resposta_type"]=>   string(4) "json"   
             * ["input"]=>   string(6) "upload" 
             * } 
             */
            $file = isset($_FILES[$dados['input']]) ? $_FILES[$dados['input']] : NULL;
            if ( isset($file) )
            {
                //$dados['pasta'] = '/paginas/';
                $retorno = $this->_processa_images($file, $dados);
                if ( ! $retorno['erro'] )
                {
                    /**
                     *  {   
                     *      ["chave"]=>   int(0)   
                     *      ["erro"]=>   bool(false)   
                     *      ["arquivo"]=>   string(36) "a30cd2dad4119f03c8a6c962fd36abc8.png" 
                     * } array(8) 
                     * {   
                     */            
                    $retorno['classe'] = $dados['classe'];
                    if ( $dados['tabela'] == 'publicidade_campanhas')
                    {
                        $pasta = 'publicidade';
                        
                    }
                    else
                    {
                        $pasta = 'paginas';
                    }
                    $destino = CWD_IMAGE.'/'.$pasta.'/'.$retorno['arquivo'];
                    $temporaria = CWD_IMAGE.'/images/upload/'.$retorno['arquivo'];
                    $moveu = copy($temporaria, $destino);
                    if ( $moveu )
                    {
                        unlink($temporaria);
                        $model = $dados['tabela'].'_model';
                        $filtro = array('id' => $dados['id']);
                        $data = array($dados['classe'] => $retorno['arquivo']);
                        $this->load->model($model);
                        $update = $this->$model->editar($data, $filtro);
                        if ( ! $update )
                        {
                            $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não pode ser salvo no banco de dados, verifique as inclusoes junto ao administrador de banco de dados, tente novamente: ');
                        }
                    }
                    else
                    {
                        $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não foi copiado, verifique as permissoes de pasta junto ao administrador de rede, tente novamente: ');
                    }
                }
            }
            else
            {
                $retorno = array('chave' => 0, 'erro' => TRUE, 'mensagem' => 'O arquivo não foi enviado, tente novamente: ');
            }
            echo json_encode($retorno);
        }
        else
        {
            $class = strtolower(__CLASS__);
            $data['tabela'] = $tabela;
            $data['classe'] = $classe;
            $data['id'] = $id;
            $data['tipo'] = $tipo;
            $data['formatos'] = 'jpeg|png|gif';
            $this->layout
                        ->set_function( __FUNCTION__ )
                        ->set_include('js/upload_via_modal.js', TRUE)
                        ->set_include('js/upload2/funcs.js', TRUE)
                        ->set_include('css/estilo.css', TRUE)
                        ->set_usuario($this->set_usuario())
                        ->view('upload_via_modal',$data, 'layout/sem_menu');
        }
    }   
    
    private function _post( $nimage = TRUE )
    {
            $data = $this->input->post(NULL, TRUE);
            if ( (isset($_FILES['files']) && $_FILES['files'] ) )
            {
                $data['files'] = $_FILES['files'];
            }
            if ( (isset($_FILES['file']) && $_FILES['file'] ) )
            {
                $data['file'] = $_FILES['file'];
            }
            return $data;
    }
}
