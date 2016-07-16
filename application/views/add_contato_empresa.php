<form role="form" method="post" action="<?php echo isset($action) ? $action : '#';?>" >
    <div class="form-group">
        <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
        <?php //$validacao = validation_errors(); echo ! empty( $validacao ) ? '<div class="alert alert-danger">'.$validacao.'</div>' : ''; ?>
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <div class="resultado-email"></div>
        <input type="hidden" name="id" id="id_empresa" class="form-control" value="<?php echo set_value('id', isset($item->id ) ? $item->id : '');?>">
    </div>
    <div class="row alert alert-info">
        <div>
            <div class="form-group">
                <label for="id_empresa">Id Empresa</label>
                <input for="id_empresa" type="number"><?php echo set_value('id_empresa', isset($id_empresa ) ? $id_empresa : '');?></input>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <label for="empresa_telefone">Telefone</label>
                <input name="empresa_telefone" type="text" class="form-control" id="empresa_telefone" placeholder="Telefone" value="<?php echo set_value('empresa_telefone', isset($item->empresa_telefone ) ? $item->empresa_telefone : '');?>">
            </div>  
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="empresa_razao_social">Razão Social</label>
                <input required name="empresa_razao_social" type="text" class="form-control" id="empresa_razao_social" placeholder="Razão Social" value="<?php echo set_value('empresa_razao_social', isset($item->empresa_razao_social ) ? $item->empresa_razao_social : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="empresa_nome_fantasia">Nome Fantasia</label>
                <input required name="empresa_nome_fantasia" type="text" class="form-control" id="empresa_nome_fantasia" placeholder="Nome Fantasia" value="<?php echo set_value('empresa_nome_fantasia', isset($item->empresa_nome_fantasia ) ? $item->empresa_nome_fantasia : '');?>">
            </div>
        </div>
        
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <label for="empresa_cep">CEP</label>
                <input name="empresa_cep" type="text" class="form-control cep" id="cep" placeholder="CEP" value="<?php echo set_value('empresa_cep', isset($item->cep ) ? $item->cep : '');?>">
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="empresa_endereco">Endereço</label>
                <input name="id_logradouro" class="id_logradouro" type="hidden" value="<?php echo set_value('id_logradouro', isset($item->id_logradouro ) ? $item->id_logradouro : '');?>">
                <input name="empresa_endereco" type="text" class="form-control endereco" id="endereco" placeholder="Endereço" value="<?php echo set_value('empresa_endereco', isset($item->endereco ) ? $item->endereco : '');?>">
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <label for="empresa_numero">Numero</label>
                <input name="empresa_numero" type="text" class="form-control numero" id="empresa_numero" placeholder="Numero" value="<?php echo set_value('empresa_numero', isset($item->empresa_numero ) ? $item->empresa_numero : '');?>">
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <label for="empresa_complemento">Complemento</label>
                <input name="empresa_complemento" type="text" class="form-control complemento" id="empresa_complemento" placeholder="Complemento" value="<?php echo set_value('empresa_complemento', isset($item->empresa_complemento ) ? $item->empresa_complemento : '');?>">
            </div>
        </div>
    
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <label for="empresa_bairro">Bairro</label>
                <input name="empresa_bairro" type="text" class="form-control bairro" id="bairro" placeholder="Bairro" value="<?php echo set_value('empresa_bairro', isset($item->bairro ) ? $item->bairro : '');?>">
            </div>
        </div>
        
        
        <div class="col-lg-12 col-md-12 help-endereco">
            
        </div>
        
        
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="empresa_email">Empresa e-mail</label>
                <input name="empresa_email" type="email" class="form-control empresa_email" id="empresa_email" placeholder="Empresa E-mail" value="<?php echo set_value('empresa_email', isset($item->empresa_email ) ? $item->empresa_email : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="empresa_dominio">Empresa dominio (www)</label>
                <input name="empresa_dominio" type="text" class="form-control empresa_dominio" id="empresa_dominio" placeholder="Empresa dominio" value="<?php echo set_value('empresa_dominio', isset($item->empresa_dominio ) ? $item->empresa_dominio : '');?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <?php
                        echo '<div class="controls">' ;
                        $config['valor'] = $cidades; 
                        $config['nome'] = 'cidades'; 
                        $config['extra'] = 'class="form-control"'; 
                        echo form_select($config, set_value('cidade', isset($item->cidade) ? $item->cidade : '')); 
                        echo    '</div>';
                ?>            
            </div>
        </div>
        
    </div>
</form>





