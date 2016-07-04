<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" enctype="multipart/form-data" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
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
    <input type="hidden" name="operacao" value="<?php echo (isset($classe) && $classe) ? $classe : '' ?>">
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
        <?php if($classe == 'facebook_groups'): ?>
         <div class="col-lg-12">
            <div class="form-group ">
                <label for="titulo">Titulo</label>
                <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required="required" />
            </div>
         </div>
         <div class="col-lg-12">
              <div class="form-group">
                <label for="link">Link</label>
                <input name="link" type="text" class="form-control" id="link" placeholder="Link" required="required" />
              </div> 
          </div>
         <?php endif; ?>
         <div class="col-lg-12">
              <div class="form-group">
                <label for="mensagem">Mensagem</label>
                <textarea name="mensagem" class="form-control" id="mensagem" placeholder="Mensagem"></textarea>
             </div>
         </div>
        <?php if($classe == 'facebook_pages'): ?>
            <div class="col-lg-5">
                <input type="file" name="file">
            </div>
        <?php endif; ?>
        <div class="col-lg-4">
            <button type="submit" class="btn btn-warning">Postar</button>
        </div>
    </div>
    <div class="row">
         <div class="col-lg-12">
            <div class="controls" id="grupos"></div>
         </div>
    </div>
</form>

