<div class="container-fluid">
<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <div class="form-group">
            <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
            <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        </div>
        
    </div>
</div>

    <?php if (  ( isset($item) && $item ) ) : ?>
        <div class="alert bg-warning">
            <div class="hide id-tarefa" data-item="<?php echo $item['item']->id;?>"></div>
            <div class="hide id-usuario" data-item="<?php echo $item['item']->id_usuario;?>"></div>
            <div class="hide id-usuario-sessao" data-item="<?php echo $id_usuario;?>"></div>
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 pull-left">
                    <h3 class="text-muted " ><strong>Projeto: </strong><?php echo $tarefas_projeto->titulo;?></h3>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 pull-left">
                    Tarefa: <h2 class="text-muted <?php echo ( ($item['item']->id_usuario == $id_usuario) && $item['item']->id_tarefa_status == 1 ) ? 'editavel' : '';?> elemento-titulo" data-item="titulo" data-valor="<?php echo $item['item']->titulo;?>"><?php echo $item['item']->titulo;?></h2>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-3 pull-right ">
                    <h3 class="pull-left text-right hide">Id: <?php echo $item['item']->id;?></h3>
                    <?php 
                    if ( array_key_exists($id_usuario, $item['usuarios']) && $item['item']->id_tarefa_status == 1 ) :
                        ?>
                        <!--  <button class="col-lg-6 col-sm-6 col-md-6 col-xs-6 btn btn-default pull-right trabalhar-tarefa" title="trabalhar nesta tarefa geral." data-id="<?php //echo $item['item']->id;?>"><span class="glyphicon glyphicon-play" aria-hidden="true"></span></button> -->
                        <button class="col-lg-6 col-sm-6 col-md-6 col-xs-6 btn btn-default pull-right fechar-tarefa" title="fechar tarefa e suas atividades" data-id="<?php echo $item['item']->id;?>"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></button>
                        <?php 
                    elseif( ( $item['item']->id_tarefa_status == 2 ) && ( $item['item']->id_usuario == $id_usuario ) ) :
                        ?>
                        <button class="col-lg-6 col-sm-6 col-md-6 col-xs-6 btn btn-success pull-right reabrir-tarefa" title="Reabrir a tarefa " data-id="<?php echo $item['item']->id;?>"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></button>
                            
                        <?php 
                    endif;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-3 col-md-3 col-xs-4">
                    <h2><strong>Status: </strong></h2><h4><?php echo '<span class="" data-item="status" data-valor="'.$item['item']->id_tarefa_status.'">'.$item['item']->tarefa_status.'</span>';?></h4>
                </div>
                <div class="col-lg-9 col-sm-9 col-md-9 col-xs-8">
                    <h2 class="col-lg-12 col-sm-12 col-md-12 col-xs-12">Prazos:</h2>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <h4><strong>Em Horas: </strong><?php echo '<span class="'.( ( $item['item']->id_usuario == $id_usuario && $item['item']->id_tarefa_status == 1 ) ? 'editavel' : '' ) .' elemento-previsao_horas" data-item="previsao_horas" data-valor="'.$item['item']->previsao_horas.'">'.$item['item']->previsao_horas.'</span>';?> hs.</h4>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                        <h4><strong>Inicio: </strong><?php echo '<span class="'.( ( $item['item']->id_usuario == $id_usuario && $item['item']->id_tarefa_status == 1 ) ? 'editavel' : '' ) .' elemento-data_inicio" data-item="data_inicio" data-valor="'.$item['item']->data_inicio.'">'.$item['item']->data_inicio.'</span>';?></h4>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                        <h4><strong>Fim: </strong><?php echo '<span class="'.( ( $item['item']->id_usuario == $id_usuario && $item['item']->id_tarefa_status == 1 ) ? 'editavel' : '' ) .' elemento-data_fim" data-item="data_fim" data-valor="'.$item['item']->data_fim.'">'.$item['item']->data_fim.'</span>';?></h4>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h4><strong>Descrição:</strong></h4>
                    <p><?php echo '<span class="'.( ( $item['item']->id_usuario == $id_usuario && $item['item']->id_tarefa_status == 1 ) ? 'editavel' : '' ) .' elemento-descricao" data-item="descricao" data-valor="'.$item['item']->descricao.'">'.nl2br($item['item']->descricao).'</span>';?></p>
                </div>
            </div>
        </div>
    
        <div class="row">
        <?php 
        if ( isset($item['empresas']) && $item['empresas'] ) :
            ?>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                    <div class="alert bg-info">

                        <h5 class="">Empresas Envolvidas</h5>
                        <div class="empresas editavel" data-item="empresas">
                            <?php 
                            foreach( $item['empresas'] as $empresa ) :
                                echo '<div class="empresa btn btn-default" data-item="'.$empresa->id.'"><a href="'.( base_url().'empresas/editar/'.$item['item']->id ).'" title="veja os dados da empresa." target="_blank">'.$empresa->descricao.'</a></div>';
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>

            <?php
        endif;
        if ( isset($item['usuarios']) && $item['usuarios'] ) :
            ?>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                    <div class="alert bg-info">
                        <h5 class="">Usuarios envolvidos</h5>
                        <div class="usuarios editavel" data-item="usuarios">
                            <p class="help-block"></p>
                            <?php 
                            foreach( $item['usuarios'] as $id => $descricao ) :
                                ?>
                            <div class="usuario btn btn-default usuario-<?php echo $id;?>" data-item="<?php echo $id;?>"><span class="nome-usuario"><?php echo $descricao;?></span>&nbsp;&nbsp;&nbsp;X</div>
                                <?php
                            endforeach;
                            ?>
                            <div class="novo-usuario btn btn-default " ><span class="glyphicon glyphicon-plus"></span></div>
                        </div>
                    </div>
                    <div class="espaco-novo-usuario">
                        
                    </div>
                </div>
            <?php
        endif;
        ?>
        </div>
        <div class="alert bg-warning">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h2>Existem <span class="qtde-atividades"><?php echo ( isset($item['atividades']['itens']) && $item['atividades']['qtde'] > 0 ) ? $item['atividades']['qtde'] : 0; ?></span>  Atividades:</h2>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="erro-atividade"></div>
                    <div class="espaco-atividade alert"></div>
                    <button class="btn btn-success add-atividades" data-item="<?php echo $item['item']->id;?>">Adicionar Atividades</button>
                    <br><br>
                </div>
                <?php 
                    ?>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <ul class="atividades list-unstyled">
                    <?php 
                if ( isset($item['atividades']['itens']) && $item['atividades']['qtde'] > 0  ) :
                    foreach( $item['atividades']['itens'] as $atividade ) :
                        ?>
                    <li class="alert alert-<?php echo ($atividade->id_tarefa_status == 1 ) ? 'info' : 'danger';?> atividade elemento-<?php echo $atividade->id;?>" id="<?php echo $atividade->id;?>">
                        <div class="row">
                            <p class="col-lg-11 col-sm-11 col-md-11 col-xs-11 pull-left font-maior"><?php echo $atividade->id . ' - ' . nl2br($atividade->descricao);?></p>
                            <?php 
                            if ( ($atividade->id_usuario == $id_usuario) && $atividade->id_tarefa_status == 1 ) :
                                echo '<button type="button" class="col-lg-1 col-sm-1 col-md-1 col-xs-1 close deleta-atividade pull-right" data-id="'.$atividade->id.'" aria-label="Close" tiitle="deletar atividade"><span aria-hidden="true">&times;</span></button>';
                            endif;
                            ?>
                            <p class="col-lg-6 col-sm-6 col-md-6 col-xs-6 pull-left" >Previsão: <?php echo $atividade->previsao_tempo;?> hs. - Data Limite: <?php echo $atividade->data_fim;?></p>
                            <p class="col-lg-5 col-sm-5 col-md-5 col-xs-5 pull-left" >Usuarios da tarefa: 
                            <?php 
                            if ( isset($atividade->usuarios) && count($atividade->usuarios) > 0 ) :
                                foreach( $atividade->usuarios as $usuarios ) :
                                    $usuarios_atividade[$usuarios->id] = $usuarios->id;
                                    echo $usuarios->descricao.', ';
                                endforeach;
                            else :
                                    $usuarios_atividade = array();
                            endif;
                            ?></p>
                            <div class="pull-right col-lg-1 col-sm-1 col-md-1 col-xs-1 controles">
                            <?php 
                            //array_key_exists($id_usuario, $item['usuarios']) || 
                            //if ( isset($usuarios_tarefa) ) :
                                if ( ( $atividade->id_tarefa_status == 1 ) && ( $atividade->id_usuario == $id_usuario || ( array_key_exists($id_usuario, $usuarios_atividade) || array_key_exists($id_usuario, $item['usuarios']) ) ) ) :
                                    ?>
                                <button class="tempo-atividade-<?php echo $atividade->id;?> tempo-tarefas-<?php echo $atividade->id_tarefas;?> btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 trabalhar-atividade" data-id="<?php echo $item['item']->id;?>" data-id-atividade="<?php echo $atividade->id;?>" title="trabalhar nesta atividade"><span class="glyphicon glyphicon-play" aria-hidden="true"></span></button>
                                <button class="btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 fechar-atividade" data-id="<?php echo $item['item']->id;?>" data-id-atividade="<?php echo $atividade->id;?>" title="Fechar esta atividade"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></button>
                                    <?php 
                                endif;
                            //endif;
                            ?>
                            </div>
                        </div>
                        <div class="row">
                            <button class="pull-left col-lg-1 col-sm-1 col-md-1 col-xs-1 btn btn-default pull-left ver-interacoes" data-id="<?php echo $item['item']->id;?>" data-id-atividade="<?php echo $atividade->id;?>" title="ver Interação"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

                            <h3 class="pull-right col-lg-11 col-sm-11 col-md-11 col-xs-11">Interacoes</h3>

                            <hr>
                            <div class="hide interacoes-<?php echo $atividade->id;?> col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="erro-interacoes"></div>
                                <ul class="interacoes">
                                <?php 
                                if ( isset( $atividade->interacoes ) && count($atividade->interacoes) > 0 ) :
                                    foreach( $atividade->interacoes as $interacoes ) :
                                    ?>
                                    <li class=""><?php echo $interacoes->id . ' - ' . $interacoes->descricao.' - '.$interacoes->data.' - '.$interacoes->usuario;?></li>
                                    <?php
                                    endforeach;
                                endif; 
                                ?>
                                </ul>
                                <?php 
                                if ( $atividade->id_tarefa_status == 1 ) :
                                ?>
                                <button class="btn btn-default adicionar-interacoes" data-id="<?php echo $item['item']->id;?>" data-id-atividade="<?php echo $atividade->id;?>" title="Adicionar Interação">Adicionar interação</button>
                                <?php endif; ?>
                                <div class="espaco-interacoes"></div>
                            </div>
                        </div>
                    </li>
                        <?php
                    endforeach;
                endif;
                    ?>
                    </ul>
                </div><?php 
                ?>
                
            </div>
        </div>

        
    <?php else : ?>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" id="form-tarefa" >
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group Projeto">
                    <label for="titulo">Projeto</label>
                    <h3><?php echo $tarefas_projeto->titulo;?></h3>
                    <input type="hidden" name="id_tarefas_projeto" value="<?php echo $id_tarefas_projeto;?>">
                    <p class="help-block id_tarefas_projeto"></p>
                </div>
            </div>  
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group titulo">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control titulo" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : ( isset($inicia['titulo']) ? $inicia['titulo'] : '' ) );?>">
                    <p class="help-block titulo"></p>
                </div>
            </div>  
            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 id_tarefa_status">
                <label for="id_tarefas_status">Status</label>
                <div class="controls ">
                    <?php 
                    $config['valor'] = $status; 
                    $config['nome'] = 'id_tarefas_status'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_tarefas_status', isset($item->id_tarefas_status) ? $item->id_tarefas_status : '1')); 
                ?>
                </div>
                <p class="help-block id_tarefa_status"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group data_inicio">
                    <label for="data_inicio">Data Previsão Inicio</label>
                    <div class="input-group date date-time-picker" id="data-previsao-inicio">
                        <input name="data_inicio" type="text" data-date-format="DD/MM/YYYY HH:mm" class="form-control" id="data_inicio" placeholder="Previsão Inicio"  value="<?php echo set_value('data_inicio', isset($item->data_inicio ) ? $item->data_inicio : '');?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <!--<input name="data_inicio" type="text" class="form-control data_hora" id="data_inicio" placeholder="Previsão Inicio"  value="<?php //echo set_value('data_inicio', isset($item->data_inicio ) ? $item->data_inicio : '');?>">
                    <p class="help-block data_inicio"></p> -->
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group data_fim">
                    <label for="data_fim">Data Previsão Fim</label>
                    <div class="input-group date date-time-picker" id="data-previsao-fim">
                        <input name="data_fim" type="text" data-date-format="DD/MM/YYYY HH:mm" class="form-control" id="data_fim" placeholder="Data Previsão Fim"  value="<?php echo set_value('data_fim', isset($item->data_fim ) ? $item->data : ( isset($inicia['data_fim']) ? $inicia['data_fim'] : '' ));?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <!--<input name="data_fim" type="text" class="form-control data_hora" id="data_fim" placeholder="Data Previsão Fim"  value="<?php //echo set_value('data_fim', isset($item->data_fim ) ? $item->data : '');?>">
                    <p class="help-block data_fim"></p>-->
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group previsao_horas">
                    <label for="previsao_horas">Previsão em horas</label>
                    <input name="previsao_horas" type="text" class="form-control" id="previsao_horas" placeholder="Previsão em horas" value="<?php echo set_value('previsao_horas', isset($item->previsao_horas) ? $item->previsao_horas : '');?>">
                    <p class="help-block previsao_horas"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group descricao">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descrição"><?php echo set_value('descricao', isset($item->descricao) ? $item->descricao : ( isset($inicia['descricao']) ? $inicia['descricao'] : '' ));?></textarea>
                    <p class="help-block descricao"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 usuarios">
                <label for="usuarios">Selecione os usuários envolvidos</label>
                <p class="help-block usuarios"></p>
                <div class="controls ">
                    <?php 
                    $config['valor'] = $usuarios; 
                    $config['nome'] = 'usuarios'; 
                    $config['class'] = ' col-lg-3 col-sm-3 col-md-3 col-xs-4 '; 
                    echo form_checkbox_($config, set_value('usuarios', isset($usuarios_selecionados) ? $usuarios_selecionados : array() ),2 ); 
                ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-2 col-lg-offset-10 col-sm-2  col-sm-offset-10 col-md-3  col-md-offset-9 col-xs-6  col-xs-offset-6">  
                <button type="submit" class="btn btn-warning">Salvar</button>
            </div>
        </div>
        
    </div>
</form>
<?php endif; ?>

 </div>