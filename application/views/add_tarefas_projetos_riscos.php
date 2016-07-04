<?php
if( (isset($edita) && $edita ) ) :
?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-1 com-md-1 col-sm-1 col-xs-2"><br>
                <button type="button" class="btn  btn-default btn-lg deleta-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="riscos" title="Deletar Risco"><span class="glyphicon glyphicon-minus-sign"></span></button>
                <?php
                $atributo = '';
                if ( isset($item) ) :
                    $atributo = 'disabled="disabled"';
                    ?>
                    <button type="button" class="btn  btn-default btn-lg edita-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="riscos" title="Editar Risco"><span class="glyphicon glyphicon-plus-sign"></span></button>
                    <?php
                else :
                    ?>
                    <button type="button" class="btn  btn-default btn-lg salva-elemento" data-elemento="<?php echo $ordem;?>" data-salvo="<?php echo (isset($item)) ? 1 : 0; ?>" data-tipo="riscos" title="Salvar Risco"><span class="glyphicon glyphicon-save"></span></button>
                    <?php
                endif;
                ?>
            </div>  
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
                <div class="form-group">
                    <label>Impacto</label>
                    <?php
                    $config['valor'] = get_select_impacto();
                    $config['nome']  = 'riscos['.$ordem.'][impacto]';
                    $config['extra'] = 'id="impacto" '.$atributo;
                    echo form_select($config, set_value('impacto', isset($item->impacto) ? $item->impacto : ''));
                    ?>
                    <p class="help-block impacto">Marque o Impacto que esta risco trará ao projeto</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <div class="form-group">
                    <label>Probabilidade</label>
                    <?php
                    $config['valor'] = get_select_impacto();
                    $config['nome']  = 'riscos['.$ordem.'][probabilidade]';
                    $config['extra'] = 'id="probabilidade" '.$atributo;
                    echo form_select($config, set_value('probabilidade', isset($item->probabilidade) ? $item->probabilidade : ''));
                    ?>
                    <p class="help-block probabilidade">Qual a probabilidade disso acontecer?</p>
                </div>
                <p class="help-block impacto"></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Descrição do Risco</label>
                    <textarea name="riscos[<?php echo $ordem; ?>][descricao_risco]" id="descricao_risco" class="form-control"  <?php echo $atributo;?>><?php echo set_value('riscos['.$ordem.'][descricao_risco]', isset($item->descricao_risco) ? $item->descricao_risco : ''); ?></textarea>
                    <p class="help-block descricao_risco"></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Descrição do Impacto</label>
                    <textarea name="riscos[<?php echo $ordem;?>][descricao_impacto]" id="descricao_impacto"  class="form-control" <?php echo $atributo;?>><?php echo set_value('riscos['.$ordem.'][descricao_impacto]', isset($item->descricao_impacto) ? $item->descricao_impacto : ''); ?></textarea>
                    <p class="help-block descricao_impacto"></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Estratégia de Resposta</label>
                    <textarea name="riscos[<?php echo $ordem;?>][estrategia_resposta]" id="estrategia_resposta"  class="form-control"  <?php echo $atributo;?>><?php echo set_value('riscos['.$ordem.'][estrategia_resposta]', isset($item->estrategia_resposta) ? $item->estrategia_resposta : '');?></textarea>
                    <p class="help-block estrategia_resposta">O que vai ser feito em respota a este acontecimento</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Plano de Resposta</label>
                    <textarea name="riscos[<?php  echo $ordem;?>][plano_resposta]" id="plano_resposta"  class="form-control"  <?php echo $atributo;?>><?php echo set_value('riscos['.$ordem.'][plano_resposta]', isset($item->plano_resposta) ? $item->plano_resposta : '');?></textarea>
                    <p class="help-block plano_resposta">Como vamos alcançar esta estratégia de resposta</p>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label>Plano de Contigência</label>
                    <textarea name="riscos[<?php echo $ordem; ?>][plano_contingencia]" id="plano_contingencia"  class="form-control"  <?php echo $atributo;?>><?php echo set_value('riscos['.$ordem.'][plano_contingencia]', isset($item->plano_contingencia) ? $item->plano_contingencia : '');?></textarea>
                    <p class="help-block plano_contingencia"></p>
                </div>
            </div>
        </div>  
    </div>  
<?php
else :
    ?>
    <div class="alert alert-success elementos elemento-<?php echo $ordem;?>">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Impacto
                        </h4>
                        <p>
                            <?php 
                            foreach( get_select_impacto() as $frequencia )
                            {
                                if ( $item->impacto == $frequencia->id )
                                {
                                    echo $frequencia->descricao; 
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Probabilidade
                        </h4>
                        <p>
                            <?php 
                            foreach( get_select_impacto() as $frequencia )
                            {
                                if ( $item->probabilidade == $frequencia->id )
                                {
                                    echo $frequencia->descricao; 
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Descrição do Risco
                        </h4>
                        <p>
                            <?php 
                            echo $item->descricao_risco;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Descrição do Impacto
                        </h4>
                        <p>
                            <?php 
                            echo $item->descricao_impacto;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Estratégia de Resposta
                        </h4>
                        <p>
                            <?php 
                            echo $item->estrategia_resposta;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Plano Resposta
                        </h4>
                        <p>
                            <?php 
                            echo $item->plano_resposta;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading">
                            Plano Contingência
                        </h4>
                        <p>
                            <?php 
                            echo $item->plano_contingencia;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>  
    </div>  
    <?php
endif;
?>

