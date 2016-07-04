<?php 
if(isset($itens) && $itens):
    foreach ($itens as $item) : 
        switch($item['item']->status):

            case 'Retorno':
                $alert = 'alert-warning';
                break;
            case 'Fechado':
                $alert = 'alert-danger';
                break;
            case 'Fechado com Sucesso':
                $alert = 'alert-success';
                break;
            default:
                $alert = 'alert-info';
                break;
        endswitch; 
    
    ?>
    <div class="alert <?php echo $alert; ?>">
        <div class="row" id="<?php echo $item['item']->id;?>">
            <fieldset class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <legend>Ocorrência: <?php echo (strlen($item['item']->texto) > 30 ? substr($item['item']->texto, 0, 35).'... Nº '.$item['item']->id : $item['item']->texto.' Nº '.$item['item']->id ); ?> 
                        <span class="pull-right glyphicon glyphicon-plus oc-interacoes" data-item="<?php echo $item['item']->id; ?>"></span>
                    <?php if($item['item']->status != 'Fechado' && $item['item']->status !== 'Fechado com Sucesso' && $this->sessao['id'] == $item['item']->id_usuario_ativo) : ?>
                        <span class="pull-right glyphicon glyphicon-play oc-play play-<?php echo $item['item']->id; ?>" data-item="<?php echo $item['item']->id; ?>"></span>
                    <?php endif;?>

                </legend>
                <div class="row">
                    <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12">  
                        <div class="form-group">
                            <h5>Status: <?php echo $item['item']->status;?></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12">  
                        <div class="form-group">
                            <h5>Assunto: <?php echo nl2br($item['item']->assunto);?></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">  
                        <div class="form-group">
                            <h5>Responsável: <?php echo $item['item']->usuario_ativo;?></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">  
                        <div class="form-group">
                            <h5>Tempo do chamado: <?php 
                            
                            echo $item['item']->dias.' Dias  e '.$item['item']->horas.' Horas';
                            //echo date('H:i:s',$horas);
                            //echo 'Dias: '.$dias.' e Horas:'.$horas;
                            //echo $item['item']->dias.' Dias ';
                            /*
                            $data_final = NULL;
                            $hora_final = NULL;

                            if($item['item']->status == 'Fechado' || $item['item']->status == 'Fechado com Sucesso') :
                                $data_final = $item['interacoes'][0]->data_inclusao;
                                $hora_final = $item['interacoes'][0]->hora_inclusao;
                            endif;
                            $tempo = calcular_intervalo_tempo($item['item']->data_inicial, $item['item']->hora_inicial, $data_final, $hora_final);
                            echo $tempo['dias'].' Dias  e '.$tempo['horas'].' Horas';
                             */
                            ?>
                            </h5>
                        </div>
                    </div>

                    <?php if($item['item']->status == 'Retorno'): ?>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">  
                            <div class="form-group">
                                <!-- <h5>Retorno: <?php //echo $item['item']->data_retorno.' Periodo: '.$item['item']->periodo_retorno ;?></h5> -->
                                <h5>Retorno: <?php echo $item['item']->data_retorno;?></h5>
                            </div>
                        </div>    
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php foreach($item['interacoes'] as $interacao): ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row dados-interacoes-<?php echo $item['item']->id; ?> alert-oc-in">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <h5>Cliente: <?php echo $interacao->nome_contato; ?></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <h5>Inclusão: <?php echo $interacao->data_inclusao.' '.$interacao->hora_inclusao; ?></h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">  
                        <div class="form-group">
                            <h5>Usuário POW: <?php echo $interacao->nome_usuario;?></h5>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <h5>Descrição: <?php echo nl2br($interacao->obs); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;
            if($item['item']->status != 'Fechado' && $item['item']->status !== 'Fechado com Sucesso') : 
                if ( $this->sessao['id'] == $item['item']->id_usuario_ativo ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row" data-item="<?php echo $interacao->id_empresas_ocorrencia; ?>">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-interacao" data-item="<?php echo $item['item']->id; ?>">Nova Interação</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                else :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row" data-item="<?php echo $interacao->id_empresas_ocorrencia; ?>">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-warning btn-opiniao" data-item="<?php echo $item['item']->id; ?>">Opinar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endif;
                ?>
                <?php 
                            
            endif; 
            ?>
        </div>
    </div>
<?php endforeach;?>

<div class="modal fade in-add" id="modal-in-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row alert alert-danger error-add-contato-in">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 error-contato-in"></div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  nao-opiniao">
                        <div class="form-group status-in"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="prioridade-in">Prioridade</label>
                            <select name="prioridade-in" id="prioridade-in" class="form-control">
                                <?php for($i=1; $i <= 9; $i++): ?>
                                    <option value="<?php echo $i; ?>"> <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  nao-opiniao">
                        <div class="form-group contato-empresa-in"></div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12  nao-opiniao">
                        <br>
                        <button type="button" class="btn btn-default btn-block" name="novo-contato-in" id="novo-contato-in" data-item="<?php echo $item['item']->id; ?>" data-toggle="modal" data-target="#modal-contato-add" >Novo Contato</button>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12  nao-opiniao">
                        <br>
                        <button type="button" class="btn btn-success btn-block" id="editar-contato" data-sufix="in">Editar Contato</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nao-opiniao">
                        <div class="form-group setor-in" data-tipo="in"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nao-opiniao">
                        <div class="form-group usuario-setor-in"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nao-opiniao">
                        <label for="data-retorno-inicio-in">Data de Retorno Inicio</label>
                        <div class="input-group date" id="datetimepickerInicioIn">
                            <input type="text" class="form-control" data-date-format="DD/MM/YYYY HH:mm" name="data-retorno-inicio-in" id="data-retorno-inicio-in" value="<?php echo date('d/m/Y H:i'); ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <!--
                        <div class="form-group">
                            <label for="data-retorno-inicio-in">Data de Retorno Inicio</label>
                            <input type="text" name="data-retorno-inicio-in" id="data-retorno-inicio-in" value="<?php //echo date('d/m/Y H:i'); ?>" class="form-control">
                        </div>-->
                        <!--
                        <label for="data-retorno-in">Data de Retorno</label>
                        <div id="sandbox-container">
                            <div class="input-group date">
                                <input type="text"  name="data-retorno-in" id="data-retorno-in" value="" class="form-control">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-th"></i>
                                </span>
                            </div>
                        </div>
                        <link rel="stylesheet" href="<?php //echo base_url().'css/datepicker.css' ?>" />
                        <script src="<?php //echo base_url().'js/datepicker/bootstrap-datepicker.js'; ?>"></script>
                        <script>
                            $('#sandbox-container div').datepicker({
                                format: "dd/mm/yyyy",
                                autoclose: true,
                                language: "pt-BR",
                                todayHighlight: true
                            });
                        </script>-->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nao-opiniao">
                        <label for="data-retorno-fim-in">Data de Retorno Fim</label>
                        <div class="input-group date" id="datetimepickerFimIn">
                            <input type="text" class="form-control"  data-date-format="DD/MM/YYYY HH:mm" name="data-retorno-fim-in" id="data-retorno-fim-in" value="<?php echo date('d/m/Y H:i'); ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <!--<div class="form-group">
                            <label for="data-retorno-fim-in">Data de Retorno Fim</label>
                            <input type="text" name="data-retorno-fim-in" id="data-retorno-fim-in" value="<?php echo date('d/m/Y H:i'); ?>" class="form-control">
                        </div>-->
                        <!--
                        <div class="form-group">
                            <label for="periodo-in">Periodo</label>
                            <input type="text" name="periodo-in" id="periodo-in" class="form-control">
                        </div> -->
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="descricao_in">Descrição</label>
                        <textarea name="descricao_in" id="descricao_in" class="form-control"></textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Emails <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu emails-in" role="menu"></ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 nao-opiniao">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="save-interacao" data-item="">Salvar</button>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 sim-opiniao">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="opiniao-interacao" data-item="">enviar opinião</button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div><br>
                <div class="row nao-opiniao" id="legendas">
                    <fieldset>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <legend>Dicas</legend>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><small><b>* Campo Status e Descrição são Obrigatórios</b></small></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><small><b>* Quanto maior o numero da prioridade mais rápido deve ser dado retorno.</b></small></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><small><b>* "Contatos da Empresa" é a pessoa com que falou no momento da interação.</b></small></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><small><b>* Se não selecionar nenhum "Usuario POW" a ocorrência é sua.</b></small></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><small><b>* Se não houver data de retorno apague os valores padrões.</b></small></p>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>