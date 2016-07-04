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
    <div class="alert alert-info">
        <div class="row">
             <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cepi">CEP </label>
                    <input name="cepi" type="text" class="form-control cep" id="cep" placeholder="CEP" required value="<?php echo set_value('cepi', isset($item->cepi ) ? $item->cepi : '');?>">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="logradouro">Logradouro</label>
                    <input name="logradouro" type="text" class="form-control logradouro" id="logradouro" placeholder="Logradouro" value="<?php echo set_value('logradouro', isset($item->logradouro) ? $item->logradouro : '');?>">
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input name="bairro" type="text" class="form-control" id="bairro" placeholder="Bairro" value="<?php echo set_value('bairro', isset($item->bairro) ? $item->bairro : '');?>">
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                <div class="form-group">
                    <label for="inicio">Inicio</label>
                    
                    <input name="inicio" type="text" class="form-control" id="inicio" placeholder="Inicio" value="<?php echo set_value('inicio', isset($item->inicio) ? $item->inicio : '');?>">
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                <div class="form-group">
                    <label for="final">Fim</label>
                    
                    <input name="final" type="text" class="form-control" id="final" placeholder="Fim" value="<?php echo set_value('final', isset($item->final) ? $item->final : '');?>">
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                <div class="form-group">
                    <label for="km">KM</label>
                    
                    <input name="km" type="text" class="form-control" id="km" placeholder="km" value="<?php echo set_value('km', isset($item->km) ? $item->km : '');?>">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Cidade</label>
                    <?php 
                    $config['valor'] = $cidades; 
                    $config['nome'] = 'id_cidade'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_cidade', isset($item->id_cidade) ? $item->id_cidade : '')); 
                    ?>
                    <p class="helper-block"><?php echo form_error('id_cidade'); ?></p>
                </div>
            </div> 
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

