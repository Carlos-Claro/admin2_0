<!DOCTYPE html!>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
        <meta name="author" content="POWImoveis - <comercial@pow.com.br>" />
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
        <link type="text/css" href="http://www.guiasjp.com/admin2_0/css/bootstrap.css" rel="stylesheet">
<?php 
	echo ( isset($includes) ? $includes : '' );
        //var_dump($logo, $cidade);
?>
</head>
<body>
    <div class="container text-center">     
<table style="width:760px; margin:0 auto;">
    <tr>
        <td>
    <center><a href="<?php echo strtolower($cidade['portal']);?>"><img src="<?php echo strtolower($cidade['portal']).'/imagens/'.$logo;?>"></a></center>
        </td>
    </tr>
    <tr>
        <td>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 style="text-align: center;"><?php echo $titulo;?></h2>
                        <center><a href="<?php echo strtolower($cidade['portal']).'/';?>" class="btn btn-lg btn-primary " target="_blank">CLIQUE AQUI PARA BUSCAR MAIS IMÓVEIS NO PORTAL   <?php echo substr($cidade['portal'], 11)?> </a></center>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>
</table>
<style type="text/css">
    .thumbnail {
        margin-left: 5px;
    }
    .thumbnail img {
        height: auto;
        max-height: 200px;
        width: 100%;
    }
</style>
<table style="width:680px; margin:0 auto;">
    <?php
    if ( isset($imoveis) && count($imoveis) > 0 ) :
        $a = 0;
        foreach ( $imoveis as $item ) :
            if ( $a == 0 || $a == 2 || $a == 4 || $a == 6 ) :
                echo '<tr>';
            endif;
            ?>
            <td>
                <?php 
                echo $item;?>
            </td>
            <?php
            if ( $a == 1 || $a == 3 || $a == 5 || $a == 7 ) :
                echo '</tr><tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>';
            endif;
            $a++;
        endforeach;
    endif;
    ?>
</table>
<div class="divider-modo1"></div>

<table style="width:680px; margin:0 auto;">
    <tr>
        <td>
            <?php 
            
            if ( isset($link_tipo) ) :
                $url = $link_tipo['url'];
                unset($link_tipo['url']);
                ?>
            <center >
                <?php
                $conta = 0;
                $qtde_tipo = 5;
                foreach ( $link_tipo as $chave => $valor ): 
                    if ( isset($tipo_consulta) )
                    {
                        if ( $tipo_consulta != $chave )
                        {
                            $valor['itens'] = array();
                        }
                        else
                        {
                            $qtde_tipo = 8;
                        }
                    }
                    if ( count($valor['itens']) > 0 && $conta <= 1 ):
                        ?>
                        <h2>+ Imóveis para <?php echo $valor['titulo'];?> em <?php echo $cidade['titulo'];?></h2>
                                    <?php 
                                    $c_o = 0;
                                    foreach($valor['itens'] as $v):
                                        $array = array('[oq]','[tipo]');
                                        $array_b = array($chave,$v->link);
                                        if ( $c_o < $qtde_tipo )
                                        echo '<a href="'.  str_replace($array, $array_b, $url).'" title="'.$valor['titulo'].' '.$v->descricao.' '.$cidade['titulo'].'" class="btn btn-default" style="margin-left:5px; margin-bottom:5px;">'.$v->descricao.' <br> + '.  number_format(round(($v->qtde),-1),0,',','.').' Imóveis</a>';
                                        
                                        $c_o++;
                                    endforeach;
                                    ?>
                                    </ul>
                                </li>
                            </ul>
                        </li> 
                        <?php
                        $conta++;
                    endif;
                endforeach;
            endif;
            ?>
            </center>
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center><a href="<?php echo base_url();?>" class="btn btn-lg btn-primary " target="_blank" style="margin-bottom: 15px;">TEMOS O IMÓVEL QUE VOCÊ BUSCA NO PORTAL <?php echo substr($cidade['portal'], 11)?>, CLIQUE E CONFIRA </a></center>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center>Olá, <?php echo $contato->nome.' ('.$contato->email.')';?>,<br> Você esta recebendo este e-mail, pois autorizou o portal <?php echo substr($cidade['portal'], 11)?>,<br> em contato que realizou na ficha de imóveis do seu interresse nos ultimos 30 dias.</center>
                        <br><br><center>Este é um serviço <a href="http://www.powinternet.com.br">Pow Internet </a></center>
                        <br><br><br><center><span class="alert alert-success"><a href="%REMOVER%">Não quero mais receber este E-mail</a></span></center>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>



</div>
</body>
</html>