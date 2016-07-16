<style type="text/css">
    .resposta_tipos{margin-top: -15px;}
    .resposta_estilos{margin-top: -15px;}
    .resposta_sistema{margin-top: -15px;}
</style>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
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
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="tipo">Titulo</label>
                    <input name="tipo" type="text" class="form-control" <?php isset($item->tipo)?'disabled="disabled"' : '';?>id="tipo" placeholder="Tipo"  value="<?php echo set_value('tipo', isset($item->tipo ) ? $item->tipo : '');?>">
                    <p class="helper-block"><?php echo form_error('tipo'); ?></p>
                </div>
            </div>   
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="id_tipo">Tipo Casa</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $tipos; 
                    $config['nome'] = 'id_tipo'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_tipo', isset($item->id_tipo) ? $item->id_tipo : '')); 
                    ?>
                </div>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="id_estilo">Estilos</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $estilos; 
                    $config['nome'] = 'id_estilo'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_estilo', isset($item->id_estilo) ? $item->id_estilo : '')); 
                    ?>
                </div>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="sistema">Sistemas</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $sistemas; 
                    $config['nome'] = 'sistema'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('sistema', isset($item->sistema) ? $item->sistema : '')); 
                    ?>
                </div>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="tipo_area">Tipo Área</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $tipo_areas; 
                    $config['nome'] = 'tipo_area'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('tipo_area', isset($item->tipo_area) ? $item->tipo_area : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
                <label>Residencial</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="residencial" value="1" id="residencial" <?php echo set_value('residencial', (isset($item->residencial) && $item->residencial == 1) ? 'checked="checked"' : 0);?>> Residencial 
                    </label>
                </div>
            </div>
            <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
                <label>Comercial</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="comercial" value="1" id="comercial" <?php echo set_value('comercial', (isset($item->comercial) && $item->comercial == 1) ? 'checked="checked"' : 0);?>> Comercial 
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                <label>Lazer</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="lazer" value="1" id="lazer" <?php echo set_value('lazer', (isset($item->lazer) && $item->lazer == 1) ? 'checked="checked"' : 0);?>> Lazer 
                    </label>
                </div>
            </div>
            
        </div>
    </div>
    <button id="salvar"  type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

