<?php
function exporta_excel($data, $name)
{
	function cleanData(&$str) 
	{ 
		$str = preg_replace("/\t/", "\\t", $str); 
		$str = preg_replace("/\r?\n/", "\\n", $str);
		$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
		$str = utf8_decode($str);
		$str = strip_tags($str);
		
		if(strstr($str, '"')) 
		{
			$str = '"' . str_replace('"', '""', $str) . '"';
		} 
		
	}	
	header("Content-Disposition: attachment; filename=".$name); 
	header("Content-Type: application/vnd.ms-excel");

	$flag = false; 
	
	foreach($data['itens'] as $row) 
	{
		$row_alt = (array)$row;
		if(!$flag) 
		{
			$cabecalho =  implode("\t", array_keys($row_alt)) . "\r\n";
			echo $cabecalho; 
			$flag = true;
		} 
		array_walk($row_alt, 'cleanData'); 
		$texto = implode("\t", array_values($row_alt)) . "\r\n";
		echo $texto;		 
	} 
	exit;
}
if ( ! function_exists('form_select'))
{
    
    function form_select($item, $selecionado = '')
    {
            $campo  = '<select '
                    . (isset($data['controller']) ? 'data-controller="'.$data['controller'] : '').'" '
                    . 'name="'.$item['nome'].'" '
                    . ''.$item['extra'].' '
                    . ''.(!empty($item['disabled']) ? 'disabled="disabled"' : '').' '
                    . 'class="form-control '.( isset($item['class']) ? $item['class'] : '').''
                    . '"'
                    . 'data-tabela="'.(isset($item['tabela']) ? $item['tabela'] : 'empresas').'" '
                    . '>'.PHP_EOL;
            $campo .= '<option value="">Selecione...</option>'.PHP_EOL;
            if(isset($item['valor']) && $item['valor'])
            {
                foreach ($item['valor'] as $opcao)
                {
                    $campo .= '<option value="'.$opcao->id.'"'.(($opcao->id == $selecionado) ? ' selected="selected" ' : '').' title="'.$opcao->descricao.'">';
                    $campo .= $opcao->descricao;
                    $campo .= '</option>'.PHP_EOL;
                }
            }
            $campo .= '</select>'.PHP_EOL;
            return $campo;
    }
}

