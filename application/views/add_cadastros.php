<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    </div>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" disabled="disabled" name="id" class="form-control id" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
            </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email', isset($item->email) ? $item->email : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input name="nome" type="text" class="form-control titulo" id="nome" placeholder="Nome" required value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>">
                </div>
            </div>   
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-6"> 
                <label>Sexo</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="radio" name="sexo" class="masculino" id="sexo" value="M" <?php echo ((isset($item->sexo) && $item->sexo == 'M') ? 'checked' : '');?> >
                    </span>
                    <input type="text" class="form-control" value="Masculino">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-6">
                <label>Sexo</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="radio" name="sexo" class="feminimo" id="sexo" value="F" <?php echo ((isset($item->sexo) && $item->sexo == 'F') ? 'checked' : '');?>>
                    </span>
                    <input type="text" class="form-control" value="Feminino">
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="id_canal">Canal</label>
                    <div class="controls">
                        <?php 
                        $config['valor'] = $canal; 
                        $config['nome']  = 'id_canal'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('id_canal', isset($selecionado) ? $selecionado : '')); 
                        ?>
                    </div>
                </div>	
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="nascimento">Data de Nascimento</label>
                    <input name="nascimento" type="date" class="form-control" id="nascimento" placeholder="Nascimento" value="<?php echo set_value('nascimento', isset($item->nascimento) ? $item->nascimento : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="fone">Telefone</label>
                    <input name="fone" type="text" class="form-control" id="fone" placeholder="Telefone" value="<?php echo set_value('fone', isset($item->fone) ? $item->fone : '');?>">
                </div>
            </div>
           <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cep">Cep</label>
                    <input name="cep" type="text" class="form-control" id="cep" placeholder="Cep" value="<?php echo set_value('cep', isset($item->cep) ? $item->cep : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input name="endereco" type="text" class="form-control" id="endereco" placeholder="Endereço" value="<?php echo set_value('endereco', isset($item->endereco) ? $item->endereco : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input name="complemento" type="text" class="form-control" id="complemento" placeholder="Complemento" value="<?php echo set_value('complemento', isset($item->complemento) ? $item->complemento : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input name="cidade" type="text" class="form-control" id="cidade" placeholder="Cidade" value="<?php echo set_value('cidade', isset($item->cidade) ? $item->cidade : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input name="estado" type="text" class="form-control" id="estado" placeholder="Estado" value="<?php echo set_value('estado', isset($item->estado) ? $item->estado : '');?>">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input name="bairro" type="text" class="form-control" id="bairro" placeholder="Bairro" value="<?php echo set_value('bairro', isset($item->bairro) ? $item->bairro : '');?>">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-warning">Salvar</button>
    <?php if ( isset($mostra_id) && $mostra_id ) : ?>
       <a href="<?php echo $action_novo; ?>" class="btn btn-primary">Adicionar Novo</a>
    <?php endif; ?>
</form>

