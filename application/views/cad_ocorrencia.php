    <div class="row alert alert-warning">
        <div class="col-lg-2 col-md-2">
            <label for="ocorrencia">Escolha um Contato</label>
            <div><button type="button" class="btn btn-primary nome_contato_ocorrencia" data-toggle="modal" data-target="#agendarModal">Contato</button></div>
            <input id="nome_contato_agendamento" type="hidden">
        </div>
        <div class="col-lg-4 col-md-4">
            <label for="contato_email">Setor</label>
            <?php
                $config['valor'] = $cargos; 
                $config['nome'] = 'setor_ocorrencia'; 
                $config['extra'] = 'class="form-control"'; 
                echo form_select($config, set_value('cargos', isset($item->cargos) ? $item->cargos : ''));
            ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <label for="contato_email">Assunto</label>
            <input class="form-control" id="assunto_ocorrencia" placeholder="Assunto" value="" type="text" name="assunto_ocorrencia">
        </div>
    </div>
    <div id="formulario_interacao_setor"></div>

    <?php 
    if(isset($agendamento))
    {
        echo $ocorrencias;
        echo $modal;
    }
    ?>
    <div id="novo_teste"></div>