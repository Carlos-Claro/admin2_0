<style type="text/css">
    .resposta_cadastro{margin-top: -15px;}
</style>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    </div>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Promoção</label>
                    <input name="titulo" type="text" disabled="disabled" class="form-control" id="titulo" placeholder="Titulo"  value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                    <p class="helper-block"><?php echo form_error('titulo'); ?></p>
                </div>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="qtde">Quantidade</label>
                    <input name="qtde" type="text" class="form-control" id="qtde" placeholder="Quantidade"  value="">
                    <div><p class="qtdeobrigatoria"></p></div>
                </div>
            </div>
            
        </div>
        <button type="button" id="gerar" data-item="<?php echo $item->id;?>" class="btn btn-warning">Sortear</button>
        <button type="button" id="limpar" class="btn btn-warning" data-item="<?php echo $item->id;?>">Limpar vencedores</button>
    </div>
    
    <div class="alert alert-info">
        <div class="row">
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label>Vencedor</label>
            </div>
        </div>
        <div class="esconde"></div>
        <div class="esconde2"></div>
        <input class="idpromocao" type="hidden" data-item='<?php echo $item->id;?>'>
    </div>
    
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>