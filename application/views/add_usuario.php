<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
        <div class="alert">
            <div class="row">
                <div class="form-group">
                    <label for="id">ID</label>
                    <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                <button class="btn btn-primary cronograma-requisita" type="button">Ver cronograma</button>
                
            </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 hide espaco-cronograma"></div>
        </div>
    </div>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="E-mail" required value="<?php echo set_value('email', isset($item->email ) ? $item->email : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input name="telefone" type="text" class="form-control telefone" id="telefone" placeholder="Telefone" required value="<?php echo set_value('telefone', isset($item->telefone ) ? $item->telefone : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input name="senha" type="password" class="form-control" id="senha" placeholder="Senha" >
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="resenha">Redigite a Senha</label>
                    <input name="resenha" type="password" class="form-control" id="resenha" placeholder="Redigite a Senha">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                 <div class="form-group">
                    <label for="id_empresa">Empresa</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $empresas; 
                        $config['nome'] = 'id_empresa'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_empresa', isset($item->id_empresa) ? $item->id_empresa : '')); 
                        ?>
                    </div>
                </div>	
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label>Cargos</label>
                <div class="form-group">
                    <?php foreach($cargos as $cargo): ?>
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="cargos[]" id="cargos[]" value="<?php echo $cargo->id; ?>" <?php echo (isset($cargos_selecionados) && array_key_exists($cargo->id, $cargos_selecionados) ) ? 'checked="checked"' : ''; ?> ><?php echo $cargo->descricao; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
    <?php 
    if ( ! isset($self) ) :
        ?>
            <div class="form-group ">
                <label for="data_cadastro">Data Cadastro</label>
                <input name="data_cadastro" type="text" class="form-control data_hora" id="data_hora" placeholder="Data Cadastro" required value="<?php echo set_value('data_cadastro', isset($item->data_cadastro ) ? $item->data_cadastro : date('Y-m-d H:i:s'));?>">
            </div>
            <div class="form-group">
                <label for="observacao">Observação</label>
                <textarea class="form-control ckeditor" id="observacao" rows="3"><?php echo set_value('observacao', isset($item->observacao ) ? $item->observacao : '');?></textarea>
                <?php echo display_ckeditor($ckeditor_observacao);?>
            </div>
            <div class="checkbox">
                <label>
                  <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
                </label>
            </div>
            <?php 
            if ( isset($item->id) ) :
            ?>
            <div class="form-group">
                <label for="setores">Setores</label>
                <div class="mensagens"></div>
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                        <h4>Setores do sistema: </h4>
                        <?php 
                        if ( isset($setores) && (count($setores) > 0) ) :
                            foreach ( $setores as $s ):
                            ?>
                                <div data-item="<?php echo $s->id;?>" class="alert alert-info setores col-lg-4"><?php echo $s->descricao;?></div>
                            <?php 
                            endforeach;
                        endif;
                        ?>

                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                        <h4>Setores com acesso liberado, Clique no checkbox para liberar edição ao setor.</h4>
                        <div class="selecionados">

                            <?php 
                            if ( isset($selecionados) && (count($selecionados) > 0) ) :
                                foreach ( $selecionados as $sel ):
                                ?>
                                    <div class="row form-group alert alert-success selecionado-<?php echo $sel->id;?>" data-item="<?php echo $sel->id;?>" id="<?php echo $sel->id;?>">
                                        <label class="pull-left col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <?php echo $sel->descricao;?>
                                        </label>
                                        <input type="checkbox" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 checkbox pull-left form-group setor-<?php echo $sel->id;?>" data-setor="<?php echo $sel->id;?>" data-usuario="<?php echo $sel->id;?>" value="1" <?php echo ( isset($sel->edita) && $sel->edita) ? 'checked="checked"' : '';?>" title="Pode Editar">
                                        <div class="close pull-right col-lg-2 col-md-2 col-sm-2 col-xs-2" data-item="<?php echo $sel->id;?>" >&times;</div>
                                    </div>
                                <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                </div>

            </div> <!-- .form-group setores -->
        <?php 
        endif;
    else :
        ?>
            <input name="sel" type="hidden" value="self">
        <?php 
    endif;
    ?>
        </div> 
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>
 