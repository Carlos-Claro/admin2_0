<div class="login col-lg-5 col-lg-offset-3" style=" background-color: #AEAFBO; border: solid 2px #00648f; border-radius: 10px; margin-top: 20px; padding: 20px;" >
    <div cllass="brand text-center">
        <img src="<?php echo base_url();?>images/logo_pow.png">
    </div>
    <h1>Acesse o sistema Pow Internet</h1>
    <?php if ( isset($erro) ) : echo '<p class="'.$erro['class'].'">'.$erro['texto'].'</p>'; endif; ?>
    <form class="form-horizontal" role="form" method="post" action="<?php echo isset($action) ? $action : '#' ?>">
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">Email</label>
            <div class="col-lg-10">
                <!--<input type="email" class="form-control" id="email" placeholder="Email" required name="email">-->
                <input type="text" class="form-control" id="email" placeholder="Login" required name="email">
            </div>
        </div> 
        <div class="form-group">
            <label for="senha" class="col-lg-2 control-label">Senha</label>
            <div class="col-lg-10">
                <input type="password" class="form-control" id="password" placeholder="Senha" required name="senha">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-primary">Login</button>
                <a data-toggle="modal" href="#esqueciModal" class="btn btn-default">Esqueceu sua Senha?</a>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="esqueciModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Recuperar Senha!</h4>
            </div>
            <div class="modal-body">
                <label class="control-label" for="mail">Digite seu E-mail</label>
                <div class="input-group">
                    <input type="email" id="mail" class="form-control">
                    <span class="input-group-addon btn btn-warning verificar">Verificar</span>
                </div>
                <div class="verificado"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->