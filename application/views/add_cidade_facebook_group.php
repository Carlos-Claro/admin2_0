<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <span class="alert alert-danger"><?php echo validation_errors(); ?></span>
    </div>
    <?php 
    if ( isset($mostra_id) && $mostra_id ) :
        ?>
    <div class="form-group">
        <label for="id">ID</label>
        <input type="text" disabled="disabled" name="id" class="form-control" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
    </div>
        <?php
    endif;
    ?>
    <div class="row alert alert-info">
        <div class="form-group col-lg-6">
            <label for="uf_estado">Estado</label>
            <div class="controls ">
                <?php 
                $config['valor'] = $estado; 
                $config['nome'] = 'uf_estado'; 
                $config['extra'] = 'class="form-control"'; 
                echo form_select($config, set_value('uf_estado', isset($estado->id) ? $estado->id : '')); 
            ?>
            </div>
        </div>
        <div id="cidades" class="col-lg-6"> </div>
    </div>
    
    <label for="groups">Grupos</label>
    <div class="controls">
        <?php 
        //var_dump($item);
        $config['valor'] = $item; 
        $config['nome'] = 'groups'; 
        $config['extra'] = 'col-lg-3'; 
        echo form_checkbox_($config, set_value('groups', array())); 
        ?>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

