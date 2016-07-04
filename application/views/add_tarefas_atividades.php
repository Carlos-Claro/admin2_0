<div class="row campos-atividade bg-info">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <h3 class="col-lg-11 col-sm-11 col-md-11 col-xs-11 pull-left">Nova Atividade:</h3>
        <button type="button" class="col-lg-1 col-sm-1 col-md-1 col-xs-1 close deleta-espaco pull-right" aria-label="Close" tiitle="deletar atividade"><span aria-hidden="true"><br>&times;</span></button>
    </div>
    <div class="form-group descricao col-lg-6 col-sm-6 col-md-6 col-xs-6">
        <label for="descricao">Descrição</label>
        <textarea name="descricao" class="form-control" id="descricao" placeholder="Descrição"></textarea>
        <p class="help-block descricao"></p>
    </div>
    <div class="form-group previsao_tempo col-lg-6 col-sm-6 col-md-6 col-xs-6">
        <label for="previsao_tempo">Previsão em Horas</label>
        <input type="text" name="previsao_tempo" class="form-control" id="previsao_tempo" placeholder="Previsao em hs">
        <p class="help-block previsao_tempo"></p>
    </div>
    <div class="form-group data_fim col-lg-6 col-sm-6 col-md-6 col-xs-6">
        <label for="data_fim">Data Limite</label>
        <input type="text" name="data_fim" class="form-control data_hora" id="data_fim" placeholder="Data limite da atividade">
        <p class="help-block data_fim"></p>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 usuarios">
        <label for="usuarios">Selecione os usuários designados para esta tarefa:</label>
        <p class="help-block usuarios"></p>
        <div class="controls ">
            <?php 
            $config['valor'] = $usuarios; 
            $config['nome'] = 'usuarios_designados'; 
            $config['extra'] = ' col-lg-3 col-sm-3 col-md-3 col-xs-4 '; 
            echo form_checkbox_($config, set_value('usuarios_designados', isset($usuarios_selecionados) ? $usuarios_selecionados : array() ),2 ); 
        ?>
        </div>
    </div>
    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
        <button class="btn btn-info pull-right salva-atividade col-lg-4 col-sm-4 col-md-4 col-xs-4" title="Salvar atividade" data-id="<?php echo $id;?>"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span></button>
    </div>
</div>