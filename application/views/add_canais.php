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
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>   
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="classe">Classe</label>
                    <input name="classe" type="text" class="form-control" id="classe" placeholder="Classe" value="<?php echo set_value('classe', isset($item->classe ) ? $item->classe : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="ordem">Ordem</label>
                    <input name="ordem" type="text" class="form-control" id="ordem" placeholder="Ordem" value="<?php echo set_value('ordem', isset($item->ordem) ? $item->ordem : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descricao" ><?php echo set_value('descricao', isset($item->descricao) ? $item->descricao : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_descricao);?>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4>Meta Tag</h4>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="title" type="text" class="form-control" id="title" placeholder="Title" value="<?php echo set_value('title', isset($item->title ) ? $item->title : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="library">Library</label>
                    <input name="library" type="text" class="form-control" id="library" placeholder="Library" value="<?php echo set_value('library', isset($item->library) ? $item->library : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <br>
                <div class="checkbox">
                    <label>
                        <input name="menu_ativo" type="checkbox" value="1" <?php echo set_value('menu_ativo', isset($item->menu_ativo ) && $item->menu_ativo == 1 ? 'checked="checked"' : '');?>> Menu Ativo
                    </label>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="posicao_menu">Posicão Menu</label>
                    <input name="posicao_menu" type="text" class="form-control" id="posicao_menu" placeholder="Posicao Menu" value="<?php echo set_value('posicao_menu', isset($item->posicao_menu ) ? $item->posicao_menu : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="description">Description</label> Caracateres restantes:<span id="char-digitado">140</span> (Máximo de 140) 
                    <textarea name="description" class="form-control" id="description" placeholder="Description" ><?php echo set_value('description', isset($item->description ) ? $item->description : '');?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="checkbox">
        <label>
            <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
        </label>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="res">Relacionamento</label>
        <?php 
        $config['valor'] = $subcategorias; 
        $config['nome'] = 'id_subcategoria'; 
        $config['extra'] = 'col-lg-3'; 
        echo form_checkbox_($config, set_value('id', isset($canais_subcategorias) ? $canais_subcategorias : array())); 
        ?>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

