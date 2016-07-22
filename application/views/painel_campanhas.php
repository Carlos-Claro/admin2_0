<?php if($itens['qtde'] > 0 ): ?>
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <h4 class="text-primary">Essas são as campanhas abertas.</h4>
    <?php foreach ($itens['itens'] as $item): ?>
    <fieldset>
        <legend>
            <a href="<?php echo base_url().'campanhas/listar_empresas_campanha/'.$item->id; ?>">
                <?php echo $item->titulo; ?>
            </a>
            <span class="glyphicon glyphicon-plus pull-right campanha-plus" data-item="<?php echo $item->id; ?>"></span>
        </legend>
        <div class="row campanhas" id="campanhas-painel-<?php echo $item->id; ?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <small>Trabalhadas: <?php //echo $item->trabalhadas; ?></small>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <small>Não Trabalhadas: <?php //echo $item->nao_trabalhadas; ?></small>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <small>Quantidade de Interações: <?php echo $item->qtde_interacao; ?></small>
            </div>
        </div>
    </fieldset><br>
    <?php endforeach; ?>
</div>
<?php endif; ?>