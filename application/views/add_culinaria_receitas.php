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
                    <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item['item']->id ) ? $item['item']->id : '');?>">
                </div>
                <div class="control-group">
                    <div class="controls">
                        <a href="<?php echo $action_anexo;?>" id="edit_image"><button type="button" class="btn" >Editar Anexos</button></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php 
    //var_dump($item['image_moderar']);
    if ( isset( $item['image_moderar'] ) && count( $item['image_moderar'] ) > 0 ) :
        ?>
        <div class="alert alert-success">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><p>Arquivos a moderar:</p></div>
                <?php
                foreach( $item['image_moderar'] as $image ) :
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 elemento-<?php echo $image->id; ?>">
                        <center><img src="<?php echo base_url().$image->pasta.$image->arquivo; ?>" class="img-responsive" ></center>
                        <p class="text-center"><strong><?php echo $image->descricao;?></strong></p>
                        <center>
                            <button class="btn btn-primary moderar" type="button" data-item="<?php echo $image->id;?>">Moderar</button>
                            
                            <!-- <button class="btn btn-danger deleta-image" type="button" data-item="<?php //echo $image->id;?>">Deletar</button> -->
                        </center>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
            
        <?php
    endif;
    ?>
    
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-6 col-ms-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item['item']->titulo ) ? $item['item']->titulo : '');?>">
                </div>
            </div>
            <div class="col-lg-6 col-ms-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" value="<?php echo set_value('nome', isset($item['item']->nome) ? $item['item']->nome : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-ms-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="ingredientes">Ingredientes</label>
                    <textarea name="ingredientes" class="form-control"  id="ingredientes" placeholder="Ingredientes"><?php echo set_value('ingredientes', isset($item['item']->ingredientes) ? $item['item']->ingredientes : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_ingredientes);?>
                </div>
            </div>
            <div class="col-lg-12 col-ms-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="modo_preparo">Modo de Preparo</label>
                    <textarea name="modo_preparo" class="form-control"  id="modo_preparo" placeholder="Modo de Preparo"><?php echo set_value('modo_preparo', isset($item['item']->modo_preparo) ? $item['item']->modo_preparo : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_modo_preparo);?>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="id_categoria">Categorias</label>
                <div class="controls ">
                    <?php      
                    $config['valor'] = $pai; 
                    $config['nome'] = 'id_categoria'; 
                    $config['extra'] = 'class="form-control"'; 
                    echo form_select($config, set_value('id', (isset($selecionado) && $selecionado) ? $selecionado : isset($item['item']->id_categoria) ? $item['item']->id_categoria : 0 )); 
                ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?php 
                
                if(isset($item['item']->data_cadastro) && $item['item']->data_cadastro)
                {
                    $exp_a = explode('-', $item['item']->data_cadastro);
                    $data = $exp_a[2].'-'.$exp_a[1].'-'.$exp_a[0];
                }
                
                ?>
                <div class="form-group">
                    <label for="data_cadastro">Data de Cadastro</label>
                    <input name="data_cadastro" type="date" class="form-control" id="data_cadastro" value="<?php echo set_value('data_cadastro', isset($data) ? $data : date('d-m-Y'));?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email', isset($item['item']->email) ? $item['item']->email : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input name="telefone" type="text" class="form-control" id="telefone" placeholder="Telefone" value="<?php echo set_value('telefone', isset($item['item']->telefone) ? $item['item']->telefone : '');?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <label>
                  <input name="liberado" type="checkbox" value="1" <?php if (isset($item['item']->liberado) && $item['item']->liberado == "1"): echo "checked=checked"; endif;?>> Liberado
                </label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <label>
                  <input name="aceito" type="checkbox" value="1" <?php if (isset($item['item']->aceito) && $item['item']->aceito == "1"): echo "checked=checked"; endif;?>> Aceito
                </label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

