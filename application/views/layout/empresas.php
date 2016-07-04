<!DOCTYPE html!>
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
    <header>
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Navegação Mobile</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">POW Internet</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <?php 
                if ( isset($menu) ) :
                    echo $menu;
                endif;
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="trabalhando"></li>
                    <li><a href="#" title="Edite seu perfil">Login: <?php echo isset($usuario) ? $usuario : '' ;?></a></li>
                    <li><a href="<?php echo base_url();?>login/logout">Sair</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </header>
    <div class="container-fluid">
        <div class="row">      
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <?php 
            echo $conteudo;
            ?>
            </div>
        </div>
    </div>
</body>
</html>