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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="pedido">Pedido</label>
                    <textarea rows="8" name="pedido" disabled="disabled" id="pedido" class="form-control"><?php echo $item->pedido; ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cidade_interesse">Cidade de Interesse</label>
                    <input name="cidade_interesse" disabled="disabled" type="text" class="form-control" id="cidade_interesse" placeholder="Cidade Interesse"  value="<?php echo set_value('cidade_interesse', $item->cidade_interesse);?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="data">Data</label>
                    <input name="data" disabled="disabled" type="text" class="form-control" id="data" placeholder="Data"  value="<?php echo set_value('data', date('d/m/Y H:i:s',$item->data) );?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="respostas">Respostas</label>
                    <input name="respostas" disabled="disabled" type="text" class="form-control" id="respostas" placeholder="Respostas"  value="<?php echo set_value('respostas', $item->respostas);?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="estados">Estados</label>
                    <?php
                        $config['valor'] = $estados; 
                        $config['nome'] = 'estados'; 
                        $config['extra'] = 'id="estados"'; 
                        echo form_select($config, set_value('estados', (isset($estado_select->uf) && $estado_select->uf) ? $estado_select->uf : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group" id="cidades_select">
                    <label for="cidades">Cidades</label>
                    <select name="cidades" class="form-control">
                        <option value="">Selecione...</option>
                    </select>
                </div>
                <input type="hidden" name="cidade_hidden" id="cidade_hidden" value="<?php echo $item->id_cidade; ?>">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="enviado">Status</label>
                    <input name="enviado" type="text" class="form-control" id="enviado" placeholder="Enviado"  value="<?php echo set_value('enviado', $item->enviado);?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="alert alert-danger">Status  0 = Pronto para ser enviado</label><br>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="alert alert-success">Status 1 = Enviado com sucesso</label><br>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="alert alert-warning">Status 2 = Cidade não econtrada</label><br>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

