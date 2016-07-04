<?php

class Anexo_Lib
{
    private $CI;
    
    public function __construct() 
    {
        $this->CI =& get_instance();
        $this->CI->load->model('images_model');
    }
    
    public function do_upload($dados = array())
    {
        $retorno = '';
        $dir = $this->CI->images_model->get_id_tipo_image('id = '.$dados['image_tipo'].' AND image_tipo.tipo = "'.$dados['familia'].'"');
        if($dados['familia'] == 'noticias')
        {
            $diretorio = str_replace('[ano]', date('Y'), $dir->pasta);
            $diretorio = str_replace('[mes]', date('m'), $diretorio);
        }
        else
        {
            $diretorio = $dir->pasta ;
        }
        $extras['lixo'] = $dados['lixo'];
        $extras['replace'] = $dados['id_pai'];
        $data = $this->upload( $dados['files'] , $diretorio, $extras); 
        if( isset ( $data ) )
        {
            $retorno = 'success-'.$data['lixo'].'-'.$data['pasta'].'-'.$data['name'].'-'.$data['diretorio_imagem'];
        }
        return $retorno;
    }
    
    private function upload( $file, $dir = 'images/upload/', $extras = array() )	
    {	
        if( $file['name'] )		
        {
            if(preg_match("/\.(jpg|png|gif|JPG|PNG|pdf|doc){1}$/i", $file['name'][0], $ext))
            {
                $nome = explode('.', $file['name'][0]);			
                $name = tira_acento($nome[0]).date('Mhis').'.'.$ext[1];
                
                if($extras['lixo'] == 's')
                {
                    $imagem_dir = getcwd().'/images/lixo/';
                    $imagem_dir = str_replace('admin2_0/', '',$imagem_dir);
                }
                else
                {
                    $imagem_dir = getcwd().'/'.$dir;
                    $imagem_dir = str_replace('admin2_0/', '',$imagem_dir);
                    if(isset($extras['replace']) && $extras['replace'])
                    {
                        $imagem_dir = str_replace('[id]', $extras['replace'], $imagem_dir);
                    }
                }
                
                $pasta = $imagem_dir;
                
                if(!is_dir($pasta))
                {
                    criar_diretorios($pasta);
                }
                $imagem_dir .= $name;
                $data = (move_uploaded_file( $file['tmp_name'][0], $imagem_dir)) ? array('name' => $name, 'pasta' => $dir, 'lixo' => $extras['lixo'], 'diretorio_imagem' => $imagem_dir ) : NULL ;
            }
        }
        else		
        {			
            $data = NULL;		
        }		
        return $data;
    }	
    
    public function atualizar_uploads($data = array())
    {
        if(isset($data['arquivo']) && !empty($data['arquivo']))
        {
            $image_arquivo['data'] = date('Y-m-d H:i:s');

            $image_pai['id_image_tipo'] = $data['id_image_tipo'];
            $image_pai['id_pai'] = $data['id_pai'];

            if($data['lixo'] == 's')
            {
                $arquivo = $this->CI->images_model->get_tipo_por_id($image_pai['id_image_tipo']);
                $origem = getcwd().'/images/lixo/';
                $origem = str_replace('admin2_0/', '', $origem);
                $destino = getcwd().'/'.$arquivo->pasta;

                $replaces['itens'][] = (object) array('find' => '[ano]', 'replace' => date('Y') );
                $replaces['itens'][] = (object) array('find' => '[mes]', 'replace' => date('m') );
                $replaces['itens'][] = (object) array('find' => '[id]',  'replace' => $data['id_pai'] );
                $replaces['itens'][] = (object) array('find' => 'admin2_0/',  'replace' => '' );

                foreach($replaces['itens'] as $replace)
                {
                    if(strripos($destino, $replace->find))
                    {
                        $destino = str_replace($replace->find, $replace->replace, $destino);
                    }
                }
            }

            foreach($data['arquivo'] as $chave => $valor)
            {
                $image_arquivo['arquivo'] = $valor;
                $id_arquivo = $this->CI->images_model->adicionar_arquivo($image_arquivo);
                if($id_arquivo)
                {
                    if($data['lixo'] == 's')
                    {
                        $org  = $origem.$valor;
                        $dest = $destino.$valor;
                        copy($org, $dest);
                        unlink($org);
                    }
                    $image_pai['id_image_arquivo'] = $id_arquivo;
                    $image_pai['descricao'] = $data['descricao'][$chave];
                    $this->CI->images_model->adicionar_pai($image_pai);
                }
            }
        }
    }
	
    public function deletar_arquivo($dados = array())
    {
        $retorno = 0;
        if($dados['lixo'] == 's')
        {
            $arquivo = getcwd().'/'.$dados['id_image_arquivo'];
            $arquivo = str_replace('admin2_0/', 'images/lixo/', $arquivo);
        }
        else
        {
            $arquivo = $dados['path'];
            $arquivo = str_replace('admin2_0/', 'images/'.$dados['familia'].'/', $arquivo);
        }
        
        if ( file_exists($arquivo) )
        {
            unlink($arquivo);
            $retorno = 1;
        }
        return $retorno;
    }
        
    public function remover_arquivo($dados = array())
    {
        
        $image = $this->CI->images_model->get_item_por_id($dados['id_image_arquivo']);
        $arquivo = getcwd().'/'.$image->pasta;
        
        $replaces['itens'][] = (object) array('find' => '[ano]', 'replace' => date('Y') );
        $replaces['itens'][] = (object) array('find' => '[mes]', 'replace' => date('m') );
        $replaces['itens'][] = (object) array('find' => '[id]',  'replace' => $dados['id_pai'] );
        $replaces['itens'][] = (object) array('find' => 'admin2_0/',  'replace' => '' );
        
        foreach($replaces['itens'] as $replace)
        {
            if(strripos($arquivo, $replace->find))
            {
                $arquivo = str_replace($replace->find, $replace->replace, $arquivo);
            }
        }
        
        $arquivo .= $image->arquivo;
        
        if ( file_exists($arquivo) )
        {
                unlink($arquivo);
        }
        $quantidade = $this->CI->images_model->excluir_pai('id_image_arquivo = '.$dados['id_image_arquivo'] );
        $image_arquivo = $this->CI->images_model->get_id_image_arquivo($dados['id_image_arquivo']);
        if($image_arquivo <= 1)
        {
            $quantidade_arquivo = $this->CI->images_model->excluir_arquivo('image_arquivo.id = '.$dados['id_image_arquivo'] );
        }
        $retorno = ( ($quantidade > 0) ? $quantidade.' itens foram apagados.' : 'Nenhum item apagado.') ;
        return $retorno;
    }
    
}