if ( ! function_exists('form_checkbox_'))
{
	function form_checkbox_($item, $selecionado = array(), $tipo = 1)
	{
            $campo  = '';
            switch($tipo)
            {
                case 1 :
                    $campo .= '<div class="row">';
                    $campo .= '<div class="input-group col-lg-3" >'.PHP_EOL; 
                    $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                    $campo .= '<input type="checkbox" id="sel_todos" />';
                    $campo .= '</div>';
                    $campo .= '<input type="text" class="form-control" value="Marcar Todos">'.PHP_EOL; 
                    $campo .= '</div>';
                    foreach ($item['valor'] as $opcao)
                    {
                            $campo .= '<div class="input-group '.$item['extra'].'">';
                            $campo .= '<div class="input-group-addon" >'.PHP_EOL; 
                            $campo .= '<input class="groups" name="'.$item['nome'].'['.$opcao->id.']" id="'.$item['nome'].'" '.((array_key_exists($opcao->id,$selecionado))? 'checked="checked"' : '').' type="checkbox" value="'.$opcao->id.'">';
                            $campo .= '</div>';
                            $campo .= '<input type="text" class="form-control" value=" '.$opcao->descricao.' ">'.PHP_EOL; 
                            $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    break;
                case 2 :
                    $campo .= '<div class="alert alert-success">';
                    $campo .= '<div class="row">';
                    foreach ($item['valor'] as $opcao)
                    {
                        $campo .= '<div class="checkbox '.(isset($item['class']) ? $item['class'] : '').'">';
                        $campo .= '<label>';
                        $campo .= '<input type="checkbox" name="'.$item['nome'].'[]" id="'.$item['nome'].'" '.((array_key_exists($opcao->id, $selecionado))? 'checked="checked"' : '').' value="'.$opcao->id.'" />';
                        $campo .= $opcao->descricao;
                        $campo .= '</label>';
                        $campo .= '</div>';
                    }
                    $campo .= '</div>';
                    $campo .= '</div>';
                    break;
            }
            return $campo;
	}
}
function form_selecionavel($item, $selecionado = NULL, $link = TRUE, $valor = TRUE)
{
    $retorno = '<div class="list-group">';
    foreach( $item['valor'] as $data )
    {
        if ( isset($data->qtde) && $data->qtde > 0 )
        {
            $retorno .= '<a tabindex="1"';
            $retorno .= ( ( $link && isset($data->link) ) ? 'href="' . $data->link . '"' : ''  );
            $retorno .= ' class="list-group-item '.$item['link'].' item-pesquisa col-lg-4 col-sm-6 col-md-6 col-xs-12 '.( isset($selecionado) && ($data->id == $selecionado ) ? 'active' : '' ).'" data-item="' . $data->id . '" >';
            $retorno .= '<p class="list-group-item-text">' . $data->descricao . '</p> ';
            $retorno .= (isset($data->qtde) && $valor) ? '<span class="badge pull-right">' . $data->qtde . '</span>' : '';
            $retorno .= '</a>';
        }
    }
    $retorno .= '</div>';
    return $retorno;
}

/**
 * monta campo input editavel automaticamente
 * @param array $data
 * [tipo] - text, textarea
 * [classe]
 * [sequencia]
 * [class]
 * [valor]
 * [disablled]
 * [titulo]
 * [nao_salva]
 * [complemento] 
 * [helper_text] 
 * [controller] 
 * [tabela] 
 * @return string $retorno
 */
function set_campo_editavel ( $data )
{
    $retorno = '';
    $retorno .= '<div class="form-group '.$data['classe'].'">';
$retorno .= '       <button title="desfazer alteraçao" type="button" class="btn btn-danger btn-md glyphicon glyphicon-retweet hide historico '.$data['classe'].'" data-campo="'.$data['classe'].'"></button>';
    $retorno .= '   <label for="'.$data['classe'].'">'.$data['titulo'];
    $retorno .= '   </label>';
    //$retorno .= '   <div class="input-group">';
    $antes = '';
    $depois = '';
    $classe_ = '';
    switch( $data['tipo'] )
    {
        case 'hidden':
        case 'text':
        case 'password':
            if ( isset($data['prefixo']) )
            {
                $antes .= '<div class="input-group">';
                $antes .= '<span class="input-group-addon" id="basic-addon1">'.$data['prefixo'].'</span>';
                $depois .= '</div>';
            }
            $retorno .= $antes;
            $retorno .= '<input '
                            . 'name="'.$data['classe'].'" '
                            .( isset($data['disabled']) ? 'disabled="disabled"' : '' ).' '
                            . 'type="'.$data['tipo'].'" '
                            . 'class="form-control campo-'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).' '.(isset($data['class']) ? $data['class'] : '').'" ';
            $retorno .= 'data-sequencia="'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).'" ';
            $retorno .= isset($data['nao_salva']) ? 'data-nao-salva="1" ' : '';
            $retorno .= 'data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" ';
            $retorno .= 'data-tabela="'.(isset($data['tabela']) ? $data['tabela'] : 'empresas').'" ';
            $retorno .= 'id="'.$data['classe'].'" '
                            . 'placeholder="'.$data['titulo'].'" '
                            . 'value="'.$data['valor'].'">';
            $retorno .= $depois;
            break;
        case 'textarea':
            $retorno .= '<span class="pull-right text-info">qtde caracteres: <span class="contador_'.$data['classe'].'"></span></span>
                            <textarea 
                                name="'.$data['classe'].'" '
                                . ' class="form-control contavel campo-'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).'" '
                                . 'data-sequencia="'.( isset($data['sequencia']) ? $data['sequencia'] : '0' ).'" '
                                . ''
                . 'data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" '
                . 'data-tabela="'.(isset($data['tabela']) ? $data['tabela'] : 'empresas').'" '
                . 'id="'.$data['classe'].'" '
                                . 'placeholder="'.$data['titulo'].'">'
                                    . $data['valor'].'</textarea>'
                . '<script type="javascript">$(function(){contador.por_classe("#'.$data['classe'].'", ".contador_'.$data['classe'].'");});</script>';
            break;
    }
    //$retorno .= '   </div>';
    $retorno .= isset($data['complemento']) ? $data['complemento'] : '';
    $retorno .= '<p class="'.$data['classe'].' help-block">'.( isset($data['helper_text']) ? $data['helper_text'] : '' ).'</p>';
    $retorno .= '</div>';
    return $retorno;
}

