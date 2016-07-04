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
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group id_servico">
                <label for="id_servico">Serviço</label>
                <?php
                $config['valor'] = $publicidade_areas; 
                $config['nome'] = 'id_servico'; 
                $config['extra'] = 'id="id_servico" data-sequencia="'.$sequencia.'" data-controller="publicidade_campanhas"'; 
                $config['class'] = 'campo-'.$sequencia; 
                echo form_select($config, set_value('id_servico', (isset($item->id_servico) ? $item->id_servico : '') ) ); 
                $sequencia++;
                ?>
                <p class="id_servico help-block"></p>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group id_servico">
                <label for="tipo">Tipo</label>
                <?php
                $config['valor'] = $tipo_; 
                $config['nome'] = 'tipo'; 
                $config['extra'] = 'id="tipo" data-sequencia="'.$sequencia.'" data-controller="publicidade_campanhas"'; 
                $config['class'] = 'campo-'.$sequencia; 
                echo form_select($config, set_value('tipo', (isset($item->tipo) ? $item->tipo : '') ) ); 
                $sequencia++;
                ?>
                <p class="tipo help-block"></p>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <?php
            $image['classe'] = 'banner';
            $image['image'] = ( isset($item->banner) && ! empty($item->banner) ) ? URL_IMAGE_PUBLICIDADE.$item->banner : NULL;
            $image['titulo'] = 'Banner';
            $image['controller'] = 'publicidade_campanhas';
            unset($campo);
            $sequencia++;
            echo set_image_editavel($image);
            unset($image);
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <?php
            $campo = array(
                            'tipo' => 'text',
                            'classe' => 'inicio',
                            'sequencia' => $sequencia,
                            'controller' => 'publicidade_campanhas',
                            'class' => 'data_hora_pt_br',
                            'valor' => set_value('inicio', ( isset($item->inicio) ? $item->inicio : '' ) ) ,
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
                            'classe' => 'termino',
                            'sequencia' => $sequencia,
                            'controller' => 'publicidade_campanhas',
                            'class' => 'data_hora_pt_br',
                            'valor' => set_value('termino', ( isset($item->termino) ? $item->termino : '' ) ) ,
                            'titulo' => 'Data de termino',
                        );
            echo set_campo_editavel($campo);
            unset($campo);
            $sequencia++;
            ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <?php
            $campo = array(
                            'tipo' => 'text',
                            'classe' => 'url',
                            'sequencia' => $sequencia,
                            'controller' => 'publicidade_campanhas',
                            'class' => '',
                            'valor' => set_value('url', ( isset($item->url) ? $item->url : '' ) ) ,
                            'titulo' => 'Direcionamento',
                        );
            echo set_campo_editavel($campo);
            unset($campo);
            $sequencia++;
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                <?php 
                $botao['classe'] = 'expande';
                $botao['valor'] = set_value('expande', ( isset($item->expande) ? $item->expande : '' ) ) ;
                $botao['texto']['on'] = 'Expande';
                $botao['texto']['off'] = 'Não expande';
                $botao['reverse'] = 1;
                $botao['controller'] = 'publicidade_campanhas';
                echo set_botao_editavel($botao);
                ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <?php 
                $botao['classe'] = 'janela_nova';
                $botao['valor'] = set_value('janela_nova', ( isset($item->janela_nova) ? $item->janela_nova : '' ) ) ;
                $botao['texto']['on'] = 'Nova Janela';
                $botao['texto']['off'] = 'Não nova janela';
                $botao['reverse'] = 1;
                $botao['controller'] = 'publicidade_campanhas';
                echo set_botao_editavel($botao);
                ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <?php 
                $botao['classe'] = 'diferente_interno';
                $botao['valor'] = set_value('diferente_interno', ( isset($item->diferente_interno) ? $item->diferente_interno : '' ) ) ;
                $botao['texto']['on'] = 'Diferente Interno';
                $botao['texto']['off'] = 'Não diferente interno';
                $botao['reverse'] = 1;
                $botao['controller'] = 'publicidade_campanhas';
                $image['classe'] = 'banner_alternativo';
                $image['image'] = ( isset($item->banner_alternativo) && ! empty($item->banner_alternativo) ) ? URL_IMAGE_PUBLICIDADE.$item->banner_alternativo : NULL;
                $image['titulo'] = 'Banner Alternativo';
                $image['controller'] = 'publicidade_campanhas';
                unset($campo);
                $sequencia++;
                $botao['complemento'] =  set_image_editavel($image);
                unset($image);
                
                echo set_botao_editavel($botao);
                ?>
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <?php
                    ?>
                </div>
            </div>
        </div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-base">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title"></h4>
</div>
<div class="modal-body">

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    </div>
</div>