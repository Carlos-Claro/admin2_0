<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <input type="hidden" name="id" id="id_empresa" class="form-control" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
    </div>
    <h2>Dados do Cliente : <?php echo set_value('empresa_razao_social', isset($item->empresa_razao_social ) ? $item->empresa_razao_social : '');?></h2>
    <div class="alert alert-info">
        <fieldset>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="inscricao">Inscrição</label>
                        <input disabled="disabled" name="inscricao" type="text" class="form-control" id="inscricao" placeholder="Inscrição" value="<?php echo set_value('inscricao', isset($item->inscricao ) ? $item->inscricao : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="contato_nome">Contato nome</label>
                        <input name="contato_nome" type="text" class="form-control contato_nome" id="contato_nome" placeholder="Contato nome" value="<?php echo set_value('contato_nome', isset($item->contato_nome ) ? $item->contato_nome : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="contato_email">Contato e-mail</label>
                        <input name="contato_email" type="email" class="form-control contato_email" id="contato_email" placeholder="Contato e-mail" value="<?php echo set_value('contato_email', isset($item->contato_email ) ? $item->contato_email : '');?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="contato_ddd">Contato DDD</label>
                        <input name="contato_ddd" type="text" class="form-control contato_ddd" id="contato_ddd" placeholder="Contato DDD" value="<?php echo set_value('contato_ddd', isset($item->contato_ddd ) ? $item->contato_ddd : '');?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="contato_telefone">Contato Telefone</label>
                        <input name="contato_telefone" type="text" class="form-control contato_telefone" id="contato_telefone" placeholder="Contato Telefone" value="<?php echo set_value('contato_telefone', isset($item->contato_telefone ) ? $item->contato_telefone : '');?>">
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    
    <div class="alert alert-info">
        <fieldset>    
            <div class="row">
                <legend class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Dados do Autorizador</legend>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_nome">Nome</label>
                        <input name="autorizador_nome" disabled="disabled" type="text" class="form-control" id="autorizador_nome" placeholder="Autorizador nome" value="<?php echo set_value('autorizador_nome', isset($item->autorizador_nome ) ? $item->autorizador_nome : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_cpf">CPF</label>
                        <input name="autorizador_cpf" disabled="disabled" type="text" class="form-control" id="autorizador_cpf" placeholder="CPF" value="<?php echo set_value('autorizador_cpf', isset($item->autorizador_cpf ) ? $item->autorizador_cpf : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <?php
                    $data_nascimento = '';
                    if(isset($item->autorizador_nascimento) && $item->autorizador_nascimento)
                    {
                        $exp_a = explode('-', $item->autorizador_nascimento);
                        $data_nascimento = $exp_a[2].'/'.$exp_a[1].'/'.$exp_a[0];
                    }
                    ?>
                    <div class="form-group">
                        <label for="autorizador_nascimento">Data de Nascimento</label>
                        <input name="autorizador_nascimento" disabled="disabled" type="text" class="form-control" id="autorizador_nascimento" placeholder="Data de nascimento" value="<?php echo set_value('autorizador_nascimento', $data_nascimento);?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_cargo">Cargo</label>
                        <input name="autorizador_cargo" type="text" class="form-control" id="autorizador_cargo" placeholder="Autorizador Cargo" value="<?php echo set_value('autorizador_cargo', isset($item->autorizador_cargo ) ? $item->autorizador_cargo : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_email">E-mail</label>
                        <input name="autorizador_email" type="text" class="form-control" id="autorizador_email" placeholder="Autorizador email" value="<?php echo set_value('autorizador_email', isset($item->autorizador_email ) ? $item->autorizador_email : '');?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_ddd">DDD</label>
                        <input name="autorizador_ddd" type="text" class="form-control" id="autorizador_ddd" placeholder="DDD" value="<?php echo set_value('autorizador_ddd', isset($item->autorizador_ddd ) ? $item->autorizador_ddd : '');?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="autorizador_telefone">Telefone</label>
                        <input name="autorizador_telefone" type="text" class="form-control" id="autorizador_telefone" placeholder="Autorizador telefone" value="<?php echo set_value('autorizador_telefone', isset($item->autorizador_telefone ) ? $item->autorizador_telefone : '');?>">
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    
    <div class="alert alert-info">
        <fieldset>
            <div class="row">
                <legend class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Dados da Empresa</legend>
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
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="pagina_nome_inicial">Nome Inicial</label>
                        <input required name="pagina_nome_inicial" type="text" class="form-control" id="pagina_nome_inicial" placeholder="Nome Inicial" value="<?php echo set_value('pagina_nome_inicial', isset($item->pagina_nome_inicial ) ? $item->pagina_nome_inicial : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="pagina_creci">Creci</label>
                        <input name="pagina_creci" type="text" class="form-control" id="pagina_creci" placeholder="Página Creci" value="<?php echo set_value('pagina_creci', isset($item->pagina_creci ) ? $item->pagina_creci : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_endereco">Endereço</label>
                        <input name="id_logradouro" disabled="disabled" class="id_logradouro" type="hidden" value="<?php echo set_value('id_logradouro', isset($item->id_logradouro ) ? $item->id_logradouro : '');?>">
                        <input name="empresa_endereco" disabled="disabled" type="text" class="form-control endereco" id="empresa_endereco" placeholder="Endereço" value="<?php echo set_value('empresa_endereco', isset($item->endereco ) ? $item->endereco : '');?>">
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
                        <input name="empresa_bairro" disabled="disabled" type="text" class="form-control bairro" id="empresa_bairro" placeholder="Bairro" value="<?php echo set_value('empresa_bairro', isset($item->bairro ) ? $item->bairro : '');?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input name="cidade" disabled="disabled" type="text" class="form-control" id="cidade" placeholder="Cidade" value="<?php echo set_value('cidade', '');?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_telefone">Telefone: (41)</label>
                        <input name="empresa_telefone" type="text" class="form-control" id="empresa_telefone" placeholder="Telefone" value="<?php echo set_value('empresa_telefone', isset($item->empresa_telefone ) ? $item->empresa_telefone : '');?>">
                    </div>  
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_fone_sms">Celular p/ SMS</label>
                        <input name="empresa_fone_sms" type="text" class="form-control" id="empresa_fone_sms" placeholder="SMS" value="<?php echo set_value('empresa_fone_sms', isset($item->empresa_fone_sms ) ? $item->empresa_fone_sms : '');?>">
                    </div>  
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="pagina_fax">FAX</label>
                        <input name="pagina_fax" type="text" class="form-control" id="pagina_fax" placeholder="FAX" value="<?php echo set_value('pagina_fax', isset($item->pagina_fax ) ? $item->pagina_fax : '');?>">
                    </div>  
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_cep">CEP</label>
                        <input name="empresa_cep" disabled="disabled" type="text" class="form-control cep" id="empresa_cep" placeholder="CEP" value="<?php echo set_value('empresa_cep', isset($item->cep ) ? $item->cep : '');?>">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 help-endereco"></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_cnpj">CNPJ</label>
                        <input name="empresa_cnpj" disabled="disabled" type="text" class="form-control" id="empresa_cnpj" placeholder="CNPJ" value="<?php echo set_value('empresa_cnpj', isset($item->empresa_cnpj ) ? $item->empresa_cnpj : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_email">Empresa e-mail</label>
                        <input name="empresa_email" type="email" class="form-control empresa_email" id="empresa_email" placeholder="Empresa E-mail" value="<?php echo set_value('empresa_email', isset($item->empresa_email ) ? $item->empresa_email : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_emaillocacao">E-mail Locação</label>
                        <input name="empresa_emaillocacao" type="email" class="form-control" id="empresa_emaillocacao" placeholder="E-mail Locação" value="<?php echo set_value('empresa_emaillocacao', isset($item->empresa_emaillocacao ) ? $item->empresa_emaillocacao : '');?>">
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="id_subcategoria">Setor:</label>
                        <input name="id_subcategoria" disabled="disabled" type="text" class="form-control" id="id_subcategoria" placeholder="Subcategoria" value="<?php echo set_value('id_subcategoria', isset($item->id_subcategoria ) ? $item->id_subcategoria : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_dominio">Empresa dominio (www)</label>
                        <input name="empresa_dominio" type="text" class="form-control empresa_dominio" id="empresa_dominio" placeholder="Empresa dominio" value="<?php echo set_value('empresa_dominio', isset($item->empresa_dominio ) ? $item->empresa_dominio : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="titulo_site">Titulo p/ o Site</label>
                        <input name="titulo_site" type="text" class="form-control" id="titulo_site" placeholder="Titulo p/ o site" value="<?php echo set_value('titulo_site', isset($item->titulo_site ) ? $item->titulo_site : '');?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_funcionarios">Nº de Funcionários</label>
                        <input name="empresa_funcionarios" type="text" class="form-control" id="empresa_funcionarios" placeholder="Nº de Funcionários" value="<?php echo set_value('empresa_funcionarios', isset($item->empresa_funcionarios ) ? $item->empresa_funcionarios : '');?>">
                    </div>
                </div>
            </div>
               
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="empresa_descricao">Empresa Descrição</label>
                        <textarea name="empresa_descricao" id="empresa_descricao" class="descricao form-control"><?php echo set_value('empresa_descricao', isset($item->empresa_descricao ) ? $item->empresa_descricao : '');?></textarea>
                        <p class="help-block help-qtde-caracteres">Maximo de caracteres: <span class="qtde-caracteres text-success">0</span>/60 <span class="mensagem-erro"></span></p>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="palavraschave">Palavras Chave:</label>
                        <textarea name="palavraschave" id="palavraschave" class="form-control"><?php echo set_value('palavraschave', isset($item->palavraschave ) ? $item->palavraschave : '');?></textarea>
                    </div>
                </div>
            </div>
            <div class="checkbox-inline">
                <label>
                    <input type="checkbox" disabled="disabled" name="aciap" id="aciap" value="1" <?php echo set_value('aciap', isset($item->aciap ) ? 'checked="checked"' : '');?> >Membro ACIAP
                </label>
            </div>
        </fieldset>
    </div>
    
    <div class="alert alert-info">
        <fieldset>
            <legend>Upload de Imagens</legend>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="familia" value="<?php echo $familia;?>">
                    <input type="hidden" name="id_pai" id="id_pai" value="<?php echo (isset($item->id) ? $item->id : $this->sessao['id']) ;?>">
                    <input type="hidden" name="lixo" id="lixo" value="<?php echo (isset($item->id) ? 'n' : 's') ;?>">
                    <div class="form-group">
                        <label for="image_tipo">Tipo de Imagem</label>
                        <div class="controls">
                            <?php 
                            $config['valor'] = $imagens; 
                            $config['nome'] = 'image_tipo'; 
                            $config['extra'] = 'class="form-control"'; 
                            echo form_select($config, set_value('image_tipo',set_value('image_tipo', array()))); 
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="upload-form">
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                                <span>Selecionar Arquivos...</span>
                            <input id="fileupload" type="file" name="files[]" data-url="<?php echo $data_url; ?>" multiple>
                        </span>
                        <script src="<?php echo base_url().'js/vendor/jquery.ui.widget.js';?>"></script>
                        <script src="<?php echo base_url().'js/jquery.iframe-transport.js';?>"></script>
                        <script src="<?php echo base_url().'js/jquery.fileupload.js';?>"></script>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="status"></div>
                                <div id="progress"></div>
                            </div>
                        </div>
                        <div class="row" id="images_temp"></div>
                        <div class="row" id="img_files"></div>
                    </div> 
                </div>
            </div>
        </fieldset>
    </div>
    
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>