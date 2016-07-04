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
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome"  value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                    <p class="helper-block"><?php echo form_error('nome'); ?></p>
                </div>
            </div> 
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="tag">Tag</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $tag; 
                    $config['nome'] = 'tag'; 
                    $config['extra'] = ''; 
                    echo form_select($config, set_value('tag', isset($item->tag) ? $item->tag : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="responsavel">Responsável</label>
                    <input name="responsavel" type="text" class="form-control" id="responsavel" placeholder="Responsável" value="<?php echo set_value('responsavel', isset($item->responsavel) ? $item->responsavel : '');?>">
                    <p class="helper-block"><?php echo form_error('responsavel'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input name="email" type="text" class="form-control" id="email" placeholder="E-mail" value="<?php echo set_value('email', isset($item->email) ? $item->email : '');?>">
                    <p class="helper-block"><?php echo form_error('email'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input name="telefone" type="text" class="form-control" id="telefone" placeholder="Telefone" value="<?php echo set_value('telefone', isset($item->telefone) ? $item->telefone : '');?>">
                    <p class="helper-block"><?php echo form_error('telefone'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="documentacao">Documentação</label>
                    <input name="documentacao" type="text" class="form-control" id="documentacao" placeholder="Documentação" value="<?php echo set_value('documentacao', isset($item->documentacao) ? $item->documentacao : '');?>">
                    <p class="helper-block"><?php echo form_error('documentacao'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

