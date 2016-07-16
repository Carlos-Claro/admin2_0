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
             <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control titulo" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control link" readonly id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input name="endereco" type="text" class="form-control" id="endereco" placeholder="Endereco" value="<?php echo set_value('endereco', isset($item->endereco) ? $item->endereco : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="meta_titulo">Meta Titulo</label>
                    
                    <input name="meta_titulo" type="text" class="form-control" id="meta_titulo" placeholder="Meta Titulo" value="<?php echo set_value('meta_titulo', isset($item->meta_titulo) ? $item->meta_titulo : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group meta_descricao">
                    <label for="meta_descricao">Meta Descrição</label><span class="pull-right text-info">qtde caracteres: <span class="contador_descricao"></span></span>
                    <textarea name="meta_descricao" class="form-control" id="meta_descricao" placeholder="Meta Descricao" ><?php echo set_value('meta_descricao', isset($item->meta_descricao) ? $item->meta_descricao : '');?></textarea>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="meta_keys">Meta Keys</label>
                    <input name="meta_keys" type="text" class="form-control" id="meta_keys" placeholder="Meta Keys" value="<?php echo set_value('meta_keys', isset($item->meta_keys) ? $item->meta_keys : '');?>">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

