<style type="text/css">
    .resposta_cadastro{margin-top: -15px;}
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
                    <input type="text" disabled="disabled" name="id" class="form-control ids" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_cadastro">Nome</label>
                    <input name="busca_cadastro" type="text" class="form-control" id="busca_cadastro" placeholder="Nome"  value="<?php echo set_value('cadastro', isset($cadastro->nome) ? $cadastro->nome : '');?>">
                </div>
                <div class="resposta_cadastro"></div>
            </div>  
            <input type="hidden" id="id_cadastro" name="id_cadastro" value="<?php echo set_value('id_cadastro', isset($cadastro->id) ? $cadastro->id : '');?>">
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="id_promocao">Promoção</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $promocao; 
                    $config['nome'] = 'id_promocao'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_promocao', isset($item->id_promocao) ? $item->id_promocao : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div id="form-group">
                    <label for="data_cadastro">Data</label>
                    <input type="text" name="data_cadastro" class="form-control" id="data_cadastro"  value="<?php echo set_value('data_cadastro', (isset($item->data_cadastro)? $item->data_cadastro : date('d/m/Y H:i:s')));?>">
                    <p class="helper-block"><?php echo form_error('data_cadastro'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label>Vencedor</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="vencedor" value="1" id="vencedor" <?php echo set_value('vencedor', (isset($item->vencedor) && $item->vencedor == 1) ? 'checked="checked"' : 0);?>> vencedor
                    </label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

