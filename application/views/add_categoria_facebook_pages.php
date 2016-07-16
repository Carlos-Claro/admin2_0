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
    <h2>Selecione as Páginas que você deseja que tenham a categoria</h2>
    <label for="groups">Páginas</label>
    <div class="controls">
        <?php 
        //var_dump($item);
        $config['valor'] = $item_categoria; 
        $config['nome'] = 'pages'; 
        $config['extra'] = 'col-lg-3'; 
        echo form_checkbox_($config, set_value('pages', array())); 
        ?>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

