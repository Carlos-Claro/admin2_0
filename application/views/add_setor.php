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
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="classe">Classe</label>
                    <input name="classe" type="text" class="form-control" id="classe" placeholder="Classe" required value="<?php echo set_value('classe', isset($item->classe ) ? $item->classe : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_pai">Setor Pai</label>
                    <div class="controls">
                        <?php 
                        $valor_pai = (isset($item->id_pai) && $item->id_pai) ? $item->id_pai : ( (isset($id_pai) && $id_pai) ? $id_pai : '');
                        $config['valor'] = $pai; 
                        $config['nome'] = 'id_pai'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_pai', $valor_pai)); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="model">Model</label>
                    <input name="model" type="text" class="form-control" id="model" placeholder="Model" value="<?php echo set_value('model', isset($item->model ) ? $item->model : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="checkbox">
                    <label>
                        <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="checkbox">
                    <label>
                        <input name="painel" type="checkbox" value="1" <?php if (isset($item->painel) && $item->painel == "1"): echo "checked=checked"; endif;?>>Painel
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

