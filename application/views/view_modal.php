<div class="modal fade" data-item="<?php echo $item->id; ?>" id="modal-ca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 error-ca"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 class="text-center" id="texto-campanha" data-item="<?php echo $item->titulo; ?>">Campanha : <?php echo $item->titulo; ?> </h3>
                    <div id="qtde-empresas"></div>
                    <br>
                    <div class="row">
                        <div id="setores-campanha">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 setor-ca"></div>
                        </div>
                        <div id="usuarios-campanha">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 usuario-setor-ca"></div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="save-empresas-campanha">Salvar</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>