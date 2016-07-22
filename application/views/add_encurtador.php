<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" id="form_enc">
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
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link_encaminhado">Link Encaminhado:</label>
                    <input name="link_encaminhado" type="text" class="form-control link_encaminhado" id="link_encaminhado" placeholder="Insira seu link aqui" required value="<?php echo set_value('link_encaminhado', isset($item->link_encaminhado ) ? urldecode($item->link_encaminhado) : '');?>">
                </div>
            </div>   
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link_encurtado">Link Encurtado</label>
                    <input name="link_encurtado" type="text" class="form-control link_encurtado" id="link_encurtado" placeholder="Link Encurtado, insira o que deseja até 10 caracteres" required value="<?php echo set_value('link_encurtado', isset($item->link_encurtado) ? $item->link_encurtado : '');?>">
                    <p class="help-block"><?php echo ( isset($mostra_id) && $mostra_id ) ? 'Utilize o link: <a target="_blank" href="http://pow.vc/'.$item->link_encurtado.'">http://pow.vc/'.$item->link_encurtado.'</a>' : '';?></p>
                    <button class="gera-link btn btn-primary" type="button">Gerar automático</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-warning salva">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

