<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
            </div>
        </div>
    </div>
    <form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
        <div class="alert alert-info">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <div class="form-group id">
                        <label for="id">ID</label>
                        <input name="id" type="text" disabled="disabled" class="form-control id" id="id" placeholder="ID" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
                        <p class="help-block id"></p>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <div class="form-group titulo">
                        <label for="titulo">Titulo</label>
                        <input name="titulo" type="text" class="form-control titulo" id="titulo" placeholder="Titulo" required value="<?php echo set_value('titulo', isset($item->titulo ) ? $item->titulo : '');?>">
                        <p class="help-block titulo"></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 id_responsavel">
                    <label for="id_responsavel">Gerente do Projeto:</label>
                    <div class="controls ">
                        <?php 
                        $config['valor'] = $usuarios; 
                        $config['nome'] = 'id_responsavel'; 
                        $config['extra'] = 'id="id_responsavel"'; 
                        echo form_select($config, set_value('id_responsavel', isset($item->id_responsavel) ? $item->id_responsavel : 0 ) ); 
                         ?>
                    </div>
                    <p class="help-block id_responsavel">Selecione o usuário que é responsável por este Portfólio.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group descricao">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" class="form-control" id="descricao" placeholder="Descrição"><?php echo set_value('descricao', isset($item->descricao) ? $item->descricao : '');?></textarea>
                        <p class="help-block descricao"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-warning">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group demanda_semanal">
                        <label for="demanda_semanal">Demanda de horas semanais:</label>
                        <input name="demanda_semanal" type="text" class="form-control" id="demanda_semanal" placeholder="Demanada semanal" value="<?php echo set_value('demanda_semanal', isset($item->demanda_semanal) ? $item->demanda_semanal : '');?>">
                        <p class="help-block demanda_semanal"></p>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <?php 
                    $tem_data = ( ( isset($item->data_inicio) && ! empty($item->data_inicio) && isset($item->data_fim) && ! empty($item->data_fim)  ) ? TRUE : FALSE );
                    $classes_check = $tem_data ? 'col-lg-2 col-md-2 col-sm-2 col-xs-2 caixa_tem_data' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 caixa_tem_data';
                    ?>
                    <div class="row">
                        <div class="<?php echo $classes_check;?>">
                            <div class="form-group checkbox tem_data">
                                <label for="tem_data">
                                    <input type="checkbox" id="tem_data" <?php echo ( $tem_data ? 'checked="checked"' : '' );?> value="1"> Este Portfólio tem datas definidas?
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 caixa_data_inicio <?php echo ( $tem_data ? 'show' : 'hide' );?>">
                            <div class="form-group data_inicio">
                                <label for="data_inicio">Data Previsão Inicio</label>
                                <div>
                                    <input name="data_inicio" type="text" class="form-control data_hora_pt_br"  <?php echo ( $tem_data ? '' : 'disabled="disabled"' );?> id="data_inicio" placeholder="Previsão Inicio"  value="<?php echo set_value('data_inicio', isset($item->data_inicio ) ? $item->data_inicio : '');?>">
                                </div>
                                <p class="help-block data_inicio"></p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 caixa_data_fim <?php echo ( $tem_data ? 'show' : 'hide' );?>">
                            <div class="form-group data_fim">
                                <label for="data_fim">Data Previsão Fim</label>
                                <div >
                                    <input name="data_fim" type="text" class="form-control data_hora_pt_br" <?php echo ( $tem_data ? '' : 'disabled="disabled"' );?> id="data_fim" placeholder="Previsão Fim"  value="<?php echo set_value('data_fim', isset($item->data_fim ) ? $item->data_fim : '');?>">
                                </div>
                                <p class="help-block data_fim"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-lg-offset-10 col-sm-2  col-sm-offset-10 col-md-3  col-md-offset-9 col-xs-6  col-xs-offset-6">  
                    <button type="submit" class="btn btn-warning">Salvar</button>
                </div>
            </div>
        </div>
    </form>
 </div>
<!-- Desenvolvimento e aperfeiçoamento da ferramenta de Administração de sites, publicidade, Notícias e conteúdo em geral do Pow Internet.  -->