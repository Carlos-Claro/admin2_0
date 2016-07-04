<style type="text/css">
    .resposta_cidade{margin-top: -15px;}
    .resposta_bairro{margin-top: -15px;}
</style>
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
                    <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id_origem ) ? $item->id_origem : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="destino">Destino</label>
                    <input name="destino" type="text" class="form-control" id="destino" placeholder="Destino"  value="<?php echo set_value('destino', isset($item->destino ) ? $item->destino : '');?>">
                    <p class="helper-block"><?php echo form_error('destino'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo"  value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                    <p class="helper-block"><?php echo form_error('titulo'); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="vantagens">Vantagens</label>
                    <textarea name="vantagens" class="form-control" id="vantagens" placeholder="Vantagens"><?php echo set_value('vantagens', isset($item->vantagens) ? $item->vantagens : '');?></textarea>
                    <p class="helper-block"><?php echo form_error('vantagens'); ?></p>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="outras_vantagens">Outras Vantagens</label>
                    <textarea name="outras_vantagens" class="form-control" id="outras_vantagens" placeholder="Outras Vantagens"><?php echo set_value('outras_vantagens', isset($item->outras_vantagens) ? $item->outras_vantagens : '');?></textarea>
                    <p class="helper-block"><?php echo form_error('outras_vantagens'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

