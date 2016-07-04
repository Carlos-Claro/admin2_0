<style type="text/css"> 
    .resposta_empresa{margin-top: -15px;}
</style>
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
                <div class="control-group">
                    <div class="controls">
                        <a href="<?php echo $action_anexo;?>" id="edit_image"><button type="button" class="btn" >Editar Anexos</button></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_empresa">Empresa</label>
                    <input name="busca_empresa" type="text" class="form-control" id="busca_empresa" placeholder="Empresa" required value="<?php echo set_value('empresa', isset($empresa->empresa_nome_fantasia) ? $empresa->empresa_nome_fantasia : '');?>">
                </div>
                <div class="resposta_empresa"></div>
            </div>  
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo set_value('id_empresa', isset($empresa->id) ? $empresa->id : '');?>">
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="id_dica_tipo">Tipo</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $tipo_dica; 
                    $config['nome'] = 'id_dica_tipo'; 
                    $config['extra'] = ''; 
                    echo form_select($config, set_value('id_dica_tipo', isset($item->id_dica_tipo) ? $item->id_dica_tipo : '')); 
                    ?>
                </div>
            </div>	
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>   
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?php
                    $dt_inicio = date('d/m/Y');
                    if(isset($datas->data_inicio) && !empty($datas->data_inicio) && $datas->data_inicio != '0000-00-00 00:00:00')
                    {
                       $exp_a = explode('-',$datas->data_inicio);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_inicio = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0];
                    }
                ?>
                <label for="data_inicio">Data de Inicio</label>
                <div id="sandbox-container-dt-inicio">
                    <div class="input-group date">
                        <input type="text" name="data_inicio" id="data_inicioss" value="<?php echo set_value('data_inicio', $dt_inicio);?>" class="form-control">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>  
                </div>
                <script>
                    $('#sandbox-container-dt-inicio div').datepicker({
                        language: "pt-BR",
                        format: "dd/mm/yyyy",
                        autoclose: true,
                        daysOfWeekDisabled: "0,6",
                        todayHighlight: true
                    });
                </script>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                 <?php
                    $dt_fim = date('d/m/Y');
                    if(isset($datas->data_fim) && !empty($datas->data_fim) && $datas->data_inicio != '0000-00-00 00:00:00')
                    {
                       $exp_a = explode('-',$datas->data_fim);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_fim = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0];
                    }
                ?>
                <label for="data_fim">Data de Fim</label>
                <div id="sandbox-container-dt-fim">
                    <div class="input-group date">
                        <input type="text" name="data_fim" id="data_fim" value="<?php echo set_value('data_fim', $dt_fim);?>" class="form-control">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>  
                </div>
                <script>
                    $('#sandbox-container-dt-fim div').datepicker({
                        language: "pt-BR",
                        format: "dd/mm/yyyy",
                        autoclose: true,
                        daysOfWeekDisabled: "0,6",
                        todayHighlight: true
                    });
                </script>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descricao" ><?php echo set_value('descricao', isset($item->descricao) ? $item->descricao : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_descricao); ?>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

