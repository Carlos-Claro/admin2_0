<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
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
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <div class="input-group">
                        <span class="input-group-addon">Proximo </span>
                        <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo"  value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                    </div>
                    <p class="helper-block"><?php echo form_error('titulo'); ?></p>
                </div>
            </div>   
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <div class="input-group">
                        <span class="input-group-addon">proximo_</span>
                        <input name="link" type="text" class="form-control" id="link" placeholder="Link"  value="<?php echo set_value('link', isset($item->link ) ? $item->link : '');?>">
                    </div>
                    <p class="helper-block"><?php echo form_error('link'); ?></p>
                </div>
            </div> 
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Cidade</label>
                    <?php 
                    $config['valor'] = $cidades; 
                    $config['nome'] = 'id_cidade'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_cidade', isset($item->id_cidade) ? $item->id_cidade : '')); 
                    ?>
                    <p class="helper-block"><?php echo form_error('id_cidade'); ?></p>
                </div>
            </div> 
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label>Ativo</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="ativo" value="1" id="ativo" <?php echo set_value('ativo', (isset($item->ativo) && $item->ativo == 1) ? 'checked="checked"' : 0);?>> Ativo 
                    </label>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link_bairros">Link Bairros ( Links Separados por "-" )</label>
                    <input name="link_bairros" type="text" class="form-control" id="link_bairros" placeholder="Link para os bairros" value="<?php echo set_value('link_bairros', isset($item->link_bairros) ? $item->link_bairros : '');?>">
                    <p class="helper-block"><?php echo form_error('link_bairros'); ?></p>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group resultado-busca">
                    <label for="digite">Digite o bairro buscado:</label>
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-danger"><span class="glyphicon glyphicon-question-sign"></span>&nbsp;Instruções:</li>
                        <li class="list-group-item list-group-item-text">Digite o nome aproximado do bairro;</li>
                        <li class="list-group-item list-group-item-text">Separe o bairro da cidade utilizando o simbolo "-"(traço);</li>
                        <li class="list-group-item list-group-item-text">Clique botão "Buscar !";</li>
                        <li class="list-group-item list-group-item-text">Clique no resultado que busca no campo abaixo;</li>
                        <li class="list-group-item list-group-item-text">Verifique se foi adicionado mais 1 link no campo "Link";</li>
                        <li class="list-group-item list-group-item-text">Para deletar um link, apenas selecione o link desejado mais o traço "-" que o precede;</li>
                        <li class="list-group-item list-group-item-text">Para limpar todos, selecione todos os link e delete.</li>
                    </ul>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button type="button" class="buscar btn btn-default"> Buscar ! </button>
                        </span>
                        <input type="text" class="form-control" id="digite" placeholder="Busque bairros" value="" autocomplete="off">
                    </div>
                    <div class="helper-block"></div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

