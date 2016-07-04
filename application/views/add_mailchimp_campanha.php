<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <div class="alert alert-info">
        <fieldset>
            <legend>Recipientes</legend>
        </fieldset>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="type">Tipo da Campanha</label>
                    <select name="type" id="type" class="form-control">
                        <option value="regular">Regular</option>
                        <option value="plaintext">Texto Pleno</option>
                        <option value="absplit">A/B Split</option>
                        <option value="rss">RSS-Driven</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="options[list_id]">Lista que será utilizada</label>
                    <?php
                        $config['valor'] = $lists; 
                        $config['nome'] = 'options[list_id]'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('list_id', '')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info">
        <fieldset>
            <legend>Configurações da Campanha</legend>
        </fieldset>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="options[title]">Nome da Campanha</label>
                    <input type="text" name="options[title]" id="options[title]" class="form-control" value="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="options[subject]">Assunto</label>
                    <input type="text" name="options[subject]" id="options[subject]" class="form-control" value="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="options[from_name]">Nome do Remetente</label>
                    <input type="text" name="options[from_name]" id="options[from_name]" class="form-control" value="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="options[from_email]">Email do Remetente</label>
                    <input type="text" name="options[from_email]" id="options[from_email]" class="form-control" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="" id="" class="checkbox-inline" value="">Autenticar
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info">
        <fieldset>
            <legend>Templates</legend>
        </fieldset>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input type="text" name="content[url]" id="content[url]" class="form-control" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Importar de URL</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-warning">Criar</button>
</form>

