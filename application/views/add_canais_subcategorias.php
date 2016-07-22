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
    <div class="row alert alert-info">
         <div class="form-group">
             <label for="res">Canal</label>
             <div class="controls">
                 
                 <?php 
                 $config['valor'] = $canais; 
                 $config['nome'] = 'id_canais'; 
                 $config['extra'] = 'col-lg-3'; 
                 echo form_select($config, set_value('id_canais', isset($canais->id) ? $canais->id : array())); 
                 ?>
             </div>
         </div>
         <div class="form-group">
             <label for="res">Subcategoria</label>
             <div class="controls">
                 <?php 
                 $config['valor'] = $subcategorias; 
                 $config['nome'] = 'id_subcategoria'; 
                 $config['extra'] = 'col-lg-3'; 
                 echo form_select($config, set_value('id_subcategoria', isset($canais_subcategorias) ? $canais_subcategorias : array())); 
                 ?>
             </div>
         </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

