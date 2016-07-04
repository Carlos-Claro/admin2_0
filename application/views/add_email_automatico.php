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
                <div class="control-group">
                    <div class="controls">
                        <a href="<?php echo $action_anexo;?>" id="edit_image"><button type="button" class="btn" >Editar Anexos</button></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo) ? $item->titulo : '');?>">
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="corpo">Corpo</label>
                    <textarea name="corpo" class="form-control" id="corpo" placeholder="Corpo" ><?php echo set_value('corpo', isset($item->corpo) ? $item->corpo : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_corpo); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="assinatura">Assinatura</label>
                    <textarea name="assinatura" class="form-control" id="assinatura" placeholder="Assinatura" ><?php echo set_value('assinatura', isset($item->assinatura) ? $item->assinatura : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_assinatura); ?>
                </div>
            </div>	
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

