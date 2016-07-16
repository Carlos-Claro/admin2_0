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
             <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control titulo" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>   
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="ordem">Ordem</label>
                    <input name="ordem" type="text" class="form-control" id="ordem" placeholder="Ordem" value="<?php echo set_value('ordem', isset($item->ordem) ? $item->ordem : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control link" readonly id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label>
                  <input name="liberado" type="checkbox" value="1" <?php if (isset($item->liberado) && $item->liberado == "1"): echo "checked=checked"; endif;?>> Liberado
                </label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

