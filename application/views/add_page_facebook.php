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
        <div class="col-lg-4">
            <div class="form-group">
                <label for="id_page">ID da Página</label>
                <input type="text" name="id_page[]" class="form-control">
            </div>
        </div>
    <div id="add_pages" class="controls"></div>
    </div>
    <button type="button" class="btn btn-success" id="mais_pages">Adicionar Mais FanPage</button> 
    <button type="submit" class="btn btn-warning">Gravar FanPage</button>
</form>

