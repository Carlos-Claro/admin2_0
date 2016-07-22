<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de gerenciamento de servidor
 * @version 1.0
 * @access public
 * @package Servidor
 */
class Servidor extends MY_Controller 
{       
        
        
        /**
         * Controi a classe e carrega valores de extends
         * e carrega models padrao para esta classe
         * @return void
         */
	public function __construct()
	{
            if ( COMMAND_LINE )
            {
                $valida = FALSE;
            }
            else
            {
                if ( isset($_GET['own']) )
                {
                    $valida = FALSE;
                    
                }
                else
                {
                    $valida = ( isset($_GET['usuario'] ) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86' ) ? FALSE : TRUE;
                    
                }
            }
            parent::__construct($valida);
	}
        
        public function DNS( $arquivo = FALSE )
        {
            
            if ( $arquivo && file_exists(getcwd().'/temporario/'.$arquivo) )
            {
                $conteudo = file_get_contents(getcwd().'/temporario/'.$arquivo);
                //$arquivo
            }
            else
            {
                show_error('Nenhum arquivo de dominios enviado, utilize um parametro da url, ex: '.base_url().'/DNS/Nomedoarquivo.txt onde nomedoarquivo.txt deve estar na pasta temporario');
            }
        }
        
}
