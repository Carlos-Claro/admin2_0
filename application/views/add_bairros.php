<style type="text/css">
    .resposta_cidade{margin-top: -15px;}
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
                    <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="codigo">Codigo</label>
                    <input name="codigo" type="text" class="form-control" id="codigo" placeholder="Codigo"  value="<?php echo set_value('codigo', isset($item->codigo ) ? $item->codigo : '');?>">
                    <p class="helper-block"><?php echo form_error('codigo'); ?></p>
                </div>
            </div>   
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome"  value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                    <p class="helper-block"><?php echo form_error('nome'); ?></p>
                </div>
            </div> 
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="mapa">Mapa</label>
                    <input name="mapa" type="text" class="form-control" id="mapa" placeholder="Mapa" value="<?php echo set_value('mapa', isset($item->mapa) ? $item->mapa : '');?>">
                    <p class="helper-block"><?php echo form_error('mapa'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_cidade">Cidade</label>
                    <input name="busca_cidade" type="text" class="form-control" id="busca_cidade" placeholder="Cidade"  value="<?php echo set_value('cidades', isset($cidades->nome) ? $cidades->nome : '');?>">
                </div>
                <div class="resposta_cidade"></div>
            </div>  
            <input type="hidden" id="cidade" name="cidade" value="<?php echo set_value('cidade', isset($cidades->id) ? $cidades->id : '');?>">
            
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="zona">Zona</label>
                    <input name="zona" type="text" class="form-control" id="zona" placeholder="Zona" value="<?php echo set_value('zona', isset($item->zona) ? $item->zona : '');?>">
                    <p class="helper-block"><?php echo form_error('zona'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input type="text" name="link" class="form-control" id="link" placeholder="Link" value="<?php echo set_value('link', isset($item->link ) ? $item->link : '');?>">
                    <p class="helper-block"><?php echo form_error('link'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label>Liberação</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="libera" value="1" id="vitrine" <?php echo set_value('libera', (isset($item->libera) && $item->libera == 1) ? 'checked="checked"' : 0);?>> Libera 
                    </label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

