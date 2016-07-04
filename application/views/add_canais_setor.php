<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" enctype="multipart/form-data">
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
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                 <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link ) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_canais">Canal Ligação</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $canais; 
                        $config['nome'] = 'id_canais'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canais', isset($selecionado) ? $selecionado: $canal)); 
                        ?>
                    </div>
                </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_canais_noticias">Canais Noticias</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $canais_noticias; 
                        $config['nome']  = 'id_canais_noticias'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canais_noticias', (isset($item->id_canais_noticias ) && $item->id_canais_noticias) ? $item->id_canais_noticias: '')); 
                        ?>
                    </div>
                </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_pai">Setor Ligação</label>
                    <div class="controls">
                        <?php 
                        if(isset($item->id_pai)) : $valor_pai = $item->id_pai;
                        elseif(isset($id_pai)) : $valor_pai = $id_pai;
                        else : $valor_pai = '';
                        endif;
                        $config['valor'] = $pai; 
                        $config['nome'] = 'id_pai'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_pai', $valor_pai)); 
                        ?>
                    </div>
                 </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="hexa_cor_titulo">Cor do Titulo</label>
                    <div class="form-group">
                        <div class="input-group hexa_cor_titulo" >
                            <input type="text" id="hexa_cor_titulo" name="hexa_cor_titulo" value="<?php echo set_value('hexa_cor_titulo', isset($item->hexa_cor_titulo ) ? '#'.$item->hexa_cor_titulo : '');?>" class="form-control" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                        <script>
                            $(function(){
                                $('.hexa_cor_titulo').colorpicker();
                            });
                        </script>
                    </div>
                </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="ordem">Ordem</label>
                    <input name="ordem" type="text" class="form-control" id="ordem" placeholder="Ordem" value="<?php echo set_value('ordem', isset($item->ordem ) ? $item->ordem : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descricao"><?php echo set_value('descricao', isset($item->descricao ) ? $item->descricao : '');?></textarea>
                    <?php echo display_ckeditor($ckeditor_descricao);?>
                </div>    
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4>Meta Tags</h4>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="title" type="text" class="form-control" id="title" placeholder="Title" value="<?php echo set_value('title', isset($item->title ) ? $item->title : '');?>">
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="description">Description</label> Caracateres restantes:<span id="char-digitado">140</span> (Máximo de 140) 
                    <textarea name="description" class="form-control" id="description" placeholder="Description"><?php echo set_value('description', isset($item->description ) ? $item->description : '');?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="checkbox">
        <label>
          <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
        </label>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

