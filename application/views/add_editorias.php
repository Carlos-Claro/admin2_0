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
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                </div>
            </div>   
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input name="link" type="text" class="form-control" id="link" placeholder="Link" required value="<?php echo set_value('link', isset($item->link) ? $item->link : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cor">Cor</label>
                    <div class="form-group">
                        <div class="input-group cor" >
                            <input type="text" id="cor" name="cor" value="<?php echo set_value('cor', isset($item->cor ) ? '#'.$item->cor : '');?>" class="form-control" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                        <script>
                            $(function(){
                                $('.cor').colorpicker();
                            });
                        </script>
                    </div>
                </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_canais">Canal</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $canais; 
                        $config['nome'] = 'id_canais'; 
                        $config['extra'] = ''; 
                        echo form_select($config, set_value('id_canais', isset($item->id_canais) ? $item->id_canais : '')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

