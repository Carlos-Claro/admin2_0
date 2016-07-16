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
                    <input type="text" disabled="disabled" name="id" class="form-control ids" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_cidade">Cidade</label>
                    <input name="busca_cidade" type="text" class="form-control" id="busca_cidade" placeholder="Cidade"  value="<?php echo set_value('cidade', isset($cidade->nome) ? $cidade->nome : '');?>">
                </div>
                <div class="resposta_cidade"></div>
            </div>  
            <input type="hidden" id="id_cidade" name="id_cidade" value="<?php echo set_value('id_cidade', isset($cidade->id) ? $cidade->id : '');?>">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_bairro">Bairro</label>
                    <input name="busca_bairro" type="text" class="form-control" id="busca_bairro" placeholder="Bairro"  value="<?php echo set_value('bairro', isset($bairro->nome) ? $bairro->nome : '');?>">
                </div>
                <div class="resposta_bairro"></div>
            </div>  
            <input type="hidden" id="id_bairro" name="id_bairro" value="<?php echo set_value('id_bairro', isset($bairro->id) ? $bairro->id : '');?>">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome_equivalente">Nome Equivalente</label>
                    <input name="nome_equivalente" type="text" class="form-control" id="nome_equivalente" placeholder="Nome Equivalente"  value="<?php echo set_value('nome_equivalente', isset($item->nome_equivalente ) ? $item->nome_equivalente : '');?>">
                    <p class="helper-block"><?php echo form_error('nome_equivalente'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

