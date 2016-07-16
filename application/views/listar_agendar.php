<?php if ( isset($filtro) ) : echo $filtro; endif; ?>
<div class="">
<div class="paginacao"><?php if (isset($paginacao)) : echo $paginacao; endif; ?></div>
<?php echo $listagem;?>
<div class="paginacao"><?php if (isset($paginacao)) : echo $paginacao; endif; ?></div>
</div>