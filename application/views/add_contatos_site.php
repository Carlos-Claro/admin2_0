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
                    <label for="ids">ID</label>
                    <input type="text" disabled="disabled" name="ids" class="form-control ids" value="<?php echo set_value('ids', isset($item->ids ) ? $item->ids : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome"  value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                    <p class="helper-block"><?php echo form_error('nome'); ?></p>
                </div>
            </div>   
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="origem">Local de Origem</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $local_origem;
                    $config['nome'] = 'origem'; 
                    $config['extra'] = ''; 
                    echo form_select($config, set_value('origem', isset($item->origem) ? $item->origem : '')); 
                    ?>
                </div>
            </div>	
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input name="email" type="text" class="form-control" id="email" placeholder="E-mail"  value="<?php echo set_value('email', isset($item->email ) ? $item->email : '');?>">
                    <p class="helper-block"><?php echo form_error('email'); ?></p>
                </div>
            </div>   
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="assunto">Assunto</label>
                    <input name="assunto" type="text" class="form-control" id="assunto" placeholder="Assunto"  value="<?php echo set_value('assunto', isset($item->assunto) ? $item->assunto : '');?>">
                    <p class="helper-block"><?php echo form_error('assunto'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div id="form-group">
                    <label for="data">Data</label>
                    <input type="text" name="data" class="form-control" id="data"  value="<?php echo set_value('data', (isset($item->data)? $item->data : date('d/m/Y H:i')));?>">
                    <p class="helper-block"><?php echo form_error('data'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="fone">Telefone</label>
                    <input name="fone" type="text" class="form-control" id="fone" placeholder="Fone" value="<?php echo set_value('fone', isset($item->fone) ? $item->fone : '');?>">
                    <p class="helper-block"><?php echo form_error('fone'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input name="cidade" type="text" class="form-control" id="cidade" placeholder="Cidade" value="<?php echo set_value('cidade', isset($item->cidade) ? $item->cidade : '');?>">
                    <p class="helper-block"><?php echo form_error('cidade'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input name="estado" type="text" class="form-control" id="estado" placeholder="Estado" value="<?php echo set_value('estado', isset($item->estado) ? $item->estado : '');?>">
                    <p class="helper-block"><?php echo form_error('estado'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="sms_enviado">SMS Enviados</label>
                    <input type="text" name="sms_enviado" class="form-control sms_enviado" id="sms_enviado" placeholder="SMS Enviados" value="<?php echo set_value('sms_enviado', isset($item->sms_enviado ) ? $item->sms_enviado : '');?>">
                    <p class="helper-block"><?php echo form_error('sms_enviado'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="portal">Portal</label>
                    <input name="portal" type="text" class="form-control" id="portal" placeholder="portal"  value="<?php echo set_value('portal', isset($item->portal) ? $item->portal : '');?>">
                    <p class="helper-block"><?php echo form_error('portal'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="busca_empresa">Empresa</label>
                    <input name="busca_empresa" type="text" class="form-control" id="busca_empresa" placeholder="Empresa"  value="<?php echo set_value('empresa', isset($empresa->empresa_nome_fantasia) ? $empresa->empresa_nome_fantasia : '');?>">
                </div>
                <div class="resposta_empresa"></div>
            </div>  
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo set_value('id_empresa', isset($empresa->id) ? $empresa->id : '');?>">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_item">ID do Item</label>
                    <input name="id_item" type="text" class="form-control" id="id_item" placeholder="ID do Item"  value="<?php echo set_value('id_item', isset($item->id_item) ? $item->id_item : '');?>">
                    <p class="helper-block"><?php echo form_error('id_item'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_cidade">ID da Cidade</label>
                    <input name="id_cidade" type="text" class="form-control" id="id_cidade" placeholder="ID da Cidade"  value="<?php echo set_value('id_cidade', isset($item->id_cidade) ? $item->id_cidade : '');?>">
                    <p class="helper-block"><?php echo form_error('id_cidade'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_tipo_item">ID Tipo Item</label>
                    <input name="id_tipo_item" type="text" class="form-control" id="id_tipo_item" placeholder="ID Tipo Item"  value="<?php echo set_value('id_tipo_item', isset($item->id_tipo_item) ? $item->id_tipo_item : '');?>">
                    <p class="helper-block"><?php echo form_error('id_tipo_item'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="tipo_negocio_item">Tipo Negocio Item</label>
                    <input name="tipo_negocio_item" type="text" class="form-control" id="tipo_negocio_item" placeholder="Tipo Negocio Item"  value="<?php echo set_value('tipo_negocio_item', isset($item->tipo_negocio_item) ? $item->tipo_negocio_item : '');?>">
                    <p class="helper-block"><?php echo form_error('tipo_negocio_item'); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="mensagem">Mensagem</label>
                    <textarea name="mensagem" class="form-control" id="mensagem" placeholder="Mensagem"><?php echo set_value('mensagem', isset($item->mensagem) ? $item->mensagem : '');?></textarea>
                    <p class="helper-block"><?php echo form_error('mensagem'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>

