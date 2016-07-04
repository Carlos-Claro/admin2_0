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
        <div class="col-lg-6">
            <label for="filtrar_por"> Realizar Postagem por  </label>
            <select name="filtrar_por" id="filtrar_por" class="form-control">
                <option title="selecione" selected="selected">Selecione..</option>
                <option value="estados" title="estados">Estado / Cidade</option>
                <option value="facebook_categorias" title="categorias">Categorias</option>
            </select>
        </div>
    </div>
    <div id="resultado"></div>
    
    <div class="row alert alert-success">
         <div class="col-lg-12">
              <div class="form-group">
                <label for="mensagem">Mensagem</label>
                <textarea name="mensagem" class="form-control" id="mensagem" placeholder="Mensagem"></textarea>
             </div>
         </div>
        <div class="col-lg-4">
            <button type="submit" class="btn btn-warning">Postar</button>
        </div>
    </div>
    <div class="row">
         <div class="col-lg-12">
            <div class="controls" id="grupos"></div>
         </div>
    </div>
    
    <div class="controls">
        <?php 
        //var_dump($item);
        $config['valor'] = $item; 
        $config['nome'] = 'pages'; 
        $config['extra'] = 'col-lg-3'; 
        echo form_checkbox_($config, set_value('pages', array())); 
        ?>
     </div>
</form>

