<html lang=pt-br>
<head>
    <meta charset="UTF-8" >
    <meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
    <meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
    <meta name="author" content="Carlos Claro - http://www.carlosclaro.com.br / PowInternet - http://www.pow.com.br" />
    <title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
<?php 
    echo ( isset($includes) ? $includes : '' );
?>
</head>
<body>
   <?php echo $tabela; ?>
</body>
</html>