/**
 * monta botao editavel automaticamente
 * @param array $data
 * [classe]
 * [valor]
 * [texto][on]
 * [texto][off]
 * [datas][inicio]
 * [datas][fim]
 * [complemento] string/array com os campos que ccomplementao e usam o mesma base de classe
 * @return string $retorno
 */
function set_botao_editavel ( $data )
{
    $on = 'success';
    $off = 'danger';
    if ( isset($data['reverse']) )
    {
        $on = 'danger';
        $off = 'success';
    }
    $item = ( isset($data['valor']) && $data['valor'] ) ? $data['valor'] : FALSE;
    $retorno = '<div class="form-group">';
    $expande = 0;
    $abre = '';
    $fecha = '';
    if ( isset($data['datas']) || isset($data['complemento']) )
    {
        $expande = 1;
        $abre .= '<div class="alert alert-'.( $item ? $on : $off ).' expansivo-'.$data['classe'].'">';
        $abre .= '<div class="row">';
        $abre .= '<div class="col-lg-12 col-md-12 col-sm-12 cool-xs-12">';
        
        $fecha .= '</div>';
        $fecha .= '</div>';
        $fecha .= '<div class="'.( $item ? 'show' : 'hide' ).' expansivo">';
        if ( isset($data['datas']) )
        {
            $fecha .= '<div class="row">';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $inicio = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'empresas',
                            'classe' => isset($data['datas']['inicio']['classe']) ? $data['datas']['inicio']['classe'] : $data['classe'].'_inicio',
                            'class' => 'data',
                            'valor' => $data['datas']['inicio']['valor'],
                            'titulo' => $data['texto']['on'].' Inicio',
                            );
            $fecha .= set_campo_editavel($inicio);
            
            $fecha .= '</div>';
            $fecha .= '<div class="col-lg-6 col-md-6 col-sm-6 cool-xs-6">';
            $fim = array(
                            'tipo' => 'text',
                            'controller' => isset($data['controller']) ? $data['controller'] : 'empresas',
                            'classe' => isset($data['datas']['fim']['classe']) ? $data['datas']['fim']['classe'] : $data['classe'].'_termino',
                            'class' => 'data',
                            'valor' => $data['datas']['fim']['valor'],
                            'titulo' => $data['texto']['on'].' Termino',
                        );
            $fecha .= set_campo_editavel($fim);
            $fecha .= '';
            $fecha .= '</div>';
            $fecha .= '</div>';
            
        }
        if ( isset($data['complemento']) )
        {
            $fecha .= $data['complemento'];
            
        }
        $fecha .= '</div>';
        $fecha .= '</div>';
    }
    $retorno .= $abre;
    $retorno .= '<button '
                        . 'class="form-control btn-acao '.$data['classe'].' btn btn-'.( $item ? $on : $off).'" '
                        . 'data-reverse="'.( isset($data['reverse']) ? 1 : 0 ).'" '
                        . 'data-item="'.( $item ? 1 : 0 ).'" '
                        . 'data-controller = '.( isset($data['controller']) ? $data['controller'] : 'empresas' ).' '
                        . 'data-campo="'.$data['classe'].'" '
                        . 'data-expande="'.$expande.'" '
                        . 'data-marcado="'.$data['texto']['on'].'" '
                        . 'data-desmarcado="'.$data['texto']['off'].'" '
                        . 'type="button" >'
                        . ( $item ? $data['texto']['on'] : $data['texto']['off'] ).'
                </button>
                <div class="help-block '.$data['classe'].'"></div>';
    $retorno .= $fecha;
    $retorno .= '</div>';
    return $retorno;
}

