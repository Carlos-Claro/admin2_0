<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
        <div class="alert">
            <div class="row">
                <div class="form-group">
                    <a href="<?php echo base_url().'noticias/adicionar';?>" class="btn btn-primary">Add nova notícia</a>
                </div>
            </div>
        </div>
        <div class="alert">
            <div class="row">
                <div class="form-group">
                    <label for="id">ID</label>
                    <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <input type="hidden" id="editavel" class="form-control editavel" value="<?php echo $editavel;?>">
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
                    <input name="data" type="text" class="form-control" id="data" placeholder="Data"  value="<?php echo set_value('data', $data);?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="texto">Texto</label>
                    <textarea rows="8" name="texto" class="form-control" id="texto" resize="none" placeholder="Texto"><?php echo set_value('texto', isset($item->texto) ? $item->texto : '');?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-warning">
        <div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="id_categoria">Categorias</label>
                <div class="controls ">
                    <?php 
                        $config['valor'] = $categoria; 
                        $config['nome'] = 'id_categoria'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_categoria', isset($item->id_categoria) ? $item->id_categoria : '')); 
                    ?>
                </div>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="id_editoria">Editorias</label>
                <div class="controls ">
                    <?php 
                        $config['valor'] = $editoria; 
                        $config['nome'] = 'id_editoria'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_editoria', isset($item->id_editoria) ? $item->id_editoria : '')); 
                    ?>
                </div>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="id_canal">Canal</label>
                <div class="controls ">
                    <?php 
                        $config['valor'] = $canal; 
                        $config['nome'] = 'id_canal'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canal', isset($item->id_canal) ? $item->id_canal : '')); 
                    ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="tipo_area">Tipo Area</label>
                    <?php 
                        $config['valor'] = $tipo_area; 
                        $config['nome'] = 'tipo_area'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('tipo_area', isset($item->tipo_area) ? $item->tipo_area : 0)); 
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_canais">Canal 2014</label>
                        <?php 
                            $config['valor'] = $canal_2014; 
                            $config['nome'] = 'id_canais'; 
                            $config['extra'] = 'class="form-control"'; 
                            echo form_select($config, set_value('id_canais', isset($item->id_canais) ? $item->id_canais : '')); 
                        ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link_url">Link URL</label>
                    <input name="link_url" type="text" class="form-control" id="link_url" placeholder="Link URL" value="<?php echo set_value('link_url', isset($item->link_url) ? $item->link_url : '');?>">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="link_descricao">Link Descricao</label>
                    <input name="link_descricao" type="text" class="form-control" id="link_descricao" placeholder="Link Descricao" value="<?php echo set_value('link_descricao', isset($item->link_descricao) ? $item->link_descricao : '');?>">
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
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="vitrine_canal" value="1" id="vitrine_canal" <?php echo set_value('vitrine_canal', (isset($item->vitrine_canal) && $item->vitrine_canal == 1) ? 'checked="checked"' : '');?>>Exibir na Home do Canal
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="canal_noticias" value="1" id="canal_noticias" <?php echo set_value('canal_noticias', (isset($item->canal_noticias) && $item->canal_noticias == 1) ? 'checked="checked"' : '');?>>Exibir no Canal de Noticias
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="newsletter" value="1" id="newsletter" <?php echo set_value('newsletter', (isset($item->newsletter) && $item->newsletter == 1) ? 'checked="checked"' : '');?>> Newsletter
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
                    $replace_a = array('[ano]','[mes]' );
                    $replace_b = array($item->ano, $item->mes );
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
    <?php
    if ( isset($log) ) :
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
            <div class="alert alert-success">
            <ul class="list-group">
                <?php
                foreach ($log['itens'] as $logs) :
                ?>
                <li class="list-group-item">
                    <?php 
                    echo '- '.$logs->nome.' - '.$logs->data.' - '.str_replace('ar', 'ou', $logs->acao);
                    ?>
                </li>
                <?php
                endforeach;
                ?>
            </ul>
            </div>
        </div>
    </div>
    <?php
    endif;
    ?>
    <?php
    if ( isset($item->logs['itens']) && $item->logs['qtde'] > 0 ) :
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12">
            <?php
            if ( isset($item->logs_totais['itens']) && $item->logs_totais['qtde'] > 0 ) :
            ?>
            <div class="alert alert-info">
            <?php 
            echo 'Total Views: '.$item->logs_totais['itens'][0]->views;
            echo '<br> Total Clicks: '.$item->logs_totais['itens'][0]->clicks;
            ?>
            </div>
            <?php
            endif;
            ?>
            <div class="alert alert-success">
                <ul class="list-group">
                <?php
                foreach ($item->logs['itens'] as $log_dia) :
                ?>
                <li class="list-group-item">
                    <?php 
                    echo '- '.$log_dia->id.' - '.$log_dia->data.' - views: '.$log_dia->views.' - clicks: '.$log_dia->clicks;
                    ?>
                </li>
                <?php
                endforeach;
                ?>
            </ul>
            </div>
        </div>
    </div>
    <?php
    endif;
    ?>
    
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

