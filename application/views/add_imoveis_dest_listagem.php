<?php $sequencia = 100;?>
<div class="alert alert-danger">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" id="id" class="id" value="<?php echo isset($item->id) ? $item->id : 0;?>">
            <input type="hidden" id="id_empresa" class="id_empresa" value="<?php echo isset($item->id_empresa) ? $item->id_empresa : $empresa->id;?>">
            <h3>
                <button type="button" class="btn btn-default pull-right status_servico glyphicon glyphicon-download">
                    Status: <span class="status" data-item="<?php echo isset($item->id) ? $item->id : '';?>"><?php echo isset($item->id) ? 'Editando' : 'Novo';?></span>
                </button>
            </h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <p><strong>Id Empresa: </strong><?php echo isset($item->id_empresa) ? $item->id_empresa : $empresa->id;?></p>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
            <p><strong>Nome fantasia: </strong><?php echo isset($item->empresa_nome_fantasia) ? $item->empresa_nome_fantasia : $empresa->empresa_nome_fantasia;?></p>
        </div>
    </div>
</div>
<div class="alert alert-info">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group id_tipo">
                <label for="id_tipo">Tipo</label>
                <?php
                $config['valor'] = $imoveis_tipos; 
                $config['nome'] = 'id_tipo'; 
                $config['extra'] = 'id="id_tipo" data-sequencia="'.$sequencia.'" data-controller="imoveis_dest_listagem"'; 
                $config['class'] = 'campo-'.$sequencia; 
                echo form_select($config, set_value('id_tipo', (isset($item->id_tipo) ? $item->id_tipo : '') ) ); 
                $sequencia++;
                ?>
                <p class="id_tipo help-block"></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group id_cidade">
                <label for="id_cidade">Cidade</label>
                <?php
                $config['valor'] = $cidades; 
                $config['nome'] = 'id_cidade'; 
                $config['extra'] = 'id="id_cidade" data-sequencia="'.$sequencia.'" data-controller="imoveis_dest_listagem"'; 
                $config['class'] = 'campo-'.$sequencia; 
                echo form_select($config, set_value('id_cidade', (isset($item->id_cidade) ? $item->id_cidade : '') ) ); 
                $sequencia++;
                ?>
                <p class="id_cidade help-block"></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group negocio">
                <label for="negocio">Negocio</label>
                <?php
                $config['valor'] = $negocio; 
                $config['nome'] = 'negocio'; 
                $config['extra'] = 'id="negocio" data-sequencia="'.$sequencia.'" data-controller="imoveis_dest_listagem"'; 
                $config['class'] = 'campo-'.$sequencia; 
                echo form_select($config, set_value('negocio', (isset($item->negocio) ? $item->negocio : '') ) ); 
                $sequencia++;
                ?>
                <p class="negocio help-block"></p>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <?php
            $campo = array(
                            'tipo' => 'text',
                            'classe' => 'data_ini',
                            'sequencia' => $sequencia,
                            'controller' => 'imoveis_dest_listagem',
                            'class' => 'data_hora_pt_br',
                            'valor' => set_value('data_ini', ( isset($item->data_ini) ? $item->data_ini : '' ) ) ,
                            'titulo' => 'Data de inicio',
                        );
            echo set_campo_editavel($campo);
            unset($campo);
            $sequencia++;
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 ">
            <?php
            $campo = array(
                            'tipo' => 'text',
                            'classe' => 'data_fim',
                            'sequencia' => $sequencia,
                            'controller' => 'imoveis_dest_listagem',
                            'class' => 'data_hora_pt_br',
                            'valor' => set_value('data_fim', ( isset($item->data_fim) ? $item->data_fim : '' ) ) ,
                            'titulo' => 'Data de Fim',
                        );
            echo set_campo_editavel($campo);
            unset($campo);
            $sequencia++;
            ?>
        </div>
    </div>
</div>