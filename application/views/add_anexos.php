<div class="container">
    <a href="<?php echo $link_voltar;?>" class="btn btn-info">Voltar ao Item</a>
    <form action="<?php echo base_url();?>anexos/adicionar_image" class="post" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend><?php echo isset($itens->titulo) ? $familia.' - '.$itens->titulo : $familia ;?></legend>
            <input type="hidden" name="familia" value="<?php echo $familia;?>">
            <input type="hidden" name="id_pai" id="id_pai" value="<?php echo $id_pai;?>">
            <div class="form-group">
                <label for="image_tipo">Tipo de Imagem</label>
                <div class="controls">
                    <?php 
                    $config['valor'] = $imagens; 
                    $config['nome'] = 'id_image_tipo'; 
                    $config['extra'] = 'class="form-control" id="id_image_tipo"'; 
                    echo form_select($config, set_value('id_image_tipo',set_value('id_image_tipo', array()))); 
                    ?>
                </div>
            </div>
            <br>
            <div class="opcoes-form">
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                        <span>Selecionar Arquivos...</span>
                    <input  id="fileupload" type="file" name="files[]" data-url="<?php echo $data_url; ?>" multiple>
                </span>
                <script src="<?php echo base_url().'/js/vendor/jquery.ui.widget.js';?>"></script>
                <script src="<?php echo base_url().'/js/jquery.iframe-transport.js';?>"></script>
                <script src="<?php echo base_url().'/js/jquery.fileupload.js';?>"></script>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="status"></div>
                        <div id="progresso"></div>
                    </div>
                </div>
                <div class="row" id="img_files"></div>
                <!--
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                        <div id="upload" class="btn btn-default btn-file ">
                            Selecione o arquivo 
                        </div>
                        <div class="status"></div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                        <div id="files"></div>
                    </div>
                </div> 
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label" for="titulo">Titulo</label>
                        <input name="titulo" type="text" id="titulo" class="form-control" value="<?php //echo set_value('titulo', '');?>" placeholder="Titulo">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label" for="descricao">Descrição</label>
                        <input name="descricao" type="text" id="descricao" class="form-control" value="<?php //echo set_value('descricao', '');?>" placeholder="Descricao">
                    </div>
                </div>
                <button class="btn enviar" type="submit">Salvar</button>-->
            </div> 
        </fieldset>
    </form>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="images">
                <?php 
//                if ( $images['qtde'] > 0 )
//                {
//                        echo '<ul class="thumbnails">';
//                        foreach ( $images['itens'] as $image )
//                        {
//                                ?>
<!--                                            <li class="col-lg-4">
                                                <div class="thumbnail">
                                                    <img src="//<?php 
//                                                    $url = str_replace('admin2_0/', '', $url);
//                                                    echo $url.(str_replace('[id]', $image->id_pai, $image->pasta)).$image->arquivo;?>" style="width:50px; height:50px;" >
                                                    <h3>//<?php //echo $image->titulo; ?></h3>
                                                    <p>Tipo de Imagem: //<?php //echo $image->descricao;?></p>
                                                    <button class="btn btn-alert deleta" id="//<?php //echo $image->id_image;?>">Deletar</button>
                                                </div>
                                            </li>-->
                                <?php  
//                        }
//                        echo '</ul>';
//                }
//                else 
//                {
//                        //echo 'Nenhuma Imagem Cadastrada!';
//                }
                ?>
            </div>
        </div>
    </div>
</div>