function set_image_editavel ( $data )
{
    $retorno = '';
    $retorno .= '<div class="form-group '.$data['classe'].'">';
    $retorno .= '<div class="alert alert-success">';
    $retorno .= '<label for="'.$data['classe'].'">'.$data['titulo'].'</label>';
    $retorno .= '<div class="espaco-image">';
    if ( isset($data['image'] ) && ! empty($data['image']) ) 
    {
        $retorno .= '<center><img src="'.$data['image'].'" class="image " data-item="'.$data['classe'].'" ></center>';
        $retorno .= '<button type="button" class="btn btn-danger form-control deleta-image" data-item="'.$data['classe'].'" data-controller="'.(isset($data['controller']) ? $data['controller'] : 'empresas').'" '.(isset($data['extras']) ? $data['extras'] : '').'>Deletar</button>';
    }
    else
    {
        $retorno .= '<button type="button" class="btn btn-success image form-control" data-item="'.$data['classe'].'" data-titulo="'.$data['titulo'].'" data-controller="'.( isset($data['controller']) ? $data['controller'] : 'empresas').'" '.(isset($data['extras']) ? $data['extras'] : '').'>Upload '.$data['titulo'].'</button>';
    }
    $retorno .= '</div>';
    $retorno .= isset( $data['complemento'] ) ? $data['complemento'] : '';
    $retorno .= '</div>';
    $retorno .= '</div>';
    
    return $retorno;
}

function converte_data_mysql($data)
{
    $data_explode = explode(' ', $data);
    $data_i = explode('/',$data_explode[0]);
    $data = $data_i[2].'-'.$data_i[1].'-'.$data_i[0].' '.( isset($data_explode[1]) ? $data_explode[1] : '00:00' );
    return $data;
}

function reverte_data_mysql($data)
{
    if( ! empty( $data ))
    { 
	$data_explode = explode(' ', $data);
	$data_i = explode('-',$data_explode[0]);
	$data = $data_i[2].'/'.$data_i[1].'/'.$data_i[0].' '.( isset($data_explode[1]) ? $data_explode[1] : '' );
    }
    return $data;
}

function converte_data_unixtime($data)
{
        list($data, $hora) = explode(' ', $data);
	$data = explode('-', $data);
	$hora = explode(':', $hora);
	$timestamp = mktime((isset($hora[0]) ? $hora[0] : 0), (isset($hora[1]) ? $hora[1] : 0),(isset($hora[2]) ? $hora[2] : 0), $data[1], $data[2],$data[0]);
        
	return $timestamp;
}

function set_time_to_data_pt_br($data = '')
{
    $retorno = '';
    if ( !empty($data) )
    {
	$retorno = strstr($data,'/') ? $data : date('d-m-Y H:i', $data);
    }
    return $retorno;
}

function mes_ano($data)
{
        list($data, $hora) = explode(' ', $data);
	$data = explode('/', $data);
	$retorno['mes'] = $data[1];
	$retorno['ano'] = $data[2];
        
	return $retorno;
}

function tira_acento ( $palavra )
{		
    //$palavra = strtolower($palavra);
    //$array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','�',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','-','1','2','3','4','5','6','7','8','9','0 ','1 ','2 ','3 ','4 ','5 ','6 ','7 ','8 ','9 ','0 ');
    //$array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '', '+','+','+','+','+','+','+','+','+','S', '+' ,'+','+','+','+', ''  ,''  , '', '','', '','','','','','','','','','','',' ','','','','','','','','','','');
    //$retorno = str_replace($array_a, $array_b, $palavra);
    //return $retorno;
    
    //$palavra = strtolower($palavra);
    $array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','�',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','–','.','#','0',',');
    $array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '',  '', '', '', '', '', '', '', '', '', '',  '+', '','', '', '', ''  , ''  ,'',   '', ''   , '+','' ,'', '' ,'','+' );
    $retorno = str_replace($array_a, $array_b, $palavra);
    $sim = 0;
    for( $a = 0; $a <= 9; $a++ )
    {
        $e = stripos($retorno, strval($a) );
        if ( ( $e && $e < 2 ) || $e == 0 )
        {
            $sim = 1;
            $retorno = str_replace(strval($a), '', $retorno);
        }
    }
    
    if ( $sim )
    {
        for( $a = 0; $a <= 9; $a++ )
        {
            $e = stripos($retorno, strval($a) );
            if ( ( $e && $e < 2 ) || $e == 0 )
            {
                $retorno = str_replace(strval($a).'+', '', $retorno);
            }
        }
    }
    if ( substr($retorno, 0, 1) == '+' )
    {
        $retorno = substr($retorno, 1);
    }
    return strtolower($retorno);
} 

