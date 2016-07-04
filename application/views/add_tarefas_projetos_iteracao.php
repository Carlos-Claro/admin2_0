<?php
if( ! (isset($item_iteracao) && $item_iteracao ) ) : 
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <?php
                $atributo = '';
                if ( isset($item_iteracao) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento-iteracao" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item_iteracao)) ? 1 : 0; ?>" data-tipo="iteracao" title="Salvar Iteração"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="message">Message</label>
                    <?php 
                    if ( isset($id_pai) && $id_pai ) :
                        ?>
                    <input type="hidden" name="id_pai" class="id_pai" value="<?php echo $id_pai;?>">
                        <?php
                    endif;
                    ?>
                    <textarea name="iteracao[<?php echo $ordem;?>][message]" class="form-control" <?php echo $atributo;?> id="message" ><?php echo set_value('iteracao['.$ordem.'][message]', isset($item_iteracao->message) ? $item_iteracao->message : ''); ?></textarea>
                    <p class="help-block message"></p>
                </div>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 usuarios">
                <label for="usuarios">Selecione os usuários a serem comunicados por email desta Iteração.</label>
                <p class="help-block usuarios"></p>
                <div class="controls ">
                    <?php 
                    $config['valor'] = $usuarios; 
                    $config['nome'] = 'usuarios'; 
                    $config['class'] = ' col-lg-3 col-sm-3 col-md-3 col-xs-4 '; 
                    echo form_checkbox_($config, set_value('usuarios', array() ) ,2 ); 
                ?>
                </div>
            </div>
        </div>
    </div>
<?php
else :
    ?>
    <li class="list-group-item elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <?php 
                foreach( $usuarios as $usuario )
                {
                    if ( $item_iteracao->id_usuario == $usuario->id )
                    {
                        echo $usuario->descricao; 
                    }
                }
                echo ' ('.$item_iteracao->data.') - ';
                echo $item_iteracao->message;
            ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <span class="responder btn pull-right" data-item="<?php echo $item_iteracao->id;?>">{ responder }</span>
            </div>
        </div>    
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                <span class="espaco-resposta"></span>
            </div>
        </div>    
    <?php 
    if ( $item_iteracao->qtde_respostas == 0 ) :
        ?>
        </li>
        <?php
    endif;
    ?>
        
    <?php
endif;
?>
