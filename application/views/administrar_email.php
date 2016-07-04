<?php 
$sequencia = 1;
?>
<div class="container-fluid administrar-email">
    <div class="item">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
            </div>
        </div>
    </div>
        <div class="alert alert-danger">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="editavel" id="editavel" class="form-control editavel" value="<?php echo $editavel;?>">
                    <input type="hidden" name="id" id="id" class="form-control id" value="<?php echo isset($item->id) ? $item->id : 0;?>">
                    <h3>
                        <button type="button" class="btn btn-default pull-right status_servico glyphicon glyphicon-download">
                            Status: <span class="status" data-item="<?php echo isset($item->id) ? $item->id : '';?>"><?php echo isset($item->id) ? ( $editavel ? 'Editando' : 'Verificando' ) : 'Novo';?></span>
                        </button>
                    </h3>
                </div>
                <hr>
            </div>
        </div>
        <div class="alert alert-info principal">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    $campo = array(
                                    'tipo' => 'textarea',
                                    'classe' => 'titulo',
                                    'sequencia' => $sequencia,
                                    'class' => '',
                                    'controller' => 'email_mkt',
                                    'tabela' => 'email_mkt',
                                    'valor' => set_value('titulo', ( isset($item->titulo) ? $item->titulo : '' ) ) ,
                                    'titulo' => 'Titulo',
                                );
                    echo set_campo_editavel($campo);
                    unset($campo);
                    $sequencia++;
                    ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
                    <?php
                    $campo = array(
                                    'tipo' => 'textarea',
                                    'classe' => 'descricao',
                                    'sequencia' => $sequencia,
                                    'class' => '',
                                    'valor' => set_value('descricao', ( isset($item->descricao) ? $item->descricao : '' ) ) ,
                                    'controller' => 'email_mkt',
                                    'tabela' => 'email_mkt',
                                    'titulo' => 'Descriçao',
                                );
                    echo set_campo_editavel($campo);
                    unset($campo);
                    $sequencia++;
                    ?>
                </div>
                
            </div>
        </div>
        
        <div class="itens hide">
            <ul class="nav nav-pills" role="tablist">
                <?php
                $li = '';
                $corpo = '';
                foreach( $abas as $aba ) :
                    ?>
                    <li role="presentation" class="abas <?php echo $aba->function;?> elemento-<?php echo $aba->id;?>" data-item="<?php echo $aba->function;?>">
                        <a href="#<?php echo $aba->function;?>" aria-controls="<?php echo $aba->function;?>" role="tab" data-toggle="tab"><?php echo $aba->descricao;;?></a>
                    </li>
                    <?php
                    $corpo .= '<div id="'.$aba->function.'" role="tabpanel" class="tab-pane '.$aba->function.'"></div>';
                endforeach;
                echo $li;
                ?>
            </ul>
            <div class="tab-content alert">
                <?php 
                echo $corpo;
                ?>
            </div>
        </div>
    </div>
</div>
      
    

<!-- Desenvolvimento e aperfeiçoamento da ferramenta de Administração de sites, publicidade, Notícias e conteúdo em geral do Pow Internet.  -->