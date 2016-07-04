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
             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control titulo" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div> 
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control link" id="link" readonly="readonly" placeholder="link" value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="id_categoria">Categorias</label>
                <div class="controls ">
                    <?php 
                    $config['valor'] = $pai; 
                    $config['nome'] = 'id_categoria'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id_categoria', (isset($selecionado) && $selecionado) ? $selecionado : $id_categoria)); 
                ?>
                </div>
            </div>	
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?php
                if( isset($item->data ) && $item->data)
                {
                    $exp_a = explode('-', $item->data);
                    $data = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                }
                ?>
                <div class="form-group">
                    <label for="data">Data</label>
                    <input name="data" type="text" class="form-control" id="data" placeholder="Data"  value="<?php echo set_value('data', isset($data) ? $data : date('d-m-Y') );?>">
                </div>
            </div>   
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="listar">Listar</label>
                    <input name="listar" type="text" class="form-control" id="listar" placeholder="Listar" value="<?php echo set_value('listar', isset($item->listar) ? $item->listar : '');?>">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

