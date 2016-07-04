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
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_empresa">Empresa</label>
                    <input name="busca_empresa" type="text" class="form-control" id="busca_empresa" placeholder="Empresa" required value="<?php echo set_value('empresa', isset($empresa->empresa_nome_fantasia) ? $empresa->empresa_nome_fantasia : '');?>">
                </div>
                <div class="resposta_empresa"></div>
            </div>  
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo set_value('id_empresa', isset($empresa->id) ? $empresa->id : '');?>">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                </div>
            </div>   
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input name="telefone" type="text" class="form-control" id="telefone" placeholder="Telefone" value="<?php echo set_value('telefone', isset($item->telefone) ? $item->telefone : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email', isset($item->email) ? $item->email : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="funcao">Função</label>
                    <input name="funcao" type="text" class="form-control" id="funcao" placeholder="Funcao" value="<?php echo set_value('funcao', isset($item->funcao) ? $item->funcao : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="obs">Observação</label>
                    <textarea name="obs" id="obs" class="form-control"><?php echo set_value('obs', isset($item->obs) ? $item->obs : '');?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="principal" id="principal" value="1" <?php echo ((isset($item->principal) && $item->principal)  ? 'checked="checked"' : '');?>>Principal
                    </label>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="status" id="status" value="1" <?php echo ((isset($item->status) && $item->status)  ? 'checked="checked"' : '');?>>Ativo
                    </label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

