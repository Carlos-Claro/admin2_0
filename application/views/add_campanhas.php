<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
        <div class="form-group">
            <label for="id">ID</label>
            <input type="text" disabled="disabled" name="id" class="form-control" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
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
                <?php
                    $dt_inicio = NULL;
                    if(isset($item->data_inicio) && !empty($item->data_inicio))
                    {
                       $exp_a = explode('-',$item->data_inicio);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_inicio = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0].' '.$exp_b[1];
                    }
                ?>
                <div class="form-group">
                    <label for="data_inicio">Data de Inicio</label>
                    <input name="data_inicio" type="text" class="form-control" id="data_inicio" placeholder="Data de Inicio" value="<?php echo set_value('data_inicio', isset($dt_inicio) ? $dt_inicio : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?php
                    $dt_fim = NULL;
                    if(isset($item->data_fim) && !empty($item->data_fim))
                    {
                       $exp_a = explode('-',$item->data_fim);
                       $exp_b = explode(' ',$exp_a[2]);
                       $dt_fim = $exp_b[0].'/'.$exp_a[1].'/'.$exp_a[0].' '.$exp_b[1];
                    }
                ?>
                <div class="form-group">
                    <label for="data_fim">Data de Fim</label>
                    <input name="data_fim" type="text" class="form-control" id="data_fim" placeholder="Data de Fim" value="<?php echo set_value('data_fim', isset($dt_fim) ? $dt_fim : '');?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="meta">Meta</label>
                    <input name="meta" type="text" class="form-control" id="meta" placeholder="Meta" value="<?php echo set_value('meta', isset($item->meta) ? $item->meta : '');?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" placeholder="Descricao" ><?php echo set_value('descricao', isset($item->descricao) ? $item->descricao : '');?></textarea>
                </div>
            </div>
        </div>
        <?php if($function != 'editar') : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="em_automatico">E-mails Automáticos desta campanha</label>
                        <?php
                            $config['valor'] = $email_automatico;
                            $config['nome']  = 'email_automatico[]';
                            $config['extra'] = '';
                            echo form_checkbox_($config, set_value('emails_automatico[]', array()), 2);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="equipes">Equipes desta campanha</label>
                </div>
            </div>
            <div class="row">
                        <?php
                            $config['valor'] = $equipes;
                            $config['nome']  = 'equipes[]';
                            $config['extra'] = 'class="col-lg-2 col-md-2 col-sm-2 col-xs-12"';
                            echo form_checkbox_($config, set_value('equipes', array()), 2);
                        ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if($function != 'editar') : ?>
        <div class="alert alert-success">
            <fieldset>
                <legend>Empresas</legend>
                <div class="alert alert-info">
                    <div class="row">
                        <div id="empresas-selecionadas">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>Empresas Selecionadas</h4>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <button name="del_todos" id="del_todos" type="button" class="btn btn-info">Remover Todas</button>
                                </div>
                            </div>
                            <div id="lista-empresas-selecionadas"></div>
                        </div>
                        <div id="inputs-empresas-selecionadas"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-categoria"></div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-subcategoria">
                        <div class="form-group">
                            <label for="subcategorias">Subcategorias</label>
                            <select class="form-control" name="subcategorias" id="subcategorias"><option value="">Selecione...</option></select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-estado"></div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-cidade">
                        <div class="form-group">
                            <label for="cidades">Cidades</label>
                            <select class="form-control" name="cidades" id="cidades"><option value="">Selecione...</option></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-bairro">
                        <div class="form-group">
                            <label for="bairros">Bairros</label>
                            <select class="form-control" name="bairros" id="bairros"><option value="">Selecione...</option></select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-status"></div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-logradouro">
                        <div class="form-group">
                            <label for="logradouro">Logradouro</label>
                            <input type="text" class="form-control" name="logradouro" id="logradouro" value="">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 filtro-logradouro">
                        <div class="form-group">
                            <label for="nome_empresa">Nome da Empresa</label>
                            <input type="text" class="form-control" name="nome_empresa" id="nome_empresa" value="">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                        <br>
                        <button type="button" id="pesquisar-empresas" class="btn btn-primary">Pesquisar</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                        <br>
                        <button type="submit" class="btn btn-warning">Salvar</button>
                    </div>
                </div>
                <div class="row">
                    <div id="resultado-empresas"></div>
                </div>
            </fieldset>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-warning">Salvar</button>
</form>

