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
                    <label for="tipo">Tipo</label>
                    <div class="controls">
                        <?php 
                        $tipo = array(
                                (object)    array('id' => 'dica', 'descricao' => 'Dica'),
                                (object)    array('id' => 'promocao', 'descricao' => 'Promoção'),
                            );
                        $config['valor'] = $tipo; 
                        $config['nome'] = 'tipo'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('tipo', isset($item->tipo) ? $item->tipo : '')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_dica_setor">Setor</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $setor; 
                        $config['nome'] = 'id_dica_setor'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('tipo', isset($item->id_dica_setor) ? $item->id_dica_setor : '')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

