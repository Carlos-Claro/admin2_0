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
        <input type="text" disabled="disabled" name="id" class="form-control" value="<?php echo set_value('id', isset($item_categoria->id ) ? $item_categoria->id : '');?>">
    </div>
        <?php
    endif;
    ?>
    <div class="row alert alert-info">
        <div class="col-lg-3">
            <label for="groups">Categorias</label>
            <div class="controls">
                <?php 
                $config['valor'] = $categoria; 
                $config['nome']  = 'categoria'; 
                $config['extra'] = ''; 
                echo form_select($config, set_value('categoria', isset($categoria->id) ? $categoria->id : '')); 
                ?>
            </div>
        </div>
    </div>
    <label for="groups">Itens</label>
    <div class="controls">
        <?php 
        //var_dump($item);
        $config['valor'] = $item_categoria; 
        $config['nome'] = 'groups'; 
        $config['extra'] = 'col-lg-3'; 
        echo form_checkbox_($config, set_value('groups', array())); 
        ?>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

