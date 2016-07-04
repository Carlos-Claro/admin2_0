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
                    <label for="id_categoria">Categoria</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $categorias; 
                        $config['nome'] = 'id_categoria'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_categoria', isset($item->id_categoria) ? $item->id_categoria : '')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" value="<?php echo set_value('link', isset($item->link ) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="altura_iframe">Altura do Iframe</label>
                    <input name="altura_iframe" type="text" class="form-control" id="altura_iframe" placeholder="Altura do Iframe" value="<?php echo set_value('altura_iframe', isset($item->altura_iframe ) ? $item->altura_iframe : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="texto">Texto</label>
                    <textarea class="form-control" name="texto" id="texto" placeholder="Texto"><?php echo set_value('texto', isset($item->texto ) ? $item->texto : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_texto);?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="itinerario">Itinerário</label>
                    <textarea class="form-control" name="itinerario" id="itinerario" placeholder="Itinerario"><?php echo set_value('itinerario', isset($item->itinerario ) ? $item->itinerario : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_itinerario);?>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

