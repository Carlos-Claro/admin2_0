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
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="titulo">Titulo</label>
                    <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link ) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="id_canais_setor">Setor Pai</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $pai; 
                        $config['nome'] = 'id_canais_setor'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canais_setor', (isset($selecionado) && $selecionado) ? $selecionado: $canal)); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <?php
                    $dt_publicacao = NULL;
                    if(isset($item->data_publicacao) && !empty($item->data_publicacao))
                    {
                       $exp_a = explode('-',$item->data_publicacao);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_publicacao = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0].' '.$exp_b[1];
                    }
                ?>
                <div class="form-group">
                    <label for="data_publicacao">Data de Publicação</label>
                    <input type="text" name="data_publicacao" id="data_publicacao" value="<?php echo set_value('data_publicacao', isset($dt_publicacao) ? $dt_publicacao : date('d-m-Y H:i:s') ); ?>" class="form-control">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <?php
                    $dt_inicio = NULL;
                    if(isset($item->data_acao_inicio) && !empty($item->data_acao_inicio))
                    {
                       $exp_a = explode('-',$item->data_acao_inicio);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_inicio = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0].' '.$exp_b[1];
                    }
                ?>
                <div class="form-group">
                    <label for="data_acao_inicio">Data de Inicio</label>
                    <input type="text" name="data_acao_inicio" id="data_acao_inicio" value="<?php echo set_value('data_acao_inicio', isset($dt_inicio) ? $dt_inicio : '' ); ?>" class="form-control">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                 <?php
                    $dt_fim = NULL;
                    if(isset($item->data_acao_fim) && !empty($item->data_acao_fim))
                    {
                       $exp_a = explode('-',$item->data_acao_fim);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_fim = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0].' '.$exp_b[1];
                    }
                ?>
                <div class="form-group">
                    <label for="data_acao_fim">Data de Fim</label>
                    <input type="text" name="data_acao_fim" id="data_acao_fim" value="<?php echo set_value('data_acao_fim', isset($dt_fim) ? $dt_fim : '' ); ?>" class="form-control">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="ordem">Ordem</label>
                    <input name="ordem" type="text" class="form-control" id="ordem" placeholder="Ordem"  value="<?php echo set_value('ordem', isset($item->ordem ) ? $item->ordem : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php //var_dump($item->descricao); ?>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descricao" ><?php echo set_value('descricao', isset($item->descricao ) ? $item->descricao : '');?></textarea>
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
            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="title" type="text" class="form-control" id="title" placeholder="Title"  value="<?php echo set_value('title', isset($item->title ) ? $item->title : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 <div class="form-group">
                    <label for="description">Description</label> Caracateres restantes:<span id="char-digitado">140</span> (Máximo de 140) 
                    <textarea name="description"  class="form-control" id="description" placeholder="description" ><?php echo set_value('description', isset($item->description ) ? $item->description : '');?></textarea>
                 </div>
            </div>
        </div>
    </div>
    <div class="checkbox">
        <label>
          <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
        </label>
    </div>
    <div class="checkbox">
        <label>
          <input name="exibe_destaque" type="checkbox" value="1" <?php if (isset($item->exibe_destaque) && $item->exibe_destaque == "1"): echo "checked=checked"; endif;?>> Exibe Destaque
        </label>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

