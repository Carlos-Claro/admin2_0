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
    <div class="form-group">
        <label for="titulo">Titulo</label>
        <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
    </div>
        <div class="form-group">
        <label for="titulo">Titulo</label>
        <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
    </div>
    <div class="form-group">
        <label for="classe">Classe</label>
        <input name="classe" type="text" class="form-control" id="classe" placeholder="Classe" required value="<?php echo set_value('classe', isset($item->classe ) ? $item->classe : '');?>">
    </div>
     <div class="form-group">
        <label for="id_pai">Setor Pai</label>
        <div class="controls">
            <?php 
            $config['valor'] = $pai; 
            $config['nome'] = 'id_pai'; 
            $config['extra'] = 'class="form-control"'; 
            echo form_select($config, set_value('id_pai', isset($item->id_pai) ? $item->id_pai : '')); 
            ?>
        </div>
    </div>	
    <div class="checkbox">
        <label>
          <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
        </label>
    </div>
    
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

