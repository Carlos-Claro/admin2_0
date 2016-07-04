<script type="text/javascript">
    $(function(){
        CKEDITOR.replace( 'texto' );
    });
</script>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <span class="alert alert-danger"><?php echo validation_errors(); ?></span>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
    <div class="form-group">
        <label for="id">ID</label>
        <input type="text" disabled="disabled" name="id_newsletter" class="form-control" value="<?php echo set_value('id_newsletter', isset($item->id_newsletter ) ? $item->id_newsletter : '');?>">
    </div>
    <?php endif; ?>
    <?php echo $html;?>
    <div class="row alert alert-info">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="texto">Texto</label>
                <textarea name="texto" class="form-control"  id="texto" placeholder="Texto">
                    <?php echo set_value('texto', isset($item->texto) ? $item->texto : '');?>
                </textarea>
            </div>
        </div>   
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="id_empresa">Empresa </label>
                <input name="id_empresa" type="text" class="form-control" id="id_empresa" placeholder="Empresa" required value="<?php echo set_value('id_empresa', isset($item->id_empresa) ? $item->id_empresa : '');?>">
            </div>
        </div>  
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="texto_titulo">Texto do Titulo</label>
                <input name="texto_titulo" type="text" class="form-control" id="texto_titulo" placeholder="Texto do Titulo" value="<?php echo set_value('texto_titulo', isset($item->texto_titulo ) ? $item->texto_titulo : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="email_nw">Email</label>
                <input name="email_nw" type="email" class="form-control" id="email_nw" placeholder="Email" required value="<?php echo set_value('email_nw', isset($item->email_nw) ? $item->email_nw : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="cor_texto">Cor do Texto</label>
                <input name="cor_texto" type="color" class="form-control" id="cor_texto" placeholder="" value="<?php echo set_value('cor_texto', isset($item->cor_texto) ? $item->cor_texto : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="cor_fundo">Cor do Fundo</label>
                <input name="cor_fundo" type="color" class="form-control" id="cor_fundo" placeholder="" value="<?php echo set_value('cor_fundo', isset($item->cor_fundo ) ? $item->cor_fundo : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="cor_botao">Cor do Botão</label>
                <input name="cor_botao" type="color" class="form-control" id="cor_botao" placeholder="" value="<?php echo set_value('cor_botao', isset($item->cor_botao ) ? $item->cor_botao : '');?>">
            </div>
        </div>
    </div>
    <div class="checkbox">
        <label>
            <input name="aberto" type="checkbox" value="1" <?php if (isset($item->aberto) && $item->aberto == "1"): echo "checked=checked"; endif;?>> Aberto
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input name="ativo" type="checkbox" value="1" <?php if (isset($item->ativo) && $item->ativo == "1"): echo "checked=checked"; endif;?>> Ativo
        </label>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

