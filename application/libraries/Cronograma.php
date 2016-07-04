<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Librarie Cronograma, responsavel por montar a tabela de cronograma dos projetos e usuarios, com base nas tarefas agendadas
 */
class Cronograma
{
	private $data;
        
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct() 
	{
	}
	
        /**
         * Monta html
         * @todo 
         * @param array $data seguem com elementos fundamentais para a aplicação:
         * @param object $data['datas'] contem data_inicio e data_fim, sendo os limites do cronograma
         * @param object $data['tarefas'] contem as informações das tarefas, onde cada uma tem [item] e [horas_trabalhado] como objeto principal, cada tarefa deve conter as datas de inicio e fim, responsaveis e muito mais...
         * @return string html formado do cronograma
         */
	public function get_html( $data )
	{
            $data_inicio = explode(' ', $data['datas']->data_inicio);
            $primeiro_dia = explode('-', $data_inicio[0]);
            $time_inicio = retorna_time($data['datas']->data_inicio);
            $time_fim = retorna_time($data['datas']->data_fim);
            $qtde_dias_projeto = ceil(( $time_fim - $time_inicio) / 86400 );
            $cabecalho = '<tr>';
            $cabecalho .= '<th>Tarefas</th>';
            $cabecalho .= '<th>Horas</th>';
            $cabecalho .= '<th>Horas_trabalhadas</th>';
            for ( $a = 0; $a < $qtde_dias_projeto; $a++ )
            {
                $dia_ca = date('d'.PHP_EOL.'m',mktime (0, 0, 0, $primeiro_dia[1]  , $primeiro_dia[2]+$a, $primeiro_dia[0]));
                $cabecalho .= '<th width="10">'.$dia_ca.'</th>';
            }
            $cabecalho .= '</tr>';
            $corpo = '';
            $hoje = date('Y-m-d');
            $hoje_time = time();
            foreach( $data['tarefas'] as $tarefa )
            {
                $corpo .= '<tr>';
                /*
                 * ' - '.$tarefa['item']->data_inicio.' - '.$tarefa['item']->data_fim.
                 */
                $corpo .= '<td><a href="'.base_url().'tarefas/editar/'.$tarefa['item']->id_tarefas_projeto.'/'.$tarefa['item']->id.'" target="_blank">';
                $corpo .= $tarefa['item']->titulo;
                //$corpo .= ' - '.$tarefa['item']->data_inicio.' - '.$tarefa['item']->data_fim;
                $corpo .= '</a></td>';
                $corpo .= '<td>'.$tarefa['item']->previsao_horas.'</td>';
                $corpo .= '<td>'.number_format($tarefa['horas_trabalhado'],2 ).'</td>';
                $data_inicio_tarefa = explode(' ', $tarefa['item']->data_inicio);
                $primeiro_dia_tarefa = explode('-', $data_inicio_tarefa[0]);
                //$time_inicio_tarefa = retorna_time($tarefa['item']->data_inicio);
                //$time_fim_tarefa = retorna_time($tarefa['item']->data_fim);
                $data_fim_tarefa = explode(' ', $tarefa['item']->data_fim);
                $cor = 'FFF';
                $comeca = FALSE;
                //var_dump($qtde_dias_tarefa);
                for ( $a = 0; $a < $qtde_dias_projeto; $a++ )
                {
                    $dia = date('Y-m-d',mktime (0, 0, 0, $primeiro_dia[1]  , $primeiro_dia[2]+$a, $primeiro_dia[0]));
                    $dia_time = mktime (0, 0, 0, $primeiro_dia[1]  , $primeiro_dia[2]+$a, $primeiro_dia[0]);
                    //var_dump($dia_tarefa, $data_inicio_tarefa[0]);
                    if ( $data_inicio_tarefa[0] == $dia )
                    {
                        $cor = '00F';
                        $comeca = TRUE;
                        $dias = 0;
                    }
                    if ( $cor != 'FFF' )
                    {
                        if ( $tarefa['item']->id_tarefa_status == 2 )
                        {
                            $cor = '0F0';
                        }
                        else
                        {
                            $cor = $dia_time < $hoje_time ? 'F00' : '00F';
                        }
                    }
                    $corpo .= '<td style="background-color:#'.$cor.'">&nbsp;</td>';
                    if ( $comeca )
                    {
                        if ( $data_fim_tarefa[0] == $dia )
                        {
                            $comeca = FALSE;
                            $cor = 'FFF';
                        }
                    }
                }
                
                $corpo .= '</tr>';
            }
            $tabela = '<table border="1" class="table">';
            $tabela .= $cabecalho;
            $tabela .= $corpo;
            $tabela .= '</table>';
            return $tabela;
        }
}