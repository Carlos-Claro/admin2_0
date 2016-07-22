<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<style>
    #fixo { height: 100%; padding: 10px 25px;}
    #fixo h3 { text-align: center; margin: 0;}
    .preview{ width: 100%; height: 100%; border: 1px solid #aaaaaa; padding:15px 20px;}
    .atributos{padding: 0px;}
    .atributos h4{padding: 5px; cursor: pointer;}
    .elementos{display: none;}
</style>
<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="canal">Canal</label>
                    <input name="canal" disabled="disabled" type="text" class="form-control" id="canal" value="<?php echo set_value('canal', isset($canal->titulo) ? $canal->titulo : '');?>" >
                    <input name="id_canais" type="hidden" class="form-control" id="id_canais" value="<?php echo set_value('id_canais', isset($canal->id) ? $canal->id : '');?>" >
                </div>
            </div>  
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="camada">Camada</label>
                </div>
                <label class="checkbox-inline">
                    <input name="camada" type="checkbox" checked="checked" value="a" class="camada">A
                </label>
                <label class="checkbox-inline">
                    <input name="camada" type="checkbox" value="b" class="camada">B
                </label>
                <label class="checkbox-inline">
                    <input name="camada" type="checkbox" value="c" class="camada">C
                </label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <br>
                <a href="<?php echo $action_adicionar; ?>" class="btn btn-info">Adicionar Novo</a>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="elementos">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="qtde">Quantidade</label>
                            <input name="qtde" type="text" class="form-control submit-form" id="qtde" placeholder="Quantidade" value="" data-item="">
                        </div>
                    </div>  
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="ordem">Ordem</label>
                            <input name="ordem" type="text" class="form-control submit-form" id="ordem" placeholder="Ordem" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="n_coluna_lg_sm">Nº de Colunas em Telas Grandes</label>
                            <input name="n_coluna_lg_sm" type="text" class="form-control submit-form" id="n_coluna_lg_sm" placeholder="Nº de Colunas em Telas Grandes" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="n_coluna_md">Nº de Colunas em Telas Médias</label>
                            <input name="n_coluna_md" type="text" class="form-control submit-form" id="n_coluna_md" placeholder="Nº de Colunas em Telas Médias" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="n_coluna_xs">Nº de Colunas em Telas Pequenas</label>
                            <input name="n_coluna_xs" type="text" class="form-control submit-form" id="n_coluna_xs" placeholder="Nº de Colunas em Telas Pequenas" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <div class="form-group">
                             <label for="tipo_ordem">Tipo de Ordem</label>
                             <input name="tipo_ordem" type="text" class="form-control submit-form" id="tipo_ordem" placeholder="Tipo de Ordem" value="" data-item="">
                         </div>
                     </div> 
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="campo_ordem">Campo de Ordem</label>
                            <input name="campo_ordem" type="text" class="form-control submit-form" id="campo_ordem" placeholder="Campo de Ordem" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input name="titulo" type="text" class="form-control submit-form" id="titulo" placeholder="Titulo" required value="" data-item="">
                        </div>
                    </div>  
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="qtde_caracteres_descricao">Nº de Caracteres para Descrição</label>
                            <input name="qtde_caracteres_descricao" type="text" class="form-control submit-form" id="qtde_caracteres_descricao" placeholder="Nº de caracteres para descrição" value="" data-item="">
                        </div>
                    </div> 
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="qtde_colunas">Nº de Colunas</label>
                            <input name="qtde_colunas" type="text" class="form-control submit-form" id="qtde_colunas" placeholder="Quantidade de colunas" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <div class="form-group">
                             <label for="classe">Classe</label>
                             <input name="classe" type="text" class="form-control submit-form" id="classe" placeholder="Classe" value="" data-item="">
                         </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="classe_master">Classe Master</label>
                            <input name="classe_master" type="text" class="form-control submit-form" id="classe_master" placeholder="Classe Master" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="posicao_image">Posição da Imagem</label>
                            <input name="posicao_image" type="text" class="form-control submit-form" id="posicao_image" placeholder="Posição da Imagem" value="" data-item="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="checkbox">
                           <label>
                               <input name="link_mais" class="submit-form" type="checkbox" id="link_mais" value='1' data-item="">
                               Link Mais
                           </label>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <div class="checkbox">
                            <label>
                                <input name="titulo_exibe" class="submit-form" type="checkbox" id="titulo_exibe" value='1' data-item="">
                                Exibir Titulo
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <div class="checkbox">
                            <label>
                                <input name="mostra_estrela" class="submit-form" type="checkbox" id="mostra_estrela" value='1' data-item="">
                                Mostrar Estrela
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="button" id="btn-submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="fixo">
                <div class="preview">
                </div>
            </div>
        </div>
    </div>
</form>

