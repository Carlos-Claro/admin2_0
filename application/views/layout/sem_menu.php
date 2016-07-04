<!DOCTYPE html!>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
	<meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
	<meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
        <meta name="author" content="POWImoveis - <comercial@pow.com.br>" />
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
<?php 
	echo ( isset($includes) ? $includes : '' );
?>
</head>
<body>
    <div class="container text-center">      
        <?php 
        echo $conteudo;
        ?>
    </div>
</body>
</html>