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
                <a class="navbar-brand" href="<?php echo base_url().'painel/'?>">POW Internet</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <?php 
                if ( isset($menu) ) :
                    echo $menu;
                endif;
                ?>
                <?php /*
                <ul class="nav navbar-nav">
                    <li class="dropdown <?php echo ( $classe == 'usuario' ) ? 'active': ''; ?> ">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuário <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url();?>usuario/listar">Lista</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ( $classe == 'setor' ) ? 'active': ''; ?> ">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setores <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url();?>setor/listar">Lista</a></li>
                        </ul>
                    </li>
                </ul>
              */?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="agenda">
                        <a href="<?php echo base_url();?>tarefas/agenda" title="Acesse sua agenda" class="btn btn-default btn-lg">
                            <span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge qtde_tarefas_hoje"></span>
                        </a>
                    </li>
                    <li class="ocorrencias">
                        <a href="<?php echo base_url();?>ocorrencias/agenda" title="Acesse sua agenda de ocorrencias" class="btn btn-default btn-lg">
                            <span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge qtde_ocorrencias_hoje"></span>
                        </a>
                    </li>
                    <li class="trabalhando"></li>
                    <li><a href="<?php echo base_url();?>usuario/edita_perfil" title="Edite seu perfil">Login: <?php echo isset($usuario) ? $usuario : '' ;?></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ajuda <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Saiba Mais</a></li>
                            <li class="divider-vertical"></li>
                            <li><a href="#">Contate o Administrador</a></li>
                            <li><a href="#" target="_blank">Tutorial de Ocorrências</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url();?>login/logout">Sair</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
        <?php echo isset($breadscrumbs) ? $breadscrumbs : ''; ?>
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