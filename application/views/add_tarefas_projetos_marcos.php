<?php 
if( (isset($edita) && $edita ) ) :
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="marcos" title="Deleta Marco"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <?php
                $atributo = '';
                if ( isset($item) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="marcos" title="Editar Marco"><span class="glyphicon glyphicon-plus-sign"></span></button>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="marcos" title="Salvar Marco"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-10">
                <div class="form-group ">
                    <label for="titulo">Titulo</label>
                    <input name="marcos[<?php echo $ordem; ?>][titulo]" id="titulo" type="text" class="form-control marco_titulo" value="<?php echo set_value('marcos['.$ordem.'][titulo]', isset($item->titulo) ? $item->titulo : ''); ?>" <?php echo $atributo;?>>
                    <p class="help-block marco_titulo"></p>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label for="data">Data  (YYYY-mm-dd)</label>
                <input name="marcos[<?php echo $ordem; ?>][data]" id="data" type="text" class="data_db form-control marco_data" value="<?php echo set_value('marcos['.$ordem.'][data]', isset($item->data) ? $item->data : ''); ?>" <?php echo $atributo;?> >
                <p class="help-block marco_data"></p>
            </div>
        </div>    
    </div>    
<?php 
else :
    ?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-10">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Titulo
                        </h4>
                        <p>
                            <?php 
                            echo $item->titulo;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Data  (YYYY-mm-dd)
                        </h4>
                        <p>
                            <?php 
                            echo $item->data;
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