/*
function calcular_intervalo_tempo($data_inicio = '', $hora_inicio = '', $data_fim = NULL, $hora_fim = NULL)
{
    $retorno = NULL;
        
    $dt_ini = getMicroTime($data_inicio);
    $hr_ini = DateTime::createFromFormat('H:i:s', $hora_inicio);
    
    if(isset($data_fim) && $data_fim)
    {
        $dt_fim = getMicroTime($data_fim);
        $hr_fim = DateTime::createFromFormat('H:i:s', $hora_fim);
    }
    else
    {
        $dt_fim = getMicroTime(date('d-m-Y'));
        $hr_fim = DateTime::createFromFormat('H:i:s', date('H:i:s'));
    }
    
    $diferenca = $dt_fim - $dt_ini;
    $dias = (int)floor( $diferenca / (60 * 60 * 24));
    
    $intervalo = $hr_ini->diff($hr_fim);
    $retorno = array('dias' => $dias, 'horas' => $intervalo->format('%H:%I:%S'));
        
    return $retorno;
}
*/

function getMicroTime($data)
{
    $partes = explode('-', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function get_horario_comercial()
{
    $hr_ini = '08:00';
    $periodo[] = $hr_ini;
    $hora;
    for($i = 1; $i < 19; $i++)
    {
        if(empty($hora))
        {
            $periodo[$i] = date('H:i', strtotime('+30 minute', strtotime($hr_ini)));
        }
        else
        {
            $periodo[$i] = date('H:i', strtotime('+30 minute', strtotime($hora[$i-1])));
        }
        $hora = $periodo;
    }
    return $periodo;
}

/*
 * Cria diretorios que não existem recursivamente.
 * Ex.: images/teste/empresa/83002/  
 * Vai percorrer cada diretorio e se não existir criá-lo, utilizada em conjunto com
 * função de fazer upload.
 * 
 * @author: Breno Henrique Moreno Nunes
 * @param: string $diretorio Ex.: images/teste/empresa/83002/ 
 */
function criar_diretorios($diretorio = NULL){
    
    if (!is_dir($diretorio) )
    {
        $temp = str_replace('\\', '/', $diretorio);
        $temp = explode('/', $temp);
        $path = $temp[0];
        $qtde = count($temp);
        $i = 0;
        while($i < $qtde)
        {
            if(!is_dir($path)) {  mkdir($path, 0777); }
            $i++;
            if($i < $qtde){ $path .= '/'.$temp[$i]; }
        }
    }
}

function get_select_frequencia()
{
    $array = array(
                    (object)array('id' => 'unico', 'descricao' => 'Unico'),
                    (object)array('id' => 'diaria', 'descricao' => 'Diária'),
                    (object)array('id' => 'semanal', 'descricao' => 'Semanal'),
                    (object)array('id' => 'quinzenal', 'descricao' => 'Quinzenal'),
                    (object)array('id' => 'mensal', 'descricao' => 'Mensal'),
                    );
    return $array;
}

function get_select_impacto()
{
    $array = array(
                    (object)array('id' => '9', 'descricao' => 'Muito Grande'),
                    (object)array('id' => '7', 'descricao' => 'Grande'),
                    (object)array('id' => '5', 'descricao' => 'Médio'),
                    (object)array('id' => '3', 'descricao' => 'Pequeno'),
                    (object)array('id' => '1', 'descricao' => 'Muito Pequeno'),
                    );
    return $array;
}

function get_tempo( $array = FALSE )
{
    $retorno = 0;
    if ( $array && count($array) > 0 )
    {
        $tempo = 0;
        foreach( $array as $data )
        {
            $time_fim = retorna_time( $data->data_fim );
            $time_inicio = retorna_time( $data->data_inicio );
            $soma = $tempo + ($time_fim - $time_inicio);
            $tempo = $soma;
        }
        $retorno = ($tempo / 3600);
    }
    return $retorno;
    
}

function retorna_time( $data )
{
    $tempo = explode(' ',$data);
    $dia = explode('-', $tempo[0]);
    $hora = explode(':', $tempo[1]);
    $time = mktime($hora[0], $hora[1], $hora[2], $dia[1], $dia[2], $dia[0]);
    return $time;
}

function verifica_image( $image = NULL )
{
    if ( isset($image) )
    {
        return TRUE;
        /*
        $header_image = get_headers( $image, 1 );
        //var_dump($header_image);
        if ( strstr( $header_image[0], 'OK' ) )
        {
            $retorno = TRUE;
        }
        else
        {
            $retorno = FALSE;
        }
         * 
         */
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}

function set_dayweek( $dia = 0 )
{
    $array = array('Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado');
    return $array[$dia];
}

function set_number_format( $number = 0, $casas = 0 )
{
    return number_format($number, $casas, ',', '.');
}

function set_aprovado( $valor = FALSE )
{
    $retorno = 'Não Aprovado';
    if ( $valor )
    {
        $retorno = 'Aprovado';
    }
    return $retorno;
}

function set_btn_contrato($inscricao)
{
    $retorno = '<a href="http://www.portaisimobiliarios.com.br/contrato/contratoKK.php?inscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-primary" target="_blank">Contrato Imóveis</a>';
    $retorno .= '<a href="http://www.powinternet.com/contrato_powsites/contrato_reimpresso.php?iinscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-default" target="_blank">Contrato pow site</a>';
    $retorno .= '<a href="http://www.guiasjp.com/contrato.php?inscricao='.$inscricao.'&base=empresas_auxiliar" class="btn btn-primary" target="_blank">Contrato guiasjp</a>';
    return $retorno;
}

function set_valores_imovel($valor)
{
    // 0 = venda
    // 1 = locacao
    // 2 = locacao dia

    $valores = explode('-', $valor);
    $retorno = '<div class="row">';

    if ($valores[0] >= 0.1)
        $retorno = '<div class="col-sm-4">Venda: R$' . number_format($valores[0], 2, ',', '.') . '</div>';

    if ($valores[1] >= 0.1)
        $retorno .= '<div class="col-sm-4">Locação: R$' . number_format($valores[1], 2, ',', '.') . '</div>';

    if ($valores[2] >= 0.1)
        $retorno .= '<div class="col-sm-4">Locação dia: R$' . number_format($valores[2], 2, ',', '.') . '</div>';

    return $retorno;
}


function set_foto_imovel($valor, $id_empresa = NULL)
{
    if ( ! strstr($valor,'http') )
    {
        $valor = str_replace('codEmpresa',$id_empresa, URL_IMAGE_MUDOU).$valor;
    }
    $retorno = '<img src="' . $valor . '" class="img-responsive">';
    return $retorno;
}

function gera_image( $local, $arquivo, $propriedades, $width, $height, $crop = FALSE )
{
    $altura = $propriedades[0];
    if ( $height == 'auto' )
    {
        $height = ( $propriedades[1] / ( $propriedades[0] / $width ) );
    }
    if ( $propriedades[0] > $width || $propriedades[1] > $height )
    {
        $altera = TRUE;
    }
    else
    {
        $altera = FALSE;
    }
    //var_dump($height, $propriedades, $width); die();
    if ( $altera )
    {
        if ( $propriedades[0] > $propriedades[1] )
        {
            $proporcao = ceil($width / $propriedades[0] );
        }
        else
        {
            $proporcao = ceil($height / $propriedades[1] );
        }
        
        $width_src = $propriedades[0];
        $height_src = $propriedades[1];
        $width_fator = $crop ? ( ( $propriedades[0] - $width ) / 2 ) : 0;
        $height_fator = $crop ? ( ($propriedades[1] - $height) / 2 ) : 0;
        if ( ! $altera )
        {
            $width = $propriedades[0];
            $height = $propriedades[1];
        }
        $image_destino = imagecreatetruecolor($width,$height);
        switch( $propriedades['mime']){
                case 'image/gif':
                        $image = imagecreatefromgif($local);
                        break;
                case 'image/png':
                        $image = imagecreatefrompng($local);
                        break;
                case 'image/jpeg':
                default:
                        $image = imagecreatefromjpeg($local);
                        break;
        }
        if ( ! $crop )
        {
            $width_src = $width;
            $height_src = $height;
            $width_fator = 0;
            $height_fator = 0;
        }

        //var_dump($height, $width, $width_src, $height_src, $propriedades, $proporcao);
        //die();
        imagecopyresampled($image_destino,$image,0,0,0,0,$width,$height,$propriedades[0],$propriedades[1]);
        $arq = fopen($arquivo,'w');
        fclose($arq);
        switch($propriedades['mime']){
                case 'image/gif':
                        imagegif($image_destino, $arquivo);
                        break;
                case 'image/png':
                        imagepng($image_destino, $arquivo);
                        break;
                case 'image/jpeg':
                default:
                        imagejpeg($image_destino, $arquivo,100);
                        break;
        }
    }
    else
    {
        copy($local, $arquivo);
    }
    if ( file_exists($arquivo) && filesize($arquivo) > 2000  )
    {
        $retorno = TRUE;
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}


/**
 * retorna a imagem para exibição
 * @param int $id -> id do imóvel
 * @param string $arquivo -> arquivo a ser modificado
 * @param int $id_empresa -> codigo da empresa
 * @param bolean $mudou -> se a empresa já mudou de repositorio
 * @param string $fs -> 32bits md5 do arquivo
 * @param int $sequencia -> sequencia do arquivo - default 1
 * @param string $tipo -> tipo do arquivo, tm, t5, t3
 * @return string -> endereço completo do arquivo.
 */
function set_arquivo_image( $id, $arquivo, $id_empresa, $mudou = FALSE, $fs = '', $sequencia = 1, $tipo = 'TM')
{
    $endereco_base = '';
    if ( LOCALHOST )
    {
        $endereco_base = str_replace('admin2_0', 'portais_novo', base_url());
    }
    else
    {
        $endereco_base = str_replace('admin2_0/', '', base_url());
    }
    // 120 x 90 -> TM -> só faz da foto 1
    // 60 x 45 -> t3 -> faz todas
    // 300 -> T5_codimovel_numerodafoto -> faz todas
    // md5_file da original
    $array_tamanho = array(
                            'TM' => array('width' => '240', 'height' => '180', 'crop' => FALSE),
                            'T3' => array('width' => '120', 'height' => '90', 'crop' => FALSE),
                            'destaque' => array('width' => '300', 'height' => '225', 'crop' => FALSE),
                            'destaque_home' => array('width' => '208', 'height' => '160', 'crop' => FALSE),
                            'T5' => array('width' => '650', 'height' => 'auto', 'crop' => FALSE),
                            '650F' => array('width' => '900', 'height' => 'auto', 'crop' => FALSE),
                            );
    $nao_tem_sequencia = ( $tipo == 'TM' ) ? TRUE : FALSE;
    $pasta_local = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_LOCAL);
    $nome_arquivo = $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
    $nome_arquivo .= str_replace('.','',substr($arquivo, -4)); 
    $existe = $pasta_local.$nome_arquivo;
    $retorno = array('status' => TRUE, 'arquivo' => $arquivo, 'code' => 200);
    if ( ! is_dir($pasta_local) )
    {
        mkdir( $pasta_local, 0777, TRUE );
    }
    if (file_exists($existe) && filesize($existe) > 2000 )
    {
        $retorno['arquivo'] = $endereco_base.str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_BASE, 1)).$nome_arquivo;
    }
    else
    {
        if ( strstr( $arquivo, 'http' ) )
        {
            if ( strstr($tipo,'destaque') )
            {
                $curl = curl_executavel($arquivo);
                if ( $curl['info']['http_code'] == 200 )
                {
                    $propriedades_image = getimagesize($arquivo);
                    $tamanho = $array_tamanho[$tipo];
                    $gerou = gera_image($arquivo, $pasta_local.$nome_arquivo, $propriedades_image, $tamanho['width'], $tamanho['height'], $tamanho['crop']);
                    if ( $gerou )
                    {
                        $a = $endereco_base.str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_BASE, 1)).$nome_arquivo;
                        $retorno['arquivo'] = $a;
                    }
                    else
                    {
                        $a = $arquivo;
                        $retorno['arquivo'] = $a;
                    }
                }
                else
                {
                    $retorno['code'] = $curl['info']['http_code'];
                    if ( $curl['info']['http_code'] == 404 )
                    {
                        $retorno['status'] = FALSE;
                    }
                    $erro = 'Arquivo inacessivel: '.$arquivo.', id_empresa: '.$id_empresa.', id_imovel: '.$id.', em: '.date('Y-d-m').', status: '.$curl['info']['http_code'].', ip destino: '.$curl['info']['primary_ip'];
                    armazena_relatorio('images', $erro);
                }
            }
            else
            {
                $a = $arquivo;
                $retorno['arquivo'] = $a;
            }

        }
        else
        {
            if ( $tipo == '650F' && $mudou == 1 )
            {
                $a = ( ( $mudou == 1 ) ? str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU) : URL_IMAGE_NAO_MUDOU);
                $a .= $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
                $a .= str_replace('.','',substr($arquivo, -4));
                $retorno['arquivo'] = $a;
            }
            elseif ( $tipo == 'destaque' )
            {
                $a = ( ( $mudou == 1 ) ? str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU) : URL_IMAGE_NAO_MUDOU);
                $a .= $arquivo;
                $curl = curl_executavel($a);
                if ( $curl['info']['http_code'] == 200 )
                {
                    $propriedades_image = getimagesize($a);
                    $tamanho = $array_tamanho[$tipo];
                    $gerou = gera_image($a, $pasta_local.$nome_arquivo, $propriedades_image, $tamanho['width'], $tamanho['height'], $tamanho['crop']);
                    if ( $gerou )
                    {
                        $a_ = '/'.str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_LOCAL, 1)).$nome_arquivo;
                        if ( filesize($a_) > 2000 )
                        {
                            $a = $endereco_base.str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_BASE, 1)).$nome_arquivo;
                        }
                        else
                        {
                            $retorno['arquivo'] = $a;
                        }

                    }
                    else
                    {
                        
                    }
                }
                else
                {
                    $retorno['code'] = $curl['info']['http_code'];
                    if ( $curl['info']['http_code'] == 404 )
                    {
                        $retorno['status'] = FALSE;
                    }
                    $erro = 'Arquivo inacessivel: '.$a.', id_empresa: '.$id_empresa.', id_imovel: '.$id.', em: '.date('Y-d-m').', status: '.$curl['info']['http_code'].', ip destino: '.$curl['info']['primary_ip'];
                    armazena_relatorio('images', $erro);
                }
                $retorno['arquivo'] = $a;
            }
            else
            {
                $a = URL_IMAGE_MUDOU;
                $a .= $arquivo;
                $retorno['arquivo'] = $a;
            }
        }
    }
        
    return $retorno;
}

function armazena_relatorio( $tipo, $erro )
{
    switch( $tipo )
    {
        case 'images':
            $arquivo = 'erro_images';
            break;
    }
    $arquivo_debug = URL_RELATORIOS.$arquivo;
    $arq = fopen($arquivo_debug,'a');
    fwrite($arq, PHP_EOL.$erro);
    fclose($arq);
}

function curl_executavel($endereco) 
{
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $endereco);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 240);
    $retorno['item'] = curl_exec($ch);
    $retorno['erro'] = curl_errno( $ch );
    $retorno['erromsg']  = curl_error( $ch );
    $retorno['info']  = curl_getinfo( $ch );
    return $retorno;
}
