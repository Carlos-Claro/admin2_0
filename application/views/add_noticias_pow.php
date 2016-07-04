<div class="alert alert-success">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if ( isset($mostra_id) && $mostra_id ) : ?>
            <h1>Editando Noticia</h1>
            <?php else: ?>
            <h1>Adicionando Noticia</h1>
            <?php endif; ?>
            <ul class="list-group list-inline">
                <li class="list-group-item"><a href="<?php echo base_url();?>noticias_pow/listar" class="btn btn-warning">Listagem</a></li>
                <li class="list-group-item"><a href="<?php echo base_url();?>noticias_pow/adicionar" class="btn btn-warning">Adicionar novo</a></li>
            </ul>
        </div>
    </div>
</div>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    
        <div class="alert">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control titulo" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?php
                $data = date('d-m-Y H:i');
                if(isset($item->data) && $item->data)
                {
                    $data = date('d-m-Y H:i', ($item->data));
                }
                ?>
                <div class="form-group">
                    <label for="data">Data</label>
                    <input name="data" type="text" class="form-control data_hora_pt_br" id="data" placeholder="Data"  value="<?php echo set_value('data', $data);?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="texto">Texto</label>
                    <textarea rows="8" name="texto" class="form-control" id="texto" resize="none" placeholder="Texto"><?php echo set_value('texto', isset($item->texto) ? $item->texto : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_texto);?>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-danger">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label>Exibição</label>
                <br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="vitrine" value="1" id="vitrine" <?php echo set_value('vitrine', (isset($item->vitrine) && $item->vitrine == 1) ? 'checked="checked"' : '');?>> Exibir na Home 
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Imagens:</h3>
            </div>
        </div>
        <div class="row">    
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="upload"></div>
                        <div class="upload_status"></div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                        <div class="status"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-9 col-md-offset-3 col-md-9 col-sm-offset-4 col-sm-8 col-xs-offset-6 col-xs-6">
                <?php 
                if ( isset($item->images) ) :
                    $replace_a = array('[ano]','[mes]', '[id]' );
                    $replace_b = array($item->ano, $item->mes, $id_empresa );
                    foreach ( $item->images as $image ) :
                    ?>
                        <div class="col-lg-4 col-md-4 col-sm- col-xs-6 image-<?php echo $image->id;?> alert alert-success">
                            <div class="media text-center">
                                <img src="<?php echo URL_IMAGE.str_replace($replace_a, $replace_b, $image->pasta).$image->arquivo; ?>" class="img-responsive arquivo-exibe-upload media-object">
                                <div class="media-body">
                                    <h4 class="media-heading text-center"><?php echo ! empty ($image->descricao_pai) ? $image->descricao_pai : '* Sem Legenda';?></h4>
                                    <div class="btn btn-danger remover-image" data-item="<?php echo $image->id;?>" data-noticia="<?php echo $item->id;?>">Remover</div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

