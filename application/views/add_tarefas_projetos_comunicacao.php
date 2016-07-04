<?php
if( (isset($edita) && $edita ) ) {
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="comunicacao" title="Deletar comunicação"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <?php
                $atributo = '';
                if ( isset($item) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="comunicacao" title="Editar comunicação"><span class="glyphicon glyphicon-plus-sign"></span></button>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="comunicacao" title="Salvar comunicação"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>  
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-10">
                <div class="form-group">
                    <label for="frequencia">Frequência</label>
                    <?php
                    $config['valor'] = get_select_frequencia();
                    $config['nome']  = 'comunicacao['.$ordem.'][frequencia]';
                    $config['extra'] = 'id="frequencia" '.$atributo;
                    echo form_select($config, set_value('frequencia', isset($item->frequencia) ? $item->frequencia : ''));
                    ?>
                    <p class="help-block frequencia"></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="form-group">
                    <label for="metodo">Método</label>
                    <input name="comunicacao[<?php echo $ordem; ?>][metodo]" id="metodo" type="text" class="form-control" value="<?php echo set_value('comunicacao['.$ordem.']', isset($item->metodo) ? $item->metodo : ''); ?>" <?php echo $atributo;?>>
                     <p class="help-block metodo"></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="form-group">
                    <label for="responsavel">Responsável</label>
                    <?php
                    $config['valor'] = $usuarios;
                    $config['nome']  = 'comunicacao['.$ordem.'][id_responsavel]';
                    $config['extra'] = 'id="id_responsavel" '.$atributo;   
                    echo form_select($config , set_value('id_responsavel', isset($item->id_responsavel) ? $item->id_responsavel : ''));
                   ?>
                    <p class="help-block responsavel"></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="objetivo">Objetivo</label>
                    <textarea name="comunicacao[<?php echo $ordem; ?>][objetivo]" id="objetivo" class="form-control"  <?php echo $atributo;?>><?php echo set_value('comunicacao['.$ordem.']', isset($item->objetivo) ? $item->objetivo : ''); ?></textarea>
                    <p class="help-block objetivo"></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="informacao">Informação</label>
                    <textarea name="comunicacao[<?php echo $ordem; ?>][informacao]" id="informacao"  class="form-control" <?php echo $atributo;?>><?php echo set_value('comunicacao['.$ordem.']', isset($item->informacao) ? $item->informacao : ''); ?></textarea>
                    <p class="help-block informacao"></p>
                </div>
            </div>
         </div>
    </div>
<?php
}else{
    ?>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-10">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Frequência
                        </h4>
                        <p>
                            <?php 
                            foreach( get_select_frequencia() as $frequencia )
                            {
                                if ( $item->frequencia == $frequencia->id )
                                {
                                    echo $frequencia->descricao; 
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Método
                        </h4>
                        <p>
                            <?php 
                            echo $item->metodo;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Responsável
                        </h4>
                        <p>
                            <?php 
                            foreach( $usuarios as $usuario )
                            {
                                if ( $item->id_responsavel == $usuario->id )
                                {
                                    echo $usuario->descricao; 
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Objetivo
                        </h4>
                        <p>
                            <?php 
                            echo $item->objetivo;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Informação
                        </h4>
                        <p>
                            <?php 
                            echo $item->informacao;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
         </div>
    </div>
    <?php
                                }
?>