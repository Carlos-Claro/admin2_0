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
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <label for="canal">Canal</label>
                    <input name="canal" disabled="disabled" type="text" class="form-control" id="canal" value="<?php echo set_value('canal', isset($canal->titulo) ? $canal->titulo : '');?>" >
                    <input name="id_canais" type="hidden" class="form-control" id="id_canais" value="<?php echo set_value('id_canais', isset($canal->id) ? $canal->id : '');?>" >
                </div>
                <!--
                 <div class="form-group">
                    <label for="id_canais">Canais</label>
                    <div class="controls">
                        <?php 
                        /*
                        $config['valor'] = $canais; 
                        $config['nome'] = 'id_canais'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canais', isset($item->id_canais) ? $item->id_canais : '')); 
                        */
                        ?>
                    </div>
                </div>
                -->
            </div>  
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                </div>
                <label class="radio-inline">
                    <input name="tipo" type="radio" value="atributo" class="tipo">Atributo
                </label>
                <label class="radio-inline">
                    <input name="tipo" type="radio" value="relacao" class="tipo">Relação
                </label>
            </div> 
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <label for="id_relacionado">ID Relacionado</label>
                    <input name="id_relacionado" type="text" class="form-control" id="id_relacionado" placeholder="ID Relacionado" value="<?php echo set_value('id_relacionado', isset($item->id_relacionado ) ? $item->id_relacionado : '');?>">
                </div>
            </div> 
        </div>
    </div>
    <div class="alert alert-warning">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " id="resposta-tipo"></div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>  
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="ordem">Ordem</label>
                    <input name="ordem" type="text" class="form-control" id="ordem" placeholder="Ordem" value="<?php echo set_value('ordem', isset($item->ordem ) ? $item->ordem : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="tipo_ordem">Tipo de Ordem</label>
                    <input name="tipo_ordem" type="text" class="form-control" id="tipo_ordem" placeholder="Tipo de Ordem" value="<?php echo set_value('tipo_ordem', isset($item->tipo_ordem ) ? $item->tipo_ordem : '');?>">
                </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="campo_ordem">Campo de Ordem</label>
                    <input name="campo_ordem" type="text" class="form-control" id="campo_ordem" placeholder="Campo de Ordem" value="<?php echo set_value('campo_ordem', isset($item->campo_ordem ) ? $item->campo_ordem : '');?>">
                </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="qtde_colunas">Nº de Colunas</label>
                    <input name="qtde_colunas" type="text" class="form-control" id="qtde_colunas" placeholder="Quantidade de colunas" value="<?php echo set_value('qtde_colunas', isset($item->qtde_colunas ) ? $item->qtde_colunas : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="n_coluna_lg_sm">Nº de Colunas em Telas Grandes</label>
                    <input name="n_coluna_lg_sm" type="text" class="form-control" id="n_coluna_lg_sm" placeholder="Nº de Colunas em Telas Grandes" value="<?php echo set_value('n_coluna_lg_sm', isset($item->n_coluna_lg_sm ) ? $item->n_coluna_lg_sm : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="n_coluna_md">Nº de Colunas em Telas Médias</label>
                    <input name="n_coluna_md" type="text" class="form-control" id="n_coluna_md" placeholder="Nº de Colunas em Telas Médias" value="<?php echo set_value('n_coluna_md', isset($item->n_coluna_md ) ? $item->n_coluna_md : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="n_coluna_xs">Nº de Colunas em Telas Pequenas</label>
                    <input name="n_coluna_xs" type="text" class="form-control" id="n_coluna_xs" placeholder="Nº de Colunas em Telas Pequenas" value="<?php echo set_value('n_coluna_xs', isset($item->n_coluna_xs ) ? $item->n_coluna_xs : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                 <div class="form-group">
                     <label for="qtde">Quantidade</label>
                     <input name="qtde" type="text" class="form-control" id="qtde" placeholder="Quantidade" value="<?php echo set_value('qtde', isset($item->qtde ) ? $item->qtde : '');?>">
                 </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="qtde_caracteres_descricao">Nº de Caracteres para Descrição</label>
                    <input name="qtde_caracteres_descricao" type="text" class="form-control" id="qtde_caracteres_descricao" placeholder="Nº de caracteres para descrição" value="<?php echo set_value('qtde_caracteres_descricao', isset($item->qtde_caracteres_descricao ) ? $item->qtde_caracteres_descricao : '');?>">
                </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="camada">Camada</label>
                    <input name="camada" type="text" class="form-control" id="camada" placeholder="Camada" value="<?php echo set_value('camada', isset($item->camada ) ? $item->camada : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="posicao_image">Posição da Imagem</label>
                    <input name="posicao_image" type="text" class="form-control" id="posicao_image" placeholder="Posição da Imagem" value="<?php echo set_value('posicao_image', isset($item->posicao_image ) ? $item->posicao_image : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="classe">Classe</label>
                    <input name="classe" type="text" class="form-control" id="classe" placeholder="Classe" value="<?php echo set_value('classe', isset($item->classe ) ? $item->classe : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="classe_master">Classe Master</label>
                    <input name="classe_master" type="text" class="form-control" id="classe_master" placeholder="Classe Master" value="<?php echo set_value('classe_master', isset($item->classe_master ) ? $item->classe_master : '');?>">
                </div>
            </div> 
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <br>
                <div class="checkbox">
                    <label>
                        <input name="link_mais" type="checkbox" id="link_mais" value='1'>
                        Link Mais
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <br>
                <div class="checkbox">
                    <label>
                        <input name="titulo_exibe" type="checkbox" id="titulo_exibe" value='1'>
                        Exibir Titulo
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <br>
                <div class="checkbox">
                    <label>
                        <input name="mostra_estrela" type="checkbox" id="mostra_estrela" value='1'>
                        Mostrar Estrela
                    </label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

