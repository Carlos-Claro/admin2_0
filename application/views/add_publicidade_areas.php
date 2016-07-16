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
    <div class="alert alert-warning">
        <div class="row">
             <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="area">Titulo</label>
                    <input name="area" type="text" class="form-control titulo" id="area" placeholder="Titulo" required value="<?php echo set_value('area', isset($item->area ) ? $item->area : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_guia">Guia: </label>
                    <?php 
                    $config['valor'] = $id_guia; 
                    $config['nome'] = 'id_guia'; 
                    $config['extra'] = 'class="form-control seleciona-id_guia"'; 
                    echo form_select($config, set_value('id_guia', isset($item->id_guia) ? $item->id_guia : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php 
                    if ( isset($id_setor) && $id_setor )
                    {
                        echo '<label for="id_setor">Setor: </label>';
                        $config['valor'] = $id_setor; 
                        $config['nome'] = 'id_setor'; 
                        $config['extra'] = 'class="form-control seleciona-id_setor"'; 
                        echo form_select($config, set_value('id_setor', isset($item->id_setor) ? $item->id_setor : '')); 
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="quantia">Qtde</label>
                    <input name="quantia" type="text" class="form-control" id="quantia" placeholder="Qtde" value="<?php echo set_value('quantia', isset($item->quantia) ? $item->quantia : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="posicao">Posição: </label>
                    <?php 
                    $config['valor'] = $posicao; 
                    $config['nome'] = 'posicao'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('posicao', isset($item->posicao) ? $item->posicao : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="largura">Largura (px)</label>
                    <input name="largura" type="text" class="form-control" id="largura" placeholder="Largura" value="<?php echo set_value('largura', isset($item->largura) ? $item->largura : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="altura">Altura (px)</label>
                    <input name="altura" type="text" class="form-control" id="altura" placeholder="Altura" value="<?php echo set_value('altura', isset($item->altura) ? $item->altura : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="peso">Peso (kb)</label>
                    <input name="peso" type="text" class="form-control" id="peso" placeholder="Peso" value="<?php echo set_value('peso', isset($item->peso) ? $item->peso : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_area">Area: </label>
                    <?php 
                    $config['valor'] = $id_area; 
                    $config['nome'] = 'id_area'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_area', isset($item->id_area) ? $item->id_area : '')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    
</form>

