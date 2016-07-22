<fieldset class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <legend>Adicionar Ocorrência
        <span class="pull-right glyphicon glyphicon-plus" id="plus-oc"></span>
    </legend>
    <div class="ocorrencia">
        <div class="error-oc">
            <div class="alert alert-danger"></div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="texto-oc">Descrição</label>
                <textarea class="form-control texto-oc" rows="4" name="texto" id="texto-oc"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group assunto-oc"></div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="prioridade-oc">Prioridade</label>
                    <select name="prioridade-oc" id="prioridade-oc" class="form-control">
                        <?php for($i=1; $i <= 9; $i++): ?>
                            <option value="<?php echo $i; ?>"> <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group setor-oc" data-tipo="oc">
                    <label>Setor Pow</label>
                    <?php 
                    $config['valor'] = $setores; 
                    $config['nome'] = 'setores-oc'; 
                    $config['extra'] = ''; 
                    $config['class'] = 'setores-oc'; 
                    echo form_select($config, set_value('setores-oc', '') ); 
                    ?>
                    
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group usuario-setor-oc">
                    <label>Usuario Pow</label>
                    <?php 
                    $config['valor'] = $usuarios; 
                    $config['nome'] = 'usuario-setor-oc'; 
                    $config['extra'] = ''; 
                    $config['class'] = 'usuario-setor-oc'; 
                    echo form_select($config, set_value('usuario-setor-oc', '') ); 
                    ?>
                    
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group ">
                    <label for="id-contato-oc">Contatos da Empresa</label>
                    <button type="button" class="btn btn-default btn-block" name="id-contato-oc" id="id-contato-oc" data-item="" data-toggle="modal" data-target="#modal-oc" >Selecionar</button>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group status-oc"></div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="data-retorno-inicio-oc">Data de Retorno Inicio</label>
                <div class="input-group date" id="datetimepickerInicioOc">
                    <input type="text" class="form-control" data-date-format="DD/MM/YYYY HH:mm" name="data-retorno-inicio-oc" id="data-retorno-inicio-oc" value="<?php echo date('d/m/Y H:i'); ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <label for="data-retorno-fim-oc">Data de Retorno Fim</label>
                <div class="input-group date" id="datetimepickerFimOc">
                    <input type="text" class="form-control"  data-date-format="DD/MM/YYYY HH:mm" name="data-retorno-fim-oc" id="data-retorno-fim-oc" value="<?php echo date('d/m/Y H:i'); ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="form-group">
                    <button id="btn-ocorrencia" class="btn btn-primary">Salvar Ocorrência</button>
                </div>
            </div>
        </div>
    <div>
</fieldset>

<div class="modal fade" id="modal-oc">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 contato-empresa-oc"></div><br>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="add-oc">Adicionar Novo</button>
                    <button type="button" class="btn btn-success" id="editar-contato" data-sufix="oc">Editar</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-contato-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row alert alert-danger error-add-contato">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 error-contato"></div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="nome_contato">Nome</label>
                        <input type="text" name="nome_contato" id="nome_contato" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="email_contato">Email</label>
                        <input type="email" name="email_contato" id="email_contato" class="form-control"><br>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="telefone_contato">Telefone</label>
                        <input type="text" name="telefone_contato" id="telefone_contato" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="funcao_contato_oc">Função</label>
                        <input type="text" name="funcao_contato" id="funcao_contato" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <div class="checkbox">
                            <label for="principal_contato">Contato Principal</label>
                            <input type="checkbox" name="principal_contato" id="principal_contato" value="1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-primary" id="mais-campos" data-sufix="oc">Mais campos</button>
                    </div>
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="novos-campos"></div>
                    <br>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-default close-co" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="save-contato" data-sufix="oc">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div> 

<!--<div class="modal fade" id="modal-oc-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row alert alert-danger error-add-contato-oc">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 error-contato-oc"></div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="nome_contato_oc">Nome</label>
                        <input type="text" name="nome_contato_oc" id="nome_contato_oc" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="email_contato_oc">Email</label>
                        <input type="email" name="email_contato_oc" id="email_contato_oc" class="form-control"><br>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="telefone_contato_oc">Telefone</label>
                        <input type="text" name="telefone_contato_oc" id="telefone_contato_oc" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <label for="funcao_contato_oc">Função</label>
                        <input type="text" name="funcao_contato_oc" id="funcao_contato_oc" class="form-control">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <div class="checkbox">
                            <label for="principal_contato_oc">Contato Principal</label>
                            <input type="checkbox" name="principal_contato_oc" id="principal_contato_oc" value="1">
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-default close-co" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="save-contato" data-sufix="oc">Salvar</button>
                    </div>
                </div>
            </div>
        </div> /.modal-content 
    </div> /.modal-dialog 
</div> /.modal -->