<?php
if( (isset($edita) && $edita ) ) : 
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="qualidade" title="Deletar Qualidade"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <?php
                $atributo = '';
                if ( isset($item) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="qualidade" title="Editar Qualidade"><span class="glyphicon glyphicon-plus-sign"></span></button>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="qualidade" title="Salvar Qualidade"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>  
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="form-group">
                    <label for="frequencia">Frequência medição: </label>
                    <?php
                    $config['valor'] = get_select_frequencia();
                    $config['nome']  = 'qualidade['.$ordem.'][frequencia]';
                    $config['extra'] = 'id="frequencia" '.$atributo;
                    echo form_select($config, set_value('frequencia', isset($item->frequencia) ? $item->frequencia : ''));
                    ?>
                    <p class="help-block frequencia"></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="form-group">
                    <label for="metodo">Método de Coleta das informações</label>
                    <input name="qualidade[<?php echo $ordem;?>][metodo]" id="metodo" type="text" class="form-control" value="<?php echo set_value('qualidade['.$ordem.'][metodo]', isset($item->metodo) ? $item->metodo : ''); ?>" <?php echo $atributo;?>>
                    <p class="help-block metodo"></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                <div class="form-group">
                    <label>Responsável</label>
                    <?php
                    $config['valor'] = $usuarios;
                    $config['nome']  = 'qualidade['.$ordem.'][id_responsavel]';
                    $config['extra'] = 'id="id_responsavel" '.$atributo;
                    echo form_select($config, set_value('id_responsavel', isset($item->id_responsavel) ? $item->id_responsavel : ''));
                    ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Indicadores</label>
                    <textarea name="qualidade[<?php echo $ordem;?>][indicador]" id="indicador"  class="form-control" <?php echo $atributo;?>><?php echo set_value('qualidade['.$ordem.'][indicador]', isset($item->indicador) ? $item->indicador : ''); ?></textarea>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Armazenamento</label>
                    <textarea name="qualidade[<?php echo $ordem; ?>][armazenamento]" id="armazenamento"  class="form-control" <?php echo $atributo;?>><?php echo set_value('qualidade['.$ordem.'][armazenamento]', isset($item->armazenamento) ? $item->armazenamento : ''); ?></textarea>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Interpretação</label>
                    <textarea name="qualidade[<?php  echo $ordem ;?>][interpretacao]" id="interpretacao" class="form-control" <?php echo $atributo;?>><?php echo set_value('qualidade['.$ordem.'][interpretacao]', isset($item->interpretacao) ? $item->interpretacao : ''); ?></textarea>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Meta</label>
                    <textarea name="qualidade[<?php echo $ordem; ?>][meta]" id="meta" class="form-control" <?php echo $atributo;?>><?php echo set_value('qualidade['.$ordem.'][meta]', isset($item->meta) ? $item->meta : '');?></textarea>
                </div>
            </div>
        </div>
    </div>
<?php
else :
    ?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Frequência medição
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
                            Método de Coleta das informações
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Indicadores
                        </h4>
                        <p>
                            <?php 
                            echo $item->indicador;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Armazenamento
                        </h4>
                        <p>
                            <?php 
                            echo $item->armazenamento;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Interpretação
                        </h4>
                        <p>
                            <?php 
                            echo $item->interpretacao;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Meta
                        </h4>
                        <p>
                            <?php 
                            echo $item->meta;
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
