<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <div class="resultado-email"></div>
        <input type="hidden" name="id" id="id_empresa" class="form-control" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
    </div>
    <div class="alert alert-info">
        <div class="row">
            <fieldset class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <legend>Dados do Cliente : <?php echo set_value('empresa_razao_social', isset($item->empresa_razao_social ) ? $item->empresa_razao_social : '');?>
                    <span class="pull-right glyphicon glyphicon-plus" id="plus-dt-cl"></span>
                </legend>
                <div class="dt-cl">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input name="conhece_guia" value="1" type="checkbox" <?php echo (isset($item->conhece_guia) && $item->conhece_guia == 1) ? 'checked="checked"' : ''?>> <br><strong>conhecia?</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_telefone">Telefone</label>
                            <input name="empresa_telefone" type="text" class="form-control" id="empresa_telefone" placeholder="Telefone" value="<?php echo set_value('empresa_telefone', isset($item->empresa_telefone ) ? $item->empresa_telefone : '');?>">
                        </div>  
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_razao_social">Razão Social</label>
                            <input required name="empresa_razao_social" type="text" class="form-control" id="empresa_razao_social" placeholder="Razão Social" value="<?php echo set_value('empresa_razao_social', isset($item->empresa_razao_social ) ? $item->empresa_razao_social : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_nome_fantasia">Nome Fantasia</label>
                            <input required name="empresa_nome_fantasia" type="text" class="form-control" id="empresa_nome_fantasia" placeholder="Nome Fantasia" value="<?php echo set_value('empresa_nome_fantasia', isset($item->empresa_nome_fantasia ) ? $item->empresa_nome_fantasia : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_cep">CEP</label>
                            <input name="empresa_cep" type="text" class="form-control cep" id="cep" placeholder="CEP" value="<?php echo set_value('empresa_cep', isset($item->cep ) ? $item->cep : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_endereco">Endereço</label>
                            <input name="id_logradouro" class="id_logradouro" type="hidden" value="<?php echo set_value('id_logradouro', isset($item->id_logradouro ) ? $item->id_logradouro : '');?>">
                            <input name="empresa_endereco" type="text" class="form-control endereco" id="endereco" placeholder="Endereço" value="<?php echo set_value('empresa_endereco', isset($item->endereco ) ? $item->endereco : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_numero">Numero</label>
                            <input name="empresa_numero" type="text" class="form-control numero" id="empresa_numero" placeholder="Numero" value="<?php echo set_value('empresa_numero', isset($item->empresa_numero ) ? $item->empresa_numero : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_complemento">Complemento</label>
                            <input name="empresa_complemento" type="text" class="form-control complemento" id="empresa_complemento" placeholder="Complemento" value="<?php echo set_value('empresa_complemento', isset($item->empresa_complemento ) ? $item->empresa_complemento : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_bairro">Bairro</label>
                            <input name="empresa_bairro" type="text" class="form-control bairro" id="bairro" placeholder="Bairro" value="<?php echo set_value('empresa_bairro', isset($item->bairro ) ? $item->bairro : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 help-endereco"></div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_email">Empresa e-mail</label>
                            <input name="empresa_email" type="email" class="form-control empresa_email" id="empresa_email" placeholder="Empresa E-mail" value="<?php echo set_value('empresa_email', isset($item->empresa_email ) ? $item->empresa_email : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_dominio">Empresa dominio (www)</label>
                            <input name="empresa_dominio" type="text" class="form-control empresa_dominio" id="empresa_dominio" placeholder="Empresa dominio" value="<?php echo set_value('empresa_dominio', isset($item->empresa_dominio ) ? $item->empresa_dominio : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <div class="controls">
                            <?php
                                $config['valor'] = $cidades; 
                                $config['nome'] = 'cidades'; 
                                $config['extra'] = 'class="form-control"'; 
                                echo form_select($config, set_value('cidade', (isset($logradouro['itens'][0]->id_cidade) && $logradouro['itens'][0]->id_cidade) ? $logradouro['itens'][0]->id_cidade : '')); 
                            ?>    
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="empresa_cnpj">CNPJ</label>
                            <input name="empresa_cnpj" type="text" class="form-control" id="empresa_cnpj" placeholder="CNPJ" value="<?php echo set_value('empresa_cnpj', isset($item->empresa_cnpj ) ? $item->empresa_cnpj : '');?>">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="boletos_usuario">Login para Boleto</label>
                            <input name="boletos_usuario" type="text" class="form-control" id="boletos_usuario" placeholder="Login Boleto" value="<?php //echo set_value('boletos_usuario', (isset($item->boletos_usuario ) &&  $item->boletos_usuario) ? $item->boletos_usuario : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="boletos_senha">Senha para Boleto</label>
                            <input name="boletos_senha" type="text" class="form-control" id="boletos_senha" placeholder="Senha Boleto" value="<?php //echo set_value('boletos_senha', (isset($item->boletos_senha ) && $item->boletos_senha) ? $item->boletos_senha : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="autorizador_nome">Autorizado nome</label>
                            <input name="autorizador_nome" type="text" class="form-control" id="autorizador_nome" placeholder="Autorizador nome" value="<?php echo set_value('autorizador_nome', isset($item->autorizador_nome ) ? $item->autorizador_nome : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="autorizador_email">Autorizado email</label>
                            <input name="autorizador_email" type="text" class="form-control" id="autorizador_email" placeholder="Autorizador email" value="<?php echo set_value('autorizador_email', isset($item->autorizador_email ) ? $item->autorizador_email : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="autorizador_ddd">Autorizado DDD</label>
                            <input name="autorizador_ddd" type="text" class="form-control" id="autorizador_ddd" placeholder="DDD" value="<?php echo set_value('autorizador_ddd', isset($item->autorizador_ddd ) ? $item->autorizador_ddd : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="autorizador_telefone">Autorizado Telefone</label>
                            <input name="autorizador_telefone" type="text" class="form-control" id="autorizador_telefone" placeholder="Autorizador telefone" value="<?php echo set_value('autorizador_telefone', isset($item->autorizador_telefone ) ? $item->autorizador_telefone : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="contato_nome">Contato nome</label>
                            <input name="contato_nome" type="text" class="form-control contato_nome" id="contato_nome" placeholder="Contato nome" value="<?php echo set_value('contato_nome', isset($item->contato_nome ) ? $item->contato_nome : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="contato_email">Contato e-mail</label>
                            <input name="contato_email" type="email" class="form-control contato_email" id="contato_email" placeholder="Contato e-mail" value="<?php echo set_value('contato_email', isset($item->contato_email ) ? $item->contato_email : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-6">
                        <div class="form-group">
                            <label for="contato_ddd">Contato DDD</label>
                            <input name="contato_ddd" type="text" class="form-control contato_ddd" id="contato_ddd" placeholder="Contato DDD" value="<?php echo set_value('contato_ddd', isset($item->contato_ddd ) ? $item->contato_ddd : '');?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-6">
                        <div class="form-group">
                            <label for="contato_telefone">Contato Telefone</label>
                            <input name="contato_telefone" type="text" class="form-control contato_telefone" id="contato_telefone" placeholder="Contato Telefone" value="<?php echo set_value('contato_telefone', isset($item->contato_telefone ) ? $item->contato_telefone : '');?>">
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <?php if($this->session->userdata['id'] == '14'): ?>
        <div class="row alert alert-danger">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="id_subcategoria">Categoria de Serviço</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $subcategorias; 
                        $config['nome'] = 'id_subcategoria'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_subcategoria', isset($item->id_subcategoria) ? $item->id_subcategoria : '')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="status_atualizada">Status</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $status_atualizada; 
                        $config['nome'] = 'status_atualizada'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('status_atualizada', isset($item->status_atualizada) ? $item->status_atualizada : '')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="empresa_descricao">Empresa Descrição</label>
                    <textarea name="empresa_descricao" id="empresa_descricao" class="descricao form-control"><?php echo set_value('empresa_descricao', isset($item->empresa_descricao ) ? $item->empresa_descricao : '');?></textarea>
                    <p class="help-block help-qtde-caracteres">Maximo de caracteres: <span class="qtde-caracteres text-success">0</span>/60 <span class="mensagem-erro"></span></p>
                </div>
            </div>
            <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                <button type="submit" class="btn btn-danger btn-block">Salvar</button>
            </div>
            <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                <button type="button" class="btn btn-info btn-block enviar-mail">Enviar E-mail</button>    
            </div>
        </div>
    <?php endif; ?>
</form>
<?php  if(isset($ocorrencias) && $ocorrencias): ?>
<div class="alert alert-warning">
    <div class="row"><?php echo $ocorrencias; ?></div>
</div>
<?php  endif; ?>
<?php echo (isset($interacoes) && $interacoes) ? $interacoes : ''; ?>