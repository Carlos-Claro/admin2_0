<?php
if( (isset($edita) && $edita ) ) :
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="has_usuarios" title="Deletar Usuário"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <?php
                $atributo = '';
                if ( isset($item) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="has_usuarios" title="Editar Usuário"><span class="glyphicon glyphicon-plus-sign"></span></button>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="has_usuarios" title="Salvar Usuário"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>  
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="form-group id_usuario">
                    <label for="usuarios">Usuário</label>
                    <?php
                    $config['valor'] = $usuarios;
                    $config['nome']  = 'has_usuarios['.$ordem.'][id_usuario]';
                    $config['extra'] = 'id="id_usuario" '.$atributo;
                    echo form_select($config , set_value('id_usuario', isset($item->id_usuario) ? $item->id_usuario : ''));                              
                    ?>
                    <p class="help-block id_usuario"></p>
                </div>
             </div>
            <div class="col-lg-6 com-md-6 col-sm-6 col-xs-10">
                <div class="form-group">
                    <label>Papel</label>
                    <input name="has_usuarios[<?php echo $ordem;?>][papel]" type="text" id="papel"  class="form-control papel" value="<?php echo set_value('has_usuarios['.$ordem.'][papel]', isset($item->papel) ? $item->papel : ''); ?>" <?php echo $atributo;?>>
                    <p class="help-block usuarios-papel "></p>
                </div>
            </div>  
        </div>      
    </div>
<?php 
else :
    ?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Usuário
                        </h4>
                        <p>
                            <?php 
                            foreach( $usuarios as $usuario )
                            {
                                if ( $item->id_usuario == $usuario->id )
                                {
                                    echo $usuario->descricao; 
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
             </div>
            <div class="col-lg-4 com-md-4 col-sm-4 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Papel
                        </h4>
                        <p>
                            <?php 
                            echo $item->papel;
                            ?>
                        </p>
                    </div>
                </div>
            </div>  
            <div class="col-lg-4 com-md-4 col-sm-4 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Email
                        </h4>
                        <p>
                            <?php 
                            echo $item->email;
                            ?>
                        </p>
                    </div>
                </div>
            </div>  
        </div>      
    </div>
    <?php
endif;
?>