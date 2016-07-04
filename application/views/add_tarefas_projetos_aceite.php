<?php 
if( (isset($edita) && $edita ) ) {
?> 
<div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
    <div class="row">
        <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
            <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="aceite" title="Deletar Criterio de aceite"><span class="glyphicon glyphicon-minus-sign"></span></button>
            <?php
            $atributo = '';
            if ( isset($item) ) :
                $atributo = 'disabled="disabled"';
                ?>
                <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="aceite" title="Editar Criterio de aceite"><span class="glyphicon glyphicon-plus-sign"></span></button>
                <button type="button" class="btn  btn-default btn-lg tarefa-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="aceite" title="Adicionar tarefa"><span class="glyphicon glyphicon-asterisk"></span></button>
                <?php
            else :
                ?>
                <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="aceite" title="Salvar Criterio de aceite"><span class="glyphicon glyphicon-save"></span></button>
                <button type="button" class="hide btn  btn-default btn-lg tarefa-elemento" data-elemento="" data-salvo="" data-tipo="aceite" title="Adicionar tarefa"><span class="glyphicon glyphicon-asterisk"></span></button>
                <?php
            endif;
            ?>
        </div>  
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            <div class="form-group criterios-aceite-data-medida">
                <label for="aceite">Data Medida (YYYY-mm-dd)</label>
                <input name="aceite[<?php echo $ordem; ?>][data_medida]" id="data_medida" type="text" class="form-control data_db criterios-aceite-data-medida" value="<?php echo set_value('aceite['.$ordem.'][data_medida]', isset($item->data_medida) ? $item->data_medida : ''); ?>" <?php echo $atributo;?>>
                <p class="help-block criterios-aceite-data-medida"></p>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group criterios-aceite-id-responsavel">
                <label>Responsável</label>
                <?php
                $config['valor'] = $usuarios;
                $config['nome']  = 'aceite['.$ordem.'][id_responsavel]';
                $config['extra'] = 'id="id_responsavel" '.$atributo;    
                echo form_select($config, set_value('id_responsavel',isset($item->id_responsavel)? $item->id_responsavel :''));
                ?>
                <p class="help-block criterios-aceite-id-responsavel"></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group criterios-aceite-objetivo">
                <label for="objetivo">Objetivo</label>
                <textarea name="aceite[<?php echo $ordem; ?>][objetivo]" id="objetivo" type="text" class="form-control"  <?php echo $atributo;?>> <?php echo set_value('aceite['.$ordem.'][objetivo]', isset($item->objetivo) ? $item->objetivo : ''); ?></textarea>
                <p class="help-block criterios-aceite-objetivo"></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group criterios-aceite-indicador">
                <label for="indicador">Indicador</label>
                <textarea name="aceite[<?php echo $ordem; ?>][indicador]" id="indicador" type="text" class="form-control"  <?php echo $atributo;?>> <?php echo set_value('aceite['.$ordem.'][indicador]', isset($item->indicador) ? $item->indicador : ''); ?></textarea>
                <p class="help-block criterios-aceite-indicador"></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group criterios-meta">
                <label for="meta">Meta</label>
                <textarea name="aceite[<?php echo $ordem; ?>][meta]" type="text" id="meta" class="form-control"  <?php echo $atributo;?>> <?php echo set_value('aceite['.$ordem.'][meta]', isset($item->meta) ? $item->meta : ''); ?></textarea>
                <p class="help-block criterios-aceite-meta"></p>
            </div>
        </div>
    </div>  
 </div>
<?php 
}              
else 
                {
                    
?>
<div class="alert alert-success">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="media">
                <div class="media-body">
                    <h4 class="media-heading">Data Medida (YYYY-mm-dd)</h4>
                    <p><?php echo set_value('aceite['.$ordem.'][data_medida]', isset($item->data_medida) ? $item->data_medida : ''); ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="media">
                <div class="media-body">
                    <h4 class="media-heading">
                        Indicador
                    </h4>
                    <p>
                        <?php 
                        echo $item->indicador;
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                }