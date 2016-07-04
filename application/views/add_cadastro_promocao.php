<style type="text/css">
    .resposta_cidade{margin-top: -15px;}
    .resposta_bairro{margin-top: -15px;}
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
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo"  value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                    <p class="helper-block"><?php echo form_error('titulo'); ?></p>
                </div>
            </div>
             <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div id="form-group">
                    <label for="data">Data Sorteio</label>
                    <input type="text" name="data" class="form-control" id="data"  value="<?php echo set_value('data', (isset($item->data)? $item->data : date('Y-m-d')));?>">
                </div>
            </div>
        </div>
    </div>
    <?php 
    if ( isset($item->id) )
    {
        ?>
    
        <div class="alert alert-warning espaco-arquivos <?php if ( isset($item->id_arquivo) && ! empty($item->id_arquivo) ){ echo 'hide'; }?>" data-id-pai="<?php echo $item->id;?>" data-tipo="<?php echo $image_tipo->id;?>" data-pasta="<?php echo $image_tipo->pasta;?>" data-formatos="jpeg|png|gif">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label for="upload">
                            <div class="upload"></div>
                        </label>
                        <div class="upload_status"></div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="erro-upload"></div>
                    <div class="status">

                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-warning espaco-thumbs" >
            <?php
            if ( isset($item->id_arquivo) && ! empty($item->id_arquivo) )
            {
                ?>
            <center>
                <img src="<?php echo URL_IMAGE.$item->pasta.$item->arquivo;?>" class="image" data-id-pai="<?php echo $item->id;?>" >
            </center>
            <button type="button" class="btn btn-danger form-control deleta-image" data-id-pai-arquivo="<?php echo $item->id_pai_arquivo;?>" >Deletar</button>
                <?php
            }
            ?>
        </div>
        
        <?php
    }
    else
    {
        ?>
    <div class="alert alert-info">
        <h3>Salve a promoção para liberar o banner... </h3>
    </div>
        <?php
    }
    ?>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <?php endif; ?>
</form>
